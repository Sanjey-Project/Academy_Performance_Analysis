<?php
session_start();
error_reporting(0);
include('includes/config.php');
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
    	<meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Check Result | Academic Portal</title>
        <!-- Google Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&family=Poppins:wght@500;600;700&display=swap" rel="stylesheet">
        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
         <!-- Tailwind CSS -->
        <script src="https://cdn.tailwindcss.com"></script>
        <script src="js/tailwind-config.js"></script>
    </head>
    <body class="bg-darker font-sans antialiased min-h-screen flex items-center justify-center relative overflow-hidden text-gray-200">
        
        <!-- Animated Background -->
        <div class="absolute inset-0 z-0">
             <div class="absolute top-0 right-0 -mr-20 -mt-20 w-96 h-96 bg-primary/20 rounded-full blur-3xl animate-pulse"></div>
            <div class="absolute bottom-0 left-0 -ml-20 -mb-20 w-96 h-96 bg-emerald-500/10 rounded-full blur-3xl animate-pulse" style="animation-delay: 1s;"></div>
        </div>

        <div class="relative z-10 w-full max-w-md px-4">
             <div class="text-center mb-8 animate-fade-in text-white">
                <i class="fa-solid fa-graduation-cap text-5xl text-primary mb-4 drop-shadow-[0_0_15px_rgba(99,102,241,0.5)]"></i>
                <h1 class="text-3xl font-bold font-heading">Check Result</h1>
                <p class="text-gray-400 mt-2">Enter your details to view your academic performance</p>
            </div>

            <div class="bg-surface/50 backdrop-blur-xl border border-white/10 rounded-2xl shadow-2xl overflow-hidden animate-slide-up">
                 <div class="p-8">
                    <form action="result.php" method="post" class="space-y-6">
                        
                        <div class="space-y-2">
                            <label for="rollid" class="text-sm font-medium text-gray-300 ml-1">Roll Number</label>
                            <div class="relative">
                                <span class="absolute left-4 top-3.5 text-gray-500">
                                    <i class="fa-solid fa-id-card"></i>
                                </span>
                                <input type="text" 
                                       id="rollid"
                                       name="rollid" 
                                       class="w-full bg-dark/50 border border-white/10 rounded-xl py-3 pl-11 pr-4 text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-primary/50 focus:border-primary/50 transition-all shadow-inner" 
                                       placeholder="Enter your student code"
                                       required
                                       autocomplete="off">
                            </div>
                        </div>

                        <div class="space-y-2">
                            <label for="class" class="text-sm font-medium text-gray-300 ml-1">Class</label>
                            <div class="relative">
                                <span class="absolute left-4 top-3.5 text-gray-500">
                                    <i class="fa-solid fa-layer-group"></i>
                                </span>
                                <select name="class" 
                                        id="class" 
                                        class="w-full bg-dark/50 border border-white/10 rounded-xl py-3 pl-11 pr-10 text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-primary/50 focus:border-primary/50 transition-all shadow-inner appearance-none" 
                                        required>
                                    <option value="">Select your class</option>
                                    <?php 
                                    $sql = "SELECT * from classdata";
                                    $query = $dbh->prepare($sql);
                                    $query->execute();
                                    $results=$query->fetchAll(PDO::FETCH_OBJ);
                                    if($query->rowCount() > 0) {
                                        foreach($results as $result) { ?>
                                            <option class="bg-dark text-white" value="<?php echo htmlentities($result->id); ?>">
                                                <?php echo htmlentities($result->ClassName); ?> (<?php echo htmlentities($result->Section); ?>)
                                            </option>
                                    <?php }} ?>
                                </select>
                                <span class="absolute right-4 top-3.5 text-gray-500 pointer-events-none">
                                    <i class="fa-solid fa-chevron-down text-xs"></i>
                                </span>
                            </div>
                        </div>

                        <button type="submit" class="w-full bg-gradient-to-r from-primary to-indigo-600 hover:from-primary-dark hover:to-indigo-700 text-white font-bold py-3.5 rounded-xl shadow-lg shadow-indigo-500/20 transform transition-all hover:scale-[1.02] active:scale-[0.98] flex items-center justify-center gap-2">
                            <span>Search Result</span>
                            <i class="fa-solid fa-arrow-right"></i>
                        </button>

                    </form>
                 </div>
                 
                 <div class="px-8 py-4 bg-black/20 border-t border-white/5 text-center">
                    <a href="index.php" class="text-sm text-gray-400 hover:text-white transition-colors flex items-center justify-center gap-2">
                        <i class="fa-solid fa-house"></i> Back to Homepage
                    </a>
                </div>
            </div>

            <!-- Footer -->
            <p class="text-center text-gray-500 text-sm mt-8 animate-fade-in" style="animation-delay: 0.2s;">
                &copy; <?php echo date('Y');?> Academic Performance Analysis Portal
            </p>

        </div>

    </body>
</html>
