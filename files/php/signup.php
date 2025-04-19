<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h2>Sign Up</h2>
        <form action="signup.php" method="post">
            <input type="text" placeholder="Username" class="spacebox" name="username" required><br>
            <input type="email" placeholder="Email" class="spacebox" name="email" required><br>
            <input type="password" placeholder="Password" class="spacebox" name="password" required><br>
            <input type="password" placeholder="Confirm Password" class="spacebox" name="cpassword" required><br>

            <input type="submit" value="SIGN UP" id="sbtbtn"><br>
            <a id="haha" href="index.php">Login →</a></p>
        </form>
    </div>
</body>
</html>