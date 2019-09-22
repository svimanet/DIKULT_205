<!DOCTYPE html>

<?php
include "scripts/common_scripts.php";
include "scripts/vote.php";
include "scripts/post_scripts.php";
include "db/connect.php";

$db = get_connection();
session_start();
$logged = False;
$author = False;
$admin = False;

$id = $_GET['post'];
$post = get_post_info($id, $db);
$p_title = $post[1];
$p_content = $post[2];
$p_points = $post[3];
$p_author = $post[4];
$p_author_id = $post[5];
$title_head = substr($p_title, 0, 15);
$num_comments = get_num_comments_post($id, $db);


if (isset($_SESSION['username'])){
    $logged = True;
    $_SESSION['points'] = update_user($_SESSION['id'], $db);

    if (strtolower($_SESSION['username']) === strtolower($p_author)){
        $author = True;
    } else if ($_SESSION['rank'] === "admin"){
        $admin = True;
    }

}

 ?>

<html>
<head>
    <title><?php echo "Post - $title_head" ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="style/main.css">
    <link rel="stylesheet" type="text/css" href="style/post.css">
    <meta charset="utf-8" />
    <script src="https://use.fontawesome.com/42d36387a4.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
</head>
<body>

<!-- ########################## -->
<!-- # Menu for all the pages # -->
<!-- #    username + poeng    # -->
<!-- ########################## -->
<header>
    <nav>
        <ol>
            <li class="navItem">
                <a class="navLink" href="index.php">H O M E</a>
                <a class="navIcon" href="index.php"><i class="fa fa-home fa-3x" aria-hidden="true"></i></a>
            </li>
            <li class="navItem">
                <?php
                if (isset($_SESSION['username'])){
                    echo '
                    <a class="navLink" href="profile.php">P R O F I L E</a>
                    <a class="navIcon" href="profile.php"><i class="fa fa-user fa-3x" aria-hidden="true"></i></a>';

                } else {
                    echo '
                    <a class="navLink" href="login.php">P R O F I L E</a>
                    <a class="navIcon" href="login.php"><i class="fa fa-user fa-3x" aria-hidden="true"></i></a>';
                }
                ?>
            </li>
            <li class="navItem">
                <a class="navLink" href="browse.php">B R O W S E</a>
                <a class="navIcon" href="browse.php"><i class="fa fa-compass fa-3x" aria-hidden="true"></i></a>
            </li>
            <li class="navItem">
                <a class="navLink" href="about.php">A B O U T</a>
                <a class="navIcon" href="about.php"><i class="fa fa-info-circle fa-3x" aria-hidden="true"></i></i></a>
            </li>

            <li class="navItem">
                <?php
                # Check if the user is logged in.
                if (isset($_SESSION['username'])){
                    echo '<a id="navUsername" href="profile.php">' . $_SESSION['username'] . '<br><span id="navPoeng">' . $_SESSION['points'] . '</span>';

                } else {
                    echo '<a id="navUsername" href="login.php">Login';
                }
                ?>
                </a>
            </li>
        </ol>
    </nav>
