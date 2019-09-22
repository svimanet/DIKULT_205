<?php
/**
 * Created by PhpStorm.
 * User: svimanet
 * Date: 14.05.17
 * Time: 17:12
 */


/**
 * @param $db - database
 * @param $pid - post. or comments.id
 * @param $table - table to query
 * @return mixed  - points
 * Updates a post or comments points on click.
 */
function update_post($db, $pid, $table){
    $q = "SELECT points FROM $table WHERE id = $pid";
    $result = $db->query($q);
    $row = $result->fetch_assoc();
    $points = $row['points'];
    return $points;
}


/**
 * @param $pid - posts_id
 * @param $db - database
 * @return array - the post in question
 * Returns information about a post in the form of an array.
 * Used to display posts on 'post.html'.
 */
function get_post_info($pid, $db){
    $q = "SELECT * FROM `posts` WHERE `id` = $pid";
    $result = $db->query($q);
    $row = $result->fetch_assoc();
    $array = array($pid, $row['title'], $row['content'], $row['points'], $row['author'], $row['authorid']);
    return $array;
}



/**
 * @param $pid - posts_id
 * @param $db - database
 * @return array - array of comments
 * Returns a list of comments based on a post's id.
 */
function get_comments_on_post($pid, $db){
    $q = "SELECT id, author, authorid, content, points FROM comments WHERE postid = $pid  order by date";
    $result = $db->query($q);
    $comments = array();
    while ($row = $result->fetch_assoc()){
        $comment = array($row['id'], $row['author'], $row['authorid'], $row['content'], $row['points']);
        array_push($comments, $comment);
    }
    return $comments;
}

/**
 * @param $array - comments
 * Requires a param from a return method.
 * Requires a list of comments for å specified post.
 * Generates comments to the post page.
 */
function generate_comments_on_post($pid, $db, $uid){
    $array = get_comments_on_post($pid, $db);
    $num_comments = count($array);

    for ($i=0; $i < $num_comments; $i++){
        $comment = $array[$i];
        $cid = $comment[0];
        $author = $comment[1];
        $author_id = $comment[2];
        $content = $comment[3];
        $points = $comment[4];
        $color = points_color($points);

        $q = "SELECT * FROM likes WHERE userid = $uid AND commentid = $cid";
        $result = $db->query($q);
        $rows = $result->num_rows;

        $up = "fa fa-chevron-up";
        $down = "fa fa-chevron-down";

        if ($rows > 0) {
            $row = $result->fetch_assoc();
            if ($row['vote'] == "up") {
                $up = "fa fa-chevron-circle-up";
            } else {
                $down = "fa fa-chevron-circle-down";
            }
        }

        $q2 = "SELECT * FROM comments WHERE authorid = $uid AND id = $cid";
        $result2 = $db->query($q2);
        $rows2 = $result2->num_rows;

        $delete = "";

        if ($rows2 > 0){
            $delete = "<button onclick='delete_comment($cid)' id='delete_$cid' class='delete_comment' ><i class='fa fa-trash' aria-hidden='true'></i></button>";
        }

        echo "
        <div class='comment' id='comment_$cid'>
            <div class='info_wrapper'>
                <p class='comment_text'>$content</p>
             </div>
             <div class='points_wrapper'>
                <p class='points'>Points: <span id='p_$cid' class='point' style='color:$color;'>$points</span></p>
                <div class='vote_btns'>
                    <button onclick='vote_comment($cid, $author_id, $points, \"up\")' id='u_$cid' class=\"plus\"><i class='$up' aria-hidden=\"true\"></i></button>
                    <button onclick='vote_comment($cid, $author_id, $points, \"down\")' id='d_$cid' class=\"minus\"><i class='$down' aria-hidden=\"true\"></i></button>
                    $delete
                </div>
                <p class=\"author\"><a class=\"user\" href=\"profile.php?user=$author_id\">" . ucfirst($author) . "</a></p>
             </div>
         </div>";
    }
}