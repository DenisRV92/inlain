<?php


$sql = [
    "CREATE DATABASE inlain CHARACTER SET utf8 COLLATE utf8_general_ci;",
    "USE inlain;",
    "CREATE TABLE IF NOT EXISTS posts (
        id   INTEGER PRIMARY KEY,
        title VARCHAR (255) NOT NULL,
        body TEXT,
        userId INTEGER);",
    "CREATE TABLE IF NOT EXISTS comments (
        id INTEGER PRIMARY KEY,
        name  VARCHAR (255) NOT NULL,
        email  VARCHAR (350) NOT NULL,
        body TEXT,
        postID INTEGER,
        FOREIGN KEY (postId) REFERENCES posts(id));"
];

return $sql;
