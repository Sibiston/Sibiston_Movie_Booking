<?php
session_start();

// Check if movie_id and movie_name are provided in the URL
if (isset($_GET['movie_id']) && isset($_GET['movie_name'])) {
    $movie_id = $_GET['movie_id'];
    $movie_name = $_GET['movie_name']; // Convert movie name to lowercase if required

    // Store the movie ID in the session
    $_SESSION['movie_id'] = $movie_id;
    $_SESSION['movie_name'] = $movie_name;
    // Redirect to the movie-specific page
    header("Location: movie-details.php");
    exit();
} else {
    echo "Movie details are missing.";
}

?>
