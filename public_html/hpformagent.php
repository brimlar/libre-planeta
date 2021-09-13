<?php

include 'pdoconfig.php';
include 'Warden.class.php';
include 'Mailmanager.class.php';

if (isset($_POST['Create']))
{
	// PICTURES, depending on how many pictures, loop through to all 18
	// If something exists in the agent photo field, start the loop at 0 to pick up the agent picture, etc
	if ($_FILES['Picture0']['name'] != '')	
	{
		$start = 0;
	}
	else
	{
		$start = 1;
	}

	// Need to get these variables declared asap so we can pass them to Warden
	$translatedid = rand(1,999999999);
	$managementid = Warden::get_rand_id(6);
	$timestamp = time();

	for ($i=$start;$i<=$_POST['NumberOfPictures'];$i++) 
	{
		Warden::secure_pic_uploader($i, $translatedid, 'create', $timestamp);
		
		if ($_POST['Picture'.$i.'Description'] == '-- Put a short description here (optional) --')
		{
			$_POST['Picture'.$i.'Description'] = '';
		}
		// Luckily, we set $_POST values in Warden so we're not inserting nulls
		// We'll use those values in this array so we can insert the key => values via PDO later
		$picarray['Picture'.$i.'Name'] = $_POST['Picture'.$i.'Name'];
		$picarray['Picture'.$i.'Width'] = $_POST['Picture'.$i.'Width'];
		$picarray['Picture'.$i.'Height'] = $_POST['Picture'.$i.'Height'];
		$picarray['Picture'.$i.'Description'] = $_POST['Picture'.$i.'Description'];
	}

	foreach ($picarray as $key => $value)
	{
		$picheaderstring .= "$key, ";
		$picvariablestring .= ':'.strtolower($key).', ';
	}

	// We'll take off the space and comma on the end to be good citizens
	$picheaderstring = substr($picheaderstring, 0, -2);
	$picvariablestring = substr($picvariablestring, 0, -2);

	//// END PICTURES ////////////////////////////////////////////////////////////////////////////////////////////////////////////

	// Before binding values, blank leasenumber2 and leasetime2 if no values exist
	if ($_POST['AnotherLease'] != 1) 
	{
		$_POST['LeaseNumber2'] = 0;
		$_POST['LeaseTime2'] = 0;
	}

	// Before binding values, set CompName if it is blank
	if ($_POST['CompName'] == '')
	{
		$_POST['CompName'] = "Independent Agent";
	}

	// Before binding values, trim CompWWW (we will escape html characters later)
	if ($_POST['CompWWW'] != '')
	{
		$_POST['CompWWW'] = trim($_POST['CompWWW']);
	}

	$ispublic = '0';
	// We set up a default status of "not paid" -- we'll pass the POST value for PayPalPick after the creation to PayPal, if they choose a paid package
	// Then, if they buy something from PayPal, the IPN handler will update IsPaid and DateExpires on its own
	// EDIT 7-11-2013 getting rid of Adsense so changing $ispaid = '0' to $ispaid = '1', no ads
	$ispaid = '1';

	// Figure out 30 days from today for $dateexpires
	$today = date("Y-m-d");
	$unixtoday = strtotime($today);
	$unixdateexpires = ($unixtoday + 2678400);
	$dateexpires = date("Y-m-d", $unixdateexpires);

    $query = "INSERT INTO ListingInfo (AutoID, TranslatedID, ManagementID, EmailAddress, HideEmail, ShowAgentInfo, AgentName, AgentPhone, AgentFax, CompName, CompAddress, CompWWW, Title, Address, Latitude, Longitude, TypeOfProperty, RentOrSale, RentalPrice, RentalPeriod, AnotherRentalPeriod, RentalPrice2, RentalPeriod2, SalePrice, LeaseNumber, LeaseTime, AnotherLease, LeaseNumber2, LeaseTime2, NoUtilitiesIncluded, HeatIncluded, ElectricIncluded, WaterIncluded, GasIncluded, PetsAllowed, CatsAllowed, SmallDogsAllowed, BigDogsAllowed, FishAllowed, BirdsAllowed, CaseByCase, SmokingPermitted, NoResponsibilities, ResponsibleLawn, ResponsibleLeavesBrush, ResponsibleSnow, ResponsibleGuttersDownspouts, ResponsibleSidewalk, ResponsibleMinorItems, CreditCheck, SecurityDeposit, SecurityDepositAmount, WasherDryer, DateAvailable, Description, Neighborhood, Size, MeasurementType, NoAppliancesIncluded, RefrigeratorIncluded, MicrowaveIncluded, OvenIncluded, DishwasherIncluded, ClothesWasherIncluded, DryerIncluded, Bedrooms, Bathrooms, CentralAir, GarageOrParking, GarageSpots, ParkingSpots, OtherAmenities, NumberOfPictures, $picheaderstring, DateExpires, IsPublic, IsPaid) VALUES (:autoid, :translatedid, :managementid, :emailaddress, :hideemail, :showagentinfo, :agentname, :agentphone, :agentfax, :compname, :compaddress, :compwww, :title, :address, :latitude, :longitude, :typeofproperty, :rentorsale, :rentalprice, :rentalperiod, :anotherrentalperiod, :rentalprice2, :rentalperiod2, :saleprice, :leasenumber, :leasetime, :anotherlease, :leasenumber2, :leasetime2, :noutilitiesincluded, :heatincluded, :electricincluded, :waterincluded, :gasincluded, :petsallowed, :catsallowed, :smalldogsallowed, :bigdogsallowed, :fishallowed, :birdsallowed, :casebycase, :smokingpermitted, :noresponsibilities, :responsiblelawn, :responsibleleavesbrush, :responsiblesnow, :responsibleguttersdownspouts, :responsiblesidewalk, :responsibleminoritems, :creditcheck, :securitydeposit, :securitydepositamount, :washerdryer, :dateavailable, :description, :neighborhood, :size, :measurementtype, :noappliancesincluded, :refrigeratorincluded, :microwaveincluded, :ovenincluded, :dishwasherincluded, :clotheswasherincluded, :dryerincluded, :bedrooms, :bathrooms, :centralair, :garageorparking, :garagespots, :parkingspots, :otheramenities, :numberofpictures, $picvariablestring, :dateexpires, :ispublic, :ispaid)";

	$resource = $connection->prepare($query);

	$resource->bindValue(':autoid', 'NULL');

	$resource->bindParam(':translatedid', $translatedid);

	$resource->bindParam(':managementid', $managementid);

	// we need to do special things to emailaddress, we're getting rid of agentemail
	$emailaddress = htmlspecialchars($_POST['EmailAddress']);
	$emailaddress = trim($emailaddress);
	$resource->bindParam(':emailaddress', $emailaddress);

	$resource->bindParam(':hideemail', htmlspecialchars($_POST['HideEmail']));
	$resource->bindParam(':showagentinfo', htmlspecialchars($_POST['ShowAgentInfo']));
	$resource->bindParam(':agentname', htmlspecialchars($_POST['AgentName']));
	$resource->bindParam(':agentphone', htmlspecialchars($_POST['AgentPhone']));
	$resource->bindParam(':agentfax', htmlspecialchars($_POST['AgentFax']));
	$resource->bindParam(':compname', htmlspecialchars($_POST['CompName']));
	$resource->bindParam(':compaddress', htmlspecialchars($_POST['CompAddress']));
	$resource->bindParam(':compwww', htmlspecialchars($_POST['CompWWW']));
	$resource->bindParam(':title', htmlspecialchars($_POST['Title']));
	$resource->bindParam(':address', htmlspecialchars($_POST['Address']));
	$resource->bindParam(':latitude', htmlspecialchars($_POST['Latitude']));
	$resource->bindParam(':longitude', htmlspecialchars($_POST['Longitude']));
	$resource->bindParam(':typeofproperty', htmlspecialchars($_POST['TypeOfProperty']));
	$resource->bindParam(':rentorsale', htmlspecialchars($_POST['RentOrSale']));
	$resource->bindParam(':rentalprice', htmlspecialchars($_POST['RentalPrice']));
	$resource->bindParam(':rentalperiod', htmlspecialchars($_POST['RentalPeriod']));
	$resource->bindParam(':anotherrentalperiod', htmlspecialchars($_POST['AnotherRentalPeriod']));
	$resource->bindParam(':rentalprice2', htmlspecialchars($_POST['RentalPrice2']));
	$resource->bindParam(':rentalperiod2', htmlspecialchars($_POST['RentalPeriod2']));
	$resource->bindParam(':saleprice', htmlspecialchars($_POST['SalePrice']));
	$resource->bindParam(':leasenumber', htmlspecialchars($_POST['LeaseNumber']));
	$resource->bindParam(':leasetime', htmlspecialchars($_POST['LeaseTime']));
	$resource->bindParam(':anotherlease', htmlspecialchars($_POST['AnotherLease']));
	$resource->bindParam(':leasenumber2', htmlspecialchars($_POST['LeaseNumber2']));
	$resource->bindParam(':leasetime2', htmlspecialchars($_POST['LeaseTime2']));
	$resource->bindParam(':noutilitiesincluded', htmlspecialchars($_POST['NoUtilitiesIncluded']));
	$resource->bindParam(':heatincluded', htmlspecialchars($_POST['HeatIncluded']));
	$resource->bindParam(':electricincluded', htmlspecialchars($_POST['ElectricIncluded']));
	$resource->bindParam(':waterincluded', htmlspecialchars($_POST['WaterIncluded']));
	$resource->bindParam(':gasincluded', htmlspecialchars($_POST['GasIncluded']));
	$resource->bindParam(':petsallowed', htmlspecialchars($_POST['PetsAllowed']));
	$resource->bindParam(':catsallowed', htmlspecialchars($_POST['CatsAllowed']));
	$resource->bindParam(':smalldogsallowed', htmlspecialchars($_POST['SmallDogsAllowed']));
	$resource->bindParam(':bigdogsallowed', htmlspecialchars($_POST['BigDogsAllowed']));
	$resource->bindParam(':fishallowed', htmlspecialchars($_POST['FishAllowed']));
	$resource->bindParam(':birdsallowed', htmlspecialchars($_POST['BirdsAllowed']));
	$resource->bindParam(':casebycase', htmlspecialchars($_POST['CaseByCase']));
	$resource->bindParam(':smokingpermitted', htmlspecialchars($_POST['SmokingPermitted']));
	$resource->bindParam(':noresponsibilities', htmlspecialchars($_POST['NoResponsibilities']));
	$resource->bindParam(':responsiblelawn', htmlspecialchars($_POST['ResponsibleLawn']));
	$resource->bindParam(':responsibleleavesbrush', htmlspecialchars($_POST['ResponsibleLeavesBrush']));
	$resource->bindParam(':responsiblesnow', htmlspecialchars($_POST['ResponsibleSnow']));
	$resource->bindParam(':responsibleguttersdownspouts', htmlspecialchars($_POST['ResponsibleGuttersDownspouts']));
	$resource->bindParam(':responsiblesidewalk', htmlspecialchars($_POST['ResponsibleSidewalk']));
	$resource->bindParam(':responsibleminoritems', htmlspecialchars($_POST['ResponsibleMinorItems']));
	$resource->bindParam(':creditcheck', htmlspecialchars($_POST['CreditCheck']));
	$resource->bindParam(':securitydeposit', htmlspecialchars($_POST['SecurityDeposit']));
	$resource->bindParam(':securitydepositamount', htmlspecialchars($_POST['SecurityDepositAmount']));
	$resource->bindParam(':washerdryer', htmlspecialchars($_POST['WasherDryer']));

	// we have to modify dateavailable b/c we want to submit it in mysql native format
	$dateavailable = htmlspecialchars($_POST['DateAvailable']);
	list($m,$d,$y) = explode("/",$dateavailable);
	$mysqldate = "$y-$m-$d";
	$resource->bindParam(':dateavailable', $mysqldate);

	$resource->bindParam(':description', htmlspecialchars($_POST['Description']));
	$resource->bindParam(':neighborhood', htmlspecialchars($_POST['Neighborhood']));
	$resource->bindParam(':size', htmlspecialchars($_POST['Size']));
	$resource->bindParam(':measurementtype', htmlspecialchars($_POST['MeasurementType']));
	$resource->bindParam(':noappliancesincluded', htmlspecialchars($_POST['NoAppliancesIncluded']));
	$resource->bindParam(':refrigeratorincluded', htmlspecialchars($_POST['RefrigeratorIncluded']));
	$resource->bindParam(':microwaveincluded', htmlspecialchars($_POST['MicrowaveIncluded']));
	$resource->bindParam(':ovenincluded', htmlspecialchars($_POST['OvenIncluded']));
	$resource->bindParam(':dishwasherincluded', htmlspecialchars($_POST['DishwasherIncluded']));
	$resource->bindParam(':clotheswasherincluded', htmlspecialchars($_POST['ClothesWasherIncluded']));
	$resource->bindParam(':dryerincluded', htmlspecialchars($_POST['DryerIncluded']));
	$resource->bindParam(':bedrooms', htmlspecialchars($_POST['Bedrooms']));
	$resource->bindParam(':bathrooms', htmlspecialchars($_POST['Bathrooms']));
	$resource->bindParam(':centralair', htmlspecialchars($_POST['CentralAir']));
	$resource->bindParam(':garageorparking', htmlspecialchars($_POST['GarageOrParking']));
	$resource->bindParam(':garagespots', htmlspecialchars($_POST['GarageSpots']));
	$resource->bindParam(':parkingspots', htmlspecialchars($_POST['ParkingSpots']));
	$resource->bindParam(':otheramenities', htmlspecialchars($_POST['OtherAmenities']));
	$resource->bindParam(':numberofpictures', htmlspecialchars($_POST['NumberOfPictures']));

	foreach ($picarray as $key => $value)
	{
		$bindvariable = ":" . strtolower($key);
		$resource->bindParam("$bindvariable", htmlspecialchars($value));
	}

	$resource->bindParam(':dateexpires', htmlspecialchars($dateexpires));
	$resource->bindParam(':ispublic', htmlspecialchars($ispublic));
	$resource->bindParam(':ispaid', htmlspecialchars($ispaid));

	try
	{
		$resource->execute();
	}
	catch (PDOException $e)
	{
		Warden::remove_directory($_SERVER['DOCUMENT_ROOT'] . '/pictures/' . $translatedid);
		throw 'Message: ' . $e->getMessage();
	}

	$paypalpick = $_POST['PayPalPick'];

	$mailobject = new PostSubmissionEmail($emailaddress, $translatedid, $managementid);
	$mailobject->send_it();

	switch ($paypalpick) 
	{
		case 0:
			header ('Location: http://homeplaneta.com/landing.php?t=1');
			exit ();
		case 1:
			$postvars = array('cmd' => '_s-xclick',
							'hosted_button_id' => 'NHD7K4AUM2EN6',
							'custom' => $translatedID);
			$host = 'https://www.paypal.com/cgi-bin/webscr?';
			$data = http_build_query($postvars);
			header("Location: ".$host.$data);
			exit ();
	/*	case 1:
			$postvars = array('cmd' => '_s-xclick',
							'hosted_button_id' => '7KF4DNJ37VM3A',
							'custom' => $translatedID);
			$host = 'https://www.sandbox.paypal.com/cgi-bin/webscr?';
			$data = http_build_query($postvars);
			header("Location: ".$host.$data);
			exit (); */
		case 2:
			$postvars = array('cmd' => '_s-xclick',
							'hosted_button_id' => 'DK8SZFNX77CVA',
							'custom' => $translatedID);
			$host = 'https://www.paypal.com/cgi-bin/webscr?';
			$data = http_build_query($postvars);
			header("Location: ".$host.$data);
			exit ();
		case 3:
			$postvars = array('cmd' => '_s-xclick',
							'hosted_button_id' => 'CJY5ZNDZ3KZEY',
							'custom' => $translatedID);
			$host = 'https://www.paypal.com/cgi-bin/webscr?';
			$data = http_build_query($postvars);
			header("Location: ".$host.$data);
			exit ();
	}
}

