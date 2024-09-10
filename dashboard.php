<?php
session_start();

// Check if the user is not logged in
if (!isset($_SESSION['username'])) {
    header('Location: index.php');
 exit();
}

if (isset($_GET['logout'])) {
    session_unset(); // Unset all session variables
    session_destroy(); // Destroy the session
    header('Location: index.php');
    exit();
}
require_once('header.php');
?>
<style >
  .li1 a{
    color:#4b68e8;
  }
</style>
      <div class="divlog" onclick="returnTo()">Logout</div>
      
        <h1 class="h19">Welcome </h1>
        <h1 class="h19">To </h1>
        <h1 class="h19">BCA Book Management System</h1>
       
      

        <script>
            // Function to navigate to the specified URL
            function navigateTo(url) {
                window.location.href = url;
            }
        </script>
        <script>
          function returnTo(){
            var confirmation = confirm('Ary you sure want to Logout?');
            if(confirmation){
              navigateTo('dashboard.php?logout=1');
            }
            return false;
          }
        </script>
    
  <?php require_once('footer.php'); ?>
</body>
</html>
