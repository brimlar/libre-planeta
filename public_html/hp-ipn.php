<?php

# This needs to be at the start of the variable list back to PayPal
$req = 'cmd=_notify-validate';

# Grab each POST and put them into keys and values, then put them into $req
foreach ($_POST as $key => $value) {
	$value = urlencode(stripslashes($value));
	$req .= "&$key=$value";
	}

# post back to PayPal system to validate
$header .= "POST /cgi-bin/webscr HTTP/1.0\r\n";
$header .= "Content-Type: application/x-www-form-urlencoded\r\n";
$header .= "Content-Length: " . strlen($req) . "\r\n\r\n";
$fp = fsockopen ('ssl://www.paypal.com', 443, $errno, $errstr, 30);

# assign posted variables to local variables
$item_name = $_POST['item_name'];
$item_number = $_POST['item_number'];
$payment_status = $_POST['payment_status'];
$payment_amount = $_POST['mc_gross'];
$payment_currency = $_POST['mc_currency'];
$txn_id = $_POST['txn_id'];
$receiver_email = $_POST['receiver_email'];
$payer_email = $_POST['payer_email'];
$translatedID = $_POST['custom'];

if (!$fp) {
# HTTP ERROR
} 
else {
	fputs ($fp, $header . $req);
	while (!feof($fp)) {
		$res = fgets ($fp, 1024);
		if (strcmp ($res, "VERIFIED") == 0) {
			# check the payment_status is Completed
			if ($payment_status == 'Completed') {
				# check that txn_id has not been previously processed
				include 'pdoconfig.php';
				$tquery = "SELECT * FROM PaymentHistoryTable WHERE TxnID = :txnid";
				$resource = $connection->prepare($query);
				$resource->bindParam(':txnid', $txn_id);
				$resource->execute();
				$numrows = $resource->columnCount();
				if ($numrows == 0)
				{
					# check that receiver_email is your Primary PayPal email
					# check that payment_amount/payment_currency are correct
					# process payment
					if (($receiver_email == 'owner@homeplaneta.com') && (PackageCompare($item_number,$payment_amount,$payment_currency))) {
						include 'Mailmanager.class.php';
						ProcessPurchase($translatedID, $payer_email, $txn_id, $payment_amount);
						$mail1 = new ReceiptEmail($payer_email, $item_name, $item_number, $payment_amount);
						$mail1->send_it();
					} 
				} 
			} 
		}
		else if (strcmp ($res, "INVALID") == 0) {
			# log for manual investigation
			}
	}
	fclose ($fp);
}

function PackageCompare($itemnumber,$payamount,$currency)
{
	$valid = 'false';
	if ($currency == 'USD') {
		if (($itemnumber == '3-30') && ($payamount == '3.00')) $valid = 'true';
		elseif (($itemnumber == '12-90') && ($payamount == '12.00')) $valid = 'true';
		elseif (($itemnumber == '28-120') && ($payamount == '28.00')) $valid = 'true';
		}
	return $valid;
}

function ProcessPurchase($translatedID, $emailaddress, $txn_id, $payment_amount)
{
	if ($payment_amount == '3.00') $days = 30;
	elseif ($payment_amount == '12.00') $days = 90;
	elseif ($payment_amount == '28.00') $days = 180;
	#Get status from ListingInfo
	$getprevquery = "SELECT DateExpires, IsPaid FROM ListingInfo WHERE TranslatedID = :translatedid";
	$resource = $connection->prepare($getprevquery);
	$resource->bindParam(':translatedid', $translatedID);
	$resource->execute();
	$prevresult = $resource->fetch(PDO::FETCH_ASSOC);
	$previousexpiredate = $prevresult['DateExpires'];
	$previouslypaid = $prevresult['IsPaid'];
	#Now put the info into PurchaseTable
	$payquery = "INSERT INTO PaymentHistoryTable (AutoID, TranslatedID, EmailAddress, TxnID, TxnAmount, PreviousExpireDate, PreviouslyPaid) VALUES ('NULL', :translatedid, :emailaddress, :txn_id, :payment_amount, :previousexpiredate, :previouslypaid";
	$resource = $connection->prepare($payquery);
	$resource->bindParam(':translatedid', $translatedID);
	$resource->bindParam(':emailaddress', $emailaddress);
	$resource->bindParam(':txn_id', $txn_id);
	$resource->bindParam(':payment_amount', $payment_amount);
	$resource->bindParam(':previousexpiredate', $previousexpiredate);
	$resource->bindParam(':previouslypaid', $previouslypaid);
	$resource->execute();
	#Finally, update ListingInfo with new expiration date and paid status
	$dataquery = "UPDATE ListingInfo SET IsPaid='1', DateExpires=DATE_ADD(DateExpires, INTERVAL :days DAY) WHERE TranslatedID = :translatedid";
	$resource = $connection->prepare($dataquery);
	$resource->bindParam(':translatedid', $translatedid);
	$resource->bindParam(':days', $days);
	$resource->execute();
}

function ProcessRefund($translatedID, $emailaddress, $txn_id)
{
	#Get previous status from PurchaseTable
	$getprevquery = "SELECT PreviousExpireDate, PreviouslyPaid FROM PurchaseTable WHERE TranslatedID = $translatedID";
	$prevresult = mysql_query($getprevquery);
	list($previousexpiredate, $previouslypaid) = mysql_fetch_row($prevresult);
}
?>
