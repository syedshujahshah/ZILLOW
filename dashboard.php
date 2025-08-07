<?php
session_start();
require 'db.php';
if (!isset($_SESSION['user_id'])) {
    echo "<script>window.location.href='login.php';</script>";
    exit;
}

// Save property
if (isset($_POST['save_property'])) {
    $property_id = $conn->real_escape_string($_POST['property_id']);
    $user_id = $_SESSION['user_id'];
    $sql = "INSERT INTO saved_listings (user_id, property_id) VALUES ('$user_id', '$property_id')";
    $conn->query($sql);
}

// Delete property (for agents)
if (isset($_POST['delete_property'])) {
    $property_id = $conn->real_escape_string($_POST['property_id']);
    $sql = "DELETE FROM properties WHERE id = '$property_id' AND user_id = '{$_SESSION['user_id']}'";
    $conn->query($sql);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Zillow Clone</title>
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
        .dashboard {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 1rem;
        }
        .section {
            background: white;
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        .property-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1rem;
        }
        .property-card {
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        .property-card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }
        .property-card h3 {
            margin: 0.5rem;
            font-size: 1.2rem;
        }
        .property-card p {
            margin: 0.5rem;
            color: #666;
        }
        button {
            padding: 0.5rem;
            background-color: #006aff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin: 0.5rem;
        }
        button:hover {
            background-color: #0051cc;
        }
        @media (max-width: 768px) {
            .dashboard {
                margin: 1rem;
            }
        }
    </style>
</head>
<body>
    <header>
        <h1>Dashboard</h1>
        <nav>
            <a href="#" onclick="navigate('index.php')" style="color: white; margin: 0 1rem;">Home</a>
            <a href="#" onclick="navigate('list_property.php')" style="color: white; margin: 0 1rem;">List Property</a>
            <a href="#" onclick="logout()" style="color: white; margin: 0 1rem;">Logout</a>
        </nav>
    </header>
    <div class="dashboard">
        <?php if ($_SESSION['role'] == 'agent'): ?>
            <div class="section">
                <h2>Your Listings</h2>
                <div class="property-grid">
                    <?php
                    $user_id = $_SESSION['user_id'];
                    $sql = "SELECT * FROM properties WHERE user_id = '$user_id'";
                    $result = $conn->query($sql);
                    while ($row = $result->fetch_assoc()):
                    ?>
                        <div class="property-card">
                            <img src="<?php echo $row['image_url'] ?: 'https://source.unsplash.com/random/300x200?house'; ?>" alt="Property">
                            <h3><?php echo htmlspecialchars($row['title']); ?></h3>
                            <p><?php echo htmlspecialchars($row['city'] . ', ' . $row['state']); ?></p>
                            <p>$<?php echo number_format($row['price']); ?></p>
                            <form method="POST">
                                <input type="hidden" name="property_id" value="<?php echo $row['id']; ?>">
                                <button type="submit" name="delete_property">Delete</button>
                            </form>
                        </div>
                    <?php endwhile; ?>
                </div>
            </div>
        <?php endif; ?>
        <div class="section">
            <h2>Saved Listings</h2>
            <div class="property-grid">
                <?php
                $user_id = $_SESSION['user_id'];
                $sql = "SELECT p.* FROM properties p JOIN saved_listings s ON p.id = s.property_id WHERE s.user_id = '$user_id'";
                $result = $conn->query($sql);
                while ($row = $result->fetch_assoc()):
                ?>
                    <div class="property-card" onclick="navigate('property_details.php?id=<?php echo $row['id']; ?>')">
                        <img src="<?php echo $row['image_url'] ?: 'https://source.unsplash.com/random/300x200?house'; ?>" alt="Property">
                        <h3><?php echo htmlspecialchars($row['title']); ?></h3>
                        <p><?php echo htmlspecialchars($row['city'] . ', ' . $row['state']); ?></p>
                        <p>$<?php echo number_format($row['price']); ?></p>
                    </div>
                <?php endwhile; ?>
            </div>
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
