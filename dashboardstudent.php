<?php
session_start();
//error_reporting(0);
include('includes/config.php');
if(strlen($_SESSION['alogin'])=="")
    {   
    header("Location: login.php"); 
    }
    else{
        $studentid = $_SESSION['StudentId'];
        $sql = "SELECT StudentName, passedoutyear FROM studentdata WHERE StudentId = :studentid";
        $query = $dbh->prepare($sql);
        $query->bindParam(':studentid', $studentid, PDO::PARAM_INT);
        $query->execute();
        $studentrow = $query->fetch(PDO::FETCH_ASSOC);
        $studentname = $studentrow['StudentName'];
        $passedoutyear = $studentrow['passedoutyear'];

        $resultSql = "SELECT PostingDate FROM resultdata WHERE StudentId = :studentid ORDER BY PostingDate DESC LIMIT 1";
        $resultQuery = $dbh->prepare($resultSql);
        $resultQuery->bindParam(':studentid', $studentid, PDO::PARAM_INT);
        $resultQuery->execute();
        $resultRow = $resultQuery->fetch(PDO::FETCH_ASSOC);
        $postingDate = $resultRow['PostingDate'];

        $currentDate = date('Y-m-d'); 
        $dateDiff = date_diff(date_create($postingDate), date_create($currentDate))->format('%a');
        $semestersAppeared = ceil($dateDiff / 180);
        ?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
    	<meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Student Dashboard | Academic Portal</title>
        <!-- Google Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&family=Poppins:wght@500;600;700&display=swap" rel="stylesheet">
        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        <!-- Tailwind CSS -->
        <script src="https://cdn.tailwindcss.com"></script>
        <script src="js/tailwind-config.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    </head>
    <body class="bg-darker text-white font-sans antialiased overflow-x-hidden">
        
        <div class="min-h-screen flex flex-col">
            
            <?php include('includes/topbarstudent.php');?>
            
            <div class="flex flex-1 pt-16">
                
                <?php include('includes/leftbarstudent.php');?>

                <main class="flex-1 lg:ml-64 p-6 transition-all duration-300">
                    
                    <div class="max-w-7xl mx-auto">
                        
                        <!-- Page Title -->
                        <div class="flex items-center justify-between mb-8 animate-fade-in">
                            <div>
                                <h1 class="text-3xl font-bold font-heading">Welcome, <?php echo htmlentities($studentname); ?></h1>
                                <p class="text-gray-400 mt-1">Passed Out Year: <?php echo htmlentities($passedoutyear); ?></p>
                            </div>
                            <div class="hidden sm:block">
                                <span class="bg-surface border border-white/10 px-4 py-2 rounded-lg text-sm text-gray-300">
                                    <i class="fa-regular fa-calendar mr-2"></i> <?php echo date("F j, Y"); ?>
                                </span>
                            </div>
                        </div>

                        <!-- Stats Grid -->
                        <section class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8 animate-slide-up">
                            
                            <!-- Semesters Appeared -->
                            <div class="relative overflow-hidden bg-surface border border-white/10 rounded-2xl p-6 group hover:border-pink-500/50 transition-all duration-300 hover:shadow-lg hover:shadow-pink-500/10">
                                <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                                    <i class="fa-solid fa-clock-rotate-left text-6xl text-pink-500"></i>
                                </div>
                                <div class="relative z-10">
                                    <h2 class="text-4xl font-bold font-heading text-white mb-1"><?php echo $semestersAppeared; ?></h2>
                                    <p class="text-gray-400 text-sm font-medium uppercase tracking-wider">Semesters Appeared</p>
                                </div>
                                <div class="absolute bottom-0 left-0 w-full h-1 bg-gradient-to-r from-pink-500 to-rose-600"></div>
                            </div>

                            <!-- Subjects Listed -->
                            <div class="relative overflow-hidden bg-surface border border-white/10 rounded-2xl p-6 group hover:border-violet-500/50 transition-all duration-300 hover:shadow-lg hover:shadow-violet-500/10">
                                <?php
                                $resultSql = "SELECT COUNT(DISTINCT subjectid) AS subjectCount FROM resultdata WHERE StudentId = :studentid";
                                $resultQuery = $dbh->prepare($resultSql);
                                $resultQuery->bindParam(':studentid', $studentid, PDO::PARAM_INT);
                                $resultQuery->execute();
                                $resultRow = $resultQuery->fetch(PDO::FETCH_ASSOC);
                                $subjectCount = $resultRow['subjectCount']; ?>
                                <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                                    <i class="fa-solid fa-book text-6xl text-violet-500"></i>
                                </div>
                                <div class="relative z-10">
                                    <h2 class="text-4xl font-bold font-heading text-white mb-1"><?php echo $subjectCount; ?></h2>
                                    <p class="text-gray-400 text-sm font-medium uppercase tracking-wider">Subjects Taken</p>
                                </div>
                                <div class="absolute bottom-0 left-0 w-full h-1 bg-gradient-to-r from-violet-500 to-purple-600"></div>
                            </div>

                            <!-- CGPA -->
                            <div class="relative overflow-hidden bg-surface border border-white/10 rounded-2xl p-6 group hover:border-yellow-500/50 transition-all duration-300 hover:shadow-lg hover:shadow-yellow-500/10">
                                <?php 
                                $cgpaSql = "SELECT SUM(Grades * credit) / SUM(credit) AS cgpa FROM resultdata 
                                INNER JOIN subjectdata ON resultdata.subjectid = subjectdata.id 
                                WHERE resultdata.StudentId = :studentid";
                                $cgpaQuery = $dbh->prepare($cgpaSql);
                                $cgpaQuery->bindParam(':studentid', $studentid, PDO::PARAM_INT);
                                $cgpaQuery->execute();
                                $cgpaRow = $cgpaQuery->fetch(PDO::FETCH_ASSOC);
                                $cgpa = round($cgpaRow['cgpa'], 2);
                                ?>
                                <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                                    <i class="fa-solid fa-star text-6xl text-yellow-500"></i>
                                </div>
                                <div class="relative z-10">
                                    <h2 class="text-4xl font-bold font-heading text-white mb-1"><?php echo $cgpa; ?></h2>
                                    <p class="text-gray-400 text-sm font-medium uppercase tracking-wider">Overall CGPA</p>
                                </div>
                                <div class="absolute bottom-0 left-0 w-full h-1 bg-gradient-to-r from-yellow-500 to-amber-600"></div>
                            </div>

                            <!-- Arrears -->
                            <div class="relative overflow-hidden bg-surface border border-white/10 rounded-2xl p-6 group hover:border-red-500/50 transition-all duration-300 hover:shadow-lg hover:shadow-red-500/10">
                                <?php
                                $arrearsSql = "SELECT COUNT(*) AS arrearsCount FROM resultdata WHERE StudentId = :studentid AND Grades = 0";
                                $arrearsQuery = $dbh->prepare($arrearsSql);
                                $arrearsQuery->bindParam(':studentid', $studentid, PDO::PARAM_INT);
                                $arrearsQuery->execute();
                                $arrearsRow = $arrearsQuery->fetch(PDO::FETCH_ASSOC);
                                $arrearsCount = $arrearsRow['arrearsCount']; ?>
                                <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                                    <i class="fa-solid fa-triangle-exclamation text-6xl text-red-500"></i>
                                </div>
                                <div class="relative z-10">
                                    <h2 class="text-4xl font-bold font-heading text-white mb-1"><?php echo $arrearsCount; ?></h2>
                                    <p class="text-gray-400 text-sm font-medium uppercase tracking-wider">Arrears</p>
                                </div>
                                <div class="absolute bottom-0 left-0 w-full h-1 bg-gradient-to-r from-red-500 to-orange-600"></div>
                            </div>

                        </section>

                         <!-- Charts -->
                        <div class="bg-surface border border-white/10 rounded-2xl p-6 animate-slide-up" style="animation-delay: 0.1s;">
                            <h3 class="text-xl font-bold font-heading mb-6">Performance By Semester</h3>
                            <div class="relative h-64 w-full">
                                <canvas id="studentChart"></canvas>
                            </div>
                        </div>

                    </div>
                </main>
            </div>
        </div>

        <script>
            const ctx = document.getElementById('studentChart').getContext('2d');
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ['Sem 1', 'Sem 2', 'Sem 3', 'Sem 4', 'Sem 5', 'Sem 6'], // Example labels
                    datasets: [{
                        label: 'GPA',
                        data: [7.4, 9.53, 9.36, 7.96, 8.16, 8.63], // Example data
                        backgroundColor: 'rgba(236, 72, 153, 0.5)', // Pink
                        borderColor: '#ec4899', // Pink 500
                        borderWidth: 1,
                        borderRadius: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            max: 10,
                            grid: { color: 'rgba(255, 255, 255, 0.1)' },
                            ticks: { color: '#9ca3af' }
                        },
                        x: {
                            grid: { display: false },
                            ticks: { color: '#9ca3af' }
                        }
                    },
                    plugins: {
                        legend: { display: false }
                    }
                }
            });
        </script>
    </body>
</html>
<?php } ?>
