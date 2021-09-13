<?php

class Warden
{
	// This determines mheader access.  It is private and called by an accessor function below.
	private static $mgmtmode = "No";

	private static function setMGMT($yesno)
	{
		self::$mgmtmode = $yesno;
	}

	public static function getMGMT()
	{
		return self::$mgmtmode;
	} 

	public static function Examine()
	{
		// Call two different functions, one does special checks if the page requested is SSL.  The normal one does different checks.
		if ($_SERVER['HTTPS'] == "on")
		{
			self::deep_checker($_GET['q'], $_GET['m'], 'http://homeplaneta.com');
		}
		else
		{
			self::basic_checker();
		}
	}

	public static function deep_checker($translatedid, $managementid, $redirect)
	// This function does deep security checks to make sure m and q are what they should be
	{
		// First, check if both translatedid and managementid have been set.  If not, redirect.
		if ((!isset($translatedid) || (!isset($managementid))))
		{
			header ("Location: $redirect");
		}

		// Then, preg check managementid's format.  If not valid, redirect.
		if (!preg_match("/^[a-z0-9]{6}$/", $managementid)) 
		{
			header ("Location: $redirect");
		}

		// Third, simple check translatedid's length and numeric status.  If not valid, redirect.
		if ((strlen($translatedid) > 9) || (!is_numeric($translatedid)))
		{
			header ("Location: $redirect");
		}

		// Fourth, connect to DB and validate that q and m are legit and go together.  If not, redirect.
		require 'pdoconfig.php';
		$query = "SELECT ManagementID FROM ListingInfo WHERE TranslatedID = :translatedID";
		$resource = $connection->prepare($query);
		$resource->bindParam(':translatedID', $translatedid);
		$resource->execute();

		try
		{
			$resultarray = $resource->fetch(PDO::FETCH_ASSOC);
		}
		catch (PDOException $exception)
		{
			$exception->getMessage();
		}

		if ($resultarray['ManagementID'] != $managementid)
		{
			header ("Location: $redirect");
		}
		
		// Last, if it's all worked out so far, set $mgmtmode to Yes using the private setter
		self::setMGMT("Yes");
	}

	private static function basic_checker()
	{
		if ($_SERVER['SCRIPT_NAME'] == '/listproperty.php')
		{
			if (isset($_GET['q']))
			{
				header ('Location: http://listproperty.php');
			}
		}

		elseif ($_SERVER['SCRIPT_NAME'] == '/listing.php')
		{
			// Check to see if the page IsPublic, if not, redirect.
			if (!isset($_GET['q']))
			{
				header ('Location: http://homeplaneta.com');
			}

			require_once 'pdoconfig.php';
			$query = "SELECT IsPublic FROM ListingInfo WHERE TranslatedID = :translatedID";
			$resource = $connection->prepare($query);
			$resource->bindParam(':translatedID', $_GET['q']);
			$resource->execute();

			try
			{
				$result = $resource->fetch(PDO::FETCH_ASSOC);
			}
			catch (PDOException $exception)
			{
				header ('Location: http://homeplaneta.com');
			}

			if ($result['IsPublic'] != 1)
			{
				header ('Location: http://homeplaneta.com/landing.php?t=3');
			}
		}

		else
		{
			header ('Location: http://homeplaneta.com');
		}
	}

