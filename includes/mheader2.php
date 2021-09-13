<?php
echo "<div id='mheader'>
<form action='hpformagent.php' name='Finishform' method='post' />
<input type='hidden' name='Tid' value='$listing1->TranslatedID' />
<input type='hidden' name='Mid' value='$listing1->ManagementID' />
<h3>Manage Your Listing</h3>
<div id='mcontent'>";

if ($_SERVER['SCRIPT_NAME'] == '/listing.php') 
{ 
	// Top item, the publish button or publish link
	echo "\n<div id='mitem'>\n";
	if ($listing1->IsPublic == 1) 
	{ 
		echo "Your website has been published at: <b><a href='http://homeplaneta.com/".$listing1->TranslatedID."'>http://homeplaneta.com/".$listing1->TranslatedID."</a></b>\n"; 
	}
	if ($listing1->IsPublic == 0) 
	{
		echo "\t<div id='mleft'>If you like what you see, click the Publish button and make your listing viewable to others.</div>
		<div id='mright'><input type='submit' name='Publish' value='Publish' /></div>\n"; 
	}
	echo "\t</div>\n"; 
	// Second item, the Edit button
	echo "\n<div id='mitem'>
	<div id='mleft'>If you want to edit your listing, click the Edit button.</div>
	<div id='mright'><input type='button' value='Edit' onclick=\"javascript: (location.href='https://homeplaneta.com/listproperty.php?q=".$listing1->TranslatedID."&m=".$listing1->ManagementID."')\" /></div>
	</div>\n"; 
	// Third item, the Delete button
	echo "\n<div id='mitem' style='border-top: 1px solid #ccc; margin-top: 4px; padding-top: 6px;'>
	<div id='mleft'>If you want to delete your listing, click the Delete button.</div>
	<div id='mright'><input type='submit' value='Delete' name='Delete' /></div>
	</div>"; 
}

#Else, if the page is listproperty just give directions and remove the buttons
else 
{ 
	echo "\n<div id='mitem'>
	Make your edits below and click 'Submit' at the bottom to commit the changes.<br />
	Once you click Submit, you will be returned to your Management Page where you can view your changes.
	\n\t</div>"; 
}

echo "\n</div>\n";
if ($_SERVER['SCRIPT_NAME'] == '/listing.php') 
{
	echo "<span class='greyblock'>";
	if ($listing1->IsPaid == 0) 
	{
		echo "You have a <b>free listing</b>";
	}
	else 
	{
		echo "You have a <b>paid listing</b>";
	}

	echo " that expires on <b>" . $listing1->get_friendly_date($listing1->DateExpires) . "</b>.</span>";

	if ($listing1->IsPublic == 1) 
	{
		echo "<input style='margin-left: 10px;' type='button' value='Get CraigCode' name='GetHTML' onclick='showHTML(); return false;' />
		<input type='button' value='Generate PDF' name='GeneratePDF' onclick=\"javascript: (location.href='pdfgenerator.php?q=$listing1->TranslatedID')\"/>";
	}
	echo "<br /><br />\nTo lengthen the life of your listing or remove the ads, you can use the Edit button and select a PayPal option, if you desire.\n";
}

echo "</div>\n";

if ($_SERVER['SCRIPT_NAME'] == '/listing.php')
{
	echo "\n<div id='htmlcode' style='display: none;'>\n";
	include 'craigcoder.php';
	echo "<h4>Copy and paste the text below into your Craigslist ad</h4>
	\n<textarea id='htmltext'>$htmlcode</textarea>\n<br />
	<ol>
	<li>Press <a href='#' onclick='select_all_HTML(); return false;'>Select All</a></li>
	<li>Right-click the highlighted selection and select 'Copy' (or Control-V)</li>
	<li>Paste the text into your Craigslist ad</li>
	<li>Close this window by pressing <a href='#' onclick='showHTML(); return false;'>Close</a></li>\n</ol>";
}

echo "</div>\n</form>\n";
?>
