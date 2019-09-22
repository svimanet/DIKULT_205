<!DOCTYPE html>

<?php
include "scripts/common_scripts.php";
include "scripts/vote.php";
include "scripts/post_scripts.php";
include "db/connect.php";

$db = get_connection();
session_start();
$logged = False;

if (isset($_SESSION['username'])){
    $logged = True;
    $_SESSION['points'] = update_user($_SESSION['id'], $db);
}

 ?>

<html>
<head>
    <title>New post</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="style/main.css">
    <link rel="stylesheet" type="text/css" href="style/write_post.css">
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

        <form role="form" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method = "post">
            <input id="in_title" type="text" name="title" placeholder="title .."
                   value="<?php if (isset($_POST['submit'])) echo $_POST['title']; ?>">

            <textarea id="in_content" name="content" placeholder="Post content .." cols="30" rows="10"><?php if(isset($_POST['content'])){echo $_POST['content'];} ?></textarea>

            <input id="in_submit" type="submit" name="submit" value="Write post">
        </form>


        <?php
        if (isset($_POST['submit'])){
            if ($logged){

                $user = $_SESSION['username'];
                $uid = $_SESSION['id'];
                $title = mysqli_real_escape_string($db, strip_tags($_POST['title']));
                $content = mysqli_real_escape_string($db, strip_tags($_POST['content']));

                $q = "INSERT INTO `posts` (`authorid`, `author`, `date`, `content`, `points`, `title`)
                      VALUES ('$uid', '$user', CURRENT_TIMESTAMP, '$content', '0', '$title');
                      ";

                if ($result = $db->query($q)){
                    $id = mysqli_insert_id($db);
                    header('Location: ./post.php?post=' . $id);

                } else {
                    echo "<script>alert('Something went wrong. Please try again.')</script>";
                }

            } else {
                echo "<script>alert('Need to be signed in to post.')</script>";
            }
        }
        ?>


    </section>

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
</main>
</body>
</html>