<?php
declare(strict_types=1);
/*
 * Copyright 2016-2017 Xenofon Spafaridis
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

require __DIR__ . '/../vendor/autoload.php';

//Include settings
$settings = include __DIR__ . '/settings.php';

//Prepare database

//Delete database file if already exists
if (file_exists($settings->db->file)) {
    unlink($settings->db->file);
}

$adapter = new \Phramework\Database\SQLite($settings->db);

$adapter->execute(
    'CREATE TABLE article(
        `id` INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
        `title` varchar(255),
        `body` TEXT,
        `creator-user_id` int,
        `status` int
    )'
);

$adapter->execute(
    'CREATE TABLE `tag`(
        `id` INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
        `title` varchar(255),
        `status` int
    )'
);
$adapter->execute(
    'CREATE TABLE `article-tag`(
        `article_id` INTEGER,
        `tag_id` INTEGER,
        `status` int
    )'
);

$adapter->execute(
    'CREATE TABLE `user`(
        `id` INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
        `username` varchar(255),
        `name` varchar(255),
        `status` int
    )'
);

$articles = [
    [1, 'Hello World', 'HELLO WORLD', 1, 1],
    [2, 'About us', 'We are...', 1, 1]
];
$tags = [
    [1, 'blog', 1],
    [2, 'programming', 1],
    [3, 'php', 1],
    [4, 'html', 0]
];
$articles_tags = [
    [1, 1, 1],
    [1, 2, 1],
    [2, 1, 1]
];

$users = [
    [1, 'nohponex', 'Xenofon Spafaridis', 1],
    [2, 'janedoe', 'Jane Doe', 0]
];


//Insert data

foreach ($users as $user) {
    $adapter->execute(
        'INSERT INTO `user` (`id`, `username`, `name`, `status`)
      VALUES (?, ?, ?, ?)',
        $user
    );
}
foreach ($articles as $article) {
    $adapter->execute(
        'INSERT INTO `article` (`id`, `title`, `body`, `creator-user_id`, `status`)
      VALUES (?, ?, ?, ?, ?)',
        $article
    );
}
foreach ($tags as $tag) {
    $adapter->execute(
        'INSERT INTO `tag` (`id`, `title`, `status`)
          VALUES (?, ?, ?)',
        $tag
    );
}
foreach ($articles_tags as $article_tag) {
    $adapter->execute(
        'INSERT INTO `article-tag` (`article_id`, `tag_id`, `status`)
      VALUES (?, ?, ?)',
        $article_tag
    );
}