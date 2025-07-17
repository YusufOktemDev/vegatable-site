<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "mysql1234.";
$dbname = "ShopApp";

// Bağlantı oluşturma
$conn = new mysqli($servername, $username, $password, $dbname);

// Bağlantıyı kontrol et
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Form gönderildiyse
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Güvenlik için kullanıcıdan gelen verileri temizle
    $email = htmlspecialchars($_POST["email"]);
    $password = htmlspecialchars($_POST["password"]);

    // Prepared Statement ile güvenli sorgu
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ? AND password = ?");
    $stmt->bind_param("ss", $email, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $_SESSION["user"] = $email;
        header("Location: Homepage_afterlogin.html");
        exit();
    } else {
        $error_message = "Invalid email or password.";
        header("Location: login.php?error=" . urlencode($error_message));
        exit();
    }
    $stmt->close();
}
$conn->close();
?>
