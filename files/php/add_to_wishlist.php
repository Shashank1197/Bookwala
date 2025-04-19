<?php
require_once 'session.php';
require_once 'config.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Please login to add items to wishlist']);
    exit;
}

$book_id = isset($_POST['book_id']) ? (int)$_POST['book_id'] : 0;

if (!$book_id) {
    echo json_encode(['success' => false, 'message' => 'Invalid book ID']);
    exit;
}

// Check if book exists
$stmt = $conn->prepare("SELECT id FROM books WHERE id = ?");
$stmt->bind_param("i", $book_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode(['success' => false, 'message' => 'Book not found']);
    exit;
}

// Check if book is already in wishlist
$stmt = $conn->prepare("SELECT id FROM wishlist WHERE user_id = ? AND book_id = ?");
$stmt->bind_param("ii", $_SESSION['user_id'], $book_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo json_encode(['success' => false, 'message' => 'Book is already in your wishlist']);
    exit;
}

// Add to wishlist
$stmt = $conn->prepare("INSERT INTO wishlist (user_id, book_id) VALUES (?, ?)");
$stmt->bind_param("ii", $_SESSION['user_id'], $book_id);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Book added to wishlist successfully']);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to add book to wishlist']);
} 