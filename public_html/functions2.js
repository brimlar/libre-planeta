var XMLHttpRequestObject = false;
	
if (window.XMLHttpRequest) {
    XMLHttpRequestObject = new XMLHttpRequest();
} else if (window.ActiveXObject) {
    XMLHttpRequestObject = new ActiveXObject("Microsoft.XMLHTTP");
}

function showhideCalendar(date)
{
  if(XMLHttpRequestObject) {
    var obj4 = document.getElementById('calendar');
    XMLHttpRequestObject.open('GET', 'simplecal.php?day='+date);
    XMLHttpRequestObject.onreadystatechange = function()
    {
      if (XMLHttpRequestObject.readyState == 4 &&
        XMLHttpRequestObject.status == 200) {
          obj4.innerHTML = XMLHttpRequestObject.responseText;
			 obj4.style.display = "block";
      }
    }
    XMLHttpRequestObject.send(null);
}
}

function mapOverlay() {
	// figure out which document.link it is
	var numlinks = document.links.length;
	for (i=0;i<numlinks;i++) {
		if ((document.links[i].textContent == 'Map this property') || (document.links[i].innerText == 'Map this property')) {
			var leftpos = document.links[i].offsetLeft;
			var width = document.links[i].offsetWidth;
		}
	}
	//var leftpos = maplink.offsetLeft;
	//var width = maplink.offsetWidth;
	// this puts the right edge of the overlay near middle, plus 10 pixels to the right...would be 461 otherwise
	var newleft = (leftpos+width)-451;
	var mailtarget = document.getElementById('mailoverlaydiv');
	mailtarget.style.display = 'none';
	var maptarget = document.getElementById('mapoverlaydiv');
	maptarget.style.left = newleft+'px';
	maptarget.style.zIndex = (maptarget.style.zIndex == "3") ? "-1" : "3";
	/*maptarget.style.display = (maptarget.style.display == 'none') ? 'block' : 'none';*/
}

function mailOverlay() {
	//var bg = document.getElementById('darkbg');
	//bg.style.display = (bg.style.display == 'none') ? 'block' : 'none';
	var maptarget = document.getElementById('mapoverlaydiv');
	/*maptarget.style.display = 'none';*/
	maptarget.style.zIndex = "-1";
	var mailtarget = document.getElementById('mailoverlaydiv');
	mailtarget.style.display = (mailtarget.style.display == 'none') ? 'block' : 'none';
}

function inquire(translatedID)
{
	if(XMLHttpRequestObject) {
		var miniresultmsg = document.getElementById('miniresultmsg');
		var person = document.minimail.Sender.value;
		var email = document.minimail.FirstEmail.value;
		var secondemail = document.minimail.SecondEmail.value;
		var msg = document.minimail.Message.value;
		XMLHttpRequestObject.open('GET', 'minimailer3.php?mt=1&q='+translatedID+'&n='+person+'&s='+email+'&s2='+secondemail+'&b='+msg);
		XMLHttpRequestObject.onreadystatechange = function ()
			{
				if (XMLHttpRequestObject.readyState == 4 &&
					XMLHttpRequestObject.status == 200) {
						miniresultmsg.innerHTML = XMLHttpRequestObject.responseText;
						miniresultmsg.style.display = 'block';
					}
			}
		XMLHttpRequestObject.send(null);
}
}

function recoverEmail()
{
	if(XMLHttpRequestObject) {
		var resultmsg = document.getElementById('resultmsg');
		resultmsg.innerHTML = '<i>Processing...</i>';
		var recoveryemail = document.RecoveryForm.RecoveryEmailAddress.value;
		XMLHttpRequestObject.open('GET','minimailer3.php?mt=2&e='+recoveryemail);
		XMLHttpRequestObject.onreadystatechange = function ()
			{
				if (XMLHttpRequestObject.readyState == 4 &&
					XMLHttpRequestObject.status == 200) {
						resultmsg.innerHTML = XMLHttpRequestObject.responseText;
						resultmsg.style.display = 'block';
					}
			}
		XMLHttpRequestObject.send(null);
}
} 

