<?php

$dp = date_parse(date('Y-m-d H:i:s'));
$pickup_status_stemp = mktime($dp['hour'], $dp['minute'], $dp['second'], $dp['month'], $dp['day'], $dp['year']) + 300;

echo $pickup_status_stemp. PHP_EOL;
echo time();