<!DOCTYPE html>
<?php
include 'scripts/common_scripts.php';
include 'db/connect.php';
?>
<html>
<head>
	<title>L o g i n</title><meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="style/main.css">
    <link rel="stylesheet" type="text/css" href="style/login.css">
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
                    echo '<a id="navUsername" id="currentPage" href="login.php">Login';
                }
                ?>
                </a>
            </li>
        </ol>
    </nav>
</header>

    <!-- Main content of the site -->
    <main>

    	<!-- Main section of the site -->
    	<section id="main_section">

            <?php
            $msg = '';
            $db = get_connection();

            # Act IF the login button is clicked.
            # Get the username and password input by the user.
            # Credentials are fetched with $_POST to avoid displaying
            # sensitive information in the URL.
            if (isset($_POST['login'])) {
                $stay = $_POST['stay_logged'];
                $username = mysqli_real_escape_string($db, strip_tags($_POST['username']));
                $password = mysqli_real_escape_string($db, strip_tags($_POST['password']));
                $q = "SELECT * FROM users WHERE username LIKE '$username';";
                $result = $db->query($q);

                # Act IF the username and password get a match in the database.
                # Get the uer information from the database
                # and input it in a session, then return the user to the index page.
                if ($result->num_rows > 0) {
                    $row = $result->fetch_assoc();
                    $salt = $row["salt"];
                    $pw = $row["password"];

                    if ($pw == md5($password . $salt)){
                        $points = $row['points'];
                        $email = $row['email'];
                        $rank = $row['rank'];
                        $id = $row['id'];

                        session_start();
                        $_SESSION['username'] = ucfirst($username);
                        $_SESSION['points'] = $points;
                        $_SESSION['email'] = $email;
                        $_SESSION['rank'] = $rank;
                        $_SESSION['id'] = $id;
                        $db->close();
                        header('Location: ./index.php');

                    } else {
                        # Display an error if the username and password don't match.
                        $msg = "Wrong username or password";
                    }

                } else {
                    # Display an error if the username and password don't match.
                    $msg = "Wrong username or password";
                }
            }

            ?>

            <!-- Form used to let the user login to the service using a
                 Username, Password and the option to stay logged in.   -->
            <form role = "form" action = "<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method = "post">
                <label >Username</label>
    		    <input class="txt_field" type="text" name="username" required autofocus
                       value="<?php if (isset($_POST['login'])) echo $_POST['username']; ?>">

    		    <label>Password</label>
    		    <input class="txt_field" type="password" name="password" required>

    		    <input id="login" type="submit" value="Login" name="login">
                <h3 id="error"><?php echo $msg ?></h3>
            </form>

    	<h3>Yet to join the discussion?</h3>
    	<a id="sign_up" href="register.php"><h4>Sign up here</h4></a>
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