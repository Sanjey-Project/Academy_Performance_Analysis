<?php
session_start();
error_reporting(0);
include('includes/config.php');
require 'excelReader/excel_reader2.php';
require 'excelReader/SpreadsheetReader.php';

if(strlen($_SESSION['alogin']) == "") {
    header("Location: index.php");
} else {
    if(isset($_POST['submit'])) {
        $marks = $_POST['marks'];
        $class = $_POST['class'];
        $studentid = $_POST['studentid'];

        $stmt = $dbh->prepare("SELECT subjectdata.SubjectName, subjectdata.id FROM subjectcombinationdata JOIN subjectdata ON subjectdata.id = subjectcombinationdata.SubjectId WHERE subjectcombinationdata.ClassId = :cid ORDER BY subjectdata.SubjectName");
        $stmt->execute(array(':cid' => $class));
        $sid1 = array();

        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            array_push($sid1, $row['id']);
        }

        for($i = 0; $i < count($marks); $i++) {
            $mark = $marks[$i];
            $sid = $sid1[$i];
            
            $grades = convertGradeToMarks($mark); // Convert grade to marks

            $sql = "INSERT INTO resultdata (StudentId, ClassId, SubjectId, Grades, Marks) VALUES (:studentid, :class, :sid, :grade, :marks)";
            $query = $dbh->prepare($sql);
            $query->bindParam(':studentid', $studentid, PDO::PARAM_STR);
            $query->bindParam(':class', $class, PDO::PARAM_STR);
            $query->bindParam(':sid', $sid, PDO::PARAM_STR);
            $query->bindParam(':grade', $grades, PDO::PARAM_INT);
            $query->bindParam(':marks', $mark, PDO::PARAM_STR);
            $query->execute();

            $lastInsertId = $dbh->lastInsertId();
            if($lastInsertId) {
                $msg = "Result info added successfully";
            } else {
                $error = "Something went wrong. Please try again";
            }
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
            $headerRow = $reader->current();
            foreach(array_slice($headerRow,1) as $subjectcode){
            if(!empty($subjectcode))
            {
                $checkSubject = $dbh->prepare("SELECT id FROM subjectdata WHERE SubjectCode = :subjectcode");
                $checkSubject->bindParam(':subjectcode', $subjectcode, PDO::PARAM_STR);
                $checkSubject->execute();
                $subjectIdRow = $checkSubject->fetch(PDO::FETCH_ASSOC);
    
                if (!$subjectIdRow) {
                    // Subject code doesn't exist, insert it into subjectdata table
                    $insertSubject = $dbh->prepare("INSERT INTO subjectdata (SubjectCode) VALUES (:subjectcode)");
                    $insertSubject->bindParam(':subjectcode', $subjectcode, PDO::PARAM_STR);
                    $insertSubject->execute();
                }
            }
        }
        foreach($reader as $key =>$row)
        {
            $rollno = $row[0];
            $getstudentId = $dbh->prepare("SELECT StudentId FROM studentdata WHERE RollId = :rollid");
            $getstudentId->bindParam(':rollid', $rollno, PDO::PARAM_STR);
            $getstudentId->execute();
            $studentIdRow = $getstudentId->fetch(PDO::FETCH_ASSOC);
            $rollid = $studentIdRow['StudentId'];
            $getclassId = $dbh->prepare("SELECT ClassId FROM studentdata WHERE RollId = :rollid");
            $getclassId->bindParam(':rollid', $rollno, PDO::PARAM_STR);
            $getclassId->execute();
            $classIdRow = $getclassId->fetch(PDO::FETCH_ASSOC);
            $classid = $classIdRow['ClassId'];
    
            foreach(array_slice($row,1) as $index =>$grade)
            {
                if(!empty($grade))
                {
                    $mark = convertGradeToMarks($grade);
                    $subjectcode = $headerRow[$index + 1]; // Adjust index for subject codes starting from second column
                        $getsubjectid = $dbh->prepare("SELECT id FROM subjectdata WHERE SubjectCode = :subjectcode");
                        $getsubjectid->bindParam(':subjectcode', $subjectcode, PDO::PARAM_STR);
                        $getsubjectid->execute();
                        $subjectidrow = $getsubjectid->fetch(PDO::FETCH_ASSOC);
                        $subjectid = $subjectidrow['id'];
                        $insertResult = $dbh->prepare("INSERT INTO resultdata (StudentId, ClassId, SubjectId, marks,Grades) VALUES (:studentid, :classid, :subjectid,:grade,:marks)");
                        $insertResult->bindParam(':studentid', $rollid, PDO::PARAM_INT);
                        $insertResult->bindParam(':classid', $classid, PDO::PARAM_INT);
                        $insertResult->bindParam(':subjectid', $subjectid, PDO::PARAM_INT);
                        $insertResult->bindParam(':grade', $grade, PDO::PARAM_STR);
                        $insertResult->bindParam(':marks',$mark,PDO::PARAM_INT);
                        $insertResult->execute();
                }
                else
                {
                    break;
                }
            }
        }
            $msg = "Combination Imported successfully";
        } else {
            $error = "Invalid file format. Please upload an Excel file (.xls or .xlsx)";
        }
    }
    
}

