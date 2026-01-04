<?php
if(isset($_SESSION['StudentId'])) {
    $id = $_SESSION['StudentId'];
    $getNameQuery = $dbh->prepare("SELECT StudentName FROM studentdata WHERE StudentId = :id");
    $getNameQuery->bindParam(':id', $id, PDO::PARAM_INT);
    $getNameQuery->execute();
    $row = $getNameQuery->fetch(PDO::FETCH_ASSOC);
    $studentname = $row ? $row['StudentName'] : "Unknown";
}
?>
<aside class="fixed left-0 top-16 h-[calc(100vh-4rem)] w-64 bg-surface border-r border-white/10 overflow-y-auto transition-transform duration-300 -translate-x-full lg:translate-x-0 z-40" id="sidebar">
    
    <!-- User Profile Summary -->
    <div class="p-6 border-b border-white/5">
        <div class="flex items-center gap-3">
             <img src="images/profile-placeholder.png" onerror="this.src='https://ui-avatars.com/api/?name=<?php echo $studentname; ?>&background=random'" alt="User" class="w-10 h-10 rounded-full border-2 border-pink-500/20">
             <div>
                 <h6 class="text-sm font-semibold text-white whitespace-nowrap overflow-hidden text-ellipsis w-32"><?php echo htmlentities($studentname); ?></h6>
                 <span class="text-xs text-green-400 flex items-center gap-1"><span class="w-1.5 h-1.5 rounded-full bg-green-500"></span> Online</span>
             </div>
        </div>
    </div>

    <!-- Navigation -->
    <nav class="p-4 space-y-1">
        
        <p class="px-4 text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2 mt-2">Main</p>
        <a href="dashboardstudent.php" class="flex items-center gap-3 px-4 py-3 text-gray-300 hover:bg-white/5 hover:text-pink-400 rounded-lg transition-colors group">
            <i class="fa-solid fa-gauge-high text-lg group-hover:scale-110 transition-transform"></i>
            <span class="font-medium">Dashboard</span>
        </a>

        <p class="px-4 text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2 mt-6">Results</p>
        
        <a href="semester.php" class="flex items-center gap-3 px-4 py-3 text-gray-300 hover:bg-white/5 hover:text-pink-400 rounded-lg transition-colors group">
            <i class="fa-solid fa-chart-bar text-lg group-hover:scale-110 transition-transform"></i>
            <span class="font-medium">Semester Results</span>
        </a>

        <a href="mark.php" class="flex items-center gap-3 px-4 py-3 text-gray-300 hover:bg-white/5 hover:text-pink-400 rounded-lg transition-colors group">
            <i class="fa-solid fa-file-invoice text-lg group-hover:scale-110 transition-transform"></i>
            <span class="font-medium">Marks Sheet</span>
        </a>

    </nav>
</aside>

<script>
    // Mobile sidebar toggle logic
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