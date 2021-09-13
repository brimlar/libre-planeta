<?php
require_once 'Warden.class.php';

Warden::Examine();

require_once 'Listing.class.php';

$listing1 = Listing::Generate('EditListing', $_GET['q']);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>

<head>
<title>Homeplaneta - List your property</title>
<META NAME="Description" CONTENT="Submit or create a listing for your property at this page.">
<meta name="keywords" content="homes for rent, homes for sale, planet, list your own property">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="stylesheet" type="text/css" href="style.css">
<link rel="stylesheet" type="text/css" href="print.css" media="print">
<link rel="shortcut icon" href="/images/house.ico">
<link rel="icon" type="image/ico" href="/images/house.ico">
<script language="javascript" src="functions2.js"></script>
<?php if ($_SERVER['HTTPS'] != 'on') echo "<script src='http://maps.google.com/maps/api/js?sensor=false'></script>"; ?>
</head>

<?php if (Warden::getMGMT() == 'Yes') include 'mheader2.php'; ?>

<body onload="toggleAgentZone();" <?php // To disable hiding e-mail if agent on page load ?>>
<script language="javascript">
var imgsrc = new Array ("pictures/<?php echo $listing1->TranslatedID.'/'.$listing1->Picture0Name ?>", "pictures/<?php echo $listing1->TranslatedID.'/'.$listing1->Picture1Name ?>", "pictures/<?php echo $listing1->TranslatedID.'/'.$listing1->Picture2Name ?>", "pictures/<?php echo $listing1->TranslatedID.'/'.$listing1->Picture3Name ?>", "pictures/<?php echo $listing1->TranslatedID.'/'.$listing1->Picture4Name ?>", "pictures/<?php echo $listing1->TranslatedID.'/'.$listing1->Picture5Name ?>", "pictures/<?php echo $listing1->TranslatedID.'/'.$listing1->Picture6Name ?>", "pictures/<?php echo $listing1->TranslatedID.'/'.$listing1->Picture7Name ?>", "pictures/<?php echo $listing1->TranslatedID.'/'.$listing1->Picture8Name ?>", "pictures/<?php echo $listing1->TranslatedID.'/'.$listing1->Picture9Name ?>", "pictures/<?php echo $listing1->TranslatedID.'/'.$listing1->Picture10Name ?>", "pictures/<?php echo $listing1->TranslatedID.'/'.$listing1->Picture11Name ?>", "pictures/<?php echo $listing1->TranslatedID.'/'.$listing1->Picture12Name ?>", "pictures/<?php echo $listing1->TranslatedID.'/'.$listing1->Picture13Name ?>", "pictures/<?php echo $listing1->TranslatedID.'/'.$listing1->Picture14Name ?>", "pictures/<?php echo $listing1->TranslatedID.'/'.$listing1->Picture15Name ?>", "pictures/<?php echo $listing1->TranslatedID.'/'.$listing1->Picture16Name ?>", "pictures/<?php echo $listing1->TranslatedID.'/'.$listing1->Picture17Name ?>", "pictures/<?php echo $listing1->TranslatedID.'/'.$listing1->Picture18Name ?>");
</script>

<div class="frame">
<a href="http://homeplaneta.com"><img src="/images/homeplanetaLarge.gif" alt="homeplaneta" vspace="6" border='0' /></a>

<div class="toptext">
<h3>Please enter the details of your property</h3>
Take the time to fill out the following items -- remember: the more items you fill out, the more useful your listing is to
people who are reading it.
</div>

<div class="error" <?php if (isset($_GET['e'])) { echo "style='display: block; position: relative; width: 730px; margin: auto; border: 1px solid #E65062; background: #FFD1D7; color: #000; font-weight: bold; padding-top: 10px; padding-bottom: 10px; margin-bottom: 20px;'>";} else {echo "style='display: none;'>";} ?><?php if (isset($_GET['e'])) echo "There was a problem with the ".$_GET['e']." field.  Please check it.<br />"; ?>
</div>

