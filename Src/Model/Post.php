<?php

namespace Src\Model;

use Db\Db;
use Src\Contract\PostInterface;

class Post implements PostInterface
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
    public function getPosts(): int
    {
        $count = $this->conn->count('posts');
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
        return 0;
    }

    /**
     * Записываем посты в таблицу
     * @param $data
     * @return bool
     */
    public function setPosts($data): bool
    {
        $sql = "INSERT INTO `posts`(id,title,body,userId) 
                VALUES $data";
        $result = $this->conn->getConnection()->prepare($sql)->execute();
        return $result;
    }
}