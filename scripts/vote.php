<?php

if ($_POST['action'] == 'check'){
    include "../db/connect.php";

    $db = get_connection();
    $uid = $_POST['user'];
    $id =  $_POST['post'];

    $q = "SELECT * FROM likes WHERE userid = '$uid' AND postid = '$id'";
    $result = $db->query($q);
    $rows = $result->num_rows;
    $row = $result->fetch_assoc();
    $vote = "";

    if ($rows > 0) {
        $liked = "true";
        if ($row['vote'] == "up") {
            $vote = "up";

        } else {
            $vote = "down";
        }
    } else {
        $liked = "false";
    }
    echo "$liked $vote";
}


elseif($_POST['action'] == 'vote'){
    include "../db/connect.php";

    $db = get_connection();
    $type = $_POST['type'];
    $user = $_POST['uid'];
    $author = $_POST['aid'];
    $id = $_POST['id'];

    $get_vote = "SELECT * FROM likes WHERE userid = '$user' AND postid = '$id'; ";
    $result = mysqli_query($db, $get_vote);
    $rows = $result->num_rows;

    if ($rows > 0){
        $row = $result->fetch_assoc();
        $vote = $row['vote'];
        $response = "Error";
        $q = "";

        if ($vote=== "up" && $type === "up") {
            $response = "up up";
            $q = "UPDATE users SET points = points - 1 WHERE id = $author;
              UPDATE posts SET points = points - 1 WHERE id = $id;
              DELETE FROM likes WHERE userid = $user AND postid = $id;";

        } elseif($vote === "down" && $type === "down"){
            $response = "down down";
            $q = "UPDATE users SET points = points + 1 WHERE id = $author;
              UPDATE posts SET points = points + 1 WHERE id = $id;
              DELETE FROM likes WHERE userid = $user AND postid = $id;";

        } elseif($vote === "up" && $type === "down"){
            $response = "up down";
            $q = "UPDATE users SET points = points - 2 WHERE id = $author;
              UPDATE posts SET points = points - 2 WHERE id = $id;
              UPDATE likes SET vote = 'down' WHERE postid = $id AND userid = $user;";

        } elseif($vote === "down" && $type === "up"){
            $response = "down up";
            $q = "UPDATE users SET points = points + 2 WHERE id = $author;
              UPDATE posts SET points = points + 2 WHERE id = $id;
              UPDATE likes SET vote = 'up' WHERE postid = $id AND userid = $user;";
        }

        mysqli_multi_query($db, $q);
        $db->close();
        echo $response;

    } else {
        if ($type === "up"){
            $points = "+ 1";
        } else {
            $points = "- 1";
        }

        $q = "UPDATE users SET points = points $points WHERE id = '$author';
          UPDATE posts SET points = points $points WHERE id = '$id';
          INSERT INTO `likes` (`id`, `userid`, `postid`, `vote`, `commentid`) VALUES (NULL, '$user', '$id', '$type', NULL);";

        mysqli_multi_query($db, $q);
        $db->close();
        echo "first $type";
    }
}


elseif($_POST['action'] == 'vote_comments'){
    include "../db/connect.php";

    $db = get_connection();

    $uid = $_POST['uid'];
    $aid = $_POST['aid'];
    $cid = $_POST['cid'];
    $pid = $_POST['pid'];
    $mode = $_POST['mode'];

    $vote = "none";
    $q = "";
    $q1 = "SELECT vote FROM likes WHERE commentid = $cid and userid = $uid";
    $result = $db->query($q1);


    if ($result->num_rows>0){
        $row = $result->fetch_assoc();
        $vote = $row['vote'];

        if ($vote=== "up" && $mode === "up") {
            $response = "up up";
            $q = "UPDATE users SET points = points - 1 WHERE id = $aid;
              UPDATE comments SET points = points - 1 WHERE id = $cid;
              DELETE FROM likes WHERE userid = $uid AND commentid = $cid;";

        } elseif($vote === "down" && $mode === "down"){
            $response = "down down";
            $q = "UPDATE users SET points = points + 1 WHERE id = $aid;
              UPDATE comments SET points = points + 1 WHERE id = $cid;
              DELETE FROM likes WHERE userid = $uid AND commentid = $cid;";

        } elseif($vote === "up" && $mode === "down"){
            $response = "up down";
            $q = "UPDATE users SET points = points - 2 WHERE id = $aid;
              UPDATE comments SET points = points - 2 WHERE id = $cid;
              UPDATE likes SET vote = 'down' WHERE commentid = $cid AND userid = $uid;";

        } elseif($vote === "down" && $mode === "up"){
            $response = "down up";
            $q = "UPDATE users SET points = points + 2 WHERE id = $author;
              UPDATE comments SET points = points + 2 WHERE id = $id;
              UPDATE likes SET vote = 'up' WHERE commentid = $cid AND userid = $uid;";
        }
    } else {
        if ($mode === "up"){
            $points = "+ 1";
        } else {
            $points = "- 1";
        }

        $q = "UPDATE users SET points = points $points WHERE id = $aid;
          UPDATE comments SET points = points $points WHERE id = $cid;
          INSERT INTO `likes` (`id`, `userid`, `postid`, `vote`, `commentid`) VALUES (NULL, '$uid', NULL, '$mode', $cid);";

    }

    mysqli_multi_query($db, $q);
    $db->close();
    echo "$cid $vote $mode";

}



elseif($_POST['action'] == 'delete'){
    include "../db/connect.php";

    $db = get_connection();

    $uid = $_POST['uid'];
    $pid = $_POST['pid'];

    $q_points = $db->query( "SELECT points FROM posts WHERE id = $pid");
    $result = $q_points->fetch_assoc();
    $points = $result['points'];


    $q = "DELETE FROM `posts` WHERE `posts`.`id` = $pid;";

    mysqli_multi_query($db, $q);
    $db->close();
    echo "deleted $pid";

}

