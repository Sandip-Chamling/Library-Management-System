<?php
session_start();

// Database connection
require_once('dbConnection.php');

 //Check if the user is already logged in
if (isset($_SESSION['username'])) {
    header('Location:dashboard.php');
    exit();
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

   // Fetch hashed password from the database based on the provided username
   $stmt = $conn->prepare("SELECT password FROM login_tbl WHERE username = ?");
   $stmt->bind_param("s", $username);
   $stmt->execute();
   $result = $stmt->get_result();
   $row = $result->fetch_assoc();

   if ($row) {
       if (password_verify($password, $row['password'])) {
        $_SESSION['username'] = $username;
        header('Location:dashboard.php');
        exit();
    } else {
        $error = 'Invalid username or password';
       
    }
} else {
    $error = 'no-record';
}
   $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style8.css">
    <link rel="icon" type="image/x-icon" href="favicon.jpeg">
    <title>Login</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/css/all.min.css">
</head>
<body class="indexBody">
    <div class="infodiv">
        <h1 class="tith1">BCA BOOK MANAGEMENT SYSTEM</h1>
      </div>
    <div class="login-container">
    <i class='fas'>&#xf508;</i>
        <h1 id="inh1"><u>USER LOGIN</u></h1>
        <?php if (isset($error)) : ?>
            <p class="error"><?php echo $error; ?></p>
        <?php endif; ?>
        <form class="form15" action="index.php" method="post" onsubmit="return validateForm()">
            <input type="text" id="username" name="username" placeholder="User_Name" required>
            <br>
            <input type="password" id="password" name="password" placeholder="Password" required>
            <i class="far fa-eye" id="togglePassword" style="margin-left: -30px; cursor: pointer;"></i>
            <br><br>
            <button class="index-button" type="submit">Login</button>
            
        </form>
        
        <script>
            function validateForm() {
                const username = document.getElementById('username').value;
                const password = document.getElementById('password').value;
                // validate username
                if (username.length < 8) {
                    alert('Username must be at least 8 characters');
                    return false;
                }
                // password validation
                if (!(password.length > 8 && /[A-Z]/.test(password) && /[a-z]/.test(password) && /\d/.test(password) && /[!@#$%^&*()_+{}\[\]:;<>,.?~\\/-]/.test(password))) {
                    alert('Password length must be more than 8 characters and must contain at least one uppercase letter, one lowercase letter, one digit, and one special character.');
                    
                    return false;
                }
                return true;
            }
        </script>
        <script>
             const togglePassword = document.querySelector('#togglePassword');
             const password = document.querySelector('#password');

             togglePassword.addEventListener('click', function (e) {
             // toggle the type attribute
             const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
             password.setAttribute('type', type);
             // toggle the eye slash icon
            this.classList.toggle('fa-eye-slash');
             });
    </script>
    </div>
</body>
</html>