function GrabDate(date) {
    var obj4 = document.getElementById('calendar');
    obj4.style.display = "none";
    var obj5 = document.getElementById('DateAvailable');
    obj5.value = date;
}

function toggleAgentZone()
{
	var AgentZoneDiv = document.getElementById('AgentZone');
	if (document.Form1.ShowAgentInfo[0].checked) { 
		AgentZoneDiv.style.display = 'block'; 
		document.Form1.HideEmail[0].disabled = true;
		document.Form1.HideEmail[1].checked = true;
		}
	else if (document.Form1.ShowAgentInfo[1].checked) { 
		AgentZoneDiv.style.display = 'none';
		document.Form1.AgentName.value = '';
		document.Form1.AgentPhone.value = '';
		document.Form1.AgentFax.value = '';
		document.Form1.CompName.value = '';
		document.Form1.CompAddress.value = '';
		document.Form1.CompWWW.value = '';
		document.Form1.HideEmail[0].disabled = false;
	}
}

function toggleRentalZone()
{
  var RentalZoneDiv = document.getElementById('RentalZone');
  var RentalPriceItemDiv = document.getElementById('RentalPriceItem');
  var SalePriceItemDiv = document.getElementById('SalePriceItem');
  if (document.Form1.RentOrSale[0].checked) {
	RentalPriceItemDiv.style.display = 'block';
	document.Form1.SalePrice.value = '';
	SalePriceItemDiv.style.display = 'none';
	RentalZoneDiv.style.display = 'block'; }
  if (document.Form1.RentOrSale[1].checked) {
	document.Form1.RentalPrice.value = '';
	RentalPriceItemDiv.style.display = 'none';
	SalePriceItemDiv.style.display = 'block';
	RentalZoneDiv.style.display = 'none'; }
  if (document.Form1.RentOrSale[2].checked) {
	RentalPriceItemDiv.style.display = 'block';
	SalePriceItemDiv.style.display = 'block';
	RentalZoneDiv.style.display = 'block'; }
}

function AddRentalPeriod()
{
	var RentOption2Div = document.getElementById('RentalPriceItem2');
	if (document.Form1.AnotherRentalPeriod.checked) {
		RentOption2Div.style.display = 'block';
	}
	else if (!document.Form1.AnotherRentalPeriod.checked) {
		RentOption2Div.style.display = 'none';
		document.Form1.RentalPrice2.value = '';
	}
}

function removeNotLand()
{
  var NotLandDiv = document.getElementById('notLand');
  if (document.Form1.TypeOfProperty[3].checked) { NotLandDiv.style.display = 'none'; }
  if (!document.Form1.TypeOfProperty[3].checked) { NotLandDiv.style.display = 'block'; }
}

function AddLease()
{
  var AnotherLeaseDiv = document.getElementById('Lease2');
  if (document.Form1.AnotherLease.checked) { AnotherLeaseDiv.style.display = 'block'; }
  if (!document.Form1.AnotherLease.checked) { AnotherLeaseDiv.style.display = 'none'; }
}

function toggleWhichPets()
{
	var WhichPetsDiv = document.getElementById('WhichPets');
	var WhichPetsArray = new Array('CatsAllowed','SmallDogsAllowed','BigDogsAllowed','FishAllowed','BirdsAllowed','CaseByCase');
	if (document.Form1.PetsAllowed[0].checked) {
		for (i=0;i<WhichPetsArray.length;i++) {
			document.Form1[WhichPetsArray[i]].checked = false; }
		WhichPetsDiv.style.display = 'none'; }
	if (document.Form1.PetsAllowed[1].checked) {
		WhichPetsDiv.style.display = 'block'; }
	if (document.Form1.PetsAllowed[2].checked) {
		for (i=0;i<WhichPetsArray.length;i++) {
			document.Form1[WhichPetsArray[i]].checked = true; }
		WhichPetsDiv.style.display = 'none'; }
}

