<!DOCTYPE html>

<?php
include 'scripts/common_scripts.php';
include 'db/connect.php';
session_start();
$logged = False;
$db = get_connection();

if (isset($_SESSION['username'])){
    $logged = True;
    $_SESSION['points'] = update_user($_SESSION['id'], $db);

}
?>

<html>
<head>
    <title>Browse</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="style/main.css">
    <link rel="stylesheet" type="text/css" href="style/index.css">
    <script src="https://use.fontawesome.com/42d36387a4.js"></script>
    <meta charset="utf-8" />
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
                <a class="navLink" id="currentPage" href="browse.php">B R O W S E</a>
                <a class="navIcon" href="browse.php"><i id="currentIcon" class="fa fa-compass fa-3x" aria-hidden="true"></i></a>
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

    <!-- ############################# -->
    <!-- # Main section of the index # -->
    <!-- ############################# -->
    <main>

    <!-- ################################# -->
    <!-- # Container index main sections #-->
    <!-- ################################# -->
    <section id="main_container">

        <!-- Button to write new post -->
        <?php

        if ($logged){
            echo "
                <section id=\"new_post_wrapper\">
                    <a id=\"new_post\" href='write_post.php'>Write post</a>
                </section>";
        }
        ?>


        <!-- ################################## -->
        <!-- # Section for FIRST row of posts # -->
        <!-- ################################## -->
        <section class="posts_section" id="posts_section">

            <?php
            $posts = get_new_posts($db);

            for($i=0; $i<21; $i++){
                $posts = preview_post($posts);
            }
            ?>

            <!--
            <div class="post">
                <h3>Title of post</h3>
                <p class="post_content">start of post content to tempt the user into clicking the post hehe clickbait ...</p>
                <p class="post_info"><span class="post_author">username</span> <span class="post_points">2945</span></p>
            </div>
            -->
        </section>

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
