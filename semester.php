<?php
session_start();
error_reporting(0);
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

        // Fetch the updation dates and calculate year differences
        $sql = "SELECT UpdationDate FROM resultdata WHERE StudentId = :studentid";
        $query = $dbh->prepare($sql);
        $query->bindParam(':studentid', $studentid, PDO::PARAM_INT);
        $query->execute();
        $results = $query->fetchAll(PDO::FETCH_ASSOC);

        $semesterData = [];
        foreach ($results as $result) {
            $updationDate = $result['UpdationDate'];
            $updationYear = date('Y', strtotime($updationDate));
            $updationMonth = date('n', strtotime($updationDate)); // Extract month as integer (1-12)
            
            $yearDifference = $updationYear - $passedoutyear;

            // Adjust the yearDifference based on the month
            if ($updationMonth <= 6) {
                $adjustedYearDifference = $yearDifference * 2 - 1;
            } else {
                $adjustedYearDifference = $yearDifference * 2;
            }

            $semesterData[$adjustedYearDifference][] = [
                'updationDate' => $updationDate,
            ];
        }
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
    	<meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Semester Results | Academic Portal</title>
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
            
            <?php include('includes/topbarstudent.php');?> 
            
            <div class="flex flex-1 pt-16">
                
                <?php include('includes/leftbarstudent.php');?>

                <main class="flex-1 lg:ml-64 p-6 transition-all duration-300">
                    
                    <div class="max-w-7xl mx-auto">
                        
                         <!-- Breadcrumb & Title -->
                        <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4 animate-fade-in">
                            <div>
                                <h1 class="text-3xl font-bold font-heading text-white">Semester Results</h1>
                                <nav class="flex mt-2 text-sm text-gray-400">
                                    <a href="dashboard.php" class="hover:text-primary transition-colors">Home</a>
                                    <span class="mx-2">/</span>
                                    <span class="text-gray-200">Results</span>
                                    <span class="mx-2">/</span>
                                    <span class="text-gray-200">Semester</span>
                                </nav>
                            </div>
                        </div>

                        <!-- Data Table Section -->
                        <div class="bg-surface border border-white/10 rounded-2xl p-6 shadow-xl animate-slide-up">
                            <h2 class="text-xl font-bold font-heading mb-4 border-b border-white/10 pb-2 flex items-center gap-2">
                                <i class="fa-solid fa-graduation-cap text-teal-400"></i> Available Semesters
                            </h2>
                            
                            <div class="overflow-x-auto">
                                <table id="example" class="w-full text-left border-collapse">
                                    <thead>
                                        <tr>
                                            <th class="py-3 px-4 font-semibold text-sm uppercase tracking-wider text-gray-400">#</th>
                                            <th class="py-3 px-4 font-semibold text-sm uppercase tracking-wider text-gray-400">Semester</th>
                                            <th class="py-3 px-4 font-semibold text-sm uppercase tracking-wider text-gray-400">Published Date</th>
                                            <th class="py-3 px-4 font-semibold text-sm uppercase tracking-wider text-gray-400">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody class="text-sm">
<?php
$cnt = 1;
foreach ($semesterData as $adjustedYearDifference => $data) {
    $updationDate = $data[0]['updationDate']; // Assuming you want the first date
    $updationYear = date('Y', strtotime($updationDate));
    $updationMonth = date('n', strtotime($updationDate));
?>
                                        <tr class="hover:bg-white/5 transition-colors border-b border-white/5 last:border-0">
                                            <td class="py-3 px-4"><?php echo htmlentities($cnt);?></td>
                                            <td class="py-3 px-4 font-bold text-lg text-white"><?php echo htmlentities($adjustedYearDifference);?>-Semester</td>
                                            <td class="py-3 px-4 text-gray-400"><?php echo htmlentities($updationDate);?></td>
                                            <td class="py-3 px-4">
                                                <div class="flex gap-2">
                                                    <a href="viewgrades.php?studentid=<?php echo $studentid; ?>&year=<?php echo $updationYear; ?>&month=<?php echo $updationMonth; ?>" class="inline-flex items-center gap-2 px-3 py-1.5 bg-primary hover:bg-primary-dark text-white text-xs font-semibold rounded-lg shadow-md transition-all">
                                                        <i class="fa-solid fa-eye"></i> View
                                                    </a>
                                                    <a href="download-result.php?studentid=<?php echo $studentid; ?>&year=<?php echo $updationYear; ?>&month=<?php echo $updationMonth; ?>" class="inline-flex items-center gap-2 px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold rounded-lg shadow-md transition-all">
                                                        <i class="fa-solid fa-download"></i> Download
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
<?php $cnt++; } ?>
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
                    "lengthChange": false,
                    "searching": true,
                    "ordering": true,
                    "info": true,
                    "autoWidth": false,
                    "responsive": true,
                    "language": {
                        "search": "_INPUT_",
                        "searchPlaceholder": "Search semesters...",
                    }
                });
            });
        </script>
    </body>
</html>
<?php } ?>
