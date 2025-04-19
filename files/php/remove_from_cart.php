<?php
require_once 'session.php';
require_once 'config.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Please login to remove items from cart']);
    exit;
}

$book_id = isset($_POST['book_id']) ? (int)$_POST['book_id'] : 0;

if (!$book_id) {
    echo json_encode(['success' => false, 'message' => 'Invalid book ID']);
    exit;
}

// Check if book exists in cart
$stmt = $conn->prepare("SELECT id, quantity FROM cart WHERE user_id = ? AND book_id = ?");
$stmt->bind_param("ii", $_SESSION['user_id'], $book_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode(['success' => false, 'message' => 'Book not found in cart']);
    exit;
}

// Remove from cart
$stmt = $conn->prepare("DELETE FROM cart WHERE user_id = ? AND book_id = ?");
$stmt->bind_param("ii", $_SESSION['user_id'], $book_id);

if ($stmt->execute()) {
    // Get updated cart total
    $stmt = $conn->prepare("SELECT SUM(c.quantity * b.price) as total, COUNT(*) as count 
                           FROM cart c 
                           JOIN books b ON c.book_id = b.id 
                           WHERE c.user_id = ?");
    $stmt->bind_param("i", $_SESSION['user_id']);
    $stmt->execute();
    $result = $stmt->get_result();
    $cart_info = $result->fetch_assoc();
    
    $total = $cart_info['total'] ?? 0;
    $count = $cart_info['count'] ?? 0;
    
    echo json_encode([
        'success' => true, 
        'message' => 'Book removed from cart successfully',
        'cart_total' => number_format($total, 2),
        'cart_count' => $count
    ]);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to remove book from cart']);
} 