<?php
// This file handles all assignment-related routes such as add, get, update, delete

// Load the AssignmentController
require_once __DIR__ . '/../controller/AssignmentController.php';

// Create a new instance of the controller
$controller = new AssignmentController();

// Call the controller's method to handle the request
$controller->handleRequest();
