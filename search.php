<?php
session_start();
require 'db.php';

$filters = [];
$query = "SELECT * FROM properties WHERE 1=1";
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!empty($_POST['location'])) {
        $location = $conn->real_escape_string($_POST['location']);
        $query .= " AND (city LIKE '%$location%' OR state LIKE '%$location%')";
    }
    if (!empty($_POST['min_price'])) {
        $min_price = $conn->real_escape_string($_POST['min_price']);
        $query .= " AND price >= '$min_price'";
    }
    if (!empty($_POST['max_price'])) {
        $max_price = $conn->real_escape_string($_POST['max_price']);
        $query .= " AND price <= '$max_price'";
    }
    if (!empty($_POST['type'])) {
        $type = $conn->real_escape_string($_POST['type']);
        $query .= " AND type = '$type'";
    }
    if (!empty($_POST['bedrooms'])) {
        $bedrooms = $conn->real_escape_string($_POST['bedrooms']);
        $query .= " AND bedrooms = '$bedrooms'";
    }
    if (!empty($_POST['amenities'])) {
        $amenities = $conn->real_escape_string($_POST['amenities']);
        $query .= " AND amenities LIKE '%$amenities%'";
    }
}
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Properties - Zillow Clone</title>
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
        .search-container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 1rem;
        }
        .filter-form {
            background: white;
            padding: 1rem;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
        }
        .filter-form .form-group {
            margin-bottom: 1rem;
        }
        .filter-form label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: bold;
        }
        .filter-form input, .filter-form select {
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
        @media (max-width: 768px) {
            .search-container {
                margin: 1rem;
            }
            .filter-form {
                padding: 1rem;
            }
        }
    </style>
</head>
<body>
    <header>
        <h1>Search Properties</h1>
        <nav>
            <a href="#" onclick="navigate('index.php')" style="color: white; margin: 0 1rem;">Home</a>
            <a href="#" onclick="navigate('dashboard.php')" style="color: white; margin: 0 1rem;">Dashboard</a>
            <a href="#" onclick="logout()" style="color: white; margin: 0 1rem;">Logout</a>
        </nav>
    </header>
    <div class="search-container">
        <div class="filter-form">
            <form method="POST">
                <div class="form-group">
                    <label for="location">Location (City/State)</label>
                    <input type="text" id="location" name="location" value="<?php echo isset($_POST['location']) ? htmlspecialchars($_POST['location']) : ''; ?>">
                </div>
                <div class="form-group">
                    <label for="min_price">Min Price ($)</label>
                    <input type="number" id="min_price" name="min_price" value="<?php echo isset($_POST['min_price']) ? htmlspecialchars($_POST['min_price']) : ''; ?>">
                </div>
                <div class="form-group">
                    <label for="max_price">Max Price ($)</label>
                    <input type="number" id="max_price" name="max_price" value="<?php echo isset($_POST['max_price']) ? htmlspecialchars($_POST['max_price']) : ''; ?>">
                </div>
                <div class="form-group">
                    <label for="type">Property Type</label>
                    <select id="type" name="type">
                        <option value="">Any</option>
                        <option value="house" <?php echo isset($_POST['type']) && $_POST['type'] == 'house' ? 'selected' : ''; ?>>House</option>
                        <option value="apartment" <?php echo isset($_POST['type']) && $_POST['type'] == 'apartment' ? 'selected' : ''; ?>>Apartment</option>
                        <option value="commercial" <?php echo isset($_POST['type']) && $_POST['type'] == 'commercial' ? 'selected' : ''; ?>>Commercial</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="bedrooms">Bedrooms</label>
                    <input type="number" id="bedrooms" name="bedrooms" value="<?php echo isset($_POST['bedrooms']) ? htmlspecialchars($_POST['bedrooms']) : ''; ?>">
                </div>
                <div class="form-group">
                    <label for="amenities">Amenities (e.g., pool, garage)</label>
                    <input type="text" id="amenities" name="amenities" value="<?php echo isset($_POST['amenities']) ? htmlspecialchars($_POST['amenities']) : ''; ?>">
                </div>
                <button type="submit">Search</button>
            </form>
        </div>
        <div class="property-grid">
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="property-card" onclick="navigate('property_details.php?id=<?php echo $row['id']; ?>')">
                    <img src="<?php echo $row['image_url'] ?: 'https://source.unsplash.com/random/300x200?house'; ?>" alt="Property">
                    <h3><?php echo htmlspecialchars($row['title']); ?></h3>
                    <p><?php echo htmlspecialchars($row['city'] . ', ' . $row['state']); ?></p>
                    <p>$<?php echo number_format($row['price']); ?></p>
                </div>
            <?php endwhile; ?>
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
