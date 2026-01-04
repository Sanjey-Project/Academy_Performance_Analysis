
<?php
session_start();
error_reporting(0);
include('includes/config.php');
if(strlen($_SESSION['alogin'])=="")
    {   
    header("Location: login.php"); 
    }
    else{
        $departmentid = $_SESSION['id'];
        $sql = "SELECT DepartmentName FROM departmentdata WHERE id = :departmentid";
    $stmt = $dbh->prepare($sql);
    $stmt->bindParam(':departmentid', $departmentid,PDO::PARAM_INT);
    $stmt->execute();
    $facultyIdRow = $stmt->fetch(PDO::FETCH_ASSOC);
    $departmentname = $facultyIdRow['DepartmentName'];

    $sql="SELECT 
        sd.studentid,
        sd.studentname AS 'Student Name',
        cd.ClassNameNumeric AS 'Year Name',
        cd.Section,
        COUNT(*) AS 'Total Subjects',
        SUM(CASE WHEN r.grades > 0 THEN 1 ELSE 0 END) AS 'Passed Subjects',
        (SUM(CASE WHEN r.grades > 0 THEN 1 ELSE 0 END) / COUNT(*)) * 100 AS 'Pass Percentage'
    FROM 
        studentdata sd
    LEFT JOIN 
        classdata cd ON sd.classid = cd.id
    LEFT JOIN 
        resultdata r ON sd.studentid = r.studentid
        WHERE 
          cd.ClassName = :ClassName
    GROUP BY 
        sd.studentid, sd.studentname, cd.ClassNameNumeric, cd.Section
    ORDER BY 
        cd.ClassNameNumeric;";
 
    $query = $dbh->prepare($sql);
    $query->bindParam(':ClassName', $departmentname, PDO::PARAM_STR);
    $query->execute();
    $results = $query->fetchAll(PDO::FETCH_OBJ);

    $labels = [];
    $data_percentage = [];
    
    if ($query->rowCount() > 0) {
        foreach ($results as $result) {
            $pass_percentage = ($result->{'Passed Subjects'} / $result->{'Total Subjects'}) * 100;
             $labels[] = $result->{'Student Name'};
             $data_percentage[] = round($pass_percentage, 2);
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
    	<meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Student Wise Performance | Academic Portal</title>
        <!-- Google Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&family=Poppins:wght@500;600;700&display=swap" rel="stylesheet">
        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        <!-- Tailwind CSS -->
        <script src="https://cdn.tailwindcss.com"></script>
        <script src="js/tailwind-config.js"></script>
         <!-- Chart.js -->
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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

            /* DataTables Customization for Dark Theme */
            .dataTables_wrapper .dataTables_length, 
            .dataTables_wrapper .dataTables_filter, 
            .dataTables_wrapper .dataTables_info, 
            .dataTables_wrapper .dataTables_processing, 
            .dataTables_wrapper .dataTables_paginate {
                color: #9ca3af !important; /* text-gray-400 */
                margin-bottom: 1rem;
            }
            .dataTables_wrapper .dataTables_filter input {
                background-color: #1e293b; 
                border: 1px solid #374151; 
                color: #e5e7eb; 
                border-radius: 0.375rem;
                padding: 0.25rem 0.5rem;
            }
            .dataTables_wrapper .dataTables_length select {
                background-color: #1e293b;
                border: 1px solid #374151;
                color: #e5e7eb;
                border-radius: 0.375rem;
                padding: 0.25rem 2rem 0.25rem 0.5rem;
            }
            table.dataTable tbody tr {
                background-color: transparent !important;
            }
            table.dataTable tbody tr:hover {
                background-color: rgba(255, 255, 255, 0.05) !important;
            }
            table.dataTable td {
                border-bottom: 1px solid #374151 !important; 
                color: #d1d5db; 
            }
            table.dataTable th {
                border-bottom: 1px solid #374151 !important;
                color: #f3f4f6; 
            }
            .dataTables_wrapper .dataTables_paginate .paginate_button {
                color: #9ca3af !important;
            }
            .dataTables_wrapper .dataTables_paginate .paginate_button.current {
                color: white !important;
                background: #4f46e5 !important; 
                border: none !important;
            }
        </style>
    </head>
    <body class="bg-darker text-white font-sans antialiased overflow-x-hidden">
        
        <div class="min-h-screen flex flex-col">
            
            <?php include('includes/topbardept.php');?> 
            
            <div class="flex flex-1 pt-16">
                
                <?php include('includes/leftbardept.php');?>

                <main class="flex-1 lg:ml-64 p-6 transition-all duration-300">
                    
                    <div class="max-w-7xl mx-auto">
                        
                         <!-- Breadcrumb & Title -->
                        <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4 animate-fade-in">
                            <div>
                                <h1 class="text-3xl font-bold font-heading text-white">Student Performance Analysis</h1>
                                <nav class="flex mt-2 text-sm text-gray-400">
                                    <a href="dashboarddept.php" class="hover:text-primary transition-colors">Home</a>
                                    <span class="mx-2">/</span>
                                    <span class="text-gray-200">Analysis</span>
                                    <span class="mx-2">/</span>
                                    <span class="text-gray-200">Student Wise</span>
                                </nav>
                            </div>
                        </div>

                        <!-- Chart Section -->
                         <div class="bg-surface border border-white/10 rounded-2xl p-6 shadow-xl mb-8 animate-slide-up">
                            <h2 class="text-xl font-bold font-heading mb-4 text-white flex items-center gap-2">
                                <i class="fa-solid fa-chart-line text-primary"></i> Performance Overview
                            </h2>
                            <div class="relative h-80 w-full">
                                <canvas id="performanceChart"></canvas>
                            </div>
                        </div>

                        <!-- Data Table Section -->
                        <div class="bg-surface border border-white/10 rounded-2xl p-6 shadow-xl animate-slide-up" style="animation-delay: 0.1s;">
                            <h2 class="text-xl font-bold font-heading mb-4 border-b border-white/10 pb-2">Student List</h2>
                            
                            <div class="overflow-x-auto">
                                <table id="example" class="w-full text-left border-collapse">
                                    <thead>
                                        <tr>
                                            <th class="py-3 px-4 font-semibold text-sm uppercase tracking-wider text-gray-400">#</th>
                                            <th class="py-3 px-4 font-semibold text-sm uppercase tracking-wider text-gray-400">Student Name</th>
                                            <th class="py-3 px-4 font-semibold text-sm uppercase tracking-wider text-gray-400">Year</th>
                                            <th class="py-3 px-4 font-semibold text-sm uppercase tracking-wider text-gray-400">Section</th>
                                            <th class="py-3 px-4 font-semibold text-sm uppercase tracking-wider text-gray-400">Pass %</th>
                                        </tr>
                                    </thead>
                                    <tbody class="text-sm">
<?php
    // Reset internal pointer of results to reuse
    $cnt = 1;
    if (count($results) > 0) {
    foreach ($results as $result) {
        $pass_percentage = ($result->{'Passed Subjects'} / $result->{'Total Subjects'}) * 100;
?>
                                        <tr class="hover:bg-white/5 transition-colors border-b border-white/5 last:border-0">
                                            <td class="py-3 px-4"><?php echo htmlentities($cnt);?></td>
                                            <td class="py-3 px-4 font-medium"><?php echo htmlentities($result->{'Student Name'});?></td>
                                            <td class="py-3 px-4"><?php echo htmlentities($result->{'Year Name'});?></td>
                                            <td class="py-3 px-4"><?php echo htmlentities($result->Section);?></td>
                                            <td class="py-3 px-4">
                                                <div class="flex items-center gap-2">
                                                    <div class="w-full bg-dark rounded-full h-2.5 max-w-[100px]">
                                                        <div class="bg-gradient-to-r from-primary to-purple-500 h-2.5 rounded-full" style="width: <?php echo round($pass_percentage);?>%"></div>
                                                    </div>
                                                    <span class="text-xs font-medium"><?php echo round($pass_percentage, 1);?>%</span>
                                                </div>
                                            </td>
                                        </tr>
<?php $cnt++; }} ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div>
                </main>
            </div>
        </div>

        <!-- Scripts -->
        <script src="js/jquery/jquery-2.2.4.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
        
        <script>
            $(document).ready(function() {
                $('#example').DataTable({
                    "paging": true,
                    "lengthChange": true,
                    "searching": true,
                    "ordering": true,
                    "info": true,
                    "autoWidth": false,
                    "responsive": true,
                    "language": {
                        "search": "_INPUT_",
                        "searchPlaceholder": "Search students...",
                    }
                });

                // Chart.js Configuration
                const ctx = document.getElementById('performanceChart').getContext('2d');
                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: <?php echo json_encode($labels); ?>,
                        datasets: [{
                            label: 'Pass Percentage',
                            data: <?php echo json_encode($data_percentage); ?>,
                            backgroundColor: 'rgba(99, 102, 241, 0.5)', // Primary/Indigo with opacity
                            borderColor: '#6366f1', // Primary/Indigo
                            borderWidth: 1,
                            borderRadius: 4,
                            hoverBackgroundColor: 'rgba(99, 102, 241, 0.7)'
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                labels: {
                                    color: '#9ca3af' // text-gray-400
                                }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                max: 100,
                                grid: {
                                    color: '#374151' // border-gray-700
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
                        }
                    }
                });
            });
        </script>
    </body>
</html>
<?php } ?>
