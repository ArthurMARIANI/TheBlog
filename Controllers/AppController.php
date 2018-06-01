<?php

include "../Config/core.php";


class AppController
{
    public static function loadModel($model)
    {
        return($model)(Database::get_instance()->get_db());
    }

    public function beforeRender()
    {
    }

    public static function render($file = null, $content)
    {
        $loader = new Twig_Loader_Filesystem(__ROOT__ . '/Views');
        $twig = new Twig_Environment($loader, []);
        echo $twig->render($file, $content);
    }



    public function redirect($param)
    {
        //– Redirects a user from a method of the router to another method of the router. – $param is an array with the URL of the route. – You have to use Dispatcher class.
    }
}
