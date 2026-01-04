<?php
if (isset($_SESSION["username"])) {
    $username = $_SESSION["username"];
} else {
    $username = "Admin";
}
?>
<aside class="fixed left-0 top-16 h-[calc(100vh-4rem)] w-64 bg-surface border-r border-white/10 overflow-y-auto transition-transform duration-300 -translate-x-full lg:translate-x-0 z-40" id="sidebar">
    
    <!-- User Profile Summary in Sidebar (Optional) -->
    <div class="p-6 border-b border-white/5">
        <div class="flex items-center gap-3">
             <img src="images/profile-placeholder.png" onerror="this.src='https://ui-avatars.com/api/?name=<?php echo $username; ?>&background=random'" alt="User" class="w-10 h-10 rounded-full border-2 border-primary/20">
             <div>
                 <h6 class="text-sm font-semibold text-white"><?php echo htmlentities($username); ?></h6>
                 <span class="text-xs text-green-400 flex items-center gap-1"><span class="w-1.5 h-1.5 rounded-full bg-green-500"></span> Online</span>
             </div>
        </div>
    </div>

    <!-- Navigation -->
    <nav class="p-4 space-y-1">
        
        <p class="px-4 text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2 mt-2">Main</p>
        <a href="dashboard.php" class="flex items-center gap-3 px-4 py-3 text-gray-300 hover:bg-white/5 hover:text-primary rounded-lg transition-colors group">
            <i class="fa-solid fa-gauge-high text-lg group-hover:scale-110 transition-transform"></i>
            <span class="font-medium">Dashboard</span>
        </a>

        <p class="px-4 text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2 mt-6">Management</p>
        
        <!-- Classes Submenu -->
        <div class="submenu-container">
            <button class="w-full flex items-center justify-between px-4 py-3 text-gray-300 hover:bg-white/5 hover:text-primary rounded-lg transition-colors group focus:outline-none" onclick="toggleSubmenu('classes-menu')">
                <div class="flex items-center gap-3">
                    <i class="fa-solid fa-chalkboard text-lg group-hover:scale-110 transition-transform"></i>
                    <span class="font-medium">Classes</span>
                </div>
                <i class="fa-solid fa-chevron-down text-xs transition-transform duration-200" id="classes-menu-arrow"></i>
            </button>
            <div class="hidden pl-11 pr-2 space-y-1 mt-1" id="classes-menu">
                <a href="create-class.php" class="block py-2 text-sm text-gray-400 hover:text-white transition-colors">Create Class</a>
                <a href="manage-classes.php" class="block py-2 text-sm text-gray-400 hover:text-white transition-colors">Manage Classes</a>
            </div>
        </div>

        <!-- Subjects Submenu -->
        <div class="submenu-container">
            <button class="w-full flex items-center justify-between px-4 py-3 text-gray-300 hover:bg-white/5 hover:text-primary rounded-lg transition-colors group focus:outline-none" onclick="toggleSubmenu('subjects-menu')">
                <div class="flex items-center gap-3">
                    <i class="fa-solid fa-book text-lg group-hover:scale-110 transition-transform"></i>
                    <span class="font-medium">Subjects</span>
                </div>
                <i class="fa-solid fa-chevron-down text-xs transition-transform duration-200" id="subjects-menu-arrow"></i>
            </button>
            <div class="hidden pl-11 pr-2 space-y-1 mt-1" id="subjects-menu">
                <a href="create-subject.php" class="block py-2 text-sm text-gray-400 hover:text-white transition-colors">Create Subject</a>
                <a href="manage-subjects.php" class="block py-2 text-sm text-gray-400 hover:text-white transition-colors">Manage Subjects</a>
                <a href="add-subjectcombination.php" class="block py-2 text-sm text-gray-400 hover:text-white transition-colors">Add Combination</a>
                <a href="manage-subjectcombination.php" class="block py-2 text-sm text-gray-400 hover:text-white transition-colors">Manage Combination</a>
            </div>
        </div>

        <!-- Faculty Submenu -->
         <div class="submenu-container">
            <button class="w-full flex items-center justify-between px-4 py-3 text-gray-300 hover:bg-white/5 hover:text-secondary rounded-lg transition-colors group focus:outline-none" onclick="toggleSubmenu('faculty-menu')">
                <div class="flex items-center gap-3">
                    <i class="fa-solid fa-chalkboard-user text-lg group-hover:scale-110 transition-transform"></i>
                    <span class="font-medium">Faculty</span>
                </div>
                <i class="fa-solid fa-chevron-down text-xs transition-transform duration-200" id="faculty-menu-arrow"></i>
            </button>
            <div class="hidden pl-11 pr-2 space-y-1 mt-1" id="faculty-menu">
                <a href="create-faculty.php" class="block py-2 text-sm text-gray-400 hover:text-white transition-colors">Create Faculty</a>
                <a href="manage-faculty.php" class="block py-2 text-sm text-gray-400 hover:text-white transition-colors">Manage Faculty</a>
                <a href="add-facultycombination.php" class="block py-2 text-sm text-gray-400 hover:text-white transition-colors">Add Combination</a>
                <a href="manage-facultycombination.php" class="block py-2 text-sm text-gray-400 hover:text-white transition-colors">Manage Combination</a>
            </div>
        </div>

         <!-- Students Submenu -->
         <div class="submenu-container">
            <button class="w-full flex items-center justify-between px-4 py-3 text-gray-300 hover:bg-white/5 hover:text-pink-400 rounded-lg transition-colors group focus:outline-none" onclick="toggleSubmenu('students-menu')">
                <div class="flex items-center gap-3">
                    <i class="fa-solid fa-users text-lg group-hover:scale-110 transition-transform"></i>
                    <span class="font-medium">Students</span>
                </div>
                <i class="fa-solid fa-chevron-down text-xs transition-transform duration-200" id="students-menu-arrow"></i>
            </button>
            <div class="hidden pl-11 pr-2 space-y-1 mt-1" id="students-menu">
                <a href="add-students.php" class="block py-2 text-sm text-gray-400 hover:text-white transition-colors">Add Student</a>
                <a href="manage-students.php" class="block py-2 text-sm text-gray-400 hover:text-white transition-colors">Manage Students</a>
            </div>
        </div>

        <!-- Results Submenu -->
        <div class="submenu-container">
            <button class="w-full flex items-center justify-between px-4 py-3 text-gray-300 hover:bg-white/5 hover:text-emerald-400 rounded-lg transition-colors group focus:outline-none" onclick="toggleSubmenu('results-menu')">
                <div class="flex items-center gap-3">
                    <i class="fa-solid fa-poll text-lg group-hover:scale-110 transition-transform"></i>
                    <span class="font-medium">Results</span>
                </div>
                <i class="fa-solid fa-chevron-down text-xs transition-transform duration-200" id="results-menu-arrow"></i>
            </button>
            <div class="hidden pl-11 pr-2 space-y-1 mt-1" id="results-menu">
                <a href="add-result.php" class="block py-2 text-sm text-gray-400 hover:text-white transition-colors">Add Result</a>
                <a href="manage-results.php" class="block py-2 text-sm text-gray-400 hover:text-white transition-colors">Manage Results</a>
            </div>
        </div>

        <p class="px-4 text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2 mt-6">Settings</p>
        
        <a href="change-password.php" class="flex items-center gap-3 px-4 py-3 text-gray-300 hover:bg-white/5 hover:text-orange-400 rounded-lg transition-colors group">
            <i class="fa-solid fa-key text-lg group-hover:scale-110 transition-transform"></i>
            <span class="font-medium">Change Password</span>
        </a>

        <a href="departmentdata.php" class="flex items-center gap-3 px-4 py-3 text-gray-300 hover:bg-white/5 hover:text-cyan-400 rounded-lg transition-colors group">
            <i class="fa-solid fa-fingerprint text-lg group-hover:scale-110 transition-transform"></i>
            <span class="font-medium">Other Logins</span>
        </a>

    </nav>
</aside>

<script>
    function toggleSubmenu(id) {
        const menu = document.getElementById(id);
        const arrow = document.getElementById(id + '-arrow');
        
        if (menu.classList.contains('hidden')) {
            menu.classList.remove('hidden');
            arrow.classList.add('rotate-180');
        } else {
            menu.classList.add('hidden');
            arrow.classList.remove('rotate-180');
        }
    }

    // Mobile sidebar toggle logic
    // This assumes there's a button with id 'sidebarToggle' in the topbar
    // and this script is loaded where that button exists.
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