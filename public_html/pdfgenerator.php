<?php
if ((strlen($_GET['q']) <= 9) || (is_numeric($_GET['q'])))
{
	$translatedid = $_GET['q'];
}

$internalpdfname = '../public_html/pictures/'.$translatedid.'/'.$translatedid.'.pdf';

exec('../includes/wkhtmltopdf-amd64 --default-header --header-spacing 6 --disable-javascript --allow "../public_html/fonts/Fontin_Sans_SC_45b.ttf" --page-size Letter --user-style-sheet "../public_html/pdf.css" '."http://homeplaneta.com/$translatedid $internalpdfname");

$externalpdfname = 'Homeplaneta-'.$translatedid.'.pdf';

header('Content-type: application/pdf'); 
header('Content-disposition: attachment; filename=' . $externalpdfname); 
readfile($internalpdfname); 
