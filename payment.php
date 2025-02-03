<?php
session_start();
include('connection.php');

if (!isset($_SESSION['user_id']) && !isset($_COOKIE['user_id'])) {
    header("Location: sign-in.php");
    exit();
}

$user_id = $_SESSION['user_id'] ?? $_COOKIE['user_id'];
$user_name = "Guest";
$user_email = "ayushrajput2339@gmail.com";

$sql = "SELECT name, email FROM users WHERE id = ?";
$stmt = $con->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($user_name, $user_email);
$stmt->fetch();
$stmt->close();
$_SESSION['payment_visited'] = true;
$cinema = $_SESSION['selected_cinema'] ?? 'Not selected';
$movie_name = $_SESSION['movie_name'] ?? 'Not selected';
$language = $_SESSION['selected_language'] ?? 'Not selected';
$experience = $_SESSION['selected_experience'] ?? 'Not selected';

$billName = $_POST['bill_name'] ?? '';
$phone = $_POST['phone'] ?? '';
$_SESSION['bill_name'] = $billName;
$_SESSION['phone'] = $phone;

$ticketid = sprintf('TIC%dASU2024%dRYS', rand(4444, 7000), rand(0, 1000));
$_SESSION['ticket_id'] = $ticketid;

$totalPrice = isset($_SESSION['total_price']) ? $_SESSION['total_price'] : '0';

// Razorpay API setup
require('razorpay-php/Razorpay.php');
use Razorpay\Api\Api;

$api_key = 'rzp_test_xoSRmR0gmNHQ8w';
$api_secret = 'kozv3ZlabmKvnvUEuh5QhxrE';

$api = new Api($api_key, $api_secret);

$order = $api->order->create([
    'amount' => $totalPrice * 100,
    'currency' => 'INR',
    'receipt' => 'order_receipt_12asa3'
]);

$order_id = $order->id;

$callback_url = "paymentsucess.php";
?>

<!-- Include Razorpay Checkout.js library -->
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>

<!-- Payment Button -->
<button onclick="startPayment()">Pay with Razorpay</button>

<script>
    
    function startPayment() {
        var options = {
            key: "<?php echo $api_key; ?>",
            amount: <?php echo $order->amount; ?>,
            currency: "<?php echo $order->currency; ?>",
            name: "ReserveYourSeat",
            description: "<?php echo $movie_name . ' ' . $language . '-' . $experience . ' Venue: ' . $cinema; ?>",
            image: "https://img.freepik.com/premium-photo/beautiful-popcorn-box-watercolor-carnival-clipart-illustration_962764-63544.jpg",
            order_id: "<?php echo $order_id; ?>",
            prefill: {
                name: "<?php echo $billName; ?>",
                email: "<?php echo $user_email; ?>",
                contact: "<?php echo $phone; ?>"
            },
            theme: {
                color: "#738276"
            },
            callback_url: "<?php echo $callback_url; ?>"
        };
        
        var rzp = new Razorpay(options);
        rzp.open();
    }
</script>
