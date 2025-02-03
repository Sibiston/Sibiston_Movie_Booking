<?php
// Start the session at the beginning of your PHP file
session_start();

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Save form data to session variables safely
    $_SESSION['selected_city'] = isset($_POST['select_city']) ? htmlspecialchars($_POST['select_city']) : null;
    $_SESSION['selected_date'] = isset($_POST['select_date']) ? htmlspecialchars($_POST['select_date']) : null;
    $_SESSION['selected_experience'] = isset($_POST['select_experience']) ? htmlspecialchars($_POST['select_experience']) : null;
    $_SESSION['selected_language'] = isset($_POST['select_language']) ? htmlspecialchars($_POST['select_language']) : null;
    $_SESSION['selected_cinema'] = isset($_POST['cinema']) ? htmlspecialchars($_POST['cinema']) : null;
    $_SESSION['selected_time'] = isset($_POST['Time']) ? htmlspecialchars($_POST['Time']) : null;

    // Redirect to another page if needed (e.g., confirmation or ticket booking page)
    header("Location: movie-seat-plan.php");
    exit();
}
?>