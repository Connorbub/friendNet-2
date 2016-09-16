<?php

$db_date = "2015-6-30";
$today = date("Y-m-d");
echo $today;

if ($today > $db_date) {
	echo "Offline";
} else {
	echo "Online";
}