<?php

if (preg_match("/[0-9]{2}\/[0-9]{2}\/[0-9]{4}/", $_GET['day'])) {
	$fetched_date = $_GET['day'];
	}
else {
	echo "Bad date";
	exit();
	}

$today_mdY = date("m/d/Y");
list($month, $day, $year) = explode("/", $fetched_date);
$unix_fetched = strtotime($fetched_date);
$friendlymonth = date("F", $unix_fetched);

# Get unix timestamps of the startdate and enddate of the month
$unix_firstday = mktime(0, 0, 0, $month, 1, $year);
$unix_lastday = mktime(0, 0, 0, $month+1, 0, $year);

# Start calculating the numbers from the timestamps
$numeric_firstday = date("w", $unix_firstday);
$numeric_lastday = date("j", $unix_lastday);
$raw_total_days = ($numeric_firstday + $numeric_lastday);
$raw_weeks = ceil($raw_total_days / 7);

# We'll use $counter to iterate below, and calculate previous month and next month
$counter = -$numeric_firstday;
$previous_month_unix = mktime(1, 1, 1, $month-1, 1, $year);
$previous_month = date('m/d/Y', $previous_month_unix);
$next_month_unix = mktime(1, 1, 1, $month+1, 1, $year);
$next_month = date('m/d/Y', $next_month_unix);
?>

<table id="minical2" border="1" cellpadding="3" cellspacing="0" bordercolor="#000000">

<tr>
<td colspan="1" class="arrows"><?php echo "<a href='#' onclick='showhideCalendar(\"$previous_month\");return false;'><<</a>"; ?></td>
<td colspan="5" class="monthtitle"><?php echo $friendlymonth , " " , $year; ?></td>
<td colspan="1" class="arrows"><?php echo "<a href='#' onclick='showhideCalendar(\"$next_month\");return false;'>>></a>"; ?></td>
</tr>

<tr>
<td class="daytitle">Su</td>
<td class="daytitle">M</td>
<td class="daytitle">Tu</td>
<td class="daytitle">W</td>
<td class="daytitle">Th</td>
<td class="daytitle">F</td>
<td class="daytitle">Sa</td>
</tr>

<?php
for ($w=1;$w<=$raw_weeks;$w++)
{
	echo "<tr>";
	for ($d=0;$d<7;$d++)
	{
		$counter++;
		if (($counter < 1) || ($counter > $numeric_lastday))
		{
			echo "\n<td class=\"fakeday\"></td>";
		}
		else
		{
			$counter_with_leading_zero = sprintf("%02d", $counter);
			$longdate = $month."/".$counter_with_leading_zero."/".$year;
			if ($longdate == $today_mdY)
			{
				echo "<td class=\"today\"><a href='#' onclick='GrabDate(\"$longdate\");return false;'>$counter</a></td>";
			}
			else
			{
				echo "\n<td class=\"realday\"><a href='#' onclick='GrabDate(\"$longdate\");return false;'>$counter</a></td>";
			}
		}
	}
	echo "</tr>";
}

echo "</table>";

?>
