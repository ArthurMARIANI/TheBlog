<?php
require_once '../Config/core.php';
require_once '../vendor/autoload.php';
include '../dispatcher.php';
include "../Config/core.php";

//$dispatcher = new Dispatcher($_GET['url']);
echo($_GET['url']);

//$dispatcher = new Dispatcher("/PHP_Rush_MVC/Writer/Read");
$dispatcher = new Dispatcher("/PHP_Rush_MVC/Home/Read");
//$dispatcher = new Dispatcher("/PHP_Rush_MVC/Article/Read/id=2");
