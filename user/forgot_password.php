<!DOCTYPE html>
<html>
<head>
    <title>Forgot Password</title>
</head>
<body>
    <h2>Forgot Your Password?</h2>
    <p>Please enter your email address to reset your password.</p>
    
    <form action="reset_password.php" method="post">
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>
        
        <input type="submit" value="Reset Password">
    </form>
</body>
</html>
