<?php
abstract class Listing
// Generic stuff that both subclasses will use
{
    // This date is used to give a friendly presentation of DateExpires
	public $friendlyexpiredate = '';

	static function Generate($object_type, $id)
	{
		if ($id == '')
		{
			return new $object_type;
		}
		else
		{
			require 'pdoconfig.php';
			$query = "SELECT * FROM ListingInfo WHERE TranslatedID = :translatedid";
			$resource = $connection->prepare($query);
			$resource->bindParam(':translatedid', $id);
			$resource->execute();
			$resource->setFetchMode(PDO::FETCH_CLASS, $object_type);
			return $resource->fetch(PDO::FETCH_CLASS);
		}
	}

    // Both DateExpires and DateAvailable will use this
	public function get_friendly_date($mysqldate)
	{
		$unixtime = strtotime($mysqldate);
		return date('F d, Y', $unixtime);
	}
}

class EditListing extends Listing
{
	public $today = '';

	// Date formatting, putting the MySQL date DateExpires into a friendly expiration string
	public function get_cal_date($mysqldate)
	{
		if ($mysqldate != '')
		{
			list($y,$m,$d) = explode("-",$mysqldate);
			return "$m/$d/$y";
		}
		else
		{
			// Return nothing if it's blank, i.e. this is a blank listproperty.php page
			return null;
		}
	}

	function __construct()
	{
		$this->today = date('m/d/Y');
	}
}

class PrettyListing extends Listing
{
	private $friendlysize = '';
	public $friendlyaddress = '';
	public $encodedaddress = '';
	public $propertyword = '';
	public $friendlydescription = '';

	function __construct()
	{
		$this->address_formatter();
		$this->property_word_setter();
		$this->description_nl2br_formatter();
		$this->determine_showads();
	}

	private function address_formatter()
	{
		// Called with construct b/c other functions need these variables on page load
		// This makes the Address field one line with commas instead of returns
		$this->friendlyaddress = str_replace("\r\n", ", ", $this->Address);
		$this->encodedaddress = urlencode($this->friendlyaddress);
		// This displays the Company Address, converting the submitted \n's to <br /> for display 
		if ($this->CompAddress != '')
		{
			$this->CompAddress = nl2br($this->CompAddress);
		}
	}

	private function property_word_setter()
	{
		if ($this->TypeOfProperty == 1)
		{
			$this->propertyword = 'House';
		}
		elseif ($this->TypeOfProperty == 2)
		{
			$this->propertyword = 'Apartment';
		}
		elseif ($this->TypeOfProperty == 3)
		{
			$this->propertyword = 'Condo';
		}
		elseif ($this->TypeOfProperty == 4)
		{
			$this->propertyword = 'Land';
		}

		return $this->propertyword;
	}

	private function description_nl2br_formatter()
	{
		$this->friendlydescription = nl2br($this->Description);
	}

	private function determine_showads()
	{
		if (($this->IsPaid != 1) && ($_SERVER['HTTPS'] != 'on') && ($_SERVER['REMOTE_ADDR'] != '67.20.88.103'))
		{
			$this->showads = 1;
		}
	}

	public function title_maker()
	{
		if ($this->Title == '')
		{
			// If no Title is present, use Address as the Title
			// Also, use $friendlyaddress to get rid of breaks, to make it all fit on one line
			return "<p>$this->friendlyaddress</p>";
		}
		else
		{
			return "<p>$this->Title</p>\n<span class=\"address\">$this->friendlyaddress</span>\n";
		}
	}

	public function size_maker()
	{
		//Remove decimal points from size if no remainder, and add thousands comma if bigger than 9999
		if (fmod($this->Size, 1) == 0) 
		{
			$this->friendlysize = floor($this->Size);
			if ($this->friendlysize > 9999) $this->friendlysize = number_format($this->friendlysize); 
		}
		else 
		{
			$this->friendlysize = number_format($this->Size,2);	
		}
		return $this->friendlysize . ' ' . $this->MeasurementType;
	}

	public function headerheight_maker()
	{
		$stylestring = '';

		if ($this->RentOrSale == 3)
		{
			$stylestring = 'style="height: 90px;"';
		}
		
		return $stylestring;
	}

	public function saleprice_maker()
	{
		if ($this->RentOrSale == 1)
		{
			return null;
		}
		elseif ($this->RentOrSale == 2)
		{
			return 'Listed for '.$this->SalePrice;
		}
		elseif ($this->RentOrSale == 3)
		{
			return '<br />or purchase, '.$this->SalePrice;
		}
	}

