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
$subjectname=$_POST['subjectname'];
$subjectcode=$_POST['subjectcode']; 
$credits=$_POST['credits'];
$semester=$_POST['semester'];
$sql="INSERT INTO  subjectdata(SubjectName,SubjectCode,credit,semester) VALUES(:subjectname,:subjectcode,:credits,:semester)";
$query = $dbh->prepare($sql);
$query->bindParam(':subjectname',$subjectname,PDO::PARAM_STR);
$query->bindParam(':subjectcode',$subjectcode,PDO::PARAM_STR);
$query->bindParam(':credits',$credits,PDO::PARAM_INT);
$query->bindParam(':semester',$semester,PDO::PARAM_INT);
$query->execute();
$count = $query->rowCount();
if($count>0)
{
$msg="Subject Created successfully";
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
            $subjectname = $row[0];
            $subjectcode = $row[1];
            $credits = $row[2];
            $semester = $row[3];
            $checkSql = "SELECT * FROM subjectdata WHERE SubjectName = :subjectname AND SubjectCode = :subjectcode AND credit = :credit AND semester = :semester";
            $checkQuery = $dbh->prepare($checkSql);
            $checkQuery->bindParam(':subjectname', $subjectname, PDO::PARAM_STR);
            $checkQuery->bindParam(':subjectcode', $subjectcode, PDO::PARAM_STR);
            $checkQuery->bindParam(':credit',$credits,PDO::PARAM_INT);
            $checkQuery->bindParam(':semester',$semester,PDO::PARAM_INT);
            $checkQuery->execute();
    
            $rowCount = $checkQuery->rowCount();
    
            if($rowCount > 0) {
                // Record exists, update it
                $updateSql = "UPDATE subjectdata SET SubjectName = :subjectname, SubjectCode = :subjectcode,credit =:credit,semester =:semester WHERE SubjectCode = :subjectcode";
                $updateQuery = $dbh->prepare($updateSql);
                $updateQuery->bindParam(':subjectname', $subjectname, PDO::PARAM_STR);
                $updateQuery->bindParam(':subjectcode', $subjectcode, PDO::PARAM_STR);
                $updateQuery_>bindParam(':credit',$credit,PDO::PARAM_INT);
                $updateQuery->bindParam(':semester',$semester,PDO::PARAM_INT);
                $updateQuery->execute();
            } else {
        $sql = "INSERT INTO subjectdata (SubjectName, SubjectCode,credit,semester) VALUES (:subjectname, :subjectcode,:credit,:semester)";
        $query = $dbh->prepare($sql);
        $query->bindParam(':subjectname', $subjectname, PDO::PARAM_STR);
        $query->bindParam(':subjectcode', $subjectcode, PDO::PARAM_STR);
        $query->bindParam(':credit', $credits, PDO::PARAM_INT);
        $query->bindParam(':semester',$semester,PDO::PARAM_INT);

        // Execute the query and handle errors
        $query->execute();
        $count = $query->rowCount();
        if ($count > 0) {
            $msg = "Subject Imported successfully";
        } else {
            $error = "Error importing subject. Please check your data.";
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
        <title>Create Subject | Academic Portal</title>
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
                                <h1 class="text-3xl font-bold font-heading text-white">Create Subject</h1>
                                <nav class="flex mt-2 text-sm text-gray-400">
                                    <a href="dashboard.php" class="hover:text-primary transition-colors">Home</a>
                                    <span class="mx-2">/</span>
                                    <span class="text-gray-400">Subjects</span>
                                    <span class="mx-2">/</span>
                                    <span class="text-gray-200">Create Subject</span>
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

                         <!-- Loading Indicator -->
                        <div id="loading" class="hidden text-center py-4">
                            <i class="fa-solid fa-circle-notch fa-spin text-primary text-3xl"></i>
                            <p class="mt-2 text-gray-400 text-sm">Processing...</p>
                        </div>

                        <!-- Creating Subject Form -->
                        <div class="bg-surface border border-white/10 rounded-2xl p-8 shadow-xl mb-8 animate-slide-up">
                            <h2 class="text-xl font-bold font-heading mb-6 flex items-center gap-2">
                                <i class="fa-solid fa-book-open text-primary"></i> Subject Details
                            </h2>
                            
                            <form method="post" class="space-y-6">
                                <div>
                                    <label for="subjectname" class="block text-sm font-medium text-gray-400 mb-2">Subject Name</label>
                                    <input type="text" name="subjectname" required="required" placeholder="Enter Subject Name" class="w-full bg-dark/50 border border-gray-700 rounded-lg px-4 py-3 focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary text-white transition-all">
                                </div>
                                
                                <div>
                                    <label for="subjectcode" class="block text-sm font-medium text-gray-400 mb-2">Subject Code</label>
                                    <input type="text" name="subjectcode" required="required" placeholder="Enter Subject Code" class="w-full bg-dark/50 border border-gray-700 rounded-lg px-4 py-3 focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary text-white transition-all">
                                </div>

                                <div>
                                    <label for="credits" class="block text-sm font-medium text-gray-400 mb-2">Credit</label>
                                    <input type="number" name="credits" required="required" placeholder="Enter Credits" class="w-full bg-dark/50 border border-gray-700 rounded-lg px-4 py-3 focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary text-white transition-all">
                                </div>

                                <div>
                                    <label for="semester" class="block text-sm font-medium text-gray-400 mb-2">Semester</label>
                                    <input type="number" name="semester" required="required" placeholder="Enter Semester" class="w-full bg-dark/50 border border-gray-700 rounded-lg px-4 py-3 focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary text-white transition-all">
                                </div>

                                <div class="pt-4">
                                    <button type="submit" name="submit" class="w-full sm:w-auto bg-gradient-to-r from-primary to-indigo-600 hover:from-primary/90 hover:to-indigo-500 text-white font-bold py-3 px-8 rounded-xl shadow-lg shadow-indigo-500/30 transform hover:scale-[1.02] transition-all duration-200 flex items-center justify-center gap-2">
                                        <i class="fa-solid fa-check"></i> Create Subject
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
                                    <p class="text-xs text-gray-500 mt-2">Column Format: Subject Name, Code, Credits, Semester</p>
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
<?PHP } ?>
