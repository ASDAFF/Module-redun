<?php
CModule::IncludeModule("redun");
global $DBType;

$arClasses=array(
    'cMainredun'=>'classes/general/cMainredun.php'
);

CModule::AddAutoloadClasses("redun",$arClasses);
