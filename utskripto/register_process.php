<?php
include 'config.php';

function encryptPassword($password, $key) {
    $len = strlen($password);
    $encrypted = '';

    // Encrypt using transposition cipher
    for ($i = 0; $i < count($key); $i++) {
        for ($j = $i; $j < $len; $j += count($key)) {
            $encrypted .= $password[$j];
        }
    }

    return $encrypted;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Set your transposition cipher key
    $cipherKey = array(3, 1, 2);
    
    // Encrypt the password
    $encryptedPassword = encryptPassword($password, $cipherKey);

    // Save user data to the database
    $sql = "INSERT INTO users (username, password, encrypt_password) VALUES ('$username', '$password', '$encryptedPassword')";

    if ($conn->query($sql) === TRUE) {
        echo "Registration successful!";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>
