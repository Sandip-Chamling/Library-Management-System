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
  .li2 a{
    color:#4b68e8;
  }
</style>

    <ul class="uldiv" style=" list-style: none;">
            <li><div class="clickable-div" onclick="navigateTo('studentEntry.php')">Enter New Student's Data </div></li>
            <li><div class="clickable-div" onclick="navigateTo('studentRecord.php')">Show The Records Of Students</div></li>
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
