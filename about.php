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
    <title>A N E C D E M I C</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="style/main.css">
    <link rel="stylesheet" type="text/css" href="style/about.css">
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
                <a class="navLink" id="currentPage" href="about.php">A B O U T</a>
                <a class="navIcon" href="about.php"><i id="currentIcon" class="fa fa-info-circle fa-3x" aria-hidden="true"></i></i></a>
            </li>

            <li class="navItem">
                <?php
                # Check if the user is logged in.
                if (isset($_SESSION['username'])){
                    echo '<a id="navUsername" href="profile.php">' . $_SESSION['username'] . '<br><span id="navPoeng">' . $_SESSION['points'] . '</span></a>';

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
<!-- # Main section of the about # -->
<!-- ############################# -->
<main>

    <section id="titles">
        <h3>About</h3>
        <h1>A N E C D E M I C</h1>
    </section>

    <section id="content">
        <p>
            Anecdemic was made as a semester project in the course "Webdesign II" at the University of Bergen.
        </p>

        <p>
            The platforms goals are to connect students and academics across the university. The faculties at UiB are very distant
            from each other, which is why I believe the platform could improve students and professors discussions across the uni.
            Since the faculties are so distant, and the people are so different, academics does not want to be associated with each other,
            but at Anecdemic, every academic is equal.
        </p>

        <p>
            Hopefully, by sharing ideas and thoughts, opinions and views, stories and anecdotes, academics might be able to
            increase the efficiency of a discussion and enlighten each other on subjects with people they don't meet in their daily study routines.
        </p>
    </section>

    <section id="rules">
        <h2>Rules</h2>
        <ul>
            <li><h4>Behave</h4><p>Unless it's relevant to the context of the discussion, do not be rude or offensive. Have some etiquette.</p> -- </li>
            <li><h4>Respect privacy</h4><p>If people want to remain anonymous, let them. Support the want for privacy, we don't want which hunts to occur because of indifference.</p> -- </li>
            <li><h4>Respect others opinions</h4><p>People think differently, and your word is not law. Not everyone will have the same views as you on certain subjects and you should respect that.</p></li>
        </ul>
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
