<?php
include 'config.php';

function decryptPassword($encryptedPassword, $key) {
    $len = strlen($encryptedPassword);
    $decrypted = str_repeat(' ', $len);  // Inisialisasi dengan spasi

    // Dekripsi menggunakan sandi transposisi
    $index = 0;
    for ($i = 0; $i < count($key); $i++) {
        for ($j = $i; $j < $len; $j += count($key)) {
            $decrypted[$j] = $encryptedPassword[$index++];
        }
    }

    return trim($decrypted);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Set your transposition cipher key
    $cipherKey = array(3, 1, 2);

    // Retrieve encrypted password from the database based on the username
    $sql = "SELECT encrypt_password FROM users WHERE username = '$username'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $encryptedPasswordFromDB = $row["encrypt_password"];

        // Decrypt the password
        $decryptedPassword = decryptPassword($encryptedPasswordFromDB, $cipherKey);

        // Debugging output
        echo "Encrypted Password from DB: $encryptedPasswordFromDB <br>";
        echo "Decrypted Password: $decryptedPassword <br>";

        // Check if the decrypted password matches the input password
        if ($decryptedPassword === $password) {
            echo "Login successful!";
        } else {
            echo "Login failed. Invalid username or password.";
        }
    } else {
        echo "Login failed. Invalid username or password.";
    }
}

$conn->close();
?>
