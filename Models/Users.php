<?php
//define("ERROR_LOG_FILE", "logFile.txt");


class Users
{
    public $id;
    public $name;
    public $rights;
    public $password;
    public $email;
    public $subscriptionDate;
    public $user = [];

    public function __construct($id = null)
    {
        if ($id !== null && self::is_user($id)) {
            $this->id = $id;
            $this->user = $this->get_user_info();
            $this->name = $this->user['name'];
            $this->email = $this->user['email'];
            $this->rights = $this->user['rights'];
            $this->password = $this->user['password'];
            $this->subscriptionDate = $this->user['subscription_date'];
        } elseif ($id !== null && !self::is_user($this->db, $id)) {
            return false;
        }
    }

    public function get_user_info()
    {
        $query = "SELECT * FROM users WHERE id = ?";
        $userInfo = Database::get_instance()->execute_query($query, array($this->id), false);
        return $userInfo;
    }

    public function get_subscription_date()
    {
        return $this->subscriptionDate;
    }

    public function get_name()
    {
        return $this->name;
    }

    public function get_email()
    {
        return $this->email;
    }

    public function get_password()
    {
        return $this->password;
    }

    public function get_rights()
    {
        return $this->rights;
    }

    public function get_articles()
    {
        $query = "SELECT * FROM articles WHERE author_id = ?";
        $articles = Database::get_instance()->execute_query($query, array($this->id), true);
        if ($articles) {
            return $articles;
        } else {
            return false;
        }
    }

    public function get_comments()
    {
        $query = "SELECT * FROM comments WHERE author_id = ?";
        $comments = Database::get_instance()->execute_query($query, array($this->id), true);
        if ($comments) {
            return $comments;
        } else {
            return false;
        }
    }

    public function update_user($params)
    {
        foreach ($params as $key => $value) {
            if (!empty($value)) {
                $query = "UPDATE users SET $key = ? WHERE id = ?";
                $arr = array($value, $this->id);
                Database::get_instance()->execute_query($query, $arr);
            }
        }
    }

    public function delete_user()
    {
        $query = "DELETE FROM users WHERE id = ?";
        Database::get_instance()->execute_query($query, array($this->id));
    }

    public static function is_user($id)
    {
        $query = "SELECT * FROM users WHERE id = ?";
        if (Database::get_instance()->execute_query($query, array($id), false)) {
            return true;
        } else {
            return false;
        }
    }

    public static function is_admin($id)
    {
        $query = "SELECT rights FROM users WHERE id = ?";
        $rights = Database::get_instance()->execute_query($query, array($id), false);
        if ($rights === "admin") {
            return true;
        } else {
            return false;
        }
    }

    public static function is_writer($id)
    {
        $query = "SELECT rights FROM users WHERE id = ?";
        $rights = Database::get_instance()->execute_query($query, array($id), false);
        if ($rights === "writer") {
            return true;
        } else {
            return false;
        }
    }

    public static function create_user($name, $email, $password, $rights = null)
    {
        $password = password_hash($password, 1);
        $query = "INSERT INTO users (name, email, password, rights) VALUES (?, ?, ?, ?)";
        Database::get_instance()->execute_query($query, array($name, $email, $password, $rights));
    }

    public static function get_users()
    {
        $query = "SELECT * FROM users";
        $arr = [];
        $users = Database::get_instance()->execute_query($query, $arr, true);
        return $users;
    }

    public static function get_id($email)
    {
        $query = "SELECT id FROM users WHERE email = ?";
        $id = Database::get_instance()->execute_query($query, array($email), false);
        return $id;
    }

    public static function check_email_password($email, $password)
    {
        $query = "SELECT password FROM users WHERE email = ?";
        $password_hash = Database::get_instance()->execute_query($query, array($email), true);
        if (password_verify($password, $password_hash)) {
            return true;
        } else {
            return false;
        }
    }
}
