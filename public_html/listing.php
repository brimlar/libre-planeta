<?php 
require_once 'Warden.class.php';

Warden::Examine();

require_once 'Listing.class.php';
require_once 'Advertisements.class.php';

$listing1 = Listing::Generate('PrettyListing', $_GET['q']);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>

<head>
<title><?php if ($listing1->Title == '') { echo "$listing1->friendlyaddress"; } else { echo "$listing1->Title"; } ?></title>
<META NAME="Description" CONTENT="<?php echo $listing1->friendlyaddress ?>">
<meta name="keywords" content="<?php echo $listing1->propertyword." available ".$listing1->DateAvailable ?>>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="stylesheet" type="text/css" href="style.css">
<link rel="stylesheet" type="text/css" href="print.css" media="print">
<link rel="shortcut icon" href="/images/house.ico">
<link rel="icon" type="image/ico" href="/images/house.ico">
<script language="javascript" src="functions2.js"></script>
<?php if ($_SERVER['HTTPS'] != 'on') echo "
<!-- Piwik -->
<script type='text/javascript'>
  var _paq = _paq || [];
  _paq.push(['setDomains', ['*.homeplaneta.com']]);
  _paq.push(['trackPageView']);
  _paq.push(['enableLinkTracking']);

  (function() {
    var u=(('https:' == document.location.protocol) ? 'https' : 'http') + '://homeplaneta.com/piwik/';
    _paq.push(['setTrackerUrl', u+'piwik.php']);
    _paq.push(['setSiteId', '1']);
    var d=document, g=d.createElement('script'), s=d.getElementsByTagName('script')[0]; g.type='text/javascript';
    g.defer=true; g.async=true; g.src=u+'piwik.js'; s.parentNode.insertBefore(g,s);
  })();
</script>
<!-- End Piwik Code -->"; ?>
</head>

<?php if (Warden::getMGMT() == 'Yes') require_once 'mheader2.php'; ?>

<body>
<div class="frame">
<div class="headerText">
<a href="http://homeplaneta.com" class='cornerbanner'><img src='/images/homeplaneta2.gif' alt='homeplaneta.com' border='0' /></a>
&nbsp;&nbsp;
<a href="#" onclick="javascript: mapOverlay(); return false;">Map this property</a>
&nbsp;|&nbsp;
<?php 
if ($listing1->HideEmail != 1) { echo "E-mail: <a href=\"mailto:$listing1->EmailAddress\">$listing1->EmailAddress</a>"; }
else { echo "<a href=\"#\" onclick=\"javascript: mailOverlay(); return false;\">E-mail the owner</a>"; }
?>

<div id='darkbg' style='display: none;'></div>

<div id='mapoverlaydiv'>
<?php
if ($_SERVER['HTTPS'] == 'on') {
	echo "You can't test your Google Map on this web page.<br /><br />
<b>Why is this?</b><br /><br />
This is because this Management Page is SSL-encrypted for security, so you can edit your listing safely and securely.  The map, however, is <i>not</i> SSL-encrypted, and if we were to display the map on this page it would create a warning in your web browser when you load this Management Page (which might scare people.)<br /><br />
You can view the Google Map on your Published page, once you <b>Publish</b> your listing -- and come back here to <b>Edit</b> your address if the map doesn't turn out right.<br /><br />
Sorry for the inconvenience.  Safety rules. :)<br />
 -- Homeplaneta\n"; }
elseif (($listing1->Latitude == 0) && ($listing1->Longitude == 0)) echo "There was a problem geocoding this address.<br /><br/>The owner needs to Edit their address to make it work with Google Maps.";
else {
echo"<iframe width='429' height='274' frameborder='0' scrolling='no' marginheight='0' marginwidth='0' src='http://maps.google.com/maps?hl=en&amp;q=".$listing1->encodedaddress."&amp;ie=UTF8&amp;hq=&amp;hnear=".$listing1->encodedaddress."&amp;ll=".$listing1->Latitude.",".$listing1->Longitude."&amp;spn=0.008697,0.018368&amp;z=15&amp;iwloc=A&amp;output=embed'></iframe><br /><small><a href='http://maps.google.com/maps?hl=en&amp;q=".$listing1->encodedaddress."&amp;ie=UTF8&amp;hq=&amp;hnear=.$listing1->encodedaddress.'&amp;ll=".$listing1->Latitude.",".$listing1->Longitude."&amp;spn=0.008697,0.018368&amp;z=15&amp;iwloc=A&amp;source=embed' style='color:#0000FF;text-align:left'>View Larger Map</a></small>"; }
?>
</div>

<div id='mailoverlaydiv' style='display: none;'>
<form name='minimail' />
<h4><b>Subject:</b> Response to Homeplaneta Listing #<?php echo $listing1->TranslatedID ?></h4>
<div class='spacer'><b>Your Name:</b>  <input type="text" name="Sender" size="24" style='float: right; border: 1px solid #ccc;' /></div>
<div class='spacer'><b>Your e-mail address:</b>  <input type="text" name="FirstEmail" size="24" style='float: right; border: 1px solid #ccc;' /></div>
<div class='spacer'><b>Re-enter your e-mail address:</b>  <input type="text" name="SecondEmail" size="24" style='float: right; border: 1px solid #ccc;' /></div>
<div class='spacer'><b>Your Message:</b> <textarea cols="40" rows="4" name="Message" maxlength="200"  style='float: right; border: 1px solid #ccc;'></textarea><i>200 character limit</i></div>
<div class='spacer'><input type="button" name="SendMail" value="Send E-Mail" onclick="inquire(<?php echo $listing1->TranslatedID ?>);" size="24" style='float: right;' /></div>
<div id='miniresultmsg'></div>
</form>
</div>

