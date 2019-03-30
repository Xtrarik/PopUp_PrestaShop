<?php
include(dirname(__FILE__).'/../../../config/config.inc.php');
include(dirname(__FILE__).'/../../../init.php');


$obj_mp = ModuleCore::getInstanceByName('wepika');

$arr = $obj_mp->getAllOrderDetails();
echo json_encode($arr);