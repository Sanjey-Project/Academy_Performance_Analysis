<?php
session_start();
error_reporting(0);
include('includes/config.php');
if(strlen($_SESSION['alogin'])=="")
    {   
    header("Location: login.php"); 
    }
    else{

$stid=intval($_GET['stid']);
if(isset($_POST['submit']))
{

$rowid=$_POST['id'];
$marks=$_POST['marks']; 

foreach($_POST['id'] as $count => $id){
$mrks=$marks[$count];
$iid=$rowid[$count];
for($i=0;$i<=$count;$i++) {

$sql="update resultdata  set marks=:mrks where id=:iid ";
$query = $dbh->prepare($sql);
$query->bindParam(':mrks',$mrks,PDO::PARAM_STR);
$query->bindParam(':iid',$iid,PDO::PARAM_STR);
$query->execute();

$msg="Result updated successfully";
}
}
}

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
    	<meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Student Result Info | Academic Portal</title>
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
                                <h1 class="text-3xl font-bold font-heading text-white">Student Result Info</h1>
                                <nav class="flex mt-2 text-sm text-gray-400">
                                    <a href="dashboard.php" class="hover:text-primary transition-colors">Home</a>
                                    <span class="mx-2">/</span>
                                    <span class="text-gray-200">Results</span>
                                    <span class="mx-2">/</span>
                                    <span class="text-gray-200">Edit Result</span>
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

                        <!-- Content Card -->
                        <div class="bg-surface border border-white/10 rounded-2xl p-8 shadow-xl animate-slide-up">
                            
                            <!-- Student Info Section -->
                            <div class="mb-8 p-6 bg-white/5 rounded-xl border border-white/10">
                                <h2 class="text-lg font-bold font-heading mb-4 text-primary flex items-center gap-2">
                                    <i class="fa-solid fa-user-graduate"></i> Student Details
                                </h2>
<?php 
$ret = "SELECT studentdata.StudentName,classdata.ClassName,classdata.Section from resultdata join studentdata on resultdata.StudentId=resultdata.StudentId join subjectdata on subjectdata.id=resultdata.SubjectId join classdata on classdata.id=studentdata.ClassId where studentdata.StudentId=:stid limit 1";
$stmt = $dbh->prepare($ret);
$stmt->bindParam(':stid',$stid,PDO::PARAM_STR);
$stmt->execute();
$result=$stmt->fetchAll(PDO::FETCH_OBJ);
$cnt=1;
if($stmt->rowCount() > 0)
{
foreach($result as $row)
{  ?>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <p class="text-gray-400 text-sm">Full Name</p>
                                        <p class="text-xl font-semibold text-white"><?php echo htmlentities($row->StudentName);?></p>
                                    </div>
                                    <div>
                                        <p class="text-gray-400 text-sm">Class</p>
                                        <p class="text-xl font-semibold text-white"><?php echo htmlentities($row->ClassName);?> (Section: <?php echo htmlentities($row->Section);?>)</p>
                                    </div>
                                </div>
<?php } }?>
                            </div>

                            <h2 class="text-lg font-bold font-heading mb-6 border-b border-white/10 pb-2">Edit Marks</h2>

                            <form method="post" class="space-y-4">
<?php 
$sql = "SELECT distinct studentdata.StudentName,studentdata.StudentId,classdata.ClassName,classdata.Section,subjectdata.SubjectName,resultdata.marks,resultdata.id as resultid from resultdata join studentdata on studentdata.StudentId=resultdata.StudentId join subjectdata on subjectdata.id=resultdata.SubjectId join classdata on classdata.id=studentdata.ClassId where studentdata.StudentId=:stid ";
$query = $dbh->prepare($sql);
$query->bindParam(':stid',$stid,PDO::PARAM_STR);
$query->execute();
$results=$query->fetchAll(PDO::FETCH_OBJ);
$cnt=1;
if($query->rowCount() > 0)
{
foreach($results as $result)
{  ?>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-center p-3 hover:bg-white/5 rounded-lg transition-colors border border-transparent hover:border-white/5">
                                    <label class="md:col-span-1 block text-sm font-medium text-gray-300">
                                        <?php echo htmlentities($result->SubjectName)?>
                                    </label>
                                    <div class="md:col-span-2">
                                        <input type="hidden" name="id[]" value="<?php echo htmlentities($result->resultid)?>">
                                        <input type="text" name="marks[]" value="<?php echo htmlentities($result->marks)?>" maxlength="5" required="required" autocomplete="off" class="w-full bg-dark/50 border border-gray-700 rounded-lg px-4 py-2 focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary text-white transition-all text-right font-mono" placeholder="Enter marks">
                                    </div>
                                </div>
<?php }} ?>                                                    
                                
                                <div class="pt-6 flex gap-4">
                                    <button type="submit" name="submit" class="w-full sm:w-auto bg-gradient-to-r from-primary to-indigo-600 hover:from-primary/90 hover:to-indigo-500 text-white font-bold py-3 px-8 rounded-xl shadow-lg shadow-indigo-500/30 transform hover:scale-[1.02] transition-all duration-200 flex items-center justify-center gap-2">
                                        <i class="fa-solid fa-floppy-disk"></i> Update Results
                                    </button>
                                     <a href="manage-results.php" class="w-full sm:w-auto bg-gray-700 hover:bg-gray-600 text-white font-bold py-3 px-8 rounded-xl shadow-lg transform hover:scale-[1.02] transition-all duration-200 flex items-center justify-center gap-2 text-center">
                                        <i class="fa-solid fa-arrow-left"></i> Cancel
                                    </a>
                                </div>
                            </form>
                        </div>

                    </div>
                </main>
            </div>
        </div>
        
    </body>
</html>
<?PHP } ?>
