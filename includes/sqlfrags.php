<?php
// Select one value

class ColumnFetcher
{
	private $columnstring = '';
	private $columnvalue = '';
	private $translatedid = '';

	function __construct($columnstring, $translatedid)
	{
		// security check on $translatedid?
		$this->translatedid = $translatedid
		$this->columnstring = $columnstring;
		require 'pdoconfig.php';
		$query = "SELECT $this->columnvalue FROM ListingInfo WHERE TranslatedID = :translatedid";
		$resource = $connection->prepare($this->query);
		$resource->bindParam(':translatedid', $translatedid);
		$resource->execute();
		$row = $resource->fetch(PDO::FETCH_ASSOC);
		$this->columnvalue = $row["$this->columnstring"];
	}

	public function showColumnValue()
	{
		return $this->columnvalue;
	}
}

$emailquery = "SELECT EmailAddress FROM MainTable WHERE TranslatedID = $translatedID";
$query = "SELECT IsPublic FROM ListingInfo WHERE TranslatedID = :translatedID";
$query = "SELECT TranslatedID FROM ListingInfo WHERE CURDATE() > DateExpires";
$query = "SELECT ManagementID FROM ListingInfo WHERE TranslatedID = :translatedID";
$new_listings_query = "SELECT TranslatedID FROM ListingInfo WHERE DATE_SUB(CURDATE(), INTERVAL 1 DAY) = DATE(DateCreated)";

// Select a few values
$query = "SELECT TranslatedID, ManagementID, Address FROM ListingInfo WHERE EmailAddress = :emailaddress";
$newquery = "SELECT Picture0Name, Picture1Name, Picture2Name, Picture3Name, Picture4Name, Picture5Name, Picture6Name, Picture7Name, Picture8Name, Picture9Name, Picture10Name, Picture11Name, Picture12Name, Picture13Name, Picture14Name, Picture15Name, Picture16Name, Picture17Name, Picture18Name FROM ListingInfo WHERE TranslatedID = :tid";

// Select all values
$query = "SELECT * FROM ListingInfo WHERE TranslatedID = :translatedID";
$query = "SELECT * FROM ListingInfo WHERE TranslatedID = :translatedID";

// Update values
$bigquery = 'UPDATE ListingInfo SET ' . $queryfragment . ' WHERE TranslatedID=:tid'; 
$query = "UPDATE ListingInfo SET IsPublic='1' WHERE TranslatedID = :translatedid";

// Create row / insert values
$query = "INSERT INTO ListingInfo (AutoID, TranslatedID, ManagementID, EmailAddress, HideEmail, ShowAgentInfo, AgentName, AgentPhone, AgentFax, CompName, CompAddress, CompWWW, Title, Address, Latitude, Longitude, TypeOfProperty, RentOrSale, RentalPrice, RentalPeriod, AnotherRentalPeriod, RentalPrice2, RentalPeriod2, SalePrice, LeaseNumber, LeaseTime, AnotherLease, LeaseNumber2, LeaseTime2, NoUtilitiesIncluded, HeatIncluded, ElectricIncluded, WaterIncluded, GasIncluded, PetsAllowed, CatsAllowed, SmallDogsAllowed, BigDogsAllowed, FishAllowed, BirdsAllowed, CaseByCase, SmokingPermitted, NoResponsibilities, ResponsibleLawn, ResponsibleLeavesBrush, ResponsibleSnow, ResponsibleGuttersDownspouts, ResponsibleSidewalk, ResponsibleMinorItems, CreditCheck, SecurityDeposit, SecurityDepositAmount, WasherDryer, DateAvailable, Description, Neighborhood, Size, MeasurementType, NoAppliancesIncluded, RefrigeratorIncluded, MicrowaveIncluded, OvenIncluded, DishwasherIncluded, ClothesWasherIncluded, DryerIncluded, Bedrooms, Bathrooms, CentralAir, GarageOrParking, GarageSpots, ParkingSpots, OtherAmenities, NumberOfPictures, $picheaderstring, DateExpires, IsPublic, IsPaid) VALUES ('NULL', :autoid, :translatedid, :managementid, :emailaddress, :hideemail, :showagentinfo, :agentname, :agentphone, :agentfax, :compname, :compaddress, :compwww, :title, :address, :latitude, :longitude, :typeofproperty, :rentorsale, :rentalprice, :rentalperiod, :anotherrentalperiod, :rentalprice2, :rentalperiod2, :saleprice, :leasenumber, :leasetime, :anotherlease, :leasenumber2, :leasetime2, :noutilitiesincluded, :heatincluded, :electricincluded, :waterincluded, :gasincluded, :petsallowed, :catsallowed, :smalldogsallowed, :bigdogsallowed, :fishallowed, :birdsallowed, :casebycase, :smokingpermitted, :noresponsibilities, :responsiblelawn, :responsibleleavesbrush, :responsiblesnow, :responsibleguttersdownspouts, :responsiblesidewalk, :responsibleminoritems, :creditcheck, :securitydeposit, :securitydepositamount, :washerdryer, :dateavailable, :description, :neighborhood, :size, :measurementtype, :noappliancesincluded, :refrigeratorincluded, :microwaveincluded, :ovenincluded, :dishwasherincluded, :clotheswasherincluded, :dryerincluded, :bedrooms, :bathrooms, :centralair, :garageorparking, :garagespots, :parkingspots, :otheramenities, :numberofpictures, $picvariablestring, :dateexpires, :ispublic, :ispaid)";

// Delete all values
$del_query = "DELETE FROM ListingInfo WHERE TranslatedID = $to_be_deletedID";
$query = "DELETE FROM ListingInfo WHERE TranslatedID = :translatedid";
