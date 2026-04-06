<?php
declare(strict_types=1);
define('BASE_PATH', dirname(__DIR__));

require_once BASE_PATH . '/app/config.php';
require_once BASE_PATH . '/app/core/Database.php';
require_once BASE_PATH . '/app/core/helpers.php';
require_once BASE_PATH . '/app/core/Auth.php';
require_once BASE_PATH . '/app/core/Security.php';
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
require_once BASE_PATH . '/app/controllers/AdminControllers.php';
require_once BASE_PATH . '/app/controllers/ReportController.php';
require_once BASE_PATH . '/app/controllers/CalendarController.php';
require_once BASE_PATH . '/app/controllers/JournalController.php';
require_once BASE_PATH . '/app/controllers/NotificationController.php';
require_once BASE_PATH . '/app/controllers/AnnouncementController.php';
require_once BASE_PATH . '/app/controllers/ExportController.php';
require_once BASE_PATH . '/app/controllers/AcademicYearController.php';
require_once BASE_PATH . '/app/controllers/PromotionController.php';
require_once BASE_PATH . '/app/controllers/DiscussionController.php';
require_once BASE_PATH . '/app/controllers/SppController.php';

Auth::start();
$router = new Router();
require_once BASE_PATH . '/routes/web.php';
$router->dispatch();