</header>

    <main>
    	<section id="main_section">
            <h1><?php echo $p_title ?></h1>

            <section id="post_wrapper">

                <!-- Votebuttons are grayed out and strip of functionality if the user is not logged in. -->
                <section id="vote_buttons">
                    <button <?php if(!$logged){ echo "title='Sign in to vote' style='color:gray'"; } else { echo "title='Upvote post' onclick=\"vote('up')\""; } ?> id="u_p" class="plus"><i id="p_plus" class="fa fa-chevron-up" aria-hidden="true"></i></button>
                    <button <?php if(!$logged){ echo "title='Sign in to vote' style='color:gray'"; } else { echo "title='Downvote post' onclick=\"vote('down')\""; }?> id="d_p" class="minus"><i class="fa fa-chevron-down" aria-hidden="true"></i></button>
                    <?php if ($author or $admin){echo "<button title='Delete post' id='delete_btn' onclick=\"delete_post()\"><i class=\"fa fa-trash\" aria-hidden=\"true\"></i></button>";}?>
                    <?php if ($author){echo "<button title='Edit post' id='edit_btn' onclick=\"edit_post()\"><i class=\"fa fa-pencil\" aria-hidden=\"true\"></i></button>";}?>
                </section>


                <!--
                Functions to check the votebutton on load and letting the user click a button to vote.
                The first function is run on page load and checks the database wether or not the user in question
                has voted on the post. If that is the case, the relevant vote button will be hilighted.

                The other function is run onclick on the two different votebuttons and reacts differently
                according to wether its been downvoted or upvoted beforehand.-->
                <script>
                    $(document).ready(function(){
                        var id = "<?php echo $id ?>";
                        var uid = "<?php echo $_SESSION['id'] ?>";

                        $.ajax({
                            type: "POST",
                            url: 'scripts/vote.php',
                            data: {action: 'check', user:uid, post:id},
                            success: function (result) {

                                if (result.indexOf("true") > -1) {

                                    if (result.indexOf("up") > -1) {
                                        $('#u_p').html('<i id="p_plus" class="fa fa-chevron-circle-up" aria-hidden="true"></i>')
                                    } else {
                                        $('#d_p').html('<i id="p_minus" class="fa fa-chevron-circle-down" aria-hidden="true"></i>')
                                    }
                                }
                            }
                        })
                    });

                    function vote(type) {
                        var id = "<?php echo $id ?>";
                        var aid = "<?php echo $p_author_id ?>";
                        var uid = "<?php echo $_SESSION['id'] ?>";

                        $.ajax({
                            type: "POST",
                            url: 'scripts/vote.php',
                            data:{action:'vote', type:type, id:id, aid:aid, uid:uid},
                            success:function(result) {

                                if (result === "up up"){
                                    $('#u_p').html('<i id="p_plus" class="fa fa-chevron-up" aria-hidden="true"></i>');
                                    $('#u_p')
                                    <?php $p_points = $p_points - 1 ?>
                                    $('#p_point').text(parseInt(<?php echo $p_points?>))

                                } else if(result === "up down") {
                                    $('#u_p').html('<i id="p_plus" class="fa fa-chevron-up" aria-hidden="true"></i>');
                                    $('#d_p').html('<i id="p_minus" class="fa fa-chevron-circle-down" aria-hidden="true"></i>');
                                    <?php $p_points = $p_points - 2 ?>
                                    $('#p_point').text(parseInt(<?php echo $p_points?>))

                                } else if(result === "down up"){
                                    $('#u_p').html('<i id="p_plus" class="fa fa-chevron-circle-up" aria-hidden="true"></i>');
                                    $('#d_p').html('<i id="p_minus" class="fa fa-chevron-down" aria-hidden="true"></i>');
                                    <?php $p_points = $p_points + 2 ?>
                                    $('#p_point').text(parseInt(<?php echo $p_points?>))

                                } else if(result === "down down"){
                                    $('#d_p').html('<i id="p_minus" class="fa fa-chevron-down" aria-hidden="true"></i>');
                                    <?php $p_points = $p_points + 1 ?>
                                    $('#p_point').text(parseInt(<?php echo $p_points?>))

                                } else if(result === "first up"){
                                    $('#u_p').html('<i id="p_plus" class="fa fa-chevron-circle-up" aria-hidden="true"></i>');
                                    <?php $p_points = $p_points + 1 ?>
                                    $('#p_point').text(parseInt(<?php echo $p_points?>))

                                } else if(result === "first down"){
                                    $('#d_p').html('<i id="p_minus" class="fa fa-chevron-circle-down" aria-hidden="true"></i>');
                                    <?php $p_points = $p_points - 1 ?>
                                    $('#p_point').text(<?php echo $p_points?>)

                                } else {
                                    alert("An nknown error occured: " + result)
                                }
                            }
                        });
                    }

                    function delete_post() {
                        var pid = "<?php echo $id ?>";
                        var uid = "<?php echo $p_author_id?>";

                        $.ajax({
                            type: "POST",
                            url: 'scripts/vote.php',
                            data:{action:'delete', pid:pid, uid:uid},
                            success:function(result) {

                                if (result === "deleted " + pid){
                                    alert("Post successfully removed from existence.");
                                    window.location.href = 'browse.php';

                                } else {
                                    alert("Something went wrong. Please try again. " + result);

                                }
                            }
                        });
                    }

                    function edit_post(){
                        var pid = "<?php echo $id ?>";
                        window.location.href = 'edit_post.php?post=' + pid;
                    }
                </script>


                <!-- Section for generating the post content and info. -->
                <section id="content_section">
                    <p id="content"><?php echo "$p_content<br><br><br>" ?></p>
                    <div id="data">
                        <?php
                        $color = points_color($p_points);

                        $info = "
                        <p class='points'>Points: 
                            <span id=\"p_point\" class='point' style='color:$color'>$p_points </span> Author: 
                            <a id='user_link' href='profile.php?user=" . $p_author_id . "'>$p_author</a>
                        </p>";

                        echo $info;

                        ?>

                    </div>
                </section>
            </section>
        </section>

        <!-- # Data used to manage comments and user # -->
        <?php
        $username = $_SESSION['username'];
        $uid = $_SESSION['id'];
        ?>

        <!-- Section for the user to write comments to the post. -->
        <section id="comment_input_section">
            <textarea id="comment_box" type="text" name="comment" placeholder="Comment text here .."></textarea>
            <button onclick='write_comment("<?php echo $username ?>", "<?php echo $uid ?>", "<?php echo $id ?>", $("#comment_box").val())' id="comment_btn">Comment</button>
        </section>


        <!-- Section for displaying comments -->
        <section id="comment_section">

            <script>
                function write_comment(uname, uid, pid, content) {
                    console.log("\n# ---> " + content);
                    $.ajax({
                        type: "POST",
                        url: 'scripts/comment.php',
                        data: {action: 'comment', uname:uname, uid:uid, pid:pid, content:content},
                        success: function (result) {

                            if (result.indexOf("comment~") > -1) {
                                var values = result.split("~");
                                var cid  = values[4];
                                window.location.hash = "comment_" + cid;
                                window.location.reload(true);

                            } else {
                                alert("An nknown error occured: " + result)
                            }
                        }
                    });
                }
            </script>

            <!-- Generate all comments on the post. -->
            <?php generate_comments_on_post($id, $db, $_SESSION['id']); ?>

            <!-- Gray out the vote buttons if the user is not logged in. -->
            <?php
            if (!$logged){
                $info = "<style>
                         .plus{
                             color:gray;
                         }
                         .minus{
                             color:gray;
                         }
                         </style>";
                echo $info;
            }
            ?>

            <!--
            Function on every vote button on every comment. Alerts if the user is not logged in.
            Reacts differently to different situations. E.g if the user has already upvoted and
            Clicks upvote again, the upvote is nulled out and removed from database.
            -->
            <script>
                function vote_comment(cid, aid, points, mode) {
                    var logged = "<?php if(!$logged){echo "False";} else {echo "True"; } ?>";

                    if(logged.indexOf("False")>-1){
                        alert("Please sign in to vote.");
                    } else {
                        var pid = "<?php echo $id ?>";
                        var uid = "<?php echo $_SESSION['id'] ?>";

                        $.ajax({
                            type: "POST",
                            url: 'scripts/vote.php',
                            data: {action: 'vote_comments', pid: pid, aid: aid, uid: uid, cid: cid, mode: mode},
                            success: function (result) {

                                var cid = result.split(" ")[0];
                                var up_ = "#u_" + cid;
                                var dn_ = "#d_" + cid;
                                var p_ = "#p_" + cid;

                                if (result.indexOf("up up") > -1) {
                                    $(up_).html('<i class="fa fa-chevron-up" aria-hidden="true"></i>');
                                    $(p_).text(points - 1)

                                } else if (result.indexOf("down up") > -1) {
                                    $(dn_).html('<i class="fa fa-chevron-down" aria-hidden="true"></i>');
                                    $(up_).html('<i class="fa fa-chevron-circle-up" aria-hidden="true"></i>');
                                    $(p_).text(points + 2)

                                } else if (result.indexOf("up down") > -1) {
                                    $(up_).html('<i class="fa fa-chevron-up" aria-hidden="true"></i>');
                                    $(dn_).html('<i class="fa fa-chevron-circle-down" aria-hidden="true"></i>');
                                    $(p_).text(points - 2)

                                } else if (result.indexOf("down down") > -1) {
                                    $(dn_).html('<i class="fa fa-chevron-down" aria-hidden="true"></i>');
                                    $(p_).text(points + 1)

                                } else if (result.indexOf("none up") > -1) {
                                    $(up_).html('<i class="fa fa-chevron-circle-up" aria-hidden="true"></i>');
                                    $(p_).text(points + 1)

                                } else if (result.indexOf("none down") > -1) {
                                    $(dn_).html('<i class="fa fa-chevron-circle-down" aria-hidden="true"></i>');
                                    $(p_).text(points - 1)

                                } else {
                                    alert("An nknown error occured: " + result)
                                }
                            }
                        });
                    }

                }

                function delete_comment(cid){
                    $.ajax({
                        type: "POST",
                        url: 'scripts/comment.php',
                        data: {action: 'delete_comment', cid:cid},
                        success: function (result) {
                            if (result.indexOf("deleted~"+cid) > -1) {
                                console.log("SUCCESS!");
                                window.location.reload(true);
                            } else {
                                alert("An nknown error occured: " + result)
                            }
                        }
                    });
                }
            </script>
        </section>
    </main>

<footer>
    <?php
    if (isset($_SESSION['username'])){
        echo "
        <a id='logout_button' href=\"logout.php\">Logout</a>";
    }
    ?>
    <ul id="media_buttons">
        <li class="media_button"><a href="#"><i class="fa fa-facebook-square" aria-hidden="true"></i></a></li>
        <li class="media_button"><a href="#"><i class="fa fa-twitter-square" aria-hidden="true"></i></a></li>
        <li class="media_button"><a href="#"><i class="fa fa-instagram" aria-hidden="true"></i></a></li>
        <li class="media_button"><a href="#"><i class="fa fa-google-plus-square" aria-hidden="true"></i></a></li>
    </ul>
</footer>
</body>
</html>