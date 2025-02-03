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
    <section class="main-page-header speaker-banner bg_img" data-background="./assets/images/banner/banner07.jpg">
        <div class="container">
            <div class="speaker-banner-content">
                <h2 class="title">Blog Details</h2>
                <ul class="breadcrumb">
                    <li>
                        <a href="index.php">
                            Home
                        </a>
                    </li>
                    <li>
                        <a href="blog.php">
                            blog
                        </a>
                    </li>
                    <li>
                        blog details
                    </li>
                </ul>
            </div>
        </div>
    </section>
    <!-- ==========Banner-Section========== -->

    <!-- ==========Blog-Section========== -->
    <section class="blog-section padding-top padding-bottom">
        <div class="container">
            <div class="row justify-content-center mb-30-none">
                <div class="col-lg-8 mb-50 mb-lg-0">
                    <article>
                        <div class="post-item post-details">
                            <div class="post-thumb">
                                <img src="assets/images/blog/blog01.jpg" alt="blog">
                            </div>
                            <div class="post-content">
                                <div class="post-meta text-center">
                                    <div class="item">
                                        <a href="blog-details.php#0">
                                            <i class="flaticon-conversation"></i>
                                            <span>30</span>
                                        </a>
                                    </div>
                                    <div class="item">
                                        <a href="blog-details.php#0">
                                            <i class="flaticon-share-1"></i>
                                            <span>22</span>
                                        </a>
                                    </div>
                                </div>
                                <div class="content">
                                    <div class="entry-content p-0">
                                        <div class="left">
                                            <span class="date">Dece 15, 2020 BY </span>
                                        </div>
                                    </div>
                                    <div class="post-header">
                                        <h4 class="m-title">
                                            Cheap Movie Tickets - Bring Your Crew to Check Out the Latest Flicks
                                        </h4>
                                        <p>
                                            Looking for a great deal on movie tickets? Grab 'em fast and enjoy the latest blockbusters without breaking the bank. You and your friends deserve a fun night out, so why wait? Get your tickets now!
                                        </p>
                                        <blockquote>
                                            You don’t wanna miss this one! These movies are gonna blow your mind! No excuses, get the squad together and let’s hit the theater.
                                        </blockquote>
                                        <p>
                                            Don’t stress about the price – we’ve got you covered. Grab your tickets online and skip the line. More time for popcorn and the good stuff.
                                        </p>
                                        <h4 class="title">
                                            Bring Your Crew to Check Out the Latest Flicks
                                        </h4>
                                        <p>
                                            Whether you're into action, romance, or comedy, there’s something for everyone. Grab your tickets and let's make it a movie night!
                                        </p>
                                        <p>
                                            It’s time to kick back, relax, and enjoy some top-tier entertainment. Don’t miss out – book your tickets now and let the good times roll!
                                        </p>
                                    </div>
                                    <div class="tags-area">
                                        <div class="tags">
                                            <span>
                                                Tags: &nbsp;
                                            </span>
                                            <div class="tags-item">
                                                <a href="blog-details.php#0">Tickets</a>
                                                <a href="blog-details.php#0">Coming Soon</a>
                                                <a href="blog-details.php#0">Movies</a>
                                            </div>
                                        </div>
                                        <ul class="social-icons">
                                            <li>
                                                <a href="blog-details.php#0">
                                                    <i class="fab fa-facebook-f"></i>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="blog-details.php#0" class="active">
                                                    <i class="fab fa-twitter"></i>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="blog-details.php#0">
                                                    <i class="fab fa-instagram"></i>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="blog-details.php#0">
                                                    <i class="fab fa-pinterest"></i>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="blog-details.php#0">
                                                    <i class="fab fa-google"></i>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="blog-author">
                                <div class="author-thumb">
                                    <a href="blog-details.php#0">
                                        <img src="assets/images/blog/author.jpg" alt="blog">
                                    </a>
                                </div>
                                <div class="author-content">
                                    <h5 class="title">
                                        <a href="blog-details.php#0">
                                            Lee Burke
                                        </a>
                                    </h5>
                                    <p>
                                        I’m all about movies, good times, and great deals. I’m here to keep you in the loop with the latest trends in the movie world!
                                    </p>
                                </div>
                            </div>
                            <div class="blog-comment">
                                <h5 class="title">Comments</h5>
                                <ul class="comment-area">
                                    <li>
                                        <div class="blog-thumb">
                                            <a href="blog-details.php#0">
                                                <img src="assets/images/blog/author.jpg" alt="blog">
                                            </a>
                                        </div>
                                        <div class="blog-thumb-info">
                                            <span>13 days ago</span>
                                            <h6 class="title"><a href="blog-details.php#0">Carl Morgan</a></h6>
                                        </div>
                                        <div class="blog-content">
                                            <p>
                                                Yo, these movie deals are insane! Can’t believe how easy it is to grab tickets. Definitely gonna do this again next weekend!
                                            </p>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="blog-thumb">
                                            <a href="blog-details.php#0">
                                                <img src="assets/images/blog/author.jpg" alt="blog">
                                            </a>
                                        </div>
                                        <div class="blog-thumb-info">
                                            <span>13 days ago</span>
                                            <h6 class="title"><a href="blog-details.php#0">John Flores</a></h6>
                                        </div>
                                        <div class="blog-content">
                                            <p>
                                                Seriously, this is the easiest way to book tickets. Fast, cheap, and I’m always in the loop with new releases. Love it!
                                            </p>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="blog-thumb">
                                            <a href="blog-details.php#0">
                                                <img src="assets/images/blog/author.jpg" alt="blog">
                                            </a>
                                        </div>
                                        <div class="blog-thumb-info">
                                            <span>13 days ago</span>
                                            <h6 class="title"><a href="blog-details.php#0">Carl Morgan</a></h6>
                                        </div>
                                        <div class="blog-content">
                                            <p>
                                                I just grabbed my tickets for the latest flick, and I’m pumped! Thanks for the awesome tips, Lee!
                                            </p>
                                        </div>
                                    </li>
                                </ul>


                                <div class="leave-comment">
                                    <h5 class="title">leave comment</h5>
                                    <form class="blog-form">
                                        <div class="form-group">
                                            <input type="text" placeholder="Enter Your Full Name" required>
                                        </div>
                                        <div class="form-group">
                                            <input type="text" placeholder="Enter Your Email Address" required>
                                        </div>
                                        <div class="form-group">
                                            <textarea placeholder="Write A Message" required></textarea>
                                        </div>
                                        <div class="form-group">
                                            <input type="submit" value="Submit Now">
                                        </div>
                                    </form>
                                </div>
                            </div>
                    </article>
                </div>
                <div class="col-lg-4 col-sm-10 col-md-8">
                    <aside>
                        <div class="widget widget-search">
                            <h5 class="title">search</h5>
                            <form class="search-form">
                                <input type="text" placeholder="Enter your Search Content" required>
                                <button type="submit"><i class="flaticon-loupe"></i>Search</button>
                            </form>
                        </div>
                        <div class="widget widget-post">
                            <h5 class="title">latest post</h5>
                            <div class="slider-nav">
                                <span class="flaticon-angle-pointing-to-left widget-prev"></span>
                                <span class="flaticon-right-arrow-angle widget-next active"></span>
                            </div>
                            <div class="widget-slider owl-carousel owl-theme">
                                <div class="item">
                                    <div class="thumb">
                                        <a href="blog-details.php#0">
                                            <img src="assets/images/blog/slider01.jpg" alt="blog">
                                        </a>
                                    </div>
                                    <div class="content">
                                        <h6 class="p-title">
                                            <a href="blog-details.php#0">Three Ways to Book Sporting Event Tickets</a>
                                        </h6>
                                        <div class="meta-post">
                                            <a href="blog-details.php#0" class="mr-4"><i class="flaticon-loupe"></i>20 Comments</a>
                                            <a href="blog-details.php#0"><i class="flaticon-loupe"></i>466 View</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="item">
                                    <div class="thumb">
                                        <a href="blog-details.php#0">
                                            <img src="assets/images/blog/slider01.jpg" alt="blog">
                                        </a>
                                    </div>
                                    <div class="content">
                                        <h6 class="p-title">
                                            <a href="blog-details.php#0">Three Ways to Book Sporting Event Tickets</a>
                                        </h6>
                                        <div class="meta-post">
                                            <a href="blog-details.php#0" class="mr-4"><i class="flaticon-loupe"></i>20 Comments</a>
                                            <a href="blog-details.php#0"><i class="flaticon-loupe"></i>466 View</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="widget widget-follow">
                            <h5 class="title">Follow Us</h5>
                            <ul class="social-icons">
                                <li>
                                    <a href="blog-details.php#0" class="">
                                        <i class="fab fa-facebook-f"></i>
                                    </a>
                                </li>
                                <li>
                                    <a href="blog-details.php#0" class="active">
                                        <i class="fab fa-twitter"></i>
                                    </a>
                                </li>
                                <li>
                                    <a href="blog-details.php#0" class="">
                                        <i class="fab fa-pinterest-p"></i>
                                    </a>
                                </li>
                                <li>
                                    <a href="blog-details.php#0">
                                        <i class="fab fa-google"></i>
                                    </a>
                                </li>
                                <li>
                                    <a href="blog-details.php#0">
                                        <i class="fab fa-instagram"></i>
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <div class="widget widget-categories">
                            <h5 class="title">categories</h5>
                            <ul>
                                <li>
                                    <a href="blog-details.php#0">
                                        <span>Showtimes & Tickets</span><span>50</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="blog-details.php#0">
                                        <span>Latest Trailers</span><span>43</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="blog-details.php#0">
                                        <span>Coming Soon </span><span>34</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="blog-details.php#0">
                                        <span>In Theaters</span><span>63</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="blog-details.php#0">
                                        <span>Release Calendar </span><span>11</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="blog-details.php#0">
                                        <span>Stars</span><span>30</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="blog-details.php#0">
                                        <span>Horror Movie </span><span>55</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <div class="widget widget-tags">
                            <h5 class="title">featured tags</h5>
                            <ul>
                                <li>
                                    <a href="blog-details.php#0">creative</a>
                                </li>
                                <li>
                                    <a href="blog-details.php#0">design</a>
                                </li>
                                <li>
                                    <a href="blog-details.php#0">skill</a>
                                </li>
                                <li>
                                    <a href="blog-details.php#0">template</a>
                                </li>
                                <li>
                                    <a href="blog-details.php#0" class="active">landing</a>
                                </li>
                            </ul>
                        </div>
                    </aside>
                </div>
            </div>
        </div>
    </section>
    <!-- ==========Blog-Section========== -->

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
                <a href="https://pixner.net/ReserveYourSeat/demo/index-1.html">
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
    <script src="assets/js/contact.js"></script>
    <script src="assets/js/main.js"></script>
</body>

</html>