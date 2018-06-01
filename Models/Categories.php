<?php
//define("ERROR_LOG_FILE", "logFile.txt");

class Categories
{
    private $db;
    public $id;
    public $name;
    public $category = [];
    public function __construct($id = null)
    {
        if ($id !== null && self::is_category($id)) {
            $this->id = $id;
            $this->category = $this->get_category_info();
            $this->name = $this->category['name'];
        } elseif ($id !== null && !self::is_category($this->db, $id)) {
            return false;
        }
    }

    public function get_name()
    {
        return $this->name;
    }

    public function get_category_info()
    {
        $query = "SELECT * FROM categories WHERE id = ?";
        $commentInfo = Database::get_instance()->execute_query($query, array($this->id), false);
        return $commentInfo;
    }

    public function update_category($params)
    {
        foreach ($params as $key => $value) {
            if (!empty($value)) {
                $query = "UPDATE categories SET $key = ? WHERE id = ?";
                $arr = array($value, $this->id);
                Database::get_instance()->execute_query($query, $arr);
            }
        }
    }

    public function delete_category()
    {
        $query = "DELETE FROM categories WHERE id = ?";
        Database::get_instance()->execute_query($query, array($this->id));
        $query = "DELETE FROM articles WHERE category_id = ?";
        Database::get_instance()->execute_query($query, array($this->id));
    }

    public static function is_category($id)
    {
        $query = "SELECT * FROM categories WHERE id = ?";
        $res = Database::get_instance()->execute_query($query, array($id), false);
        if (empty($res)) {
            return false;
        } else {
            return true;
        }
    }

    public static function create_category($name)
    {
        $query = "INSERT INTO categories (name) VALUES (?)";
        Database::get_instance()->execute_query($query, array($name));
    }

    public static function get_categories()
    {
        $query = "SELECT * FROM categories";
        $arr = [];
        $categories = Database::get_instance()->execute_query($query, $arr, true);
        return $categories;
    }
}
