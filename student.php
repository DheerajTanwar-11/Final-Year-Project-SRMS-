<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/student.css">
    <title>Result</title>
    <style>
        /* CSS for the table */
.table {
  margin-top: 20px;
}

table {
  width: 100%;
  border-collapse: collapse;
}

th, td {
  padding: 8px;
  text-align: left;
  border-bottom: 1px solid #ddd;
}

th {
  background-color: #f2f2f2;
  color: black;
}

/* Additional CSS styles for the result section and button */
.result {
  margin-top: 20px;
  font-size: 18px;
}

.button {
  margin-top: 20px;
  text-align: center;
}

button {
  padding: 10px 20px;
  background-color: #4CAF50;
  color: #fff;
  border: none;
  cursor: pointer;
  font-size: 16px;
}

button:hover {
  background-color: #45a049;
}

/* CSS for the name, class, and roll number table */
.details-table {
  margin-top: 20px;
  width: 100%;
  border-collapse: collapse;
}

.details-table th,
.details-table td {
  padding: 8px;
  text-align: left;
  border-bottom: 1px solid #ddd;
}

.details-table th {
  background-color: #f2f2f2;
}

/* CSS for the main result table */
.main-table {
  margin-top: 20px;
  width: 100%;
  border-collapse: collapse;
}

.main-table th,
.main-table td {
  padding: 8px;
  text-align: left;
  border-bottom: 1px solid #ddd;
}

.main-table th {
  background-color: #f2f2f2;
}

.button a {
    display: inline-block;
    padding: 10px 20px;
    background-color: #007bff; /* Change this to the desired color */
    color: #fff;
    text-decoration: none;
    border-radius: 4px;
    transition: background-color 0.3s ease;
}

.button a.logout-btn {
    background-color: #dc3545; /* Change this to the desired color for the logout button */
}

.button a:hover {
    background-color: #0056b3; /* Change this to the desired color on hover */
}
</style>
</head>
<body>
    <?php
        include("init.php");

        if(!isset($_GET['class']))
            $class=null;
        else
            $class=$_GET['class'];
        $rn=$_GET['rn'];

        // validation
        if (empty($class) or empty($rn) or preg_match("/[a-z]/i",$rn)) {
            if(empty($class))
                echo '<p class="error">Please select your class</p>';
            if(empty($rn))
                echo '<p class="error">Please enter your roll number</p>';
            if(preg_match("/[a-z]/i",$rn))
                echo '<p class="error">Please enter valid roll number</p>';
            exit();
        }

        $name_sql=mysqli_query($conn,"SELECT `name` FROM `students` WHERE `rno`='$rn' and `class_name`='$class'");
        while($row = mysqli_fetch_assoc($name_sql))
        {
            $name = $row['name'];
        }

        $result_sql=mysqli_query($conn,"SELECT `p1`, `p2`, `p3`, `p4`, `p5`, `marks`, `percentage` FROM `result` WHERE `rno`='$rn' and `class`='$class'");
        while($row = mysqli_fetch_assoc($result_sql))
        {
            $p1 = $row['p1'];
            $p2 = $row['p2'];
            $p3 = $row['p3'];
            $p4 = $row['p4'];
            $p5 = $row['p5'];
            $mark = $row['marks'];
            $percentage = $row['percentage'];
        }
        if(mysqli_num_rows($result_sql)==0){
            echo "no result";
            exit();
        }
        $status = ($percentage >= 35) ? "Pass" : "Fail";
    ?>

    <div class="container">
        <table class="details-table">
            <thead>
                <tr>
                    <th>NAME</th>
                    <th>CLASS</th>
                    <th>ROLL NUMBER</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><?php echo $name; ?></td>
                    <td><?php echo $class; ?></td>
                    <td><?php echo $rn; ?></td>
                </tr>
            </tbody>
        </table>
        <div class="table">
            <table>
                <thead>
                    <tr>
                        <th>Subjects</th>
                        <th>Paper 1</th>
                        <th>Paper 2</th>
                        <th>Paper 3</th>
                        <th>Paper 4</th>
                        <th>Paper 5</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Marks</td>
                        <td><?php echo $p1; ?></td>
                        <td><?php echo $p2; ?></td>
                        <td><?php echo $p3; ?></td>
                        <td><?php echo $p4; ?></td>
                        <td><?php echo $p5; ?></td>
                    </tr>
                    <tr>
                        <td>Total Marks</td>
                        <td colspan="5"><?php echo $mark; ?></td>
                    </tr>
                    <tr>
                        <td>Percentage</td>
                        <td colspan="5"><?php echo $percentage; ?>%</td>
                    </tr>
                    <tr>
                        <td>Status</td>
                        <td colspan="5"><?php echo $status; ?></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="button">
            <button onclick="downloadResult()">Download Result</button>
        </div>
        <div class="button">
            <a href="login.php" class="logout-btn">Logout</a>
        </div>
    </div>
    <script>
        function downloadResult(){
                // Prepare the result data as a string (example data)
                var resultData = "Name: <?php echo $name ?>\nClass: <?php echo $class; ?>\nRoll No: <?php echo $rn; ?>\n";
                resultData += "Marks:\n";
                resultData += "Paper 1: <?php echo $p1 ?>\n";
                resultData += "Paper 2: <?php echo $p2 ?>\n";
                resultData += "Paper 3: <?php echo $p3 ?>\n";
                resultData += "Paper 4: <?php echo $p4 ?>\n";
                resultData += "Paper 5: <?php echo $p5 ?>\n";
                resultData += "Total Marks:<?php echo $mark; ?>\n";
                resultData += "Percentage: <?php echo $percentage; ?>\n";
                resultData += "Status: <?php echo $status; ?>";
                // Create a temporary element to hold the result data
                var tempElement = document.createElement('a');
                tempElement.href = 'data:text/plain;charset=utf-8,' + encodeURIComponent(resultData);
                tempElement.download = 'result.txt';

                // Append the temporary element to the document body
                document.body.appendChild(tempElement);

                // Simulate a click event to trigger the download
                tempElement.click();

                // Remove the temporary element from the document body
                 document.body.removeChild(tempElement);
                }
    </script>
</body>
</html>
