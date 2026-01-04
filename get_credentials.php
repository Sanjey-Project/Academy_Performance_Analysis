<?php
include('includes/config.php');

echo "<h1>Login Credentials</h1>";

// 1. Admin
echo "<h2>Admin Accounts (admindata)</h2>";
$sql = "SELECT Username, Password FROM admindata LIMIT 5";
$query = $dbh->prepare($sql);
$query->execute();
$results = $query->fetchAll(PDO::FETCH_ASSOC);
if($results) {
    echo "<ul>";
    foreach($results as $row) {
        echo "<li>Username: " . htmlentities($row['Username']) . " (Password is hashed)</li>";
    }
    echo "</ul>";
} else {
    echo "No admin accounts found.<br>";
}

// 2. Student
echo "<h2>Student Accounts (studentdata)</h2>";
echo "<p>Login using <strong>Username = Student Name</strong> and <strong>Password = Roll Id</strong></p>";
$sql = "SELECT StudentName, RollId FROM studentdata LIMIT 5";
$query = $dbh->prepare($sql);
$query->execute();
$results = $query->fetchAll(PDO::FETCH_ASSOC);
if($results) {
    echo "<ul>";
    foreach($results as $row) {
        echo "<li>Username: " . htmlentities($row['StudentName']) . " | Password: " . htmlentities($row['RollId']) . "</li>";
    }
    echo "</ul>";
} else {
    echo "No student accounts found.<br>";
}

// 3. Faculty
echo "<h2>Faculty Accounts (facultydata)</h2>";
echo "<p>Login using <strong>Username = Faculty Name</strong> and <strong>Password = Faculty Code</strong></p>";
$sql = "SELECT FacultyName, FacultyCode FROM facultydata LIMIT 5";
$query = $dbh->prepare($sql);
$query->execute();
$results = $query->fetchAll(PDO::FETCH_ASSOC);
if($results) {
    echo "<ul>";
    foreach($results as $row) {
        echo "<li>Username: " . htmlentities($row['FacultyName']) . " | Password: " . htmlentities($row['FacultyCode']) . "</li>";
    }
    echo "</ul>";
} else {
    echo "No faculty accounts found.<br>";
}

// 4. Department Head
echo "<h2>Department Head Accounts (departmentdata)</h2>";
echo "<p>Login using <strong>Username</strong> and <strong>Password</strong></p>";
$sql = "SELECT Username, Password FROM departmentdata LIMIT 5";
$query = $dbh->prepare($sql);
$query->execute();
$results = $query->fetchAll(PDO::FETCH_ASSOC);
if($results) {
    echo "<ul>";
    foreach($results as $row) {
        echo "<li>Username: " . htmlentities($row['Username']) . " | Password: " . htmlentities($row['Password']) . " (might be plaintext or simple)</li>";
    }
    echo "</ul>";
} else {
    echo "No department head accounts found.<br>";
}
?>
