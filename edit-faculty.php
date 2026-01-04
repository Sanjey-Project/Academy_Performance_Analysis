<?php
session_start();
error_reporting(0);
include('includes/config.php');
if(strlen($_SESSION['alogin'])=="")
    {   
    header("Location: login.php"); 
    }
    else{
if(isset($_POST['Update']))
{
$sid=intval($_GET['facultyid']);
$facultyname=$_POST['facultyname'];
$facultycode=$_POST['facultycode'];
$qualification=$_POST['qualification'];
$contact=$_POST['contact'];
$sql="update  facultydata set FacultyName=:facultyname,FacultyCode=:facultycode,Qualification=:qualification,contact =:contact where id=:sid";
$query = $dbh->prepare($sql);
$query->bindParam(':facultyname',$facultyname,PDO::PARAM_STR);
$query->bindParam(':facultycode',$facultycode,PDO::PARAM_STR);
$query->bindParam(':qualification',$qualification,PDO::PARAM_STR);
$query->bindParam(':contact',$contact,PDO::PARAM_INT);
$query->bindParam(':sid',$sid,PDO::PARAM_STR);
$query->execute();
$msg="Faculty Info updated successfully";
header("Location: manage-faculty.php");
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
    	<meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Update Faculty | Academic Portal</title>
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
                                <h1 class="text-3xl font-bold font-heading text-white">Update Faculty</h1>
                                <nav class="flex mt-2 text-sm text-gray-400">
                                    <a href="dashboard.php" class="hover:text-primary transition-colors">Home</a>
                                    <span class="mx-2">/</span>
                                    <span class="text-gray-200">Faculty</span>
                                    <span class="mx-2">/</span>
                                    <span class="text-gray-200">Update Faculty</span>
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
                                <i class="fa-solid fa-user-pen text-primary"></i> Update Faculty Info
                            </h2>
                            
                            <form method="post" class="space-y-6">
 <?php
$sid=intval($_GET['facultyid']);
$sql = "SELECT * from facultydata where id=:sid";
$query = $dbh->prepare($sql);
$query->bindParam(':sid',$sid,PDO::PARAM_STR);
$query->execute();
$results=$query->fetchAll(PDO::FETCH_OBJ);
$cnt=1;
if($query->rowCount() > 0)
{
foreach($results as $result)
{   ?>                                               
                                <div class="space-y-6">
                                    
                                     <div>
                                        <label for="facultyname" class="block text-sm font-medium text-gray-400 mb-2">Faculty Name</label>
                                        <input type="text" name="facultyname" value="<?php echo htmlentities($result->FacultyName);?>" id="facultyname" required="required" class="w-full bg-dark/50 border border-gray-700 rounded-lg px-4 py-3 focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary text-white transition-all">
                                    </div>
                                    
                                    <div>
                                        <label for="facultycode" class="block text-sm font-medium text-gray-400 mb-2">Faculty Code</label>
                                        <input type="text" name="facultycode" value="<?php echo htmlentities($result->FacultyCode);?>" id="facultycode" required="required" class="w-full bg-dark/50 border border-gray-700 rounded-lg px-4 py-3 focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary text-white transition-all">
                                    </div>

                                    <div>
                                        <label for="qualification" class="block text-sm font-medium text-gray-400 mb-2">Qualification</label>
                                        <input type="text" name="qualification" value="<?php echo htmlentities($result->Qualification);?>" id="qualification" required="required" class="w-full bg-dark/50 border border-gray-700 rounded-lg px-4 py-3 focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary text-white transition-all">
                                    </div>
                                    
                                    <div>
                                        <label for="contact" class="block text-sm font-medium text-gray-400 mb-2">Contact</label>
                                        <input type="number" name="contact" value="<?php echo htmlentities($result->contact);?>" id="contact" required="required" class="w-full bg-dark/50 border border-gray-700 rounded-lg px-4 py-3 focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary text-white transition-all">
                                    </div>
                                </div>
<?php }} ?>
                                <div class="pt-4 flex gap-4">
                                    <button type="submit" name="Update" class="w-full sm:w-auto bg-gradient-to-r from-primary to-indigo-600 hover:from-primary/90 hover:to-indigo-500 text-white font-bold py-3 px-8 rounded-xl shadow-lg shadow-indigo-500/30 transform hover:scale-[1.02] transition-all duration-200 flex items-center justify-center gap-2">
                                        <i class="fa-solid fa-floppy-disk"></i> Update Faculty
                                    </button>
                                     <a href="manage-faculty.php" class="w-full sm:w-auto bg-gray-700 hover:bg-gray-600 text-white font-bold py-3 px-8 rounded-xl shadow-lg transform hover:scale-[1.02] transition-all duration-200 flex items-center justify-center gap-2 text-center">
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
