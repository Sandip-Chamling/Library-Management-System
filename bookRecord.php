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
  .li3 a{
    color:#4b68e8;
  }
</style>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

<div class="outer-div">

<div class="inner-div15">
  <form class="form34" id="Form" action="bookCode.php" method="post">
   
    <input type="text" id="search" name="search" placeholder="Enter book_code" required list="book">
                 <datalist id="book">
                        <!-- Fetch and populate book codes dynamically from the database -->
                        <?php
                        require_once('dbConnection.php');
                        $availableBooksSql = "SELECT book_code FROM book";
                        $availableBooksResult = $conn->query($availableBooksSql);
                        while ($bookCodeRow = $availableBooksResult->fetch_assoc()) {
                            echo "<option value='{$bookCodeRow['book_code']}'>";
                        }
                        ?>
                    </datalist>
    <!--<button type="submit">Enter</button>-->
    <button type="submit"><i class="fa fa-search"></i></button>
    </form><br><br>

    <form class="form34" id="Form" action="bookName.php" method="post" 
    style="width:60%; margin-left:20%; background-color:rgb(36, 154, 213) ">
    
    <input type="text" id="search" name="search" placeholder="Enter book_name"  required list="books">
                     <datalist id="books">
                        <!-- Fetch and populate book title dynamically from the database -->
                        <?php
                        require_once('dbConnection.php');
                        $availableBooksSql = "SELECT title FROM book";
                        $availableBooksResult = $conn->query($availableBooksSql);
                        while ($booktitleRow = $availableBooksResult->fetch_assoc()) {
                            echo "<option value='{$booktitleRow['title']}'>";
                        }
                        ?>
                    </datalist>

    <!--<button type="submit">Enter</button>-->
    <button type="submit"><i class="fa fa-search"></i></button>
    </form>

</div>

    <div class="inner-div25" >
    <h2 id="h2edit"> Semesters:</h2>

<form id="semesterForm" action="semesterBook.php" method="post">
    
    <ul id="semesterList1">
            <li onclick='submitForm("1st")'>1st Semester</li>
            <li onclick='submitForm("2nd")'>2nd Semester</li>
            <li onclick='submitForm("3rd")'>3rd Semester</li>
            <li onclick='submitForm("4th")'>4th Semester</li>
            <li onclick='submitForm("5th")'>5th Semester</li>
            <li onclick='submitForm("6th")'>6th Semester</li>
            <li onclick='submitForm("7th")'>7th Semester</li>
            <li onclick='submitForm("8th")'>8th Semester</li>
    </ul>
    <input type="hidden" id="selectedSemester" name="selectedSemester" value="">

</form></div>
</div>

<script>
    
    function submitForm(semester) {
        // Set the value of the hidden input field to the selected semester
        document.getElementById('selectedSemester').value = semester;

        // Submit the form
        document.getElementById('semesterForm').submit();
    }
</script>

<?php require_once('footer.php'); ?>
</body>
</html>
