<?php
require_once 'db.php';

// Fetch all categories and their book counts
$sql = "SELECT category, COUNT(*) as book_count FROM books GROUP BY category";
$result = mysqli_query($conn, $sql);
$categories = [];
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $categories[] = $row;
    }
}

// Fetch books for a specific category if selected
$selected_category = isset($_GET['category']) ? mysqli_real_escape_string($conn, $_GET['category']) : null;
$books = [];
if ($selected_category) {
    $sql = "SELECT * FROM books WHERE category = '$selected_category' ORDER BY created_at DESC";
    $result = mysqli_query($conn, $sql);
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $books[] = $row;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Categories - BOOKwala</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .nav-bar {
            background: white;
            padding: 20px 40px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            position: sticky;
            top: 0;
            z-index: 1000;
        }
        .nav-bar a {
            color: #764ba2;
            text-decoration: none;
            margin-right: 30px;
            font-weight: 500;
            transition: color 0.3s ease;
        }
        .nav-bar a:hover {
            color: #667eea;
        }
        .nav-bar i {
            margin-right: 8px;
        }
        .page-title {
            text-align: center;
            margin: 40px 0;
        }
        .page-title h2 {
            color: #333;
            font-size: 2em;
            margin-bottom: 10px;
        }
        .page-title p {
            color: #666;
            font-size: 1.1em;
        }
        .categories-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 30px;
            padding: 20px;
            max-width: 1200px;
            margin: 0 auto;
        }
        .category-card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            text-align: center;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            cursor: pointer;
        }
        .category-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        }
        .category-icon {
            font-size: 2.5em;
            color: #764ba2;
            margin-bottom: 15px;
        }
        .category-name {
            font-size: 1.2em;
            color: #333;
            margin-bottom: 10px;
            font-weight: 500;
        }
        .book-count {
            color: #666;
            font-size: 0.9em;
        }
        .books-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 30px;
            padding: 20px;
            max-width: 1200px;
            margin: 0 auto;
        }
        .book-card {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
        }
        .book-card:hover {
            transform: translateY(-5px);
        }
        .book-cover {
            width: 100%;
            height: 250px;
            object-fit: cover;
        }
        .book-info {
            padding: 20px;
        }
        .book-title {
            font-size: 1.1em;
            color: #333;
            margin-bottom: 5px;
            font-weight: 500;
        }
        .book-author {
            color: #666;
            font-size: 0.9em;
            margin-bottom: 10px;
        }
        .book-price {
            color: #764ba2;
            font-weight: 600;
            font-size: 1.1em;
        }
        .back-button {
            display: inline-block;
            padding: 10px 20px;
            background: #764ba2;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            margin-bottom: 20px;
            transition: background 0.3s ease;
        }
        .back-button:hover {
            background: #667eea;
        }
    </style>
</head>
<body>
    <div class="nav-bar">
        <a href="index.php"><i class="fas fa-home"></i> Home</a>
        <a href="category.php"><i class="fas fa-list"></i> Categories</a>
        <a href="newreleases.php"><i class="fas fa-star"></i> New Releases</a>
        <a href="bestseller.php"><i class="fas fa-crown"></i> Bestsellers</a>
    </div>

    <div class="container">
        <div class="page-title">
            <?php if ($selected_category): ?>
                <a href="category.php" class="back-button"><i class="fas fa-arrow-left"></i> Back to Categories</a>
                <h2><?php echo htmlspecialchars($selected_category); ?> Books</h2>
                <p>Browse all books in this category</p>
            <?php else: ?>
                <h2>Book Categories</h2>
                <p>Browse books by your favorite category</p>
            <?php endif; ?>
        </div>

        <?php if (!$selected_category): ?>
            <div class="categories-grid">
                <?php foreach ($categories as $category): ?>
                    <a href="?category=<?php echo urlencode($category['category']); ?>" class="category-card">
                        <div class="category-icon">
                            <?php
                            $icons = [
                                'Fiction' => 'book-open',
                                'Non-Fiction' => 'book-reader',
                                'Science' => 'flask',
                                'Technology' => 'laptop-code',
                                'Business' => 'briefcase',
                                'Arts' => 'palette',
                                'History' => 'landmark',
                                'Biography' => 'user-tie',
                                'Children' => 'child',
                                'Comics' => 'mask'
                            ];
                            $icon = isset($icons[$category['category']]) ? $icons[$category['category']] : 'book';
                            ?>
                            <i class="fas fa-<?php echo $icon; ?>"></i>
                        </div>
                        <div class="category-name"><?php echo htmlspecialchars($category['category']); ?></div>
                        <div class="book-count"><?php echo $category['book_count']; ?> Books</div>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="books-grid">
                <?php foreach ($books as $book): ?>
                    <div class="book-card">
                        <img src="<?php echo htmlspecialchars($book['cover_image']); ?>" alt="<?php echo htmlspecialchars($book['title']); ?>" class="book-cover">
                        <div class="book-info">
                            <div class="book-title"><?php echo htmlspecialchars($book['title']); ?></div>
                            <div class="book-author">by <?php echo htmlspecialchars($book['author']); ?></div>
                            <div class="book-price">₹<?php echo number_format($book['price'], 2); ?></div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
