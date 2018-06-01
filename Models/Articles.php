<?php

//define("ERROR_LOG_FILE", "logFile.txt");
include '../Config/core.php';
require_once "../Config/db.php";
require_once "Tags.php";
require_once "Users.php";
require_once "Categories.php";
require_once "../parsedown-1.7.1/Parsedown.php";

class Articles
{
    private $db;
    public $id;
    public $title;
    public $body;
    public $tags = [];
    public $categoryId;
    public $creationDate;
    public $editionDate;
    public $article = [];

    public function __construct($id = null)
    {
        if ($id !== null && self::is_article($id)) {
            $this->id = $id;
            $this->article = $this->get_article_info();
            $this->title = $this->article['title'];
            $this->body = $this->article['body'];
            $this->categoryId = $this->article['category_id'];
            $this->creationDate = $this->article['creation_date'];
            $this->editionDate = $this->article['edition_date'];
            $this->tags = $this->get_tags();
        } elseif ($id !== null && !self::is_article($id)) {
            return false;
        }
    }

    public function get_body()
    {
        return $this->body;
    }

    public function get_categoryId()
    {
        return $this->categoryId;
    }

    public function get_creationDate()
    {
        return $this->creationDate;
    }

    public function get_tags()
    {
        $query = "SELECT tag_id FROM article_tag WHERE article_id = ?";
        $this->tags = Database::get_instance()->execute_query($query, array($this->id), true);
        return $this->tags;
    }

    public function get_editionDate()
    {
        return $this->editionDate;
    }

    public function get_title()
    {
        return $this->title;
    }

    public function get_article_info()
    {
        $Parsedown = new Parsedown();
        $query = "SELECT * FROM articles WHERE id = ?";
        $articleInfo = Database::get_instance()->execute_query($query, array($this->id), false);
        $articleInfo["body"] = $Parsedown->text($articleInfo["body"]);
        return $articleInfo;
    }

    public function get_comments()
    {
        $query = "SELECT * FROM comments WHERE article_id = ?";
        $comments = Database::get_instance()->execute_query($query, array($this->id), true);
        return $comments;
    }

    public function update_article($params)
    {
        foreach ($params as $key => $value) {
            if (!empty($value)) {
                $query = "UPDATE articles SET $key = ?, edition_date = NOW() WHERE id = ?";
                $arr = array($value, $this->id);
                Database::get_instance()->execute_query($query, $arr);
            }
        }
    }

    public function delete_article()
    {
        $query = "DELETE FROM articles WHERE id = ?";
        Database::get_instance()->execute_query($query, array($this->id));
        $query = "DELETE FROM article_tag WHERE id = ?";
        Database::get_instance()->execute_query($query, array($this->id));
    }

    public function delete_tags($tags)
    {
        foreach ($tags as $item) {
            $query = "DELETE FROM article_tag WHERE article_id = ? AND tag_id = ?";
            Database::get_instance()->execute_query($query, array($this->id, $item));
        }
    }

    public static function is_article($id)
    {
        $query = "SELECT * FROM articles WHERE id = ?";
        if (Database::get_instance()->execute_query($query, array($id))) {
            return true;
        } else {
            return false;
        }
    }

    public static function create_article($title, $body, $categoryId)
    {
        $query = "INSERT INTO articles (title, body, category_id, edition_date) VALUES (?, ?, ?, NOW())";
        Database::get_instance()->execute_query($query, array($title, $body, $categoryId));
    }

    public static function get_articles()
    {
        $articles = [];
        $arr = [];
        $query = "SELECT id FROM articles ";
        $ids = Database::get_instance()->execute_query($query, $arr, true);
        //var_dump($ids);
        foreach ($ids as $itemId) {
            $articleObject = new Articles($itemId["id"]);
            $article = $articleObject->get_article_info();
            $author = new Users($article['author_id']);
            $article['author'] = $author->get_name();
            $category = new Categories($article['category_id']);
            $article['category'] = $category->get_name();
            $article['tags'] = [];
            unset($article['category_id']);
            unset($article['author_id']);
            $query = "SELECT tag_id FROM article_tag WHERE article_id = ?";
            $tags = Database::get_instance()->execute_query($query, array($itemId["id"]), true);
            foreach ($tags as $item) {
                $tag = new Tags($item["tag_id"]);
                $article['tags'][] = $tag->name;
            }
            $articles[] = $article;
        }
        return $articles;
    }

    public static function get_id($title)
    {
        $query = "SELECT id FROM articles WHERE title = ?";
        $id = Database::get_instance()->execute_query($query, array($title), false);
        return $id;
    }
}
