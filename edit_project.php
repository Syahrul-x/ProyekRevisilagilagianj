<?php
session_start();
require_once 'classes/Database.php';
require_once 'classes/Project.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'initiator') {
    header("Location: index.php");
    exit();
}

$project = new Project();
$projectData = $project->getProject($_GET['id'], $_SESSION['user_id']);

if (!$projectData) {
    header("Location: my_projects.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $updateData = [
        'projectName' => $_POST['projectName'],
        'projectDescription' => $_POST['projectDescription'],
        'detailed_description' => $_POST['detailed_description'],
        'sdgTag' => $_POST['sdgTag'],
        'contact_email' => $_POST['contact_email'],
        'contact_phone' => $_POST['contact_phone']
    ];

    $image = isset($_FILES["projectImage"]) && $_FILES["projectImage"]["size"] > 0 ? $_FILES["projectImage"] : null;

    if ($project->update($_GET['id'], $_SESSION['user_id'], $updateData, $image)) {
        header("Location: my_projects.php");
        exit();
    } else {
        echo "Error updating project.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Project - SDGs Connect</title>
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
    <div class="max-w-2xl mx-auto px-4 py-8">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white mb-6">Edit Project</h1>
            
            <form action="" method="post" enctype="multipart/form-data" class="space-y-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Project Name
                    </label>
                    <input type="text" 
                           name="projectName" 
                           value="<?php echo htmlspecialchars($projectData['NAMA']); ?>" 
                           required
                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary dark:bg-gray-700 dark:text-white">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Short Description
                    </label>
                    <textarea name="projectDescription" 
                              required
                              class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary dark:bg-gray-700 dark:text-white"><?php echo htmlspecialchars($projectData['DESKRIPSI']); ?></textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Detailed Description
                    </label>
                    <textarea name="detailed_description" 
                              required
                              class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary dark:bg-gray-700 dark:text-white"><?php echo htmlspecialchars($projectData['DESKRIPSI_DETAIL']); ?></textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        SDG Tag
                    </label>
                    <select name="sdgTag" 
                            required
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary dark:bg-gray-700 dark:text-white">
                        <option value="" disabled selected>Select SDG Tag</option>
                        <option value="1">SDG 1</option>
                        <option value="2">SDG 2</option>
                        <option value="3">SDG 3</option>
                        <option value="4">SDG 4</option>
                        <option value="5">SDG 5</option>
                        <option value="6">SDG 6</option>
                        <option value="7">SDG 7</option>
                        <option value="8">SDG 8</option>
                        <option value="9">SDG 9</option>
                        <option value="10">SDG 10</option>
                        <option value="11">SDG 11</option>
                        <option value="12">SDG 12</option>
                        <option value="13">SDG 13</option>
                        <option value="14">SDG 14</option>
                        <option value="15">SDG 15</option>
                        <option value="16">SDG 16</option>
                        <option value="17">SDG 17</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Project Image
                    </label>
                    <input type="file" 
                           name="projectImage" 
                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary dark:bg-gray-700 dark:text-white">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Contact Email
                    </label>
                    <input type="email" 
                           name="contact_email" 
                           value="<?php echo htmlspecialchars($projectData['contact_email']); ?>" 
                           required
                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary dark:bg-gray-700 dark:text-white">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Contact Phone
                    </label>
                    <input type="tel" 
                           name="contact_phone" 
                           value="<?php echo htmlspecialchars($projectData['contact_phone']); ?>" 
                           required
                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary dark:bg-gray-700 dark:text-white">
                </div>

                <div>
                    <button type="submit" 
                            class="w-full px-3 py-2 bg-primary text-white rounded-lg hover:bg-primary-focus focus:outline-none focus:ring-2 focus:ring-primary">
                        Update Project
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>
</html> 