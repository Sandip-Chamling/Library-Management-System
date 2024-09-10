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
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<div class="outer-div">

<div class="inner-div15">
  <form class="form33" id="Form" action="nameStudent.php" method="post">
    
    <input type="text" id="search" name="search" placeholder="Enter Student's Name" required list="student">
    <datalist id="student">
                     <?php
                        require_once('dbConnection.php');
                        $availableName = "SELECT name FROM student";
                        $availableNameResult = $conn->query($availableName);
                        while ($nameRow = $availableNameResult->fetch_assoc()) {
                            echo "<option value='{$nameRow['name']}'>";
                        }
                       ?>
      </datalist>

    <input type="hidden" id="selectedYearForm33" name="year" value="">
    <!--<button type="submit">Enter</button>-->
    <button type="submit"><i class="fa fa-search"></i></button>
    </form>

  
    <form class="yearform" id="yearform" method="get">
    <label for="filter_year">Batch:</label>
    <select id="year" name="year" onchange="submitForms()">
    <option value="2019">2019</option>
        <option value="2020">2020</option>
        <option value="2021">2021</option>
        <option value="2022">2022</option>
        <option value="2023">2023</option>
        <option value="2024">2024</option>
        <option value="2025">2025</option>
        <option value="2026">2026</option>
        <option value="2027">2027</option>
        <option value="2028">2028</option>
        <option value="2029">2029</option>
    </select>
</form>
</div>
<script>
    
    function submitForms() {
        // Get the selected year value
        var selectedYear = document.getElementById('year').value;

        // Set the value for the hidden input field in form33
        document.getElementById('selectedYearForm33').value = selectedYear;

        // Set the value for the hidden input field in semesterForm
        document.getElementById('selectedYearSemesterForm').value = selectedYear;

        // You can also add additional logic here if needed
    }
</script>


    <div class="inner-div25" >
    <h2 id="h2edit"> Semesters:</h2>

<form id="semesterForm" action="semesterStudent.php" method="post">
    
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

    <input type="hidden" id="selectedYearSemesterForm" name="selectedyear" value="">

</form></div>
</div>
<script>
    // Set the initial value for the hidden input field
    document.getElementById('selectedYearForm33').value = "2019";
     document.getElementById('selectedYearSemesterForm').value = "2019";

</script>

<script>
    
    function submitForm(semester) {
        // Set the value of the hidden input field to the selected semester
        document.getElementById('selectedSemester').value = semester;

        // Submit the form
        document.getElementById('semesterForm').submit();
    }
</script>
<script>
    // Reload the page when the user returns from another page
    window.addEventListener('pageshow', function(event) {
        // If the page was cached and the user returns, reload the page
        if (event.persisted || (window.performance && window.performance.navigation.type === 2)) {
            window.location.reload();
        }
    });
</script>

<?php require_once('footer.php'); ?>
</body>
</html>