function zeroOthers(item)
{
  if (item == 'Responsibilities') {
	var formArray = new Array('NoResponsibilities','ResponsibleLawn','ResponsibleLeavesBrush','ResponsibleSnow','ResponsibleGuttersDownspouts','ResponsibleSidewalk','ResponsibleMinorItems');
	}
  else if (item == 'UtilitiesIncluded') {
	var formArray = new Array ('NoUtilitiesIncluded','HeatIncluded','ElectricIncluded','WaterIncluded','GasIncluded'); 
	}
  else if (item == 'AppliancesIncluded') {
	var formArray = new Array ('NoAppliancesIncluded','RefrigeratorIncluded','MicrowaveIncluded','OvenIncluded','DishwasherIncluded','ClothesWasherIncluded','DryerIncluded'); 
	}
  for (i=1;i<formArray.length;i++) {
    if (document.Form1[formArray[0]].checked) {
		document.Form1[formArray[i]].disabled = true;
		document.Form1[formArray[i]].checked = false; 
		}
    if (!document.Form1[formArray[0]].checked) {
		document.Form1[formArray[i]].disabled = false; 
		}
  }
}

function toggleSecurityDeposit()
{
  if (document.Form1.SecurityDeposit[0].checked) {
	document.Form1.SecurityDepositAmount.disabled = false; }
  if (document.Form1.SecurityDeposit[1].checked) {
	document.Form1.SecurityDepositAmount.value = '';
	document.Form1.SecurityDepositAmount.disabled = true; }
}

function zeroParkingSpots()
{
  if (document.Form1.GarageOrParking[0].checked) {
	document.Form1.ParkingSpots[0].selected = true;
	document.Form1.GarageSpots[0].selected = true; }
  if (document.Form1.GarageOrParking[1].checked) {
	document.Form1.ParkingSpots[0].selected = true; }
  if (document.Form1.GarageOrParking[2].checked) {
	document.Form1.GarageSpots[0].selected = true; }
}

function togglePictureUploads()
{
  var picIndex = document.Form1.NumberOfPictures.selectedIndex;
  var TotalPictures = document.Form1.NumberOfPictures.options[picIndex].value;
  var PicLimit = document.Form1.piclimit.value;
  if (PicLimit == '') PicLimit = 12;
  var AddDiv = '';
  // Draw all the divs, but only display: block the ones up to TotalPictures, display: none the remainder
  for (i=4;i<=PicLimit;i++) {
  	var AdditionalPictureDiv = document.getElementById('PictureDiv'+i);
	if ((i<=TotalPictures) && (AdditionalPictureDiv.style.display == 'none')) {
	AddDiv = "\n<div class='formBoxLeft'>Picture #"+i+
		"\n<span class='small'>Select Picture #"+i+" and give a brief description of it<br />" +
		"\n<i>Note: Must be in jpg, png or bmp format, and less than 200kb in size.</i></span></div>" +
		"\n<div class='formBoxRight'>" +
		"\n<input type='file' name='Picture"+i+"' size='25' class='file' accept='images\jpeg' />&nbsp;&nbsp;<br />" +
		"\n<textarea name='Picture"+i+"Description' cols='40' rows='3' maxlength='100' class='PictureDivTextArea'>-- Put a short description here (optional) --</textarea>" +
		"\n</div>";
  		AdditionalPictureDiv.innerHTML = AddDiv;
  		AdditionalPictureDiv.style.display = 'block'; }
	else if (i>TotalPictures) {
		AdditionalPictureDiv.innerHTML = '';
		AdditionalPictureDiv.style.display = 'none'; }
	}
}

function showblankpic(divnumber)
{
	var blankpics = document.getElementById('PictureDiv'+divnumber).getElementsByTagName('img');
	blankpics[0].src = './pictures/upload.gif';
}

function select_all_HTML()
{
	document.Finishform.htmltext.focus();
	document.Finishform.htmltext.select();
}

function showHTML()
{
	var htmldiv = document.getElementById('htmlcode');
	var width = document.body.clientWidth;
	var leftpos = ((width/2) - 260);
	htmldiv.style.left = leftpos+'px';
	htmldiv.style.display = (htmldiv.style.display == 'none') ? 'block' : 'none';
}

