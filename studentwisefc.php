<?php
session_start();
error_reporting(0);
include('includes/config.php');
if(strlen($_SESSION['alogin'])=="")
    {   
    header("Location: login.php"); 
    }
    else{
        $facultyid = $_SESSION['id'];
        $sql = "SELECT FacultyName FROM facultydata WHERE id = :facultyid";
    $stmt = $dbh->prepare($sql);
    $stmt->bindParam(':facultyid', $facultyid,PDO::PARAM_INT);
    $stmt->execute();
    $facultyIdRow = $stmt->fetch(PDO::FETCH_ASSOC);
    $facultyname = $facultyIdRow['FacultyName'];

    $sql = "SELECT 
                sd.StudentName, 
                cd.ClassNameNumeric, 
                cd.Section, 
                CASE 
                    WHEN rd.Grades > 0 THEN 100 
                    ELSE 0 
                END AS PassPercentage
            FROM 
                studentdata sd
                JOIN resultdata rd ON sd.StudentId = rd.StudentId
                JOIN classdata cd ON rd.ClassId = cd.id
                JOIN facultycombinationdata fcd ON rd.ClassId = fcd.ClassId
                JOIN facultydata fd ON fcd.FacultyId = fd.id
            WHERE 
                fd.id = (
                    SELECT id 
                    FROM facultydata 
                    WHERE FacultyName = :facultyname
                )
                AND rd.SubjectId IN (
                    SELECT SubjectId 
                    FROM facultycombinationdata 
                    WHERE FacultyId = fd.id
                      AND ClassId = cd.id
                )";
                 
    $query = $dbh->prepare($sql);
    $query->bindParam(':facultyname', $facultyname, PDO::PARAM_STR);
    $query->execute();
    $results = $query->fetchAll(PDO::FETCH_OBJ);
    $chartData = [];
    $labels = [];
    $data_percentage = [];

    if ($query->rowCount() > 0) {
        foreach ($results as $result) {
            $labels[] = $result->StudentName;
            $data_percentage[] = floatval($result->PassPercentage);
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
            
            <?php include('includes/topbarfaculty.php');?> 
            
            <div class="flex flex-1 pt-16">
                
                <?php include('includes/leftbarfaculty.php');?>

                <main class="flex-1 lg:ml-64 p-6 transition-all duration-300">
                    
                    <div class="max-w-7xl mx-auto">
                        
                         <!-- Breadcrumb & Title -->
                        <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4 animate-fade-in">
                            <div>
                                <h1 class="text-3xl font-bold font-heading text-white">Student Result Analysis</h1>
                                <nav class="flex mt-2 text-sm text-gray-400">
                                    <a href="dashboardfaculty.php" class="hover:text-primary transition-colors">Home</a>
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
                                <i class="fa-solid fa-chart-bar text-primary"></i> Pass Percentage By Student
                            </h2>
                            <div class="relative h-80 w-full">
                                <canvas id="performanceChart"></canvas>
                            </div>
                        </div>

                        <!-- Data Table Section -->
                        <div class="bg-surface border border-white/10 rounded-2xl p-6 shadow-xl animate-slide-up" style="animation-delay: 0.1s;">
                            <h2 class="text-xl font-bold font-heading mb-4 border-b border-white/10 pb-2">Student Results</h2>
                            
                            <div class="overflow-x-auto">
                                <table id="example" class="w-full text-left border-collapse">
                                    <thead>
                                        <tr>
                                            <th class="py-3 px-4 font-semibold text-sm uppercase tracking-wider text-gray-400">#</th>
                                            <th class="py-3 px-4 font-semibold text-sm uppercase tracking-wider text-gray-400">Student Name</th>
                                            <th class="py-3 px-4 font-semibold text-sm uppercase tracking-wider text-gray-400">Year</th>
                                            <th class="py-3 px-4 font-semibold text-sm uppercase tracking-wider text-gray-400">Section</th>
                                            <th class="py-3 px-4 font-semibold text-sm uppercase tracking-wider text-gray-400">Result</th>
                                        </tr>
                                    </thead>
                                    <tbody class="text-sm">
<?php
    $cnt = 1;
    if (count($results) > 0) {
    foreach ($results as $result) {
        $pass_percentage = floatval($result->PassPercentage);
             $colorClass = $pass_percentage >= 40 ? 'text-emerald-400' : 'text-red-400';
             $iconClass = $pass_percentage >= 40 ? 'fa-circle-check' : 'fa-circle-xmark';
             $text = $pass_percentage >= 40 ? 'Pass' : 'Fail';
?>
                                        <tr class="hover:bg-white/5 transition-colors border-b border-white/5 last:border-0">
                                            <td class="py-3 px-4"><?php echo htmlentities($cnt);?></td>
                                            <td class="py-3 px-4 font-medium"><?php echo htmlentities($result->StudentName);?></td>
                                            <td class="py-3 px-4"><?php echo htmlentities($result->ClassNameNumeric);?></td>
                                            <td class="py-3 px-4"><?php echo htmlentities($result->Section);?></td>
                                            <td class="py-3 px-4">
                                                <div class="flex items-center gap-2 <?php echo $colorClass; ?>">
                                                    <i class="fa-solid <?php echo $iconClass; ?>"></i>
                                                    <span class="font-medium"><?php echo $text; ?></span>
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
                            backgroundColor: 'rgba(99, 102, 241, 0.5)',
                            borderColor: '#6366f1',
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
                                    color: '#9ca3af'
                                }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                max: 100,
                                grid: {
                                    color: '#374151'
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
