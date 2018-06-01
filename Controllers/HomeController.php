<?php

#________________________________________________________________________________________


require_once 'AppController.php';
require_once '../Models/Articles.php';


#________________________________________________________________________________________

class HomeController extends AppController
{
    public static function read_all()
    {
        self::render(
            'articles.twig',
            array(
            "articles"=>Articles::get_articles(),
            "tags"=>Tags::get_tags(),
            "categories"=>Categories::get_categories()
        )

        );
    }
}
