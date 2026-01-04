<?php
session_start();
//error_reporting(0);
include('includes/config.php');
if(strlen($_SESSION['alogin'])=="")
    {
    header("Location:adminlogin.php");
    }
    else{
        $facultyid = $_SESSION['id'];
        $sql = "SELECT FacultyName FROM facultydata WHERE id = :facultyid";
        $stmt = $dbh->prepare($sql);
        $stmt->bindParam(':facultyid', $facultyid,PDO::PARAM_INT);
        $stmt->execute();
        $facultyIdRow = $stmt->fetch(PDO::FETCH_ASSOC);
        $facultyname = $facultyIdRow['FacultyName'];
        ?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
    	<meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Faculty Dashboard | Academic Portal</title>
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
            
            <?php include('includes/topbarfaculty.php');?>
            
            <div class="flex flex-1 pt-16">
                
                <?php include('includes/leftbarfaculty.php');?>

                <main class="flex-1 lg:ml-64 p-6 transition-all duration-300">
                    
                    <div class="max-w-7xl mx-auto">
                        
                        <!-- Page Title -->
                        <div class="flex items-center justify-between mb-8 animate-fade-in">
                            <div>
                                <h1 class="text-3xl font-bold font-heading">Welcome, <?php echo htmlentities($facultyname); ?></h1>
                                <p class="text-gray-400 mt-1">Faculty Dashboard</p>
                            </div>
                            <div class="hidden sm:block">
                                <span class="bg-surface border border-white/10 px-4 py-2 rounded-lg text-sm text-gray-300">
                                    <i class="fa-regular fa-calendar mr-2"></i> <?php echo date("F j, Y"); ?>
                                </span>
                            </div>
                        </div>

                        <!-- Stats Grid -->
                        <section class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8 animate-slide-up">
                            
                            <!-- Students -->
                            <a href="studentwisefc.php" class="relative overflow-hidden bg-surface border border-white/10 rounded-2xl p-6 group hover:border-sky-500/50 transition-all duration-300 hover:shadow-lg hover:shadow-sky-500/10">
                                <?php
                                $sql1 ="SELECT sd.StudentId FROM studentdata sd JOIN classdata cd ON sd.ClassID = cd.id JOIN facultycombinationdata fcd ON cd.id = fcd.ClassId JOIN facultydata fd ON fcd.FacultyId = fd.id WHERE fd.id ='$facultyid'; ";
                                $query1 = $dbh -> prepare($sql1);
                                $query1->execute();
                                $totalstudents=$query1->rowCount();
                                ?>
                                <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                                    <i class="fa-solid fa-users text-6xl text-sky-500"></i>
                                </div>
                                <div class="relative z-10">
                                    <h2 class="text-4xl font-bold font-heading text-white mb-1"><?php echo htmlentities($totalstudents);?></h2>
                                    <p class="text-gray-400 text-sm font-medium uppercase tracking-wider">Students</p>
                                </div>
                                <div class="absolute bottom-0 left-0 w-full h-1 bg-gradient-to-r from-sky-500 to-cyan-600"></div>
                            </a>

                            <!-- Subjects Listed -->
                            <a href="manage-subjects.php" class="relative overflow-hidden bg-surface border border-white/10 rounded-2xl p-6 group hover:border-red-500/50 transition-all duration-300 hover:shadow-lg hover:shadow-red-500/10">
                                <?php
                                $sql ="SELECT subd.id FROM subjectdata subd JOIN facultycombinationdata fcd ON subd.id=fcd.SubjectId JOIN facultydata fd ON fcd.FacultyId = fd.id WHERE fd.id ='$facultyid'; ";
                                $query = $dbh -> prepare($sql);
                                $query->execute();
                                $totalsubjects=$query->rowCount();
                                ?>
                                <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                                    <i class="fa-solid fa-book-open text-6xl text-red-500"></i>
                                </div>
                                <div class="relative z-10">
                                    <h2 class="text-4xl font-bold font-heading text-white mb-1"><?php echo htmlentities($totalsubjects);?></h2>
                                    <p class="text-gray-400 text-sm font-medium uppercase tracking-wider">Subjects Listed</p>
                                </div>
                                <div class="absolute bottom-0 left-0 w-full h-1 bg-gradient-to-r from-red-500 to-orange-600"></div>
                            </a>

                            <!-- Classes Listed -->
                            <a href="manage-classes.php" class="relative overflow-hidden bg-surface border border-white/10 rounded-2xl p-6 group hover:border-yellow-500/50 transition-all duration-300 hover:shadow-lg hover:shadow-yellow-500/10">
                                <?php
                                $sql2 ="SELECT cd.id FROM classdata cd JOIN facultycombinationdata fcd ON cd.id=fcd.ClassId JOIN facultydata fd ON fcd.FacultyId=fd.id WHERE fd.id = '$facultyid'; ";
                                $query2 = $dbh -> prepare($sql2);
                                $query2->execute();
                                $totalclasses=$query2->rowCount();
                                ?>
                                <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                                    <i class="fa-solid fa-chalkboard text-6xl text-yellow-500"></i>
                                </div>
                                <div class="relative z-10">
                                    <h2 class="text-4xl font-bold font-heading text-white mb-1"><?php echo htmlentities($totalclasses);?></h2>
                                    <p class="text-gray-400 text-sm font-medium uppercase tracking-wider">Classes Listed</p>
                                </div>
                                <div class="absolute bottom-0 left-0 w-full h-1 bg-gradient-to-r from-yellow-500 to-amber-600"></div>
                            </a>

                            <!-- Pass Percentage -->
                            <a href="manage-results.php" class="relative overflow-hidden bg-surface border border-white/10 rounded-2xl p-6 group hover:border-emerald-500/50 transition-all duration-300 hover:shadow-lg hover:shadow-emerald-500/10">
                                <?php
                                $sql3="SELECT COUNT(rc.Grades) AS total_grades, COUNT(CASE WHEN rc.Grades > 0 THEN 1 ELSE NULL END) AS total_grades_greater_than_zero, (COUNT(CASE WHEN rc.Grades > 0 THEN 1 ELSE NULL END) * 100.0) / NULLIF(COUNT(rc.Grades), 0) AS percentage_grades_greater_than_zero FROM resultdata rc JOIN facultycombinationdata fc ON rc.ClassId = fc.ClassId AND rc.SubjectId = fc.SubjectId JOIN facultydata fd ON fc.FacultyId = fd.id WHERE fd.id = '$facultyid'; ";
                                $query3 = $dbh -> prepare($sql3);
                                $query3->execute();
                                $results3=$query3->fetchAll(PDO::FETCH_OBJ);
                                $percentage_grades_greater_than_zero = $results3[0]->percentage_grades_greater_than_zero;
                                ?>
                                <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                                    <i class="fa-solid fa-percent text-6xl text-emerald-500"></i>
                                </div>
                                <div class="relative z-10">
                                    <h2 class="text-4xl font-bold font-heading text-white mb-1"><?php echo htmlentities(round($percentage_grades_greater_than_zero, 1));?>%</h2>
                                    <p class="text-gray-400 text-sm font-medium uppercase tracking-wider">Pass Percentage</p>
                                </div>
                                <div class="absolute bottom-0 left-0 w-full h-1 bg-gradient-to-r from-emerald-500 to-teal-600"></div>
                            </a>

                        </section>

                         <!-- Charts -->
                        <div class="bg-surface border border-white/10 rounded-2xl p-6 animate-slide-up" style="animation-delay: 0.1s;">
                            <h3 class="text-xl font-bold font-heading mb-6">Subject Performance</h3>
                            <div class="relative h-64 w-full">
                                <canvas id="facultyChart"></canvas>
                            </div>
                        </div>

                    </div>
                </main>
            </div>
        </div>

        <script>
            const ctx = document.getElementById('facultyChart').getContext('2d');
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ['Subject A', 'Subject B', 'Subject C', 'Subject D'], // Placeholder
                    datasets: [{
                        label: 'Average Score',
                        data: [75, 82, 68, 90], // Placeholder
                        backgroundColor: 'rgba(14, 165, 233, 0.5)', // Sky 500
                        borderColor: '#0ea5e9',
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
                            max: 100,
                            grid: { color: 'rgba(255, 255, 255, 0.1)' },
                            ticks: { color: '#9ca3af' }
                        },
                        x: {
                            grid: { display: false },
                            ticks: { color: '#9ca3af' }
                        }
                    },
                    plugins: {
                        legend: { labels: { color: '#fff' } }
                    }
                }
            });
        </script>
    </body>
</html>
<?php } ?>
