<?php
session_start();
require_once 'classes/Database.php';
require_once 'classes/Project.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'initiator') {
    header("Location: index.php");
    exit();
}

if (isset($_GET['id'])) {
    $project = new Project();
    if ($project->delete($_GET['id'], $_SESSION['user_id'])) {
        header("Location: my_projects.php");
        exit();
    }
}

header("Location: my_projects.php");
exit();
?> 