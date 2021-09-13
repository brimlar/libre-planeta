<?php

abstract class Mailmanager
{
	abstract function send_it();

	protected function awesomeMailer($from,$to,$subject,$body,$requireResponse)
	{
		require_once 'Mail.php';
		$headers = array('From' => $from, 'To' => $to, 'Subject' => $subject);

		$host = "mail.homeplaneta.com";
		$port = "26";
		$username = "noreply@homeplaneta.com";
		$password = "{ic.#v?;)C3=";

		$headers = array ('From' => $from,'To' => $to,'Subject' => $subject);
		$smtp = Mail::factory('smtp',array ('host' => $host,'port' => $port,'auth' => true,'username' => $username,'password' => $password));
			 
		$mail = $smtp->send($to, $headers, $body);
			 
		if (PEAR::isError($mail)) {
			echo("<p>" . $mail->getMessage() . "</p>"); }
		else if ($requireResponse == 1) echo "<b>Message successfully sent!</b>";
		#else {
				#echo("<p>Message successfully sent!</p>");}
	}
}

class RecoveryEmail extends Mailmanager
{
	private $from = "Homeplaneta <noreply@homeplaneta.com>";
	private $to = '';
	private $subject = "As requested, your Homeplaneta Management Page(s).";
	private $body = "This is an auto-generated e-mail.  Do not respond to it.\n\n---------------------------------------------------------\n\nNOTE:  This link (or links) is what controls your listing!  Do not share this link with people you do not trust.\n\n";

	function __construct($emailaddress)
	{
		$emailaddress = trim($emailaddress);
		require 'pdoconfig.php';
		$query = "SELECT COUNT(*) FROM ListingInfo WHERE EmailAddress = :emailaddress";
		$resource = $connection->prepare($query);
		$resource->bindParam(':emailaddress', $emailaddress);
		$resource->execute();
		$results = $resource->fetchColumn();
		if ($results == 0)
		{
			echo "No listings are associated with that e-mail address.";
			exit();
		}
		// If there are results, we'll populate the object property $to with $emailaddress
		$query = "SELECT TranslatedID, ManagementID, Address FROM ListingInfo WHERE EmailAddress = :emailaddress";
		$resource = $connection->prepare($query);
		$resource->bindParam(':emailaddress', $emailaddress);
		$resource->execute();
		$this->to = $emailaddress;
		$message = "The following $results properties are associated with ".$this->to.":\n";
		$resultarray = $resource->fetchAll(PDO::FETCH_ASSOC);
		foreach ($resultarray as $row)
		{
			$translatedID = $row['TranslatedID'];
			$managementID = $row['ManagementID'];
			$address = str_replace("\r\n",", ",$row['Address']);
			$message .= "\n----\n".$address;
			$message .= "\nhttps://homeplaneta.com/listing.php?q=".$translatedID."&m=".$managementID;
			$message .= "\n----\n";
		}
		$this->body .= $message;
	}

	public function send_it()
	{
		$this->awesomeMailer($this->from, $this->to, $this->subject, $this->body, 1);
	}
}
	
class InquiryEmail extends Mailmanager
{
	private $from = "Homeplaneta <noreply@homeplaneta.com>";
	#private $from = '';
	private $to = '';
	private $subject = '';
	private $body = '';

	function __construct($translatedID,$fromperson,$fromaddy,$body)
	{
		require 'pdoconfig.php';

		$emailquery = "SELECT COUNT(*) FROM ListingInfo WHERE TranslatedID = :translatedid";
		$resource = $connection->prepare($emailquery);
		$resource->bindParam(':translatedid', $translatedID);
		$resource->execute();
		$numrows = $resource->fetchColumn();

		if ($numrows == 0) 
		{
			echo "No records match that listing number";
			exit();
		}

		$emailquery = "SELECT EmailAddress FROM ListingInfo WHERE TranslatedID = :translatedid";
		$resource = $connection->prepare($emailquery);
		$resource->bindParam(':translatedid', $translatedID);
		$resource->execute();
		$result = $resource->fetch(PDO::FETCH_ASSOC);
		#$this->from = $fromperson."<".$fromaddy.">";
		$this->to = $result['EmailAddress'];
		$this->subject = "Response to Homeplaneta listing #".$translatedID;
		$first_body = "You have received an inquiry from ".$fromperson." (".$fromaddy.")";
		$this->body = $first_body."\r\n\r\nThe message is:\r\n\r\n".$body;
	}

	public function send_it()
	{
		$this->awesomeMailer($this->from, $this->to, $this->subject, $this->body, 1);
	}
}

class DailyReportEmail extends Mailmanager
{
	private $from = "Homeplaneta <noreply@homeplaneta.com>";
	private $to = "Master <owner@homeplaneta.com>";
	private $body = "This is an auto-generated e-mail.  Do not respond to it.\n\n";
	private $subject = '';

