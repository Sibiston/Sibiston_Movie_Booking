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



// Search Query
$search_query = isset($_POST['search']) ? $_POST['search'] : '';

// Prepare the SQL query: Show all movies if no search query, otherwise filter by search query
if (empty($search_query)) {
    // If there's no search, apply filters
    // Get selected filter values from the GET request
    $selectedLanguages = isset($_GET['lang']) ? (is_array($_GET['lang']) ? $_GET['lang'] : [$_GET['lang']]) : [];
    $selectedExperiences = isset($_GET['mode']) ? (is_array($_GET['mode']) ? $_GET['mode'] : [$_GET['mode']]) : [];
    $selectedGenres = isset($_GET['genre']) ? (is_array($_GET['genre']) ? $_GET['genre'] : [$_GET['genre']]) : [];

    // Start building the SQL query
    $sql = "SELECT * FROM movies WHERE 1=1";

    // Add language filters
    if (!empty($selectedLanguages)) {
        $languagesQuery = [];
        foreach ($selectedLanguages as $language) {
            $languagesQuery[] = "JSON_CONTAINS(languages, '\"$language\"', '$')";
        }
        $sql .= " AND (" . implode(" OR ", $languagesQuery) . ")";
    }

    // Add experience filters
    if (!empty($selectedExperiences)) {
        $experiencesQuery = [];
        foreach ($selectedExperiences as $experience) {
            $experiencesQuery[] = "JSON_CONTAINS(experiences, '\"$experience\"', '$')";
        }
        $sql .= " AND (" . implode(" OR ", $experiencesQuery) . ")";
    }

    // Add genre filters
    if (!empty($selectedGenres)) {
        $genresQuery = [];
        foreach ($selectedGenres as $genre) {
            $genresQuery[] = "JSON_CONTAINS(genres, '\"$genre\"', '$')";
        }
        $sql .= " AND (" . implode(" OR ", $genresQuery) . ")";
    }

    // Execute the query with filters
    $stmt = $con->prepare($sql);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    // If a search query is provided, only search by name, without applying filters
    $sql = "SELECT * FROM movies WHERE name LIKE ?";
    $stmt = $con->prepare($sql);
    $search_term = "%{$search_query}%";
    $stmt->bind_param("s", $search_term);
    $stmt->execute();
    $result = $stmt->get_result();
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
    <section class="banner-section">
        <div class="banner-bg bg_img bg-fixed" data-background="./assets/images/banner/banner02.jpg"></div>
        <div class="container">
            <div class="banner-content">
                <h1 class="title bold">get <span class="color-theme">movie</span> tickets</h1>
                <p>Buy movie tickets in advance, find movie times watch trailers, read movie reviews and much more</p>
            </div>
        </div>
    </section>
    <!-- ==========Banner-Section========== -->

    <!-- ==========Ticket-Search========== -->
    <section class="search-ticket-section padding-top pt-lg-0">
        <div class="container">
            <div class="search-tab bg_img" data-background="./assets/images/ticket/ticket-bg01.jpg">
                <div class="row align-items-center mb--20">
                    <div class="col-lg-6 mb-20">
                        <div class="search-ticket-header">
                            <h6 class="category">welcome to ReserveYourTicket </h6>
                            <h3 class="title">what are you looking for</h3>
                        </div>
                    </div>
                    <div class="col-lg-6 mb-20">
                        <ul class="tab-menu ticket-tab-menu">
                            <li class="active">
                                <div class="tab-thumb">
                                    <img src="assets/images/ticket/ticket-tab01.png" alt="ticket">
                                </div>
                                <span>movie</span>
                            </li>
                            <li>
                                <div class="tab-thumb">
                                    <img src="assets/images/ticket/ticket-tab02.png" alt="ticket">
                                </div>
                                <span>events</span>
                            </li>
                            <li>
                                <div class="tab-thumb">
                                    <img src="assets/images/ticket/ticket-tab03.png" alt="ticket">
                                </div>
                                <span>sports</span>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="tab-area">
                    <div class="tab-item active">
                        <form class="ticket-search-form" method="POST">
                            <div class="form-group large">
                                <input type="text" name="search" placeholder="Search for Movies"
                                    value="<?php echo isset($_POST['search']) ? htmlspecialchars($_POST['search']) : ''; ?>">
                                <button type="submit"><i class="fas fa-search"></i></button>
                            </div>

                            <div class="form-group">
                                <div class="thumb">
                                    <img src="assets/images/ticket/city.png" alt="ticket">
                                </div>
                                <span class="type">city</span>
                                <select class="select-bar" name="city">
                                    <option value="Jalandhar" <?php echo (isset($_POST['city']) && $_POST['city'] == 'Jalandhar') ? 'selected' : ''; ?>>Jalandhar</option>
                                    <option value="Mumbai" <?php echo (isset($_POST['city']) && $_POST['city'] == 'Mumbai') ? 'selected' : ''; ?>>Mumbai</option>
                                    <option value="Delhi" <?php echo (isset($_POST['city']) && $_POST['city'] == 'Delhi') ? 'selected' : ''; ?>>Delhi</option>
                                    <option value="Patna" <?php echo (isset($_POST['city']) && $_POST['city'] == 'Patna') ? 'selected' : ''; ?>>Patna</option>
                                    <option value="Guwahti" <?php echo (isset($_POST['city']) && $_POST['city'] == 'Guwahti') ? 'selected' : ''; ?>>Guwahati</option>
                                    <option value="Punjab" <?php echo (isset($_POST['city']) && $_POST['city'] == 'Phagwara') ? 'selected' : ''; ?>>Phagwara</option>
                                    <option value="Chennai" <?php echo (isset($_POST['city']) && $_POST['city'] == 'Chennai') ? 'selected' : ''; ?>>Chennai</option>
                                    <option value="Benguluru" <?php echo (isset($_POST['city']) && $_POST['city'] == 'Benguluru') ? 'selected' : ''; ?>>Benguluru</option>
                                    <option value="Kolkata" <?php echo (isset($_POST['city']) && $_POST['city'] == 'Kolkata') ? 'selected' : ''; ?>>Kolkata</option>
                                    <option value="Bhopal" <?php echo (isset($_POST['city']) && $_POST['city'] == 'Bhopal') ? 'selected' : ''; ?>>Bhopal</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <div class="thumb">
                                    <img src="assets/images/ticket/date.png" alt="ticket">
                                </div>
                                <span class="type">date</span>
                                <select class="select-bar" name="date" id="dateSelect">

                                </select>
                            </div>
                        </form>

                    </div>
                    <div class="tab-item">
                        <center><h4>No Events Available Now</h4></center>

                    </div>
                    <div class="tab-item">
                        <center><h4>No Sports Available Now</h4></center>

                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- ==========Ticket-Search========== -->

    <!-- ==========Movie-Section========== -->
    <section class="movie-section padding-top padding-bottom">
        <div class="container">
            <div class="row flex-wrap-reverse justify-content-center">
                <div class="col-sm-10 col-md-8 col-lg-3">

                    <form action="movie-grid.php" method="GET">
                        <div class="widget-1 widget-check">
                            <div class="widget-header">
                                <h5 class="m-title">Filter By</h5>
                                <a href="movie-grid.php#0" class="clear-check">Clear All</a>
                            </div>
                            <div class="widget-1-body">
                                <h6 class="subtitle">Language</h6>
                                <div class="check-area">
                                    <div class="form-group">
                                        <input type="checkbox" name="lang[]" id="lang1" value="Tamil" <?php echo isset($_GET['lang']) && in_array('Tamil', $_GET['lang']) ? 'checked' : ''; ?>>
                                        <label for="lang1">Tamil</label>
                                    </div>
                                    <div class="form-group">
                                        <input type="checkbox" name="lang[]" id="lang2" value="Telegu" <?php echo isset($_GET['lang']) && in_array('Telegu', $_GET['lang']) ? 'checked' : ''; ?>>
                                        <label for="lang2">Telegu</label>
                                    </div>
                                    <div class="form-group">
                                        <input type="checkbox" name="lang[]" id="lang3" value="Hindi" <?php echo isset($_GET['lang']) && in_array('Hindi', $_GET['lang']) ? 'checked' : ''; ?>>
                                        <label for="lang3">Hindi</label>
                                    </div>
                                    <div class="form-group">
                                        <input type="checkbox" name="lang[]" id="lang4" value="English" <?php echo isset($_GET['lang']) && in_array('English', $_GET['lang']) ? 'checked' : ''; ?>>
                                        <label for="lang4">English</label>
                                    </div>
                                    <div class="form-group">
                                        <input type="checkbox" name="lang[]" id="lang5" value="Multiple Language" <?php echo isset($_GET['lang']) && in_array('Multiple Language', $_GET['lang']) ? 'checked' : ''; ?>>
                                        <label for="lang5">Multiple Language</label>
                                    </div>
                                    <div class="form-group">
                                        <input type="checkbox" name="lang[]" id="lang6" value="Gujrati" <?php echo isset($_GET['lang']) && in_array('Gujrati', $_GET['lang']) ? 'checked' : ''; ?>>
                                        <label for="lang6">Gujrati</label>
                                    </div>
                                    <div class="form-group">
                                        <input type="checkbox" name="lang[]" id="lang7" value="Bangla" <?php echo isset($_GET['lang']) && in_array('Bangla', $_GET['lang']) ? 'checked' : ''; ?>>
                                        <label for="lang7">Bangla</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="widget-1 widget-check">
                            <div class="widget-1-body">
                                <h6 class="subtitle">Experience</h6>
                                <div class="check-area">
                                    <div class="form-group">
                                        <input type="checkbox" name="mode[]" id="mode1" value="2d" <?php echo isset($_GET['mode']) && in_array('2d', $_GET['mode']) ? 'checked' : ''; ?>>
                                        <label for="mode1">2D</label>
                                    </div>
                                    <div class="form-group">
                                        <input type="checkbox" name="mode[]" id="mode2" value="3d" <?php echo isset($_GET['mode']) && in_array('3d', $_GET['mode']) ? 'checked' : ''; ?>>
                                        <label for="mode2">3D</label>
                                    </div>
                                </div>
                                <div class="add-check-area">
                                    <a href="movie-grid.php#0">View More <i class="plus"></i></a>
                                </div>
                            </div>
                        </div>
                        <div class="widget-1 widget-check">
                            <div class="widget-1-body">
                                <h6 class="subtitle">Genre</h6>
                                <div class="check-area">
                                    <div class="form-group">
                                        <input type="checkbox" name="genre[]" id="genre1" value="Thriller" <?php echo isset($_GET['genre']) && in_array('Thriller', $_GET['genre']) ? 'checked' : ''; ?>>
                                        <label for="genre1">Thriller</label>
                                    </div>
                                    <div class="form-group">
                                        <input type="checkbox" name="genre[]" id="genre2" value="Horror" <?php echo isset($_GET['genre']) && in_array('Horror', $_GET['genre']) ? 'checked' : ''; ?>>
                                        <label for="genre2">Horror</label>
                                    </div>
                                    <div class="form-group">
                                        <input type="checkbox" name="genre[]" id="genre3" value="Drama" <?php echo isset($_GET['genre']) && in_array('Drama', $_GET['genre']) ? 'checked' : ''; ?>>
                                        <label for="genre3">Drama</label>
                                    </div>
                                    <div class="form-group">
                                        <input type="checkbox" name="genre[]" id="genre5" value="Action" <?php echo isset($_GET['genre']) && in_array('Action', $_GET['genre']) ? 'checked' : ''; ?>>
                                        <label for="genre5">Action</label>
                                    </div>
                                    <div class="form-group">
                                        <input type="checkbox" name="genre[]" id="genre6" value="Comedy" <?php echo isset($_GET['genre']) && in_array('Comedy', $_GET['genre']) ? 'checked' : ''; ?>>
                                        <label for="genre6">Comedy</label>
                                    </div>
                                    <div class="form-group">
                                        <input type="checkbox" name="genre[]" id="genre7" value="Romantic" <?php echo isset($_GET['genre']) && in_array('Romantic', $_GET['genre']) ? 'checked' : ''; ?>>
                                        <label for="genre7">Romantic</label>
                                    </div>
                                    <div class="form-group">
                                        <input type="checkbox" name="genre[]" id="genre8" value="Animation" <?php echo isset($_GET['genre']) && in_array('Animation', $_GET['genre']) ? 'checked' : ''; ?>>
                                        <label for="genre8">Animation</label>
                                    </div>
                                    <div class="form-group">
                                        <input type="checkbox" name="genre[]" id="genre9" value="Sci-Fi" <?php echo isset($_GET['genre']) && in_array('Sci-Fi', $_GET['genre']) ? 'checked' : ''; ?>>
                                        <label for="genre9">Sci-Fi</label>
                                    </div>
                                    <div class="form-group">
                                        <input type="checkbox" name="genre[]" id="genre10" value="Adventure" <?php echo isset($_GET['genre']) && in_array('Adventure', $_GET['genre']) ? 'checked' : ''; ?>>
                                        <label for="genre10">Adventure</label>
                                    </div>
                                </div>
                                <div class="add-check-area">
                                    <a href="movie-grid.php#0">View More <i class="plus"></i></a>
                                </div>
                            </div>
                        </div>
                        <button type="submit" style="color: #8D4DE8;">Apply</button>
                    </form>


                </div>
                <div class="col-lg-9 mb-50 mb-lg-0">
                    <div class="filter-tab tab">
                        <div class="filter-area">
                            <div class="filter-main">
                                <div class="left">
                                    <div class="item">
                                        <span class="show">Show :</span>
                                        <select class="select-bar">
                                            <option value="12">12</option>
                                            <option value="15">15</option>
                                            <option value="18">18</option>
                                            <option value="21">21</option>
                                            <option value="24">24</option>
                                            <option value="27">27</option>
                                            <option value="30">30</option>
                                        </select>
                                    </div>
                                    <div class="item">
                                        <span class="show">Sort By :</span>
                                        <select class="select-bar">
                                            <option value="showing">now showing</option>
                                            <option value="exclusive">exclusive</option>
                                            <option value="trending">trending</option>
                                            <option value="most-view">most view</option>
                                        </select>
                                    </div>
                                </div>
                                <ul class="grid-button tab-">
                                    <li class="active">
                                        <i class="fas fa-th"></i>
                                    </li>

                                </ul>
                            </div>
                        </div>
                        <div class="tab-area">
                            <div class="tab-item active">
                                <div class="row mb-10 justify-content-center">

                                    <?php if ($result && $result->num_rows > 0): ?>
                                        <?php while ($movie = $result->fetch_assoc()): ?>
                                            <div class="col-sm-6 col-lg-4">
                                                <div class="movie-grid">
                                                    <div class="movie-thumb c-thumb">
                                                        <a href="set_movie_cookie.php?movie_id=<?php echo $movie['id']; ?>&movie_name=<?php echo urlencode($movie['name']); ?>">
                                                            <img src="assets/images/movie/movie<?php echo $movie['id']; ?>.jpg" alt="movie">
                                                        </a>
                                                    </div>
                                                    <div class="movie-content bg-one">
                                                        <h5 class="title m-0">
                                                            <a href="set_movie_cookie.php?movie_id=<?php echo $movie['id']; ?>&movie_name=<?php echo urlencode($movie['name']); ?>">
                                                                <?php echo htmlspecialchars($movie['name']); ?>
                                                            </a>
                                                        </h5>
                                                        <ul class="movie-rating-percent">
                                                            <li>
                                                                <div class="thumb">
                                                                    <img src="assets/images/movie/tomato.png" alt="movie">
                                                                </div>
                                                                <span class="content"><?php echo $movie['rating_percent']; ?>%</span>
                                                            </li>
                                                            <li>
                                                                <div class="thumb">
                                                                    <img src="assets/images/movie/cake.png" alt="movie">
                                                                </div>
                                                                <span class="content"><?php echo $movie['rating_percent']; ?>%</span>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endwhile; ?>
                                    <?php else: ?>
                                        <p>No movies found.</p>
                                    <?php endif; ?>
                                </div>

                                <?php
                                // Close the statement and connection
                                $stmt->close();
                                $con->close();
                                ?>

                            </div>
                        </div>

                    </div>
                    <div class="pagination-area text-center">
                        <a href="movie-grid.php#0"><i class="fas fa-angle-double-left"></i><span>Prev</span></a>
                        <a href="movie-grid.php#0">1</a>
                        <a href="movie-grid.php#0">2</a>
                        <a href="movie-grid.php#0" class="active">3</a>
                        <a href="movie-grid.php#0">4</a>
                        <a href="movie-grid.php#0">5</a>
                        <a href="movie-grid.php#0"><span>Next</span><i class="fas fa-angle-double-right"></i></a>
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
                <p>Hi $user_name!</p>
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
</body>

</html>