<?php

namespace Src\Model;

use Db\Db;
use Src\Contract\CommentInterface;

class Comment implements CommentInterface
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
    public function getComments(): int
    {
        $count = $this->conn->count('comments');
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
        return 0;
    }

    /**
     * Записываем комментарии в таблицу
     * @param $data
     * @return bool
     */
    public function setComments($data): bool
    {
        $sql = "INSERT INTO `comments`(postId,id,name,email,body) 
                VALUES $data";
        $result = $this->conn->getConnection()->prepare($sql)->execute();
        return $result;
    }

    /**
     * Ищем текст
     * @param $str
     * @return array
     */
    public function searchText($str): array
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