	public function rent_maker()
	{
		$rentalstring1 = '';
		$rentalstring2 = '';

		if (($this->RentOrSale == 1) || ($this->RentOrSale == 3))
		{
			$rentalstring1 = $this->RentalPrice.' / '.$this->RentalPeriod;

			if ($this->AnotherRentalPeriod == 1)
			{
				$rentalstring2 = ', or '.$this->RentalPrice2.' / '.$this->RentalPeriod2;
			}

			return $rentalstring1.$rentalstring2;
		}
		else
		{
			return null;
		}
	}
	
	public function utilities_maker()
	{
		$utilities_string = '';
		$counter = 0;
		$utilities_array = array('heat' => $this->HeatIncluded,
								 'electric' => $this->ElectricIncluded,
								 'water' => $this->WaterIncluded,
								 'gas' => $this->GasIncluded);

		// If some utilities are included, test array for 1's
		if ($this->NoUtilitiesIncluded == 0)
		{
			foreach ($utilities_array as $key => $value)
			{
				if ($value == 1)
				{
					$utilities_string .= $key . " / ";
					$counter++;
				}
			}
		}

		// If all items were 1's, 'all utilities included' 
		if ($counter == count($utilities_array))
		{
			$utilities_string = 'Yes';
		}
		elseif (($counter >= 1) && ($counter < count($utilities_array)))
		{
			// Strip off last space and comma for pleasant presentation
			$utilities_string = ucfirst(substr($utilities_string, 0, -2));
		}

		// Else, if no utilities are included, set $utilities_string to '+ utilities' b/c the price will not include utilities
		elseif ($this->NoUtilitiesIncluded == 1)
		{
			$utilities_string = 'No';
		}

		return $utilities_string;
	}

	public function appliance_maker()
	{
		// Create a blank string, put the values into a key/value array, check the array for positives and build the string
		$appliance_string = '';

		if ($this->NoAppliancesIncluded == 1)
		{
			$appliance_string = "No appliances included";
		}
		else
		{
			$appliance_array = array('refrigerator' => $this->RefrigeratorIncluded, 
									 'microwave' => $this->MicrowaveIncluded, 
									 'oven' => $this->OvenIncluded, 
									 'dishwasher' => $this->DishwasherIncluded, 
									 'clothes washer' => $this->ClothesWasherIncluded, 
									 'dryer' => $this->DryerIncluded);

			foreach ($appliance_array as $key => $value)
			{
				if ($value == 1)
				{
					// Each positive entry will have the name plus a comma -- we'll strip out the last comma and space when we finish up
					$appliance_string .= $key . ", ";
				}
			}

			if (empty($appliance_string))
			{
				$appliance_string = "No appliances included";
			}
			else
			{
				// Take off the last comma, last space and capitalize the first letter
				$appliance_string = ucfirst(substr($appliance_string, 0, -2));
			}
		}

		return $appliance_string;
	}

	public function additional_items_maker() 
	{
		$amenities_string = '';
		// Replace the double-pluses with li tags
		$amenities_string = "<li>" . str_replace('++', "</li>\n<li>", $this->OtherAmenities) . "</li>";
		return $amenities_string;
	}

	public function washerdryer_maker()
	{
		$washerdryer_string = '';
		if ($this->WasherDryer == 0)
		{
			$washerdryer_string = "No washer or dryer on premises";
		}
		elseif ($this->WasherDryer == 1)
		{
			$washerdryer_string = "In-unit";
		}
		elseif ($this->WasherDryer == 2)
		{
			$washerdryer_string = "(Free) in building";
		}
		elseif ($this->WasherDryer == 3)
		{
			$washerdryer_string = "(Cash/coin) in building";
		}
		return $washerdryer_string;
	}

	public function parking_maker()
	{
		$parking_string = '';
		if ($this->GarageOrParking == 0)
		{
			$parking_string = "No parking included";
		}
		elseif ($this->GarageOrParking == 1)
		{
			$parking_string = $this->GarageSpots . "-car garage";
		}
		elseif ($this->GarageOrParking == 2)
		{
			$parking_string = "spots for $this->ParkingSpots cars";
		}
		return $parking_string;	
	}

