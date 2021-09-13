<?php
$text=htmlspecialchars($_GET['t']);
if ((!isset($text)) || (!is_numeric($text))) {
	header ('Location: http://homeplaneta.com');
	exit();
	}
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>

<head>
<title>Homeplaneta - List your rental or sale property for free</title>
<META NAME="Description" CONTENT="Homeplaneta is a site where anyone can list their property to rent or sell for free -- easily.">
<meta name="keywords" content="homes for rent, homes for sale, planet, list your own property">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="stylesheet" type="text/css" href="style.css">
<link rel="stylesheet" type="text/css" href="print.css" media="print">
<link rel="shortcut icon" href="/images/house.ico">
<link rel="icon" type="image/ico" href="/images/house.ico"> 
</head>

<body>

<div class="frame">
<a href="http://homeplaneta.com"><img src="/images/homeplanetaLarge.gif" alt="homeplaneta" vspace="6" border='0' /></a>

<div class="toptext">
<?php 
if ($text == '1') echo "
<h3>Your listing has been submitted</h3>
An e-mail has been sent to the e-mail address you specified, which includes a link that can be used to finalize and publish your listing. <br /><br />Thank you for using Homeplaneta.
</div>

<div class='content'>
<br /><br />
<a href='listproperty.php' class='entry'>Submit another listing</a>
<br /><br /><br />
</div>";
elseif ($text == '2') echo "
<h3>Your listing has been deleted</h3>
Your listing has been deleted and the associated pictures destroyed.<br /><br />Thank you for using Homeplaneta.
</div>

<div class='content'>
<br /><br />
<a href='listproperty.php' class='entry'>Submit another listing</a>
<br /><br /><br />
</div>";
elseif ($text == '3') echo "
<h3>You are attempting to access a non-public listing</h3>
This listing hasn't been made public by its owner yet.<br /><br />If you are the owner, and wish for this listing to be made public,
please access the e-mail account you specified when you completed the listing form.  In that e-mail account there will be an
e-mail (sent by Homeplaneta) which includes a link you can use to publish your listing.
</div>

<div class='content'>
<br /><br />
<a href='listproperty.php' class='entry'>Submit another listing</a>
<br /><br /><br />
</div>";
elseif ($text == '4') echo "
<h3>Thank you for your purchase</h3>
Thank you for your PayPal purchase.  It may take a few minutes for your listing to reflect your transaction.  An e-mail will be sent to you
to acknowledge the transaction, and after you receive that e-mail you can view your Management Page and notice its updated status.<br /><br />
If you had already had a paid listing before your purchase, you should notice that your expiration date has been further extended.  If you didn't
previously have a paid listing, you will notice that 1) the advertisements will be gone, 2) you will be able to post up to 18 pictures, and 3) the 
expiration date has been extended.<br /><br />
If you experience any problems during your transaction, feel free to contact us at our customer service e-mail, 
<a href='mailto:customerservice@homeplaneta.com' style='color: #d7d5bf;'>customerservice@homeplaneta.com</a>, or
let us know via our PayPal shop page.
</div>

<div class='content'>
<br /><br />
<a href='listproperty.php' class='entry'>Submit another listing</a>
<br /><br /><br />
</div>";
else echo "
<h3>There is nothing here</h3>
I don't know why you're here -- this is a blank page.  It may <i>appear</i> that this page exists, but it is simply
your imagination working overtime again.  Perhaps now would be a good time to grab a tasty beverage and rest the eyes for a few minutes?<br /><br />
Feel free to submit a listing by following the link below, though.  Thanks again.
</div>

<div class='content'>
<br /><br />
<a href='listproperty.php' class='entry'>Submit another listing</a>
<br /><br /><br />
</div>";
?>

</div>
<?php include 'footer.php'; ?>
</body>

</html>
