<?php

include('connection.php');

$error_message = ""; // To store error message
$registration_success = false; // Flag for registration success

if (isset($_POST['save_btn'])) {
    $name = mysqli_real_escape_string($con, $_POST['name']);
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $password = mysqli_real_escape_string($con, $_POST['password']);

    // Email validation
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_message = "Invalid email format.";
    }

    // Password validation (at least 6 characters)
    if (strlen($password) < 6) {
        $error_message = "Password must be at least 6 characters long.";
    }

    if (empty($error_message)) {
        // Check if email already exists in the database
        $check_email_query = "SELECT * FROM users WHERE email = '$email'";
        $result = mysqli_query($con, $check_email_query);

        if (mysqli_num_rows($result) > 0) {
            // Email already exists
            $error_message = "Email is already registered.";
        } else {
            // Insert user data into the database
            // $hashed_password = password_hash($password, PASSWORD_DEFAULT); // Hash the password for security
            $sql = "INSERT INTO users (name, email, password) VALUES ('$name', '$email', '$password')";

            if (mysqli_query($con, $sql)) {
                $registration_success = true; // Set flag for success
            } else {
                $error_message = "Error: " . mysqli_error($con);
            }
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

    <!-- CSS links -->
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
    <title>ReserveYourSeat - Online Ticket Booking</title>
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
<body>
    <div class="preloader">
        <div class="preloader-inner">
            <div class="preloader-icon">
                <span></span>
                <span></span>
            </div>
        </div>
    </div>

    <section class="account-section bg_img" data-background="./assets/images/account/account-bg.jpg">
        <div class="container">
            <?php if ($registration_success): ?>
            <div style="padding-top:15%;">
                <div class="account-area" style="background-color: rgba(0, 18, 50, 0.9); 
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.3); 
            border-radius: 10px;height:40%;">
                    <div class="section-header-3">
                        <span class="cate">Welcome</span>
                        <h4>to ReserveYourSeat</h4>
                    </div>

                    <!-- Check if registration is successful -->
                
                    <div class="form-group text-center">
                        <p style="color: green;">Register successfully!</p>
                    </div>
                    <?php else: ?>
                        <div style="padding-top:10px;">
                        <div class="account-area" style="background-color: rgba(0, 18, 50, 0.9); 
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.3); 
            border-radius: 10px;height:97%;">
                    <div class="section-header-3">
                        <span class="cate">Welcome</span>
                        <h4>to ReserveYourSeat</h4>
                    </div>
                    <!-- Registration form if not registered successfully -->
                    <form class="account-form" method="POST" action="">
                        <div class="form-group">
                            <label for="name">Name<span>*</span></label>
                            <input type="text" placeholder="Enter Your Name" id="name" name="name" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Email<span>*</span></label>
                            <input type="email" placeholder="Enter Your Email" id="email" name="email" required>
                        </div>
                        <div class="form-group">
                            <label for="password">Password<span>*</span></label>
                            <input type="password" placeholder="Password" id="password" name="password" required>
                        </div>
                        <div class="form-group checkgroup">
                            <input type="checkbox" id="terms" required checked>
                            <label for="terms">I agree to the <a href="#">Terms, Privacy Policy</a> and <a href="#">Fees</a></label>
                        </div>

                        <!-- Error message display -->
                        <?php if ($error_message != ""): ?>
                        <div class="form-group text-center">
                            <p style="color: red;"><?php echo $error_message; ?></p>
                        </div>
                        <?php endif; ?>

                        <div class="form-group text-center">
                            <input type="submit" name="save_btn" value="Sign Up">
                        </div>
                    </form>
                    <?php endif; ?>

                    <div class="option">
                        Already have an account? <a href="sign-in.php">Login</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- JS links -->
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

