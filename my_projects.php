<?php
session_start();
require_once 'classes/Database.php';
require_once 'classes/Project.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'initiator') {
    header("Location: index.php");
    exit();
}

$project = new Project();
$result = $project->getUserProjects($_SESSION['user_id']);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Projects - SDGs Connect</title>
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
    <!-- Header (same as index.php) -->
    <header class="bg-primary shadow-md">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <a href="index.php" class="flex items-center space-x-3">
                        <img src="https://sdgs.un.org/sites/default/files/2020-09/SDG%20Wheel_Transparent_WEB.png" 
                             alt="SDGs Logo" 
                             class="h-8 w-8">
                        <span class="text-white text-xl font-bold">SDGs Connect</span>
                    </a>
                </div>
                <nav class="flex items-center space-x-4">
                    <a href="my_projects.php" class="text-white bg-white/10 px-3 py-2 rounded-lg">
                        My Projects
                    </a>
                    <div class="relative" x-data="{ isOpen: false }">
                        <button @click="isOpen = !isOpen" 
                                class="flex items-center text-white hover:bg-white/10 px-3 py-2 rounded-lg">
                            <span class="mr-2"><?php echo htmlspecialchars($_SESSION['username']); ?></span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div x-show="isOpen" 
                             @click.away="isOpen = false"
                             x-transition:enter="transition ease-out duration-100"
                             x-transition:enter-start="transform opacity-0 scale-95"
                             x-transition:enter-end="transform opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-75"
                             x-transition:leave-start="transform opacity-100 scale-100"
                             x-transition:leave-end="transform opacity-0 scale-95"
                             class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-lg shadow-lg py-1">
                            <a href="logout.php" 
                               class="block px-4 py-2 text-gray-800 dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700">
                                Logout
                            </a>
                        </div>
                    </div>
                </nav>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white">My Projects</h1>
            <button onclick="showModal()" 
                    class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                Add New Project
            </button>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php if ($result->num_rows > 0): ?>
                <?php while($project = $result->fetch_assoc()): ?>
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden">
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
                            <div class="inline-block bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 text-sm px-2 py-1 rounded mb-4">
                                <?php echo htmlspecialchars($project['SDGTAG']); ?>
                            </div>
                            <div class="flex space-x-2">
                                <a href="edit_project.php?id=<?php echo $project['ID_PROYEK']; ?>" 
                                   class="flex-1 text-center bg-primary text-white py-2 rounded-lg hover:bg-blue-700 transition">
                                    Edit
                                </a>
                                <button onclick="deleteProject(<?php echo $project['ID_PROYEK']; ?>)" 
                                        class="flex-1 bg-red-600 text-white py-2 rounded-lg hover:bg-red-700 transition">
                                    Delete
                                </button>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="col-span-full text-center py-8">
                    <p class="text-gray-600 dark:text-gray-400">You haven't created any projects yet.</p>
                </div>
            <?php endif; ?>
        </div>
    </main>

    <!-- Add Project Modal (same as in index.php) -->
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
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Contact Email
                        </label>
                        <input type="email" name="contact_email" required
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary dark:bg-gray-700 dark:text-white">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Contact Phone
                        </label>
                        <input type="tel" name="contact_phone" required
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

    <script>
        function deleteProject(projectId) {
            if (confirm('Are you sure you want to delete this project?')) {
                window.location.href = `delete_project.php?id=${projectId}`;
            }
        }

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