<?

namespace Src;

use Db\Db;

class Posts
{
    private $conn;

    public function __construct()
    {
        $this->conn = Db::getInstance();
    }

    /**
     * Получаем посты
     * @return int
     */
    public function getPosts()
    {
        $count = $this->conn->сount('posts');
        if ($count > 0) {
            return $count;
        }
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
     * Записываем посты в таблицу
     * @param $data
     * @return mixed
     */
    public function setPosts($data)
    {
        $sql = "INSERT INTO `posts`(id,title,body,userId) 
                VALUES $data";
        $result = $this->conn->getConnection()->prepare($sql)->execute();
        return $result;
    }
}