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
        <title>Result | Academic Portal</title>
        <!-- Google Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&family=Poppins:wght@500;600;700&display=swap" rel="stylesheet">
        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        <!-- Tailwind CSS -->
        <script src="https://cdn.tailwindcss.com"></script>
        <script src="js/tailwind-config.js"></script>
    </head>
    <body class="bg-darker text-white font-sans antialiased min-h-screen relative overflow-x-hidden">
        
        <!-- Navbar -->
        <div class="bg-surface/50 backdrop-blur-md border-b border-white/10 sticky top-0 z-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between h-16">
                    <div class="flex items-center gap-3">
                         <div class="bg-primary/20 p-2 rounded-lg text-primary">
                            <i class="fa-solid fa-graduation-cap text-xl"></i>
                         </div>
                         <h1 class="text-xl font-bold font-heading text-white hidden sm:block">Academic Portal</h1>
                    </div>
                    <a href="index.php" class="text-sm font-medium text-gray-300 hover:text-white transition-colors">Back to Home</a>
                </div>
            </div>
        </div>

        <div class="container mx-auto px-4 py-12">
            
            <div class="max-w-4xl mx-auto">
                
                <?php
                if(isset($_POST['rollid']) && isset($_POST['class'])) {
                $rollid = $_POST['rollid'];
                $classid = $_POST['class'];
                $_SESSION['rollid'] = $rollid;
                $_SESSION['classid'] = $classid;

                // Query Student Data with new schema
                $qery = "SELECT sd.StudentName, sd.RollId, sd.StudentId, cd.ClassName, cd.Section 
                         FROM studentdata sd 
                         JOIN classdata cd ON cd.id = sd.ClassId 
                         WHERE sd.RollId = :rollid AND sd.ClassId = :classid";
                $stmt = $dbh->prepare($qery);
                $stmt->bindParam(':rollid', $rollid, PDO::PARAM_STR);
                $stmt->bindParam(':classid', $classid, PDO::PARAM_STR);
                $stmt->execute();
                $resultss = $stmt->fetch(PDO::FETCH_ASSOC);

                if($stmt->rowCount() > 0) {
                    $studentId = $resultss['StudentId'];
                    $studentName = $resultss['StudentName'];
                    $rollId = $resultss['RollId'];
                    $className = $resultss['ClassName'];
                    $section = $resultss['Section'];
                ?>
                
                    <!-- Student Info Card -->
                    <div class="bg-surface border border-white/10 rounded-2xl p-8 shadow-2xl mb-8 animate-fade-in relative overflow-hidden">
                        <!-- Decorations -->
                        <div class="absolute top-0 right-0 -mr-20 -mt-20 w-64 h-64 bg-primary/10 rounded-full blur-3xl"></div>
                        
                        <h2 class="text-2xl font-bold font-heading text-white mb-6 flex items-center gap-3">
                            <i class="fa-solid fa-user-graduate text-primary"></i> Result Declaration
                        </h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="bg-dark/30 rounded-xl p-4 border border-white/5">
                                <p class="text-sm text-gray-400 mb-1">Student Name</p>
                                <p class="text-lg font-medium text-white"><?php echo htmlentities($studentName);?></p>
                            </div>
                            <div class="bg-dark/30 rounded-xl p-4 border border-white/5">
                                <p class="text-sm text-gray-400 mb-1">Roll ID</p>
                                <p class="text-lg font-medium text-white"><?php echo htmlentities($rollId);?></p>
                            </div>
                            <div class="bg-dark/30 rounded-xl p-4 border border-white/5 md:col-span-2">
                                <p class="text-sm text-gray-400 mb-1">Class</p>
                                <p class="text-lg font-medium text-white"><?php echo htmlentities($className);?> (Section: <?php echo htmlentities($section);?>)</p>
                            </div>
                        </div>
                    </div>

                    <!-- Marks Table -->
                    <div class="bg-surface border border-white/10 rounded-2xl p-8 shadow-2xl animate-slide-up relative z-10">
                        <h3 class="text-xl font-bold font-heading text-white mb-6 border-b border-white/10 pb-2">Marks Details</h3>
                        
                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr class="border-b border-white/10">
                                        <th class="py-4 px-4 font-semibold text-sm uppercase text-gray-400">#</th>
                                        <th class="py-4 px-4 font-semibold text-sm uppercase text-gray-400">Subject</th>
                                        <th class="py-4 px-4 font-semibold text-sm uppercase text-gray-400 text-right">Marks</th>
                                    </tr>
                                </thead>
                                <tbody class="text-sm">
                                    <?php 
                                    // Query Result Data with new schema
                                    $query = "SELECT s.SubjectName, rd.marks 
                                              FROM resultdata rd 
                                              JOIN subjectdata s ON s.id = rd.SubjectId 
                                              WHERE rd.StudentId = :studentid";
                                    $stmt = $dbh->prepare($query);
                                    $stmt->bindParam(':studentid', $studentId, PDO::PARAM_INT);
                                    $stmt->execute();
                                    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                    
                                    $cnt = 1;
                                    $hasResults = false;
                                    if($stmt->rowCount() > 0) {
                                        $hasResults = true;
                                        foreach($results as $row) {
                                            $marks = $row['marks'];
                                    ?>
                                    <tr class="border-b border-white/5 last:border-0 hover:bg-white/5 transition-colors">
                                        <td class="py-4 px-4 text-gray-500"><?php echo htmlentities($cnt);?></td>
                                        <td class="py-4 px-4 font-medium text-white"><?php echo htmlentities($row['SubjectName']);?></td>
                                        <td class="py-4 px-4 font-bold text-right text-emerald-400"><?php echo htmlentities($marks);?></td>
                                    </tr>
                                    <?php 
                                            $cnt++;
                                        } 
                                    } else {
                                        echo '<tr><td colspan="3" class="py-6 text-center text-gray-400">No results found relative to this roll number.</td></tr>';
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>

                        <?php if($hasResults) { ?>
                        <div class="mt-8 pt-6 border-t border-white/10 flex justify-center">
                            <!-- Note: The existing download script might need Year/Month. 
                                 Since this public view shows ALL results, we might not be able to direct link to a PDF 
                                 unless we pick a specific semester or the download script is updated to handle 'All'. 
                                 For now, suppressing link or linking to a static 'Please contact admin' if dynamic generation isn't ready for 'All'.
                                 actually, let's link to index or print. -->
                            
                             <button onclick="window.print()" class="inline-flex items-center gap-2 px-6 py-3 bg-primary hover:bg-primary-dark text-white font-bold rounded-xl shadow-lg shadow-indigo-500/20 transform transition-all hover:scale-105 active:scale-95">
                                <i class="fa-solid fa-print"></i> Print Result
                             </button>
                        </div>
                        <?php } ?>

                    </div>

                <?php 
                } else { ?>
                    <!-- Invalid Roll ID Alert -->
                     <div class="max-w-md mx-auto mt-20">
                        <div class="bg-red-500/10 border border-red-500/20 rounded-2xl p-6 text-center animate-shake">
                            <i class="fa-solid fa-circle-exclamation text-4xl text-red-500 mb-4"></i>
                            <h2 class="text-xl font-bold text-white mb-2">Invalid Details</h2>
                            <p class="text-gray-400 mb-6">No student found with the provided Roll Number and Class.</p>
                            <a href="find-result.php" class="inline-flex items-center gap-2 px-6 py-2 bg-white/10 hover:bg-white/20 text-white rounded-lg transition-colors">
                                <i class="fa-solid fa-arrow-left"></i> Try Again
                            </a>
                        </div>
                    </div>
                <?php } 
                } else {
                    // Redirect if accessing directly
                    echo "<script>window.location.href = 'find-result.php';</script>";
                }
                ?>
            </div>
        </div>

    </body>
</html>
