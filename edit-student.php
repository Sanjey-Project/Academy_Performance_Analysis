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
$studentname=$_POST['fullanme'];
$roolid=$_POST['rollid']; 
$studentemail=$_POST['emailid']; 
$gender=$_POST['gender']; 
$classid=$_POST['class']; 
$dob=$_POST['dob']; 
$batch=$_POST['batch'];

$sql="update studentdata set StudentName=:studentname,RollId=:roolid,StudentEmail=:studentemail,Gender=:gender,DOB=:dob,passedoutyear=:batch where StudentId=:stid ";
$query = $dbh->prepare($sql);
$query->bindParam(':studentname',$studentname,PDO::PARAM_STR);
$query->bindParam(':roolid',$roolid,PDO::PARAM_STR);
$query->bindParam(':studentemail',$studentemail,PDO::PARAM_STR);
$query->bindParam(':gender',$gender,PDO::PARAM_STR);
$query->bindParam(':dob',$dob,PDO::PARAM_STR);
$query->bindParam(':stid',$stid,PDO::PARAM_STR);
$query->bindParam(':batch',$batch,PDO::PARAM_INT);
$query->execute();
header("Location: manage-students.php"); // Redirect back to manage page
$msg="Student info updated successfully";
}


?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
    	<meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Edit Student | Academic Portal</title>
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
                                <h1 class="text-3xl font-bold font-heading text-white">Edit Student</h1>
                                <nav class="flex mt-2 text-sm text-gray-400">
                                    <a href="dashboard.php" class="hover:text-primary transition-colors">Home</a>
                                    <span class="mx-2">/</span>
                                    <span class="text-gray-200">Students</span>
                                    <span class="mx-2">/</span>
                                    <span class="text-gray-200">Edit Student</span>
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

                        <!-- Edit Form -->
                        <div class="bg-surface border border-white/10 rounded-2xl p-8 shadow-xl animate-slide-up">
                            <h2 class="text-xl font-bold font-heading mb-6 flex items-center gap-2">
                                <i class="fa-solid fa-user-pen text-primary"></i> Update Student Info
                            </h2>
                            
                            <form method="post" class="space-y-6">
