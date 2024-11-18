<?php
session_start();
require_once 'classes/Database.php';
require_once 'classes/Project.php';

$project = new Project();
$result = $project->getAllProjects();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SDGs Connect</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        primary: '#0066cc',
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-gray-100 dark:bg-gray-900">
    <!-- Header -->
    <header class="bg-primary shadow-md">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <div class="flex items-center justify-between">
                <!-- Logo -->
                <div class="flex items-center space-x-3">
                    <img src="https://sdgs.un.org/sites/default/files/2020-09/SDG%20Wheel_Transparent_WEB.png" 
                         alt="SDGs Logo" 
                         class="h-8 w-8">
                    <span class="text-white text-xl font-bold">SDGs Connect</span>
                </div>

                <!-- Search -->
                <div class="flex-1 max-w-md mx-4">
                    <div class="relative">
                        <input type="text" 
                               id="searchInput" 
                               placeholder="Search projects..." 
                               class="w-full bg-white/10 text-white placeholder-white/70 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-white/50">
                    </div>
                </div>

                <!-- Modified Navigation -->
                <nav class="flex items-center space-x-4">
                    <?php if (isset($_SESSION['user_id']) && $_SESSION['role'] === 'initiator'): ?>
                        <a href="my_projects.php" class="text-white hover:bg-white/10 px-3 py-2 rounded-lg transition">
                            My Projects
                        </a>
                    <?php endif; ?>
                    
                    <!-- Dark Mode Toggle -->
                    <button id="darkModeToggle" class="text-white hover:bg-white/10 p-2 rounded-lg">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z">
                            </path>
                        </svg>
                    </button>

                    <!-- Modified Profile/Login Section -->
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <!-- Logged in user dropdown -->
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" class="flex items-center text-white hover:bg-white/10 px-3 py-2 rounded-lg">
                                <span class="mr-2"><?php echo htmlspecialchars($_SESSION['username']); ?></span>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                            <div x-show="open" 
                                 @click.away="open = false" 
                                 class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-lg shadow-lg py-1">
                                <a href="logout.php" class="block px-4 py-2 text-gray-800 dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700">
                                    Logout
                                </a>
                            </div>
                        </div>
                    <?php else: ?>
                        <!-- Guest user dropdown -->
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" 
                                    class="flex items-center text-white hover:bg-white/10 px-3 py-2 rounded-lg">
                                <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                          d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                            <div x-show="open" 
                                 @click.away="open = false" 
                                 class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-lg shadow-lg py-1">
                                <a href="login.php" class="block px-4 py-2 text-gray-800 dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700">
                                    Login
                                </a>
                            </div>
                        </div>
                    <?php endif; ?>
                </nav>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php if ($result->num_rows > 0): ?>
                <?php while($project = $result->fetch_assoc()): ?>
                    <div class="project bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden">
                        <img src="<?php echo htmlspecialchars($project['FOTO_PROYEK']); ?>" 
                             alt="<?php echo htmlspecialchars($project['NAMA']); ?>"
                             class="w-full h-48 object-cover">
                        <div class="p-4">
                            <h2 class="text-xl font-semibold text-gray-800 dark:text-white mb-2">
                                <?php echo htmlspecialchars($project['NAMA']); ?>
                            </h2>
                            <p class="text-gray-600 dark:text-gray-300 mb-4">
                                <?php echo htmlspecialchars($project['DESKRIPSI']); ?>
                            </p>
                            <div class="flex items-center text-sm text-gray-500 dark:text-gray-400 mb-2">
                                <span>By: <?php echo htmlspecialchars($project['username']); ?></span>
                            </div>
                            <div class="inline-block bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 text-sm px-2 py-1 rounded mb-4">
                                <?php echo htmlspecialchars($project['SDGTAG']); ?>
                            </div>
                            <a href="project_detail.php?id=<?php echo $project['ID_PROYEK']; ?>" 
                               class="block w-full text-center bg-primary text-white py-2 rounded-lg hover:bg-blue-700 transition">
                                View Details
                            </a>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p class="text-gray-600 dark:text-gray-400">No projects found.</p>
            <?php endif; ?>
        </div>
    </main>

    <!-- Add Project Button - Only show for logged-in initiators -->
    <?php if (isset($_SESSION['user_id']) && $_SESSION['role'] === 'initiator'): ?>
        <!-- Add Project Button -->
        <button onclick="showModal()" 
                class="fixed bottom-8 right-8 bg-primary text-white w-14 h-14 rounded-full shadow-lg flex items-center justify-center hover:bg-blue-700 transition">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
        </button>

        <!-- Add Project Modal -->
        <div id="addProjectModal" 
             class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4">
            <div class="bg-white dark:bg-gray-800 rounded-lg max-w-md w-full p-6">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-semibold text-gray-800 dark:text-white">Add New Project</h2>
                    <button onclick="hideModal()" class="text-gray-500 hover:text-gray-700 dark:text-gray-400">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <form action="upload_project.php" method="post" enctype="multipart/form-data">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Project Name
                            </label>
                            <input type="text" name="projectName" required
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary dark:bg-gray-700 dark:text-white">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Short Description
                            </label>
                            <textarea name="projectDescription" required
                                      class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary dark:bg-gray-700 dark:text-white"></textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Detailed Description
                            </label>
                            <textarea name="detailed_description" required
                                      class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary dark:bg-gray-700 dark:text-white"></textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                SDG Tag
                            </label>
                            <input type="text" name="sdgTag" required
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary dark:bg-gray-700 dark:text-white">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Project Image
                            </label>
                            <input type="file" name="projectImage" accept="image/*" required
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary dark:bg-gray-700 dark:text-white">
                        </div>
                        <button type="submit"
                                class="w-full bg-primary text-white py-2 rounded-lg hover:bg-blue-700 transition">
                            Create Project
                        </button>
                    </div>
                </form>
            </div>
        </div>
    <?php endif; ?>

    <script>
        // Dark mode toggle
        const darkModeToggle = document.getElementById('darkModeToggle');
        const html = document.documentElement;

        darkModeToggle.addEventListener('click', () => {
            html.classList.toggle('dark');
            localStorage.setItem('darkMode', html.classList.contains('dark'));
        });

        // Check for saved dark mode preference
        if (localStorage.getItem('darkMode') === 'true') {
            html.classList.add('dark');
        }

        // Updated search functionality
        const searchInput = document.getElementById('searchInput');
        const projects = document.querySelectorAll('.project');

        searchInput.addEventListener('input', () => {
            const searchTerm = searchInput.value.toLowerCase();
            projects.forEach(project => {
                const title = project.querySelector('h2').textContent.toLowerCase();
                const description = project.querySelector('p').textContent.toLowerCase();
                const sdgTag = project.querySelector('.bg-blue-100').textContent.toLowerCase();
                
                const matches = title.includes(searchTerm) || 
                               description.includes(searchTerm) || 
                               sdgTag.includes(searchTerm);
                
                project.style.display = matches ? 'block' : 'none';
            });
        });

        // Modal functions
        function showModal() {
            document.getElementById('addProjectModal').classList.remove('hidden');
        }

        function hideModal() {
            document.getElementById('addProjectModal').classList.add('hidden');
        }

        // Close modal when clicking outside
        document.getElementById('addProjectModal').addEventListener('click', (e) => {
            if (e.target === e.currentTarget) {
                hideModal();
            }
        });
    </script>
</body>
</html> 