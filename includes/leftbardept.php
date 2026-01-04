<?php
error_reporting(0);
$username ="Unknown";
if (isset($_SESSION['id'])) {
    
    $departmentid = $_SESSION['id'];
    $sql = "SELECT Username FROM departmentdata WHERE id = :departmentid";
    $stmt = $dbh->prepare($sql);
    $stmt->bindParam(':departmentid', $departmentid,PDO::PARAM_INT);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    $username = $user ? $user["Username"] : "Unknown";
} 
?>
<aside class="fixed left-0 top-16 h-[calc(100vh-4rem)] w-64 bg-surface border-r border-white/10 overflow-y-auto transition-transform duration-300 -translate-x-full lg:translate-x-0 z-40" id="sidebar">
    
    <!-- User Profile Summary -->
    <div class="p-6 border-b border-white/5">
        <div class="flex items-center gap-3">
             <img src="images/profile-placeholder.png" onerror="this.src='https://ui-avatars.com/api/?name=<?php echo $username; ?>&background=random'" alt="User" class="w-10 h-10 rounded-full border-2 border-emerald-500/20">
             <div>
                 <h6 class="text-sm font-semibold text-white"><?php echo htmlentities($username); ?></h6>
                 <span class="text-xs text-green-400 flex items-center gap-1"><span class="w-1.5 h-1.5 rounded-full bg-green-500"></span> Online</span>
             </div>
        </div>
    </div>

    <!-- Navigation -->
    <nav class="p-4 space-y-1">
        
        <p class="px-4 text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2 mt-2">Main</p>
        <a href="dashboarddept.php" class="flex items-center gap-3 px-4 py-3 text-gray-300 hover:bg-white/5 hover:text-emerald-400 rounded-lg transition-colors group">
            <i class="fa-solid fa-gauge-high text-lg group-hover:scale-110 transition-transform"></i>
            <span class="font-medium">Dashboard</span>
        </a>

        <p class="px-4 text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2 mt-6">Results</p>
        
        <a href="studentwise.php" class="flex items-center gap-3 px-4 py-3 text-gray-300 hover:bg-white/5 hover:text-emerald-400 rounded-lg transition-colors group">
            <i class="fa-solid fa-user-graduate text-lg group-hover:scale-110 transition-transform"></i>
            <span class="font-medium">Students Wise</span>
        </a>

        <a href="classwise.php" class="flex items-center gap-3 px-4 py-3 text-gray-300 hover:bg-white/5 hover:text-emerald-400 rounded-lg transition-colors group">
            <i class="fa-solid fa-chalkboard-user text-lg group-hover:scale-110 transition-transform"></i>
            <span class="font-medium">Class Wise</span>
        </a>

        <a href="facultywise.php" class="flex items-center gap-3 px-4 py-3 text-gray-300 hover:bg-white/5 hover:text-emerald-400 rounded-lg transition-colors group">
            <i class="fa-solid fa-person-chalkboard text-lg group-hover:scale-110 transition-transform"></i>
            <span class="font-medium">Faculty Wise</span>
        </a>

        <a href="subjectwise.php" class="flex items-center gap-3 px-4 py-3 text-gray-300 hover:bg-white/5 hover:text-emerald-400 rounded-lg transition-colors group">
            <i class="fa-solid fa-book-open text-lg group-hover:scale-110 transition-transform"></i>
            <span class="font-medium">Subject Wise</span>
        </a>

    </nav>
</aside>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const toggleBtn = document.getElementById('sidebarToggle');
        const sidebar = document.getElementById('sidebar');
        
        if(toggleBtn && sidebar) {
            toggleBtn.addEventListener('click', () => {
                sidebar.classList.toggle('-translate-x-full');
            });
        }
    });
</script>