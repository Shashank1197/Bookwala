<?php
require_once 'session.php';
require_once 'config.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Please login to add items to cart']);
    exit;
}

$book_id = isset($_POST['book_id']) ? (int)$_POST['book_id'] : 0;

if (!$book_id) {
    echo json_encode(['success' => false, 'message' => 'Invalid book ID']);
    exit;
}

// Get book details
$stmt = $conn->prepare("SELECT * FROM books WHERE id = ?");
$stmt->bind_param("i", $book_id);
$stmt->execute();
$result = $stmt->get_result();
$book = $result->fetch_assoc();

if (!$book) {
    echo json_encode(['success' => false, 'message' => 'Book not found']);
    exit;
}

// Check if book is already in cart
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

$found = false;
foreach ($_SESSION['cart'] as &$item) {
    if ($item['id'] == $book_id) {
        $item['quantity']++;
        $found = true;
        break;
    }
}

if (!$found) {
    $_SESSION['cart'][] = [
        'id' => $book['id'],
        'title' => $book['title'],
        'price' => $book['price'],
        'image' => $book['cover_image'],
        'quantity' => 1
    ];
}

// Generate updated cart HTML
$cartHtml = '';
$total = 0;

if (count($_SESSION['cart']) > 0) {
    $cartHtml .= '<div class="cart-items">';
    foreach ($_SESSION['cart'] as $item) {
        $total += $item['price'] * $item['quantity'];
        $cartHtml .= '
            <div class="cart-item">
                <img src="../images/' . htmlspecialchars($item['image']) . '" alt="' . htmlspecialchars($item['title']) . '">
                <div class="cart-item-info">
                    <div class="cart-item-title">' . htmlspecialchars($item['title']) . '</div>
                    <div class="cart-item-price">₹' . number_format($item['price'], 2) . ' x ' . $item['quantity'] . '</div>
                </div>
                <button class="cart-item-remove" data-id="' . $item['id'] . '">
                    <i class="fas fa-times"></i>
                </button>
            </div>';
    }
    $cartHtml .= '</div>';
    $cartHtml .= '
        <div class="cart-total">
            <span>Total:</span>
            <span>₹' . number_format($total, 2) . '</span>
        </div>
        <div class="cart-buttons">
            <a href="cart.php" class="btn btn-primary">View Cart</a>
            <a href="checkout.php" class="btn btn-secondary">Checkout</a>
        </div>';
}

echo json_encode([
    'success' => true,
    'message' => 'Book added to cart successfully',
    'cartCount' => count($_SESSION['cart']),
    'cartHtml' => $cartHtml
]); 