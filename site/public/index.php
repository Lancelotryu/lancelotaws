<?php declare(strict_types=1);
//var.php loads some text variables
require_once '../includes/var.php';
//functions.php contains PHP functions
require_once '../includes/functions.php';

//Loading credentials for MySQL database from .env file in private subnet.
load_env('../../.env');

//Connecting to database with poems, translations, etc.
$pdo = new PDO(
    "mysql:host=" . getenv('DB_HOST') . ";dbname=" . getenv('DB_NAME') . ";charset=utf8",
    getenv('DB_USER'),
    getenv('DB_PASSWORD')
);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// 1) Configuration
$allowedLangs    = ['en','fr'];
$allowedSections = ['home','about','skills','projects','contact', 'artwork','poetry','mariup','souven','books'];

// 2) Grab the request path, minus the folder prefix
$scriptDir = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/');  // e.g. "/lancelot"
$uri       = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$path      = preg_replace('#^'.preg_quote($scriptDir, '#').'#', '', $uri);
$segments  = array_values(array_filter(explode('/', $path), 'strlen')); // e.g. ['fr','poetry']

// 3) Determine language
if (isset($segments[0]) && in_array($segments[0], $allowedLangs, true)) {
    $l = $segments[0]; //$l stands for $language. I use this variable a lot, so I use short name.
    array_shift($segments);
} else {// if no prefix, we go to /en/
    $wanted = $segments[0] ?? '';
    if (in_array($wanted, $allowedSections, true)) {
        header("Location: {$scriptDir}/en/{$wanted}", true, 301);
        exit;
    }
    $l = 'en';
}

// 2) Retrieve section from URL
$section = $segments[0] ?? 'home';
if (!in_array($section, $allowedSections, true)) {
    $section = 'home';
}

// 6) Build paths
$basePath    = $scriptDir . '/' . $l . '/'; // "/lancelot/en/"
$contentFile = "data/{$section}.php"; // data/poetry.php, etc.

?><!DOCTYPE html>
<html lang="<?php echo htmlspecialchars($l, ENT_QUOTES, 'UTF-8'); ?>">
<?php include_once '../includes/header.php'; ?>
<body>
  <?php include_once '../includes/nav.php'; ?>
    <main>
        <?php
        // Include the section content or fallback to home
if (file_exists($contentFile)) {include $contentFile;} else {include 'data/home.php';} ?>
    </main>

    <?php include_once '../includes/footer.php'; ?>
</body>
</html>