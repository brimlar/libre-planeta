<?php

# $mailtype is the switch that determines whether inquireEmail or recoverEmail is called
$mailtype = $_GET['mt'];
# these are for inquireEmail
$translatedID = $_GET['q'];
$fromperson = $_GET['n'];
$fromaddy = $_GET['s'];
$second = $_GET['s2'];
$newbody = $_GET['b'];
# $emailaddy is the only thing we need for recoverEmail
$emailaddy = $_GET['e'];

if (!is_numeric($mailtype)) {
	echo "Bad query";
	exit(); }

######################################################################################
##### 1 is for inquireEmail, to send an e-mail to an anonymously-listed property #####
######################################################################################

if ($mailtype == '1') {

# Security checks on translatedID
# Use mysql_real_escape_string if it makes it through for a safe query

	if (!((strlen($translatedID)) <= 9) && (!is_numeric($translatedID))) {
		echo "Invalid listing number";
		exit(); }
	# We were doing this up here, but you need a valid MySql connection, it seems
	#else $translatedID = mysql_real_escape_string($translatedID);

# Security checks on the person's name
# Truncate the name to 45 chars too

	if (!is_string($fromperson)) {
		echo "Invalid name";
		exit(); }
	else if ((strlen($fromperson)) > 45) $fromperson = (substr($fromperson,0,45));

# Security checks on $sender and $second

	if ($fromaddy != $second) {
		echo "The two e-mail addresses provided do not match";
		exit(); }
// -- we only need to check one since they are equal at this point
	if (!filter_var($fromaddy, FILTER_VALIDATE_EMAIL)) {
		echo "Invalid e-mail address";
		exit(); }

# Security checks on $newbody
# Strip html tags from the body, if they tried to put them in

	$newbody = strip_tags($newbody);
	require_once 'Mailmanager.class.php';

	$mailobject = new InquiryEmail($translatedID,$fromperson,$fromaddy,$newbody);
	$mailobject->send_it();
}

#######################################################################################
##### 2 is for recoverEmail, to recover your Management Links if you've lost them #####
#######################################################################################

else if ($mailtype == '2') {

	if(!filter_var($emailaddy, FILTER_VALIDATE_EMAIL)) {
		echo "E-mail is not valid";
		exit(); }

	require_once 'Mailmanager.class.php';

	$mailobject = new RecoveryEmail($emailaddy);
	$mailobject->send_it();
}

else {
	echo "General Error";
	exit(); }

?>

