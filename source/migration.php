<?php

$sql = "create table users
(
id            int auto_increment
primary key,
user_name     varchar(128) null,
email         varchar(128) not null,
user_password varchar(128) not null,
last_login    int(10)      null,
created_at    int(10)      null,
roles         varchar(128) null,
avatar_src    varchar(255) null
);";
$conn->query($sql);
if($conn->error) {
    print_r($conn->error);
    die;
}

$sql = "create table user_request
(
id          int auto_increment
primary key,
asset_id    int         not null,
user_link   varchar(64) null,
comment     text        null,
status      int(1)      null,
created_by  int         null,
updated_by  int         null,
created_at  int(10)     null,
updated_at  int(10)     null,
category    int         null,
uid         bigint(20)  null,
total_spent float       null,
tags        text        null
);";
$conn->query($sql);
if($conn->error) {
    print_r($conn->error);
    die;
}

$sql = "create table app_settings
(
ID    int auto_increment
primary key,
name  varchar(128) not null,
env   varchar(128) null,
value text         null
);";
$conn->query($sql);
if($conn->error) {
    print_r($conn->error);
    die;
}

$sql = "create table categories
(
  id   int auto_increment
    primary key,
  name varchar(64) null,
  constraint categories_id_uindex
  unique (id)
);
";
$conn->query($sql);
if($conn->error) {
    print_r($conn->error);
    die;
}
$sql = "create table tags
(
	id int auto_increment,
	name varchar(32) not null,
	constraint tags_pk
		primary key (id)
);
";
$conn->query($sql);
if($conn->error) {
    print_r($conn->error);
    die;
}

$sql = "create table request_tags
(
    tag_id int not null,
	request_id int not null,
	constraint request_tags_pk
		primary key (tag_id, request_id)
);
";
$conn->query($sql);
if($conn->error) {
    print_r($conn->error);
    die;
}

$categoryJson = '{"4":"Assets","7":"Christmas","2":"Clothes","3":"Easter","5":"Gameplay","8":"Halloween","6":"Release theme","1":"Scenery","10":"St. Patricks","9":"St.Valentine","11":"Stylist"}';
$categories = json_decode($categoryJson, true);
foreach($categories as $key => $val){
    $sql = "insert into categories (id, name) values ($key, '" . $val . "')";
    $conn->query($sql);
    if($conn->error) {
        print_r($conn->error);
        die;
    }
}

$filename = '../source/testJsonDataToSort.txt';
$f = file_get_contents($filename);
$user_r = json_decode($f, true);
foreach($user_r as $k => $r) {
    $r['tags'] = json_encode($r['tags']);
    $sql = 'insert into user_request (id, asset_id, user_link, comment, status, created_by, updated_by, created_at, updated_at, category, uid, total_spent, tags) values (' . $r['id'] . ', ' . $r['asset_id'] . ', "' . $r['user_link'] . '","' . str_replace('"', '', ($r['comment'])) . '",' . $r['status'] . ',' . $r['created_by'] . ',' . $r['updated_by'] . ',' . $r['created_at'] . ',' . $r['updated_at'] . ',' . $r['category'] . ',' . $r['uid'] . ',' . $r['total_spent'] . ',\'' . $r['tags'] . '\')';
    $conn->query($sql);
    if($conn->error) {
        print_r($conn->error);
        die;
    }
}

$sql = 'select * from user_request';
$userRequestData = $conn->query($sql); // выполняю запрос
if ($conn->error) { // обрабатываю ошибки
    print_r($conn->error);
    die;
}
$userRequest = $userRequestData->fetch_all(MYSQLI_ASSOC);
$tags = [];
foreach ($userRequest as $val) {
    foreach (json_decode($val['tags'], true) as $tag) {
        if (!in_array($tag, $tags)) {
            $tags[] = $tag;

            $sql = 'insert into tags (name) value ("' . $tag . '")';
            $conn->query($sql); // выполняю запрос
            if ($conn->error) { // обрабатываю ошибки
                print_r($conn->error);
                die;
            }
        }
    }
}

$tags = [];
foreach ($userRequest as $val) {
    foreach (json_decode($val['tags'], true) as $tag) {
        if (!in_array($tag, $tags)) {
            $tags[] = $tag;

            $sql = 'select id from tags where name = "' . $tag . '"';
            $tagId = $conn->query($sql); // выполняю запрос
            if ($conn->error) { // обрабатываю ошибки
                print_r($conn->error);
                die;
            }
            $tagId = $tagId->fetch_assoc();

            $sql = 'insert into request_tags (tag_id, request_id)  value (' . $tagId['id'] . ', ' . $val['id'] . ')';
            $conn->query($sql); // выполняю запрос
            if ($conn->error) { // обрабатываю ошибки
                print_r($conn->error);
                die;
            }
        }
    }
}

$sql = 'alter table user_request drop column tags';
$conn->query($sql); // выполняю запрос
if ($conn->error) { // обрабатываю ошибки
    print_r($conn->error);
    die;
}

header('Location: /');