function ShowUpload(number)
{
	var PicArea = document.getElementById('Picture'+number);
	if (document.Form1['PicCheck'+number].checked) {
		PicArea.style.display = 'block'; 
		PicArea.innerHTML = "<input type='file' name='Picture"+number+"' onchange='showblankpic("+number+")' size='25' class='file' />"; }
	else if (!document.Form1['PicCheck'+number].checked) {
		PicArea.style.display = 'none'; 
		PicArea.innerHTML = ''; 
		var blankpics = document.getElementById('PictureDiv'+number).getElementsByTagName('img');
		blankpics[0].src = imgsrc[number]; }
}

function reset_and_home()
{
	document.Form1.reset();
	window.location = "http://homeplaneta.com";
}

function processForm()
{
	var valid;
	if (validateClean()) GetGoogleMapLatLng();
}

function GetGoogleMapLatLng()
{
	// If Original Address is the same as Address (unchanged) return zeros
	if (document.Form1.OrigAddress.value != document.Form1.Address.value)
	{
		var geocoder = new google.maps.Geocoder();
		var address = document.Form1.Address.value;
		// had to replace all the newline characters
		address = address.replace(/(\r\n|\r|\n)/,",");
		geocoder.geocode( { 'address': address}, function(results, status) {
			if (status == google.maps.GeocoderStatus.OK) {
				passAlong((results[0].geometry.location.lat()),(results[0].geometry.location.lng()));
			}
			else {
            console.log("Geocoding failed: " + status);
         }
		});
	}
}

function passAlong(lati,longi)
{
	document.Form1.Latitude.value = lati;
	document.Form1.Longitude.value = longi;
	document.Form1.submit();
}

