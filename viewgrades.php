<?php
session_start();
error_reporting(0);
include('includes/config.php');

if(isset($_GET['studentid']) && is_numeric($_GET['studentid']) &&
   isset($_GET['year']) && is_numeric($_GET['year']) &&
   isset($_GET['month']) && is_numeric($_GET['month'])) {
    $studentid = intval($_GET['studentid']);
    $year = intval($_GET['year']);
    $month = intval($_GET['month']);
} else {
    // If accessing directly without params, redirect or show error (or handle gracefull)
    // For now, let's just die since this is a detail view
     echo "<script>alert('Invalid parameters.'); window.location.href='semester.php';</script>";
     exit;
}

try {
    // Fetch student data
    $sql = "SELECT StudentName, RollId, ClassId FROM studentdata WHERE StudentId = :studentid";
    $query = $dbh->prepare($sql);
    $query->bindParam(':studentid', $studentid, PDO::PARAM_INT);
    $query->execute();
    $studentData = $query->fetch(PDO::FETCH_ASSOC);
    if ($studentData) {
        $studentname = htmlspecialchars($studentData['StudentName']);
        $rollid = htmlspecialchars($studentData['RollId']);
        $classid = htmlspecialchars($studentData['ClassId']);
    } else {
         echo "<script>alert('Student Data Not Found'); window.location.href='semester.php';</script>";
        exit;
    }

    // Fetch Class Name
     $sqlClass = "SELECT ClassName, Section FROM classdata WHERE id = :classid";
    $queryClass = $dbh->prepare($sqlClass);
    $queryClass->bindParam(':classid', $classid, PDO::PARAM_INT);
    $queryClass->execute();
    $classData = $queryClass->fetch(PDO::FETCH_ASSOC);
    $className = $classData['ClassName'];
    $section = $classData['Section'];


    $sql = "SELECT r.marks, r.UpdationDate,r.Grades, s.SubjectName,s.SubjectCode,s.credit 
            FROM resultdata r 
            JOIN subjectdata s ON r.subjectid = s.id 
            WHERE r.studentid = :studentid AND YEAR(r.updationdate) = :year AND MONTH(r.updationdate) = :month";
    $query = $dbh->prepare($sql);
    $query->bindParam(':studentid', $studentid, PDO::PARAM_INT);
    $query->bindParam(':year', $year, PDO::PARAM_INT);
    $query->bindParam(':month', $month, PDO::PARAM_INT);
    $query->execute();
    $results = $query->fetchAll(PDO::FETCH_OBJ);

    $totalCredits = 0;
    $gradeCreditsSum = 0;
    if ($results) {
        foreach ($results as $result) {
            $gradeCreditsSum += ($result->Grades * $result->credit);
            $totalCredits += $result->credit;
        }
    }
    $gpa = ($totalCredits > 0) ? ($gradeCreditsSum / $totalCredits) : 0;
    $gpa_formatted = number_format($gpa, 2);

} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
    	<meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Result Details | Academic Portal</title>
        <!-- Google Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&family=Poppins:wght@500;600;700&display=swap" rel="stylesheet">
        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        <!-- Tailwind CSS -->
        <script src="https://cdn.tailwindcss.com"></script>
        <script src="js/tailwind-config.js"></script>
         <!-- DataTables CSS for Tailwind -->
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css"/>
        <style>
             /* Custom Scrollbar for dark theme */
            ::-webkit-scrollbar {
                width: 8px;
            }
            ::-webkit-scrollbar-track {
                background: #0f172a; 
            }
            ::-webkit-scrollbar-thumb {
                background: #334155; 
                border-radius: 4px;
            }
            ::-webkit-scrollbar-thumb:hover {
                background: #475569; 
            }
        </style>
    </head>
    <body class="bg-darker text-white font-sans antialiased overflow-x-hidden">
        
        <div class="min-h-screen flex flex-col">
            
            <?php include('includes/topbarstudent.php');?> 
            
            <div class="flex flex-1 pt-16">
                
                <?php include('includes/leftbarstudent.php');?>

                <main class="flex-1 lg:ml-64 p-6 transition-all duration-300">
                    
                    <div class="max-w-4xl mx-auto">
                        
                         <!-- Breadcrumb & Title -->
                        <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4 animate-fade-in">
                            <div>
                                <h1 class="text-3xl font-bold font-heading text-white">Result Sheet</h1>
                                <nav class="flex mt-2 text-sm text-gray-400">
                                    <a href="semester.php" class="hover:text-primary transition-colors">Semesters</a>
                                    <span class="mx-2">/</span>
                                    <span class="text-gray-200">View Result</span>
                                </nav>
                            </div>
                            <a href="semester.php" class="inline-flex items-center gap-2 px-4 py-2 bg-surface hover:bg-white/10 text-gray-300 rounded-lg border border-white/10 transition-colors pointer-events-auto">
                                <i class="fa-solid fa-arrow-left"></i> Back
                            </a>
                        </div>

                        <!-- Result Card -->
                        <div class="bg-surface border border-white/10 rounded-2xl p-8 shadow-2xl animate-slide-up relative overflow-hidden">
                            <!-- Background Decoration -->
                            <div class="absolute top-0 right-0 -mr-20 -mt-20 w-64 h-64 bg-primary/10 rounded-full blur-3xl"></div>
                            <div class="absolute bottom-0 left-0 -ml-20 -mb-20 w-64 h-64 bg-emerald-500/10 rounded-full blur-3xl"></div>

                            <!-- Header Info -->
                            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 pb-8 border-b border-white/10 gap-6 relative z-10">
                                <div>
                                    <h2 class="text-2xl font-bold text-white mb-1"><?php echo $studentname; ?></h2>
                                    <div class="flex flex-col sm:flex-row gap-2 sm:gap-6 text-sm text-gray-400">
                                        <span><i class="fa-solid fa-id-card mr-2 text-primary"></i><?php echo $rollid; ?></span>
                                        <span><i class="fa-solid fa-layer-group mr-2 text-emerald-400"></i><?php echo $className; ?> (<?php echo $section; ?>)</span>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm text-gray-400 mb-1">GPA</p>
                                    <div class="text-4xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-primary to-indigo-400">
                                        <?php echo $gpa_formatted; ?>
                                    </div>
                                </div>
                            </div>

                            <!-- Grade Table -->
                            <div class="overflow-x-auto relative z-10">
                                <table class="w-full text-left border-collapse">
                                    <thead>
                                        <tr class="border-b border-white/10">
                                            <th class="py-4 px-2 font-semibold text-sm uppercase text-gray-400">#</th>
                                            <th class="py-4 px-2 font-semibold text-sm uppercase text-gray-400">Code</th>
                                            <th class="py-4 px-2 font-semibold text-sm uppercase text-gray-400">Subject</th>
                                            <th class="py-4 px-2 font-semibold text-sm uppercase text-gray-400 text-center">Credit</th>
                                            <th class="py-4 px-2 font-semibold text-sm uppercase text-gray-400 text-center">Grade</th>
                                            <th class="py-4 px-2 font-semibold text-sm uppercase text-gray-400 text-right">Marks</th>
                                        </tr>
                                    </thead>
                                    <tbody class="text-sm">
                                        <?php
                                        if ($results) {
                                            $cnt = 1;
                                            foreach ($results as $result) {
                                                // Determine Pass/Fail visual
                                                $isPass = true; // Assuming non-numeric grades are handled or standard numeric check
                                                // Assuming simple check logic is not really needed as we just display marks/grades here
                                        ?>
                                        <tr class="border-b border-white/5 last:border-0 hover:bg-white/5 transition-colors">
                                            <td class="py-3 px-2 text-gray-500"><?php echo $cnt; ?></td>
                                            <td class="py-3 px-2 font-mono text-gray-400"><?php echo htmlentities($result->SubjectCode); ?></td>
                                            <td class="py-3 px-2 font-medium text-white"><?php echo htmlentities($result->SubjectName); ?></td>
                                            <td class="py-3 px-2 text-center text-gray-400"><?php echo htmlentities($result->credit); ?></td>
                                            <td class="py-3 px-2 text-center font-bold text-primary"><?php echo htmlentities($result->Grades); ?></td>
                                            <td class="py-3 px-2 text-right font-bold text-emerald-400"><?php echo htmlentities($result->marks); ?></td>
                                        </tr>
                                        <?php
                                                $cnt++;
                                            }
                                        } else {
                                            echo '<tr><td colspan="6" class="py-4 text-center text-gray-400">No results found.</td></tr>';
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>

                            <!-- Footer Actions -->
                            <div class="mt-8 pt-6 border-t border-white/10 flex justify-end gap-3 relative z-10">
                                <a href="download-result.php?studentid=<?php echo $studentid; ?>&year=<?php echo $year; ?>&month=<?php echo $month; ?>" class="inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 text-white font-semibold rounded-xl shadow-lg shadow-emerald-500/20 transform transition-all hover:scale-105 active:scale-95">
                                    <i class="fa-solid fa-file-pdf"></i> Download PDF
                                </a>
                            </div>

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
