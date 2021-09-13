<?php ob_start(); ?>
<div style="background: #D0D1B8; font-family: corbel,arial,helvetica,ubuntu,sans-serif;">
<br />
<center>
<table width="760" cellpadding="0" cellspacing="0" style="background: #fff; border: 1px solid #a7a383;">
<tr>
<td>
<center>
<table width="724" cellpadding="0" cellspacing="0">

<tr>
<td height="22" style="padding: 2px; font-size: 0px; width: 30%; padding-bottom: 6px;"><a href="http://homeplaneta.com"><img src="http://homeplaneta.com/images/homeplaneta2.gif" style="border-style: none;"></a></td>
<td style="width: 70%; text-align: right; padding: 2px; padding-bottom: 6px;"><font size="2"><a href="http://homeplaneta.com/<?php echo $listing1->TranslatedID ?>">View the full listing, including a map and bigger photos</a></font></td>
</tr>

<tr>
<td colspan="2" style="border: 1px solid #ccc; background: #efefef; border-bottom: 0px; text-align: center;">
<br />
<font size="4" face="trebuchet ms"><b><?php if ($listing1->Title == '') echo $listing1->friendlyaddress."</b></font>"; else echo $listing1->Title; ?></b></font><br />
<?php if ($listing1->Title != '') echo "<font size=\"2\">".$listing1->friendlyaddress."</font>"; ?>
<font size="2"><br /><br /></font>
</td>
</tr>

<tr>
<td style="background: #efefef; width: 50%; text-align: left; vertical-align: bottom; padding: 4px; border-left: 1px solid #ccc;"><font size="3" face="trebuchet ms">Available <?php echo $listing1->get_friendly_date($listing1->DateAvailable) ?></font></td>
<td style="background: #efefef; width: 50%; text-align: right; padding: 4px; border-right: 1px solid #ccc;"><font size="3" face="trebuchet ms"><?php echo $listing1->rent_maker().$listing1->saleprice_maker() ?></font></td>
</tr>

<tr>
<td style="padding: 2px; padding-top: 10px; border-top: 6px solid #222;"><font size="2" face="trebuchet ms"><b><?php echo $listing1->propertyword ?> Description</b></font></td>
<td style="padding: 2px; padding-top: 10px; text-align: right; vertical-align: top; border-top: 6px solid #222;"><font size="1">Homeplaneta ID: <?php echo $listing1->TranslatedID ?></font></td>
</tr>

<tr>
<td colspan="2" style="padding: 4px;">
<font size="2"><?php echo $listing1->Description ?><br /></font>
<br />
</td>
</tr>

<tr>
<td style="width: 50%; text-align: left; padding: 4px; vertical-align: top;">
<font size="2"><font face="trebuchet ms"><b><?php echo $listing1->propertyword ?> Statistics</b></font>
<ul>
<?php
if (!empty($listing1->Neighborhood)) { echo "<li><b>Neighborhood</b>: $listing1->Neighborhood</li>"; }
if (!empty($listing1->Size)) { echo "<li><b>Size</b>: ".$listing1->size_maker()."</li>"; }
echo "
<li><b>Bed/bath</b>: $listing1->Bedrooms bedrooms, $listing1->Bathrooms bathrooms</li>
<li><b>Appliances included</b>: ".$listing1->appliance_maker()."</li>";

