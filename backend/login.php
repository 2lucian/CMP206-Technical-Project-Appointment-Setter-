<?php
include 'dbconnect.php';

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo "Invalid request method.";
    exit;
}

$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';

if (empty($email) || empty($password)) {
    echo "Email and password are required.";
    exit;
}

// Try patient login first
$stmt = $conn->prepare("SELECT * FROM patients WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

$user = null;
$role = null;

if ($result && $result->num_rows === 1) {
    $user = $result->fetch_assoc();
    $role = "patient";
}

$stmt->close();

// Try doctor login
if (!$user) {
    $stmt = $conn->prepare("SELECT * FROM doctors WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result && $result->num_rows === 1) {
        $user = $result->fetch_assoc();
        $role = "doctor";
    }

    $stmt->close();
}

// Try admin login
if (!$user) {
    $stmt = $conn->prepare("SELECT * FROM admin WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result && $result->num_rows === 1) {
        $user = $result->fetch_assoc();
        $role = "admin";
    }

    $stmt->close();
}

// No user found
if (!$user) {
    // Redirect back to the login page and indicate an error so the
    // front-end can show a popup instead of echoing here.
    header("Location: http://localhost/frontend/Log_In.html?error=1");
    $conn->close();
    exit;
}

// Verify password
if (!password_verify($password, $user['password'])) {
    // Redirect back to the login page and indicate an error so the
    // front-end can show a popup instead of echoing here.
    header("Location: http://localhost/frontend/Log_In.html?error=1");
    $conn->close();
    exit;
}

// Set cookie and redirect after password verification
if ($role === "patient") {
    setcookie("userID", $user['patient_id'], time() + (3600 * 2), "/");
    header("Location: http://localhost/frontend/Home.html");
    exit;
}

if ($role === "doctor") {
    setcookie("userID", $user['doctor_id'], time() + (3600 * 2), "/");
    header("Location: http://localhost/frontend/Doctor.html");
    exit;
}

if ($role === "admin") {
    setcookie("userID", $user['admin_id'], time() + (3600 * 2), "/");
    header("Location: http://localhost/frontend/Admin.html");
    exit;
}

$conn->close();
?>