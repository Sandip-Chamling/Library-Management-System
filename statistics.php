<?php
session_start();

// Check if the user is not logged in
if (!isset($_SESSION['username'])) {
    header('Location: index.php');
 exit();
}
require_once('header.php');
?>
<style >
  .li4 a{
    color:#4b68e8;
  }
</style>
<style >
  .li4 a{
    color:#4b68e8;
  }
</style>
    <ul class="uldiv5" style=" list-style: none;">
            <li><div class="clickable-div" onclick="navigateTo('borrow.php')">Borrow Status</div></li>
            <li><div class="clickable-div" onclick="navigateTo('expire.php')">Expired Stauus</div></li>
            <li><div class="clickable-div" onclick="navigateTo('available.php')">Available Books</div></li>
            <li><div class="clickable-div" onclick="navigateTo('reserved.php')">Reserved Books</div></li>
            <li><div class="clickable-div" onclick="navigateTo('total.php')">Total</div></li>
</ul>

    
  <script>
            // Function to navigate to the specified URL
            function navigateTo(url) {
                window.location.href = url;
            }
        </script>
        <?php require_once('footer.php'); ?>
</body>
</html>
