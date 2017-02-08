<?php

/**
* Transformar warning em exceptions
*/
error_reporting(E_ALL);
function exceptionThrower($type, $errMsg, $errFile, $errLine) {
	throw new Exception($errMsg);
}
set_error_handler('exceptionThrower');

/**
* adaptação para usar o storage_path do laravel
*/
function storage_path()
{
	return __DIR__;
}

require_once("Json.php");
require_once("Backup.php");

try {
  $content = file_get_contents("http://104.236.235.27/libreclass-backup/".$_GET["name"]);
  $return = file_put_contents("backups/".$_GET["name"], $content);
  $backup = new Backup;
  $backup->name  = $_GET["name"];
  $backup->size = $_GET["size"];
  $backup->runtime = $_GET["runtime"];
  $backup->created = date("Y-m-d h:i:s");
  $backup->save();
} catch (Exception $e) {
  echo $e->getMessage() . "\n\n";
}

require_once("gmail.php");
