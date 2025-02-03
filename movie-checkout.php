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
$user_email ="ayushrajput2339@gmail.com";
// Fetch the user's name from the database
$sql = "SELECT name, email FROM users WHERE id = ?";
$stmt = $con->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($user_name, $user_email);
$stmt->fetch();
$stmt->close();



$cinema = isset($_SESSION['selected_cinema']) ? $_SESSION['selected_cinema'] : 'Not selected';
$movie_name = isset($_SESSION['movie_name']) ? $_SESSION['movie_name'] : 'Not selected';
$language = isset($_SESSION['selected_language']) ? $_SESSION['selected_language'] : 'Not selected';
$experience = isset($_SESSION['selected_experience']) ? $_SESSION['selected_experience'] : 'Not selected';
$selected_date = isset($_SESSION['selected_date']) ? $_SESSION['selected_date'] : 'Not selected';
$selected_time = isset($_SESSION['selected_time']) ? $_SESSION['selected_time'] : 'Not selected';
$selectedSeats = isset($_SESSION['selected_seat_numbers']) ? $_SESSION['selected_seat_numbers'] : 'None';
$seatPrice = isset($_SESSION['seat_price']) ? $_SESSION['seat_price'] : '0';
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



$totalPrice = isset($_SESSION['total_price']) ? $_SESSION['total_price'] : '0';
$snackPrice = isset($_SESSION['snack_price']) ? $_SESSION['snack_price'] : '0';
$itemSummary = isset($_SESSION['item_summary']) ? $_SESSION['item_summary'] : 'Not Selected';



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
                        <a href="movie-checkout.php#0"><?php echo $cinema; ?></a>
                        <a href="movie-checkout.php#0"><?php echo $language . ' - ' . $experience; ?></a>
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
                    <a href="popcorn.php" class="custom-button back-button">
                        <i class="flaticon-double-right-arrows-angles"></i>back
                    </a>
                </div>
                <div class="item date-item">
                    <span class="date"><?php echo $formatted_date; ?></span>
                    <div class="article-section">
                        <a class="view-all" href="movie-checkout.php#"><?php echo $selected_time !== 'Not selected' ? $selected_time : ''; ?></a>
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
                    
                    <div class="checkout-widget checkout-contact">
                        <h5 class="title">Share your Contact  Details</h5>
                        <form class="checkout-contact-form">
                            <div class="form-group">
                                <input type="text" placeholder="Full Name" required>
                            </div>
                            <div class="form-group">
                                <input type="text" value="<?php echo htmlspecialchars($user_email);?>" style="display: none;">
                               <span style="color: greenyellow;"> Registered Email :</span>  <?php echo htmlspecialchars($user_email); ?>
                            </div>
                            <div class="form-group">
                                <input type="text" placeholder="Enter your Phone Number " required  pattern="^\d{10}$"  title="Please enter exactly 10 digits">
                            </div>
                            <div class="form-group">
                                <input type="submit" value="Continue" class="custom-button">
                            </div>
                        </form>
                    </div>
                    <div class="checkout-widget checkout-contact">
                        <h5 class="title">Promo Code </h5>
                        <form class="checkout-contact-form">
                            <div class="form-group">
                                <input type="text" placeholder="Please enter promo code">
                            </div>
                            <div class="form-group">
                                <input type="submit" value="Verify" class="custom-button">
                            </div>
                        </form>
                    </div>
                    <div class="checkout-widget checkout-card mb-0">
                        <h5 class="title">Payment Gateway</h5>
                        
                        
                        <form class="payment-card-form" action="payment.php" method="post">
                            
                            <div class="form-group check-group">
                                <input id="card5" type="checkbox" required>
                                <label for="card5">
                                    <span class="title">RazorPay</span>
                                    <span class="info">Safe , Secure and faster payments.</span>
                                </label>
                            </div>
                            <div class="form-group">
                                <input type="submit" class="custom-button" value="make payment">
                                <input type="text" name="bill_name" id="bill_name" style="display: none;">
                                <input type="text" name="phone" id="phone" style="display: none;">
                            </div>
                        </form>
                        <p class="notice">
                            By Clicking "Make Payment" you agree to the <a href="movie-checkout.php#0">terms and conditions</a>
                        </p>
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
                                <h6 class="subtitle mb-0"><span>Ticket's Price (Rs.)</span><span><?php echo htmlspecialchars($seatPrice); ?></span></h6>
                            </li>
                        </ul>
                        <ul class="side-shape">
                        <li>
        <h6 class="subtitle"><span>Food & Beverages</span></h6>
        <span class="info"><span id="snack-description"><?php echo htmlspecialchars_decode($itemSummary); ?></span></span>

    </li>
    <li>
        <h6 class="subtitle"><span>Snack's Price</span> <span id="snack-price"><?php echo htmlspecialchars($snackPrice); ?></span></h6>
    </li>
</ul>
<ul>
    <li>
        <span class="info"><span>Total Price (Rs.)</span><span id="total-price"><?php echo htmlspecialchars($totalPrice); ?></span></span>
    </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
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
  document.addEventListener('DOMContentLoaded', () => {
  const form = document.querySelector('.checkout-contact-form');
  const bookingSummary = document.querySelector('.booking-summery ul');
  const contactsDiv = document.querySelector('.checkout-widget.checkout-contact'); // Get the div with the class

  // Initially hide the contacts div
  

  form.addEventListener('submit', (event) => {
    event.preventDefault();

    // Retrieve form data
    const fullName = form.querySelector('input[placeholder="Full Name"]').value;
    const phoneNumber = form.querySelector('input[placeholder="Enter your Phone Number "]').value;
    const userEmail = "<?php echo htmlspecialchars($user_email); ?>";
    const bill_name = document.getElementById("bill_name")
    const phone = document.getElementById("phone")
    // Create HTML for user info to be prepended
    const userInfoHtml = `
      <li>
        <h6 class="subtitle"><span>Full Name:</span><span>${fullName}</span></h6>
      </li>
      <li>
        <h6 class="subtitle"><span>Email:</span><span>${userEmail}</span></h6>
      </li>
      <li>
        <h6 class="subtitle"><span>Phone Number:</span><span>+91 ${phoneNumber}</span></h6>
      </li>
    `;
    bill_name.value = fullName;
    phone.value = phoneNumber;

    // Insert at the beginning of bookingSummary
    bookingSummary.insertAdjacentHTML("afterbegin", userInfoHtml);

    // Show the 'contacts' div by setting its display to block
    contactsDiv.style.display = 'none';
  });
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