<?php 
$sql = "SELECT studentdata.StudentName,studentdata.RollId,studentdata.RegDate,studentdata.StudentId,studentdata.StudentEmail,studentdata.Gender,studentdata.DOB,studentdata.passedoutyear,classdata.ClassNameNumeric,classdata.ClassName,classdata.Section from studentdata join classdata on classdata.id=studentdata.ClassId where studentdata.StudentId=:stid";
$query = $dbh->prepare($sql);
$query->bindParam(':stid',$stid,PDO::PARAM_STR);
$query->execute();
$results=$query->fetchAll(PDO::FETCH_OBJ);
$cnt=1;
if($query->rowCount() > 0)
{
foreach($results as $result)
{  ?>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label for="fullanme" class="block text-sm font-medium text-gray-400 mb-2">Full Name</label>
                                        <input type="text" name="fullanme" id="fullanme" value="<?php echo htmlentities($result->StudentName)?>" required="required" autocomplete="off" class="w-full bg-dark/50 border border-gray-700 rounded-lg px-4 py-3 focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary text-white transition-all">
                                    </div>
                                    
                                    <div>
                                        <label for="rollid" class="block text-sm font-medium text-gray-400 mb-2">Roll ID</label>
                                        <input type="text" name="rollid" id="rollid" value="<?php echo htmlentities($result->RollId)?>" required="required" autocomplete="off" class="w-full bg-dark/50 border border-gray-700 rounded-lg px-4 py-3 focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary text-white transition-all">
                                    </div>

                                    <div>
                                        <label for="email" class="block text-sm font-medium text-gray-400 mb-2">Email ID</label>
                                        <input type="email" name="emailid" id="email" value="<?php echo htmlentities($result->StudentEmail)?>" required="required" autocomplete="off" class="w-full bg-dark/50 border border-gray-700 rounded-lg px-4 py-3 focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary text-white transition-all">
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-400 mb-2">Gender</label>
                                        <div class="flex items-center gap-6 mt-3">
                                            <?php $gndr=$result->Gender; ?>
                                            <label class="flex items-center gap-2 cursor-pointer group">
                                                <div class="relative flex items-center">
                                                    <input type="radio" name="gender" value="Male" class="peer hidden" required="required" <?php if($gndr=="MALE" || $gndr=="Male") echo "checked"; ?>>
                                                    <div class="w-5 h-5 border-2 border-gray-600 rounded-full peer-checked:border-primary peer-checked:border-4 transition-all"></div>
                                                </div>
                                                <span class="text-gray-300 group-hover:text-white transition-colors">Male</span>
                                            </label>
                                            <label class="flex items-center gap-2 cursor-pointer group">
                                                <div class="relative flex items-center">
                                                    <input type="radio" name="gender" value="Female" class="peer hidden" required="required" <?php if($gndr=="FEMALE" || $gndr=="Female") echo "checked"; ?>>
                                                    <div class="w-5 h-5 border-2 border-gray-600 rounded-full peer-checked:border-primary peer-checked:border-4 transition-all"></div>
                                                </div>
                                                <span class="text-gray-300 group-hover:text-white transition-colors">Female</span>
                                            </label>
                                            <label class="flex items-center gap-2 cursor-pointer group">
                                                <div class="relative flex items-center">
                                                    <input type="radio" name="gender" value="Other" class="peer hidden" required="required" <?php if($gndr!="MALE" && $gndr!="Male" && $gndr!="FEMALE" && $gndr!="Female") echo "checked"; ?>>
                                                    <div class="w-5 h-5 border-2 border-gray-600 rounded-full peer-checked:border-primary peer-checked:border-4 transition-all"></div>
                                                </div>
                                                <span class="text-gray-300 group-hover:text-white transition-colors">Other</span>
                                            </label>
                                        </div>
                                    </div>

                                    <div>
                                        <label for="classname" class="block text-sm font-medium text-gray-400 mb-2">Class</label>
                                        <input type="text" name="classname" id="classname" value="<?php echo htmlentities($result->ClassName)?>(<?php echo htmlentities($result->ClassNameNumeric)?>-<?php echo htmlentities($result->Section)?>)" readonly class="w-full bg-dark/30 border border-gray-700/50 rounded-lg px-4 py-3 text-gray-400 cursor-not-allowed">
                                    </div>

                                    <div>
                                        <label for="date" class="block text-sm font-medium text-gray-400 mb-2">Date of Birth</label>
                                        <input type="date" name="dob" id="date" value="<?php echo htmlentities($result->DOB)?>" class="w-full bg-dark/50 border border-gray-700 rounded-lg px-4 py-3 focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary text-white transition-all">
                                    </div>

                                     <div>
                                        <label for="batch" class="block text-sm font-medium text-gray-400 mb-2">Batch</label>
                                        <input type="number" name="batch" id="batch" value="<?php echo htmlentities($result->passedoutyear)?>" required="required" autocomplete="off" class="w-full bg-dark/50 border border-gray-700 rounded-lg px-4 py-3 focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary text-white transition-all">
                                    </div>

                                     <div>
                                        <label class="block text-sm font-medium text-gray-400 mb-2">Registration Date</label>
                                        <div class="w-full bg-dark/30 border border-gray-700/50 rounded-lg px-4 py-3 text-gray-400">
                                            <?php echo htmlentities($result->RegDate)?>
                                        </div>
                                    </div>
                                </div>
<?php }} ?>
                                <div class="pt-4 flex gap-4">
                                    <button type="submit" name="submit" class="w-full sm:w-auto bg-gradient-to-r from-primary to-indigo-600 hover:from-primary/90 hover:to-indigo-500 text-white font-bold py-3 px-8 rounded-xl shadow-lg shadow-indigo-500/30 transform hover:scale-[1.02] transition-all duration-200 flex items-center justify-center gap-2">
                                        <i class="fa-solid fa-floppy-disk"></i> Update Info
                                    </button>
                                     <a href="manage-students.php" class="w-full sm:w-auto bg-gray-700 hover:bg-gray-600 text-white font-bold py-3 px-8 rounded-xl shadow-lg transform hover:scale-[1.02] transition-all duration-200 flex items-center justify-center gap-2 text-center">
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
