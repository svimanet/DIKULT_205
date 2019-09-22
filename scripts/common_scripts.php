<?php
/**
 * This file is used for handeling datbse connections and data.
 */


/**
 * @param $id - users_id
 * @param $db - database
 * @return mixed - users_points
 * Returns the points of a user. Used to update on page load.
 */
function update_user($id, $db){
    $q = "SELECT points FROM `users` WHERE `id` like $id" ;
    $result = $db->query($q);
    $row = $result->fetch_assoc();
    return $row['points'];
}

function update_bio($db, $user, $bio){
    $q = "UPDATE users SET bio = '$bio' WHERE username LIKE '$user'";
    $db->query($q);
}

/**
 * @param $id - user_id
 * @param $db - database
 * @return mixed - number of rows
 * Returns the number of comments made by a user.
 */
function get_num_comments($id, $db){
    $q = "SELECT points FROM `comments` WHERE `authorid` like $id";
    $result = $db->query($q);
    return $result->num_rows;
}


/**
 * @param $pid - posts_id
 * @param $db - database
 * @return mixed - number of comments
 * Returns the number of comments associated with a post.
 */
function get_num_comments_post($pid, $db){
    $q = "SELECT * FROM `comments` WHERE postid = $pid";
    $result = $db->query($q);
    return $result->num_rows;
}

/**
 * @param $id - users_id
 * @param $db - database
 * @return mixed - number of rows as int
 *  Returns the number of posts made by a user.
 */
function get_num_posts($id, $db){
    $q = "SELECT points FROM `posts` WHERE `authorid` LIKE $id";
    $result = $db->query($q);
    return $result->num_rows;
}


/**
 * @param $id - Users ID
 * @param $db - Database to use
 * @return $bio - Users bio
 * Fetches the users bio from the `users` table.
 */
function get_own_bio($id, $db){
    $q = "SELECT bio FROM `users` WHERE `id` LIKE $id";
    $result = $db->query($q);
    $row = $result->fetch_assoc();
    return $row['bio'];
}


/**
 * @param $id - users_id
 * @param $db - database
 * @param $array - array of posts
 * @return mixed - a post as array
 * Returns an array of post data and pops it from the array its gotten from to avoid duplicates.
 */
function get_random_post($array){
    $rand = rand(count($array));
    return array_pop($array($rand));
}

/**
 * @param $uid - users_id
 * @param $db - database
 * @return array - list of posts
 * Returns a list of all the posts made by a user.
 * Further used in 'display_users_posts()'.
 */
function get_users_posts($uid, $db){
    $q = "SELECT * FROM `posts` WHERE `authorid` LIKE $uid";
    $result = $db->query($q);
    $posts = array();
    while ($row = $result->fetch_assoc()){
        $post = array($row['id'], $row['title'], $row['content'], $row['points'], $row['author'], $row['authorid']);
        array_push($posts, $post);
    }
    return $posts;
}

/**
 * @param $id - users_id
 * @param $db - database
 * Displays bots of information about a users post on the profile page.
 */
function display_users_posts($id, $db, $num_posts){
    $posts = get_users_posts($id, $db);

    for ($i=0; $i < $num_posts; $i++){
        $list = array_pop($posts);
        $title = substr($list[1], 0, 10);
        $content = substr($list[2],0, 20);
        $points = $list[3];
        $pid = $list[0];

        echo "<a class='post_link' href='post.php?post=$pid'>
          <div class='posts'>
          <h3>$title</h3>
          <p>$content</p>
          <h5>$points points 0 comments</h5></div></a>";

    }
}

/**
 * @param $db - database
 * @return array - an array of posts
 * Fetches random posts for displaying on the landing page.
 */
function get_top_posts($db){
    $q = "SELECT * FROM `posts` ORDER BY points DESC LIMIT 9";
    $result = $db->query($q);
    $posts = array();
    $comments = 0;
    while ($row = $result->fetch_assoc()){
        $id = $row['id'];
        $q_comments = "SELECT id FROM comments WHERE postid = $id";
        $result_c = $db->query($q_comments);
        $comments = $result_c->num_rows;
        $post = array( $id, $row['title'], $row['content'], $row['author'], $row['points'], $comments);
        array_push($posts, $post);
    }
    return $posts;
}


function get_new_posts($db){
    $q = "SELECT * FROM `posts` ORDER BY `date` DESC LIMIT 21";
    $result = $db->query($q);
    $posts = array();
    while ($row = $result->fetch_assoc()){
        $id = $row['id'];
        $q_comments = "SELECT id FROM comments WHERE postid = $id";
        $result_c = $db->query($q_comments);
        $comments = $result_c->num_rows;
        $post = array( $id, $row['title'], $row['content'], $row['author'], $row['points'], $comments);
        array_push($posts, $post);
    }
    return $posts;
}

/**
 * @param $list
 * @return mixed
 * Previews random posts from a list on the index page.
 */
function preview_post($list){
    if(!empty($list)){
        $backgrounds = array(
            "CAEBF2",
            "C9EBF3",
            "D5DEF3",
            "C1D0DC",
            "C1DCDA",
            "D5F3E8",
            "D5D6E8",
            "C1CDDC",
            "C1DCD6",
            "D5F3E1",
            "D5D2E1",
            "C1C9DC",
            "C1DCD3",
            "D5E8D7"
        );

        $background = "#" . $backgrounds[rand(0, 10)];
        $post = array_shift($list);
        $url = "post.php?post=$post[0]";
        $color = points_color($post[4]);
        if (strlen($post[2])>175){

            $content = substr($post[2], 0, 175) . "...";
        } else {
            $content = substr($post[2], 0, 175);
        }

        echo "<a class='post_link' href='$url'>
          <div style='background: $background;' class='post'>
          <h3>$post[1]</h3>
          <p class='post_content'>$content</p>
          <p class='post_info'><span class='post_author'>" . ucfirst($post[3]) . "</span>
          <span style='color:$color;' class='post_points'>$post[4]</span> - <span class='comments'>$post[5]</span></p></div>
          </a>";

        return $list;
    }
}


/**
 * @param $points - points of a commen / post / user
 * @return string - the color to display points in
 * Returns a color based on amount of points. Used to give -points a red color and + a green color.
 */
function points_color($points){
    if($points < 0){
        return "indianred";
    } elseif ($points > 0){
        return "lightseagreen";
    }
}



function get_username_on_profile($id, $db){
    $q = "SELECT * FROM users WHERE id = $id";
    $result = $db->query($q);
    $row = $result->fetch_assoc();

    $uname = $row['username'];
    $points = $row['points'];
    $bio = $row['bio'];

    return $details = array($uname, $points, $bio);
}