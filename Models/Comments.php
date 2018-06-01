<?php

define("ERROR_LOG_FILE", "logFile.txt");


class Comments
{
    public $id;
    public $body;
    public $articleId;
    public $authorId;
    public $creationDate;
    public $comment = [];
    public function __construct($id = null)
    {
        if ($id !== null && self::is_comment($id)) {
            $this->id = $id;
            $this->comment = $this->get_comment_info();
            $this->body = $this->comment['body'];
            $this->articleId = $this->comment['article_id'];
            $this->authorId = $this->comment['author_id'];
            $this->creationDate = $this->comment['creation_date'];
        } elseif ($id !== null && !self::is_comment($id)) {
            return false;
        }
    }

    public function get_body()
    {
        return $this->body;
    }

    public function get_authorId()
    {
        return $this->authorId;
    }

    public function get_creationDate()
    {
        return $this->creationDate;
    }

    public function get_articleId()
    {
        return $this->articleId;
    }

    public function get_comments_from_article()
    {
        $query = "SELECT * FROM comments WHERE article_id = ?";
        $comments = Database::get_instance()->execute_query($query, array($this->articleId), true);
        return $comments;
    }

    public function get_comments_from_author()
    {
        $query = "SELECT * FROM comments WHERE author_id = ?";
        $comments = Database::get_instance()->execute_query($query, array($this->authorId), true);
        return $comments;
    }

    public function get_comment_info()
    {
        $query = "SELECT * FROM comments WHERE id = ?";
        $commentInfo = Database::get_instance()->execute_query($query, array($this->id), false);
        return $commentInfo;
    }

    public function update_comment($params)
    {
        foreach ($params as $key => $value) {
            if (!empty($value)) {
                $query = "UPDATE comments SET $key = ? WHERE id = ?";
                $arr = array($value, $this->id);
                Database::get_instance()->execute_query($query, $arr);
            }
        }
    }

    public function delete_article()
    {
        $query = "DELETE FROM comments WHERE id = ?";
        Database::get_instance()->execute_query($query, array($this->id));
    }

    public static function is_comment($id)
    {
        $query = "SELECT * FROM comments WHERE id = ?";
        if (Database::get_instance()->execute_query($query, array($id))) {
            return true;
        } else {
            return false;
        }
    }

    public static function create_comment($body, $authorId, $articleId)
    {
        $query = "INSERT INTO comments (body, author_id, article_date) VALUES (?, ?, ?)";
        Database::get_instance()->execute_query($query, array($body, $authorId, $articleId));
    }
}
