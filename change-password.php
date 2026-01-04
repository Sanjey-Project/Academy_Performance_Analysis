<?php
session_start();
error_reporting(0);
include('includes/config.php');
if(strlen($_SESSION['alogin'])=="")
    {   
    header("Location: login.php"); 
    }
    else{
        if(isset($_POST['submit']))
        {
            $password=md5($_POST['password']);
            $newpassword=md5($_POST['newpassword']);
            $username=$_SESSION['alogin'];
            $sql ="SELECT Password FROM admindata WHERE UserName=:username and Password=:password";
            $query= $dbh -> prepare($sql);
            $query-> bindParam(':username', $username, PDO::PARAM_STR);
            $query-> bindParam(':password', $password, PDO::PARAM_STR);
            $query-> execute();
            $results = $query -> fetchAll(PDO::FETCH_OBJ);
            if($query -> rowCount() > 0)
            {
                $con="update admindata set Password=:newpassword where UserName=:username";
                $chngpwd1 = $dbh->prepare($con);
                $chngpwd1-> bindParam(':username', $username, PDO::PARAM_STR);
                $chngpwd1-> bindParam(':newpassword', $newpassword, PDO::PARAM_STR);
                $chngpwd1->execute();
                $msg="Your Password succesfully changed";
            }
            else {
                $error="Your current password is wrong";    
            }
        }
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
    	<meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Admin Change Password | Academic Portal</title>
        <!-- Google Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&family=Poppins:wght@500;600;700&display=swap" rel="stylesheet">
        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        <!-- Tailwind CSS -->
        <script src="https://cdn.tailwindcss.com"></script>
        <script src="js/tailwind-config.js"></script>
        <script type="text/javascript">
            function valid() {
                if(document.chngpwd.newpassword.value!= document.chngpwd.confirmpassword.value) {
                    alert("New Password and Confirm Password Field do not match  !!");
                    document.chngpwd.confirmpassword.focus();
                    return false;
                }
                return true;
            }
        </script>
    </head>
    <body class="bg-darker text-white font-sans antialiased overflow-x-hidden">
        
        <div class="min-h-screen flex flex-col">
            
            <?php include('includes/topbar.php');?> 
            
            <div class="flex flex-1 pt-16">
                
                <?php include('includes/leftbar.php');?>

                <main class="flex-1 lg:ml-64 p-6 transition-all duration-300">
                    
                    <div class="max-w-xl mx-auto">
                        
                         <!-- Breadcrumb & Title -->
                        <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4 animate-fade-in">
                            <div>
                                <h1 class="text-3xl font-bold font-heading text-white">Change Password</h1>
                                <nav class="flex mt-2 text-sm text-gray-400">
                                    <a href="dashboard.php" class="hover:text-primary transition-colors">Home</a>
                                    <span class="mx-2">/</span>
                                    <span class="text-gray-200">Admin</span>
                                </nav>
                            </div>
                        </div>

                         <!-- Form Section -->
                        <div class="bg-surface border border-white/10 rounded-2xl p-8 shadow-2xl animate-slide-up">
                            
                            <?php if($msg){?>
                                <div class="bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 px-4 py-3 rounded-xl mb-6 flex items-center gap-3" role="alert">
                                    <i class="fa-solid fa-circle-check"></i>
                                    <span><strong>Well done!</strong> <?php echo htmlentities($msg); ?></span>
                                </div>
                            <?php } else if($error){?>
                                <div class="bg-red-500/10 border border-red-500/20 text-red-400 px-4 py-3 rounded-xl mb-6 flex items-center gap-3" role="alert">
                                    <i class="fa-solid fa-circle-exclamation"></i>
                                    <span><strong>Oh snap!</strong> <?php echo htmlentities($error); ?></span>
                                </div>
                            <?php } ?>

                            <h2 class="text-xl font-bold font-heading mb-6 border-b border-white/10 pb-4">Update Password</h2>

                            <form name="chngpwd" method="post" onSubmit="return valid();" class="space-y-6">
                                
                                <div class="space-y-2">
                                    <label for="password" class="text-sm font-medium text-gray-300 ml-1">Current Password</label>
                                    <input type="password" 
                                           name="password" 
                                           class="w-full bg-dark/50 border border-white/10 rounded-xl py-3 px-4 text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-primary/50 focus:border-primary/50 transition-all shadow-inner" 
                                           required 
                                           id="password"
                                           placeholder="Enter current password">
                                </div>

                                <div class="space-y-2">
                                    <label for="newpassword" class="text-sm font-medium text-gray-300 ml-1">New Password</label>
                                    <input type="password" 
                                           name="newpassword" 
                                           class="w-full bg-dark/50 border border-white/10 rounded-xl py-3 px-4 text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-primary/50 focus:border-primary/50 transition-all shadow-inner" 
                                           required 
                                           id="newpassword"
                                           placeholder="Enter new password">
                                </div>

                                <div class="space-y-2">
                                    <label for="confirmpassword" class="text-sm font-medium text-gray-300 ml-1">Confirm New Password</label>
                                    <input type="password" 
                                           name="confirmpassword" 
                                           class="w-full bg-dark/50 border border-white/10 rounded-xl py-3 px-4 text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-primary/50 focus:border-primary/50 transition-all shadow-inner" 
                                           required 
                                           id="confirmpassword"
                                           placeholder="Confirm new password">
                                </div>

                                <div class="pt-4">
                                    <button type="submit" name="submit" class="w-full bg-gradient-to-r from-primary to-indigo-600 hover:from-primary-dark hover:to-indigo-700 text-white font-bold py-3 rounded-xl shadow-lg shadow-indigo-500/20 transform transition-all hover:scale-[1.02] active:scale-[0.98] flex items-center justify-center gap-2">
                                        <i class="fa-solid fa-key"></i>
                                        <span>Change Password</span>
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
        <script src="js/main.js"></script>
    </body>
</html>
<?php  } ?>
