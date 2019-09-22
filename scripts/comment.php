<?php


if ($_POST['action'] == 'comment'){
    include "../db/connect.php";

    $db = get_connection();
    $uid = $_POST['uid'];
    $pid =  $_POST['pid'];
    $user = $_POST['uname'];
    $content = $_POST['content'];

    $q = "INSERT INTO `comments` (`id`, `author`, `authorid`, `content`, `date`, `points`, `postid`) VALUES (NULL, '$user', '$uid', '$content', CURRENT_TIMESTAMP, '0', '$pid');";
    $result = $db->query($q);
    $id = mysqli_insert_id($db);

    echo "comment~$user~$uid~$content~$id";
}


if ($_POST['action'] == 'delete_comment'){
    include "../db/connect.php";

    $db = get_connection();
    $cid = $_POST['cid'];

    $q = "DELETE FROM comments WHERE id = $cid";
    mysqli_query($db, $q);

    echo "deleted~$cid";
}