	public function lease_maker()
	{
		if ($this->AnotherLease == 1)
		{
			return "$this->LeaseNumber-$this->LeaseTime or $this->LeaseNumber2-$this->LeaseTime2";
		}
		else
		{
			return "$this->LeaseNumber-$this->LeaseTime";
		}
	}

	public function show_responsibilities()
	{
		$allrespitems = array($this->NoResponsibilities,
							  $this->ResponsibleLawn,
							  $this->ResponsibleLeavesBrush,
							  $this->ResponsibleSnow,
							  $this->ResponsibleGuttersDownspouts,
							  $this->ResponsibleSidewalk,
							  $this->ResponsibleMinorItems);
		if (in_array(1, $allrespitems))
		{
			return True;
		}
		else
		{
			return False;
		}
	}

	public function responsibilities_maker()
	{
		$responsibilities_string = '';

		if ($this->NoResponsibilities == 1)
		{
			$responsibilities_string = "Nothing; owner provides all maintenance";
		}
		else
		{
			$responsibilities_array = array('lawn' => $this->ResponsibleLawn,
											'leaves/brush' => $this->ResponsibleLeavesBrush,
											'snow' => $this->ResponsibleSnow,
											'gutters and downspouts' => $this->ResponsibleGuttersDownspouts,
											'sidewalk' => $this->ResponsibleSidewalk,
											'minor items (light bulbs, drain clogs, etc)' => $this->ResponsibleMinorItems);
			foreach ($responsibilities_array as $key => $value)
			{
				if ($value == 1)
				{
					$responsibilities_string .= $key . ', ';
				}
			}
			$responsibilities_string = ucfirst(substr($responsibilities_string, 0, -2));
		}

		return $responsibilities_string; 
	}

	public function pet_maker()
	{
		$pet_string = '';
		$pet_string2 = '';

		if ($this->PetsAllowed == 0)
		{
			$pet_string = "No pets allowed";
		}
		elseif ($this->PetsAllowed == 1)
		{
			$pet_string = "Some pets allowed --";

			$pet_array = array('cats' => $this->CatsAllowed,
							   'small dogs' => $this->SmallDogsAllowed,
							   'big dogs' => $this->BigDogsAllowed,
							   'fish' => $this->FishAllowed,
							   'birds' => $this->BirdsAllowed,
							   'inquire / case-by-case basis' => $this->CaseByCase);
			
			foreach ($pet_array as $key => $value)
			{
				if ($value == 1)
				{
					$pet_string2 .= $key . ', ';
				}
			}

			$pet_string = "$pet_string\n<ul><li>" . ucfirst(substr($pet_string2, 0, -2)) . "</li></ul>";
		}
		elseif ($this->PetsAllowed == 2)
		{
			$pet_string = "Pet-friendly (pets allowed)";
		}

		return $pet_string;
	}

	function picturediv_maker($i)	
	{
		$divhtml = '';
		$widthstring = 'Picture' . $i . 'Width';
		$heightstring = 'Picture' . $i . 'Height';
		$descstring = 'Picture' . $i . 'Description';
		$namestring = 'Picture' . $i . 'Name';

		// Set p attributes based upon the size of the picture, and if no description exists
		// First, get width based upon a height of 396 pixels
		$pwidth = (($this->$widthstring * 396) / $this->$heightstring);

		// If picture's width is less than 680 AND height is less than 396, it won't get touched by the resize and so we will just use its native value
		if (($this->$widthstring  < 680) && ($this->$heightstring < 396)) 
		{
			$pwidth = $this->$widthstring;
		}

		// If picture width is greather than 680 set the width to 680.  This may scrunch a really wide photo but "oh well"
		if ($pwidth > 680) 
		{
			$pwidth = 680;
		}

		// Figure out the dynamic left and width values so we can align the transparent p div description right on target
		// We need to subtract a few pixels to compensate for CSS stuff
		$pleft = round(((732 - $pwidth) / 2), 2);
		$pwidth = round(($pwidth - 4), 2);
		if ($this->$descstring == '') 
		{
			$pvisibilitystring = "display: none; ";
		}

		$divhtml = "\n<div class='pictureContainer'>\n<img src='pictures/".$this->TranslatedID.'/'.$this->$namestring."' />\n<p class='number' style='left: ".$pleft."px;' >Picture ".$i." of ".$this->NumberOfPictures."</p>\n<p style='".$pvisibilitystring."width: ".$pwidth."px; left: ".$pleft."px;'>".$this->$descstring."</p>\n</div>\n";

		return $divhtml;
	}
}
