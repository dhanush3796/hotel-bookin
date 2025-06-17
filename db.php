<?php
// Database configuration
$host = "localhost";
$username = "root";
$password = "";
$database = "hotel_booking";
$socket = "/Applications/XAMPP/xamppfiles/var/mysql/mysql.sock";

// Connect to MySQL server first
$conn = mysqli_connect($host, $username, $password, null, 3306, $socket);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Create database if it doesn't exist
$sql = "CREATE DATABASE IF NOT EXISTS $database";
mysqli_query($conn, $sql);

// Close and reconnect to the specific database
mysqli_close($conn);
$conn = mysqli_connect($host, $username, $password, $database, 3306, $socket);

if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

// Create bookings table
$bookings_table = "CREATE TABLE IF NOT EXISTS bookings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    check_in_date DATE NOT NULL,
    check_out_date DATE NOT NULL,
    adults INT NOT NULL,
    children INT DEFAULT 0,
    rooms INT NOT NULL,
    room_type VARCHAR(50) NOT NULL,
    special_requests TEXT,
    services JSON,
    total_amount DECIMAL(10,2),
    booking_status ENUM('pending', 'confirmed', 'cancelled') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)";

mysqli_query($conn, $bookings_table);

// Create rooms table
$rooms_table = "CREATE TABLE IF NOT EXISTS rooms (
    id INT AUTO_INCREMENT PRIMARY KEY,
    room_number VARCHAR(10) NOT NULL UNIQUE,
    room_type VARCHAR(50) NOT NULL,
    price_per_night DECIMAL(10,2) NOT NULL,
    max_occupancy INT NOT NULL,
    amenities TEXT,
    status ENUM('available', 'occupied', 'maintenance') DEFAULT 'available',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

mysqli_query($conn, $rooms_table);

// Add some sample rooms if table is empty
$check_rooms = "SELECT COUNT(*) as count FROM rooms";
$result = mysqli_query($conn, $check_rooms);
$row = mysqli_fetch_assoc($result);

if ($row['count'] == 0) {
    $sample_rooms = "INSERT INTO rooms (room_number, room_type, price_per_night, max_occupancy, amenities) VALUES
        ('101', 'standard', 99.00, 2, 'TV, WiFi, Air Conditioning'),
        ('102', 'standard', 99.00, 2, 'TV, WiFi, Air Conditioning'),
        ('201', 'deluxe', 149.00, 3, 'TV, WiFi, Air Conditioning, Mini Bar'),
        ('202', 'deluxe', 149.00, 3, 'TV, WiFi, Air Conditioning, Mini Bar'),
        ('301', 'suite', 199.00, 4, 'TV, WiFi, Air Conditioning, Mini Bar, Balcony'),
        ('401', 'presidential', 399.00, 6, 'TV, WiFi, Air Conditioning, Mini Bar, Balcony, Jacuzzi, Living Room')";
    
    mysqli_query($conn, $sample_rooms);
}

// Connection is ready to use
?>  