<?php
declare(strict_types=1);

define('BASE_PATH', dirname(__DIR__));

require_once BASE_PATH . '/app/config.php';
require_once BASE_PATH . '/app/core/Database.php';
require_once BASE_PATH . '/app/core/helpers.php';
require_once BASE_PATH . '/app/core/Auth.php';
require_once BASE_PATH . '/app/core/BaseClasses.php';
require_once BASE_PATH . '/app/core/Router.php';

// Models
require_once BASE_PATH . '/app/models/Models.php';

// Controllers
require_once BASE_PATH . '/app/controllers/AuthController.php';
require_once BASE_PATH . '/app/controllers/DashboardController.php';
require_once BASE_PATH . '/app/controllers/ResourceControllers.php';
require_once BASE_PATH . '/app/controllers/GradeAssignmentControllers.php';
require_once BASE_PATH . '/app/controllers/AttendanceController.php';

// Start
Auth::start();

$router = new Router();
require_once BASE_PATH . '/routes/web.php';
$router->dispatch();
