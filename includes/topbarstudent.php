  <nav class="fixed top-0 left-0 w-full z-50 bg-surface/80 backdrop-blur-md border-b border-white/10 h-16 transition-all duration-300">
            <div class="flex items-center justify-between h-full px-6">
                
                <!-- Logo & Toggle -->
                <div class="flex items-center gap-4">
                    <a href="dashboardstudent.php" class="text-xl font-bold font-heading text-white tracking-wide">
                        Academic<span class="text-pink-500">Student</span>
                    </a>
                    <button id="sidebarToggle" class="text-gray-400 hover:text-white transition-colors lg:hidden">
                        <i class="fa-solid fa-bars text-xl"></i>
                    </button>
                </div>

                <!-- Right Side Actions -->
                <div class="flex items-center gap-6">
                    
                    <!-- Search -->
                    <div class="hidden md:block relative">
                        <input type="text" placeholder="Search..." class="bg-dark/50 border border-gray-700 text-sm rounded-full px-4 py-1.5 focus:outline-none focus:border-pink-500 text-gray-300 w-48 transition-all">
                        <i class="fa-solid fa-search absolute right-3 top-2 text-gray-500 text-xs"></i>
                    </div>

                    <!-- User Actions -->
                    <div class="flex items-center gap-4">
                        <button class="relative text-gray-400 hover:text-white transition-colors">
                            <i class="fa-regular fa-bell"></i>
                            <span class="absolute -top-1 -right-1 w-2 h-2 bg-red-500 rounded-full"></span>
                        </button>

                        <!-- Profile/Logout -->
                        <div class="relative group">
                            <button class="flex items-center gap-2 focus:outline-none">
                                <img src="images/profile-placeholder.png" onerror="this.src='https://ui-avatars.com/api/?name=Student&background=random'" alt="Student" class="w-8 h-8 rounded-full border border-gray-600">
                                <span class="hidden md:block text-sm font-medium text-gray-300 group-hover:text-white transition-colors">Student</span>
                                <i class="fa-solid fa-chevron-down text-xs text-gray-500"></i>
                            </button>
                            
                            <!-- Dropdown -->
                            <div class="absolute right-0 mt-2 w-48 bg-surface border border-white/10 rounded-lg shadow-xl opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 transform origin-top-right">
                                <div class="py-1">
                                    <a href="logout.php" class="block px-4 py-2 text-sm text-red-400 hover:bg-red-500/10 transition-colors">
                                        <i class="fa-solid fa-sign-out-alt mr-2"></i> Logout
                                    </a>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
    </nav>