	public static function secure_pic_uploader($i, $translatedID, $mode, $timestamp)
	{
		// Make a counter so that we know when something happens the first time, we'll increment it at the bottom
		$counter = $i;

		$picname = $_FILES['Picture'.$i]['name'];
		$tmpname = $_FILES['Picture'.$i]['tmp_name'];
		$filesize = $_FILES['Picture'.$i]['size'];
		$filetype = $_FILES['Picture'.$i]['type'];
		$error = $_FILES['Picture'.$i]['error'];

		//Get image dimensions
		$imgsize = getimagesize($tmpname);
		$picwidth = $imgsize[0];
		$picheight = $imgsize[1];

		// SECURITY CHECKS
		//Security - file size limit
		if ($filesize > 204800 ) 
		{
			echo $picname." is too big.  It must be less than <b>200kb</b> in size.<br />
			One way to make the file smaller might be to resize the photo to a maximum width of 680px or maximum height of 396px,<br />
			as explained on the <a href=\"http://homeplaneta.com/tips.php#phot1\">Homeplaneta tips page</a>.<br /><br />
			Press the back arrow in your browser to go back.";
			if ($mode == 'create') self::remove_directory($_SERVER['DOCUMENT_ROOT'] . '/pictures/' . $translatedID);
			exit();
		}

		//Security - blacklist file extensions
		$blacklist = array('.php','.phtml','.php3','.php4','.php5','.js','.inc','.exe','.htaccess','.html','.htm','.pl','.py','.jsp','.sh','.shtml','.cgi');
		$ext = substr(strrchr($picname,'.'), 0);
		if (in_array($ext, $blacklist)) 
		{
			echo $picname." is not in a valid image format (bad file.)<br />We require <b>.jpg</b>, <b>.png</b>, or <b>.gif</b> files.<br /><br />
			Press the back arrow in your browser to go back.";
			if ($mode == 'create') self::remove_directory($_SERVER['DOCUMENT_ROOT'] . '/pictures/' . $translatedID);
			exit();
		}

		//Security - If image file is not in the right format, stop everything and exit
		$acceptableimagearray = array('image/png','image/jpeg','image/pjpeg','image/gif');
		if (!in_array($filetype, $acceptableimagearray)) 
		{ 
			echo $i."-".$picname." is not in a valid image format (filetype.)".$filetype.$picname."<br />
			We require <b>.jpg</b>, <b>.png</b>, or <b>.gif</b> files.<br /><br />
			Press the back arrow in your browser to go back.";
			if ($mode == 'create') self::remove_directory($_SERVER['DOCUMENT_ROOT'] . '/pictures/' . $translatedID);
			exit();
		}

		//Security - check for mime type from getimagesize(), stop everything and exit if wrong
		if (!in_array($imgsize['mime'], $acceptableimagearray)) 
		{
			echo $picname." is not in a valid image format (mime.)<br />We require <b>.jpg</b>, <b>.png</b>, or <b>.gif</b> files.<br /><br />
			Press the back arrow in your browser to go back.";
			if ($mode == 'create') self::remove_directory($_SERVER['DOCUMENT_ROOT'] . '/pictures/' . $translatedID);
			exit();
		}

		// Make new directory on the server for the pictures to sit, just the first time
		$newdir = $_SERVER['DOCUMENT_ROOT'] . '/pictures/' . $translatedID;
		if (!is_dir($newdir))
		{
			mkdir($newdir) or die("Failed to create directory /pictures/$translatedID.<br />");
		}

		//Rename picture name to 1.jpg, etc
		$picname = $i.'-'.$timestamp.$ext;
		//Make new filename for the pic to dump it into the database
		$picfullpath = $_SERVER['DOCUMENT_ROOT'] . '/pictures/' . $translatedID . '/' . $picname;

		//Move the file -- if unsuccessful, throw an error 
		$result = move_uploaded_file($tmpname,$picfullpath);
		if ($result == 0) 
		{
			echo "Failed to upload file number ".$i." to new directory $picfullpath.<br />";
			if ($mode == 'create') self::remove_directory($_SERVER['DOCUMENT_ROOT'] . '/pictures/' . $translatedID);
		}

		// Unset PicCheck so later on we don't try to insert it as a database field in the DB
		unset($_POST['PicCheck'.$i]);

		$_POST['Picture'.$i.'Name'] = $picname;
		$_POST['Picture'.$i.'Width'] = $picwidth;
		$_POST['Picture'.$i.'Height'] = $picheight;

	}

	// Function to delete directory and associated files if upload is unsuccessful
	public static function remove_directory($dir)
	{
		if (is_dir($dir)) 
		{
			$scanresults = scandir($dir);
			foreach($scanresults as $picfiletobedeleted) 
			{
				if (($picfiletobedeleted != '.') && ($picfiletobedeleted != '..')) 
				{
					unlink($dir."/".$picfiletobedeleted); 
				} 
			}
			rmdir($dir);
		}
	}

