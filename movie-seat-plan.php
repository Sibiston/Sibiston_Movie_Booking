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







// echo "<h2>Selected Details</h2>";
// echo "<p><strong>City:</strong> " . (isset($_SESSION['selected_city']) ? htmlspecialchars($_SESSION['selected_city']) : 'Not selected') . "</p>";
// echo "<p><strong>Date:</strong> " . (isset($_SESSION['selected_date']) ? htmlspecialchars($_SESSION['selected_date']) : 'Not selected') . "</p>";
// echo "<p><strong>Experience:</strong> " . (isset($_SESSION['selected_experience']) ? htmlspecialchars($_SESSION['selected_experience']) : 'Not selected') . "</p>";
// echo "<p><strong>Language:</strong> " . (isset($_SESSION['selected_language']) ? htmlspecialchars($_SESSION['selected_language']) : 'Not selected') . "</p>";
// echo "<p><strong>Cinema:</strong> " . (isset($_SESSION['selected_cinema']) ? htmlspecialchars($_SESSION['selected_cinema']) : 'Not selected') . "</p>";
// echo "<p><strong>Time:</strong> " . (isset($_SESSION['selected_time']) ? htmlspecialchars($_SESSION['selected_time']) : 'Not selected') . "</p>";



$cinema = isset($_SESSION['selected_cinema']) ? $_SESSION['selected_cinema'] : 'Not selected';
$movie_name = isset($_SESSION['movie_name']) ? $_SESSION['movie_name'] : 'Not selected';
$language = isset($_SESSION['selected_language']) ? $_SESSION['selected_language'] : 'Not selected';
$experience = isset($_SESSION['selected_experience']) ? $_SESSION['selected_experience'] : 'Not selected';
$selected_date = isset($_SESSION['selected_date']) ? $_SESSION['selected_date'] : 'Not selected';
$selected_time = isset($_SESSION['selected_time']) ? $_SESSION['selected_time'] : 'Not selected';

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

$query = "SELECT seats FROM reserved_seats WHERE cinema = ? AND booking_date = ? AND booking_time = ? AND experience = ? AND language =? AND movie=?";
$stmt1 = $con->prepare($query);
$stmt1->bind_param('ssssss', $cinema, $selected_date, $selected_time , $experience ,$language,$movie_name);
$stmt1->execute();
$result = $stmt1->get_result();

// Get all reserved seats as an array
$reserved_seats = [];
while ($row = $result->fetch_assoc()) {
    $reserved_seats = array_merge($reserved_seats, explode(', ', $row['seats']));
}

