<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Academic Performance Analysis | Welcome</title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&family=Poppins:wght@500;600;700&display=swap" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="js/tailwind-config.js"></script>
    <style>
        .glass-card {
            background: rgba(30, 41, 59, 0.4);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        .glass-card:hover {
            background: rgba(30, 41, 59, 0.6);
            border: 1px solid rgba(255, 255, 255, 0.2);
            transform: translateY(-5px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
    </style>
</head>
<body class="bg-darker text-white font-sans min-h-screen flex flex-col relative overflow-hidden">
    
    <!-- Background Elements -->
    <div class="absolute top-0 left-0 w-full h-full overflow-hidden -z-10">
        <div class="absolute top-[-10%] right-[-5%] w-[500px] h-[500px] bg-primary/20 rounded-full blur-[100px]"></div>
        <div class="absolute bottom-[-10%] left-[-10%] w-[600px] h-[600px] bg-secondary/10 rounded-full blur-[120px]"></div>
    </div>

    <!-- Main Content -->
    <main class="flex-grow flex flex-col items-center justify-center container mx-auto px-4 py-12 animate-fade-in">
        
        <!-- Hero Text -->
        <div class="text-center mb-16 max-w-3xl">
            <h1 class="text-4xl md:text-6xl font-bold font-heading mb-6 leading-tight bg-clip-text text-transparent bg-gradient-to-r from-white to-gray-400">
                Academic Performance Analysis System
            </h1>
            <p class="text-gray-400 text-lg md:text-xl">
                Streamlining academic data management for students, faculty, and departments.
            </p>
        </div>

        <!-- Login Cards Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 w-full max-w-6xl animate-slide-up">
            
            <!-- Admin Card -->
            <a href="adminlogin.php" class="glass-card rounded-2xl p-8 flex flex-col items-center text-center transition-all duration-300 group">
                <div class="w-16 h-16 rounded-full bg-primary/20 flex items-center justify-center mb-6 group-hover:bg-primary/30 transition-colors">
                    <i class="fa-solid fa-user-shield text-2xl text-primary group-hover:scale-110 transition-transform"></i>
                </div>
                <h3 class="text-xl font-bold font-heading mb-2">Admin</h3>
                <p class="text-sm text-gray-400 mb-6">Manage system settings and users</p>
                <span class="text-primary font-medium group-hover:translate-x-1 transition-transform inline-flex items-center">
                    Login <i class="fa-solid fa-arrow-right ml-2 text-xs"></i>
                </span>
            </a>

            <!-- Department Head Card -->
            <a href="departmentheadlogin.php" class="glass-card rounded-2xl p-8 flex flex-col items-center text-center transition-all duration-300 group">
                <div class="w-16 h-16 rounded-full bg-emerald-500/20 flex items-center justify-center mb-6 group-hover:bg-emerald-500/30 transition-colors">
                    <i class="fa-solid fa-building-columns text-2xl text-emerald-400 group-hover:scale-110 transition-transform"></i>
                </div>
                <h3 class="text-xl font-bold font-heading mb-2">Department</h3>
                <p class="text-sm text-gray-400 mb-6">Departmental oversight & reports</p>
                <span class="text-emerald-400 font-medium group-hover:translate-x-1 transition-transform inline-flex items-center">
                    Login <i class="fa-solid fa-arrow-right ml-2 text-xs"></i>
                </span>
            </a>

             <!-- Faculty Card -->
             <a href="facultylogin.php" class="glass-card rounded-2xl p-8 flex flex-col items-center text-center transition-all duration-300 group">
                <div class="w-16 h-16 rounded-full bg-secondary/20 flex items-center justify-center mb-6 group-hover:bg-secondary/30 transition-colors">
                    <i class="fa-solid fa-chalkboard-user text-2xl text-secondary group-hover:scale-110 transition-transform"></i>
                </div>
                <h3 class="text-xl font-bold font-heading mb-2">Faculty</h3>
                <p class="text-sm text-gray-400 mb-6">Enter marks and track progress</p>
                <span class="text-secondary font-medium group-hover:translate-x-1 transition-transform inline-flex items-center">
                    Login <i class="fa-solid fa-arrow-right ml-2 text-xs"></i>
                </span>
            </a>

            <!-- Student Card -->
            <a href="studentlogin.php" class="glass-card rounded-2xl p-8 flex flex-col items-center text-center transition-all duration-300 group">
                <div class="w-16 h-16 rounded-full bg-pink-500/20 flex items-center justify-center mb-6 group-hover:bg-pink-500/30 transition-colors">
                    <i class="fa-solid fa-graduation-cap text-2xl text-pink-400 group-hover:scale-110 transition-transform"></i>
                </div>
                <h3 class="text-xl font-bold font-heading mb-2">Student</h3>
                <p class="text-sm text-gray-400 mb-6">View results and performance</p>
                <span class="text-pink-400 font-medium group-hover:translate-x-1 transition-transform inline-flex items-center">
                    Login <i class="fa-solid fa-arrow-right ml-2 text-xs"></i>
                </span>
            </a>

        </div>
    </main>

    <!-- Footer -->
    <footer class="py-6 text-center text-gray-500 text-sm relative z-10">
        <p>&copy; 2024 Academic Performance Analysis System. All rights reserved.</p>
    </footer>

</body>
</html>