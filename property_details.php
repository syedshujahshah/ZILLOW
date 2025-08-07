<?php
session_start();
require 'db.php';

if (!isset($_GET['id'])) {
    echo "<script>window.location.href='index.php';</script>";
    exit;
}
$property_id = $conn->real_escape_string($_GET['id']);
$sql = "SELECT p.*, u.username, u.email FROM properties p JOIN users u ON p.user_id = u.id WHERE p.id = '$property_id'";
$result = $conn->query($sql);
$property = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['inquire'])) {
    if (!isset($_SESSION['user_id'])) {
        echo "<script>alert('Please login to send an inquiry'); window.location.href='login.php';</script>";
        exit;
    }
    $user_id = $_SESSION['user_id'];
    $message = $conn->real_escape_string($_POST['message']);
    $sql = "INSERT INTO inquiries (property_id, user_id, message) VALUES ('$property_id', '$user_id', '$message')";
    if ($conn->query($sql)) {
        echo "<script>alert('Inquiry sent successfully!');</script>";
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
    <title>Property Details - Zillow Clone</title>
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
        .property-details {
            max-width: 1200px;
            margin: 2rem auto;
            display: flex;
            gap: 2rem;
            padding: 0 1rem;
        }
        .property-image img {
            width: 100%;
            max-width: 600px;
            border-radius: 8px;
        }
        .property-info {
            background: white;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            flex: 1;
        }
        .property-info h2 {
            margin-top: 0;
            color: #006aff;
        }
        .inquiry-form {
            background: white;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            max-width: 400px;
        }
        .form-group {
            margin-bottom: 1rem;
        }
        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: bold;
        }
        .form-group textarea {
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
            .property-details {
                flex-direction: column;
            }
            .property-image img {
                max-width: 100%;
            }
            .inquiry-form {
                max-width: 100%;
            }
        }
    </style>
</head>
<body>
    <header>
        <h1>Property Details</h1>
        <nav>
            <a href="#" onclick="navigate('index.php')" style="color: white; margin: 0 1rem;">Home</a>
            <a href="#" onclick="navigate('dashboard.php')" style="color: white; margin: 0 1rem;">Dashboard</a>
            <a href="#" onclick="logout()" style="color: white; margin: 0 1rem;">Logout</a>
        </nav>
    </header>
    <div class="property-details">
        <div class="property-image">
            <img src="<?php echo $property['image_url'] ?: 'https://source.unsplash.com/random/600x400?house'; ?>" alt="Property">
        </div>
        <div class="property-info">
            <h2><?php echo htmlspecialchars($property['title']); ?></h2>
            <p><strong>Location:</strong> <?php echo htmlspecialchars($property['city'] . ', ' . $property['state']); ?></p>
            <p><strong>Price:</strong> $<?php echo number_format($property['price']); ?></p>
            <p><strong>Bedrooms:</strong> <?php echo htmlspecialchars($property['bedrooms']); ?></p>
            <p><strong>Type:</strong> <?php echo htmlspecialchars($property['type']); ?></p>
            <p><strong>Amenities:</strong> <?php echo htmlspecialchars($property['amenities']); ?></p>
            <p><strong>Description:</strong> <?php echo htmlspecialchars($property['description']); ?></p>
            <p><strong>Agent:</strong> <?php echo htmlspecialchars($property['username']); ?> (<?php echo htmlspecialchars($property['email']); ?>)</p>
            <?php if (isset($_SESSION['user_id'])): ?>
                <form method="POST">
                    <input type="hidden" name="property_id" value="<?php echo $property['id']; ?>">
                    <button type="submit" name="save_property">Save to Favorites</button>
                </form>
            <?php endif; ?>
        </div>
        <div class="inquiry-form">
            <h3>Contact Agent</h3>
            <form method="POST">
                <div class="form-group">
                    <label for="message">Your Message</label>
                    <textarea id="message" name="message" rows="5" required></textarea>
                </div>
                <button type="submit" name="inquire">Send Inquiry</button>
            </form>
        </div>
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
