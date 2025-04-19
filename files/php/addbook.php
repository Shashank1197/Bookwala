<?php
require_once 'db.php';

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $author = mysqli_real_escape_string($conn, $_POST['author']);
    $category = mysqli_real_escape_string($conn, $_POST['category']);
    $price = mysqli_real_escape_string($conn, $_POST['price']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    
    // Handle file upload
    $target_dir = "images/books/";
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true);
    }
    
    $target_file = $target_dir . basename($_FILES["cover"]["name"]);
    $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
    
    // Check if image file is actual image
    if(isset($_FILES["cover"])) {
        $check = getimagesize($_FILES["cover"]["tmp_name"]);
        if($check !== false) {
            if (move_uploaded_file($_FILES["cover"]["tmp_name"], $target_file)) {
                $cover_path = $target_file;
                
                // Insert book into database
                $sql = "INSERT INTO books (title, author, category, price, description, cover_image) 
                        VALUES ('$title', '$author', '$category', '$price', '$description', '$cover_path')";
                
                if (mysqli_query($conn, $sql)) {
                    $success = "Book added successfully!";
                } else {
                    $error = "Error: " . mysqli_error($conn);
                }
            } else {
                $error = "Sorry, there was an error uploading your file.";
            }
        } else {
            $error = "File is not an image.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Book - BOOKwala</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .book-form {
            max-width: 800px;
            margin: 40px auto;
            background: white;
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
        }
        .form-row {
            display: flex;
            gap: 30px;
            margin-bottom: 25px;
        }
        .form-group {
            flex: 1;
            position: relative;
        }
        .form-group label {
            display: block;
            margin-bottom: 10px;
            color: #333;
            font-weight: 500;
            font-size: 0.95em;
        }
        .form-control {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e1e1e1;
            border-radius: 10px;
            font-size: 0.95em;
            transition: all 0.3s ease;
        }
        .form-control:focus {
            border-color: #764ba2;
            box-shadow: 0 0 0 3px rgba(118, 75, 162, 0.1);
            outline: none;
        }
        textarea.form-control {
            height: 150px;
            resize: vertical;
            font-family: 'Poppins', sans-serif;
        }
        .alert {
            padding: 15px 20px;
            margin-bottom: 25px;
            border-radius: 10px;
            font-weight: 500;
        }
        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border-left: 4px solid #155724;
        }
        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
            border-left: 4px solid #721c24;
        }
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
            margin-bottom: 30px;
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
        .submit-btn {
            background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
            color: white;
            padding: 15px 30px;
            border: none;
            border-radius: 10px;
            font-size: 1.1em;
            font-weight: 500;
            cursor: pointer;
            width: 100%;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .submit-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(118, 75, 162, 0.3);
        }
        .file-upload {
            position: relative;
            overflow: hidden;
            margin-top: 10px;
        }
        .file-upload input[type=file] {
            font-size: 100px;
            position: absolute;
            left: 0;
            top: 0;
            opacity: 0;
            cursor: pointer;
        }
        .upload-btn {
            display: inline-block;
            padding: 12px 20px;
            background: #f8f9fa;
            border: 2px dashed #764ba2;
            border-radius: 10px;
            color: #764ba2;
            font-weight: 500;
            text-align: center;
            width: 100%;
            transition: all 0.3s ease;
        }
        .upload-btn:hover {
            background: #764ba2;
            color: white;
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
        <div class="book-form">
            <div class="page-title">
                <h2>Add New Book</h2>
                <p>Enter the book details below to add it to the library</p>
            </div>
            
            <?php if(isset($success)): ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i> <?php echo $success; ?>
                </div>
            <?php endif; ?>
            
            <?php if(isset($error)): ?>
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle"></i> <?php echo $error; ?>
                </div>
            <?php endif; ?>

            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
                <div class="form-row">
                    <div class="form-group">
                        <label><i class="fas fa-book"></i> Book Title</label>
                        <input type="text" class="form-control" name="title" required placeholder="Enter book title">
                    </div>
                    <div class="form-group">
                        <label><i class="fas fa-user-edit"></i> Author</label>
                        <input type="text" class="form-control" name="author" required placeholder="Enter author name">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label><i class="fas fa-tags"></i> Category</label>
                        <select class="form-control" name="category" required>
                            <option value="">Select Category</option>
                            <option value="Fiction">Fiction</option>
                            <option value="Non-Fiction">Non-Fiction</option>
                            <option value="Science">Science</option>
                            <option value="Technology">Technology</option>
                            <option value="Business">Business</option>
                            <option value="Arts">Arts</option>
                            <option value="History">History</option>
                            <option value="Biography">Biography</option>
                            <option value="Children">Children</option>
                            <option value="Comics">Comics & Graphic Novels</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label><i class="fas fa-tag"></i> Price</label>
                        <input type="number" step="0.01" class="form-control" name="price" required placeholder="Enter price">
                    </div>
                </div>

                <div class="form-group">
                    <label><i class="fas fa-align-left"></i> Description</label>
                    <textarea class="form-control" name="description" required placeholder="Enter book description"></textarea>
                </div>

                <div class="form-group">
                    <label><i class="fas fa-image"></i> Cover Image</label>
                    <div class="file-upload">
                        <div class="upload-btn">
                            <i class="fas fa-cloud-upload-alt"></i> Choose Cover Image
                        </div>
                        <input type="file" class="form-control" name="cover" accept="image/*" required>
                    </div>
                </div>

                <button type="submit" class="submit-btn">
                    <i class="fas fa-plus-circle"></i> Add Book
                </button>
            </form>
        </div>
    </div>
</body>
</html> 