	// On submission, get the latitude and longitude from Google Maps for the address
	static function getGoogleMapCoords($address)
	{
		$friendlyaddress = str_replace("\r\n",", ",$address);
		$encodedaddress = urlencode($friendlyaddress);
		$url = "http://maps.googleapis.com/maps/api/geocode/json?address=".$encodedaddress."&sensor=false";
		$result_string = file_get_contents($url);
		$rarray = json_decode($result_string);
		$lat = $rarray->results[0]->geometry->location->lat;
		$long = $rarray->results[0]->geometry->location->lng;
		return array ($lat, $long);
	}

	static function assign_rand_value($num)
	{
	// accepts 1 - 36
	  switch($num)
	  {
		case "1":
		 $rand_value = "a";
		break;
		case "2":
		 $rand_value = "b";
		break;
		case "3":
		 $rand_value = "c";
		break;
		case "4":
		 $rand_value = "d";
		break;
		case "5":
		 $rand_value = "e";
		break;
		case "6":
		 $rand_value = "f";
		break;
		case "7":
		 $rand_value = "g";
		break;
		case "8":
		 $rand_value = "h";
		break;
		case "9":
		 $rand_value = "i";
		break;
		case "10":
		 $rand_value = "j";
		break;
		case "11":
		 $rand_value = "k";
		break;
		case "12":
		 $rand_value = "l";
		break;
		case "13":
		 $rand_value = "m";
		break;
		case "14":
		 $rand_value = "n";
		break;
		case "15":
		 $rand_value = "o";
		break;
		case "16":
		 $rand_value = "p";
		break;
		case "17":
		 $rand_value = "q";
		break;
		case "18":
		 $rand_value = "r";
		break;
		case "19":
		 $rand_value = "s";
		break;
		case "20":
		 $rand_value = "t";
		break;
		case "21":
		 $rand_value = "u";
		break;
		case "22":
		 $rand_value = "v";
		break;
		case "23":
		 $rand_value = "w";
		break;
		case "24":
		 $rand_value = "x";
		break;
		case "25":
		 $rand_value = "y";
		break;
		case "26":
		 $rand_value = "z";
		break;
		case "27":
		 $rand_value = "0";
		break;
		case "28":
		 $rand_value = "1";
		break;
		case "29":
		 $rand_value = "2";
		break;
		case "30":
		 $rand_value = "3";
		break;
		case "31":
		 $rand_value = "4";
		break;
		case "32":
		 $rand_value = "5";
		break;
		case "33":
		 $rand_value = "6";
		break;
		case "34":
		 $rand_value = "7";
		break;
		case "35":
		 $rand_value = "8";
		break;
		case "36":
		 $rand_value = "9";
		break;
	  }
	return $rand_value;
	}

	static function get_rand_id($length)
	{
	  if($length>0) 
	  { 
	  $rand_id="";
	   for($i=1; $i<=$length; $i++)
	   {
	   srand((double)microtime() * 1000000);
	   $num = rand(1,36);
	   $rand_id .= self::assign_rand_value($num);
	   }
	  }
	return $rand_id;
	}

	public static function expire_listings($returnmode)
	{
		require 'pdoconfig.php';
		$query = "SELECT TranslatedID FROM ListingInfo WHERE CURDATE() > DateExpires";

		$resource = $connection->query($query);
		$properties_deleted = 0;

		$rows = $resource->fetchAll(PDO::FETCH_ASSOC);

		foreach ($rows as $row)
		{
			$to_be_deletedID = $row['TranslatedID'];
			$del_query = "DELETE FROM ListingInfo WHERE TranslatedID = :translatedid";
			$resource = $connection->prepare($del_query);
			$resource->bindParam(':translatedid', $to_be_deletedID);
			$resource->execute();
			$properties_deleted++;
			self::remove_directory($_SERVER['DOCUMENT_ROOT'] . '/pictures/' . $to_be_deletedID);
		}
		
		if ($returnmode == 'return_properties_deleted')
		{
			return $properties_deleted;
		}
	}
}
