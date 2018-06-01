<?php
//define("ERROR_LOG_FILE", "logFile.txt");

class Tags
{
    public $id;
    public $name;
    public $tag = [];
    public function __construct($id = null)
    {
        if ($id !== null && self::is_tag($id)) {
            $this->id = $id;
            $this->tag = $this->get_tag_info();
            $this->name = $this->tag['name'];
        } elseif ($id !== null && !self::is_tag($id)) {
            return false;
        }
    }

    public function get_name()
    {
        return $this->name;
    }

    public function get_articles_from_tag()
    {
        $query = "SELECT article_id FROM article_tag WHERE tag_id = ?";
        $tags = Database::get_instance()->execute_query($query, array($this->id), true);
        return $tags;
    }

    public function get_tag_info()
    {
        $query = "SELECT * FROM tags WHERE id = ?";
        $tagInfo = Database::get_instance()->execute_query($query, array($this->id), false);
        return $tagInfo;
    }

    public function update_tag($params)
    {
        foreach ($params as $key => $value) {
            if (!empty($value)) {
                $query = "UPDATE tags SET $key = ? WHERE id = ?";
                $arr = array($value, $this->id);
                Database::get_instance()->execute_query($query, $arr);
            }
        }
    }

    public function delete_tag()
    {
        $query = "DELETE FROM tags WHERE id = ?";
        Database::get_instance()->execute_query($query, array($this->id));
        $query = "DELETE FROM article_tag WHERE id = ?";
        Database::get_instance()->execute_query($query, array($this->id));
    }

    public static function is_tag($id)
    {
        $query = "SELECT * FROM tags WHERE id = ?";
        if (Database::get_instance()->execute_query($query, array($id))) {
            return true;
        } else {
            return false;
        }
    }

    public static function create_tag($name)
    {
        $query = "INSERT INTO tags (name) VALUES (?)";
        Database::get_instance()->execute_query($query, array($name));
    }

    public static function get_tags()
    {
        $query = "SELECT * FROM tags";
        $arr = [];
        $tags = Database::get_instance()->execute_query($query, $arr, true);
        return $tags;
    }
}
