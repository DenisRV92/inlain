<?php

require_once __DIR__ . '/vendor/autoload.php';

use Db\Db;

$db = new db();
$db->connect();
$db->createTables();
$post = $db->getPosts();
$comments = $db->getComments();
if ($post && $comments) {
    echo "<script>console.log('Загружено $post записей и $comments комментариев')</script>";
}
require 'view/search.php';
if (isset($_GET)) {
    if (strlen($_GET['q']) >= 3) {
        $results = $db->searchText($_GET['q']);
        if (!empty($results)) {
            require 'view/table.php';
        } else {
            echo "Ничего не нашлось";
        }
    } else {
        echo 'Текс должен быть не менее 3 символов';
    }
}
