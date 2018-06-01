<?php
require_once "Controllers/HomeController.php";
require_once "Controllers/UsersController.php";
require_once "Controllers/ArticleController.php";
require_once "Controllers/WriterController.php";


require_once "Src/router.php";
include 'Config/core.php';



class Dispatcher
{
    private $router;
    public $controllerAction;
    private $params;

    public function __construct($url)
    {
        $this->router = new Router($url);
        $this->controllerAction = $this->router->get_method();
        $this->params = $this->router->get_params();
        ($this->controllerAction)($this->params);
    }
}