	function __construct($properties_deleted)
	{
		require 'pdoconfig.php';
		$new_listings_query = "SELECT COUNT(*) FROM ListingInfo WHERE DATE_SUB(CURDATE(), INTERVAL 1 DAY) = DATE(DateCreated)";
		$resource = $connection->query($new_listings_query);
		$new_listings = $resource->fetchColumn();

		$this->subject = "Master: I deleted $properties_deleted listings, also $new_listings were created.";
		$this->body .= "---------------------------------------------------------\n\n";
		$this->body .= "Just an fyi that I dutifully deleted $properties_deleted properties today.\n\n";
		$this->body .= "Also, $new_listings listings were created yesterday.\n\n";
		if ($new_listings > 0)
		{
			$newquery = "SELECT TranslatedID FROM ListingInfo WHERE DATE_SUB(CURDATE(), INTERVAL 1 DAY) = DATE(DateCreated)";
			$newresource = $connection->query($newquery);
			$rows = $newresource->fetchAll(PDO::FETCH_ASSOC);
			foreach ($rows as $row)
			{
				$this->body .= 'Maybe available: http://homeplaneta.com/'.$row['TranslatedID']."\n";
			}
		}
		$this->body .= "XOXO,\nNameless Server Daemon";
	}

	public function send_it()
	{
		$this->awesomeMailer($this->from, $this->to, $this->subject, $this->body, 0);
	}
}

class PostSubmissionEmail extends Mailmanager
{
	private $from = "Homeplaneta <noreply@homeplaneta.com>";
	private $to = '';
	private $subject = "You've almost completed your listing.  Now...";
	private $body = "This is an auto-generated e-mail.  Do not respond to it.\n\n";

	function __construct($emailaddress, $translatedid, $managementid)
	{
		$this->to = $emailaddress;
		$this->body .= "---------------------------------------------------------\n\n";
		$this->body .= "Thank you for your submission.  Now do the following:\n\n";
		$this->body .= "Click the link below to go to your Management Page for your listing, so you can finalize and publish it.";
		$this->body .= "  NOTE: This link is what controls your listing!  Do not share this link with people you do not trust.\n\n";
		$this->body .= "Link to your Management Page:  https://homeplaneta.com/listing.php?q=".$translatedid."&m=".$managementid;
	}

	public function send_it()
	{
		$this->awesomeMailer($this->from, $this->to, $this->subject, $this->body, 0);
	}
}

class ReminderEmail extends Mailmanager
{
	private $from = "Homeplaneta <noreply@homeplaneta.com>";
	private $to = '';
	private $subject = "Friendly reminder: you have a listing nearing deletion";
	private $body = "This is an auto-generated e-mail.  Do not respond to it.\n\n";

	function __construct($emailaddress, $address)
	{
		$this->to = $emailaddress;
		$this->body .= "---------------------------------------------------------\n\n";
		$this->body .= "This is just a friendly reminder that you have a listing at Homeplaneta that is scheduled for expiration/deletion in three (3) days.\n\n";
		$this->body .= "The property's is: $address.  If you would like to extend your listing, or grab data from the listing before it's deleted, feel free to do so.";
		$this->body .= "  You can do this by accessing your Management Page.  If you've lost the address for your Management page, you can go to http://homeplaneta.com/recoveremail.php to recover the link.\n\n";
		$this->body .= "Otherwise, feel free to ignore this e-mail.\n\nThanks,\nHomeplaneta";
	}

	public function send_it()
	{
		$this->awesomeMailer($this->from, $this->to, $this->subject, $this->body, 0);
	}
}

class ReceiptEmail extends Mailmanager
{
	private $from = "Homeplaneta <noreplay@homeplaneta.com";
	private $to = '';
	private $subject = "Thank you for your purchase";
	private $body = '';

	function __construct($email, $item, $itemnum, $paytotal)
	{
		if ($paytotal == '3.00') $days = 30;
		elseif ($paytotal == '12.00') $days = 90;
		elseif ($paytotal == '28.00') $days = 180;

		$this->to = $email;
		$this->body .= "This is an auto-generated e-mail.  Do not respond to it.\n\n";
		$this->body .= "---------------------------------------------------------\n\n";
		$this->body .= "Thank you for your purchase of:\n\n";
		$this->body .= "$item (Item #$itemnum) for USD $paytotal.\n\n";
		$this->body .= "This transaction has been applied to your listing, and you will notice on your Management Page that your expiration date has been extended $days days.\n\n";
		$this->body .= "You should also notice that advertisements have been removed from your listing.  You can now also Edit your listing from your Management Page and add more pictures (up to a max of 18) as well.\n\n";
		$this->body .= "Thank you, and good luck with your listing.\n";
		$this->body .= "-- Homeplaneta";
	}

	public function send_it()
	{
		$this->awesomeMailer($this->from, $this->to, $this->subject, $this->body, 0);
	}
}
