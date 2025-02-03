<?php
session_start();

if (!isset($_SESSION['payment_visited']) || $_SESSION['payment_visited'] !== true) {
    header("Location: 404.php");
    exit();
}
unset($_SESSION['payment_visited']);

include('connection.php');  // Include the connection file
include('smtp/PHPMailerAutoload.php'); // Include PHPMailer
// Check if the user is not logged in
if (!isset($_SESSION['user_id']) && !isset($_COOKIE['user_id'])) {
    // Redirect to login page if the user is not logged in
    header("Location: sign-in.php");
    exit();  // Ensure no further code is executed
}

// Fetch the user's name based on user_id
$user_id = $_SESSION['user_id'] ?? $_COOKIE['user_id'];
$user_name = "Guest";  // Default name if not found
$user_email = "ayushrajput2339@gmail.com";
// Fetch the user's name from the database
$sql = "SELECT name, email FROM users WHERE id = ?";
$stmt = $con->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($user_name, $user_email);
$stmt->fetch();
$stmt->close();


$cinema = isset($_SESSION['movie_id']) ? $_SESSION['movie_id'] : 'Not selected';
$cinema = isset($_SESSION['selected_cinema']) ? $_SESSION['selected_cinema'] : 'Not selected';
$movie_name = isset($_SESSION['movie_name']) ? $_SESSION['movie_name'] : 'Not selected';
$language = isset($_SESSION['selected_language']) ? $_SESSION['selected_language'] : 'Not selected';
$experience = isset($_SESSION['selected_experience']) ? $_SESSION['selected_experience'] : 'Not selected';
$selected_date = isset($_SESSION['selected_date']) ? $_SESSION['selected_date'] : 'Not selected';
$selected_time = isset($_SESSION['selected_time']) ? $_SESSION['selected_time'] : 'Not selected';
$selected_city = isset($_SESSION['selected_city']) ? $_SESSION['selected_city'] : 'Not selected';
$selectedSeats = isset($_SESSION['selected_seat_numbers']) ? $_SESSION['selected_seat_numbers'] : 'None';
$seatPrice = isset($_SESSION['seat_price']) ? $_SESSION['seat_price'] : '0';

$today = date("Y-m-d");  // Current date in "YYYY-MM-DD" format
$today_date = date("D, d M Y", strtotime($today));

