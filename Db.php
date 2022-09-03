<?

namespace Db;

use PDO;
use PDOException;


class Db
{
    private $dsn = 'mysql:host=localhost;';
    private $username = 'root';
    private $password = '';
    private $conn;

    /**
     * Создаем базу данных и подключаемся к ней
     */
    public function connect()
    {

        try {
            $this->conn = new PDO($this->dsn, $this->username, $this->password);
            $sql = "CREATE DATABASE inlain CHARACTER SET utf8 COLLATE utf8_general_ci;";
            $this->conn->exec($sql);
            $this->conn->query("use inlain");

        } catch (PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
    }

    /**
     * Создаем таблицы
     */
    public function createTables()
    {
        $sql = include 'sql.php';
        $this->conn->exec($sql);
    }

    /**
     * Получаем посты
     * @return int
     */
    public function getPosts()
    {
        $posts = file_get_contents('https://jsonplaceholder.typicode.com/posts');
        $posts = json_decode($posts, true);
        $sql = '';
        foreach ($posts as $post) {
            $sql .= "($post[id],'$post[title]','$post[body]',$post[userId]),";
        }
        $result = $this->setPosts(rtrim($sql, ","));
        if ($result) {
            return count($posts);
        }
    }


    /**
     * Получаем комментарии
     * @return int
     */
    public function getComments()
    {
        $comments = file_get_contents('https://jsonplaceholder.typicode.com/comments');
        $comments = json_decode($comments, true);
        $sql = '';

        foreach ($comments as $comment) {
            $sql .= "($comment[postId],$comment[id],'$comment[name]','$comment[email]','$comment[body]'),";

        }
        $result = $this->setComments(rtrim($sql, ","));
        if ($result) {
            return count($comments);
        }
    }

    /**
     * Записываем посты в таблицу
     * @param $data
     * @return mixed
     */
    public function setPosts($data)
    {
        $sql = "INSERT INTO `posts`(id,title,body,userId) 
                VALUES $data";
        $result = $this->conn->prepare($sql)->execute();
        return $result;
    }

    /**
     * Записываем комментарии в таблицу
     * @param $data
     * @return mixed
     */
    public function setComments($data)
    {
        $sql = "INSERT INTO `comments`(postId,id,name,email,body) 
                VALUES $data";
        $result = $this->conn->prepare($sql)->execute();
        return $result;
    }

    /**
     * Ищем текст
     * @param $str
     * @return mixed
     */
    public function searchText($str)
    {
        $sql = "SELECT posts.title,comments.body FROM posts
                INNER JOIN `comments` ON posts.id = comments.postID
                WHERE comments.body LIKE '%$str%'";
        $result = $this->conn->prepare($sql);
        $result->execute();
        $result = $result->fetchAll();
        return $result;
    }
}