$stmt1->close();
$con->close();




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
                        <a href="movie-seat-plan.php#0"><?php echo $cinema; ?></a>
                        <a href="movie-seat-plan.php#0"><?php echo $language . ' - ' . $experience; ?></a>
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
                    <a href="movie-ticket-plan.php" class="custom-button back-button">
                        <i class="flaticon-double-right-arrows-angles"></i>back
                    </a>
                </div>
                <div class="item date-item">
                    <span class="date"><?php echo $formatted_date; ?></span>
                    <div class="article-section">
                        <a class="view-all" href="movie-seat-plan.php#"><?php echo $selected_time !== 'Not selected' ? $selected_time : ''; ?></a>
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
    <div class="seat-plan-section padding-bottom padding-top">
        <div class="container">
            <div class="screen-area">
                <h4 class="screen">screen</h4>
                <div class="screen-thumb">
                    <img src="assets/images/movie/screen-thumb.png" alt="movie">
                </div>


                <h5 class="subtitle">silver plus</h5>
                <div class="screen-wrapper">
                    <ul class="seat-area">
                        <li class="seat-line">
                            <span>G</span>
                            <ul class="seat--area">
                                <li class="front-seat">
                                    <ul>
                                        <?php for ($i = 1; $i <= 4; $i++):
                                            $seat_id = "g$i";
                                            $is_reserved = in_array($seat_id, $reserved_seats);
                                        ?>
                                            <li class="single-seat <?= $is_reserved ? '' : 'seat-free'; ?>"
                                                <?= $is_reserved ? '' : "onclick=\"selectSeat('$seat_id', this)\""; ?>
                                                data-price="100" data-seat-type="single">
                                                <img src="assets/images/movie/seat01<?= $is_reserved ? '' : '-free'; ?>.png" alt="seat">
                                                <?php if (!$is_reserved): ?>
                                                    <span class="sit-num"><?= $seat_id; ?></span>
                                                <?php endif; ?>
                                            </li>
                                        <?php endfor; ?>
                                    </ul>
                                </li>
                                <li class="front-seat">
                                    <ul>
                                        <?php for ($i = 5; $i <= 10; $i++):
                                            $seat_id = "g$i";
                                            $is_reserved = in_array($seat_id, $reserved_seats);
                                        ?>
                                            <li class="single-seat <?= $is_reserved ? '' : 'seat-free'; ?>"
                                                <?= $is_reserved ? '' : "onclick=\"selectSeat('$seat_id', this)\""; ?>
                                                data-price="100" data-seat-type="single">
                                                <img src="assets/images/movie/seat01<?= $is_reserved ? '' : '-free'; ?>.png" alt="seat">
                                                <?php if (!$is_reserved): ?>
                                                    <span class="sit-num"><?= $seat_id; ?></span>
                                                <?php endif; ?>
                                            </li>
                                        <?php endfor; ?>
                                    </ul>
                                </li>
                                <li class="front-seat">
                                    <ul>
                                        <?php for ($i = 11; $i <= 14; $i++):
                                            $seat_id = "g$i";
                                            $is_reserved = in_array($seat_id, $reserved_seats);
                                        ?>
                                            <li class="single-seat <?= $is_reserved ? '' : 'seat-free'; ?>"
                                                <?= $is_reserved ? '' : "onclick=\"selectSeat('$seat_id', this)\""; ?>
                                                data-price="100" data-seat-type="single">
                                                <img src="assets/images/movie/seat01<?= $is_reserved ? '' : '-free'; ?>.png" alt="seat">
                                                <?php if (!$is_reserved): ?>
                                                    <span class="sit-num"><?= $seat_id; ?></span>
                                                <?php endif; ?>
                                            </li>
                                        <?php endfor; ?>
                                    </ul>
                                </li>
                            </ul>
                            <span>G</span>
                        </li>
                        <li class="seat-line">
                            <span>F</span>
                            <ul class="seat--area">
                                <li class="front-seat">
                                    <ul>
                                        <?php for ($i = 1; $i <= 4; $i++):
                                            $seat_id = "f$i";
                                            $is_reserved = in_array($seat_id, $reserved_seats);
                                        ?>
                                            <li class="single-seat <?= $is_reserved ? '' : 'seat-free'; ?>"
                                                <?= $is_reserved ? '' : "onclick=\"selectSeat('$seat_id', this)\""; ?>
                                                data-price="100" data-seat-type="single">
                                                <img src="assets/images/movie/seat01<?= $is_reserved ? '' : '-free'; ?>.png" alt="seat">
                                                <?php if (!$is_reserved): ?>
                                                    <span class="sit-num"><?= $seat_id; ?></span>
                                                <?php endif; ?>
                                            </li>
                                        <?php endfor; ?>
                                    </ul>
                                </li>
                                <li class="front-seat">
                                    <ul>
                                        <?php for ($i = 5; $i <= 10; $i++):
                                            $seat_id = "f$i";
                                            $is_reserved = in_array($seat_id, $reserved_seats);
                                        ?>
                                            <li class="single-seat <?= $is_reserved ? '' : 'seat-free'; ?>"
                                                <?= $is_reserved ? '' : "onclick=\"selectSeat('$seat_id', this)\""; ?>
                                                data-price="100" data-seat-type="single">
                                                <img src="assets/images/movie/seat01<?= $is_reserved ? '' : '-free'; ?>.png" alt="seat">
                                                <?php if (!$is_reserved): ?>
                                                    <span class="sit-num"><?= $seat_id; ?></span>
                                                <?php endif; ?>
                                            </li>
                                        <?php endfor; ?>
                                    </ul>
                                </li>
                                <li class="front-seat">
                                    <ul>
                                        <?php for ($i = 11; $i <= 14; $i++):
                                            $seat_id = "f$i";
                                            $is_reserved = in_array($seat_id, $reserved_seats);
                                        ?>
                                            <li class="single-seat <?= $is_reserved ? '' : 'seat-free'; ?>"
                                                <?= $is_reserved ? '' : "onclick=\"selectSeat('$seat_id', this)\""; ?>
                                                data-price="100" data-seat-type="single">
                                                <img src="assets/images/movie/seat01<?= $is_reserved ? '' : '-free'; ?>.png" alt="seat">
                                                <?php if (!$is_reserved): ?>
                                                    <span class="sit-num"><?= $seat_id; ?></span>
                                                <?php endif; ?>
                                            </li>
                                        <?php endfor; ?>
                                    </ul>
                                </li>
                            </ul>
                            <span>F</span>
                        </li>
                    </ul>
                </div>
                <h5 class="subtitle">silver plus</h5>
                <div class="screen-wrapper">
                    <ul class="seat-area couple">
                        <li class="seat-line">
                            <span>e</span>
                            <ul class="seat--area">
                                <li class="front-seat">
                                    <ul>
                                        <?php
                                        // Double-seater seat IDs
                                        $double_seats = ['e1 e2', 'e3 e4'];
                                        foreach ($double_seats as $seat_id) :
                                            $is_reserved = in_array($seat_id, $reserved_seats); // Check if the seat is reserved
                                        ?>
                                            <li class="single-seat <?= $is_reserved ? '' : 'seat-free-two'; ?>"
                                                <?= $is_reserved ? '' : "onclick=\"selectSeat('$seat_id', this)\""; ?>
                                                data-price="200" data-seat-type="double">
                                                <img src="assets/images/movie/seat02<?= $is_reserved ? '' : '-free'; ?>.png" alt="seat">
                                                <?php if (!$is_reserved): ?>
                                                    <span class="sit-num"><?= $seat_id; ?></span>
                                                <?php endif; ?>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                </li>
                                <li class="front-seat">
                                    <ul>
                                        <?php
                                        // Double-seater seat IDs
                                        $double_seats = ['e5 e6', 'e7 e8', 'e9 e10'];
                                        foreach ($double_seats as $seat_id) :
                                            $is_reserved = in_array($seat_id, $reserved_seats); // Check if the seat is reserved
                                        ?>
                                            <li class="single-seat <?= $is_reserved ? '' : 'seat-free-two'; ?>"
                                                <?= $is_reserved ? '' : "onclick=\"selectSeat('$seat_id', this)\""; ?>
                                                data-price="200" data-seat-type="double">
                                                <img src="assets/images/movie/seat02<?= $is_reserved ? '' : '-free'; ?>.png" alt="seat">
                                                <?php if (!$is_reserved): ?>
                                                    <span class="sit-num"><?= $seat_id; ?></span>
                                                <?php endif; ?>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                </li>
                                <li class="front-seat">
                                    <ul>
                                        <?php
                                        // Double-seater seat IDs
                                        $double_seats = ['e11', 'e12'];
                                        foreach ($double_seats as $seat_id) :
                                            $is_reserved = in_array($seat_id, $reserved_seats); // Check if the seat is reserved
                                        ?>
                                            <li class="single-seat <?= $is_reserved ? '' : 'seat-free-two'; ?>"
                                                <?= $is_reserved ? '' : "onclick=\"selectSeat('$seat_id', this)\""; ?>
                                                data-price="200" data-seat-type="double">
                                                <img src="assets/images/movie/seat02<?= $is_reserved ? '' : '-free'; ?>.png" alt="seat">
                                                <?php if (!$is_reserved): ?>
                                                    <span class="sit-num"><?= $seat_id; ?></span>
                                                <?php endif; ?>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                </li>
                            </ul>
                            <span>e</span>
                        </li>
                        <li class="seat-line">
                            <span>d</span>
                            <ul class="seat--area">
                                <li class="front-seat">
                                    <ul>
                                        <?php
                                        // Double-seater seat IDs for row d
                                        $double_seats = ['d1 d2', 'd3 d4'];
                                        foreach ($double_seats as $seat_id) :
                                            $is_reserved = in_array($seat_id, $reserved_seats); // Check if the seat is reserved
                                        ?>
                                            <li class="single-seat <?= $is_reserved ? '' : 'seat-free-two'; ?>"
                                                <?= $is_reserved ? '' : "onclick=\"selectSeat('$seat_id', this)\""; ?>
                                                data-price="200" data-seat-type="double">
                                                <img src="assets/images/movie/seat02<?= $is_reserved ? '' : '-free'; ?>.png" alt="seat">
                                                <?php if (!$is_reserved): ?>
                                                    <span class="sit-num"><?= $seat_id; ?></span>
                                                <?php endif; ?>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                </li>
                                <li class="front-seat">
                                    <ul>
                                        <?php
                                        // Double-seater seat IDs for row d
                                        $double_seats = ['d5 d6', 'd7 d8', 'd9 d10'];
                                        foreach ($double_seats as $seat_id) :
                                            $is_reserved = in_array($seat_id, $reserved_seats); // Check if the seat is reserved
                                        ?>
                                            <li class="single-seat <?= $is_reserved ? '' : 'seat-free-two'; ?>"
                                                <?= $is_reserved ? '' : "onclick=\"selectSeat('$seat_id', this)\""; ?>
                                                data-price="200" data-seat-type="double">
                                                <img src="assets/images/movie/seat02<?= $is_reserved ? '' : '-free'; ?>.png" alt="seat">
                                                <?php if (!$is_reserved): ?>
                                                    <span class="sit-num"><?= $seat_id; ?></span>
                                                <?php endif; ?>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                </li>
                                <li class="front-seat">
                                    <ul>
                                        <?php
                                        // Double-seater seat IDs for row d
                                        $double_seats = ['d11', 'd12'];
                                        foreach ($double_seats as $seat_id) :
                                            $is_reserved = in_array($seat_id, $reserved_seats); // Check if the seat is reserved
                                        ?>
                                            <li class="single-seat <?= $is_reserved ? '' : 'seat-free-two'; ?>"
                                                <?= $is_reserved ? '' : "onclick=\"selectSeat('$seat_id', this)\""; ?>
                                                data-price="200" data-seat-type="double">
                                                <img src="assets/images/movie/seat02<?= $is_reserved ? '' : '-free'; ?>.png" alt="seat">
                                                <?php if (!$is_reserved): ?>
                                                    <span class="sit-num"><?= $seat_id; ?></span>
                                                <?php endif; ?>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                </li>
                            </ul>
                            <span>d</span>
                        </li>


                        <li class="seat-line">
                            <span>c</span>
                            <ul class="seat--area">
                                <li class="front-seat">
                                    <ul>
                                        <?php
                                        // Double-seater seat IDs for row c
                                        $double_seats = ['c1 c2', 'c3 c4'];
                                        foreach ($double_seats as $seat_id) :
                                            $is_reserved = in_array($seat_id, $reserved_seats); // Check if the seat is reserved
                                        ?>
                                            <li class="single-seat <?= $is_reserved ? '' : 'seat-free-two'; ?>"
                                                <?= $is_reserved ? '' : "onclick=\"selectSeat('$seat_id', this)\""; ?>
                                                data-price="200" data-seat-type="double">
                                                <img src="assets/images/movie/seat02<?= $is_reserved ? '' : '-free'; ?>.png" alt="seat">
                                                <?php if (!$is_reserved): ?>
                                                    <span class="sit-num"><?= $seat_id; ?></span>
                                                <?php endif; ?>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                </li>
                                <li class="front-seat">
                                    <ul>
                                        <?php
                                        // Double-seater seat IDs for row c
                                        $double_seats = ['c5 c6', 'c7 c8', 'c9 c10'];
                                        foreach ($double_seats as $seat_id) :
                                            $is_reserved = in_array($seat_id, $reserved_seats); // Check if the seat is reserved
                                        ?>
                                            <li class="single-seat  <?= $is_reserved ? '' : 'seat-free-two'; ?>"
                                                <?= $is_reserved ? '' : "onclick=\"selectSeat('$seat_id', this)\""; ?>
                                                data-price="200" data-seat-type="double">
                                                <img src="assets/images/movie/seat02<?= $is_reserved ? '' : '-free'; ?>.png" alt="seat">
                                                <?php if (!$is_reserved): ?>
                                                    <span class="sit-num"><?= $seat_id; ?></span>
                                                <?php endif; ?>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                </li>
                                <li class="front-seat">
                                    <ul>
                                        <?php
                                        // Double-seater seat IDs for row c
                                        $double_seats = ['c11', 'c12'];
                                        foreach ($double_seats as $seat_id) :
                                            $is_reserved = in_array($seat_id, $reserved_seats); // Check if the seat is reserved
                                        ?>
                                            <li class="single-seat  <?= $is_reserved ? '' : 'seat-free-two'; ?>"
                                                <?= $is_reserved ? '' : "onclick=\"selectSeat('$seat_id', this)\""; ?>
                                                data-price="200" data-seat-type="double">
                                                <img src="assets/images/movie/seat02<?= $is_reserved ? '' : '-free'; ?>.png" alt="seat">
                                                <?php if (!$is_reserved): ?>
                                                    <span class="sit-num"><?= $seat_id; ?></span>
                                                <?php endif; ?>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                </li>
                            </ul>
                            <span>c</span>
                        </li>

                        <li class="seat-line">
                            <span>b</span>
                            <ul class="seat--area">
                                <li class="front-seat">
                                    <ul>
                                        <?php
                                        // Double-seater seat IDs for row b
                                        $double_seats = ['b1 b2', 'b3 b4'];
                                        foreach ($double_seats as $seat_id) :
                                            $is_reserved = in_array($seat_id, $reserved_seats); // Check if the seat is reserved
                                        ?>
                                            <li class="single-seat  <?= $is_reserved ? '' : 'seat-free-two'; ?>"
                                                <?= $is_reserved ? '' : "onclick=\"selectSeat('$seat_id', this)\""; ?>
                                                data-price="200" data-seat-type="double">
                                                <img src="assets/images/movie/seat02<?= $is_reserved ? '' : '-free'; ?>.png" alt="seat">
                                                <?php if (!$is_reserved): ?>
                                                    <span class="sit-num"><?= $seat_id; ?></span>
                                                <?php endif; ?>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                </li>
                                <li class="front-seat">
                                    <ul>
                                        <?php
                                        // Double-seater seat IDs for row b
                                        $double_seats = ['b5 b6', 'b7 b8', 'b9 b10'];
                                        foreach ($double_seats as $seat_id) :
                                            $is_reserved = in_array($seat_id, $reserved_seats); // Check if the seat is reserved
                                        ?>
                                            <li class="single-seat <?= $is_reserved ? '' : 'seat-free-two'; ?>"
                                                <?= $is_reserved ? '' : "onclick=\"selectSeat('$seat_id', this)\""; ?>
                                                data-price="200" data-seat-type="double">
                                                <img src="assets/images/movie/seat02<?= $is_reserved ? '' : '-free'; ?>.png" alt="seat">
                                                <?php if (!$is_reserved): ?>
                                                    <span class="sit-num"><?= $seat_id; ?></span>
                                                <?php endif; ?>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                </li>
                                <li class="front-seat">
                                    <ul>
                                        <?php
                                        // Double-seater seat IDs for row b
                                        $double_seats = ['b11', 'b12'];
                                        foreach ($double_seats as $seat_id) :
                                            $is_reserved = in_array($seat_id, $reserved_seats); // Check if the seat is reserved
                                        ?>
                                            <li class="single-seat  <?= $is_reserved ? '' : 'seat-free-two'; ?>"
                                                <?= $is_reserved ? '' : "onclick=\"selectSeat('$seat_id', this)\""; ?>
                                                data-price="200" data-seat-type="double">
                                                <img src="assets/images/movie/seat02<?= $is_reserved ? '' : '-free'; ?>.png" alt="seat">
                                                <?php if (!$is_reserved): ?>
                                                    <span class="sit-num"><?= $seat_id; ?></span>
                                                <?php endif; ?>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                </li>
                            </ul>
                            <span>b</span>
                        </li>

                        <li class="seat-line">
                            <span>a</span>
                            <ul class="seat--area">
                                <li class="front-seat">
                                    <ul>
                                        <?php
                                        // Double-seater seat IDs for row a
                                        $double_seats = ['a1 a2', 'a3 a4'];
                                        foreach ($double_seats as $seat_id) :
                                            $is_reserved = in_array($seat_id, $reserved_seats); // Check if the seat is reserved
                                        ?>
                                            <li class="single-seat  <?= $is_reserved ? '' : 'seat-free-two'; ?>"
                                                <?= $is_reserved ? '' : "onclick=\"selectSeat('$seat_id', this)\""; ?>
                                                data-price="200" data-seat-type="double">
                                                <img src="assets/images/movie/seat02<?= $is_reserved ? '' : '-free'; ?>.png" alt="seat">
                                                <?php if (!$is_reserved): ?>
                                                    <span class="sit-num"><?= $seat_id; ?></span>
                                                <?php endif; ?>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                </li>
                                <li class="front-seat">
                                    <ul>
                                        <?php
                                        // Double-seater seat IDs for row a
                                        $double_seats = ['a5 a6', 'a7 a8', 'a9 a10'];
                                        foreach ($double_seats as $seat_id) :
                                            $is_reserved = in_array($seat_id, $reserved_seats); // Check if the seat is reserved
                                        ?>
                                            <li class="single-seat <?= $is_reserved ? '' : 'seat-free-two'; ?>"
                                                <?= $is_reserved ? '' : "onclick=\"selectSeat('$seat_id', this)\""; ?>
                                                data-price="200" data-seat-type="double">
                                                <img src="assets/images/movie/seat02<?= $is_reserved ? '' : '-free'; ?>.png" alt="seat">
                                                <?php if (!$is_reserved): ?>
                                                    <span class="sit-num"><?= $seat_id; ?></span>
                                                <?php endif; ?>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                </li>
                                <li class="front-seat">
                                    <ul>
                                        <?php
                                        // Double-seater seat IDs for row a
                                        $double_seats = ['a11', 'a12'];
                                        foreach ($double_seats as $seat_id) :
                                            $is_reserved = in_array($seat_id, $reserved_seats); // Check if the seat is reserved
                                        ?>
                                            <li class="single-seat <?= $is_reserved ? '' : 'seat-free-two'; ?>"
                                                <?= $is_reserved ? '' : "onclick=\"selectSeat('$seat_id', this)\""; ?>
                                                data-price="200" data-seat-type="double">
                                                <img src="assets/images/movie/seat02<?= $is_reserved ? '' : '-free'; ?>.png" alt="seat">
                                                <?php if (!$is_reserved): ?>
                                                    <span class="sit-num"><?= $seat_id; ?></span>
                                                <?php endif; ?>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                </li>
                            </ul>
                            <span>a</span>
                        </li>

                    </ul>
                </div>
            </div>
            <div class="proceed-book bg_img" data-background="./assets/images/movie/movie-bg-proceed.jpg">
                <div class="proceed-to-book">
                    <div class="book-item">
                        <span>You have Choosed Seat</span>
                        <h3 class="title">None</h3>
                    </div>
                    <div class="book-item">
                        <span>total price</span>
                        <h3 class="title">0</h3>
                    </div>
                    <div class="book-item">
                        <a href="popcorn.php" class="custom-button">proceed</a>
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




    <script defer>
        // Array to hold selected seats with their prices
        let selectedSeats = [];

        // Function to handle seat selection
        function selectSeat(seatNumber, element) {
            const seatIndex = selectedSeats.findIndex(seat => seat.number === seatNumber);
            const seatPrice = parseInt(element.getAttribute('data-price'));
            const seatType = element.getAttribute('data-seat-type'); // Get seat type (single or double)

            // Toggle seat selection
            if (seatIndex === -1) {
                selectedSeats.push({
                    number: seatNumber,
                    price: seatPrice
                });
                if (seatType === 'single') {
                    element.querySelector('img').src = 'assets/images/movie/seat01-booked.png';
                } else if (seatType === 'double') {
                    element.querySelector('img').src = 'assets/images/movie/seat02-booked.png';
                }
            } else {
                selectedSeats.splice(seatIndex, 1);
                if (seatType === 'single') {
                    element.querySelector('img').src = 'assets/images/movie/seat01-free.png';
                } else if (seatType === 'double') {
                    element.querySelector('img').src = 'assets/images/movie/seat02-free.png';
                }
            }

            // Update selected seats display
            const titleElement = document.querySelector('.proceed-to-book .title');
            titleElement.innerText = selectedSeats.length > 0 ?
                selectedSeats.map(seat => seat.number).join(', ') :
                'None';

            // Calculate and update total price
            const totalPrice = selectedSeats.reduce((sum, seat) => sum + seat.price, 0);
            const totalPriceElement = document.querySelector('.proceed-to-book .book-item:nth-child(2) .title');
            totalPriceElement.innerText = `Rs: ${totalPrice}`;

            // Send seat details to PHP via AJAX
            const selectedSeatNumbers = selectedSeats.map(seat => seat.number).join(', ');
            sendSeatDataToSession(selectedSeatNumbers, totalPrice);
        }

        // Function to send seat data to the server
        function sendSeatDataToSession(seatNumbers, totalPrice) {
            fetch('save_seat_data.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        seatNumbers,
                        totalPrice
                    })
                })
                .then(response => response.json())
                .then(data => console.log(data.message))
                .catch(error => console.error('Error:', error));
        }
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