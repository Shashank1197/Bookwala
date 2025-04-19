<?php
require_once 'db.php';

// For demonstration, we'll show all books sorted by creation date
// In a real application, you would track sales and sort by that
$sql = "SELECT * FROM books ORDER BY created_at DESC LIMIT 10";
$result = mysqli_query($conn, $sql);
$bestsellers = [];
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $bestsellers[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bestsellers - BOOKwala</title>
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
            padding: 0 20px;
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
        .bestsellers-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
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
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            position: relative;
        }
        .book-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        }
        .book-content {
            display: flex;
            padding: 20px;
        }
        .book-cover-wrapper {
            width: 120px;
            flex-shrink: 0;
            margin-right: 20px;
        }
        .book-cover {
            width: 100%;
            height: 180px;
            object-fit: cover;
            border-radius: 8px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
        }
        .book-details {
            flex-grow: 1;
        }
        .book-title {
            font-size: 1.2em;
            color: #333;
            margin-bottom: 8px;
            font-weight: 500;
        }
        .book-author {
            color: #666;
            font-size: 0.9em;
            margin-bottom: 12px;
        }
        .book-description {
            color: #666;
            font-size: 0.9em;
            margin: 15px 0;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        .book-meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 15px;
        }
        .book-price {
            color: #764ba2;
            font-weight: 600;
            font-size: 1.2em;
        }
        .book-category {
            background: #f8f9fa;
            padding: 5px 12px;
            border-radius: 15px;
            color: #666;
            font-size: 0.85em;
        }
        .bestseller-badge {
            position: absolute;
            top: 15px;
            right: 15px;
            background: #ffd700;
            color: #333;
            padding: 5px 15px;
            border-radius: 15px;
            font-size: 0.85em;
            font-weight: 500;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .empty-state {
            text-align: center;
            padding: 50px 20px;
            color: #666;
        }
        .empty-state i {
            font-size: 3em;
            color: #764ba2;
            margin-bottom: 20px;
        }
        .empty-state p {
            font-size: 1.1em;
            margin-bottom: 20px;
        }
        .add-book-btn {
            display: inline-block;
            padding: 12px 25px;
            background: #764ba2;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            transition: background 0.3s ease;
        }
        .add-book-btn:hover {
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
            <h2>Bestsellers</h2>
            <p>Our most popular books loved by readers</p>
        </div>

        <?php if (empty($bestsellers)): ?>
            <div class="empty-state">
                <i class="fas fa-books"></i>
                <p>No bestsellers available at the moment</p>
                <a href="addbook.php" class="add-book-btn">
                    <i class="fas fa-plus-circle"></i> Add New Book
                </a>
            </div>
        <?php else: ?>
            <div class="bestsellers-grid">
                <?php foreach ($bestsellers as $index => $book): ?>
                    <div class="book-card">
                        <div class="bestseller-badge">
                            <i class="fas fa-crown"></i> #<?php echo $index + 1; ?> Bestseller
                        </div>
                        <div class="book-content">
                            <div class="book-cover-wrapper">
                                <img src="<?php echo htmlspecialchars($book['cover_image']); ?>" alt="<?php echo htmlspecialchars($book['title']); ?>" class="book-cover">
                            </div>
                            <div class="book-details">
                                <div class="book-title"><?php echo htmlspecialchars($book['title']); ?></div>
                                <div class="book-author">by <?php echo htmlspecialchars($book['author']); ?></div>
                                <div class="book-description"><?php echo htmlspecialchars($book['description']); ?></div>
                                <div class="book-meta">
                                    <div class="book-price">₹<?php echo number_format($book['price'], 2); ?></div>
                                    <div class="book-category"><?php echo htmlspecialchars($book['category']); ?></div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>