<?php

class Router
{
    private $url;
    private $routes = [];
    private $route;
    public $action;
    public $params = [];
    public $method;

    public function __construct($url)
    {
        //requested URL;
        $this->url = $this->clean_url($url);
        //Definition of all existing routes
        $this->routes['categories'] = "CategoriesController.php";
        $this->routes['login'] = "LogInController.php";
        $this->routes['subscription'] = "SubscribeController.php";
        $this->routes[''] = "HomeController.php";
        $this->routes['home'] = "HomeController.php";
        $this->routes['article'] = "ArticleController.php";
        $this->routes['write'] = "WriterController.php";
        //Creation of array with all info of the URL
        $urlArr = explode('/', $this->url);
        //Deletion of PHP_Rush_MVC ou index.php
        array_shift($urlArr);
        //Extraction of Route requested
        $this->route = ucfirst(array_shift($urlArr));
        //Extraction of Action requested
        $this->action = ucfirst(array_shift($urlArr));
        //Extraction of Class requested + put first letter to upper case
        if (!empty($urlArr)) {
            $this->params = $this->arr_param($urlArr);
        }
    }

    private function strip_accents($url)
    {
        return strtr(utf8_decode($url), utf8_decode('àáâãäçèéêëìíîïñòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ'), 'aaaaaceeeeiiiinooooouuuuyyAAAAACEEEEIIIINOOOOOUUUUY');
    }

    private function clean_url($url)
    {
        //cleaning of URL (delete unnecessary character such as \0)
        $url = trim($url, '/');
        //remove accents from URL
        $url = $this->strip_accents($url);
        //put the whole url in lower case
        $url = strtolower($url);
        return $url;
    }

    private function arr_param($urlArr)
    {
        $paramArr = [];
        foreach ($urlArr as $item) {
            $arr = explode("=", $item);
            $paramArr[$arr[0]] = $arr[1];
        }
        return $paramArr;
    }

    //get the route requested -> vraiment nécessaire?
    public function get_route()
    {
        if ($this->is_route()) {
            return $this->routes[$this->route];
        } else {
            return false;
        }
    }

    //get requested action
    public function get_action()
    {
        return $this->action;
    }

    //get requested parameters
    public function get_params()
    {
        return $this->params;
    }

    //Check if the route exists
    public function is_route()
    {
        if (!array_key_exists($this->route, $this->routes)) {
            return false;
        } else {
            return true;
        }
    }

    //Define the method to be used according to the action and class requested and check if it exist
    public function get_method()
    {
        switch ($this->action) {
            case "Create":
                if (!empty($this->params)) {
                    $this->method = ucfirst($this->route) . "Controller::create_" . strtolower($this->route);
                    if (is_callable($this->method)) {
                        return $this->method;
                    } else {
                        return false;
                    }
                } else {
                    return false;
                }

                break;
            case "Read":

                if (!empty($this->params)) {
                    $this->method = ucfirst($this->route) . "Controller::read_" . strtolower($this->route);
                    if (is_callable($this->method)) {
                        return $this->method;
                    } else {
                        return false;
                    }
                } else {
                    $this->method = ucfirst($this->route) . "Controller::read_all";
                    if (is_callable($this->method)) {
                        return $this->method;
                    } else {
                        return false;
                    }
                }
                break;
            case "Update":
                if (!empty($this->params)) {
                    $this->method = ucfirst($this->route) . "Controller::update_" . strtolower($this->class);
                    if (is_callable($this->method)) {
                        return $this->method;
                    } else {
                        return false;
                    }
                } else {
                    return false;
                }
                break;
            case "Delete":
                if (!empty($this->params)) {
                    $this->method = ucfirst($this->route) . "::delete_" . strtolower($this->class);
                    if (is_callable($this->method)) {
                        return $this->method;
                    } else {
                        return false;
                    }
                    break;
                }
        }
    }
}