// Format the date (assuming the format is "YYYY-MM-DD")
if ($selected_date !== 'Not selected') {
    $formatted_date = date("D, d M Y", strtotime($selected_date));
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
$ticketid = isset($_SESSION['ticket_id']) ? $_SESSION['ticket_id'] : 'NONE';
$billname = isset($_SESSION['bill_name']) ? $_SESSION['bill_name'] : 'NONE';
$phone = isset($_SESSION['phone']) ? $_SESSION['phone'] : 'NONE';




if (isset($_SESSION['movie_id'])) {
    $movie_id = $_SESSION['movie_id'];

    // Fetch movie details from the database based on the movie_id
    $query = "SELECT * FROM movies WHERE id = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param("i", $movie_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if a movie with the given ID exists
    if ($result->num_rows > 0) {
        $movie = $result->fetch_assoc();

        // Decode JSON fields for languages and genres

        $genres = json_decode($movie['genres'], true);
    } else {
        echo "Movie not found.";
    }

    // Close the statement and connection
    
} else {
    echo "No movie selected.";
}








// Fetch user details and movie data from session or database


// Set up the email content
$email_content = "
<html>
<head>
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #333;
            background-color: #f4f4f9;
            padding: 20px;
        }
        .container {
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            padding: 20px;
        }
        h2 {
            color: #ff6347;
            text-align: center;
            font-size: 24px;
        }
        p {
            font-size: 16px;
            line-height: 1.5;
        }
        .highlight {
            font-weight: bold;
            color: #2e8b57;
        }
        .movie-details {
            margin-top: 20px;
        }
        .movie-details div {
            margin-bottom: 10px;
        }
        .movie-details span {
            font-weight: bold;
        }
        .emoji {
            font-size: 20px;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            font-size: 14px;
            color: #888;
        }
        .button {
            background-color: #ff6347;
            color: #fff;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            font-weight: bold;
            text-align: center;
            display: block;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class='container'>
        <h2>🎬 Booking Confirmation:" . strtoupper($movie_name) . "🎥</h2>
        <p>Hi <span class='highlight'>$user_name</span>,</p>
        <p>Thank you for booking your ticket with us! Here are the details of your movie booking:</p>

        <div class='movie-details'>
            <div><span class='highlight'>Movie Name:</span> " . strtoupper($movie_name) . "</div>
            <div><span class='highlight'>Amount Paid:</span> Rs. $totalPrice</div>
            <div><span class='highlight'>Ticket No.:</span> $ticketid</div>
            <div><span class='highlight'>Seats Booked:</span> " . strtoupper($selectedSeats) . "</div>
            <div><span class='highlight'>Language:</span> $language</div>
            <div><span class='highlight'>Experience:</span> $experience</div>
            <div><span class='highlight'>Cinema:</span> $cinema</div>
            <div><span class='highlight'>City:</span> $selected_city</div>
            <div><span class='highlight'>Show Time:</span> $selected_time</div>
            <div><span class='highlight'>Snacks Included:</span> " . html_entity_decode($itemSummary) . "</div>
        </div>

        <p class='footer'>We hope you have a great time watching your movie! 🍿🎬</p>
        <a href='http://localhost/PHP_Project/movie-list.php' class='button'>View Booking Details</a>
    </div>
</body>
</html>
";
$subject = "🎬 Your Movie Ticket is Booked! 🎟️ Confirmed Details Inside";
// Set up PHPMailer
$mail = new PHPMailer();
$mail->IsSMTP();
$mail->SMTPAuth = true;
$mail->SMTPSecure = 'tls';
$mail->Host = "smtp.gmail.com";
$mail->Port = 587;
$mail->IsHTML(true);
$mail->CharSet = 'UTF-8';
//$mail->SMTPDebug = 2;  // Uncomment for debugging
$mail->Username = "sibistonb@gmail.com"; // Replace with your email
        $mail->Password = "vvomdvuhedtxeqeq"; // Replace with your app-specific password
        $mail->SetFrom("sibistonb@gmail.com"); // Replace with your email
$mail->Subject = $subject;
$mail->Body = $email_content;
$mail->AddAddress($user_email);
$mail->SMTPOptions = array(
    'ssl' => array(
        'verify_peer' => false,
        'verify_peer_name' => false,
        'allow_self_signed' => true
    )
);
$mail_sent = false;
if (!$mail->Send()) {
    $error_message = $mail->ErrorInfo;
} else {
    $mail_sent = true;
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
    <a href="index.php" class="scrollToTop">
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

    <section class="movie-section padding-top padding-bottom">
        <section style="margin-top: 4%; margin-bottom: 4%;">
            <div class="banner-bg bg_img bg-fixed" data-background="./assets/images/banner/banner02.jpg"></div>
            <div class="container">
                <div class="banner-content">
                    <h1 class="title bold"> <span class="color-theme">Payment</span> Sucessfull</h1>
                    <p>Ticket details sent to email : <?php echo $user_email; ?></p>
                </div>
            </div>
        </section>
        <!-- ==========Banner-Section========== -->

        <!-- ==========Movie-Section========== -->
        <div class="container">

            <div class="col-lg-9 mb-50 mb-lg-0" style="margin-left: 12%;">
                <div class="filter-tab tab">

                    <div class="tab-area">
                        <div class="tab-item">
                            <div class="row mb-10 justify-content-center">

                                <div class="col-sm-6 col-lg-4">
                                    <div class="movie-grid">
                                        <div class="movie-thumb c-thumb">
                                            <a href="movie-details.php">
                                                <img src="assets/images/movie/movie2.jpg" alt="movie">
                                            </a>
                                        </div>
                                        <div class="movie-content bg-one">
                                            <h5 class="title m-0">
                                                <a href="movie-details.php">mars</a>
                                            </h5>
                                            <ul class="movie-rating-percent">
                                                <li>
                                                    <div class="thumb">
                                                        <img src="assets/images/movie/tomato.png" alt="movie">
                                                    </div>
                                                    <span class="content">88%</span>
                                                </li>
                                                <li>
                                                    <div class="thumb">
                                                        <img src="assets/images/movie/cake.png" alt="movie">
                                                    </div>
                                                    <span class="content">88%</span>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-lg-4">
                                    <div class="movie-grid">
                                        <div class="movie-thumb c-thumb">
                                            <a href="movie-details.php">
                                                <img src="assets/images/movie/movie3.jpg" alt="movie">
                                            </a>
                                        </div>
                                        <div class="movie-content bg-one">
                                            <h5 class="title m-0">
                                                <a href="movie-details.php">venus</a>
                                            </h5>
                                            <ul class="movie-rating-percent">
                                                <li>
                                                    <div class="thumb">
                                                        <img src="assets/images/movie/tomato.png" alt="movie">
                                                    </div>
                                                    <span class="content">88%</span>
                                                </li>
                                                <li>
                                                    <div class="thumb">
                                                        <img src="assets/images/movie/cake.png" alt="movie">
                                                    </div>
                                                    <span class="content">88%</span>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-lg-4">
                                    <div class="movie-grid">
                                        <div class="movie-thumb c-thumb">
                                            <a href="movie-details.php">
                                                <img src="assets/images/movie/movie4.jpg" alt="movie">
                                            </a>
                                        </div>
                                        <div class="movie-content bg-one">
                                            <h5 class="title m-0">
                                                <a href="movie-details.php">on watch</a>
                                            </h5>
                                            <ul class="movie-rating-percent">
                                                <li>
                                                    <div class="thumb">
                                                        <img src="assets/images/movie/tomato.png" alt="movie">
                                                    </div>
                                                    <span class="content">88%</span>
                                                </li>
                                                <li>
                                                    <div class="thumb">
                                                        <img src="assets/images/movie/cake.png" alt="movie">
                                                    </div>
                                                    <span class="content">88%</span>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-lg-4">
                                    <div class="movie-grid">
                                        <div class="movie-thumb c-thumb">
                                            <a href="movie-details.php">
                                                <img src="assets/images/movie/movie5.jpg" alt="movie">
                                            </a>
                                        </div>
                                        <div class="movie-content bg-one">
                                            <h5 class="title m-0">
                                                <a href="movie-details.php">fury</a>
                                            </h5>
                                            <ul class="movie-rating-percent">
                                                <li>
                                                    <div class="thumb">
                                                        <img src="assets/images/movie/tomato.png" alt="movie">
                                                    </div>
                                                    <span class="content">88%</span>
                                                </li>
                                                <li>
                                                    <div class="thumb">
                                                        <img src="assets/images/movie/cake.png" alt="movie">
                                                    </div>
                                                    <span class="content">88%</span>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-lg-4">
                                    <div class="movie-grid">
                                        <div class="movie-thumb c-thumb">
                                            <a href="movie-details.php">
                                                <img src="assets/images/movie/movie6.jpg" alt="movie">
                                            </a>
                                        </div>
                                        <div class="movie-content bg-one">
                                            <h5 class="title m-0">
                                                <a href="movie-details.php">trooper</a>
                                            </h5>
                                            <ul class="movie-rating-percent">
                                                <li>
                                                    <div class="thumb">
                                                        <img src="assets/images/movie/tomato.png" alt="movie">
                                                    </div>
                                                    <span class="content">88%</span>
                                                </li>
                                                <li>
                                                    <div class="thumb">
                                                        <img src="assets/images/movie/cake.png" alt="movie">
                                                    </div>
                                                    <span class="content">88%</span>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-lg-4">
                                    <div class="movie-grid">
                                        <div class="movie-thumb c-thumb">
                                            <a href="movie-details.php">
                                                <img src="assets/images/movie/movie7.jpg" alt="movie">
                                            </a>
                                        </div>
                                        <div class="movie-content bg-one">
                                            <h5 class="title m-0">
                                                <a href="movie-details.php">horror night</a>
                                            </h5>
                                            <ul class="movie-rating-percent">
                                                <li>
                                                    <div class="thumb">
                                                        <img src="assets/images/movie/tomato.png" alt="movie">
                                                    </div>
                                                    <span class="content">88%</span>
                                                </li>
                                                <li>
                                                    <div class="thumb">
                                                        <img src="assets/images/movie/cake.png" alt="movie">
                                                    </div>
                                                    <span class="content">88%</span>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-lg-4">
                                    <div class="movie-grid">
                                        <div class="movie-thumb c-thumb">
                                            <a href="movie-details.php">
                                                <img src="assets/images/movie/movie8.jpg" alt="movie">
                                            </a>
                                        </div>
                                        <div class="movie-content bg-one">
                                            <h5 class="title m-0">
                                                <a href="movie-details.php">the lost name</a>
                                            </h5>
                                            <ul class="movie-rating-percent">
                                                <li>
                                                    <div class="thumb">
                                                        <img src="assets/images/movie/tomato.png" alt="movie">
                                                    </div>
                                                    <span class="content">88%</span>
                                                </li>
                                                <li>
                                                    <div class="thumb">
                                                        <img src="assets/images/movie/cake.png" alt="movie">
                                                    </div>
                                                    <span class="content">88%</span>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-lg-4">
                                    <div class="movie-grid">
                                        <div class="movie-thumb c-thumb">
                                            <a href="movie-details.php">
                                                <img src="assets/images/movie/movie9.jpg" alt="movie">
                                            </a>
                                        </div>
                                        <div class="movie-content bg-one">
                                            <h5 class="title m-0">
                                                <a href="movie-details.php">calm stedfast</a>
                                            </h5>
                                            <ul class="movie-rating-percent">
                                                <li>
                                                    <div class="thumb">
                                                        <img src="assets/images/movie/tomato.png" alt="movie">
                                                    </div>
                                                    <span class="content">88%</span>
                                                </li>
                                                <li>
                                                    <div class="thumb">
                                                        <img src="assets/images/movie/cake.png" alt="movie">
                                                    </div>
                                                    <span class="content">88%</span>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-lg-4">
                                    <div class="movie-grid">
                                        <div class="movie-thumb c-thumb">
                                            <a href="movie-details.php">
                                                <img src="assets/images/movie/movie10.jpg" alt="movie">
                                            </a>
                                        </div>
                                        <div class="movie-content bg-one">
                                            <h5 class="title m-0">
                                                <a href="movie-details.php">criminal on party</a>
                                            </h5>
                                            <ul class="movie-rating-percent">
                                                <li>
                                                    <div class="thumb">
                                                        <img src="assets/images/movie/tomato.png" alt="movie">
                                                    </div>
                                                    <span class="content">88%</span>
                                                </li>
                                                <li>
                                                    <div class="thumb">
                                                        <img src="assets/images/movie/cake.png" alt="movie">
                                                    </div>
                                                    <span class="content">88%</span>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-lg-4">
                                    <div class="movie-grid">
                                        <div class="movie-thumb c-thumb">
                                            <a href="movie-details.php">
                                                <img src="assets/images/movie/movie11.jpg" alt="movie">
                                            </a>
                                        </div>
                                        <div class="movie-content bg-one">
                                            <h5 class="title m-0">
                                                <a href="movie-details.php">halloween party</a>
                                            </h5>
                                            <ul class="movie-rating-percent">
                                                <li>
                                                    <div class="thumb">
                                                        <img src="assets/images/movie/tomato.png" alt="movie">
                                                    </div>
                                                    <span class="content">88%</span>
                                                </li>
                                                <li>
                                                    <div class="thumb">
                                                        <img src="assets/images/movie/cake.png" alt="movie">
                                                    </div>
                                                    <span class="content">88%</span>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-lg-4">
                                    <div class="movie-grid">
                                        <div class="movie-thumb c-thumb">
                                            <a href="movie-details.php">
                                                <img src="assets/images/movie/movie12.jpg" alt="movie">
                                            </a>
                                        </div>
                                        <div class="movie-content bg-one">
                                            <h5 class="title m-0">
                                                <a href="movie-details.php">the most wanted</a>
                                            </h5>
                                            <ul class="movie-rating-percent">
                                                <li>
                                                    <div class="thumb">
                                                        <img src="assets/images/movie/tomato.png" alt="movie">
                                                    </div>
                                                    <span class="content">88%</span>
                                                </li>
                                                <li>
                                                    <div class="thumb">
                                                        <img src="assets/images/movie/cake.png" alt="movie">
                                                    </div>
                                                    <span class="content">88%</span>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-item active">
                            <?php

                            ob_start();
                            ?>
                            <div class="movie-area mb-10">
                                <div class="movie-list">
                                    <div class="movie-thumb c-thumb">
                                        <a href="movie-details.php" class="w-100 bg_img h-100" data-background="./assets/images/movie/movie<?php echo htmlspecialchars($movie_id);?>.jpg">
                                            <img class="d-sm-none" src="assets/images/movie/movie<?php echo htmlspecialchars($movie_id);?>.jpg" alt="movie">
                                        </a>
                                    </div>
                                    <div class="movie-content bg-one">
                                        <h5 class="title">
                                            <a href="movie-details.php"><?php echo htmlspecialchars($movie_name); ?></a>
                                        </h5>
                                        <p class="duration">2hrs 50 min</p>
                                        <div class="movie-tags">
                                            <?php foreach ($genres as $genre): ?>
                                                <a href="#"><?php echo htmlspecialchars($genre) ?></a>
                                            <?php endforeach; ?>


                                        </div>
                                        
                                        <div class="release">
                                            <span>Booking Date : </span> <a href="#"> <?php echo htmlspecialchars($today_date); ?></a><br>
                                            <span>Show Date : &nbsp;</span> <?php echo htmlspecialchars($formatted_date); ?></a>
                                        </div>
                                        
                                        <ul class="movie-rating-percent">

                                            <span>Name : &nbsp;</span> <?php echo htmlspecialchars($billname); ?></a>&nbsp;&nbsp;&nbsp;
                                            <span class="content">Phone No. : <?php echo htmlspecialchars($phone); ?></span>
                                        </ul>

                                        <ul class="movie-rating-percent">
                                            <span class="content">Amount Paid : Rs. <?php echo htmlspecialchars($totalPrice); ?></span>
                                        </ul>
                                        <ul class="movie-rating-percent">
                                            <span class="content">Ticket No. : <?php echo htmlspecialchars($ticketid); ?></span>
                                        </ul>
                                        <ul class="movie-rating-percent">
                                            <span class="content">Seat No. : <?php echo htmlspecialchars(strtoupper($selectedSeats)); ?></span>
                                        </ul>
                                        <ul class="movie-rating-percent">

                                            <span>Language : &nbsp;</span> <?php echo htmlspecialchars($language); ?></a> &nbsp;&nbsp;
                                            <span>Experience : &nbsp;</span> <?php echo htmlspecialchars($experience); ?></a>
                                        </ul>

                                        <ul class="movie-rating-percent">

                                            <span class="content">City : <?php echo htmlspecialchars($selected_city); ?></span>&nbsp;&nbsp;&nbsp;
                                            <span class="content">Venue: <?php echo htmlspecialchars($cinema); ?></span>

                                            &nbsp;&nbsp;
                                            &nbsp; <span class="content">Timming: <?php echo htmlspecialchars($selected_time); ?></span>

                                        </ul>

                                        <div class="release">
                                            <span>Snacks Included: </span><br>

                                            <ul class="movie-rating-percent">
                                                <span class="content"><?php echo html_entity_decode($itemSummary); ?></span>
                                            </ul>
                                        </div>
                                        <div class="book-area">
                                            <div class="book-ticket">
                                                <div class="react-item">
                                                    <a href="#">
                                                        <div class="thumb">
                                                            <img src="assets/images/icons/heart.png" alt="icons">
                                                        </div>
                                                    </a>
                                                </div>
                                                <div class="react-item mr-auto">
                                                    <a href="movie-list.php#0">
                                                        <div class="thumb">
                                                            <img src="assets/images/icons/book.png" alt="icons">
                                                        </div>
                                                        <span>booked ticket</span>
                                                    </a>
                                                </div>
                                                <div class="react-item">
                                                    <a href="#" class="popup-video">
                                                        <div class="thumb">
                                                            <img src="assets/images/icons/play-button.png" alt="icons">
                                                        </div>
                                                        <span>watch trailer</span>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <?php
                            $movie_html =  ob_get_clean();
                            echo $movie_html;
                            // Insert the HTML into the database
                            $sql = "INSERT INTO ticket_history (user_id, movie_html) VALUES ('$user_id', '$movie_html')";
                            
                            mysqli_query($con, $sql);
                            $sql1 ="INSERT INTO reserved_seats (cinema, booking_date , seats ,booking_time,movie,experience,language) VALUES ('$cinema', '$selected_date','$selectedSeats','$selected_time' ,'$movie_name','$experience','$language')";
                            mysqli_query($con, $sql1);
                            ?>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        </div>
    </section>
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
        $mail->Username = "sibistonb@gmail.com"; // Replace with your email
        $mail->Password = "vvomdvuhedtxeqeq"; // Replace with your app-specific password
        $mail->SetFrom("sibistonb@gmail.com"); // Replace with your email
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