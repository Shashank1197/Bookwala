<?php
require_once 'files/php/session.php';
require_once 'files/php/config.php';

// Initialize cart if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Get cart count
$cartCount = count($_SESSION['cart']);

// Check if user is logged in
$isLoggedIn = isset($_SESSION['user_id']);
$userName = $isLoggedIn ? $_SESSION['user_name'] : '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BOOKwala - Your Digital Library</title>
    <link rel="stylesheet" href="files/css/styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <header>
        <nav class="nav-bar">
            <div class="nav-left">
                <div class="logo">
                    <a href="index.php">BookWala</a>
                </div>
                <div class="nav-links">
                    <a href="index.php" class="active">Home</a>
                    <a href="files/php/bestseller.php">Best Sellers</a>
                    <a href="files/php/newreleases.php">New Releases</a>
                    <a href="files/php/category.php">Categories</a>
                    <?php if ($isLoggedIn): ?>
                        <a href="files/php/addbook.php">Add Book</a>
                    <?php endif; ?>
                </div>
            </div>

            <div class="search-bar">
                <form action="files/php/search.php" method="GET">
                    <input type="text" name="query" placeholder="Search for books, authors, or categories...">
                    <button type="submit" class="search-btn">
                        <i class="fas fa-search"></i>
                    </button>
                </form>
            </div>

            <div class="nav-right">
                <div class="cart-icon" id="cartIcon">
                    <i class="fas fa-shopping-cart"></i>
                    <span class="cart-count"><?php echo $cartCount; ?></span>
                    
                    <!-- Cart Dropdown -->
                    <div class="cart-dropdown" id="cartDropdown">
                        <?php if ($cartCount > 0): ?>
                            <div class="cart-items">
                                <?php
                                $total = 0;
                                foreach ($_SESSION['cart'] as $item):
                                    $total += $item['price'];
                                ?>
                                <div class="cart-item">
                                    <img src="files/images/<?php echo htmlspecialchars($item['image']); ?>" alt="<?php echo htmlspecialchars($item['title']); ?>">
                                    <div class="cart-item-info">
                                        <div class="cart-item-title"><?php echo htmlspecialchars($item['title']); ?></div>
                                        <div class="cart-item-price">₹<?php echo number_format($item['price'], 2); ?></div>
                                    </div>
                                    <button class="cart-item-remove" data-id="<?php echo $item['id']; ?>">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                                <?php endforeach; ?>
                            </div>
                            <div class="cart-total">
                                <span>Total:</span>
                                <span>₹<?php echo number_format($total, 2); ?></span>
                            </div>
                            <div class="cart-buttons">
                                <a href="files/php/cart.php" class="btn btn-primary">View Cart</a>
                                <a href="files/php/checkout.php" class="btn btn-secondary">Checkout</a>
                            </div>
                        <?php else: ?>
                            <div class="empty-cart">
                                <i class="fas fa-shopping-cart"></i>
                                <p>Your cart is empty</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="profile-dropdown">
                    <div class="profile-trigger" id="profileTrigger">
                        <i class="fas fa-user"></i>
                        <span><?php echo $isLoggedIn ? htmlspecialchars($userName) : 'Account'; ?></span>
                        <i class="fas fa-chevron-down"></i>
                    </div>
                    
                    <div class="profile-menu" id="profileMenu">
                        <?php if ($isLoggedIn): ?>
                            <a href="files/php/profile.php"><i class="fas fa-user-circle"></i> My Profile</a>
                            <a href="files/php/orders.php"><i class="fas fa-box"></i> My Orders</a>
                            <a href="files/php/wishlist.php"><i class="fas fa-heart"></i> Wishlist</a>
                            <div class="divider"></div>
                            <a href="files/php/logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
                        <?php else: ?>
                            <a href="files/php/login.php"><i class="fas fa-sign-in-alt"></i> Login</a>
                            <a href="files/php/register.php"><i class="fas fa-user-plus"></i> Register</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </nav>
    </header>

    <main class="container">
        <section class="hero">
            <h1>Welcome to BOOKwala</h1>
            <p>Discover millions of eBooks, audiobooks, and more</p>
        </section>

        <section class="category-section">
            <div class="section-header">
                <h2>Browse Categories</h2>
                <a href="files/php/category.php" class="view-all">View All <i class="fas fa-arrow-right"></i></a>
            </div>
            <div class="category-pills">
                <a href="files/php/category.php?category=Fiction" class="category-pill">Fiction</a>
                <a href="files/php/category.php?category=Non-Fiction" class="category-pill">Non-Fiction</a>
                <a href="files/php/category.php?category=Science" class="category-pill">Science</a>
                <a href="files/php/category.php?category=Technology" class="category-pill">Technology</a>
                <a href="files/php/category.php?category=Business" class="category-pill">Business</a>
                <a href="files/php/category.php?category=Self-Help" class="category-pill">Self-Help</a>
            </div>
        </section>

        <section class="featured-books">
            <div class="section-header">
                <h2>Featured Books</h2>
                <a href="files/php/bestseller.php" class="view-all">View All <i class="fas fa-arrow-right"></i></a>
            </div>
            <div class="grid">
                <?php
                require_once 'files/php/db.php';
                $sql = "SELECT * FROM books ORDER BY created_at DESC LIMIT 8";
                $result = mysqli_query($conn, $sql);
                
                if ($result && mysqli_num_rows($result) > 0) {
                    while ($book = mysqli_fetch_assoc($result)) {
                        $discounted_price = $book['price'] * 0.9; // 10% discount
                ?>
                <div class="book-card">
                    <?php if (strtotime($book['created_at']) > strtotime('-7 days')): ?>
                        <div class="badge badge-new">New</div>
                    <?php endif; ?>
                    
                    <img src="files/images/<?php echo htmlspecialchars($book['cover_image']); ?>" alt="<?php echo htmlspecialchars($book['title']); ?>" class="book-cover">
                    
                    <div class="book-info">
                        <h3 class="book-title"><?php echo htmlspecialchars($book['title']); ?></h3>
                        <p class="book-author">by <?php echo htmlspecialchars($book['author']); ?></p>
                        
                        <div class="rating">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star-half-alt"></i>
                            <span class="rating-count">(<?php echo rand(10, 100); ?>)</span>
                        </div>
                        
                        <div class="price-tag">
                            <span class="original-price">₹<?php echo number_format($book['price'], 2); ?></span>
                            <span class="discount-price">₹<?php echo number_format($discounted_price, 2); ?></span>
                            <span class="discount-badge">10% off</span>
                        </div>
                        
                        <div class="book-actions">
                            <button class="btn btn-primary add-to-cart" data-id="<?php echo $book['id']; ?>">
                                <i class="fas fa-shopping-cart"></i> Add to Cart
                            </button>
                            <button class="btn btn-secondary add-to-wishlist" data-id="<?php echo $book['id']; ?>">
                                <i class="fas fa-heart"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <?php
                    }
                }
                ?>
            </div>
        </section>
    </main>

    <footer>
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <h3>About BookWala</h3>
                    <p>Your one-stop destination for books. Discover millions of eBooks, audiobooks, and more.</p>
                </div>
                <div class="footer-section">
                    <h3>Quick Links</h3>
                    <ul>
                        <li><a href="files/php/about.php">About Us</a></li>
                        <li><a href="files/php/contact.php">Contact</a></li>
                        <li><a href="files/php/privacy.php">Privacy Policy</a></li>
                        <li><a href="files/php/terms.php">Terms of Service</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h3>Contact Us</h3>
                    <p>Email: support@bookwala.com</p>
                    <p>Phone: +91 123-456-7890</p>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2024 BookWala. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script src="files/js/script.js"></script>
</body>
</html>