<!DOCTYPE html>
<?php
include "db/connect.php";
include "scripts/common_scripts.php";
include "scripts/register_scripts.php";

$db = get_connection();
?>
<html>
<head>
	<title>Sign up</title><meta name="viewport" content="width=device-width, initial-scale=1">
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
                    echo '<a id="navUsername" href="login.php">Login';
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
            $msg = "";

            if (isset($_POST['register'])){

                $username = mysqli_real_escape_string($db, strip_tags($_POST['username']));
                $password = mysqli_real_escape_string($db, strip_tags($_POST['password']));
                $repassword = mysqli_real_escape_string($db, strip_tags($_POST['re_password']));
                $email =  mysqli_real_escape_string($db, strip_tags($_POST['email']));
                $accept = $_POST['accept'];

                if ($accept !== "on"){
                    $msg = "Please read and understand the terms of use.";

                } elseif (check_username_available($username, $db)){
                    $msg = "Username is already in use.";

                } elseif (check_email_available($email, $db)){
                    $msg = "Email is already in use.";

                } elseif ($password !== $repassword){
                    $msg = "Passwords don't match.";

                } else {

                    $salt = substr(md5(crypt("l4212lk3hgks36")), 0, 16);
                    $pw = md5($password . $salt);
                    if (new_user($username, $pw, $email, $db, $salt)){

                        $db->close();
                        header('Location: ./login.php');

                    } else {
                        $msg = "Something went wrong";
                    }
                }
            }

    		?>
            <form role = "form" action = "<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method = "post">

    		    <label>Username</label>
    		    <input class="txt_field" type="username" name="username" placeholder="username123" required autofocus
                       value="<?php if (isset($_POST['register'])) echo $_POST['username']; ?>" >

                <label>Email</label>
                <input class="txt_field" type="email" name="email" placeholder="example@domain.com" required
                       value="<?php if (isset($_POST['register'])) echo $_POST['email']; ?>">

    		    <label>Password</label>
    		    <input class="txt_field" type="password" name="password" required>

                <label>Retype password</label>
                <input class="txt_field" type="password" name="re_password" required>

                <input type="radio" name="accept"><label>I accept the <a href="about.php">terms and conditions</a>.</label>

    		    <input id="login" value="Register" type="submit" name="register">

                <p id="error"><?php echo $msg; ?></p>
    	    </form>
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
