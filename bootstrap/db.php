<?php

$dbc = new PDO('mysql:host=localhost;dbname=pings;charset=utf8', 'root', null);
$dbc->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$dbc->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
