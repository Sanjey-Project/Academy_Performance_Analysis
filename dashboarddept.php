<?php
session_start();
//error_reporting(0);
include('includes/config.php');
if(strlen($_SESSION['alogin'])=="")
    {
    header("Location:adminlogin.php");
    }
    else{
        $departmentid = $_SESSION['id'];
        $sql = "SELECT DepartmentName FROM departmentdata WHERE id = :departmentid";
        $stmt = $dbh->prepare($sql);
        $stmt->bindParam(':departmentid', $departmentid,PDO::PARAM_INT);
        $stmt->execute();
        $facultyIdRow = $stmt->fetch(PDO::FETCH_ASSOC);
        $departmentname = $facultyIdRow['DepartmentName'];
        ?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
    	<meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Department Dashboard | Academic Portal</title>
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
            
            <?php include('includes/topbardept.php');?>
            
            <div class="flex flex-1 pt-16">
                
                <?php include('includes/leftbardept.php');?>

                <main class="flex-1 lg:ml-64 p-6 transition-all duration-300">
                    
                    <div class="max-w-7xl mx-auto">
                        
                        <!-- Page Title -->
                        <div class="flex items-center justify-between mb-8 animate-fade-in">
                            <div>
                                <h1 class="text-3xl font-bold font-heading">Welcome, <?php echo htmlentities($departmentname); ?></h1>
                                <p class="text-gray-400 mt-1">Department Dashboard</p>
                            </div>
                            <div class="hidden sm:block">
                                <span class="bg-surface border border-white/10 px-4 py-2 rounded-lg text-sm text-gray-300">
                                    <i class="fa-regular fa-calendar mr-2"></i> <?php echo date("F j, Y"); ?>
                                </span>
                            </div>
                        </div>

                        <!-- Stats Grid -->
                        <section class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8 animate-slide-up">
                            
                            <!-- Users -->
                            <a href="manage-students.php" class="relative overflow-hidden bg-surface border border-white/10 rounded-2xl p-6 group hover:border-emerald-500/50 transition-all duration-300 hover:shadow-lg hover:shadow-emerald-500/10">
                                <?php
                                $sql1 ="SELECT DISTINCT s.StudentName FROM studentdata s JOIN classdata c ON c.id = s.ClassId WHERE c.ClassName ='$departmentname';";
                                $query1 = $dbh -> prepare($sql1);
                                $query1->execute();
                                $totalstudents=$query1->rowCount();
                                ?>
                                <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                                    <i class="fa-solid fa-users text-6xl text-emerald-500"></i>
                                </div>
                                <div class="relative z-10">
                                    <h2 class="text-4xl font-bold font-heading text-white mb-1"><?php echo htmlentities($totalstudents);?></h2>
                                    <p class="text-gray-400 text-sm font-medium uppercase tracking-wider">Users</p>
                                </div>
                                <div class="absolute bottom-0 left-0 w-full h-1 bg-gradient-to-r from-emerald-500 to-teal-600"></div>
                            </a>

                            <!-- Subjects Listed -->
                            <a href="manage-subjects.php" class="relative overflow-hidden bg-surface border border-white/10 rounded-2xl p-6 group hover:border-red-500/50 transition-all duration-300 hover:shadow-lg hover:shadow-red-500/10">
                                <?php
                                $sql ="SELECT DISTINCT scd.subjectid FROM subjectcombinationdata scd JOIN classdata c ON c.id = scd.classid WHERE c.ClassName = '$departmentname';";
                                $query = $dbh -> prepare($sql);
                                $query->execute();
                                $totalsubjects=$query->rowCount();
                                ?>
                                <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                                    <i class="fa-solid fa-ticket text-6xl text-red-500"></i>
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
                                $sql2 ="SELECT id FROM classdata WHERE ClassName = '$departmentname' ";
                                $query2 = $dbh -> prepare($sql2);
                                $query2->execute();
                                $totalclasses=$query2->rowCount();
                                ?>
                                <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                                    <i class="fa-solid fa-bank text-6xl text-yellow-500"></i>
                                </div>
                                <div class="relative z-10">
                                    <h2 class="text-4xl font-bold font-heading text-white mb-1"><?php echo htmlentities($totalclasses);?></h2>
                                    <p class="text-gray-400 text-sm font-medium uppercase tracking-wider">Classes Listed</p>
                                </div>
                                <div class="absolute bottom-0 left-0 w-full h-1 bg-gradient-to-r from-yellow-500 to-amber-600"></div>
                            </a>

                            <!-- Pass Percentage -->
                            <a href="manage-results.php" class="relative overflow-hidden bg-surface border border-white/10 rounded-2xl p-6 group hover:border-blue-500/50 transition-all duration-300 hover:shadow-lg hover:shadow-blue-500/10">
                                <?php
                                $sql3="SELECT COUNT(rd.grades) AS total_grades, COUNT(CASE WHEN rd.grades > 0 THEN 1 ELSE NULL END) AS total_grades_greater_than_zero, (COUNT(CASE WHEN rd.grades > 0 THEN 1 ELSE NULL END) * 100.0) / NULLIF(COUNT(rd.grades), 0) AS percentage_grades_greater_than_zero FROM resultdata rd JOIN classdata cd ON rd.classid = cd.id WHERE cd.ClassName ='$departmentname';";
                                $query3 = $dbh -> prepare($sql3);
                                $query3->execute();
                                $results3=$query3->fetchAll(PDO::FETCH_OBJ);
                                $percentage_grades_greater_than_zero = $results3[0]->percentage_grades_greater_than_zero;
                                ?>
                                <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                                    <i class="fa-solid fa-file-text text-6xl text-blue-500"></i>
                                </div>
                                <div class="relative z-10">
                                    <h2 class="text-4xl font-bold font-heading text-white mb-1"><?php echo htmlentities(round($percentage_grades_greater_than_zero, 1));?>%</h2>
                                    <p class="text-gray-400 text-sm font-medium uppercase tracking-wider">Pass Percentage</p>
                                </div>
                                <div class="absolute bottom-0 left-0 w-full h-1 bg-gradient-to-r from-blue-500 to-indigo-600"></div>
                            </a>

                        </section>

                         <!-- Charts -->
                        <div class="bg-surface border border-white/10 rounded-2xl p-6 animate-slide-up" style="animation-delay: 0.1s;">
                            <h3 class="text-xl font-bold font-heading mb-6">Department Performance</h3>
                            <div class="relative h-64 w-full">
                                <canvas id="deptChart"></canvas>
                            </div>
                        </div>

                    </div>
                </main>
            </div>
        </div>

        <script>
            const ctx = document.getElementById('deptChart').getContext('2d');
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ['Class A', 'Class B', 'Class C', 'Class D'], 
                    datasets: [{
                        label: 'Average Score',
                        data: [78, 85, 72, 88], 
                        backgroundColor: 'rgba(16, 185, 129, 0.5)', // Emerald 500
                        borderColor: '#10b981',
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
