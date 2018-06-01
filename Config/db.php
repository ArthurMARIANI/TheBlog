<?php
define("ERROR_LOG_FILE", "logFile.txt");

class Database
{
    public static $db;
    private static $_instance;

    private function __construct($userPwd = "")
    {
        try {
            self::$db = new PDO("mysql:host=127.0.0.1;dbname=blog", "root", "RvMiRPZsk3");
        } catch (PDOException $e) {
            error_log("In " . __FILE__ . ": " . $e->getMessage() . "\n", 3, ERROR_LOG_FILE);
        }
    }

    public static function get_instance()
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new Database();
        }
        return self::$_instance;
    }


    public function get_db()
    {
        return self::$db;
    }


    public function reset_DB()
    {
        try {
            (self::$db)->query("DELETE FROM articles ");
            (self::$db)->query("ALTER TABLE articles AUTO_INCREMENT = 1");
            (self::$db)->query("DELETE FROM users ");
            (self::$db)->query("ALTER TABLE users AUTO_INCREMENT = 1");
            (self::$db)->query("DELETE FROM comments ");
            (self::$db)->query("ALTER TABLE comments AUTO_INCREMENT = 1");
            (self::$db)->query("DELETE FROM categories ");
            (self::$db)->query("ALTER TABLE categories AUTO_INCREMENT = 1");
            (self::$db)->query("DELETE FROM article_tag ");
            (self::$db)->query("DELETE FROM tags ");
            (self::$db)->query("ALTER TABLE tags AUTO_INCREMENT = 1");
            (self::$db)->query("INSERT INTO articles (title, body, category_id, edition_date, author_id) VALUES ('Mon article', 'blablabla', 1, NOW(), 1)");
            (self::$db)->query("INSERT INTO categories (name) VALUES ('Ma categorie')");
            (self::$db)->query("INSERT INTO comments (author_id, body, article_id) VALUES (1, 'blablablacommentaire', 1)");
            (self::$db)->query("INSERT INTO tags (name) VALUES ('Mon tag')");
            (self::$db)->query("INSERT INTO article_tag (article_id, tag_id) VALUES (1, 1)");
            (self::$db)->query("INSERT INTO users (name, email, password, rights) VALUES ('Admin', 'admin@admin.admin', '21232f297a57a5a743894a0e4a801fc3','admin')");
            (self::$db)->query("INSERT INTO users (name, email, password) VALUES ('User', 'user@user.user', 'ee11cbb19052e40b07aac0ca060c23ee')");
            (self::$db)->query("INSERT INTO users (name, email, password, rights) VALUES ('Writer', 'writer@writer.writer', 'a82feee3cc1af8bcabda979e8775ef0f', 'writer')");
        } catch (Exception $e) {
            error_log("In " . __FILE__ . ": " . $e->getMessage() . "\n", 3, ERROR_LOG_FILE);
        }
    }

    public function execute_query($query, $paramQuery = [], $fetchAll = null)
    {
        try {
            if (!empty($paramQuery)) {
                $data = self::$db->prepare($query);
                $executionCheck = $data->execute($paramQuery);
                if (!$executionCheck) {
                    throw new Exception("Query->execute failed line: " . __LINE__);
                }
            } else {
                $data = self::$db->query($query);
                if (!$data) {
                    throw new Exception("PDO->query failed line: " . __LINE__);
                }
            }
            if ($fetchAll === true) {
                $res = $data->fetchAll(PDO::FETCH_ASSOC);
                return $res;
            } elseif ($fetchAll === false) {
                $res = $data->fetch(PDO::FETCH_ASSOC);
                return $res;
            }

            if (!isset($res)) {
                return true;
            }
        } catch (Exception $e) {
            error_log("In " . __FILE__ . ": " . $e->getMessage() . "\n", 3, ERROR_LOG_FILE);
        }
    }
}
