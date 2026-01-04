<?php
session_start();
include("includes/config.php");

if(isset($_POST['submit'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $getfacultyId = $dbh->prepare("SELECT id FROM facultydata WHERE FacultyName = :facultyname AND FacultyCode = :FacultyCode");
    $getfacultyId->bindParam(':facultyname', $username, PDO::PARAM_STR);
    $getfacultyId->bindParam(':FacultyCode', $password, PDO::PARAM_INT);
    $getfacultyId->execute();
    $facultyIdRow = $getfacultyId->fetch(PDO::FETCH_ASSOC);
    $facultyid = $facultyIdRow['id'];

    $checkSql = "SELECT * FROM facultydata WHERE FacultyName = :facultyname AND FacultyCode  = :FacultyCode ";
    $checkQuery = $dbh->prepare($checkSql);
    $checkQuery->bindParam(':facultyname', $username, PDO::PARAM_STR);
    $checkQuery->bindParam(':FacultyCode', $password, PDO::PARAM_INT);
    $checkQuery->execute();
    $row = $checkQuery->fetch(PDO::FETCH_ASSOC);
    if($row) {
        $facultyid = $row['id'];
        $_SESSION['id'] = $facultyid;
        header("Location: dashboardfaculty.php");
        exit;
    } else {
        echo "<p>Invalid username or password.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Faculty Login | Academic Portal</title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&family=Poppins:wght@500;600;700&display=swap" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="js/tailwind-config.js"></script>
</head>
<body class="bg-darker text-white font-sans min-h-screen flex items-center justify-center relative overflow-hidden">

    <!-- Background Elements -->
    <div class="absolute top-0 left-0 w-full h-full overflow-hidden -z-10">
        <div class="absolute top-[-20%] right-[-10%] w-[600px] h-[600px] bg-secondary/20 rounded-full blur-[120px]"></div>
        <div class="absolute bottom-[-20%] left-[-10%] w-[500px] h-[500px] bg-blue-600/10 rounded-full blur-[100px]"></div>
    </div>

    <!-- Login Container -->
    <div class="w-full max-w-md p-6 animate-fade-in relative z-10">
        
        <!-- Back Button -->
        <a href="index.php" class="inline-flex items-center text-gray-400 hover:text-white mb-8 transition-colors">
            <i class="fa-solid fa-arrow-left mr-2"></i> Back to Home
        </a>

        <div class="bg-surface/50 backdrop-blur-xl border border-white/10 rounded-2xl p-8 shadow-2xl">
            <div class="text-center mb-8">
                <div class="w-16 h-16 bg-secondary/20 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fa-solid fa-chalkboard-user text-3xl text-secondary"></i>
                </div>
                <h2 class="text-2xl font-bold font-heading">Faculty Login</h2>
                <p class="text-gray-400 text-sm mt-1">Faculty Access Portal</p>
            </div>

            <form action="" method="post" class="space-y-6">
                
                <!-- Display Error Message -->
                 <?php
                 // Keeping existing error logic but styling it
                 if(isset($_POST['submit']) && !$row) {
                     echo '<div class="bg-red-500/10 border border-red-500/50 text-red-200 px-4 py-3 rounded-lg text-sm flex items-center gap-3"><i class="fa-solid fa-circle-exclamation"></i><span>Invalid username or password.</span></div>';
                 }
                 ?>

                <!-- Username Input -->
                <div class="group">
                    <label class="block text-xs font-medium text-gray-400 mb-1 ml-1 uppercase tracking-wider">Username</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fa-solid fa-user text-gray-500 group-focus-within:text-secondary transition-colors"></i>
                        </div>
                        <input type="text" name="username" required 
                            class="block w-full pl-10 pr-3 py-3 bg-dark/50 border border-gray-600 rounded-lg focus:outline-none focus:border-secondary focus:ring-1 focus:ring-secondary text-white placeholder-gray-500 transition-all"
                            placeholder="Enter username">
                    </div>
                </div>

                <!-- Password Input -->
                <div class="group">
                    <label class="block text-xs font-medium text-gray-400 mb-1 ml-1 uppercase tracking-wider">Password</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fa-solid fa-lock text-gray-500 group-focus-within:text-secondary transition-colors"></i>
                        </div>
                        <input type="password" name="password" required 
                            class="block w-full pl-10 pr-3 py-3 bg-dark/50 border border-gray-600 rounded-lg focus:outline-none focus:border-secondary focus:ring-1 focus:ring-secondary text-white placeholder-gray-500 transition-all"
                            placeholder="Enter password">
                    </div>
                </div>

                <!-- Submit Button -->
                <button type="submit" name="submit" 
                    class="w-full bg-gradient-to-r from-secondary to-blue-500 text-white font-semibold py-3 px-4 rounded-lg hover:from-blue-400 hover:to-secondary focus:outline-none focus:ring-2 focus:ring-secondary focus:ring-offset-2 focus:ring-offset-dark transition-all transform hover:scale-[1.02] active:scale-[0.98] shadow-lg shadow-secondary/25">
                    Sign In
                </button>
 
            </form>
        </div>
        
        <p class="text-center text-gray-500 text-sm mt-8">
            &copy; 2024 Academic Performance Analysis
        </p>
    </div>

</body>
</html>
