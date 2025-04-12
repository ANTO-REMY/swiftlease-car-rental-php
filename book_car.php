<?php
require_once 'db.php'; 

session_start(); 


if (!isset($_SESSION['user_id'])) {
    http_response_code(401); // Unauthorized
    echo "Unauthorized. Please log in.";
    exit();
}

$user_id = $_SESSION['user_id']; // Retrieve user id


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    $car_id = $_POST['car_id'];
    $rental_days = $_POST['rental_days'];
    $daily_charge = $_POST['daily_charge'];
    $total_charge = $rental_days * $daily_charge;

    try {
        
        $stmt = $conn->prepare("INSERT INTO bookings (user_id, car_id, rental_days, total_charge) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("iiii", $user_id, $car_id, $rental_days, $total_charge); 
        $stmt->execute();

      
        echo "
        <div class='confirmation-message'>
            <div class='message-icon'>
                &#9989; <!-- Check mark icon -->
            </div>
            <div class='message-text'>
                <h2>Booking Confirmed!</h2>
                <p>Your booking has been successfully confirmed.</p>
                <p><strong>Total Charge: KSh {$total_charge}</strong></p>
            </div>
        </div>

        <script>
            // Redirect back to index.html after 30 seconds
            setTimeout(function() {
                window.location.href = 'index.html'; 
            }, 2000); 
        </script>
        ";
    } catch (mysqli_sql_exception $e) {
        
        echo "Error: " . $e->getMessage();
    }
} else {
    
    echo "Invalid request method.";
}
?>
