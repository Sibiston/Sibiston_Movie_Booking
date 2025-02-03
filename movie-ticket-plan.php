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
        $languages = json_decode($movie['languages'], true);
        $genres = json_decode($movie['genres'], true);
        $experiences = json_decode($movie['experiences'], true);

        // Movie details
        $movieName = htmlspecialchars($movie['name']);
        $movieImage = "assets/images/movie/movie" . $movie['id'] . ".jpg";
        $trailerLink = "https://www.youtube.com/embed/KGeBMAgc46E"; // Replace with actual trailer link if available
        $ratingPercent = $movie['rating_percent'];
    } else {
        echo "Movie not found.";
    }

    // Close the statement and connection
    $stmt->close();
    $con->close();
} else {
    echo "No movie selected.";
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
    <section class="details-banner hero-area bg_img" data-background="./assets/images/banner/banner03.jpg">
        <div class="container">
            <div class="details-banner-wrapper">
                <div class="details-banner-content">
                    <h3 class="title"><?php echo htmlspecialchars($movieName); ?></h3>
                    <div class="tags">
                    <?php if (!empty($genres)): ?>
        <?php foreach ($genres as $genre): ?>
            <a href="movie-ticket-plan.php#0"><?php echo htmlspecialchars($genre); ?></a>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No languages available.</p>
    <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- ==========Banner-Section========== -->

    <!-- ==========Book-Section========== -->
     
    <section class="book-section bg-one">
    <div class="container">
        <form class="ticket-search-form two" method="POST" action="ticketplan-session.php">
            <div class="form-group">
                <div class="thumb">
                    <img src="assets/images/ticket/city.png" alt="ticket">
                </div>
                <span class="type">city</span>
                <select class="select-bar" name="select_city">
                    <option value="" disabled selected>Select</option>
                    <option value="Jalandhar">Jalandhar</option>
                    <option value="Mumbai">Mumbai</option>
                    <option value="Delhi">Delhi</option>
                    <option value="Patna">Patna</option>
                    <option value="Guwahati">Guwahati</option>
                    <option value="Phagwara">Phagwara</option>
                    <option value="Chennai">Chennai</option>
                    <option value="Benguluru">Benguluru</option>
                    <option value="Kolkata">Kolkata</option>
                    <option value="Bhopal">Bhopal</option>
                </select>
            </div>

            <div class="form-group">
                <div class="thumb">
                    <img src="assets/images/ticket/date.png" alt="ticket">
                </div>
                <span class="type">date</span>
                <select class="select-bar" name="select_date" id="dateSelect">
                    <option value="" disabled selected>Select</option>
                </select>
            </div>

            <div class="form-group">
                <div class="thumb">
                    <img src="assets/images/ticket/cinema.png" alt="ticket">
                </div>
                <span class="type">Experience</span>
                <select class="select-bar" name="select_experience" id="experience">
                    <option value="" disabled selected>Select</option>
                    <?php
                    // Assuming the movie's experiences are stored in a JSON format
                    $experiences = json_decode($movie['experiences'], true);
                    if (!empty($experiences)) {
                        foreach ($experiences as $experience) {
                            echo '<option value="' . htmlspecialchars($experience) . '">' . htmlspecialchars($experience) . '</option>';
                        }
                    } else {
                        echo '<option>No experiences available</option>';
                    }
                    ?>
                </select>
            </div>

            <div class="form-group">
                <div class="thumb">
                    <img src="assets/images/ticket/exp.png" alt="ticket">
                </div>
                <span class="type">Language</span>
                <select class="select-bar" name="select_language" id="language">
                    <option value="" disabled selected>Select</option>
                    <?php
                    // Assuming the movie's languages are stored in a JSON format
                    $languages = json_decode($movie['languages'], true);
                    if (!empty($languages)) {
                        foreach ($languages as $language) {
                            echo '<option value="' . htmlspecialchars($language) . '">' . htmlspecialchars($language) . '</option>';
                        }
                    } else {
                        echo '<option>No languages available</option>';
                    }
                    ?>
                </select>
            </div>

        
    </div>
</section>

    <!-- ==========Book-Section========== -->
    <div class="article-section" id="search-place">
        <br>
            <center>
                <a class="view-all"  id="searchhalls">Search Movie Halls</a>
            </center>
            <br>
        </div>

    <!-- ==========Movie-Section========== -->
     
     <div class="ticket-plan-section padding-bottom padding-top" id="ticketPlanSection" style="display:none;">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-lg-9 mb-5 mb-lg-0">
                            <ul class="seat-plan-wrapper bg-five">
                            <li class="active">
                                    <div class="movie-name">
                                        <div class="icons">
                                            <i class="far fa-heart"></i>
                                            <i class="fas fa-heart"></i>
                                        </div>
                                        <a href="movie-ticket-plan.php#0" class="name">INOX</a>
                                        <div class="location-icon">
                                            <a href="https://maps.app.goo.gl/resnny9m5frSHyULA" target="_blank">
                                            <i class="fas fa-map-marker-alt"></i>
                                            </a>
                                        </div>
                                    </div>
                                    <div class="movie-schedule">
                                        <div class="item" onclick="selectCinemaAndTime(this)">
                                            <input type="radio" name="cinema" value="INOX" required style="opacity: 0; position: absolute; z-index: -1; "> 
                                            <input type="radio" name="Time" value="09:40" required style="opacity: 0; position: absolute; z-index: -1; "> 09:40
                                        </div>
                                        <div class="item active" onclick="selectCinemaAndTime(this)">
                                            <input type="radio" name="cinema" value="INOX" checked style="opacity: 0; position: absolute; z-index: -1; "> 
                                            <input type="radio" name="Time" value="13:45" required style="opacity: 0; position: absolute; z-index: -1; ">13:45
                                        </div>
                                        <div class="item" onclick="selectCinemaAndTime(this)">
                                            <input type="radio" name="cinema" value="INOX" style="opacity: 0; position: absolute; z-index: -1; "> 
                                            <input type="radio" name="Time" value="15:45" required style="opacity: 0; position: absolute; z-index: -1; ">15:45
                                        </div>
                                        <div class="item" onclick="selectCinemaAndTime(this)">
                                            <input type="radio" name="cinema" value="INOX" style="opacity: 0; position: absolute; z-index: -1; ">
                                            <input type="radio" name="Time" value="19:50" required style="opacity: 0; position: absolute; z-index: -1; "> 19:50
                                        </div>
                                    </div>
                                </li>
                                <!-- Repeat for other cinemas -->
                                <li class="active">
                                    <div class="movie-name">
                                        <div class="icons">
                                            <i class="far fa-heart"></i>
                                            <i class="fas fa-heart"></i>
                                        </div>
                                        <a href="movie-ticket-plan.php#0" class="name">GT Mall</a>
                                        <div class="location-icon">
                                        <a href="https://maps.app.goo.gl/byMYo9tAT2KtvXDRA" target="_blank">
                                            <i class="fas fa-map-marker-alt"></i>
                                        </a>
                                        </div>
                                    </div>
                                    <div class="movie-schedule">
                                        <div class="item" onclick="selectCinemaAndTime(this)">
                                            <input type="radio" name="cinema" value="GT Mall" required style="opacity: 0; position: absolute; z-index: -1; "> 
                                            <input type="radio" name="Time" value="09:40" required style="opacity: 0; position: absolute; z-index: -1; "> 09:40
                                        </div>
                                        <div class="item active" onclick="selectCinemaAndTime(this)">
                                            <input type="radio" name="cinema" value="GT Mall" checked style="opacity: 0; position: absolute; z-index: -1; "> 
                                            <input type="radio" name="Time" value="13:45" required style="opacity: 0; position: absolute; z-index: -1; ">13:45
                                        </div>
                                        <div class="item" onclick="selectCinemaAndTime(this)">
                                            <input type="radio" name="cinema" value="GT Mall" style="opacity: 0; position: absolute; z-index: -1; "> 
                                            <input type="radio" name="Time" value="15:45" required style="opacity: 0; position: absolute; z-index: -1; ">15:45
                                        </div>
                                        <div class="item" onclick="selectCinemaAndTime(this)">
                                            <input type="radio" name="cinema" value="GT Mall" style="opacity: 0; position: absolute; z-index: -1; ">
                                            <input type="radio" name="Time" value="19:50" required style="opacity: 0; position: absolute; z-index: -1; "> 19:50
                                        </div>
                                    </div>
                                </li>
                                <!-- Add other cinemas similarly -->
                                <li class="active">
                                    <div class="movie-name">
                                        <div class="icons">
                                            <i class="far fa-heart"></i>
                                            <i class="fas fa-heart"></i>
                                        </div>
                                        <a href="movie-ticket-plan.php#0" class="name">Cinepolis</a>
                                        <div class="location-icon">
                                            <a href="https://maps.app.goo.gl/PcyXs8oGCJh7f1MG9" target="_blank">
                                            <i class="fas fa-map-marker-alt"></i>
                                            </a>
                                        </div>
                                    </div>
                                    <div class="movie-schedule">
                                        <div class="item" onclick="selectCinemaAndTime(this)">
                                            <input type="radio" name="cinema" value="Cinepolis" required style="opacity: 0; position: absolute; z-index: -1; "> 
                                            <input type="radio" name="Time" value="09:40" required style="opacity: 0; position: absolute; z-index: -1; "> 09:40
                                        </div>
                                        <div class="item active" onclick="selectCinemaAndTime(this)">
                                            <input type="radio" name="cinema" value="Cinepolis" checked style="opacity: 0; position: absolute; z-index: -1; "> 
                                            <input type="radio" name="Time" value="13:45" required style="opacity: 0; position: absolute; z-index: -1; ">13:45
                                        </div>
                                        <div class="item" onclick="selectCinemaAndTime(this)">
                                            <input type="radio" name="cinema" value="Cinepolis" style="opacity: 0; position: absolute; z-index: -1; "> 
                                            <input type="radio" name="Time" value="15:45" required style="opacity: 0; position: absolute; z-index: -1; ">15:45
                                        </div>
                                        <div class="item" onclick="selectCinemaAndTime(this)">
                                            <input type="radio" name="cinema" value="Cinepolis" style="opacity: 0; position: absolute; z-index: -1; ">
                                            <input type="radio" name="Time" value="19:50" required style="opacity: 0; position: absolute; z-index: -1; "> 19:50
                                        </div>
                                    </div>
                                </li>
                                <li class="active">
                                    <div class="movie-name">
                                        <div class="icons">
                                            <i class="far fa-heart"></i>
                                            <i class="fas fa-heart"></i>
                                        </div>
                                        <a href="movie-ticket-plan.php#0" class="name">Genesis Cinema</a>
                                        <div class="location-icon">
                                            <a href="https://maps.app.goo.gl/xzy1JRp4dMxN3CTC8" target="_blank">
                                            <i class="fas fa-map-marker-alt"></i>
                                            </a>
                                        </div>
                                    </div>
                                    <div class="movie-schedule">
                                        <div class="item" onclick="selectCinemaAndTime(this)">
                                            <input type="radio" name="cinema" value="Genesis Cinema" required style="opacity: 0; position: absolute; z-index: -1; "> 
                                            <input type="radio" name="Time" value="09:40" required style="opacity: 0; position: absolute; z-index: -1; "> 09:40
                                        </div>
                                        <div class="item active" onclick="selectCinemaAndTime(this)">
                                            <input type="radio" name="cinema" value="Genesis Cinema" checked style="opacity: 0; position: absolute; z-index: -1; "> 
                                            <input type="radio" name="Time" value="13:45" required style="opacity: 0; position: absolute; z-index: -1; ">13:45
                                        </div>
                                        <div class="item" onclick="selectCinemaAndTime(this)">
                                            <input type="radio" name="cinema" value="Genesis Cinema" style="opacity: 0; position: absolute; z-index: -1; "> 
                                            <input type="radio" name="Time" value="15:45" required style="opacity: 0; position: absolute; z-index: -1; ">15:45
                                        </div>
                                        <div class="item" onclick="selectCinemaAndTime(this)">
                                            <input type="radio" name="cinema" value="Genesis Cinema" style="opacity: 0; position: absolute; z-index: -1; ">
                                            <input type="radio" name="Time" value="19:50" required style="opacity: 0; position: absolute; z-index: -1; "> 19:50
                                        </div>
                                    </div>
                                </li>

                                <li class="active">
                                    <div class="movie-name">
                                        <div class="icons">
                                            <i class="far fa-heart"></i>
                                            <i class="fas fa-heart"></i>
                                        </div>
                                        <a href="movie-ticket-plan.php#0" class="name">MBD Mall</a>
                                        
                                        <div class="location-icon">
                                        <a href="https://maps.app.goo.gl/PcyXs8oGCJh7f1MG9" target="_blank">
                                            <i class="fas fa-map-marker-alt"></i>
                                        </a>
                                        </div>
                                    </div>
                                    <div class="movie-schedule">
                                        <div class="item" onclick="selectCinemaAndTime(this)">
                                            <input type="radio" name="cinema" value="MBD Mall" required style="opacity: 0; position: absolute; z-index: -1; "> 
                                            <input type="radio" name="Time" value="09:40" required style="opacity: 0; position: absolute; z-index: -1; "> 09:40
                                        </div>
                                        <div class="item active" onclick="selectCinemaAndTime(this)">
                                            <input type="radio" name="cinema" value="MBD Mall" checked style="opacity: 0; position: absolute; z-index: -1; "> 
                                            <input type="radio" name="Time" value="13:45" required style="opacity: 0; position: absolute; z-index: -1; ">13:45
                                        </div>
                                        <div class="item" onclick="selectCinemaAndTime(this)">
                                            <input type="radio" name="cinema" value="MBD Mall" style="opacity: 0; position: absolute; z-index: -1; "> 
                                            <input type="radio" name="Time" value="15:45" required style="opacity: 0; position: absolute; z-index: -1; ">15:45
                                        </div>
                                        <div class="item" onclick="selectCinemaAndTime(this)"> 
                                            <input type="radio" name="cinema" value="MBD Mall" style="opacity: 0; position: absolute; z-index: -1; ">
                                            <input type="radio" name="Time" value="19:50" required style="opacity: 0; position: absolute; z-index: -1; "> 19:50
                                        </div>
                                    </div>
                                </li>
                                <li class="active">
                                    <div class="movie-name">
                                        <div class="icons">
                                            <i class="far fa-heart"></i>
                                            <i class="fas fa-heart"></i>
                                        </div>
                                        <a href="movie-ticket-plan.php#0" class="name">PVR Cinemas</a>
                                        <div class="location-icon">
                                            <a href="https://maps.app.goo.gl/EAewZcUeMdZzXa7m8"  target="_blank">
                                            <i class="fas fa-map-marker-alt"></i>
                                            </a>
                                        </div>
                                    </div>
                                    <div class="movie-schedule">
                                        <div class="item" onclick="selectCinemaAndTime(this)">
                                            <input type="radio" name="cinema" value="PVR Cinemas" required style="opacity: 0; position: absolute; z-index: -1; "> 
                                            <input type="radio" name="Time" value="09:40" required style="opacity: 0; position: absolute; z-index: -1; "> 09:40
                                        </div>
                                        <div class="item active" onclick="selectCinemaAndTime(this)">
                                            <input type="radio" name="cinema" value="PVR Cinemas" checked style="opacity: 0; position: absolute; z-index: -1; "> 
                                            <input type="radio" name="Time" value="13:45" required style="opacity: 0; position: absolute; z-index: -1; ">13:45
                                        </div>
                                        <div class="item" onclick="selectCinemaAndTime(this)">
                                            <input type="radio" name="cinema" value="PVR Cinemas" style="opacity: 0; position: absolute; z-index: -1; "> 
                                            <input type="radio" name="Time" value="15:45" required style="opacity: 0; position: absolute; z-index: -1; ">15:45
                                        </div>
                                        <div class="item" onclick="selectCinemaAndTime(this)">
                                            <input type="radio" name="cinema" value="PVR Cinemas" style="opacity: 0; position: absolute; z-index: -1; ">
                                            <input type="radio" name="Time" value="19:50" required style="opacity: 0; position: absolute; z-index: -1; "> 19:50
                                        </div>
                                    </div>
                                </li>

                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            
          
    <!-- ==========Movie-Section========== -->

    <!-- ==========Window-Warning-Section========== -->
    <section class="window-warning inActive">
    <div class="lay"></div>
    <div class="warning-item">
        <h6 class="subtitle">Welcome! </h6>
        <h4 class="title">Select Your Seats</h4>
        <div class="thumb">
            <img src="assets/images/movie/seat-plan.png" alt="movie">
        </div>
        <!-- Replace the anchor tag with a button element to submit the form -->
        <button type="submit" class="custom-button seatPlanButton">Seat Plans<i class="fas fa-angle-right"></i></button>
    </div>
</section>
                </form>
    <!-- ==========Window-Warning-Section========== -->




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
                <a href="https://pixner.net/ReserveYourSeat/demo/index.php">
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
function selectCinemaAndTime(item) {
    // Select the first radio button for 'cinema'
    item.querySelector('input[name="cinema"]').checked = true;
    // Select the second radio button for 'Time'
    item.querySelector('input[name="Time"]').checked = true;
}
</script>

<script>
        // Function to populate the dropdown with dates from the current date for the next 30 days
        function populateDates() {
            const dateSelect = document.getElementById("dateSelect");
            dateSelect.innerHTML = ""; // Clear any existing options

            const today = new Date(); // Get the current date

            // Loop to create options for the next 30 days
            for (let i = 0; i < 30; i++) {
                const futureDate = new Date(today);
                futureDate.setDate(today.getDate() + i); // Set date to 'today' + i days

                // Format the day, month, and year
                const dayString = String(futureDate.getDate()).padStart(2, '0');
                const monthString = String(futureDate.getMonth() + 1).padStart(2, '0'); // Months are zero-based
                const yearString = futureDate.getFullYear();

                // Display format (dd/mm/yyyy) and value format (yyyy-mm-dd)
                const displayDate = `${dayString}/${monthString}/${yearString}`;
                const valueDate = `${yearString}-${monthString}-${dayString}`;

                // Create an option element
                const option = document.createElement("option");
                option.value = valueDate;
                option.textContent = displayDate;

                // Append the option to the select element
                dateSelect.appendChild(option);
            }
        }

        // Populate the dropdown with dates for the next 30 days from today
        populateDates();
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

    <script>
        // Get all select elements
        const cl = document.getElementById('searchhalls');
        const btndiv = document.getElementById('search-place');
        const ticketPlanSection = document.getElementById('ticketPlanSection');

        // Function to check if all fields are selected
        function checkSelections() {
                console.log("Hello");
            ticketPlanSection.style.display = 'block'; // Show the ticket plan section
                btndiv.style.display = 'none';
        
            
        }

        // Add event listeners to each select element to check if all are selected
        cl.addEventListener('click', checkSelections);
       
    </script>

</body>

</html>