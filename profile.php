<!DOCTYPE html>

<?php
include 'scripts/common_scripts.php';
include 'db/connect.php';

session_start();
$logged = False;
$posts = 0;
$comments = 0;
$bio = "";
$points = 0;
$uid = "";
$db = get_connection();
$username = "";

if(isset($_GET['user'])){
    $user = get_username_on_profile($_GET['user'], $db);

    $username = $user[0];
    $points = $user[1];
    $bio = $user[2];
    $comments = get_num_comments($_GET['user'], $db);
    $posts = get_num_posts($_GET['user'], $db);
    $uid = $_GET['user'];

    if (strtolower($_SESSION['username']) === strtolower($username)){
        $author = True;
    } else if ($_SESSION['rank'] === "admin"){
        $author = True;
    }

}
elseif (isset($_SESSION['username'])){
    $uid = $_SESSION['id'];

    # Update the session value of points
    $_SESSION['points'] = update_user($uid, $db);

    $username = $_SESSION['username'];
    $points = $_SESSION['points'];
    $bio = get_own_bio($uid, $db);
    $comments = get_num_comments($uid, $db);
    $posts = get_num_posts($uid, $db);
    $author = True;
}
?>

<html>
<head>
	<title>P R O F I L E</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="style/main.css">
    <link rel="stylesheet" type="text/css" href="style/profile.css">
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
                    <a class="navLink"  id="currentPage" href="profile.php">P R O F I L E</a>
                    <a class="navIcon" href="profile.php"><i id="currentIcon" class="fa fa-user fa-3x" aria-hidden="true"></i></a>';

                } else {
                    echo '
                    <a class="navLink"  id="currentPage" href="login.php">P R O F I L E</a>
                    <a class="navIcon" href="login.php"><i id="currentIcon" class="fa fa-user fa-3x" aria-hidden="true"></i></a>';
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


    <!-- ################ -->
    <!-- # Profile main # -->
    <!-- ################ -->
    <main>

        <!-- #################################### -->
        <!-- # Main section for profile content # -->
        <!-- #################################### -->
        <section id="main_section">

            <!-- Top of the profile page, containing  -->
            <!-- dynamic title based on user. -->
            <section id="profile_head">
                <?php echo "<h1>$username's Profile.</h1>"; ?>
            </section>

            <!-- Section for sections with content of the page -->
            <section id="content_section">
                
                    <!-- Section to hold numbers -->
                    <!-- amout of points, amout of comments -->
                    <!-- And amount of posts. -->
                    <section id="bio_section">
                    
                    <!-- Sections to seperate titles and value -->
                    <section id="info">
                        <div id="tags">
                            <p>Points:</p>
                            <p>Posts:</p>
                            <p>Comments:</p>
                        </div>
                        <div id="values">
                            <?php $color = points_color($points)?>
                            <p <?php echo "style='color:$color'" ?> id="points"><?php echo $points ?></p>
                            <p class="value_tags" id="posts"><?php echo $posts ?></p>
                            <p class="value_tags" id="comments"><?php echo $comments ?></p>
                        </div>
                    </section>

                    <!-- section for the user generated bio -->
                    <?php
                    if (isset($_POST['update'])){
                        $bio2 = mysqli_real_escape_string($db, strip_tags($_POST['bio']));
                        $bio = strip_tags($_POST['bio']);
                        update_bio($db, $username, $bio2);

                    }

                    if ($author){
                        echo "
                        <form role=\"form\" action=\" " .htmlspecialchars($_SERVER['PHP_SELF']) . "\" method=\"post\">
                        <textarea style='padding:5px' name='bio' id='bio' cols='60' rows='10' autofocus>$bio</textarea>
                        <input title='update bio' type='submit' value='Update' name='update' id='update_btn'/>
                        </form>
                        ";


                    } else {
                        echo "
                        <section id=\"bio\">
                            <p>
                                $bio
                            </p>
                        </section>
                        ";
                    }

                    ?>
                </section>

                <!-- Section to show some previous posts -->
                <section id="posts_section">
                    <!-- Postwrapper to create a 2*n table of posts. Will  -->

                    <?php
                    if ($posts>0) {

                        echo "<div class='post_wrapper'>";
                        display_users_posts($uid, $db, $posts);
                        echo "</div>";

                    } else {
                        echo "<p>User has no posts.</p>";
                    }
                    ?>
                </section>
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
<?php
$db->close();
?>