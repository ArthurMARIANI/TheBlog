<?php

#________________________________________________________________________________________


require_once 'AppController.php';
require_once '../Models/Articles.php';


#________________________________________________________________________________________

class ArticleController extends AppController
{
    public static function read_article($param)
    {
        $article = new Articles();
        $article->id = $param["id"];
        self::render('article.twig', array_merge(
        $article->get_article_info(),
            $article->get_comments()
        ));
    }
}
