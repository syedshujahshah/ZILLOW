<?php
session_start();
require 'db.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Zillow Clone - Home</title>
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
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        header h1 {
            margin: 0;
            font-size: 2rem;
        }
        nav a {
            color: white;
            margin: 0 1rem;
            text-decoration: none;
            font-weight: bold;
        }
        nav a:hover {
            text-decoration: underline;
        }
        .hero {
            background: url('https://source.unsplash.com/random/1600x400?real-estate') no-repeat center;
            background-size: cover;
            padding: 4rem 2rem;
            text-align: center;
            color: white;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.5);
        }
        .hero h2 {
            font-size: 2.5rem;
            margin-bottom: 1rem;
        }
        .search-bar {
            background: rgba(255,255,255,0.9);
            padding: 1rem;
            border-radius: 8px;
            display: inline-block;
        }
        .search-bar input {
            padding: 0.5rem;
            font-size: 1rem;
            border: none;
            border-radius: 4px;
            margin-right: 0.5rem;
        }
        .search-bar button {
            padding: 0.5rem 1rem;
            background-color: #006aff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .search-bar button:hover {
            background-color: #0051cc;
        }
        .properties {
            padding: 2rem;
            max-width: 1200px;
            margin: 0 auto;
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
            transition: transform 0.3s;
        }
        .property-card:hover {
            transform: translateY(-5px);
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
            header h1 {
                font-size: 1.5rem;
            }
            .hero h2 {
                font-size: 1.8rem;
            }
            .search-bar input {
                width: 100%;
                margin-bottom: 0.5rem;
            }
        }
    </style>
</head>
<body>
    <header>
        <h1>Zillow Clone</h1>
        <nav>
            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="#" onclick="navigate('dashboard.php')">Dashboard</a>
                <a href="#" onclick="navigate('list_property.php')">List Property</a>
                <a href="#" onclick="logout()">Logout</a>
            <?php else: ?>
                <a href="#" onclick="navigate('login.php')">Login</a>
                <a href="#" onclick="navigate('signup.php')">Signup</a>
            <?php endif; ?>
            <a href="#" onclick="navigate('search.php')">Search</a>
        </nav>
    </header>
    <section class="hero">
        <h2>Find Your Dream Home</h2>
        <div class="search-bar">
            <input type="text" id="search-input" placeholder="Enter city, state, or neighborhood">
            <button onclick="navigate('search.php')">Search</button>
        </div>
    </section>
    <section class="properties">
        <h2>Featured Properties</h2>
        <div class="property-grid">
            <?php
            $sql = "SELECT * FROM properties WHERE featured = 1 LIMIT 6";
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
    </section>
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
