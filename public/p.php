<?php

require_once dirname( dirname(__FILE__))."/libs/Utils.php";
require_once dirname( dirname(__FILE__))."/libs/ConfigClass.php";
require_once dirname( dirname(__FILE__))."/libs/CustomErrorLog.php";

$e = new CustomErrorLog();



$module[0] = "pp";


$route = explode( "/", ConfigClass::get("config.routes.GET")[$module[0]]);

debug( $route);