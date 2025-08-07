<?php
session_start();
require 'db.php';
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'agent') {
    echo "<script>window.location.href='login.php';</script>";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $conn->real_escape_string($_POST['title']);
    $description = $conn->real_escape_string($_POST['description']);
    $price = $conn->real_escape_string($_POST['price']);
    $city = $conn->real_escape_string($_POST['city']);
    $state = $conn->real_escape_string($_POST['state']);
    $bedrooms = $conn->real_escape_string($_POST['bedrooms']);
    $type = $conn->real_escape_string($_POST['type']);
    $amenities = $conn->real_escape_string($_POST['amenities']);
    $image_url = $conn->real_escape_string($_POST['image_url']);
    $user_id = $_SESSION['user_id'];

    $sql = "INSERT INTO properties (user_id, title, description, price, city, state, bedrooms, type, amenities, image_url, featured) 
            VALUES ('$user_id', '$title', '$description', '$price', '$city', '$state', '$bedrooms', '$type', '$amenities', '$image_url', 0)";
    if ($conn->query($sql)) {
        echo "<script>alert('Property listed successfully!'); window.location.href='dashboard.php';</script>";
    } else {
        echo "<script>alert('Error: " . $conn->error . "');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>List Property - Zillow Clone</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f9;
        }
        header {
            background-color: #006aff;
            color: white;
            padding: 1rem;
            text-align: center;
        }
        .form-container {
            max-width: 600px;
            margin: 2rem auto;
            background: white;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        .form-group {
            margin-bottom: 1rem;
        }
        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: bold;
        }
        .form-group input, .form-group textarea, .form-group select {
            width: 100%;
            padding: 0.5rem;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 1rem;
        }
        button {
            width: 100%;
            padding: 0.75rem;
            background-color: #006aff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 1rem;
        }
        button:hover {
            background-color: #0051cc;
        }
        @media (max-width: 768px) {
            .form-container {
                margin: 1rem;
                padding: 1rem;
            }
        }
    </style>
</head>
<body>
    <header>
        <h1>List Property</h1>
        <nav>
            <a href="#" onclick="navigate('index.php')" style="color: white; margin: 0 1rem;">Home</a>
            <a href="#" onclick="navigate('dashboard.php')" style="color: white; margin: 0 1rem;">Dashboard</a>
            <a href="#" onclick="logout()" style="color: white; margin: 0 1rem;">Logout</a>
        </nav>
    </header>
    <div class="form-container">
        <h2>List a New Property</h2>
        <form method="POST">
            <div class="form-group">
                <label for="title">Title</label>
                <input type="text" id="title" name="title" required>
            </div>
            <div class="form-group">
                <label for="description">Description</label>
                <textarea id="description" name="description" rows="5" required></textarea>
            </div>
            <div class="form-group">
                <label for="price">Price ($)</label>
                <input type="number" id="price" name="price" required>
            </div>
            <div class="form-group">
                <label for="city">City</label>
                <input type="text" id="city" name="city" required>
            </div>
            <div class="form-group">
                <label for="state">State</label>
                <input type="text" id="state" name="state" required>
            </div>
            <div class="form-group">
                <label for="bedrooms">Bedrooms</label>
                <input type="number" id="bedrooms" name="bedrooms" required>
            </div>
            <div class="form-group">
                <label for="type">Property Type</label>
                <select id="type" name="type" required>
                    <option value="house">House</option>
                    <option value="apartment">Apartment</option>
                    <option value="commercial">Commercial</option>
                </select>
            </div>
            <div class="form-group">
                <label for="amenities">Amenities (comma-separated)</label>
                <input type="text" id="amenities" name="amenities" placeholder="e.g., pool, garage, gym">
            </div>
            <div class="form-group">
                <label for="image_url">Image URL</label>
                <input type="text" id="image_url" name="image_url" placeholder="e.g., https://example.com/image.jpg">
            </div>
            <button type="submit">List Property</button>
        </form>
    </div>
    <script>
        function navigate(url) {
            window.location.href = url;
        }
        function logout() {
            navigate('login.php');
        }
    </script>
</body>
</html>