if (($listing1->TypeOfProperty == 2) || ($listing1->TypeOfProperty == 3)) { echo "<li><b>Washer/dryer</b>: ".$listing1->washerdryer_maker()."</li>"; }
if ($listing1->CentralAir == 1) { echo "<li><b>Central air</b>: Yes</li>"; }
echo "
<li><b>Parking</b>: ".$listing1->parking_maker()."</li>\n";
if (!empty($listing1->OtherAmenities)) { echo $listing1->additional_items_maker(); }
?>
</ul>
</font>
</td>
<td style="width: 50%; text-align: left; padding: 4px; vertical-align: top;">
<?php 
if (($listing1->RentOrSale == 1) OR ($listing1->RentOrSale == 3)) { echo "
<font size=\"2\"><font face=\"trebuchet ms\"><b>Rental Agreement</b></font>
<ul>
<li><b>Price</b>: " . $listing1->rent_maker() . "</li>
<li><b>Utilities Included</b>: " . $listing1->utilities_maker() . "</li>
<li><b>Security Deposit</b>: $listing1->SecurityDepositAmount</li>
<li><b>Lease</b>: " . $listing1->lease_maker() . "</li>";
if ($listing1->show_responsibilities()) { echo "<li><b>Tenant responsible for</b>: " . $listing1->responsibilities_maker() . "</li>"; }
echo "
<li><b>Pets</b>: " . $listing1->pet_maker() . "</li>
<li><b>Smoking</b>: "; if ($listing1->SmokingPermitted == 1) { echo "Permitted"; } else { echo "No smoking inside"; } echo "</li>
<li><b>Credit history check</b>: "; if ($listing1->CreditCheck == 1) { echo "Yes"; } else { echo "No"; } echo "</li>
</ul></font>"; }
if (($listing1->RentOrSale == 2) || ($listing1->RentOrSale == 3)) { echo "
<font size=\"2\"><font face=\"trebuchet ms\"><b>Sale Price</b></font>
<ul>
<li>$listing1->SalePrice</li>
</ul></font>"; } ?>
</td>
</tr>
</table>
</center>

<?php if ($listing1->ShowAgentInfo == '1') {
echo "
<center>
<table width=\"696\" cellpadding=\"0\" cellspacing=\"0\" style=\"border: 1px solid #ccc; background: #efefef; padding: 4px; font-family: corbel,arial,helvetica,ubuntu,sans-serif; \">
<tr>";
if ($listing1->Picture0Name != '') 
{
	echo "<td colspan=\"3\"><font size=\"2\" face=\"trebuchet ms\"><b>Proudly listed on behalf of owner by:</b></font></td>
<tr><td colspan=\"3\" height=\"6\"></td></tr>"; 
}
else 
{
	echo "<td colspan=\"2\"><font size=\"2\" face=\"trebuchet ms\"><b>Proudly listed on behalf of owner by:</b></font></td>
<tr><td colspan=\"2\" height=\"6\"></td></tr>";
}
echo "
</tr>
<tr>";
if ($listing1->Picture0Name != '') { echo "<td width=\"64\"><img src='http://homeplaneta.com/pictures/".$listing1->TranslatedID.'/'.$listing1->Picture0Name."' width=\"60\" height=\"60\" style=\"border: 1px solid #000;\" /></td>"; }
echo "<td width=\"";if ($listing1->Picture0Name != '') echo "280"; else echo "340"; echo "; vertical-align: top;\">
<font size=\"2\"><font face=\"trebuchet ms\"><b>$listing1->AgentName</b></font><br />
Phone:  $listing1->AgentPhone<br />";
if ($listing1->AgentFax != '') echo "Fax:  $listing1->AgentFax<br />";
echo "\nE-mail:  $listing1->EmailAddress</font></td>

<td width=\"336\" style=\"vertical-align: top;\">
<font size=\"2\"><font face=\"trebuchet ms\"><b>".$listing1->CompName."</b></font><br />";
if ($listing1->CompAddress != '') echo "$listing1->CompAddress<br />";
if ($listing1->CompWWW != '') echo "Website:  $listing1->CompWWW</font></td>";
echo "\n</tr>
</table>
</center>"; } ?>

<center>
<table width="696" cellpadding="3" cellspacing="0" style="font-family: corbel,arial,helvetica,ubuntu,sans-serif;">
<tr><td height="8"></td></tr>
<tr>
<td colspan="2" style="background: #E6E2D1; border: 1px solid #a7a383; color: #000; text-align: center; padding: 4px; ">
<font size="2" face="trebuchet ms"><b>Photographs</b></font>
</td>
</tr>

<tr><td height="2"></td></tr>

<?php
if ($listing1->NumberOfPictures > 8) $maxpics = 8; else $maxpics = $listing1->NumberOfPictures;
for ($g=1;$g<=$maxpics;$g++)
{
	if ($g % 2 == 1) echo "\n<tr>";
	echo "<td width=\"50%\" style=\"text-align: center;\">
<a href=\"http://homeplaneta.com/".$listing1->TranslatedID."\"><img src=\"http://homeplaneta.com/pictures/".$listing1->TranslatedID.'/'.$listing1->{'Picture'.$g.'Name'}."\" height=\"200\" style=\"border: 8px solid #eaeaea; max-width: 340px;\" /></a>
</td>";
	if ((($g % 2) == 0) || ($g == $maxpics)) echo "\n</tr>\n";
}
?>

<tr><td colspan="2" height="10"></td></tr>
<tr><td colspan="2" align="center"><font size="2"><a href="http://homeplaneta.com/<?php echo $listing1->TranslatedID ?>">View the full listing of this property</a></font></td></tr>
<tr><td colspan="2" height="10"></td></tr>
</table>
</center>

</tr>
</td>
</table>
</center>
<br />
</div>
<?php $htmlcode = ob_get_contents();
ob_end_clean();
?>