function convertGradeToMarks($mark) {
    // Define your conversion rules here
    switch($mark) {
        case 'O':
            return 10;
        case 'A+':
            return 9;
        case 'A':
            return 8;
        case 'B+':
            return 7;
        case 'B':
            return 6;
        // Add more cases for other grades as needed
        default:
            return 0; // Default to 0 if grade not found
    }
}

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
    	<meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Add Result | Academic Portal</title>
        <!-- Google Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&family=Poppins:wght@500;600;700&display=swap" rel="stylesheet">
        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        <!-- Tailwind CSS -->
        <script src="https://cdn.tailwindcss.com"></script>
        <script src="js/tailwind-config.js"></script>
        <script src="js/jquery/jquery-2.2.4.min.js"></script>
        <script>
function getStudent(val) {
    $.ajax({
    type: "POST",
    url: "get_student.php",
    data:'classid='+val,
    success: function(data){
        $("#studentid").html(data);
        
    }
    });
$.ajax({
        type: "POST",
        url: "get_student.php",
        data:'classid1='+val,
        success: function(data){
            $("#subject").html(data);
            
        }
        });
}
    </script>
<script>

function getresult(val,clid) 
{   
    
var clid=$(".clid").val();
var val=$(".stid").val();;
var abh=clid+'$'+val;
//alert(abh);
    $.ajax({
        type: "POST",
        url: "get_student.php",
        data:'studclass='+abh,
        success: function(data){
            $("#reslt").html(data);
            
        }
        });
}
</script>


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
                                <h1 class="text-3xl font-bold font-heading text-white">Declare Result</h1>
                                <nav class="flex mt-2 text-sm text-gray-400">
                                    <a href="dashboard.php" class="hover:text-primary transition-colors">Home</a>
                                    <span class="mx-2">/</span>
                                    <span class="text-gray-200">Results</span>
                                    <span class="mx-2">/</span>
                                    <span class="text-gray-200">Add Result</span>
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

                        <!-- Add Result Form -->
                        <div class="bg-surface border border-white/10 rounded-2xl p-8 shadow-xl mb-8 animate-slide-up">
                            <h2 class="text-xl font-bold font-heading mb-6 flex items-center gap-2">
                                <i class="fa-solid fa-chart-line text-primary"></i> Result Details
                            </h2>
                            
                            <form method="post" class="space-y-6">
                                
                                <div>
                                    <label for="classid" class="block text-sm font-medium text-gray-400 mb-2">Class</label>
                                    <select name="class" class="w-full bg-dark/50 border border-gray-700 rounded-lg px-4 py-3 focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary text-white transition-all appearance-none clid" id="classid" onChange="getStudent(this.value);" required="required">
                                        <option value="">Select Class</option>
<?php $sql = "SELECT * from classdata";
$query = $dbh->prepare($sql);
$query->execute();
$results=$query->fetchAll(PDO::FETCH_OBJ);
if($query->rowCount() > 0)
{
foreach($results as $result)
{   ?>
<option value="<?php echo htmlentities($result->id); ?>"><?php echo htmlentities($result->ClassName); ?>&nbsp;<?php echo htmlentities($result->ClassNameNumeric); ?>&nbsp; Section-<?php echo htmlentities($result->Section); ?></option>
<?php }} ?>
                                    </select>
                                </div>

                                <div>
                                    <label for="studentid" class="block text-sm font-medium text-gray-400 mb-2">Student Name</label>
                                    <select name="studentid" class="w-full bg-dark/50 border border-gray-700 rounded-lg px-4 py-3 focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary text-white transition-all appearance-none stid" id="studentid" required="required" onChange="getresult(this.value);">
                                    </select>
                                </div>

                                <div id="reslt">
                                    <!-- Results fetched via AJAX will appear here -->
                                </div>

                                <div id="subject">
                                     <!-- Subjects fetched via AJAX will appear here -->
                                </div>

                                <div class="pt-4">
                                    <button type="submit" name="submit" id="submit" class="w-full sm:w-auto bg-gradient-to-r from-primary to-indigo-600 hover:from-primary/90 hover:to-indigo-500 text-white font-bold py-3 px-8 rounded-xl shadow-lg shadow-indigo-500/30 transform hover:scale-[1.02] transition-all duration-200 flex items-center justify-center gap-2">
                                        <i class="fa-solid fa-check"></i> Declare Result
                                    </button>
                                </div>
                            </form>
                        </div>

                         <!-- Import Excel Form -->
                        <div class="bg-surface border border-white/10 rounded-2xl p-8 shadow-xl animate-slide-up" style="animation-delay: 0.1s;">
                            <h2 class="text-xl font-bold font-heading mb-6 flex items-center gap-2">
                                <i class="fa-solid fa-file-excel text-green-500"></i> Import Result from Excel
                            </h2>
                            
                            <form method="post" enctype="multipart/form-data" class="space-y-6">
                                <div>
                                    <label for="excelFile" class="block text-sm font-medium text-gray-400 mb-2">Upload Excel File (.xls, .xlsx)</label>
                                    <div class="relative group">
                                         <input type="file" name="excelFile" id="excelFile" class="block w-full text-sm text-gray-400 file:mr-4 file:py-3 file:px-6 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-primary/20 file:text-primary hover:file:bg-primary/30 cursor-pointer bg-dark/50 border border-gray-700 rounded-lg" accept=".xls,.xlsx">
                                    </div>
                                    <p class="text-xs text-gray-500 mt-2">Column Format: RollId, Grade1, Grade2, ... </p>
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
