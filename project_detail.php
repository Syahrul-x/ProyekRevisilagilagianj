<?php
session_start();
require_once 'classes/Database.php';
require_once 'classes/Project.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$project = new Project();
$project = $project->getProject($_GET['id']);

if (!$project) {
    header("Location: index.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($project['NAMA']); ?> - SDGs Connect</title>
    <script src="https://cdn.tailwindcss.com"></script>
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
    <!-- Header (same as other pages) -->
    <header class="bg-primary shadow-md">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <div class="flex items-center justify-between">
                <a href="index.php" class="flex items-center space-x-3">
                    <img src="https://sdgs.un.org/sites/default/files/2020-09/SDG%20Wheel_Transparent_WEB.png" 
                         alt="SDGs Logo" 
                         class="h-8 w-8">
                    <span class="text-white text-xl font-bold">SDGs Connect</span>
                </a>
                <nav class="flex items-center space-x-4">
                    <?php if ($_SESSION['role'] === 'initiator'): ?>
                        <a href="my_projects.php" class="text-white hover:bg-white/10 px-3 py-2 rounded-lg transition">
                            My Projects
                        </a>
                    <?php endif; ?>
                    <a href="logout.php" class="text-white hover:bg-white/10 px-3 py-2 rounded-lg transition">
                        Logout
                    </a>
                </nav>
            </div>
        </div>
    </header>

    <!-- Project Detail Content -->
    <main class="max-w-4xl mx-auto px-4 py-8">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden">
            <img src="<?php echo htmlspecialchars($project['FOTO_PROYEK']); ?>" 
                 alt="<?php echo htmlspecialchars($project['NAMA']); ?>"
                 class="w-full h-96 object-cover">
            
            <div class="p-6">
                <h1 class="text-3xl font-bold text-gray-800 dark:text-white mb-4">
                    <?php echo htmlspecialchars($project['NAMA']); ?>
                </h1>
                
                <div class="flex items-center text-gray-600 dark:text-gray-400 mb-4">
                    <span class="mr-4">By: <?php echo htmlspecialchars($project['username']); ?></span>
                    <span class="inline-block bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 text-sm px-2 py-1 rounded">
                        <?php echo htmlspecialchars($project['SDGTAG']); ?>
                    </span>
                </div>

                <div class="prose dark:prose-invert max-w-none">
                    <h2 class="text-xl font-semibold text-gray-800 dark:text-white mb-2">Short Description</h2>
                    <p class="text-gray-600 dark:text-gray-300 mb-6">
                        <?php echo nl2br(htmlspecialchars($project['DESKRIPSI'])); ?>
                    </p>

                    <h2 class="text-xl font-semibold text-gray-800 dark:text-white mb-2">Detailed Description</h2>
                    <p class="text-gray-600 dark:text-gray-300 mb-6">
                        <?php echo nl2br(htmlspecialchars($project['DESKRIPSI_DETAIL'])); ?>
                    </p>

                    <h2 class="text-xl font-semibold text-gray-800 dark:text-white mb-2">Contact Information</h2>
                    <div class="text-gray-600 dark:text-gray-300 mb-6">
                        <p class="mb-2">
                            <span class="font-medium">Email:</span> 
                            <a href="mailto:<?php echo htmlspecialchars($project['contact_email']); ?>" 
                               class="text-primary hover:underline">
                                <?php echo htmlspecialchars($project['contact_email']); ?>
                            </a>
                        </p>
                        <p>
                            <span class="font-medium">Phone:</span> 
                            <a href="tel:<?php echo htmlspecialchars($project['contact_phone']); ?>"
                               class="text-primary hover:underline">
                                <?php echo htmlspecialchars($project['contact_phone']); ?>
                            </a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </main>
</body>
</html> 