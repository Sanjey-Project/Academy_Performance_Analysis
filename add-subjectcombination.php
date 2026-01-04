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
        if (isset($_POST['submit'])) {
            $class = $_POST['class'];
            $status = 1;
        
            // Check if the form was submitted with semester option
            if (!empty($_POST['semester'])) {
                $semester = $_POST['semester'];
        
                // Fetch all subjects for the selected semester
                $sql_subjects = "SELECT id FROM subjectdata WHERE semester = :semester";
                $query_subjects = $dbh->prepare($sql_subjects);
                $query_subjects->bindParam(':semester', $semester, PDO::PARAM_STR);
                $query_subjects->execute();
                $subjects_rows = $query_subjects->fetchAll(PDO::FETCH_ASSOC);
        
                if ($subjects_rows) {
                    foreach ($subjects_rows as $subject_row) {
                        $subject = $subject_row['id'];
                        $sql = "INSERT INTO subjectcombinationdata (ClassId, SubjectId, status) VALUES (:class, :subject, :status)";
                        $query = $dbh->prepare($sql);
                        $query->bindParam(':class', $class, PDO::PARAM_STR);
                        $query->bindParam(':subject', $subject, PDO::PARAM_STR);
                        $query->bindParam(':status', $status, PDO::PARAM_STR);
                        $query->execute();
                        $lastInsertId = $dbh->lastInsertId();
                        if ($lastInsertId) {
                            $msg = "Combinations added successfully";
                        } else {
                            $error = "Something went wrong while adding combinations. Please try again";
                            break; // Exit the loop if there's an error
                        }
                    }
                    if (!isset($error)) {
                        header("Location: add-subjectcombination.php");
                    }
                } else {
                    $error = "No subjects found for the selected semester";
                }
            } elseif (!empty($_POST['subject'])) {
                $specific_subject = $_POST['subject'];
        
                // Insert combination for the specific subject
                $sql = "INSERT INTO subjectcombinationdata (ClassId, SubjectId, status) VALUES (:class, :specific_subject, :status)";
                $query = $dbh->prepare($sql);
                $query->bindParam(':class', $class, PDO::PARAM_STR);
                $query->bindParam(':specific_subject', $specific_subject, PDO::PARAM_STR);
                $query->bindParam(':status', $status, PDO::PARAM_STR);
                $query->execute();
                $lastInsertId = $dbh->lastInsertId();
                if ($lastInsertId) {
                    $msg = "Combination added successfully";
                    header("Location: add-subjectcombination.php");
                } else {
                    $error = "Something went wrong. Please try again";
                }
            } else {
                $error = "Please select either a semester or a specific subject";
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
            $classname = $row[0];
            $yearname = $row[1];
            $section = $row[2];
            $subjectname = $row[3];
            $getClassId = $dbh->prepare("SELECT id FROM classdata WHERE ClassName = :classname AND ClassNameNumeric =:yearname AND Section =:section");
            $getClassId->bindParam(':classname', $classname, PDO::PARAM_STR);
            $getClassId->bindParam(':yearname',$yearname,PDO::PARAM_INT);
            $getClassId->bindParam('section',$section,PDO::PARAM_STR);
            $getClassId->execute();
            $classIdRow = $getClassId->fetch(PDO::FETCH_ASSOC);
            $classid = $classIdRow['id'];

            $getSubjectId = $dbh->prepare("SELECT id FROM subjectdata WHERE SubjectName = :subjectname");
            $getSubjectId->bindParam(':subjectname',$subjectname,PDO::PARAM_STR);
            $getSubjectId->execute();
            $subjectIdRow = $getSubjectId->fetch(PDO::FETCH_ASSOC);
            $subjectid = $subjectIdRow['id'];

            // Insert into subjectcombinationdata
            $insertCombination = $dbh->prepare("INSERT INTO subjectcombinationdata (ClassId, SubjectId) VALUES (:classid, :subjectid)");
            $insertCombination->bindParam(':classid', $classid, PDO::PARAM_INT);
            $insertCombination->bindParam(':subjectid', $subjectid, PDO::PARAM_INT);
            $insertCombination->execute();
        }

        $msg = "Combination Imported successfully";
    } else {
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
        <title>Add Subject Combination | Academic Portal</title>
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
                                <h1 class="text-3xl font-bold font-heading text-white">Add Subject Combination</h1>
                                <nav class="flex mt-2 text-sm text-gray-400">
                                    <a href="dashboard.php" class="hover:text-primary transition-colors">Home</a>
                                    <span class="mx-2">/</span>
                                    <span class="text-gray-200">Subjects</span>
                                    <span class="mx-2">/</span>
                                    <span class="text-gray-200">Add Combination</span>
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

                        <!-- Create Combination Form -->
                        <div class="bg-surface border border-white/10 rounded-2xl p-8 shadow-xl mb-8 animate-slide-up">
                            <h2 class="text-xl font-bold font-heading mb-6 flex items-center gap-2">
                                <i class="fa-solid fa-layer-group text-primary"></i> Combination Details
                            </h2>
                            
                            <form method="post" class="space-y-6">
                                <div>
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

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div class="col-span-1">
                                         <label for="semester" class="block text-sm font-medium text-gray-400 mb-2">Semester (Optional - Adds all)</label>
                                        <select name="semester" id="semester" class="w-full bg-dark/50 border border-gray-700 rounded-lg px-4 py-3 focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary text-white transition-all appearance-none">
                                            <option value="">Select Semester</option>
                                            <?php
                                            $sql = "SELECT DISTINCT semester FROM subjectdata ORDER BY semester ASC";
                                            $query = $dbh->prepare($sql);
                                            $query->execute();
                                            $results = $query->fetchAll(PDO::FETCH_COLUMN);
                                            foreach ($results as $semester) {
                                                ?>
                                                <option value="<?php echo htmlentities($semester); ?>"><?php echo htmlentities($semester); ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="col-span-1">
                                        <label for="subject" class="block text-sm font-medium text-gray-400 mb-2">Specific Subject (Optional)</label>
                                         <select name="subject" id="subject" class="w-full bg-dark/50 border border-gray-700 rounded-lg px-4 py-3 focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary text-white transition-all appearance-none">
                                            <option value="">Select Subject</option>
<?php $sql = "SELECT * from subjectdata";
$query = $dbh->prepare($sql);
$query->execute();
$results=$query->fetchAll(PDO::FETCH_OBJ);
if($query->rowCount() > 0)
{
foreach($results as $result)
{   ?>
<option value="<?php echo htmlentities($result->id); ?>"><?php echo htmlentities($result->SubjectName); ?></option>
<?php }} ?>
                                        </select>
                                    </div>
                                </div>
                                <p class="text-xs text-gray-500 italic">* Select either a Semester to add all subjects or a Specific Subject to add one.</p>

                                <div class="pt-4">
                                    <button type="submit" name="submit" class="w-full sm:w-auto bg-gradient-to-r from-primary to-indigo-600 hover:from-primary/90 hover:to-indigo-500 text-white font-bold py-3 px-8 rounded-xl shadow-lg shadow-indigo-500/30 transform hover:scale-[1.02] transition-all duration-200 flex items-center justify-center gap-2">
                                        <i class="fa-solid fa-plus"></i> Add Combination
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
                                    <p class="text-xs text-gray-500 mt-2">Column Format: ClassName, Year, Section, SubjectName</p>
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
        
    </body>
</html>
<?php } ?>