else
{
	Warden::deep_checker($_POST['Tid'], $_POST['Mid'], 'http://homeplaneta.com/landing.php?t=5');

	if (isset($_POST['Publish']))
	{
		$query = "UPDATE ListingInfo SET IsPublic='1' WHERE TranslatedID = :translatedid";
		$resource = $connection->prepare($query);
		$resource->bindParam(':translatedid', $_POST['Tid']);
		$resource->execute();
		header ('Location: https://homeplaneta.com/listing.php?q='.$_POST['Tid'].'&m='.$_POST['Mid']); 
		exit ();
	}

	elseif (isset($_POST['Delete']))
	{
		$query = "DELETE FROM ListingInfo WHERE TranslatedID = :translatedid";
		$resource = $connection->prepare($query);
		$resource->bindParam(':translatedid', $_POST['Tid']);
		$resource->execute();
		$picturedir = "pictures/".$_POST['Tid'];
		Warden::remove_directory($picturedir);
		header ('Location: http://homeplaneta.com/landing.php?t=2'); 
		exit ();
	}

	elseif (isset($_POST['Modify']))
	{
		// PICTURES, depending on how many pictures, loop through to all 18
		// If something exists in the agent picture value, start the loop at 0 to pick up the agent picture, etc
		if ($_FILES['Picture0']['name'] != '')	
		{
			$start = 0;
		}
		else
		{
			$start = 1;
		}
		// Set timestamp here so we don't create it each loop
		$timestamp = time();

		for ($i=$start;$i<=18;$i++) 
		{

		//Put the description into a variable regardless of what happens below, because someone could edit a Description and not change a picture
			$picdescription = $_POST['Picture'.$i.'Description'];
			if ($_POST['Picture'.$i.'Description'] == "-- Put a short description here (optional) --") 
			{
				$_POST['Picture'.$i.'Description'] = '';
			}

		//However, for Modify.php we only want to upload, etc pictures that have actually been changed
			if (($i <= $_POST['NumberOfPictures']) && (isset($_FILES['Picture'.$i]['name'])))
			{
				Warden::secure_pic_uploader($i, $_POST['Tid'], 'modify', $timestamp);
			} 

			//If the image is more than $_POST['NumberOfPictures'] but less than or equal to 18, blank out the items
			// We're putting the values back into $_POST so we can loop through them below
			elseif ($i > $_POST['NumberOfPictures']) 
			{
				$_POST["Picture".$i."Name"] = '';
				$_POST["Picture".$i."Width"] = '';
				$_POST["Picture".$i."Height"] = ''; 
			}
		}
		//# END PICTURES ######################################################

		if ($_POST['OrigAddress'] != $_POST['Address'])
		{
			// Get new latitude and longitude only if the address has changed using Server Side PHP call, since listproperty.php is SSL and client-side call won't work
			list($_POST['Latitude'],$_POST['Longitude']) = Warden::getGoogleMapCoords($_POST['Address']);
		}
		else
		{
			unset($_POST['Latitude'],$_POST['Longitude']);
		}

		// Before binding values, set CompName if it is blank
		if ($_POST['CompName'] == '')
		{
			$_POST['CompName'] = "Independent Agent";
		}

		// Before binding values, trim CompWWW (we will escape html characters later)
		if ($_POST['CompWWW'] != '')
		{
			$_POST['CompWWW'] = trim($_POST['CompWWW']);
		}

		// Here we have to do some stupid bullshit because checkboxes don't pass zeroes into $_POST if unchecked
		$checkboxarray = array('AnotherRentalPeriod','AnotherLease','NoUtilitiesIncluded','HeatIncluded','ElectricIncluded','WaterIncluded','GasIncluded','CatsAllowed','SmallDogsAllowed','BigDogsAllowed','FishAllowed','BirdsAllowed','CaseByCase','NoResponsibilities','ResponsibleLawn','ResponsibleLeavesBrush','ResponsibleSnow','ResponsibleGuttersDownspouts','ResponsibleSidewalk','ResponsibleMinorItems','NoAppliancesIncluded','RefrigeratorIncluded','MicrowaveIncluded','OvenIncluded','DishwasherIncluded','ClothesWasherIncluded','DryerIncluded');
		
		foreach ($checkboxarray as $name)
		{
			if (!isset($_POST["$name"]))
			{
				$_POST["$name"] = 0;
			}
		}

		// There are certain values we don't want to modify or use at all -- so we'll unset those variables as a security feature
		// These we do NOT want to modify
		unset($_POST['ManagementID'],$_POST['TranslatedID'],$_POST['EmailAddress']);
		// These we are not interested in using or further modifying 
		unset($_POST['OrigAddress'],$_POST['piclimit'],$_POST['orignumpix'],$_POST['Modify'],$_POST['MAX_FILE_SIZE']);
		// And there are other items that are posted from the form that we still need to use, so we'll set up an ignore array
		$ignorearray = array('Tid', 'Mid', 'PayPalPick');

		// BEGIN BUILDING QUERY //////////////////////////////////////////////

		foreach ($_POST as $key => $value)
		{
			// We don't want to update a nonexistant database field, like OrigAddress 
			if (!in_array($key, $ignorearray))
			{
				$queryfragment .= "$key=:".strtolower($key).', ';
			}
		}

		// Remove the space and the comma at the very end 
		$queryfragment = substr($queryfragment, 0, -2);

		$bigquery = 'UPDATE ListingInfo SET ' . $queryfragment . ' WHERE TranslatedID=:tid'; 
		$resource = $connection->prepare($bigquery);

		// We can't bind the value of DateAvailable as-is, the $_POST needs to be rewritten in MySQL date format
		list($m,$d,$y) = explode("/",$_POST['DateAvailable']);
		$_POST['DateAvailable'] = htmlspecialchars("$y-$m-$d");
		
		// BEGIN BINDING VALUES ///////////////////////////////////////////

		foreach ($_POST as $key => $value)
		{
			// We don't want to bind a couple values that we still need to use 
			if (!in_array($key, $ignorearray))
			{
				$bindvariable = ':'.strtolower($key);
				$resource->bindParam("$bindvariable", htmlspecialchars($value));
			}
		}

		$resource->bindParam(':tid', $_POST['Tid']);
		try
		{
			$resource->execute();
		}
		catch (PDOException $e)
		{
			throw $e->getMessage();
		}
		
		// Figure out if there are pictures in the directory that aren't being used anymore and need to be deleted/removed
		// Get filenames, put them into an array, compare them to scanned array and delete the ones in the directory that aren't in the DB
		$newquery = "SELECT Picture0Name, Picture1Name, Picture2Name, Picture3Name, Picture4Name, Picture5Name, Picture6Name, Picture7Name, Picture8Name, Picture9Name, Picture10Name, Picture11Name, Picture12Name, Picture13Name, Picture14Name, Picture15Name, Picture16Name, Picture17Name, Picture18Name FROM ListingInfo WHERE TranslatedID = :tid";
		$newresult = $connection->prepare($newquery);
		$newresult->bindParam(':tid', $_POST['Tid']);
		$newresult->execute();
		$realarray = $newresult->fetch(PDO::FETCH_ASSOC);
		$filearray = scandir("pictures/".$_POST['Tid']);
		foreach ($filearray as $filetobechecked) 
		{
			if (($filetobechecked != '.') && ($filetobechecked != '..') && (!in_array($filetobechecked, $realarray))) 
			{
				unlink("pictures/".$_POST['Tid']."/".$filetobechecked); 
			}
		}

		$paypalpick = $_POST['PayPalPick'];

		switch ($paypalpick) 
		{
			case 0:
				header ('Location: https://homeplaneta.com/listing.php?q='.$_POST['Tid'].'&m='.$_POST['Mid']);
				exit ();
			case 1:
				$postvars = array('cmd' => '_s-xclick',
								'hosted_button_id' => 'NHD7K4AUM2EN6',
								'custom' => $translatedID);
				$host = 'https://www.paypal.com/cgi-bin/webscr?';
				$data = http_build_query($postvars);
				header("Location: ".$host.$data);
				exit ();
		/*	case 1:
				$postvars = array('cmd' => '_s-xclick',
								'hosted_button_id' => '7KF4DNJ37VM3A',
								'custom' => $translatedID);
				$host = 'https://www.sandbox.paypal.com/cgi-bin/webscr?';
				$data = http_build_query($postvars);
				header("Location: ".$host.$data);
				exit (); */
			case 2:
				$postvars = array('cmd' => '_s-xclick',
								'hosted_button_id' => 'DK8SZFNX77CVA',
								'custom' => $translatedID);
				$host = 'https://www.paypal.com/cgi-bin/webscr?';
				$data = http_build_query($postvars);
				header("Location: ".$host.$data);
				exit ();
			case 3:
				$postvars = array('cmd' => '_s-xclick',
								'hosted_button_id' => 'CJY5ZNDZ3KZEY',
								'custom' => $translatedID);
				$host = 'https://www.paypal.com/cgi-bin/webscr?';
				$data = http_build_query($postvars);
				header("Location: ".$host.$data);
				exit ();
		}
	}
}



