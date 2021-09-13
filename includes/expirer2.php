<?php

require 'pdoconfig.php';
require 'Warden.class.php';
require 'Mailmanager.class.php';

$properties_deleted = Warden::expire_listings('return_properties_deleted');

$mail1 = new DailyReportEmail($properties_deleted);
$mail1->send_it();
?>
