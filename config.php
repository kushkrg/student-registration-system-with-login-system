<?php
/* Database configuration
   Change these values if your MySQL username/password/database is different. */
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'admission');

mysqli_report(MYSQLI_REPORT_OFF); // handle connection errors manually (no leaked details)
$con = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

if ($con === false) {
    // Do NOT show mysqli_connect_error() to visitors - it leaks server details.
    die('Database connection failed. Please check config.php and import Database/admission.sql');
}
mysqli_set_charset($con, 'utf8mb4');