</div>

<div class="headerBox"<?php echo $listing1->headerheight_maker() ?>>
<?php echo $listing1->title_maker() ?>
<div class="headerBoxLeft">Available <?php echo $listing1->get_friendly_date($listing1->DateAvailable) ?></div>
<div class="headerBoxRight"><?php echo $listing1->rent_maker().$listing1->saleprice_maker() ?></div>
</div>

<div class="toptext">
<span class="listing">Homeplaneta ID: <?php echo $listing1->TranslatedID ?></span>
<div class="insideDescription">
<h4><?php echo $listing1->propertyword ?> Description</h4>
<p><?php echo $listing1->friendlydescription ?></p>
</div>

<div class="insideStatsLeft">
<h4><?php echo $listing1->propertyword ?> Statistics</h4>
<ul>
<?php
if (!empty($listing1->Neighborhood)) { echo "<li><b>Neighborhood</b>: $listing1->Neighborhood</li>\n"; }
if (!empty($listing1->Size)) { echo "<li><b>Size</b>: ".$listing1->size_maker()."</li>"; }
if ($listing1->TypeOfProperty != 4) echo "
<li><b>Bed/bath</b>: $listing1->Bedrooms bedrooms, $listing1->Bathrooms bathrooms</li>
<li><b>Appliances included</b>: ".$listing1->appliance_maker()."</li>";
if (($listing1->TypeOfProperty == 2) || ($listing1->TypeOfProperty == 3)) { echo "\n<li><b>Washer/dryer</b>: ".$listing1->washerdryer_maker()."</li>\n"; }
if ($listing1->CentralAir == 1) { echo "<li><b>Central air</b>: Yes</li>\n"; }
if ($listing1->TypeOfProperty != 4) { echo "<li><b>Parking</b>: ".$listing1->parking_maker()."</li>\n"; }
if (!empty($listing1->OtherAmenities)) { echo $listing1->additional_items_maker(); }
echo "</ul>
</div>\n";

if (($listing1->RentOrSale == 1) || ($listing1->RentOrSale == 3)) { 
echo "\n<div class=\"insideStatsRight\">
<h4>Rental Agreement</h4>
<ul>
<li><b>Price</b>: " . $listing1->rent_maker() . "</li>
<li><b>Utilities Included</b>: " . $listing1->utilities_maker() . "</li>
<li><b>Security Deposit</b>: ";if ($listing1->SecurityDeposit == 1) { echo "$listing1->SecurityDepositAmount</li>"; } else { echo "No security deposit</li>"; }
echo "
<li><b>Lease</b>: " . $listing1->lease_maker() . "</li>";
if ($listing1->show_responsibilities()) { echo "<li><b>Tenant responsible for</b>: " . $listing1->responsibilities_maker() . "</li>"; }
echo "
<li><b>Pets</b>: " . $listing1->pet_maker() . "</li>
<li><b>Smoking</b>: "; if ($listing1->SmokingPermitted == 1) { echo "Permitted"; } else { echo "No smoking inside"; } echo "</li>
<li><b>Credit history check</b>: "; if ($listing1->CreditCheck == 1) { echo "Yes"; } else { echo "No"; } echo "</li>
</ul>
</div>\n\n<div class=\"insideClear\"></div>\n\n"; }
if (($listing1->RentOrSale == 2) || ($listing1->RentOrSale == 3)) {
echo "\n<div class=\"insideStatsRight\">
<h4>Sale Price</h4>
<ul>
<li>$listing1->SalePrice</li>
</ul>
</div>\n"; }
?>

<div class="insideClear"></div>

</div>

<?php if ($listing1->ShowAgentInfo == '1') {
echo "<div id=\"AgentDiv\">
<h4>Proudly listed on behalf of owner by:</h4>";

if ($listing1->Picture0Name != '') 
{
	echo "\n<img src='pictures/$listing1->TranslatedID/$listing1->Picture0Name' />\n\n<p id='leftwpic'>"; 
} 
else 
{ 
	echo "\n<p id='leftnopic'>"; 
}

echo "
<b>$listing1->AgentName</b><br />
Phone:  $listing1->AgentPhone<br />";
if ($listing1->AgentFax != '') echo "Fax:  $listing1->AgentFax<br />";
echo "E-mail:  $listing1->EmailAddress<br />
</p>

<p id='right'>
<b>$listing1->CompName</b><br />";
if ($listing1->CompAddress != '') echo "$listing1->CompAddress<br />";
if ($listing1->CompWWW != '') echo "Website:  <a href='http://$listing1->CompWWW'>$listing1->CompWWW</a>";
echo "</p>

<div class=\"insideClear\"></div>
</div>\n"; }
else {
echo "<div id=\"AgentDiv\">
<p style='text-align: center; padding: 0px; margin: 0px;'>To inquire about this listing, use the email link at the top of the page.</p>
</div>"; }
?>

<div class="content">
<?php
for ($i=1;$i<=$listing1->NumberOfPictures;$i++)
{
	if (($i == 1) && ($listing1->showads == 1))
	{
		echo Advertisements::AD1;
	}

	echo $listing1->picturediv_maker($i);

	if (($i == 3) && ($listing1->showads == 1))
	{
		echo Advertisements::AD2;
	}

	if (($i == 6) && ($listing1->showads == 1))
	{
		echo Advertisements::AD3;
	}
}
?>

<div class="insideClear"></div>

</div>

</div>
<?php require_once 'footer.php'; ?>
</body>
</html>
