<?php
session_start();
include('connection.php');  // Include the connection file

// Check if the user is not logged in
if (!isset($_SESSION['user_id']) && !isset($_COOKIE['user_id'])) {
    // Redirect to login page if the user is not logged in
    header("Location: sign-in.php");
    exit();  // Ensure no further code is executed
}

// Fetch the user's name based on user_id
$user_id = $_SESSION['user_id'] ?? $_COOKIE['user_id'];
$user_name = "Guest";  // Default name if not found

// Fetch the user's name from the database
$sql = "SELECT name FROM users WHERE id = ?";
$stmt = $con->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($user_name);
$stmt->fetch();
$stmt->close();




$cinema = isset($_SESSION['selected_cinema']) ? $_SESSION['selected_cinema'] : 'Not selected';
$movie_name = isset($_SESSION['movie_name']) ? $_SESSION['movie_name'] : 'Not selected';
$language = isset($_SESSION['selected_language']) ? $_SESSION['selected_language'] : 'Not selected';
$experience = isset($_SESSION['selected_experience']) ? $_SESSION['selected_experience'] : 'Not selected';
$selected_date = isset($_SESSION['selected_date']) ? $_SESSION['selected_date'] : 'Not selected';
$selected_time = isset($_SESSION['selected_time']) ? $_SESSION['selected_time'] : 'Not selected';
$selectedSeats = isset($_SESSION['selected_seat_numbers']) ? $_SESSION['selected_seat_numbers'] : 'None';
$totalPrice = isset($_SESSION['seat_price']) ? $_SESSION['seat_price'] : '0';
// Format the date (assuming the format is "YYYY-MM-DD")
if ($selected_date !== 'Not selected') {
    $formatted_date = date("D, M d Y", strtotime($selected_date));
} else {
    $formatted_date = $selected_date; // Keep "Not selected" if not set
}

