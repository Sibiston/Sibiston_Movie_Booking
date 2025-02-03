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
    <link rel="stylesheet" href="assets/css/main.css">

    <link rel="shortcut icon" href="assets/images/favicon.png" type="image/x-icon">

    <title>ReserveYourSeat - Online Ticket Booking Website HTML Template</title>
<style>
    .user-account-section {
        display: flex;
        align-items: center;
        justify-content: flex-end; /* Aligns content to the right end */
        position: relative;
        padding-right: 20px; /* Optional: Adds some padding on the right end */
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
        display: none; /* Hidden by default */
        position: absolute;
        background-color: white;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        border-radius: 5px;
        padding: 10px;
        z-index: 1;
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
    color: #ff9900; /* Color for 'Hello' */
}

.name-text {
    color: #ffffff; /* Color for 'Sibiston!' */
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
    <a href="about.php#0" class="scrollToTop">
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
                        <a href="movie-grid.php#0" >movies</a>
                        <ul class="submenu">
                            <li>
                                <a href="movie-grid.php#0" class="active">Movie List</a>
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
                        <a href="about.php" class="active">About Us</a>

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
                <h2 class="title">about us</h2>
                <ul class="breadcrumb">
                    <li>
                        <a href="index-2.php">
                            Home
                        </a>
                    </li>
                    <li>
                        about us
                    </li>
                </ul>
            </div>
        </div>
    </section>
    <!-- ==========Banner-Section========== -->
    
    <!-- ==========Speaker-Single========== -->
    <section class="about-section padding-top padding-bottom">
        <div class="container">
            <div class="row justify-content-between">
                <div class="col-lg-6">
                    <div class="event-about-content">
                        <div class="section-header-3 left-style m-0">
                            <span class="cate">we are ReserveYourTicket </span>
                            <h2 class="title">Get to know us</h2>
                            <p>
                               Reserve Your Ticket" is a seamless and user-friendly platform designed to provide moviegoers with an efficient and convenient way to book their movie tickets online.
                            </p>
                            <p>
                                Whether you're planning to watch the latest blockbuster or an indie film, our website ensures a smooth and hassle-free experience.
                            </p>
                            <a href="movie-grid.php" class="custom-button">book tickets</a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-5 d-none d-lg-block">
                    <div class="about-thumb">
                        <img src="assets/images/about/about01.png" alt="about">
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- ==========Speaker-Single========== -->

    <!-- ==========Philosophy-Section========== -->
    <div class="philosophy-section padding-top padding-bottom bg-one bg_img bg_quater_img" data-background="./assets/images/about/about-bg.jpg">
        <div class="container">
            <div class="row">
                <div class="col-lg-9 offset-lg-3 bg-two">
                    <div class="philosophy-content">
                        <div class="section-header-3 left-style">
                            <span class="cate">Take look at</span>
                            <h2 class="title">Our philosophy</h2>
                            <p class="ml-0">
                            Pain itself, labor and sorrow, are to be undertaken and endured by those who desire some benefit. But in order to obtain even the smallest pleasure, we must exercise effort and tolerance. Let those who wish to experience tranquility avoid labor, unless it brings some relief or pleasure.
                            </p>
                        </div>
                        <ul class="phisophy-list">
                            <li>
                                <div class="thumb">
                                    <img src="assets/images/philosophy/icon1.png" alt="philosophy">
                                </div>
                                <h5 class="title">Honesty & Fairness </h5>
                            </li>
                            <li>
                                <div class="thumb">
                                    <img src="assets/images/philosophy/icon2.png" alt="philosophy">
                                </div>
                                <h5 class="title">Clarity & Transparency</h5>
                            </li>
                            <li>
                                <div class="thumb">
                                    <img src="assets/images/philosophy/icon3.png" alt="philosophy">
                                </div>
                                <h5 class="title">Focus on Customers</h5>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- ==========Philosophy-Section========== -->

    <!-- ==========About-Counter-Section========== -->
    <section class="about-counter-section padding-bottom padding-top">
        <div class="container">
            <div class="row">
                <div class="col-lg-4">
                    <div class="section-header-3 left-style mb-lg-0">
                        <span class="cate">quick facts</span>
                        <h2 class="title">fun facts</h2>
                        <p>Objectively seize scalable metrics whereas proactive services seamlessly empower fully researched growth strategies</p>
                    </div>
                </div>
                <div class="col-lg-8">
                    <div class="about-counter">
                        <div class="counter-item">
                            <div class="counter-thumb">
                                <img src="assets/images/about/about-counter01.png" alt="about">
                            </div>
                            <div class="counter-content">
                                <h3 class="title odometer" data-odometer-final="30"></h3>
                                <h3 class="title">M+</h3>
                            </div>
                            <span class="d-block info">Customers</span>
                        </div>
                        <div class="counter-item">
                            <div class="counter-thumb">
                                <img src="assets/images/about/about-counter02.png" alt="about">
                            </div>
                            <div class="counter-content">
                                <h3 class="title odometer" data-odometer-final="11"></h3>
                            </div>
                            <span class="d-block info">Contries</span>
                        </div>
                        <div class="counter-item">
                            <div class="counter-thumb">
                                <img src="assets/images/about/about-counter03.png" alt="about">
                            </div>
                            <div class="counter-content">
                                <h3 class="title odometer" data-odometer-final="650"></h3>
                                <h3 class="title">+</h3>
                            </div>
                            <span class="d-block info">Towns & Cities</span>
                        </div>
                        <div class="counter-item">
                            <div class="counter-thumb">
                                <img src="assets/images/about/about-counter04.png" alt="about">
                            </div>
                            <div class="counter-content">
                                <h3 class="title odometer" data-odometer-final="5000"></h3>
                                <h3 class="title">+</h3>
                            </div>
                            <span class="d-block info">Screens</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- ==========About-Counter-Section========== -->

    <!-- ==========Client-Section========== -->
    <section class="client-section padding-bottom padding-top bg_img" data-background="./assets/images/client/client-bg.jpg">
        <div class="container">
            <div class="section-header-3">
                <span class="cate">testimonials</span>
                <h2 class="title">the fans have spoken</h2>
            </div>
            <div class="client-slider owl-carousel owl-theme">
                <div class="client-item">
                    <div class="client-thumb">
                        <img src="assets/images/userm1.jpg" alt="client">
                    </div>
                    <div class="client-content">
                        <h5 class="title">
                            <a href="about.php#0">Amit Singh</a>
                        </h5>
                        <span class="info"><i class="fas fa-check"></i> Verified</span>
                        <blockquote class="client-quote">
                            "Great prices and Cheaper than other sites! Easy to use."
                        </blockquote>
                    </div>
                </div>
                <div class="client-item">
                    <div class="client-thumb">
                        <img src="assets/images/userm2.jpg" alt="client">
                    </div>
                    <div class="client-content">
                        <h5 class="title">
                            <a href="about.php#0">Rudra Pratap</a>
                        </h5>
                        <span class="info"><i class="fas fa-check"></i> Verified</span>
                        <blockquote class="client-quote">
                            "It is a right to be there in the light, in itself, not blessed together."
                        </blockquote>
                    </div>
                </div>
                <div class="client-item">
                    <div class="client-thumb">
                        <img src="assets/images/userg1.jpg" alt="client">
                    </div>
                    <div class="client-content">
                        <h5 class="title">
                            <a href="about.php#0">Priya Das</a>
                        </h5>
                        <span class="info"><i class="fas fa-check"></i> Verified</span>
                        <blockquote class="client-quote">
                            "It is love, concerning the elite. The mind, how it follows."
                        </blockquote>
                    </div>
                </div>
                <div class="client-item">
                    <div class="client-thumb">
                        <img src="assets/images/userg2.jpg" alt="client">
                    </div>
                    <div class="client-content">
                        <h5 class="title">
                            <a href="about.php#0">Smriti Raina</a>
                        </h5>
                        <span class="info"><i class="fas fa-check"></i> Verified</span>
                        <blockquote class="client-quote">
                            "Because of the pleasures of the mind, the free rejection of error."
                        </blockquote>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- ==========Client-Section========== -->

    <!-- ==========Speaker-Section========== -->
    <section class="speaker-section padding-bottom padding-top">
        <div class="container">
            <div class="section-header-3">
                <span class="cate">meet our most valued</span>
                <h2 class="title">expert team members</h2>
                <p>World is committed to making participation in the event a harassment free experience for 
                everyone, regardless of level of experience, gender, gender identity and expression</p>
            </div>
           
                
                    <div class="speaker-item">
                        <div class="speaker-thumb">
                            <a href="event-speaker.php">
                                <img src="assets/images/speaker/photo1.jpg" alt="speaker">
                            </a>
                        </div>
                        <div class="speaker-content">
                            <h5 class="title">
                                <a href="event-speaker.php">
                                    Sibiston Basumatary
                                </a>
                            </h5>
                            <span>FOUNDER, CEO</span>
                        </div>
                    </div>
                </div>
            
            
        </div>
    </section>
    <!-- ==========Speaker-Section========== -->

    <!-- ==========Gallery-Section========== -->
    <section class="gallery-section padding-top padding-bottom bg-one">
        <div class="container">
            <div class="section-header-3">
                <span class="cate">Take a look at our</span>
                <h2 class="title">A ticket for every fan.</h2>
                <p>World is committed to making participation in the event a harassment free experience for 
                    everyone, regardless of level of experience, gender, gender identity and expression</p>
            </div>
            <div class="row justify-content-center gallery-wrapper mb-30-none">
                <div class="col-lg-3 col-sm-6">
                    <div class="gallery-item two">
                        <div class="gallery-thumb">
                            <a href="assets/images/gallery/gallery05.jpg" class="img-pop">
                                <i class="flaticon-loupe"></i>
                            </a>
                            <img src="assets/images/gallery/gallery05.jpg" alt="gallery">
                        </div>
                    </div>
                    <div class="gallery-item two">
                        <div class="gallery-thumb">
                            <a href="assets/images/gallery/gallery06.jpg" class="img-pop">
                                <i class="flaticon-loupe"></i>
                            </a>
                            <img src="assets/images/gallery/gallery06.jpg" alt="gallery">
                        </div>
                    </div>
                    <div class="gallery-item two">
                        <div class="gallery-thumb">
                            <a href="assets/images/gallery/gallery07.jpg" class="img-pop">
                                <i class="flaticon-loupe"></i>
                            </a>
                            <img src="assets/images/gallery/gallery07.jpg" alt="gallery">
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-3 order-lg-1">
                    <div class="gallery-item two">
                        <div class="gallery-thumb">
                            <a href="assets/images/gallery/gallery11.jpg" class="img-pop">
                                <i class="flaticon-loupe"></i>
                            </a>
                            <img src="assets/images/gallery/gallery11.jpg" alt="gallery">
                        </div>
                    </div>
                    <div class="gallery-item two">
                        <div class="gallery-thumb">
                            <a href="assets/images/gallery/gallery12.jpg" class="img-pop">
                                <i class="flaticon-loupe"></i>
                            </a>
                            <img src="assets/images/gallery/gallery12.jpg" alt="gallery">
                        </div>
                    </div>
                    <div class="gallery-item two">
                        <div class="gallery-thumb">
                            <a href="assets/images/gallery/gallery13.jpg" class="img-pop">
                                <i class="flaticon-loupe"></i>
                            </a>
                            <img src="assets/images/gallery/gallery13.jpg" alt="gallery">
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="gallery-item two">
                        <div class="gallery-thumb">
                            <a href="assets/images/gallery/gallery08.jpg" class="img-pop">
                                <i class="flaticon-loupe"></i>
                            </a>
                            <img src="assets/images/gallery/gallery08.jpg" alt="gallery">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="gallery-item two">
                                <div class="gallery-thumb">
                                    <a href="assets/images/gallery/gallery09.jpg" class="img-pop">
                                        <i class="flaticon-loupe"></i>
                                    </a>
                                    <img src="assets/images/gallery/gallery09.jpg" alt="gallery">
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="gallery-item two">
                                <div class="gallery-thumb">
                                    <a href="assets/images/gallery/gallery10.jpg" class="img-pop">
                                        <i class="flaticon-loupe"></i>
                                    </a>
                                    <img src="assets/images/gallery/gallery10.jpg" alt="gallery">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- ==========Gallery-Section========== -->

    <!-- ==========Tour-Section========== -->
    <section class="tour-section padding-top padding-bottom">
        <div class="container">
            <div class="row">
                <div class="col-lg-6">
                    <div class="tour-content">
                        <div class="section-header-3 left-style">
                            <span class="cate">take a look at our tour</span>
                            <h2 class="title">Guarantees you can trust.</h2>
                            <p class="ml-0">
                                Because more peace of mind means more love for the event.
                            </p>
                        </div>
                        <ul class="list-tour">
                            <li>
                                <div class="thumb">
                                    <img src="assets/images/tour/icon01.png" alt="tour">
                                </div>
                                <div class="content">
                                    <h5 class="title">Get In Guarantee</h5>
                                    <p>Authentic tickets, on-time delivery, and access to 
                                        your event. Or your money back. Period.</p>
                                </div>
                            </li>
                            <li>
                                <div class="thumb">
                                    <img src="assets/images/tour/icon02.png" alt="tour">
                                </div>
                                <div class="content">
                                    <h5 class="title">price match guarantee</h5>
                                    <p>The best prices are here. If you spot a better deal 
                                        elsewhere, we’ll cover the difference.</p>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-6 d-none d-lg-block">
                    <div class="tour-thumb">
                        <img src="assets/images/tour/tour.png" alt="tour">
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- ==========Tour-Section========== -->

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