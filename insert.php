<?php
include("connect.php");
$message = "";

// Prevent undefined variable error
$editData = null;

// ✅ Handle Form Submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["save"])) {
    $uname = trim($_POST["uname"]);
    $uemail = trim($_POST["uemail"]);
    $unum = trim($_POST["unum"]);
    $upsw = trim($_POST["upsw"]);
    $original_email = $_POST['original_email'] ?? '';

    if (empty($uname) || empty($uemail) || empty($unum) || empty($upsw)) {
        $message = "<div class='alert alert-danger'>❌ All fields are required!</div>";
    } else {
        if (!empty($original_email)) {
            // UPDATE
            $stmt = $conn->prepare("UPDATE users SET uname=?, uemail=?, unum=?, upsw=? WHERE uemail=?");
            $stmt->bind_param("sssss", $uname, $uemail, $unum, $upsw, $original_email);
        } else {
            // INSERT
            $stmt = $conn->prepare("INSERT INTO users (uname, uemail, unum, upsw) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $uname, $uemail, $unum, $upsw);
        }

        if ($stmt->execute()) {
            $message = "<div class='alert alert-success'>✅ Record saved successfully!</div>";
        } else {
            $message = "<div class='alert alert-danger'>❌ Database error: " . $stmt->error . "</div>";
        }

        $stmt->close();
    }
}

// ✅ Fetch all users
$result = $conn->query("SELECT * FROM users");

// ✅ Edit Data Load
if (isset($_GET['edit'])) {
    $editEmail = $_GET['edit'];
    $stmt = $conn->prepare("SELECT * FROM users WHERE uemail=?");
    $stmt->bind_param("s", $editEmail);
    $stmt->execute();
    $editData = $stmt->get_result()->fetch_assoc();
    $stmt->close();
}

// ✅ Delete Logic
if (isset($_GET['delete'])) {
    $deleteEmail = $_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM users WHERE uemail=?");
    $stmt->bind_param("s", $deleteEmail);
    if ($stmt->execute()) {
        $message = "<div class='alert alert-success'>🗑️ Record deleted successfully!</div>";
    }
    $stmt->close();
}
