<?php
include '../settings/connection.php';

$errors = [];

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = mysqli_real_escape_string($db, $_POST['name']);
    $email = mysqli_real_escape_string($db, filter_var($_POST['email'], FILTER_SANITIZE_EMAIL));
    $password = $_POST['password']; 

    // Validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }

    // Validate password
    if (!preg_match('/^((?=\S*?[A-Z])(?=\S*?[a-z])(?=\S*?[0-9]).{6,})\S$/', $password)) {
        $errors[] = "Password must have at least one uppercase letter, one lowercase letter, and one number.";
    }

    // Check if email already exists in the database
    $query = "SELECT * FROM users WHERE email = ?";
    $stmt = $db->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $errors[] = "Email already exists.";
    }

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // If no errors, insert the user into the database
    if (empty($errors)) {
        $query = "INSERT INTO users (name, email, password) VALUES (?, ?, ?)";
        $stmt = $db->prepare($query);
        $stmt->bind_param("sss", $name, $email, $hashed_password);

        if ($stmt->execute()) {
            echo ''; // Empty string to indicate success
            exit();
        } else {
            $errors[] = "Error: " . $stmt->error;
        }
    }

    // Return errors as a response
    if (!empty($errors)) {
        echo implode('<br>', $errors);
    }
}
