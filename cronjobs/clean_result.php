<?php

$db = eZDB::instance();
$today = time();
#60 seconds * 60 minutes = 3600
$max_createtime = $today - 3600;

$db->begin();
$db->arrayQuery("DELETE FROM xrowcaptcha_result WHERE createtime < $max_createtime");
$db->commit();

?>