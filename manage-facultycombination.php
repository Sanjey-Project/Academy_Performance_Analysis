
<?php
session_start();
error_reporting(0);
include('includes/config.php');
if(strlen($_SESSION['alogin'])=="")
    {   
    header("Location: login.php"); 
    }
    else{
 // for activate Subject   	
if(isset($_GET['acid']))
{
$acid=intval($_GET['acid']);
$status=1;
$sql="update facultycombinationdata set status=:status where id=:acid ";
$query = $dbh->prepare($sql);
$query->bindParam(':acid',$acid,PDO::PARAM_STR);
$query->bindParam(':status',$status,PDO::PARAM_STR);
$query->execute();
$msg="Faculty Activated successfully";
}

 // for Deactivate Subject
if(isset($_GET['did']))
{
$did=intval($_GET['did']);
$status=0;
$sql="update facultycombinationdata set status=:status where id=:did ";
$query = $dbh->prepare($sql);
$query->bindParam(':did',$did,PDO::PARAM_STR);
$query->bindParam(':status',$status,PDO::PARAM_STR);
$query->execute();
$msg="Faculty Deactivated successfully";
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
    	<meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Manage Faculty Combinations | Academic Portal</title>
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
            
            <?php include('includes/topbar.php');?> 
            
            <div class="flex flex-1 pt-16">
                
                <?php include('includes/leftbar.php');?>

                <main class="flex-1 lg:ml-64 p-6 transition-all duration-300">
                    
                    <div class="max-w-7xl mx-auto">
                        
                        <!-- Breadcrumb & Title -->
                        <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4 animate-fade-in">
                            <div>
                                <h1 class="text-3xl font-bold font-heading text-white">Manage Faculty Combinations</h1>
                                <nav class="flex mt-2 text-sm text-gray-400">
                                    <a href="dashboard.php" class="hover:text-primary transition-colors">Home</a>
                                    <span class="mx-2">/</span>
                                    <span class="text-gray-200">Faculty Combinations</span>
                                </nav>
                            </div>
                        </div>

                         <!-- Alerts -->
                        <?php if($msg){?>
                            <div class="bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 px-4 py-3 rounded-lg mb-6 flex items-center animate-slide-up" role="alert">
                                <i class="fa-solid fa-circle-check mr-2"></i>
                                <strong>Well done!</strong> <span class="ml-2"><?php echo htmlentities($msg); ?></span>
                            </div>
                        <?php } else if($error){?>
                            <div class="bg-red-500/10 border border-red-500/20 text-red-400 px-4 py-3 rounded-lg mb-6 flex items-center animate-slide-up" role="alert">
                                <i class="fa-solid fa-circle-exclamation mr-2"></i>
                                <strong>Oh snap!</strong> <span class="ml-2"><?php echo htmlentities($error); ?></span>
                            </div>
                        <?php } ?>

                        <!-- Content Card -->
                        <div class="bg-surface border border-white/10 rounded-2xl p-6 shadow-xl animate-slide-up">
                            <h2 class="text-xl font-bold font-heading mb-4 border-b border-white/10 pb-2">Faculty Combinations List</h2>
                            
                            <div class="overflow-x-auto">
                                <table id="example" class="w-full text-left border-collapse">
                                    <thead>
                                        <tr>
                                            <th class="py-3 px-4 font-semibold text-sm uppercase tracking-wider text-gray-400">#</th>
                                            <th class="py-3 px-4 font-semibold text-sm uppercase tracking-wider text-gray-400">Faculty Name</th>
                                            <th class="py-3 px-4 font-semibold text-sm uppercase tracking-wider text-gray-400">Class & Section</th>
                                            <th class="py-3 px-4 font-semibold text-sm uppercase tracking-wider text-gray-400">Subject</th>
                                            <th class="py-3 px-4 font-semibold text-sm uppercase tracking-wider text-gray-400">Status</th>
                                            <th class="py-3 px-4 font-semibold text-sm uppercase tracking-wider text-gray-400">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody class="text-sm">
<?php $sql = "SELECT facultydata.FacultyName,classdata.ClassNameNumeric,classdata.ClassName,classdata.Section,subjectdata.SubjectName,facultycombinationdata.id as fcid,facultycombinationdata.status from facultycombinationdata join classdata on classdata.id=facultycombinationdata.ClassId  join subjectdata on subjectdata.id=facultycombinationdata.SubjectId join facultydata on facultydata.id=facultycombinationdata.FacultyId";
$query = $dbh->prepare($sql);
$query->execute();
$results=$query->fetchAll(PDO::FETCH_OBJ);
$cnt=1;
if($query->rowCount() > 0)
{
foreach($results as $result)
{   ?>
                                        <tr class="hover:bg-white/5 transition-colors border-b border-white/5 last:border-0">
                                            <td class="py-3 px-4"><?php echo htmlentities($cnt);?></td>
                                            <td class="py-3 px-4 font-medium"><?php echo htmlentities($result->FacultyName);?></td>
                                            <td class="py-3 px-4"><?php echo htmlentities($result->ClassName);?> &nbsp; <?php echo htmlentities($result->ClassNameNumeric);?> &nbsp; Section-<?php echo htmlentities($result->Section);?></td>
                                            <td class="py-3 px-4"><?php echo htmlentities($result->SubjectName);?></td>
                                            <td class="py-3 px-4">
                                                <?php $stts=$result->status;
                                                if($stts=='0') { ?>
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-500/10 text-red-500 border border-red-500/20">Inactive</span>
                                                <?php } else { ?>
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-500/10 text-emerald-500 border border-emerald-500/20">Active</span>
                                                <?php } ?>
                                            </td>
                                            <td class="py-3 px-4">
                                                <?php if($stts=='0') { ?>
                                                    <a href="manage-facultycombination.php?acid=<?php echo htmlentities($result->fcid);?>" onclick="return confirm('Do you really want to activate this combination?');" class="text-emerald-400 hover:text-emerald-300 transition-colors" title="Activate">
                                                        <i class="fa-solid fa-check"></i>
                                                    </a>
                                                <?php } else { ?>
                                                    <a href="manage-facultycombination.php?did=<?php echo htmlentities($result->fcid);?>" onclick="return confirm('Do you really want to deactivate this combination?');" class="text-red-400 hover:text-red-300 transition-colors" title="Deactivate">
                                                        <i class="fa-solid fa-xmark"></i>
                                                    </a>
                                                <?php } ?>
                                            </td>
                                        </tr>
<?php $cnt=$cnt+1;}} ?>
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
                        "searchPlaceholder": "Search faculty combinations...",
                    }
                });
            });
        </script>
    </body>
</html>
<?php } ?>
