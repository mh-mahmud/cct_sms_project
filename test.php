<?php


$date = new DateTime();
$timeZone = $date->getTimezone();
echo $timeZone->getName();