<?php
session_start();
error_reporting(0);
include('includes/config.php');
if(strlen($_SESSION['alogin'])=="")
    {
    header("Location:adminlogin.php");
    }
    else{
        ?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
    	<meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Admin Dashboard | Academic Portal</title>
        <!-- Google Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&family=Poppins:wght@500;600;700&display=swap" rel="stylesheet">
        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        <!-- Tailwind CSS -->
        <script src="https://cdn.tailwindcss.com"></script>
        <script src="js/tailwind-config.js"></script>
        <!-- Chart.js (Modern Replacement for AmCharts) -->
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    </head>
    <body class="bg-darker text-white font-sans antialiased overflow-x-hidden">
        
        <div class="min-h-screen flex flex-col">
            
            <!-- Topbar -->
            <?php include('includes/topbar.php');?>
            
            <div class="flex flex-1 pt-16">
                
                <!-- Sidebar -->
                <?php include('includes/leftbar.php');?>

                <!-- Main Content -->
                <main class="flex-1 lg:ml-64 p-6 transition-all duration-300">
                    
                    <div class="max-w-7xl mx-auto">
                        
                        <!-- Page Title -->
                        <div class="flex items-center justify-between mb-8 animate-fade-in">
                            <div>
                                <h1 class="text-3xl font-bold font-heading">Dashboard</h1>
                                <p class="text-gray-400 mt-1">Overview of academic performance data</p>
                            </div>
                            <div class="hidden sm:block">
                                <span class="bg-surface border border-white/10 px-4 py-2 rounded-lg text-sm text-gray-300">
                                    <i class="fa-regular fa-calendar mr-2"></i> <?php echo date("F j, Y"); ?>
                                </span>
                            </div>
                        </div>

                        <!-- Stats Grid -->
                        <section class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8 animate-slide-up">
                            
                            <!-- Regd Users -->
                            <a href="manage-students.php" class="relative overflow-hidden bg-surface border border-white/10 rounded-2xl p-6 group hover:border-primary/50 transition-all duration-300 hover:shadow-lg hover:shadow-primary/10">
                                <?php
                                $sql1 ="SELECT StudentId from studentdata ";
                                $query1 = $dbh -> prepare($sql1);
                                $query1->execute();
                                $totalstudents=$query1->rowCount();
                                ?>
                                <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                                    <i class="fa-solid fa-users text-6xl text-primary"></i>
                                </div>
                                <div class="relative z-10">
                                    <h2 class="text-4xl font-bold font-heading text-white mb-1"><?php echo htmlentities($totalstudents);?></h2>
                                    <p class="text-gray-400 text-sm font-medium uppercase tracking-wider">Registered Users</p>
                                    <div class="mt-4 flex items-center text-primary text-sm font-medium group-hover:translate-x-1 transition-transform">
                                        View Details <i class="fa-solid fa-arrow-right ml-2 text-xs"></i>
                                    </div>
                                </div>
                                <div class="absolute bottom-0 left-0 w-full h-1 bg-gradient-to-r from-primary to-indigo-600"></div>
                            </a>

                            <!-- Subjects Listed -->
                            <a href="manage-subjects.php" class="relative overflow-hidden bg-surface border border-white/10 rounded-2xl p-6 group hover:border-red-500/50 transition-all duration-300 hover:shadow-lg hover:shadow-red-500/10">
                                <?php
                                $sql ="SELECT id from  subjectdata ";
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
                                    <div class="mt-4 flex items-center text-red-400 text-sm font-medium group-hover:translate-x-1 transition-transform">
                                        View Details <i class="fa-solid fa-arrow-right ml-2 text-xs"></i>
                                    </div>
                                </div>
                                <div class="absolute bottom-0 left-0 w-full h-1 bg-gradient-to-r from-red-500 to-orange-600"></div>
                            </a>

                            <!-- Classes Listed -->
                            <a href="manage-classes.php" class="relative overflow-hidden bg-surface border border-white/10 rounded-2xl p-6 group hover:border-yellow-500/50 transition-all duration-300 hover:shadow-lg hover:shadow-yellow-500/10">
                                <?php
                                $sql2 ="SELECT id from  classdata ";
                                $query2 = $dbh -> prepare($sql2);
                                $query2->execute();
                                $totalclasses=$query2->rowCount();
                                ?>
                                <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                                    <i class="fa-solid fa-chalkboard text-6xl text-yellow-500"></i>
                                </div>
                                <div class="relative z-10">
                                    <h2 class="text-4xl font-bold font-heading text-white mb-1"><?php echo htmlentities($totalclasses);?></h2>
                                    <p class="text-gray-400 text-sm font-medium uppercase tracking-wider">Total Classes</p>
                                    <div class="mt-4 flex items-center text-yellow-400 text-sm font-medium group-hover:translate-x-1 transition-transform">
                                        View Details <i class="fa-solid fa-arrow-right ml-2 text-xs"></i>
                                    </div>
                                </div>
                                <div class="absolute bottom-0 left-0 w-full h-1 bg-gradient-to-r from-yellow-500 to-amber-600"></div>
                            </a>

                            <!-- Results Declared -->
                            <a href="manage-results.php" class="relative overflow-hidden bg-surface border border-white/10 rounded-2xl p-6 group hover:border-emerald-500/50 transition-all duration-300 hover:shadow-lg hover:shadow-emerald-500/10">
                                <?php
                                $sql3="SELECT  distinct StudentId from  resultdata ";
                                $query3 = $dbh -> prepare($sql3);
                                $query3->execute();
                                $totalresults=$query3->rowCount();
                                ?>
                                <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                                    <i class="fa-solid fa-square-poll-vertical text-6xl text-emerald-500"></i>
                                </div>
                                <div class="relative z-10">
                                    <h2 class="text-4xl font-bold font-heading text-white mb-1"><?php echo htmlentities($totalresults);?></h2>
                                    <p class="text-gray-400 text-sm font-medium uppercase tracking-wider">Results Declared</p>
                                    <div class="mt-4 flex items-center text-emerald-400 text-sm font-medium group-hover:translate-x-1 transition-transform">
                                        View Details <i class="fa-solid fa-arrow-right ml-2 text-xs"></i>
                                    </div>
                                </div>
                                <div class="absolute bottom-0 left-0 w-full h-1 bg-gradient-to-r from-emerald-500 to-teal-600"></div>
                            </a>

                        </section>

                        <!-- Chart Section (New Addition for "Next Level") -->
                        <div class="bg-surface border border-white/10 rounded-2xl p-6 animate-slide-up" style="animation-delay: 0.1s;">
                            <div class="flex items-center justify-between mb-6">
                                <h3 class="text-xl font-bold font-heading">Performance Overview</h3>
                                <button class="text-sm text-primary hover:text-white transition-colors">Download Report</button>
                            </div>
                            <div class="relative h-64 w-full">
                                <canvas id="performanceChart"></canvas>
                            </div>
                        </div>

                    </div>
                </main>
            </div>
        </div>

        <script>
            // Initialize Chart
            const ctx = document.getElementById('performanceChart').getContext('2d');
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                    datasets: [{
                        label: 'Results Published',
                        data: [12, 19, 3, 5, 2, 3],
                        backgroundColor: 'rgba(79, 70, 229, 0.5)', // Primary color opacity
                        borderColor: '#4f46e5',
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
                            grid: {
                                color: 'rgba(255, 255, 255, 0.1)'
                            },
                            ticks: {
                                color: '#9ca3af'
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                color: '#9ca3af'
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            labels: {
                                color: '#fff'
                            }
                        }
                    }
                }
            });
        
            // Optional: Notification Toast Logic if needed (Simple version)
             // document.addEventListener('DOMContentLoaded', () => {
             //    // Notify welcome
             // });
        </script>
    </body>
</html>
<?php } ?>
