<?

namespace Src;

use Db\Db;

class Comments
{
    private $conn;

    public function __construct()
    {
        $this->conn = Db::getInstance();
    }

    /**
     * Получаем комментарии
     * @return int
     */
    public function getComments()
    {
        $count = $this->conn->сount('comments');
        if ($count > 0) {
            return $count;
        }
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
     * Записываем комментарии в таблицу
     * @param $data
     * @return mixed
     */
    public function setComments($data)
    {
        $sql = "INSERT INTO `comments`(postId,id,name,email,body) 
                VALUES $data";
        $result = $this->conn->getConnection()->prepare($sql)->execute();
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
        $result = $this->conn->getConnection()->prepare($sql);
        $result->execute();
        $result = $result->fetchAll();
        return $result;
    }
}