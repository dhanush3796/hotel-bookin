<?php
// Include database connection
require_once 'db.php';

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Get form data
    $firstName = $_POST['firstName'];   
    $lastName = $_POST['lastName'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $checkIn = $_POST['checkIn'];
    $checkOut = $_POST['checkOut'];
    $adults = (int)$_POST['adults'];
    $children = (int)$_POST['children'];
    $rooms = (int)$_POST['rooms'];
    $roomType = $_POST['roomType'];
    $specialRequests = $_POST['specialRequests'];
    
    // Handle services
    $services = isset($_POST['services']) ? $_POST['services'] : [];
    $servicesJson = json_encode($services);
    
    // Calculate total
    $prices = array('standard' => 99, 'deluxe' => 149, 'suite' => 199, 'presidential' => 399);
    $servicePrices = array('breakfast' => 15, 'parking' => 10, 'wifi' => 5, 'spa' => 25);
    
    $checkInDate = new DateTime($checkIn);
    $checkOutDate = new DateTime($checkOut);
    $nights = $checkInDate->diff($checkOutDate)->days;
    
    $roomTotal = $prices[$roomType] * $nights * $rooms;
    $serviceTotal = 0;
    foreach ($services as $service) {
        if (isset($servicePrices[$service])) {
            $serviceTotal += $servicePrices[$service] * $nights;
        }
    }
    $totalAmount = $roomTotal + $serviceTotal;
    
    // Insert into database
    $sql = "INSERT INTO bookings (first_name, last_name, email, phone, check_in_date, check_out_date, adults, children, rooms, room_type, special_requests, services, total_amount) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ssssssiiisssd", $firstName, $lastName, $email, $phone, $checkIn, $checkOut, $adults, $children, $rooms, $roomType, $specialRequests, $servicesJson, $totalAmount);
    
    if (mysqli_stmt_execute($stmt)) {
        $bookingId = mysqli_insert_id($conn);
        $success = true;
    }
    mysqli_stmt_close($stmt);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Booking Result</title>
    <style>
        body { font-family: Arial; margin: 50px; }
        .success { background: #d4edda; padding: 20px; border-radius: 5px; }
        .error { background: #f8d7da; padding: 20px; border-radius: 5px; }
    </style>
</head>
<body>

<?php if (isset($success) && $success): ?>
    <div class="success">
        <h2>Booking Confirmed</h2>
        <p>Booking ID: <?php echo $bookingId; ?></p>
        <p>Name: <?php echo $firstName . ' ' . $lastName; ?></p>
        <p>Email: <?php echo $email; ?></p>
        <p>Check-in: <?php echo $checkIn; ?></p>
        <p>Check-out: <?php echo $checkOut; ?></p>
        <p>Room Type: <?php echo $roomType; ?></p>
        <p>Total: $<?php echo $totalAmount; ?></p>
        <p><a href="book.html">Book Another</a> | <a href="index.html">Home</a></p>
    </div>
<?php else: ?>
    <div class="error">
        <h2>Booking Failed</h2>
        <p>Please try again.</p>
        <p><a href="book.html">Go Back</a></p>
    </div>
<?php endif; ?>

</body>
</html>