// Determine time of day based on selected_time
$time_of_day = 'Not selected';
if ($selected_time !== 'Not selected') {
    $hour = (int) date("H", strtotime($selected_time));

    if ($hour >= 6 && $hour < 12) {
        $time_of_day = 'Morning';
    } elseif ($hour >= 12 && $hour < 17) {
        $time_of_day = 'Afternoon';
    } elseif ($hour >= 17 && $hour < 21) {
        $time_of_day = 'Evening';
    } else {
        $time_of_day = 'Night';
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
    <link rel="stylesheet" href="assets/css/jquery.animatedheadline.css">
    <link rel="stylesheet" href="assets/css/main.css">

    <link rel="shortcut icon" href="assets/images/favicon.png" type="image/x-icon">

    <title>ReserveYourSeat - Online Ticket Booking Website HTML Template</title>


    <style>
        .article-section .view-all {
            display: inline-block;
            padding: 10px 30px;
            font-size: 20px;
            font-weight: bold;
            color: #FFFFFF;
            text-align: center;
            border-radius: 30px;
            background: linear-gradient(45deg, #FF5F6D, #8D4DE8);
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
        }

        .article-section .view-all:hover {
            box-shadow: 0px 0px 20px 10px rgba(141, 77, 232, 0.6);
            transform: scale(1.05);
        }
    </style>
    <style>
        .user-account-section {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            /* Aligns content to the right end */
            position: relative;
            padding-right: 20px;
            /* Optional: Adds some padding on the right end */
        }

        .user-logo {
            margin-left: 10px;
            cursor: pointer;
        }

        .user-welcome-text {
            font-size: 16px;
            color: #333;
        }

        .user-dropdown-menu {
            display: none;
            position: absolute;
            background-color: white;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            border-radius: 5px;
            padding: 10px;
            z-index: -1;
        }

        .user-dropdown-menu a {
            display: block;
            color: #333;
            padding: 8px 12px;
            text-decoration: none;
        }

        .user-dropdown-menu a:hover {
            background-color: #f0f0f0;
        }




        /* Styling for the text, applying different colors */
        .hello-text {
            color: #ff9900;
            /* Color for 'Hello' */
        }

        .name-text {
            color: #ffffff;
            /* Color for 'Ayush!' */
        }
    </style>

</head>

<body>
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
    <!-- ==========Overlay========== -->
    <div class="overlay"></div>
    <a href="index-2.php" class="scrollToTop">
        <i class="fas fa-angle-up"></i>
    </a>
    <!-- ==========Overlay========== -->

    <!-- ==========Header-Section========== -->
    <header class="header-section">
        <div class="container">
            <div class="header-wrapper">
                <div class="logo">
                    <a href="index.php">
                        <img src="assets/images/logo/logo.png" alt="logo">
                    </a>
                </div>
                <ul class="menu">
                    <li>
                        <a href="index.php">Home</a>

                    </li>
                    <li>
                        <a href="movie-grid.php#0" class="active">movies</a>
                        <ul class="submenu">
                            <li>
                                <a href="movie-grid.php" class="active">Movie List</a>
                            </li>
                            <li>
                                <a href="movie-list.php">Booked Ticket</a>
                            </li>
                            <li>
                                <a href="popcorn.php">Movie Food</a>
                            </li>
                        </ul>
                    </li>


                    <li>
                        <a href="about.php">About Us</a>

                    </li>
                    <li>
                        <a href="blog.php">blog</a>

                    </li>
                    <li>
                        <a href="contact.php">contact</a>
                    </li>
                    <li class="header-button pr-0">
                        <a href="sign-up.php">join us</a>
                    </li>
                </ul>

                <div class="user-container">
                    <div class="user-logo" onclick="toggleUserDropdown()">
                        <span class="user-welcome-text">
                            <span class="hello-text">Hello</span>
                            <span class="name-text"><?php
$first_name = explode(" ", trim($user_name))[0]; 
echo htmlspecialchars($first_name); 
?>!</span>
                        </span>
                        &nbsp;&nbsp;
                        <div class="user-dropdown-menu" id="userDropdownMenu">
                            <a href="sign-in.php">Login</a>
                            <a href="logout.php">Logout</a>
                        </div>
                        <img src="assets/images/user1.svg" alt="logo" height="50px" width="50px">


                    </div>


                </div>


                <div class="header-bar d-lg-none">
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
            </div>
        </div>
    </header>
    <!-- ==========Header-Section========== -->

    <!-- ==========Banner-Section========== -->
    <section class="details-banner hero-area bg_img seat-plan-banner" data-background="./assets/images/banner/banner04.jpg">
        <div class="container">
            <div class="details-banner-wrapper">
                <div class="details-banner-content style-two">
                    <h3><?php echo $movie_name; ?>
                    </h3>
                    <div class="tags">
                        <a href="popcorn.php#0"><?php echo $cinema; ?></a>
                        <a href="popcorn.php#0"><?php echo $language . ' - ' . $experience; ?></a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- ==========Banner-Section========== -->

    <!-- ==========Page-Title========== -->
    <section class="page-title bg-one">
        <div class="container">
            <div class="page-title-area">
                <div class="item md-order-1">
                    <a href="movie-seat-plan.php" class="custom-button back-button">
                        <i class="flaticon-double-right-arrows-angles"></i>back
                    </a>
                </div>
                <div class="item date-item">
                    <span class="date"><?php echo $formatted_date; ?></span>
                    <div class="article-section">
                        <a class="view-all" href="popcorn.php#"><?php echo $selected_time !== 'Not selected' ? $selected_time : ''; ?></a>
                    </div>
                </div>
                <div class="item">
                    <h5 class="title"><?php echo $time_of_day; ?></h5>
                </div>


            </div>
        </div>
    </section>
    <!-- ==========Page-Title========== -->

    <!-- ==========Movie-Section========== -->
    <div class="movie-facility padding-bottom padding-top">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    <div class="c-thumb padding-bottom">
                        <img src="assets/images/sidebar/banner/banner04.jpg" alt="sidebar/banner">
                    </div>
                    <div class="section-header-3">
                        <span class="cate">You're hungry</span>
                        <h2 class="title">we have food</h2>
                        <p>Prebook Your Meal and Save More!</p>
                    </div>
                    <div class="grid--area">
                        <ul class="filter">
                            <li data-filter="*" class="active">all</li>
                            <li data-filter=".combos">combos</li>
                            <li data-filter=".bevarage">bevarage</li>
                            <li data-filter=".popcorn">popcorn</li>
                        </ul>




                        <div class="grid-area">
                            <!-- Repeat this structure for each item -->
                            <div class="grid-item combos " data-price="70">
                                <div class="grid-inner">
                                    <div class="grid-thumb">
                                        <img src="assets/images/movie/popcorn/pop1.png" alt="movie/popcorn">
                                        <div class="offer-tag">RS: 70</div>
                                        <div class="offer-remainder">
                                            <h6 class="o-title mt-0">24%</h6>
                                            <span>off</span>
                                        </div>
                                    </div>
                                    <div class="grid-content">
                                        <h5 class="subtitle">
                                            <a href="popcorn.php#0">Muchaco, Crispy Taco, Bean Burrito</a>
                                        </h5>
                                        <div class="cart-button">
                                            <div class="cart-plus-minus">
                                                <input class="cart-plus-minus-box" type="text" name="qtybutton" value="0">
                                            </div>
                                            <button type="button" class="custom-button add-to-cart">Add</button>
                                        </div>
                                    </div>
                                </div>
                            </div>






                            <div class="grid-item combos" data-price="57">
                                <div class="grid-inner">
                                    <div class="grid-thumb">
                                        <img src="assets/images/movie/popcorn/pop2.png" alt="movie/popcorn">
                                        <div class="offer-tag">
                                            Rs: 57
                                        </div>
                                        <div class="offer-remainder">
                                            <h6 class="o-title mt-0">24%</h6>
                                            <span>off</span>
                                        </div>
                                    </div>
                                    <div class="grid-content">
                                        <h5 class="subtitle">
                                            <a href="popcorn.php#0">
                                                Crispy Chiken Taco, Chiken Mucho Nachos
                                            </a>
                                        </h5>
                                        <div class="cart-button">
                                            <div class="cart-plus-minus">
                                                <input class="cart-plus-minus-box" type="text" name="qtybutton" value="0">
                                            </div>
                                            <button type="submit" class="custom-button add-to-cart">
                                                Add
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>




                            <div class="grid-item bevarage" data-price="57">
                                <div class="grid-inner">
                                    <div class="grid-thumb">
                                        <img src="assets/images/movie/popcorn/fff.jpg" alt="movie/popcorn" height="250px">
                                        <div class="offer-tag">
                                            Rs: 57
                                        </div>
                                        <div class="offer-remainder">
                                            <h6 class="o-title mt-0">24%</h6>
                                            <span>off</span>
                                        </div>
                                    </div>
                                    <div class="grid-content">
                                        <h5 class="subtitle">
                                            <a href="popcorn.php#0">
                                                Fress Coca Cola Drink With Ice Cubes
                                            </a>
                                        </h5>
                                        <div class="cart-button">
                                            <div class="cart-plus-minus">
                                                <input class="cart-plus-minus-box" type="text" name="qtybutton" value="0">
                                            </div>
                                            <button type="submit" class="custom-button add-to-cart">
                                                add
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>




                            <div class="grid-item bevarage " data-price="57">
                                <div class="grid-inner">
                                    <div class="grid-thumb">
                                        <img src="assets/images/movie/popcorn/ff.png" alt="movie/popcorn">
                                        <div class="offer-tag">
                                            Rs: 57
                                        </div>
                                        <div class="offer-remainder">
                                            <h6 class="o-title mt-0">24%</h6>
                                            <span>off</span>
                                        </div>
                                    </div>
                                    <div class="grid-content">
                                        <h5 class="subtitle">
                                            <a href="popcorn.php#0">
                                                Lemon Soda Drink, Free Sugar
                                            </a>
                                        </h5>
                                        <div class="cart-button">
                                            <div class="cart-plus-minus">
                                                <input class="cart-plus-minus-box" type="text" name="qtybutton" value="0">
                                            </div>
                                            <button type="submit" class="custom-button add-to-cart">
                                                add
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>



                            <!--popcorn start here-->


                            <div class="grid-item popcorn" data-price="100">
                                <div class="grid-inner">
                                    <div class="grid-thumb">
                                        <img src="assets/images/movie/popcorn/large.jpg" alt="movie/popcorn">
                                        <div class="offer-tag">
                                            Rs: 70
                                        </div>
                                        <div class="offer-remainder">
                                            <h6 class="o-title mt-0">24%</h6>
                                            <span>off</span>
                                        </div>
                                    </div>
                                    <div class="grid-content">
                                        <h5 class="subtitle">
                                            <a href="popcorn.php#0">
                                                Large Size Popcorn
                                            </a>
                                        </h5>
                                        <div class="cart-button">
                                            <div class="cart-plus-minus">
                                                <input class="cart-plus-minus-box" type="text" name="qtybutton" value="0">
                                            </div>
                                            <button type="submit" class="custom-button add-to-cart">
                                                add
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>






                            <div class="grid-item popcorn " data-price="70">
                                <div class="grid-inner">
                                    <div class="grid-thumb">
                                        <img src="assets/images/movie/popcorn/small.jpg" alt="movie/popcorn">
                                        <div class="offer-tag">
                                            Rs: 60
                                        </div>
                                        <div class="offer-remainder">
                                            <h6 class="o-title mt-0">24%</h6>
                                            <span>off</span>
                                        </div>
                                    </div>
                                    <div class="grid-content">
                                        <h5 class="subtitle">
                                            <a href="popcorn.php#0">
                                                Small Size Popcorn
                                            </a>
                                        </h5>
                                        <div class="cart-button">
                                            <div class="cart-plus-minus">
                                                <input class="cart-plus-minus-box" type="text" name="qtybutton" value="0">
                                            </div>
                                            <button type="submit" class="custom-button add-to-cart">
                                                add
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- popcorn end here-->




                        </div>
                    </div>
                </div>




                <div class="col-lg-4">
                    <div class="booking-summery bg-one">
                        <h4 class="title">booking summary</h4>
                        <ul>
                            <li>
                                <h6 class="subtitle"><?php echo htmlspecialchars($movie_name); ?></h6>
                                <span class="info"><?php echo htmlspecialchars($language) . ' - ' . htmlspecialchars($experience); ?></span>
                            </li>
                            <li>
                                <h6 class="subtitle"><span><?php echo htmlspecialchars($cinema); ?></span></h6>
                                <div class="info"><span><?php echo date('d M D', strtotime($selected_date)) . ', ' . htmlspecialchars($selected_time); ?></span></div>
                            </li>
                            <li>
                                <h6 class="subtitle"><span>Seats Numbers: </span></h6>
                                <div class="info"><span><?php echo htmlspecialchars($selectedSeats); ?></span></div>
                            </li>
                            <li>
                                <h6 class="subtitle mb-0"><span>Ticket's Price (Rs.)</span><span><?php echo htmlspecialchars($totalPrice); ?></span></h6>
                            </li>
                        </ul>
                        <ul class="side-shape">
                            <li>
                                <h6 class="subtitle"><span>Food & Beverages</span></h6>
                                <span class="info"><span id="snack-description">Not Selected</span></span>

                            </li>
                            <li>
                                <h6 class="subtitle"><span>Snack's Price</span> <span id="snack-price">0</span></h6>
                            </li>
                        </ul>
                        <ul>
                            <li>
                                <span class="info"><span>Total Price (Rs.)</span><span id="total-price">0</span></span>
                            </li>
                        </ul>
                        <div class="proceed-area text-center">
                            <h6 class="subtitle"><span>Amount Payable</span><span id="amount-payable">0</span></h6>
                            <a href="movie-checkout.php" class="custom-button back-button" >Proceed</a>
                        </div>

                        <div class="note">
                            <h5 class="title">Note :</h5>
                            <p>Please give us 15 minutes for Food preparation once you're at the cinema</p>
                        </div><br>
                    </div>
                </div>
            </div>
        </div>
        <br>
        <br>
        <!-- ==========Movie-Section========== -->

        <!-- ==========Newslater-Section========== -->
        <footer class="footer-section">
            <div class="newslater-section padding-bottom">
                <div class="container">
                    <div class="newslater-container bg_img" data-background="./assets/images/newslater/newslater-bg01.jpg">
                        <div class="newslater-wrapper">
                            <h5 class="cate">subscribe to ReserveYourSeat</h5>
                            <h3 class="title">to get exclusive benefits</h3>

                            <?php
                            // Include PHPMailer
                            include('smtp/PHPMailerAutoload.php');

                            // Initialize subscription status
                            $subscribed = false;

                            // Check if the form was submitted
                            if (isset($_POST['subscribe'])) {
                                $email = $_POST['subemail'];  // Get the email from the form

                                // Send the subscription email
                                $subject = "Welcome to ReserveYourSeat - Exclusive Movie Perks Await! 🍿";
                                $message = "
        <html>
            <body>
                <p>Hi newcomer!</p>
                <p>Thank you for subscribing to our newsletter at <strong>ReserveYourSeat</strong>! 🎉 We’re thrilled to have you on board, and we can’t wait to bring you the best of movies, deals, and special offers. 🌟</p>
                <p>🎟️ <strong>Here's what you can look forward to as a subscriber:</strong></p>
                <ul>
                    <li>Early Access to tickets for blockbuster movies 🕒</li>
                    <li>Exclusive Discounts on tickets and concessions 💸</li>
                    <li>Updates on exciting new releases and movie trends 📽️</li>
                    <li>And, of course, surprise goodies just for you! 🎁</li>
                </ul>
                <p>Stay Tuned! We’ll be sending some great offers your way soon. Make sure to keep an eye on your inbox 👀 so you don’t miss out on any of our fantastic deals!</p>
                <p>Thank you again for joining our community of movie lovers! If you have any questions or just want to say hi, feel free to reach out to us anytime. 💌</p>
                <p>🍿 Happy Watching!</p>
                <p>Best Regards,<br>ReserveYourSeat</p>
            </body>
        </html>";
                                $result = smtp_mailer($email, $subject, $message);

                                if ($result == 'Sent') {
                                    $subscribed = true;
                                } else {
                                    echo "<p>There was an error. Please try again.</p>";
                                }
                            }
                            ?>

                            <?php if ($subscribed): ?>
                                <p>Thank you for subscribing! You will receive updates at <?php echo htmlspecialchars($email); ?>.</p>
                            <?php else: ?>
                                <!-- Show the form if not subscribed -->
                                <form class="newslater-form" method="POST" action="">
                                    <input type="email" name="subemail" placeholder="Your Email Address" required>
                                    <button type="submit" name="subscribe">Subscribe</button>
                                </form>
                                <p>We respect your privacy, so we never share your info</p>
                            <?php endif; ?>

                        </div>
                    </div>
                </div>
            </div>
        </footer>

        <?php
        // Function to send the email using SMTP
        function smtp_mailer($to, $subject, $msg)
        {
            $mail = new PHPMailer();
            $mail->IsSMTP();
            $mail->SMTPAuth = true;
            $mail->SMTPSecure = 'tls';
            $mail->Host = "smtp.gmail.com";
            $mail->Port = 587;
            $mail->IsHTML(true);
            $mail->CharSet = 'UTF-8';
            //$mail->SMTPDebug = 2;  // Uncomment for debugging
            $mail->Username = "ayushrajput2339@gmail.com"; // Replace with your email
            $mail->Password = "gnbejxyldfuiuzfg"; // Replace with your app-specific password
            $mail->SetFrom("ayushrajput2339@gmail.com"); // Replace with your email
            $mail->Subject = $subject;
            $mail->Body = $msg;
            $mail->AddAddress($to);
            $mail->SMTPOptions = array(
                'ssl' => array(
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                )
            );

            if (!$mail->Send()) {
                return $mail->ErrorInfo;
            } else {
                return 'Sent';
            }
        }
        ?>
        <div class="container">
            <div class="footer-top">
                <div class="logo">
                    <a href="https://pixner.net/ReserveYourSeat/demo/index-1.php">
                        <img src="assets/images/logo/logo.png" alt="footer" width="350px" height="55px">
                    </a>
                </div>
                <ul class="social-icons">
                    <li>
                        <a href="index.php">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                    </li>
                    <li>
                        <a href="index.php" class="active">
                            <i class="fab fa-twitter"></i>
                        </a>
                    </li>
                    <li>
                        <a href="index.php">
                            <i class="fab fa-pinterest-p"></i>
                        </a>
                    </li>
                    <li>
                        <a href="index.php">
                            <i class="fab fa-google"></i>
                        </a>
                    </li>
                    <li>
                        <a href="index.php">
                            <i class="fab fa-instagram"></i>
                        </a>
                    </li>
                </ul>
            </div>
            <div class="footer-bottom">
                <div class="footer-bottom-area">
                    <div class="left">
                        <p>Copyright © 2024.All Rights Reserved By <a href="index.php">ReserveYourTicket </a></p>
                    </div>
                    <ul class="links">
                        <li>
                            <a href="about.php">About</a>
                        </li>
                        <li>
                            <a href="about.php">Terms Of Use</a>
                        </li>
                        <li>
                            <a href="about.php">Privacy Policy</a>
                        </li>
                        <li>
                            <a href="blog.php">FAQ</a>
                        </li>
                        <li>
                            <a href="contact.php">Feedback</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        </footer>
        <!-- ==========Newslater-Section========== -->
        <script>
            function toggleUserDropdown() {
                const userDropdownMenu = document.getElementById("userDropdownMenu");
                userDropdownMenu.style.display = userDropdownMenu.style.display === "block" ? "none" : "block";
            }

            // Close the dropdown if clicked outside
            window.onclick = function(event) {
                const userDropdownMenu = document.getElementById("userDropdownMenu");
                if (!event.target.closest('.user-logo') && userDropdownMenu.style.display === "block") {
                    userDropdownMenu.style.display = "none";
                }
            };
        </script>

        <script>
            function saveTotalPriceToSession(totalPrice= <?php echo htmlspecialchars($totalPrice); ?>, i="" , sp=0) {
    fetch('save_total_price.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ total_price: totalPrice , item_summary : i , snack_price: sp})
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            console.log("Total price saved to session successfully.");
        } else {
            console.error("Failed to save total price to session.");
        }
    })
    .catch(error => console.error("Error:", error));
}


            document.addEventListener('DOMContentLoaded', () => {
                const addToCartButtons = document.querySelectorAll('.add-to-cart');
                const snackPriceElement = document.getElementById('snack-price');
                const proceedButton = document.querySelector('.back-button');
                const totalPriceElement = document.getElementById('total-price');
                const amountPayableElement = document.getElementById('amount-payable');
                const snackDescriptionElement = document.getElementById('snack-description');
                amountPayableElement.textContent = <?php echo htmlspecialchars($totalPrice); ?>;
                let totalAmount = 0;
                let itemSummary = "";

                // Parse seat price from PHP session as a number
                const seatPrice = parseFloat(<?php echo json_encode($_SESSION['seat_price']); ?>);
                
                addToCartButtons.forEach(button => {
                    button.addEventListener('click', (event) => {
                        event.preventDefault();
                        const itemContainer = event.target.closest('.grid-item');
                        const pricePerItem = parseFloat(itemContainer.getAttribute('data-price'));
                        const quantityInput = itemContainer.querySelector('.cart-plus-minus-box');
                        const quantity = parseInt(quantityInput.value) || 0;
                        const itemName = itemContainer.querySelector('.subtitle').textContent.trim();

                        if (quantity > 0) {
                            const itemTotal = pricePerItem * quantity;
                            totalAmount += itemTotal;
                            itemSummary += `${quantity}-${itemName}<br>`;
                            snackDescriptionElement.innerHTML = itemSummary.slice(0, -2);
                            snackPriceElement.textContent = `${totalAmount}`;

                            // Calculate total price with seat price
                            totalPriceElement.textContent = `${totalAmount + seatPrice}`;
                            amountPayableElement.textContent = `${totalAmount + seatPrice}`;

                            quantityInput.value = 0;
                            saveTotalPriceToSession(totalAmount + seatPrice , itemSummary.slice(0,-2) , totalAmount);
                        }
                    });
                });
                proceedButton.addEventListener('click' , saveTotalPriceToSession(totalAmount + seatPrice , itemSummary.slice(0,-2) , totalAmount))
                

            });
        </script>



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