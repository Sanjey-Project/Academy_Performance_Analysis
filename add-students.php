<?php
session_start();
error_reporting(0);
require 'excelReader/excel_reader2.php';
require 'excelReader/SpreadsheetReader.php';
include('includes/config.php');
if(strlen($_SESSION['alogin'])=="")
    {   
    header("Location: login.php"); 
    }
    else{
if(isset($_POST['submit']))
{
$studentname=$_POST['fullanme'];
$roolid=$_POST['rollid']; 
$studentemail=$_POST['emailid']; 
$gender=$_POST['gender']; 
$classid=$_POST['class']; 
$dob=$_POST['dob'];
$batch =$_POST['batch']; 
$sql="INSERT INTO  studentdata(StudentName,RollId,StudentEmail,Gender,ClassId,DOB,passedoutyear) VALUES(:studentname,:roolid,:studentemail,:gender,:classid,:dob,:batch)";
$query = $dbh->prepare($sql);
$query->bindParam(':studentname',$studentname,PDO::PARAM_STR);
$query->bindParam(':roolid',$roolid,PDO::PARAM_STR);
$query->bindParam(':studentemail',$studentemail,PDO::PARAM_STR);
$query->bindParam(':gender',$gender,PDO::PARAM_STR);
$query->bindParam(':classid',$classid,PDO::PARAM_STR);
$query->bindParam(':dob',$dob,PDO::PARAM_STR);
$query->bindParam(':batch',$batch,PDO::PARAM_INT);
$query->execute();
$count=$query->rowCount();
if($count>0)
{
$msg="Student info added successfully";
}
else 
{
$error="Something went wrong. Please try again";
}

}
else if (isset($_POST['importExcel'])) {
    // Handle Excel file import
    $filename = $_FILES['excelFile']['name'];
    $fileExtension = pathinfo($filename, PATHINFO_EXTENSION);
    
    // Check file extension (only allow .xls and .xlsx)
    if (in_array($fileExtension, ['xls', 'xlsx'])) {
        $targetDirectory = "includes/" . $filename;
        move_uploaded_file($_FILES['excelFile']['tmp_name'], $targetDirectory);

        $reader = new SpreadsheetReader($targetDirectory);
        foreach ($reader as $key => $row) {
            $studentname = $row[0];
            $rollid=$row[1];
            $studentemail=$row[2];
            $gender=$row[3];
            $dob=$row[4];
            $dept=$row[5];
            $year=$row[6];
            $section=$row[7];
            $batch = $row[8];
            $getClassId = $dbh->prepare("SELECT id FROM classdata WHERE ClassName = :classname AND ClassNameNumeric = :year AND Section = :section");
            $getClassId->bindParam(':classname', $dept, PDO::PARAM_STR);
            $getClassId->bindParam(':year', $year, PDO::PARAM_INT);
            $getClassId->bindParam(':section', $section, PDO::PARAM_STR);
            $getClassId->execute();
            $classIdRow = $getClassId->fetch(PDO::FETCH_ASSOC);
            $classid = $classIdRow['id'];

            $checkSql = "SELECT * FROM studentdata WHERE StudentName = :studentname AND RollId = :rollid";
            $checkQuery = $dbh->prepare($checkSql);
            $checkQuery->bindParam(':studentname', $studentname, PDO::PARAM_STR);
            $checkQuery->bindParam(':rollid', $rollid, PDO::PARAM_INT);
            $checkQuery->execute();
    
            $rowCount = $checkQuery->rowCount();
    
            if($rowCount > 0) {
                // Record exists, update it
                $updateSql = "UPDATE studentdata SET StudentName = :studentname, RollId = :rollid WHERE studentEmail= :studentEmail";
                $updateQuery = $dbh->prepare($updateSql);
                $updateQuery->bindParam(':classname', $classname, PDO::PARAM_STR);
                $updateQuery->bindParam(':classnamenumeric', $classnamenumeric, PDO::PARAM_INT);
                $updateQuery->bindParam(':section', $section, PDO::PARAM_STR);
                $updateQuery->execute();
            } else {
        $sql = "INSERT INTO studentdata (StudentName,RollId,StudentEmail,Gender,DOB,ClassId,passedoutyear) VALUES (:studentname,:rollid,:studentemail,:gender,:dob,:classid,:batch)";
        $query = $dbh->prepare($sql);
        $query->bindParam(':studentname',$studentname,PDO::PARAM_STR);
        $query->bindParam(':rollid',$rollid,PDO::PARAM_INT);
        $query->bindParam(':classid',$classid,PDO::PARAM_INT);
        $query->bindParam(':gender',$gender,PDO::PARAM_STR);
        $query->bindParam(':studentemail',$studentemail,PDO::PARAM_STR);
        $query->bindParam(':dob',$dob,PDO::PARAM_STR);
        $query->bindParam(':batch',$batch,PDO::PARAM_INT);
        // Execute the query and handle errors
        $query->execute();
        $count = $query->rowCount();
        if ($count > 0) {
            $msg = "Student Imported successfully";
        } else {
            $error = "Error importing class. Please check your data.";
            // Additional error handling/logging if needed
        }
    }
}
} else
{
    $error = "Invalid file format. Please upload an Excel file (.xls or .xlsx)";
}
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
    	<meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Student Admission | Academic Portal</title>
        <!-- Google Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&family=Poppins:wght@500;600;700&display=swap" rel="stylesheet">
        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        <!-- Tailwind CSS -->
        <script src="https://cdn.tailwindcss.com"></script>
        <script src="js/tailwind-config.js"></script>
    </head>
    <body class="bg-darker text-white font-sans antialiased overflow-x-hidden">
        
        <div class="min-h-screen flex flex-col">
            
            <?php include('includes/topbar.php');?>   
            
            <div class="flex flex-1 pt-16">
                
                <?php include('includes/leftbar.php');?>

                <main class="flex-1 lg:ml-64 p-6 transition-all duration-300">
                    
                    <div class="max-w-4xl mx-auto">
                        
                         <!-- Breadcrumb & Title -->
                        <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4 animate-fade-in">
                            <div>
                                <h1 class="text-3xl font-bold font-heading text-white">Student Admission</h1>
                                <nav class="flex mt-2 text-sm text-gray-400">
                                    <a href="dashboard.php" class="hover:text-primary transition-colors">Home</a>
                                    <span class="mx-2">/</span>
                                    <span class="text-gray-200">Student Admission</span>
                                </nav>
                            </div>
                        </div>

                         <!-- Alerts -->
                        <?php if($msg){?>
                            <div class="bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 px-4 py-3 rounded-lg mb-6 flex items-center animate-slide-up" role="alert">
                                <i class="fa-solid fa-circle-check mr-2"></i>
                                <strong>Well done!</strong> <span class="ml-2"><?php echo htmlentities($msg); ?></span>
                            </div>
                        <?php } else if($error){?>
                            <div class="bg-red-500/10 border border-red-500/20 text-red-400 px-4 py-3 rounded-lg mb-6 flex items-center animate-slide-up" role="alert">
                                <i class="fa-solid fa-circle-exclamation mr-2"></i>
                                <strong>Oh snap!</strong> <span class="ml-2"><?php echo htmlentities($error); ?></span>
                            </div>
                        <?php } ?>

                        <!-- Add Student Form -->
                        <div class="bg-surface border border-white/10 rounded-2xl p-8 shadow-xl mb-8 animate-slide-up">
                            <h2 class="text-xl font-bold font-heading mb-6 flex items-center gap-2">
                                <i class="fa-solid fa-user-plus text-primary"></i> Student Details
                            </h2>
                            
                            <form method="post" class="space-y-6">
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div class="col-span-1">
                                        <label for="fullanme" class="block text-sm font-medium text-gray-400 mb-2">Full Name</label>
                                        <input type="text" name="fullanme" id="fullanme" required="required" autocomplete="off" placeholder="Enter Full Name" class="w-full bg-dark/50 border border-gray-700 rounded-lg px-4 py-3 focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary text-white transition-all">
                                    </div>

                                    <div class="col-span-1">
                                        <label for="rollid" class="block text-sm font-medium text-gray-400 mb-2">Register No</label>
                                        <input type="text" name="rollid" id="rollid" required="required" autocomplete="off" placeholder="Enter Register No" class="w-full bg-dark/50 border border-gray-700 rounded-lg px-4 py-3 focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary text-white transition-all">
                                    </div>

                                    <div class="col-span-1">
                                        <label for="email" class="block text-sm font-medium text-gray-400 mb-2">Email ID</label>
                                        <input type="email" name="emailid" id="email" required="required" autocomplete="off" placeholder="Enter Email" class="w-full bg-dark/50 border border-gray-700 rounded-lg px-4 py-3 focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary text-white transition-all">
                                    </div>
                                    
                                     <div class="col-span-1">
                                        <label for="gender" class="block text-sm font-medium text-gray-400 mb-2">Gender</label>
                                        <div class="flex gap-4 mt-3">
                                            <label class="flex items-center space-x-2 cursor-pointer">
                                                <input type="radio" name="gender" value="Male" required="required" checked="" class="form-radio text-primary focus:ring-primary bg-dark border-gray-700">
                                                <span class="text-white">Male</span>
                                            </label>
                                            <label class="flex items-center space-x-2 cursor-pointer">
                                                <input type="radio" name="gender" value="Female" required="required" class="form-radio text-pink-500 focus:ring-pink-500 bg-dark border-gray-700">
                                                <span class="text-white">Female</span>
                                            </label>
                                            <label class="flex items-center space-x-2 cursor-pointer">
                                                <input type="radio" name="gender" value="Other" required="required" class="form-radio text-purple-500 focus:ring-purple-500 bg-dark border-gray-700">
                                                <span class="text-white">Other</span>
                                            </label>
                                        </div>
                                    </div>

                                     <div class="col-span-1">
                                        <label for="dob" class="block text-sm font-medium text-gray-400 mb-2">Date of Birth</label>
                                        <input type="date" name="dob" id="dob" class="w-full bg-dark/50 border border-gray-700 rounded-lg px-4 py-3 focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary text-white transition-all [color-scheme:dark]">
                                    </div>
                                    
                                    <div class="col-span-1">
                                        <label for="batch" class="block text-sm font-medium text-gray-400 mb-2">Batch / Year</label>
                                        <input type="number" name="batch" id="batch" placeholder="Eg: 2024" class="w-full bg-dark/50 border border-gray-700 rounded-lg px-4 py-3 focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary text-white transition-all">
                                    </div>

                                    <div class="col-span-1 md:col-span-2">
                                        <label for="class" class="block text-sm font-medium text-gray-400 mb-2">Class</label>
                                        <select name="class" id="class" required="required" class="w-full bg-dark/50 border border-gray-700 rounded-lg px-4 py-3 focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary text-white transition-all appearance-none">
                                            <option value="">Select Class</option>
<?php $sql = "SELECT * from classdata";
$query = $dbh->prepare($sql);
$query->execute();
$results=$query->fetchAll(PDO::FETCH_OBJ);
if($query->rowCount() > 0)
{
foreach($results as $result)
{   ?>
<option value="<?php echo htmlentities($result->id); ?>"><?php echo htmlentities($result->ClassName); ?>&nbsp; <?php echo htmlentities($result->ClassNameNumeric); ?>&nbsp;Section-<?php echo htmlentities($result->Section); ?></option>
<?php }} ?>
                                        </select>
                                    </div>

                                </div>

                                <div class="pt-4">
                                    <button type="submit" name="submit" class="w-full sm:w-auto bg-gradient-to-r from-primary to-indigo-600 hover:from-primary/90 hover:to-indigo-500 text-white font-bold py-3 px-8 rounded-xl shadow-lg shadow-indigo-500/30 transform hover:scale-[1.02] transition-all duration-200 flex items-center justify-center gap-2">
                                        <i class="fa-solid fa-check"></i> Add Student
                                    </button>
                                </div>
                            </form>
                        </div>

                         <!-- Import Excel Form -->
                        <div class="bg-surface border border-white/10 rounded-2xl p-8 shadow-xl animate-slide-up" style="animation-delay: 0.1s;">
                            <h2 class="text-xl font-bold font-heading mb-6 flex items-center gap-2">
                                <i class="fa-solid fa-file-excel text-green-500"></i> Import from Excel
                            </h2>
                            
                            <form method="post" enctype="multipart/form-data" class="space-y-6">
                                <div>
                                    <label for="excelFile" class="block text-sm font-medium text-gray-400 mb-2">Upload Excel File (.xls, .xlsx)</label>
                                    <div class="relative group">
                                         <input type="file" name="excelFile" id="excelFile" class="block w-full text-sm text-gray-400 file:mr-4 file:py-3 file:px-6 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-primary/20 file:text-primary hover:file:bg-primary/30 cursor-pointer bg-dark/50 border border-gray-700 rounded-lg" accept=".xls,.xlsx">
                                    </div>
                                    <p class="text-xs text-gray-500 mt-2">Column Format: Name, RollId, Email, Gender, DOB, Dept, Year, Section, Batch</p>
                                </div>

                                <div class="pt-2">
                                    <button type="submit" name="importExcel" class="w-full sm:w-auto bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-500/90 hover:to-emerald-500 text-white font-bold py-3 px-8 rounded-xl shadow-lg shadow-green-500/30 transform hover:scale-[1.02] transition-all duration-200 flex items-center justify-center gap-2">
                                        <i class="fa-solid fa-upload"></i> Import Data
                                    </button>
                                </div>
                            </form>
                        </div>

                    </div>
                </main>
            </div>
        </div>
        
        <!-- Scripts -->
        <script src="js/jquery/jquery-2.2.4.min.js"></script>
        
    </body>
</html>
<?php } ?>