<div class="content">
<?php
if (Warden::getMGMT() == 'Yes') { echo "
<form action='hpformagent.php' method='post' name='Form1' enctype='multipart/form-data' onsubmit=\"return validateClean()\" >\n"; }
else { echo "
<form action='hpformagent.php' method='post' name='Form1' enctype='multipart/form-data'>\n"; }
?>
<input type='hidden' name='Tid' value="<?php echo $listing1->TranslatedID ?>"/>
<input type='hidden' name='Mid' value="<?php echo $listing1->ManagementID ?>"/>
<input type='hidden' name='OrigAddress' value="<?php echo $listing1->Address ?>"/>
<input type='hidden' name='Latitude' value="<?php echo $listing1->Latitude ?>"/>
<input type='hidden' name='Longitude' value="<?php echo $listing1->Longitude ?>"/>

<div class='formBoxLeft' style="margin-bottom: 10px;">Descriptive Title: <span class="notrequired">(not required)</span>
<span class='small'>Give a short, elegant title to your property<br />
<i>If you don't give a title, your address will be used as the title</i></span></div>
<div class='formBoxRight'><input type="text" name="Title" size="30" value="<?php echo $listing1->Title ?>"/></div>

<div class='formBoxLeft'>Address:
<span class='small'>Give the complete address of the property<br />
<i>Precision counts.  Help us provide a working map link by<br />
being complete, including the zipcode/postcode, state/province, country, etc.</i></span></div>
<div class='formBoxRight'><textarea cols="40" rows="4" name="Address" maxlength="80"><?php echo $listing1->Address ?></textarea></div>

<div class='formBoxLeft'>Neighborhood: <span class="notrequired">(not required)</span>
<span class='small'>Give the neighborhood of the property</span></div>
<div class='formBoxRight'><input type='text' name='Neighborhood' size='30' value="<?php echo $listing1->Neighborhood ?>"/></div>

<div class='formBoxLeft'>Property Type:
<span class='small'>Please mark what type of property you are listing</span></div>
<div class='formBoxRight'>
<input type="radio" name="TypeOfProperty" value="1" onclick='removeNotLand()' <?php if ($listing1->TypeOfProperty == '1') echo "checked" ?>/> House<br />
<input type="radio" name="TypeOfProperty" value="2" onclick='removeNotLand()' <?php if ($listing1->TypeOfProperty == '2') echo "checked" ?>/> Apartment<br />
<input type="radio" name="TypeOfProperty" value="3" onclick='removeNotLand()' <?php if ($listing1->TypeOfProperty == '3') echo "checked" ?>/> Condo<br />
<input type="radio" name="TypeOfProperty" value="4" onclick='removeNotLand()' <?php if ($listing1->TypeOfProperty == '4') echo "checked" ?>/> Land
</div>

<div class='formBoxLeft'>For Rent, Sale, or Both?
<span class='small'>Please mark whether your property is for rent, for sale, or both</span></div>
<div class='formBoxRight'>
<input type="radio" name="RentOrSale" onclick="toggleRentalZone()" value="1"<?php if ($listing1->RentOrSale == '1') echo "checked" ?> /> Rent<br /> 
<input type="radio" name="RentOrSale" onclick="toggleRentalZone()" value="2"<?php if ($listing1->RentOrSale == '2') echo "checked" ?> /> Sale<br />
<input type="radio" name="RentOrSale" onclick="toggleRentalZone()" value="3"<?php if ($listing1->RentOrSale == '3') echo "checked" ?> /> Both
</div>

<div class='formBoxLeft'>Price:
<span class='small'>Submit the price you are asking for<br /><i>Include your currency symbol, such as $, £, € etc.</i></span></div>
<div class='formBoxRight'>
<div id='RentalPriceItem' <?php if ($listing1->RentOrSale == '2') { echo "style='display: none;'"; } else echo "style='display: block;'"; ?>>
<input type="text" name="RentalPrice" size="5" value="<?php echo $listing1->RentalPrice ?>"/>&nbsp;per&nbsp; 
<select name='RentalPeriod'>
<option value='month'<?php if ($listing1->RentalPeriod == 'month') echo "selected='selected'";?>>month</option>
<option value='week'<?php if ($listing1->RentalPeriod == 'week') echo "selected='selected'";?>>week</option>
<option value='day'<?php if ($listing1->RentalPeriod == 'day') echo "selected='selected'";?>>day</option>
<option value='year'<?php if ($listing1->RentalPeriod == 'year') echo "selected='selected'";?>>year</option>
</select>&nbsp;&nbsp;&nbsp;&nbsp;
<input type="checkbox" name="AnotherRentalPeriod" value="1" onclick="AddRentalPeriod()" <?php if ($listing1->AnotherRentalPeriod == 1) echo "checked" ?>> Add another rental option<br />
</div>

<div id='RentalPriceItem2' <?php if ($listing1->AnotherRentalPeriod != 1) { echo "style='display: none;'"; } else echo "style='display: block;'"; ?>>
<input type="text" name="RentalPrice2" size="5" value="<?php echo $listing1->RentalPrice2 ?>"/>&nbsp;per&nbsp; 
<select name='RentalPeriod2'>
<option value='month'<?php if ($listing1->RentalPeriod2 == 'month') echo "selected='selected'";?>>month</option>
<option value='week'<?php if ($listing1->RentalPeriod2 == 'week') echo "selected='selected'";?>>week</option>
<option value='day'<?php if ($listing1->RentalPeriod2 == 'day') echo "selected='selected'";?>>day</option>
<option value='year'<?php if ($listing1->RentalPeriod2 == 'year') echo "selected='selected'";?>>year</option>
</select>&nbsp;&nbsp;&nbsp;&nbsp;
</div>

<div id='SalePriceItem' <?php if ($listing1->RentOrSale == '1') { echo "style='display: none;'"; } else echo "style='display: block;'"; ?>>
<input type="text" name="SalePrice" size="10" value="<?php echo $listing1->SalePrice ?>"/> to purchase
</div>
</div>

<div id="RentalZone" <?php if (($listing1->RentOrSale == '1') || ($listing1->RentOrSale == '3')) { echo "style='display: block;'"; } else { echo "style='display: none;'"; }?>><h3>Rental Conditions</h3>

<div class='formBoxLeft'>Lease Length:
<span class='small'>Give the length of the lease</i></span></div>
<div class='formBoxRight'>
<select name='LeaseNumber'>
<?php
for ($i=1;$i<13;$i++) {
	if ($i == $listing1->LeaseNumber) { echo "<option value='$i' selected='selected'>$i</option>\n"; }
	else { echo "<option value='$i'>$i</option>\n"; }
	}
?>
</select>
<select name='LeaseTime'>
<option value='year'<?php if ($listing1->LeaseTime == 'year') echo "selected='selected'";?>>year</option>
<option value='month'<?php if ($listing1->LeaseTime == 'month') echo "selected='selected'";?>>month</option>
</select>&nbsp;&nbsp;&nbsp;&nbsp;

<input type="checkbox" name="AnotherLease" value="1" onclick="AddLease()" <?php if ($listing1->AnotherLease == '1') echo "checked" ?>> Add another option<br />

<div id='Lease2' <?php if ($listing1->AnotherLease == 1) echo "style='display: block;'"?>>
<select name='LeaseNumber2'>
<?php
for ($i=1;$i<13;$i++) {
	if ($i == $listing1->LeaseNumber2) { echo "<option value='$i' selected='selected'>$i</option>\n"; }
	else { echo "<option value='$i'>$i</option>\n"; }
	}
?>
</select>
<select name='LeaseTime2'>
<option value='year' <?php if ($listing1->LeaseTime2 == 'year') echo "selected='selected'";?>>year</option>
<option value='month' <?php if ($listing1->LeaseTime2 == 'month') echo "selected='selected'";?>>month</option>
</select>
</div>
</div>

<div class='formBoxLeft'>Utilities Included:
<span class='small'>Mark the utilities that are included in the price</span></div>
<div class='formBoxRight'>
<input type="checkbox" name="NoUtilitiesIncluded" value="1" onclick="zeroOthers('UtilitiesIncluded')" <?php if ($listing1->NoUtilitiesIncluded == '1') echo "checked" ?>> None<br />
<input type="checkbox" name="HeatIncluded" value="1" onclick="zeroOthers('UtilitiesIncluded')" <?php if ($listing1->HeatIncluded == '1') echo "checked" ?>> Heat<br />
<input type="checkbox" name="ElectricIncluded" value="1" onclick="zeroOthers('UtilitiesIncluded')" <?php if ($listing1->ElectricIncluded == '1') echo "checked" ?>> Electric<br />
<input type="checkbox" name="WaterIncluded" value="1" onclick="zeroOthers('UtilitiesIncluded')" <?php if ($listing1->WaterIncluded == '1') echo "checked" ?>> Water<br />
<input type="checkbox" name="GasIncluded" value="1" onclick="zeroOthers('UtilitiesIncluded')" <?php if ($listing1->GasIncluded == '1') echo "checked" ?>> Gas
</div>

<div class='formBoxLeft'>Pets:
<span class='small'>Mark whether pets are allowed</span></div>
<div class='formBoxRight'>
<input type="radio" name="PetsAllowed" value="0" onclick='toggleWhichPets()' <?php if ($listing1->PetsAllowed == '0') echo "checked" ?>> None<br />
<input type="radio" name="PetsAllowed" value="1" onclick='toggleWhichPets()' <?php if ($listing1->PetsAllowed == '1') echo "checked" ?>> Certain Species / Negotiable<br />
<div id="WhichPets" <?php if ($listing1->PetsAllowed == '1') { echo "style='display: block;'"; } else echo "style='display: none;'";?> >
<ul>
<input type="checkbox" name="CatsAllowed" value="1" <?php if ($listing1->CatsAllowed == '1') echo "checked" ?>> Cats<br />
<input type="checkbox" name="SmallDogsAllowed" value="1" <?php if ($listing1->SmallDogsAllowed == '1') echo "checked" ?>> Small dogs<br />
<input type="checkbox" name="BigDogsAllowed" value="1" <?php if ($listing1->BigDogsAllowed == '1') echo "checked" ?>> Big dogs<br />
<input type="checkbox" name="FishAllowed" value="1" <?php if ($listing1->FishAllowed == '1') echo "checked" ?>> Fish<br />
<input type="checkbox" name="BirdsAllowed" value="1" <?php if ($listing1->BirdsAllowed == '1') echo "checked" ?>> Birds<br />
<input type="checkbox" name="CaseByCase" value="1" <?php if ($listing1->CaseByCase == '1') echo "checked" ?>> Case-by-case basis<br />
</ul>
</div>
<input type="radio" name="PetsAllowed" value="2" onclick='toggleWhichPets()' <?php if ($listing1->PetsAllowed == '2') echo "checked" ?>> Any / "Pet-friendly"
</div>

<div class='formBoxLeft'>Smoking Permitted:
<span class='small'>Mark whether or not smoking is permitted inside</span></div>
<div class='formBoxRight'>
<input type="radio" name="SmokingPermitted" value="1" <?php if ($listing1->SmokingPermitted == '1') echo "checked" ?>> Yes<br />
<input type="radio" name="SmokingPermitted" value="0" <?php if ($listing1->SmokingPermitted == '0') echo "checked" ?>> No
</div>

<div class='formBoxLeft'>Tenant's Responsibilities: <span class="notrequired">(not required)</span>
<span class='small'>Mark the responsibilities of the tenant<br /><i>Leave checkboxes blank if not using this field</i></span></div>
<div class='formBoxRight'>
<input type="checkbox" name="NoResponsibilities" value="1" onclick="zeroOthers('Responsibilities')" <?php if ($listing1->NoResponsibilities == '1') echo "checked" ?>> None / Owner provides all maintenance<br />
<input type="checkbox" name="ResponsibleLawn" value="1" onclick="zeroOthers('Responsibilities')" <?php if ($listing1->ResponsibleLawn == '1') echo "checked" ?>> Lawn<br />
<input type="checkbox" name="ResponsibleLeavesBrush" value="1" onclick="zeroOthers('Responsibilities')" <?php if ($listing1->ResponsibleLeavesBrush == '1') echo "checked" ?>> Leaves / brush<br />
<input type="checkbox" name="ResponsibleSnow" value="1" onclick="zeroOthers('Responsibilities')" <?php if ($listing1->ResponsibleSnow == '1') echo "checked" ?>> Snow removal<br />
<input type="checkbox" name="ResponsibleGuttersDownspouts" value="1" onclick="zeroOthers('Responsibilities')" <?php if ($listing1->ResponsibleGuttersDownspouts == '1') echo "checked" ?>> Gutters and downspouts<br />
<input type="checkbox" name="ResponsibleSidewalk" value="1" onclick="zeroOthers('Responsibilities')" <?php if ($listing1->ResponsibleSidewalk == '1') echo "checked" ?>> Sidewalk<br />
<input type="checkbox" name="ResponsibleMinorItems" value="1" onclick="zeroOthers('Responsibilities')" <?php if ($listing1->ResponsibleMinorItems == '1') echo "checked" ?>> Minor items (lightbulbs, drain clogs, etc)
</div>

<div class='formBoxLeft'>Credit History Check:
<span class='small'>Mark whether or not applicants will have their credit history checked</span></div>
<div class='formBoxRight'>
<input type="radio" name="CreditCheck" value="1" <?php if ($listing1->CreditCheck == '1') echo "checked" ?>> Yes<br />
<input type="radio" name="CreditCheck" value="0" <?php if ($listing1->CreditCheck == '0') echo "checked" ?>> No
</div>

<div class='formBoxLeft'>Security Deposit:
<span class='small'>Mark whether or not there is a security deposit</span></div>
<div class='formBoxRight'>
<input type="radio" name="SecurityDeposit" value="1" onclick='toggleSecurityDeposit()' <?php if ($listing1->SecurityDeposit == '1') echo "checked" ?>> Yes, of
<input type="text" name="SecurityDepositAmount" size="5" style='text-align: right;' value="<?php echo $listing1->SecurityDepositAmount ?>"/>&nbsp;
<i>(Include your currency symbol)</i><br />
<input type="radio" name="SecurityDeposit" value="0" onclick='toggleSecurityDeposit()' <?php if ($listing1->SecurityDeposit == '0') echo "checked" ?>> No
</div>

<div class='formBoxLeft'>Washer / Dryer:
<span class='small'>Select the option that best describes the washer/dryer situation</span></div>
<div class='formBoxRight'>
<input type="radio" name="WasherDryer" value="0" <?php if ($listing1->WasherDryer == '0') echo "checked" ?>> None<br />
<input type="radio" name="WasherDryer" value="1" <?php if ($listing1->WasherDryer == '1') echo "checked" ?>> In-Unit<br />
<input type="radio" name="WasherDryer" value="2" <?php if ($listing1->WasherDryer == '2') echo "checked" ?>> In Building (free)<br />
<input type="radio" name="WasherDryer" value="3" <?php if ($listing1->WasherDryer == '3') echo "checked" ?>> In Building (cash)
</div>

</div>

<div class='formBoxLeft'>Date of Availability:
<span class='small'>Date when this property will be available</span></div>
<div class='formBoxRight'>
<input type="text" readonly name="DateAvailable" id="DateAvailable" size="10" onfocus="showhideCalendar('<?php echo $listing1->today ?>')" value="<?php echo $listing1->get_cal_date($listing1->DateAvailable) ?>" onClick="showhideCalendar('<?php echo $listing1->today ?>')"/>
<div id='calendar' style="width: 100px; height: auto; position: relative; z-index: 15;"></div>
</div>

<div class='formBoxLeft'>Description:
<span class='small'>Description of the property (sell it!)<br /><i>1000 character limit</i></span></div>
<div class='formBoxRight'><textarea cols="40" rows="8" name="Description" maxlength="1000"><?php echo $listing1->Description ?></textarea></div>

<div class='formBoxLeft'>Size: <span class="notrequired">(not required)</span>
<span class='small'>Give the property's size</div>
<div class='formBoxRight'><input type="text" name="Size" size="10" value="<?php echo $listing1->Size ?>"/>&nbsp;
<select name='MeasurementType'>
<option value='square feet' <?php if ($listing1->MeasurementType == 'square feet') echo "selected='selected'";?>>square feet</option>
<option value='square metres' <?php if ($listing1->MeasurementType == 'square metres') echo "selected='selected'";?>>square metres</option>
<option value='acres' <?php if ($listing1->MeasurementType == 'acres') echo "selected='selected'";?>>acres</option>
<option value='hectares' <?php if ($listing1->MeasurementType == 'hectares') echo "selected='selected'";?>>hectares</option>
</select>
</div>

<div id='notLand' <?php if ($listing1->TypeOfProperty == '4') { echo "style='display: none;'"; } else { echo "style='display: block;'"; } ?>>
<div class='formBoxLeft'>Appliances Included:
<span class='small'>Mark the appliances that are included</span></div>
<div class='formBoxRight'>
<input type="checkbox" name="NoAppliancesIncluded" value="1" onclick="zeroOthers('AppliancesIncluded')" <?php if ($listing1->NoAppliancesIncluded == '1') echo "checked" ?>> None<br />
<input type="checkbox" name="RefrigeratorIncluded" value="1" onclick="zeroOthers('AppliancesIncluded')" <?php if ($listing1->RefrigeratorIncluded == '1') echo "checked" ?>> Refrigerator<br />
<input type="checkbox" name="MicrowaveIncluded" value="1" onclick="zeroOthers('AppliancesIncluded')" <?php if ($listing1->MicrowaveIncluded == '1') echo "checked" ?>> Microwave<br />
<input type="checkbox" name="OvenIncluded" value="1" onclick="zeroOthers('AppliancesIncluded')" <?php if ($listing1->OvenIncluded == '1') echo "checked" ?>> Oven<br />
<input type="checkbox" name="DishwasherIncluded" value="1" onclick="zeroOthers('AppliancesIncluded')" <?php if ($listing1->DishwasherIncluded == '1') echo "checked" ?>> Dishwasher<br />
<input type="checkbox" name="ClothesWasherIncluded" value="1" onclick="zeroOthers('AppliancesIncluded')" <?php if ($listing1->ClothesWasherIncluded == '1') echo "checked" ?>> Clothes Washer<br />
<input type="checkbox" name="DryerIncluded" value="1" onclick="zeroOthers('AppliancesIncluded')" <?php if ($listing1->DryerIncluded == '1') echo "checked" ?>> Dryer
</div>

<div class='formBoxLeft'>Bedrooms:
<span class='small'>Give the number of bedrooms</span></div>
<div class='formBoxRight'>
<select name='Bedrooms'>
<option value=''></option>
<?php
for ($i=0;$i<17;$i++) {
	if (($i == $listing1->Bedrooms) && ($listing1->Bedrooms != '')) { echo "<option value='$i' selected='selected'>$i</option>\n"; }
	else { echo "<option value='$i'>$i</option>\n"; }
	}
?>
</select>
</div>

<div class='formBoxLeft'>Bathrooms:
<span class='small'>Give the number of bathrooms</span></div>
<div class='formBoxRight'>
<select name='Bathrooms'>
<option value=''></option>
<?php
for ($i=0;$i<8.5;$i+=0.5) {
	if (($i == $listing1->Bathrooms) && ($listing1->Bathrooms != '')) { echo "<option value='$i' selected='selected'>$i</option>\n"; }
	else { echo "<option value='$i'>$i</option>\n"; }
	}
?>
</select>
</div>

<div class='formBoxLeft'>Central Air:
<span class='small'>Does your property have central air?</span></div>
<div class='formBoxRight'>
<input type="radio" name="CentralAir" value="1" <?php if ($listing1->CentralAir == '1') echo "checked" ?>> Yes<br />
<input type="radio" name="CentralAir" value="0" <?php if ($listing1->CentralAir == '0') echo "checked" ?>> No
</div>

<div class='formBoxLeft'>Garage / parking space:
<span class='small'>Does your property have a garage or parking space included?</span></div>
<div class='formBoxRight'>
<input type="radio" name="GarageOrParking" value="0" onclick='zeroParkingSpots()' <?php if ($listing1->GarageOrParking == '0') echo "checked" ?>> None included<br />
<input type="radio" name="GarageOrParking" value="1" onclick='zeroParkingSpots()' <?php if ($listing1->GarageOrParking == '1') echo "checked" ?>> Garage which fits
<select name='GarageSpots' id='GarageSpots'>
<?php
for ($i=0;$i<7;$i++) {
	if ($i == $listing1->GarageSpots) { echo "<option value='$i' selected='selected'>$i</option>\n"; }
	else { echo "<option value='$i'>$i</option>\n"; }
	}
?>
</select> cars<br />
<input type="radio" name="GarageOrParking" value="2" onclick='zeroParkingSpots()' <?php if ($listing1->GarageOrParking == '2') echo "checked" ?>> Parking for
<select name='ParkingSpots' id='ParkingSpots'>
<?php
for ($i=0;$i<7;$i++) {
	if ($i == $listing1->ParkingSpots) { echo "<option value='$i' selected='selected'>$i</option>\n"; }
	else { echo "<option value='$i'>$i</option>\n"; }
	}
?>
</select> cars<br />
</div>
</div>

<div class='formBoxLeft' style='margin-bottom: 15px;'>Other Amenities: <span class="notrequired">(not required)</span>
<span class='small'>List some other amenities, each item separated by two plus signs (++)<br />
<i><b>Example:</b> Energy-efficient furnace++Brick patio in back++Wonderful garden gnomes</i></span></div>
<div class='formBoxRight'><input type="text" name="OtherAmenities" size="30" value="<?php echo $listing1->OtherAmenities ?>"/></div>

<div class='formBoxLeft'>ATTENTION: AGENTS
<span class='small'>If you are an agent, mark whether or not you want a special section<br />showing your agent information on this listing</span></div>
<div class='formBoxRight'>
<input type="radio" name="ShowAgentInfo" value="1" onclick="toggleAgentZone();" <?php if ($listing1->ShowAgentInfo == '1') echo "checked" ?>> Yes<br />
<input type="radio" name="ShowAgentInfo" value="0" onclick="toggleAgentZone();" <?php if ($listing1->ShowAgentInfo == '0') echo "checked" ?>> No
</div>

<div id="AgentZone" <?php if ($listing1->ShowAgentInfo == '1') { echo "style='display: block;'"; } else { echo "style='display: none;'"; }?>><h3>Agent Information</h3>

<div class='formBoxLeft' style='margin-bottom: 10px;'>Agent's Name:
<span class='small'>Give the name of the owner's agent or representative</span></div>
<div class='formBoxRight'><input type='text' name='AgentName' size='30' value="<?php echo $listing1->AgentName ?>"/></div>

<div class='formBoxLeft' style='margin-bottom: 10px;'>Agent's Photo: <span class="notrequired">(not required)</span>
<span class='small'>Upload a photo of the agent, or a corporate logo <br /><i>If you decide to include a photo/logo, square images display best</i><br /></span></div>
<div class='formBoxRight' id='PictureDiv0' ><?php
if ((Warden::getMGMT() == 'Yes') && ($listing1->Picture0Name != '')) 
{ 
	echo "<img src=\"pictures/".$listing1->TranslatedID.'/'.$listing1->Picture0Name."\" class='agentthumb' />
<label>&nbsp;<input type='checkbox' name='PicCheck0' style='vertical-align: middle;' value='1' onclick='ShowUpload(\"0\")'>
Choose a different picture</label><br />
<div id=\"Picture0\" style='display: none;'></div></div>"; 
}
else echo "<input type='file' name='Picture0' size='25' class='file' /></div>";
?>

<div class='formBoxLeft' style='margin-bottom: 10px;'>Agent's Phone Number:
<span class='small'>Give the agent's phone number</span></div>
<div class='formBoxRight'><input type='text' name='AgentPhone' size='30' value="<?php echo $listing1->AgentPhone ?>"/></div>

<div class='formBoxLeft' style='margin-bottom: 10px;'>Agent's Fax Number: <span class="notrequired">(not required)</span>
<span class='small'>Give the agent's fax number</span></div>
<div class='formBoxRight'><input type='text' name='AgentFax' size='30' value="<?php echo $listing1->AgentFax ?>"/></div>

<div class='formBoxLeft' style='margin-bottom: 10px;'>Listing Company:
<span class='small'>Give the name of the agent's listing company<br /><i>If you do not enter something here, "Independent Agent" will show up</i></span></div>
<div class='formBoxRight'><input type='text' name='CompName' size='30' value="<?php echo $listing1->CompName ?>"/></div>

<div class='formBoxLeft' style='margin-bottom: 10px;'>Listing Company Address: <span class="notrequired">(not required)</span>
<span class='small'>Give the address of the listing company</span></div>
<div class='formBoxRight'><textarea cols="40" rows="4" name="CompAddress" maxlength="80"><?php echo $listing1->CompAddress ?></textarea></div>

<div class='formBoxLeft' style='margin-bottom: 10px;'>Listing Company Website: <span class="notrequired">(not required)</span>
<span class='small'>Give the official website of the listing company<br /><i>Put your item in this format: www.something.com</i></span></div>
<div class='formBoxRight'><input type='text' name='CompWWW' size='30' value="<?php echo $listing1->CompWWW ?>"/></div>

</div>

<div class='formBoxLeft'>E-mail Address:
<span class='small'>Give the e-mail address of the person who will manage this listing</span></div>
<div class='formBoxRight'><?php
if ($listing1->EmailAddress != '') echo "<input type='hidden' name='EmailAddress' value=\"$listing1->EmailAddress\" />$listing1->EmailAddress"; 
else { echo "<input type='text' name='EmailAddress' size='30' />\n<span class='redbold'>Important</span><br />"; } 
?>
</div>

<div class='formBoxLeft' style='margin-bottom: 15px;'>Hide Your E-mail Address?
<span class='small'>If you hide your e-mail address, it will not display on the listing page<br />
<i>An anonymous e-mail form will show up if you elect to hide your e-mail address, allowing you to receive mail from interested people without compromising your anonymity</i></span></div>
<div class='formBoxRight'>
<input type="radio" name="HideEmail" value="1" <?php if ($listing1->HideEmail == '1') echo "checked" ?>> Hide my email address<br />
<input type="radio" name="HideEmail" value="0" <?php if ($listing1->HideEmail == '0') echo "checked" ?>> Display my email address
</div>

<div class='formBoxLeft' style='margin-bottom: 15px;'>How many pictures will you be posting?
<span class='small'>Give the number of pictures you'll be uploading<br /><i>You can have up to 18.</i></span></div>
<div class='formBoxRight'>
<select name='NumberOfPictures' onchange='togglePictureUploads()' >
<?php
#Set a variable so we know how many pictures there were originally
$orignumpix = $listing1->NumberOfPictures;
#Also set $piclimit here b/c we'll use it twice
$piclimit = 19;
for ($i=3;$i<$piclimit;$i++) {
	if (($listing1->NumberOfPictures == 0) && ($i == 3)) { echo "<option value='$i' selected='selected'>$i</option>\n"; }
	elseif ($i == $listing1->NumberOfPictures) { echo "<option value='$i' selected='selected'>$i</option>\n"; }
	else { echo "<option value='$i'>$i</option>\n"; }
	}
?>
</select> pictures&nbsp;&nbsp;&nbsp;<b>(Minimum of 3, maximum of <?php echo ($piclimit-1); ?>)</b>
<input type='hidden' name='orignumpix' value="<?php echo $orignumpix ?>" />
<input type='hidden' name='piclimit' value="<?php echo $piclimit ?>" />
</div>

<!--<input type='hidden' name='MAX_FILE_SIZE' value='200000' /> -->

<?php
# Draw all of the picture divs, but only fill in the ones that are less than or equal to $listing1->NumberOfPictures
if (Warden::getMGMT() == "No") $listing1->NumberOfPictures = 3;
for ($i=1;$i<$piclimit;$i++) {
	if ($i<=$listing1->NumberOfPictures) {
		$filestring = 'Picture' . $i . 'Name';
		$descstring = 'Picture' . $i . 'Description';
		echo "
<div id='PictureDiv$i' style='display: block;'>
<div class='formBoxLeft'>Picture #$i
<span class='small'>Select Picture #$i and give a brief description of it<br />
<i>Note: Must be in jpg, png or gif format, and less than 200kb in size.</i></span></div>
<div class='formBoxRight'>";
if (Warden::getMGMT() == 'Yes') { echo "
<img src=\"pictures/".$listing1->TranslatedID.'/'.$listing1->$filestring."\" class='thumb' /><br />
<label>&nbsp;<input type='checkbox' name='PicCheck$i' style='vertical-align: middle;' value='1' onclick='ShowUpload(\"$i\")'>
Choose a different picture</label><br />
<div id=\"Picture$i\" style='display: none;'></div>"; }
else { echo "<input type='file' name=\"Picture$i\" size='25' class='file' />"; }
echo "
<textarea name=\"Picture".$i."Description\" cols='40' rows='3' maxlength='100' class='PictureDivTextArea' >";
#If there's a description, put it in.  Otherwise, use the standard text.
if ($listing1->$descstring != '') echo $listing1->$descstring; else echo "-- Put a short description here (optional) --"; 
echo "</textarea>\n</div>\n</div>\n"; } 
	else echo "<div id='PictureDiv$i' style='display: none;'></div>\n"; }
?>

<?php 
if (Warden::getMGMT() == 'Yes') { echo "
<hr style='float: left; margin: auto; width: 100%; margin-bottom: 8px;'>
<span style='float: left; margin: auto; width: 100%;'>
You currently have a ";
switch ($listing1->IsPaid)
{
	case 0:
		echo "Free 30-day listing (with ads) expiring <b>" . $listing1->get_friendly_date($listing1->DateExpires) . "</b><br /><br />";
		break;
	case 1:
		echo "<b>paid listing</b> (ad-free), expiring <b>" . $listing1->get_friendly_date($listing1->DateExpires) . "</b><br /><br />";
		break;
}
echo "</span>
<div class='formBoxLeft' style='margin-bottom: 15px;'>Listing Duration
<span class='small'><i>If you want to extend the life of your listing, you can select a payment option, which will send you to PayPal
for the transaction.</i></span></div>
<div class='formBoxRight' style='margin-bottom: 0px;'>
<input type='radio' name='PayPalPick' value='0' checked> No change to my package<br />
<input type='radio' name='PayPalPick' value='1' > $3 - add 30 days to my listing<br />
<input type='radio' name='PayPalPick' value='2' > $12 - add 90 days to my listing<br />
<input type='radio' name='PayPalPick' value='3' > $28 - add <b>180 days</b> to my listing
</div>"; }

else { echo "
<div class='formBoxLeft' style='margin-bottom: 15px;'>Listing Duration
<span class='small'><i>This listing will last for 30 days, for free!  If you need to have it last longer, you can pay for extra time after you create the listing 
using the Management Page of your listing.</i></span>
</div>
<div class='formBoxRight' style='margin-bottom: 0px;'>
<input type='radio' name='PayPalPick' value='0' checked> <b>Completely Free</b> 30-day listing<br />
</div>"; } ?>

<div id="Agreement">
<h3>By clicking "Submit," you agree to the following:</h3>
For our free listings, our goal is to keep them up for a period of 30 days, after which time
the user may have the opportunity to re-post their property.  Paid listings will expire at a variable time determined by the number of days the user has purchased.  We reserve the right to modify or delete a listing <b>for any reason</b>, but this scenario would most likely occur if:  1) we receive complaints about your listing, 2) if it is negatively affecting the technical operations of the website, or 3) if we feel the listing breaks commonly-held standards of decency and legality.<br /><br />
Homeplaneta reserves the right to make any changes to the service at any time.<br /><br />
<b>You recognize that you are personally responsible for the content you publish</b> -- such as complying with laws that prohibit discrimination, for example.<br /><br />
<b>Privacy is important to us.</b>  We will not sell or give out your e-mail address.  However, you understand that posting your
property and its details makes that information viewable by anyone in the world who has a web browser, with all the associated benefits and detriments.<br /><br />
After clicking "Submit," an e-mail will be sent to the address you specified above, which will give you the ability to manage your
posting.  Do not lose this e-mail or share it with people you do not trust.<br /><br />
Thank you!<br />
<a href="#" onclick="reset_and_home(); return false;" style="position: absolute; right: 4px;">Cancel</a>
<?php
if (Warden::getMGMT() == 'Yes') { echo "
<input type='submit' name='Modify' value='Submit' />"; }
else { echo "
<input type='hidden' name='Create' value='Create' />
<input type='button' name='Create' value='Create' onclick=\"processForm()\" />"; }
?>
</div>

<div class="insideClear"></div>

</div>
</div>
<?php include 'footer.php'; ?>
</form>
</body>
</html>
