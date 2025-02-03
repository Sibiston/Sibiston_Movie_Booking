<?php
session_start();  // Start the session to store user information

include('connection.php');  // Include the database connection

$error_message = ""; // To store error message

// Check if the form is submitted
if (isset($_POST['login'])) {

    $email = mysqli_real_escape_string($con, $_POST['email']);
    $password = mysqli_real_escape_string($con, $_POST['password']);
    $remember = isset($_POST['remember']) ? true : false;  // Check if remember me is checked

    // Check if email and password are provided
    if (empty($email) || empty($password)) {
        $error_message = "Please fill in both email and password.";
    } else {
        // Check if the email exists in the database
        $query = "SELECT * FROM users WHERE email = '$email'";
        $result = mysqli_query($con, $query);

        if (mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);

            // Check if the password matches
            if ($password == $row['password']) {
                // Successful login, set session or cookie
                $_SESSION['user_id'] = $row['id'];  // Store user ID in session
                if ($remember) {
                    // Set a cookie that expires in 30 days for "remember me" functionality
                    setcookie("user_id", $row['id'], time() + (30 * 24 * 60 * 60), "/");
                }

                // Redirect to a different page after successful login (e.g., dashboard)
                header("Location: index.php");
                exit();
            } else {
                $error_message = "Invalid password.";
            }
        } else {
            $error_message = "No user found with this email.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/all.min.css">
    <link rel="stylesheet" href="assets/css/animate.css">
    <link rel="stylesheet" href="assets/css/flaticon.css">
    <link rel="stylesheet" href="assets/css/magnific-popup.css">
    <link rel="stylesheet" href="assets/css/odometer.css">
    <link rel="stylesheet" href="assets/css/owl.carousel.min.css">
    <link rel="stylesheet" href="assets/css/owl.theme.default.min.css">
    <link rel="stylesheet" href="assets/css/nice-select.css">
    <link rel="stylesheet" href="assets/css/main.css">

    <link rel="shortcut icon" href="assets/images/favicon.png" type="image/x-icon">

    <title>ReserveYourSeat - Online Ticket Booking Website HTML Template</title>
    <style>
        body {
            background-image: url('./assets/images/back1.avif');
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center;
            background-attachment: fixed; /* Optional: for parallax effect */
        }
    </style>
</head>


<body >
    <!-- ==========Preloader========== -->
    <div class="preloader">
        <div class="preloader-inner">
            <div class="preloader-icon">
                <span></span>
                <span></span>
            </div>
        </div>
    </div>
    <!-- ==========Preloader========== -->

    <!-- ==========Sign-In-Section========== -->
    <section class="account-section bg_img" >
        <div class="container" >
            <div style="padding-top: 10px;" >
                <div class="account-area" style="background-color: rgba(0, 18, 50, 0.9); 
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.3); 
            border-radius: 10px;height:97%;">
                    <div class="section-header-3">
                    
                        <span class="cate">hello</span>
                        <h2 class="title">welcome back</h2>
                        <span class="cate">to ReserveYourSeat</span>
                    </div>
                    <form class="account-form" method="POST" action="">
                        <div class="form-group">
                            <label for="email2">Email<span>*</span></label>
                            <input type="text" placeholder="Enter Your Email" id="email2" name="email" required>
                        </div>
                        <div class="form-group">
                            <label for="pass3">Password<span>*</span></label>
                            <input type="password" placeholder="Password" id="pass3" name="password" required>
                        </div>
                        <div class="form-group checkgroup">
                            <input type="checkbox" id="bal2" name="remember" checked>
                            <label for="bal2">remember password</label>
                            <a href="sign-in.html#0" class="forget-pass">Forget Password</a>
                        </div>
                        <!-- Display error message if any -->
                    <?php if ($error_message): ?>
                        <center><p style="color: red;"><?php echo $error_message; ?></p></center><br>
                    <?php endif; ?>
                        <div class="form-group text-center">
                            <input type="submit" name="login" value="log in">
                        </div>
                    </form>
                    <div class="option">
                        Don't have an account? <a href="sign-up.php">sign up now</a>
                    </div>

                    

                </div>
            </div>
        </div>
    </section>
    <!-- ==========Sign-In-Section========== -->

    <script src="assets/js/jquery-3.3.1.min.js"></script>
    <script src="assets/js/modernizr-3.6.0.min.js"></script>
    <script src="assets/js/plugins.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
    <script src="assets/js/isotope.pkgd.min.js"></script>
    <script src="assets/js/magnific-popup.min.js"></script>
    <script src="assets/js/owl.carousel.min.js"></script>
    <script src="assets/js/wow.min.js"></script>
    <script src="assets/js/countdown.min.js"></script>
    <script src="assets/js/odometer.min.js"></script>
    <script src="assets/js/viewport.jquery.js"></script>
    <script src="assets/js/nice-select.js"></script>
    <script src="assets/js/main.js"></script>
</body>

</html>
