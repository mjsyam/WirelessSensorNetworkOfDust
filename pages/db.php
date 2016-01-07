<?php
/**
 *
 * 
 * Sample input
 * 409f40e8 	2014-05-08 	11:22:34.844705 298.837158203 	209.039810181 	1.43536436558 	1.04167819023
 * sa y m d ho mi se Power Voltage Current Energy Temp Humidity Intensity
 */


// get the arguments
$sensor_ID	= $argv[1];
$year		= $argv[2];
$month		= $argv[3];
$day		= $argv[4];
$hour		= $argv[5];
$minute		= $argv[6];
$second		= $argv[7];
$watt		= number_format((float)$argv[8], 2, '.', '');
$voltage	= number_format((float)$argv[9], 2, '.', '');
$current	= number_format((float)$argv[10], 2, '.', '');
$energy		= number_format((float)$argv[11], 2, '.', '');
$temperature= number_format((float)$argv[12], 2, '.', '');
$humidity	= number_format((float)$argv[13], 2, '.', '');
$intensity	= number_format((float)$argv[14], 2, '.', '');

$datetime = $year.'-'.$month.'-'.$day.' '.$hour.':'.$minute.':'.$second;

// echo $date;
// echo $timestamp;
// connect to mysql
mysql_connect('localhost', 'root', '');
mysql_select_db('power_meter');

$query ="INSERT INTO history (sensor_ID, datetime, watt, voltage, current, energy, temperature, humidity, intensity)".
			"VALUES ('$sensor_ID', '$datetime', '$watt', '$voltage', '$current', '$energy', '$temperature', '$humidity', '$intensity')";

mysql_query($query);
//echo $query;
// =====================