function validateClean()
{

  var valid = true;

	if (document.Form1.Address.value == '') 
    {
      alert ( "Please fill in the 'Address' box." );
      document.Form1.Address.focus();
      valid = false;
    }

    else if ((!document.Form1.TypeOfProperty[0].checked) && (!document.Form1.TypeOfProperty[1].checked) && (!document.Form1.TypeOfProperty[2].checked) && (!document.Form1.TypeOfProperty[3].checked))
    {
        alert ( "Please fill in the 'Property Type' box." );
        document.Form1.TypeOfProperty[0].focus();
        valid = false;
    }

    else if ((!document.Form1.RentOrSale[0].checked) && (!document.Form1.RentOrSale[1].checked) && (!document.Form1.RentOrSale[2].checked))
    {
        alert ( "Please fill in the 'Rent or Sale' box." );
        document.Form1.RentOrSale[0].focus();
        valid = false;
    }

    else if ((document.Form1.RentOrSale[0].checked) && (document.Form1.RentalPrice.value == ''))
    {
        alert ( "Please fill in the 'Monthly Price' field." );
        document.Form1.RentalPrice.focus();
        valid = false;
    }

    else if ((document.Form1.RentOrSale[1].checked) && (document.Form1.SalePrice.value == ''))
    {
        alert ( "Please fill in the 'Sale Price' field." );
        document.Form1.SalePrice.focus();
        valid = false;
    }

    else if ((document.Form1.RentOrSale[2].checked) && ((document.Form1.RentalPrice.value == '') || (document.Form1.SalePrice.value == '')))
    {
        alert ( "Please fill in both the 'Monthly Price' and 'Sale Price' fields,\nsince you are interested in both renting and selling." );
        document.Form1.RentalPrice.focus();
        valid = false;
    }
// IF RENTING - Utilities
    else if (((document.Form1.RentOrSale[0].checked) || (document.Form1.RentOrSale[2].checked)) && ((!document.Form1.NoUtilitiesIncluded.checked) && (!document.Form1.HeatIncluded.checked) && (!document.Form1.ElectricIncluded.checked) && (!document.Form1.WaterIncluded.checked) && (!document.Form1.GasIncluded.checked)))
    {
			alert("Please fill out the 'Utilities' section.");
			document.Form1.NoUtilitiesIncluded.focus();
			valid = false;
	 }
// IF RENTING - Pets
    else if (((document.Form1.RentOrSale[0].checked) || (document.Form1.RentOrSale[2].checked)) && ((!document.Form1.PetsAllowed[0].checked) && (!document.Form1.PetsAllowed[1].checked) && (!document.Form1.PetsAllowed[2].checked)))
    {
			if ((!document.Form1.CatsAllowed.checked) && (!document.Form1.SmallDogsAllowed.checked) && (!document.Form1.BigDogsAllowed.checked) && (!document.Form1.FishAllowed.checked) && (!document.Form1.BirdsAllowed.checked) && (!document.Form1.CaseByCase.checked)) { document.Form1.CaseByCase.checked = true; } 
			alert("Please fill out the 'Pets' section.");
			document.Form1.PetsAllowed[0].focus();
			valid = false;
	 }
// IF RENTING - Smoking
    else if (((document.Form1.RentOrSale[0].checked) || (document.Form1.RentOrSale[2].checked)) && ((!document.Form1.SmokingPermitted[0].checked) && (!document.Form1.SmokingPermitted[1].checked)))
    {
			alert("Please fill out the 'Smoking Permitted' section.");
			document.Form1.SmokingPermitted[0].focus();
			valid = false;
	 }
	 /*
// IF RENTING - Responsibilities
    else if (((document.Form1.RentOrSale[0].checked) || (document.Form1.RentOrSale[2].checked)) && ((!document.Form1.NoResponsibilities.checked) && (!document.Form1.ResponsibleLawn.checked) && (!document.Form1.ResponsibleLeavesBrush.checked) && (!document.Form1.ResponsibleSnow.checked) && (!document.Form1.ResponsibleGuttersDownspouts.checked) && (!document.Form1.ResponsibleSidewalk.checked) && (!document.Form1.ResponsibleMinorItems.checked)))
    {
			alert("Please fill out the 'Tenant\'s Responsibilities' section.");
			document.Form1.NoResponsibilities.focus();
			valid = false;
	 }
	 */
// IF RENTING - Credit History Check
    else if (((document.Form1.RentOrSale[0].checked) || (document.Form1.RentOrSale[2].checked)) && ((!document.Form1.CreditCheck[0].checked) && (!document.Form1.CreditCheck[1].checked)))
    {
			alert("Please fill out the 'Credit History Check' section.");
			document.Form1.CreditCheck[0].focus();
			valid = false;
	 }
// IF RENTING - Security Deposit
    else if (((document.Form1.RentOrSale[0].checked) || (document.Form1.RentOrSale[2].checked)) && ((!document.Form1.SecurityDeposit[0].checked) && (!document.Form1.SecurityDeposit[1].checked)))
    {
			alert("Please fill out the 'Security Deposit' section.");
			document.Form1.SecurityDeposit[0].focus();
			valid = false;
	 }
// IF RENTING - Security Deposit Value
    else if (((document.Form1.RentOrSale[0].checked) || (document.Form1.RentOrSale[2].checked)) && ((document.Form1.SecurityDeposit[0].checked) && (document.Form1.SecurityDepositAmount.value == '')))
    {
			alert("Please fill out the value for the 'Security Deposit' amount.");
			document.Form1.SecurityDepositAmount.focus();
			valid = false;
	 }
// IF RENTING - Washer and Dryer
    else if (((document.Form1.RentOrSale[0].checked) || (document.Form1.RentOrSale[2].checked)) && ((!document.Form1.WasherDryer[0].checked) && (!document.Form1.WasherDryer[1].checked) && (!document.Form1.WasherDryer[2].checked) && (!document.Form1.WasherDryer[3].checked)))
    {
			alert("Please fill out the 'Washer / Dryer' section.");
			document.Form1.WasherDryer[0].focus();
			valid = false;
	 }

    else if (document.Form1.DateAvailable.value == '')
    {
        alert ( "Please fill in the 'Date Available' field." );
        document.Form1.DateAvailable.focus();
        valid = false;
    }

    else if (document.Form1.Description.value == '')
    {
        alert ( "Please fill in the 'Description' field -- no matter how short." );
	     document.Form1.Description.focus();
        valid = false;
    }

    else if (((!document.Form1.NoAppliancesIncluded.checked) && (!document.Form1.RefrigeratorIncluded.checked) && (!document.Form1.MicrowaveIncluded.checked) && (!document.Form1.OvenIncluded.checked) && (!document.Form1.DishwasherIncluded.checked) && (!document.Form1.ClothesWasherIncluded.checked) && (!document.Form1.DryerIncluded.checked)) && (!document.Form1.TypeOfProperty[3].checked))
    {
        alert ( "Please mark a value in the 'Appliances Included' section." );
	     document.Form1.NoAppliancesIncluded.focus();
        valid = false;
    }

    else if ((document.Form1.Bedrooms.value == '') && (!document.Form1.TypeOfProperty[3].checked))
    {
        alert ( "Please select a value in the 'Bedrooms' section." );
	     document.Form1.Bedrooms.focus();
        valid = false;
    }

    else if ((document.Form1.Bathrooms.value == '') && (!document.Form1.TypeOfProperty[3].checked))
    {
        alert ( "Please select a value in the 'Bathrooms' section." );
	     document.Form1.Bathrooms.focus();
        valid = false;
    }

    else if (((!document.Form1.CentralAir[0].checked) && (!document.Form1.CentralAir[1].checked)) && (!document.Form1.TypeOfProperty[3].checked))
    {
        alert ( "Please mark an option in the 'Central Air' field." );
	     document.Form1.CentralAir[0].focus();
        valid = false;
    }

    else if (((!document.Form1.GarageOrParking[0].checked) && (!document.Form1.GarageOrParking[1].checked) && (!document.Form1.GarageOrParking[2].checked)) && (!document.Form1.TypeOfProperty[3].checked))
    {
        alert ( "Please fill out the 'Garage / parking space' section." );
	     document.Form1.GarageOrParking[0].focus();
        valid = false;
    }

    else if (((document.Form1.GarageOrParking[1].checked) && (document.Form1.GarageSpots.value == 0)) && (!document.Form1.TypeOfProperty[3].checked))
    {
        alert ( "Please select a non-zero value in the 'Garage Spots' field." );
	     document.Form1.GarageSpots.focus();
        valid = false;
    }

    else if (((document.Form1.GarageOrParking[2].checked) && (document.Form1.ParkingSpots.value == 0)) && (!document.Form1.TypeOfProperty[3].checked))
    {
        alert ( "Please select a non-zero value in the 'Parking Spots' field." );
        document.Form1.ParkingSpots.focus();
        valid = false;
    }

    else if (document.Form1.EmailAddress.value == '')
    {
        alert ( "Please enter a value in the 'E-mail Address' field." );
        document.Form1.EmailAddress.focus();
        valid = false;
    }

	else if ((document.Form1.ShowAgentInfo[0].checked) && (document.Form1.AgentName.value == ''))
	{
		alert ( "If you are opting to show agent information, a name is required in the 'Agent Name' field." );
		document.Form1.AgentName.focus();
		valid = false;
	}

	else if ((document.Form1.ShowAgentInfo[0].checked) && (document.Form1.AgentPhone.value == ''))
	{
		alert ( "If you are opting to show agent information, a phone number is required in the 'Agent's Phone Number' field." );
		document.Form1.AgentPhone.focus();
		valid = false;
	}

	else if ((!document.Form1.HideEmail[0].checked) && (!document.Form1.HideEmail[1].checked))
	{
		document.Form1.HideEmail[0].checked = 'true';
	}

// PICTURES
    else if (valid == true)
    {
			var NumPix = document.Form1.NumberOfPictures.value;
			for (i=1;i<=NumPix;i++) {
				if (document.Form1["Picture"+i]) {
					if (document.Form1["Picture"+i].value == '') {
        				alert ( "You are missing a picture upload in the 'Picture #"+i+"' field." );
						document.Form1["Picture"+i].focus();
        				valid = false; 
						break; }
					}
				}
    }

  return valid;
}
