<?php
session_start();
require_once 'classes/Database.php';
require_once 'classes/Project.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'initiator') {
    header("Location: index.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $project = new Project();
    $projectData = [
        'projectName' => $_POST['projectName'],
        'projectDescription' => $_POST['projectDescription'],
        'detailed_description' => $_POST['detailed_description'],
        'sdgTag' => $_POST['sdgTag'],
        'contact_email' => $_POST['contact_email'],
        'contact_phone' => $_POST['contact_phone']
    ];

    if ($project->create($_SESSION['user_id'], $projectData, $_FILES["projectImage"])) {
        header("Location: my_projects.php");
        exit();
    } else {
        echo "Error creating project.";
    }
}
?> 