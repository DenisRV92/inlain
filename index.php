<?php

require_once __DIR__ . '/vendor/autoload.php';

use Db\Db;
use Src\Posts;
use Src\Comments;

$db = new Db();
$db->connect();


$post = new Posts();
$posts = $post->getPosts();
$comment = new Comments();
$comments = $comment->getComments();

if ($posts && $comments) {
    echo "<script>console.log('Загружено $posts записей и $comments комментариев')</script>";
}
require 'view/search.php';
if (isset($_GET)) {
    if (strlen($_GET['q']) >= 3) {
        $results = $comment->searchText($_GET['q']);
        if (!empty($results)) {
            require 'view/table.php';
        } else {
            echo "<div style='text-align: center;'>Ничего не нашлось</div>";
        }
    } else {
        echo "<div style='text-align: center;color: red'>Текс должен быть не менее 3 символов</div>";
    }
}
