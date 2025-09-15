<?php
// includes/authenticate.php
session_start();
require_once __DIR__ . '/../config.php';
require_once 'db.php';

// Process Registration
if (isset($_POST['register'])) {
    $name     = trim($_POST['name']);
    $email    = trim($_POST['email']);
    $role     = $_POST['role'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Insert into users
    $stmt = $conn->prepare("INSERT INTO users (name, email, role, password) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $name, $email, $role, $password);

    if ($stmt->execute()) {
        $user_id = $conn->insert_id;

        // Role-specific insertion
        if ($role === 'owner') {
            $roleStmt = $conn->prepare("INSERT INTO owner (owner_id, name, email) VALUES (?, ?, ?)");
            $roleStmt->bind_param("iss", $user_id, $name, $email);
        } elseif ($role === 'contractor') {
            $roleStmt = $conn->prepare("INSERT INTO contractor (contractor_id, name, email) VALUES (?, ?, ?)");
            $roleStmt->bind_param("iss", $user_id, $name, $email);
        } elseif ($role === 'manager') {
            $roleStmt = $conn->prepare("INSERT INTO manager (manager_id, name, email) VALUES (?, ?, ?)");
            $roleStmt->bind_param("iss", $user_id, $name, $email);
        } elseif ($role === 'qs') {
            $roleStmt = $conn->prepare("INSERT INTO qs (qs_id, name, email) VALUES (?, ?, ?)");
            $roleStmt->bind_param("iss", $user_id, $name, $email);
        }

        if (isset($roleStmt)) {
            $roleStmt->execute();
            $roleStmt->close();
        }

        header("Location: /service_management/pages/login.php");
    } else {
        header("Location: /service_management/pages/register.php?error=Registration failed. Try a different email.");
    }
    exit;
}

// Process Login
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_POST['register'])) {
    $email    = trim($_POST['email']);
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ? OR name = ?");
    $stmt->bind_param("ss", $email, $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($user = $result->fetch_assoc()) {
        if (password_verify($password, $user['password'])) {
            $_SESSION['user'] = $user;
            if ($user['role'] === 'owner') {
                header("Location: /service_management/pages/dashboard/owner_dashboard.php");
            }elseif ($user['role'] === 'contractor')  {
                header("Location: /service_management/pages/dashboard/contractor_dashboard.php");
            }
             elseif ($user['role'] === 'manager')  {
                header("Location: /service_management/pages/dashboard/manager_dashboard.php");
            }elseif ($user['role'] === 'qs')  {
                header("Location: /service_management/pages/dashboard/qs_dashboard.php");
            }
        } else {
            header("Location: /service_management/pages/login.php?error=Incorrect password.");
        }
    } else {
        header("Location: /service_management/pages/login.php?error=User not found.");
    }
    exit;
}
?>
