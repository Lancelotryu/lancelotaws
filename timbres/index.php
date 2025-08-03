<?php declare(strict_types=1);
require_once 'functions.php';
load_env('../.env');
$pdo = new PDO(
    "mysql:host=" . getenv('DB_HOST') . ";dbname=" . getenv('DB_NAME') . ";charset=utf8",
    getenv('DB_USER'),
    getenv('DB_PASSWORD')
);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$uri       = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$segments  = array_values(array_filter(explode('/', $uri), 'strlen'));
if (isset($segments[0])) {$l = $segments[0];} else {$l = 'en';}
$basePath    = $l . '/';
$stmt = $pdo->prepare("
    SELECT `key`, content
    FROM translations
    WHERE lang = :lang
");
$stmt->execute([':lang' => $l]);
$t = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);

$allowedLangs = ['en', 'fr']; 
$scriptDir = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/');
$uri       = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$path      = preg_replace('#^' . preg_quote($scriptDir, '#') . '#', '', $uri);
$segments  = array_values(array_filter(explode('/', $path), 'strlen'));

if (isset($segments[0]) && in_array($segments[0], $allowedLangs, true)) {
    $l = $segments[0];
    array_shift($segments);
} else {
    header("Location: /en/", true, 301);
    exit;
}
?>

<!DOCTYPE html>
<html lang="<?php echo htmlspecialchars($l, ENT_QUOTES, 'UTF-8'); ?>">
<head>
<base href="/">
<!--- Meta Website --->
<meta charset="utf-8">
<meta content="width=device-width, initial-scale=1.0" name="viewport">
<title><?php traduire('tbr00title.001.h1'); echo " ";
traduire('tbr00title.002.stt'); ?></title>
<meta name="description" content="
<?php traduire('tbr00title.001.h1'); echo " ";
traduire('tbr00title.002.stt'); ?>">
<meta name="author" content="F. de Lancelot">
<meta name="keywords" content="religion architecture">
<meta name="robots" content="index, follow">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=EB+Garamond:ital,wght@0,400;0,600;1,400&family=Playfair+Display:wght@500;700&display=swap" rel="stylesheet">
<link href="https://unpkg.com/aos@2.003.1/dist/aos.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/aos@2.003.04/dist/aos.css" rel="stylesheet">
<link href="style.css" rel="stylesheet">
<link href="favicon.png" rel="icon">
<link href="apple-touch-icon.png" rel="apple-touch-icon">
<link rel="canonical" href="<?php echo 'https://timbres.fdelancelot.com/' . $l . '/'; ?>">
</head>
<body>
<main>



<div class="container">
  <div class="text-center">
    <h2 class="mb-4">Choisissez votre langue</h2>
    <div class="d-flex justify-content-center gap-3">
      <a href="timbres.fdelancelot.com/fr/" class="btn btn-primary btn-lg">🇫🇷 Français</a>
      <a href="timbres.fdelancelot.com/en/" class="btn btn-outline-primary btn-lg">🇬🇧 English</a>
    </div>
  </div>

<div class="row">
  <div class="col p-3">
<?php t('tbr00title.001.h1'); ?>
<?php t('tbr00title.002.stt'); ?>
<?php t('tbr00title.003.ttauteur'); ?>
  </div>
</div>
<?php t('tbr00title.004.h2'); ?>
<div class="row">
  <div class="col-md-8 p-3">
<?php t('tbr01word.001.commentaire'); ?>
<?php t('tbr01word.002.commentaire'); ?>
<?php t('tbr01word.003.commentaire'); ?>
<?php t('tbr01word.004.commentaire'); ?>
<?php t('tbr01word.005.commentaire'); ?>
<?php t('tbr01word.006.commentaire'); ?>
<?php t('tbr01word.007.commentaire'); ?>
<?php t('tbr01word.008.commentaire'); ?>
  </div>
  <div class="col-md-4 p-3">
<?php t('tbr01word.009.img'); ?>
<?php t('tbr01word.010.legende'); ?>
<?php t('tbr01word.011.img'); ?>
<?php t('tbr01word.012.legende'); ?>
  </div>
</div>
<?php t('tbr10title.005.h2'); ?>
<div class="row">
  <div class="col-md-9 p-3">
<?php t('tbr11intro.001.h3'); ?>
<?php t('tbr11intro.002.p'); ?>
  </div>
  <div class="col-md-3 p-3">
<?php t('tbr11intro.003.img'); ?>
<?php t('tbr11intro.004.legende'); ?>
  </div>
</div>
<div class="row">
  <div class="col-md-4 p-3">
<?php t('tbr11intro.005.img'); ?>
<?php t('tbr11intro.006.legende'); ?>
  </div>
  <div class="col-md-4 p-3">
<?php t('tbr11intro.007.img'); ?>
<?php t('tbr11intro.008.legende'); ?>
  </div>
  <div class="col-md-4 p-3">
<?php t('tbr11intro.009.img'); ?>
<?php t('tbr11intro.010.legende'); ?>
  </div>
</div>
<div class="row">
  <div class="col-md-4 p-3">
<?php t('tbr11intro.015.p'); ?>
  </div>
  <div class="col-md-8 p-3">
<?php t('tbr11intro.012.img'); ?>
<?php t('tbr11intro.013.legende'); ?>
<?php t('tbr11intro.014.legende'); ?>
  </div>
</div>
<div class="row">
  <div class="col-md-4 p-3">
<?php t('tbr11intro.018.img'); ?>
<?php t('tbr11intro.019.legende'); ?>
  </div>
  <div class="col-md-8 p-3">
<?php t('tbr11intro.016.citation'); ?>
<?php t('tbr11intro.017.auteur'); ?>
  </div>
</div>
<div class="row">
  <div class="col-md-6 offset-md-md-3 p-3">
<?php t('tbr11intro.020.p'); ?>
<?php t('tbr11intro.021.p'); ?>
  </div>
</div>
<div class="row">
  <div class="col-md-8 p-3">
<?php t('tbr12rising.001.h3'); ?>
<?php t('tbr12rising.002.p'); ?>
<?php t('tbr12rising.004.p'); ?>
  </div>
  <div class="col-md-4 p-3">
<?php t('tbr12rising.005.img'); ?>
<?php t('tbr12rising.006.legende'); ?>
  </div>
</div>
<div class="row">
  <div class="col-md-8 offset-md-md-2 p-3">
<?php t('tbr12rising.007.img'); ?>
<?php t('tbr12rising.008.legende'); ?>
<?php t('tbr12rising.009.legende'); ?>
<?php t('tbr12rising.010.legende'); ?>
  </div>
</div>
<div class="row">
  <div class="col-md-6 p-3">
<?php t('tbr13radian.001.h3'); ?>
<?php t('tbr13radian.004.p'); ?>
<?php t('tbr13radian.005.p'); ?>
<?php t('tbr13radian.006.p'); ?>
<?php t('tbr13radian.007.p'); ?>
  </div>
  <div class="col-md-6 p-3">
<?php t('tbr13radian.002.citation'); ?>
<?php t('tbr13radian.003.auteur'); ?>
  </div>
</div>
<div class="row">
  <div class="col-md-5 p-3">
<?php t('tbr13radian.013.p'); ?>
    <div class="row">
      <div class="col-md-6 offset-md-md-3 p-3">
<?php t('tbr13radian.014.img'); ?>
<?php t('tbr13radian.015.legende'); ?>
      </div>
    </div>
  </div>
  <div class="col-md-7 p-3">
<?php t('tbr13radian.008.img'); ?>
<?php t('tbr13radian.009.legende'); ?>
<?php t('tbr13radian.010.legende'); ?>
<?php t('tbr13radian.011.legende'); ?>
<?php t('tbr13radian.012.legende'); ?>
  </div>
</div>
<div class="row">
  <div class="col-md-7 p-3">
<?php t('tbr13radian.017.img'); ?>
<?php t('tbr13radian.018.legende'); ?>
<?php t('tbr13radian.019.legende'); ?>
  </div>
  <div class="col-md-5 p-3">
<?php t('tbr13radian.016.p'); ?>
<?php t('tbr13radian.020.p'); ?>
<?php t('tbr13radian.021.p'); ?>
  </div>
</div>
<div class="row">
  <div class="col-md-4 p-3">
<?php t('tbr13radian.022.p'); ?>
<?php t('tbr13radian.023.p'); ?>
  </div>
  <div class="col-md-8 p-3">
<?php t('tbr13radian.024.img'); ?>
  </div>
</div>
<div class="row">
  <div class="col-md-4 p-3">
<?php t('tbr14royal.004.img'); ?>
<?php t('tbr14royal.005.legende'); ?>
  </div>
  <div class="col-md-8 p-3">
<?php t('tbr14royal.001.h3'); ?>
<?php t('tbr14royal.002.p'); ?>
<?php t('tbr14royal.003.p'); ?>
  </div>
</div>
<div class="row">
  <div class="col-md-3 p-3">
<?php t('tbr14royal.007.img'); ?>
<?php t('tbr14royal.008.legende'); ?>
  </div>
  <div class="col-md-9 p-3">
<?php t('tbr14royal.006.p'); ?>
<?php t('tbr14royal.009.p'); ?>
    <div class="row">
      <div class="col-md-6 offset-md-md-3 p-3">
<?php t('tbr14royal.010.img'); ?>
<?php t('tbr14royal.011.legende'); ?>
      </div>
    </div>
<?php t('tbr14royal.012.p'); ?>
  </div>
</div>
<div class="row">
  <div class="col-md-4 p-3">
<?php t('tbr15bene.004.img'); ?>
<?php t('tbr15bene.005.legende'); ?>
  </div>
  <div class="col-md-8 p-3">
<?php t('tbr15bene.001.h3'); ?>
<?php t('tbr15bene.002.p'); ?>
<?php t('tbr15bene.003.p'); ?>
  </div>
</div>
<div class="row">
  <div class="col-md-3 p-3">
<?php t('tbr15bene.011.img'); ?>
<?php t('tbr15bene.012.legende'); ?>
  </div>
  <div class="col-md-9 p-3">
<?php t('tbr15bene.006.p'); ?>
<?php t('tbr15bene.007.p'); ?>
<?php t('tbr15bene.008.p'); ?>
    <div class="row">
      <div class="col-md-6 offset-md-md-3 p-3">
<?php t('tbr15bene.009.img'); ?>
<?php t('tbr15bene.010.legende'); ?>
      </div>
    </div>
  </div>
</div>
<div class="row">
  <div class="col-md-4 p-3">
<?php t('tbr15bene.013.p'); ?>
<?php t('tbr15bene.017.p'); ?>
  </div>
  <div class="col-md-8 p-3">
<?php t('tbr15bene.014.img'); ?>
<?php t('tbr15bene.015.legende'); ?>
<?php t('tbr15bene.016.commentaire'); ?>
  </div>
</div>
<div class="row">
  <div class="col-md-5 p-3">
<?php t('tbr16mendi.004.img'); ?>
<?php t('tbr16mendi.005.legende'); ?>
  </div>
  <div class="col-md-7 p-3">
<?php t('tbr16mendi.001.h3'); ?>
<?php t('tbr16mendi.002.p'); ?>
<?php t('tbr16mendi.003.p'); ?>
  </div>
</div>
<div class="row">
  <div class="col-md-6 p-3">
<?php t('tbr16mendi.006.h4'); ?>
<?php t('tbr16mendi.007.p'); ?>
<?php t('tbr16mendi.008.p'); ?>
    <div class="row">
      <div class="col-md-8 offset-md-md-2">
<?php t('tbr16mendi.009.img'); ?>
<?php t('tbr16mendi.010.legende'); ?>
      </div>
    </div>
<?php t('tbr16mendi.011.p'); ?>
    <div class="row">
      <div class="col-md-8 offset-md-md-2">
<?php t('tbr16mendi.012.img'); ?>
<?php t('tbr16mendi.013.legende'); ?>
      </div>
    </div>
<?php t('tbr16mendi.014.p'); ?>
<?php t('tbr16mendi.015.p'); ?>
  </div>
  <div class="col-md-6 p-3">
<?php t('tbr16mendi.016.img'); ?>
<?php t('tbr16mendi.017.legende'); ?>
<?php t('tbr16mendi.018.commentaire'); ?>
  </div>
</div>
<div class="row">
  <div class="col-md-6 p-3">
<?php t('tbr16mendi.019.h4'); ?>
<?php t('tbr16mendi.020.p'); ?>
<?php t('tbr16mendi.021.p'); ?>
    <div class="row">
      <div class="col-md-8 offset-md-md-2">
<?php t('tbr16mendi.022.img'); ?>
<?php t('tbr16mendi.023.legende'); ?>
      </div>
    </div>
  </div>
  <div class="col-md-6 p-3">
<?php t('tbr16mendi.024.h4'); ?>
<?php t('tbr16mendi.025.p'); ?>
<?php t('tbr16mendi.026.p'); ?>
<?php t('tbr16mendi.027.p'); ?>
    <div class="row">
      <div class="col-md-8 offset-md-md-2">
<?php t('tbr16mendi.028.img'); ?>
<?php t('tbr16mendi.029.legende'); ?>
      </div>
    </div>
  </div>
</div>
<div class="row">
  <div class="col-md-4 p-3">
<?php t('tbr16mendi.030.h4'); ?>
<?php t('tbr16mendi.031.p'); ?>
<?php t('tbr16mendi.032.p'); ?>
<?php t('tbr16mendi.033.p'); ?>
<?php t('tbr16mendi.034.img'); ?>
<?php t('tbr16mendi.035.legende'); ?>
  </div>
  <div class="col-md-8 p-3">
<?php t('tbr16mendi.037.img'); ?>
<?php t('tbr16mendi.038.legende'); ?>
<?php t('tbr16mendi.039.legende'); ?>
<?php t('tbr16mendi.036.p'); ?>
  </div>
</div>
<div class="row">
  <div class="col-md-6 p-3">
<?php t('tbr16mendi.040.h4'); ?>
<?php t('tbr16mendi.041.p'); ?>
<?php t('tbr16mendi.042.p'); ?>
<?php t('tbr16mendi.043.p'); ?>
    <div class="row">
      <div class="col-md-8 offset-md-md-2">
<?php t('tbr16mendi.044.img'); ?>
<?php t('tbr16mendi.045.legende'); ?>
      </div>
    </div>
  </div>
  <div class="col-md-6 p-3">
<?php t('tbr16mendi.046.h4'); ?>
<?php t('tbr16mendi.047.p'); ?>
<?php t('tbr16mendi.048.p'); ?>
<?php t('tbr16mendi.049.p'); ?>
    <div class="row">
      <div class="col-md-6 offset-md-md-3">
<?php t('tbr16mendi.050.img'); ?>
<?php t('tbr16mendi.051.legende'); ?>
      </div>
    </div>
  </div>
</div>
<div class="row"> 
  <div class="col-md-4 p-3">
<?php t('tbr17clergy.001.h3'); ?>
<?php t('tbr17clergy.002.p'); ?>
<?php t('tbr17clergy.003.p'); ?>
<?php t('tbr17clergy.004.p'); ?>
<?php t('tbr17clergy.005.p'); ?>
<?php t('tbr17clergy.008.p'); ?>
<?php t('tbr17clergy.009.p'); ?>
  </div>
  <div class="col-md-8 p-3">
<?php t('tbr17clergy.010.img'); ?>
<?php t('tbr17clergy.011.legende'); ?>
    <div class="row">
      <div class="col-md-7 p-3">
<?php t('tbr17clergy.013.img'); ?>
<?php t('tbr17clergy.014.legende'); ?>
      </div>
      <div class="col-md-5 p-3">
<?php t('tbr17clergy.015.img'); ?>
<?php t('tbr17clergy.016.legende'); ?>
      </div>
    </div>
  </div>
</div>
<div class="row">
  <div class="col-md-8 offset-md-md-2">
<?php t('tbr17clergy.012.img'); ?>
  </div>
</div>
<div class="row"> 
  <div class="col-md-4 p-3">
<?php t('tbr17clergy.017.p'); ?>
<?php t('tbr17clergy.018.p'); ?>
<?php t('tbr17clergy.019.p'); ?>
  </div>
  <div class="col-md-8 p-3">
<?php t('tbr17clergy.020.img'); ?>
<?php t('tbr17clergy.021.legende'); ?>
<?php t('tbr17clergy.022.legende'); ?>
  </div>
</div>
<div class="row"> 
  <div class="col-md-8 offset-md-md-2">
<?php t('tbr17clergy.025.p'); ?>
<?php t('tbr17clergy.026.img'); ?>
<?php t('tbr17clergy.027.legende'); ?>
<?php t('tbr17clergy.028.legende'); ?>
  </div>
</div>
<div class="row"> 
  <div class="col">
<?php t('tbr17clergy.029.p'); ?>
  </div>
</div>
<div class="row p-5"> 
  <div class="col-md-4 p-3">
<?php t('tbr17clergy.023.img'); ?>
<?php t('tbr17clergy.024.legende'); ?>
  </div>
  <div class="col-md-8 p-3">
<?php t('tbr17clergy.030.img'); ?>
<?php t('tbr17clergy.031.legende'); ?>
  </div>
</div>
<div class="row"> 
  <div class="col">
<?php t('tbr18gloss.001.h3'); ?>
  </div>
</div>
<div class="row"> 
  <div class="col-md-4 p-3">
<?php t('tbr18gloss.002.h4'); ?>
<?php t('tbr18gloss.005.p'); ?>
<?php t('tbr18gloss.006.img'); ?>
<?php t('tbr18gloss.007.legende'); ?>
<?php t('tbr18gloss.008.p'); ?>
<?php t('tbr18gloss.009.img'); ?>
<?php t('tbr18gloss.010.legende'); ?>
<?php t('tbr18gloss.011.p'); ?>
<?php t('tbr18gloss.012.p'); ?>
<?php t('tbr18gloss.013.img'); ?>
<?php t('tbr18gloss.014.legende'); ?>
<?php t('tbr18gloss.015.p'); ?>
<?php t('tbr18gloss.016.img'); ?>
<?php t('tbr18gloss.017.legende'); ?>
<?php t('tbr18gloss.031.p'); ?>
<?php t('tbr18gloss.032.img'); ?>
<?php t('tbr18gloss.033.legende'); ?>
<?php t('tbr18gloss.034.p'); ?>
<?php t('tbr18gloss.035.img'); ?>
<?php t('tbr18gloss.036.legende'); ?>
  </div>
  <div class="col-md-8 p-3">
<?php t('tbr18gloss.003.img'); ?>
<?php t('tbr18gloss.004.legende'); ?>
<?php t('tbr18gloss.024.img'); ?>
<?php t('tbr18gloss.025.legende'); ?>
<?php t('tbr18gloss.026.legende'); ?>
  </div>
</div>
<div class="row"> 
  <div class="col-md-8 p-3 offset-md-md-2">
<?php t('tbr18gloss.018.p'); ?>
<?php t('tbr18gloss.019.p'); ?>
<?php t('tbr18gloss.020.img'); ?>
<?php t('tbr18gloss.021.legende'); ?>
<?php t('tbr18gloss.022.legende'); ?>
<?php t('tbr18gloss.023.legende'); ?>
<?php t('tbr18gloss.028.p'); ?>
<?php t('tbr18gloss.029.img'); ?>
<?php t('tbr18gloss.030.legende'); ?>
<?php t('tbr18gloss.037.p'); ?>
<?php t('tbr18gloss.038.img'); ?>
<?php t('tbr18gloss.039.legende'); ?>
    <div class="row">
      <div class="col-md-8">
<?php t('tbr18gloss.044.img'); ?>
      </div>
      <div class="col-md-4">
<?php t('tbr18gloss.051.img'); ?>
<?php t('tbr18gloss.052.legende'); ?>
      </div>
    </div>
<?php t('tbr18gloss.055.img'); ?>
<?php t('tbr18gloss.056.legende'); ?>

<?php t('tbr18gloss.040.p'); ?>
<?php t('tbr18gloss.041.img'); ?>
<?php t('tbr18gloss.042.legende'); ?>
<?php t('tbr18gloss.043.legende'); ?>
  </div>
</div>
<div class="row">
  <div class="col-md-8 p-3 offset-md-2">
<?php t('tbr18gloss.027.h4'); ?>
<?php t('tbr18gloss.045.p'); ?>
<?php t('tbr18gloss.046.p'); ?>
  </div>
</div>
<div class="row">
  <div class="col-md-4 p-3">
<?php t('tbr18gloss.047.img'); ?>
<?php t('tbr18gloss.048.legende'); ?>
  </div>
  <div class="col-md-4 p-3">
<?php t('tbr18gloss.049.img'); ?>
<?php t('tbr18gloss.050.legende'); ?>
  </div>
  <div class="col-md-4 p-3">
<?php t('tbr18gloss.053.img'); ?>
<?php t('tbr18gloss.054.legende'); ?>
  </div>
</div>
<div class="row">
  <div class="col-md-6 p-3">
<?php t('tbr18gloss.057.p'); ?>
<?php t('tbr18gloss.058.p'); ?>
<?php t('tbr18gloss.061.img'); ?>
<?php t('tbr18gloss.062.legende'); ?>
  </div>
  <div class="col-md-3 p-3">
<?php t('tbr18gloss.059.img'); ?>
<?php t('tbr18gloss.060.legende'); ?>
  </div>
  <div class="col-md-3 p-3">
<?php t('tbr18gloss.063.img'); ?>
<?php t('tbr18gloss.064.legende'); ?>
  </div>
</div>
<div class="row">
  <div class="col-md-8 p-3 offset-md-2">
    <div class="row">
      <div class="col-md-6">
<?php t('tbr18gloss.065.p'); ?>
<?php t('tbr18gloss.066.img'); ?>
<?php t('tbr18gloss.067.legende'); ?>
      </div>
      <div class="col-md-6">
<?php t('tbr18gloss.068.img'); ?>
<?php t('tbr18gloss.069.legende'); ?>
<?php t('tbr18gloss.070.img'); ?>
<?php t('tbr18gloss.071.legende'); ?>
      </div>
    </div>
<?php t('tbr18gloss.072.p'); ?>
<?php t('tbr18gloss.073.img'); ?>
<?php t('tbr18gloss.074.legende'); ?>
<?php t('tbr18gloss.075.p'); ?>
<?php t('tbr18gloss.076.p'); ?>
<?php t('tbr18gloss.077.img'); ?>
<?php t('tbr18gloss.078.legende'); ?>
  </div>
</div>
<div class="row">
  <div class="col-md-4 p-3">
<?php t('tbr18gloss.079.p'); ?>
<?php t('tbr18gloss.080.p'); ?>
<?php t('tbr18gloss.083.img'); ?>
<?php t('tbr18gloss.084.legende'); ?>
  </div>
  <div class="col-md-8 p-3">
<?php t('tbr18gloss.081.img'); ?>
<?php t('tbr18gloss.082.legende'); ?>
  </div>
</div>
<div class="row">
  <div class="col-md-8 p-3 offset-md-2">
<?php t('tbr18gloss.085.p'); ?>
<?php t('tbr18gloss.086.img'); ?>
<?php t('tbr18gloss.087.legende'); ?>
<?php t('tbr18gloss.088.legende'); ?>
  </div>
</div>
<div class="row">
  <div class="col-md-5 p-3">
<?php t('tbr18gloss.089.p'); ?>
<?php t('tbr18gloss.090.p'); ?>
<?php t('tbr18gloss.096.p'); ?>
<?php t('tbr18gloss.097.p'); ?>
  </div>
  <div class="col-md-3 p-3">
<?php t('tbr18gloss.091.img'); ?>
<?php t('tbr18gloss.092.legende'); ?>
  </div>
  <div class="col-md-4 p-3">
<?php t('tbr18gloss.100.img'); ?>
<?php t('tbr18gloss.101.legende'); ?>
  </div>
</div>
<div class="row">
  <div class="col-md-8 p-3 offset-md-2">
<?php t('tbr18gloss.093.img'); ?>
<?php t('tbr18gloss.094.legende'); ?>
<?php t('tbr18gloss.095.legende'); ?>
<?php t('tbr18gloss.098.img'); ?>
<?php t('tbr18gloss.099.legende'); ?>
  </div>
</div>
<div class="row">
  <div class="col-md-8 p-3">
<?php t('tbr18gloss.102.p'); ?>
<?php t('tbr18gloss.105.img'); ?>
<?php t('tbr18gloss.106.legende'); ?>
  </div>
  <div class="col-md-2 p-3">
<?php t('tbr18gloss.103.img'); ?>
<?php t('tbr18gloss.104.legende'); ?>
  </div>
  <div class="col-md-2 p-3">
<?php t('tbr18gloss.113.img'); ?>
<?php t('tbr18gloss.114.legende'); ?>  
  </div>
</div>
<div class="row">
  <div class="col-md-8 p-3 offset-md-2">
<?php t('tbr18gloss.107.p'); ?>
<?php t('tbr18gloss.108.img'); ?>
<?php t('tbr18gloss.109.legende'); ?>
<?php t('tbr18gloss.110.legende'); ?>
<?php t('tbr18gloss.111.img'); ?>
<?php t('tbr18gloss.112.legende'); ?>
<?php t('tbr18gloss.115.p'); ?>
<?php t('tbr18gloss.126.img'); ?>
<?php t('tbr18gloss.127.legende'); ?>
<?php t('tbr18gloss.128.legende'); ?>
<?php t('tbr18gloss.116.p'); ?>
<?php t('tbr18gloss.117.p'); ?>
<?php t('tbr18gloss.118.img'); ?>
<?php t('tbr18gloss.122.p'); ?>
<?php t('tbr18gloss.123.p'); ?>
  </div>
</div>
<div class="row">
  <div class="col-md-4 p-3">
<?php t('tbr18gloss.119.img'); ?>
<?php t('tbr18gloss.121.legende'); ?>
  </div>
  <div class="col-md-4 p-3">
<?php t('tbr18gloss.120.img'); ?>
<?php t('tbr18gloss.121.legende'); ?>
  </div>
  <div class="col-md-4 p-3">
<?php t('tbr18gloss.124.img'); ?>
<?php t('tbr18gloss.125.legende'); ?>
  </div>
</div>
<div class="row">
  <div class="col-md-9 p-3">
<?php t('tbr19build.001.h3'); ?>
<?php t('tbr19build.002.p'); ?>
<?php t('tbr19build.003.p'); ?>
<?php t('tbr19build.004.p'); ?>
  </div>
  <div class="col-md-3 p-3">
<?php t('tbr19build.009.img'); ?>
<?php t('tbr19build.010.legende'); ?>
  </div>
</div>
<div class="row">
  <div class="col-md-4 p-3">
<?php t('tbr19build.005.img'); ?>
<?php t('tbr19build.006.legende'); ?>
  </div>
  <div class="col-md-4 p-3">
<?php t('tbr19build.007.img'); ?>
<?php t('tbr19build.008.legende'); ?>
  </div>
  <div class="col-md-4 p-3">
<?php t('tbr19build.011.img'); ?>
<?php t('tbr19build.012.legende'); ?>
  </div>
</div>
<div class="row">
  <div class="col-md-9 p-3">
<?php t('tbr19build.013.p'); ?>
<?php t('tbr19build.014.p'); ?>
<?php t('tbr19build.015.p'); ?>
<?php t('tbr19build.018.p'); ?>
  </div>
  <div class="col-md-3 p-3">
<?php t('tbr19build.019.img'); ?>
<?php t('tbr19build.020.legende'); ?>
  </div>
</div>
<div class="row">
  <div class="col-md-4 p-3">
<?php t('tbr19build.016.img'); ?>
<?php t('tbr19build.017.legende'); ?>
  </div>
  <div class="col-md-4 p-3">
<?php t('tbr19build.021.img'); ?>
<?php t('tbr19build.022.legende'); ?>
  </div>
  <div class="col-md-4 p-3">
<?php t('tbr19build.023.img'); ?>
<?php t('tbr19build.024.legende'); ?>
  </div>
</div>
<div class="row">
  <div class="col-md-8 p-3 offset-md-2">
<?php t('tbr19build.025.p'); ?>
<?php t('tbr19build.026.p'); ?>
<?php t('tbr19build.027.img'); ?>
<?php t('tbr19build.028.legende'); ?>
<?php t('tbr19build.029.p'); ?>
<?php t('tbr19build.030.p'); ?>
<?php t('tbr19build.033.p'); ?>
  </div>
</div>
<div class="row">
  <div class="col-md-4 p-3">
<?php t('tbr19build.031.img'); ?>
<?php t('tbr19build.032.legende'); ?>
  </div>
  <div class="col-md-8 p-3">
<?php t('tbr19build.034.img'); ?>
<?php t('tbr19build.035.legende'); ?>
  </div>
</div>
<div class="row">
  <div class="col-md-4 p-3">
<?php t('tbr19build.036.img'); ?>
<?php t('tbr19build.037.legende'); ?>
  </div>
  <div class="col-md-3 p-3">
<?php t('tbr19build.038.p'); ?>
<?php t('tbr19build.039.img'); ?>
<?php t('tbr19build.040.legende'); ?>
  </div>
  <div class="col-md-5 p-3">
<?php t('tbr19build.041.p'); ?>
<?php t('tbr19build.042.img'); ?>
<?php t('tbr19build.043.legende'); ?>
  </div>
</div>
<div class="row">
  <div class="col-md-4 p-3">
<?php t('tbr19build.044.p'); ?>
<?php t('tbr19build.045.p'); ?>
<?php t('tbr19build.046.img'); ?>
<?php t('tbr19build.047.legende'); ?>
  </div>
  <div class="col-md-4 p-3">
<?php t('tbr19build.048.p'); ?>
<?php t('tbr19build.049.p'); ?>
<?php t('tbr19build.060.p'); ?>
<?php t('tbr19build.061.img'); ?>
<?php t('tbr19build.062.legende'); ?>
  </div>
  <div class="col-md-4 p-3">
<?php t('tbr19build.050.img'); ?>
<?php t('tbr19build.051.legende'); ?>
  </div>
</div>
<div class="row">
  <div class="col-md-4 p-3">
<?php t('tbr19build.054.img'); ?>
<?php t('tbr19build.055.legende'); ?>
  </div>
  <div class="col-md-4 p-3">
<?php t('tbr19build.056.img'); ?>
<?php t('tbr19build.057.legende'); ?>
  </div>
  <div class="col-md-4 p-3">
<?php t('tbr19build.058.img'); ?>
<?php t('tbr19build.059.legende'); ?>
  </div>
</div>
<div class="row">
  <div class="col-md-4 p-3">
<?php t('tbr19build.063.p'); ?>
<?php t('tbr19build.064.p'); ?>
  </div>
  <div class="col-md-8 p-3">
<?php t('tbr19build.067.p'); ?>
  </div>
</div>
<div class="row">
  <div class="col-md-4 p-3">
<?php t('tbr19build.065.img'); ?>
<?php t('tbr19build.066.legende'); ?>
  </div>
  <div class="col-md-4 p-3">
<?php t('tbr19build.068.img'); ?>
<?php t('tbr19build.069.legende'); ?>
  </div>
  <div class="col-md-4 p-3">
<?php t('tbr19build.070.img'); ?>
<?php t('tbr19build.071.legende'); ?>
  </div>
</div>
<div class="row">
  <div class="col-md-5 offset-md-2 p-3">
<?php t('tbr19build.072.p'); ?>
<?php t('tbr19build.073.p'); ?>
<?php t('tbr19build.074.p'); ?>
  </div>
  <div class="col-md-3 p-3">
<?php t('tbr19build.075.img'); ?>
<?php t('tbr19build.076.legende'); ?>
  </div>
</div>
<div class="row">
  <div class="col-md-8 p-3 offset-md-2">
<?php t('tbr19build.077.p'); ?>
<?php t('tbr19build.078.img'); ?>
<?php t('tbr19build.079.legende'); ?>
<?php t('tbr19build.080.legende'); ?>
<?php t('tbr19build.081.legende'); ?>
<?php t('tbr19build.082.p'); ?>
<?php t('tbr19build.083.img'); ?>
<?php t('tbr19build.084.legende'); ?>
<?php t('tbr19build.085.p'); ?>
<?php t('tbr19build.086.img'); ?>
<?php t('tbr19build.087.legende'); ?>
<?php t('tbr19build.088.p'); ?>
<?php t('tbr19build.089.p'); ?>
<?php t('tbr19build.090.img'); ?>
<?php t('tbr19build.091.legende'); ?>
  </div>
</div>
<?php t('tbr20title.006.h2'); ?>
<div class="row">
  <div class="col-md-8 p-3 offset-md-2">
<?php t('tbr21comp.001.h3'); ?>
<?php t('tbr21comp.002.p'); ?>
<?php t('tbr21comp.003.p'); ?>
<?php t('tbr21comp.004.p'); ?>
<?php t('tbr21comp.005.p'); ?>
<?php t('tbr21comp.006.p'); ?>
<?php t('tbr21comp.007.p'); ?>
<?php t('tbr21comp.008.img'); ?>
<?php t('tbr21comp.009.legende'); ?>
<?php t('tbr21comp.010.p'); ?>
<?php t('tbr21comp.011.p'); ?>
  </div>
</div>
<div class="row">
  <div class="col-md-4 p-3">
<?php t('tbr21comp.012.img'); ?>
<?php t('tbr21comp.013.legende'); ?>
  </div>
  <div class="col-md-4 p-3">
<?php t('tbr21comp.014.img'); ?>
<?php t('tbr21comp.015.legende'); ?>
  </div>
  <div class="col-md-4 p-3">
<?php t('tbr21comp.016.img'); ?>
<?php t('tbr21comp.017.legende'); ?>
  </div>
</div>
<div class="row">
  <div class="col-md-4 p-3">
<?php t('tbr21comp.020.img'); ?>
<?php t('tbr21comp.021.legende'); ?>
  </div>
  <div class="col-md-8 p-3">
<?php t('tbr21comp.018.img'); ?>
<?php t('tbr21comp.019.legende'); ?>
  </div>
</div>
<div class="row">
  <div class="col-md-8 offset-md-2 p-3">
<?php t('tbr21comp.022.img'); ?>
<?php t('tbr21comp.023.legende'); ?>
  </div>
</div>
<div class="row">
<div class="col">
<?php t('tbr22turone.001.h3'); ?>
  </div>
</div>
<div class="row">
  <div class="col-md-4 p-3">
<?php t('tbr22turone.002.h4'); ?>
<?php t('tbr22turone.003.p'); ?>
<?php t('tbr22turone.004.p'); ?>
<?php t('tbr22turone.011.p'); ?>
  </div>
  <div class="col-md-4 p-3">
<?php t('tbr22turone.007.img'); ?>
  </div>
  <div class="col-md-4 p-3">
<?php t('tbr22turone.012.img'); ?>
<?php t('tbr22turone.013.legende'); ?>
  </div>
</div>
<div class="row">
  <div class="col-md-3 p-3">
<?php t('tbr22turone.005.img'); ?>
<?php t('tbr22turone.006.legende'); ?>
<?php t('tbr22turone.008.img'); ?>
  </div>
  <div class="col-md-9 p-3">
<?php t('tbr22turone.009.img'); ?>
<?php t('tbr22turone.010.legende'); ?>
  </div>
</div>
<div class="row">
  <div class="col-md-4 p-3">
<?php t('tbr22turone.014.p'); ?>
<?php t('tbr22turone.015.p'); ?>
<?php t('tbr22turone.016.img'); ?>
<?php t('tbr22turone.017.legende'); ?>
<?php t('tbr22turone.021.p'); ?>
<?php t('tbr22turone.022.img'); ?>
<?php t('tbr22turone.023.legende'); ?>
  </div>
  <div class="col-md-4 p-3">
<?php t('tbr22turone.018.p'); ?>
<?php t('tbr22turone.019.img'); ?>
<?php t('tbr22turone.020.legende'); ?>
  </div>
  <div class="col-md-4 p-3">
<?php t('tbr22turone.024.p'); ?>
<?php t('tbr22turone.025.img'); ?>
<?php t('tbr22turone.026.legende'); ?>
  </div>
</div>
<div class="row">
  <div class="col-md-6 p-3">
<?php t('tbr22turone.027.p'); ?>
<?php t('tbr22turone.029.img'); ?>
<?php t('tbr22turone.030.legende'); ?>
<?php t('tbr22turone.031.legende'); ?>
<?php t('tbr22turone.032.img'); ?>
<?php t('tbr22turone.033.legende'); ?>
<?php t('tbr22turone.034.img'); ?>
<?php t('tbr22turone.035.legende'); ?>
  </div>
  <div class="col-md-6 p-3">
<?php t('tbr22turone.028.img'); ?>
  </div>
</div>
<div class="row">
  <div class="col-md-8 offset-md-2 p-3">
<?php t('tbr22turone.036.p'); ?>
<?php t('tbr22turone.037.p'); ?>
<?php t('tbr22turone.040.p'); ?>
<?php t('tbr22turone.041.p'); ?>
<?php t('tbr22turone.042.p'); ?>
  </div>
</div>
<div class="row">
  <div class="col-md-4 offset-md-1 p-3">
<?php t('tbr22turone.038.img'); ?>
<?php t('tbr22turone.039.legende'); ?>
  </div>
  <div class="col-md-4 p-3">
<?php t('tbr22turone.043.img'); ?>
<?php t('tbr22turone.044.legende'); ?>
  </div>
  <div class="col-md-3 p-3">
<?php t('tbr22turone.045.img'); ?>
<?php t('tbr22turone.046.legende'); ?>
  </div>
</div>
<div class="row">
  <div class="col-md-8 offset-md-2 p-3">
<?php t('tbr22turone.047.p'); ?>
<?php t('tbr22turone.048.p'); ?>
<?php t('tbr22turone.049.p'); ?>
<?php t('tbr22turone.050.img'); ?>
<?php t('tbr22turone.051.legende'); ?>
<?php t('tbr22turone.052.p'); ?>
<?php t('tbr22turone.053.img'); ?>
<?php t('tbr22turone.054.legende'); ?>
<?php t('tbr22turone.055.legende'); ?>
<?php t('tbr22turone.056.legende'); ?>
<?php t('tbr22turone.057.p'); ?>
<?php t('tbr22turone.058.h4'); ?>
<?php t('tbr22turone.059.p'); ?>
<?php t('tbr22turone.060.img'); ?>
<?php t('tbr22turone.061.legende'); ?>
<?php t('tbr22turone.062.p'); ?>
<?php t('tbr22turone.063.img'); ?>
<?php t('tbr22turone.064.legende'); ?>
  </div>
</div>
<div class="row">
  <div class="col-md-5 p-3">
<?php t('tbr22turone.065.p'); ?>
<?php t('tbr22turone.066.p'); ?>
<?php t('tbr22turone.067.img'); ?>
<?php t('tbr22turone.068.legende'); ?>
<?php t('tbr22turone.069.p'); ?>
<?php t('tbr22turone.070.img'); ?>
<?php t('tbr22turone.073.p'); ?>
<?php t('tbr22turone.074.img'); ?>
<?php t('tbr22turone.075.legende'); ?>
  </div>
  <div class="col-md-7 p-3">
<?php t('tbr22turone.071.img'); ?>
<?php t('tbr22turone.072.legende'); ?>
  </div>
</div>
<div class="row">
  <div class="col-md-8 offset-md-2 p-3">
<?php t('tbr22turone.076.p'); ?>
<?php t('tbr22turone.077.p'); ?>
<?php t('tbr22turone.078.img'); ?>
<?php t('tbr22turone.079.legende'); ?>
<?php t('tbr22turone.080.p'); ?>
<?php t('tbr22turone.081.img'); ?>
<?php t('tbr22turone.082.legende'); ?>
<?php t('tbr22turone.083.p'); ?>
<?php t('tbr22turone.084.h4'); ?>
<?php t('tbr22turone.085.p'); ?>
<?php t('tbr22turone.099.p'); ?>
<?php t('tbr22turone.086.img'); ?>
<?php t('tbr22turone.087.legende'); ?>
<?php t('tbr22turone.090.p'); ?>
<?php t('tbr22turone.093.img'); ?>
  </div>
</div>
<div class="row">
  <div class="col-md-4 p-3">
<?php t('tbr22turone.096.img'); ?>
<?php t('tbr22turone.097.legende'); ?>
<?php t('tbr22turone.098.legende'); ?>
  </div>
  <div class="col-md-4 p-3">
<?php t('tbr22turone.094.img'); ?>
  </div>
  <div class="col-md-4 p-3">
<?php t('tbr22turone.092.img'); ?>
  </div>
</div>
<div class="row">
  <div class="col-md-7 p-3">
<?php t('tbr22turone.095.img'); ?>
  </div>
  <div class="col-md-2 p-3">
<?php t('tbr22turone.091.img'); ?>
  </div>
  <div class="col-md-3 p-3">
<?php t('tbr22turone.088.img'); ?>
<?php t('tbr22turone.089.legende'); ?>
  </div>
</div>
<div class="row">
  <div class="col-md-8 offset-md-2 p-3">
<?php t('tbr22turone.100.p'); ?>
  </div>
</div>
<div class="row">
  <div class="col-md-3 offset-md-1 p3">
<?php t('tbr22turone.101.img'); ?>
<?php t('tbr22turone.102.legende'); ?>
  </div>
  <div class="col-md-7 p3">
<?php t('tbr22turone.103.img'); ?>
<?php t('tbr22turone.104.legende'); ?>
  </div>
</div>
<div class="row">
  <div class="col-md-8 p3">
<?php t('tbr22turone.105.p'); ?>
<?php t('tbr22turone.106.p'); ?>
<?php t('tbr22turone.107.img'); ?>
<?php t('tbr22turone.108.legende'); ?>
  </div>
  <div class="col-md-4 p3">
<?php t('tbr22turone.109.p'); ?>
<?php t('tbr22turone.110.img'); ?>
<?php t('tbr22turone.111.legende'); ?>
  </div>
</div>
<div class="row">
  <div class="col-md-4 p3">
<?php t('tbr22turone.114.img'); ?>
<?php t('tbr22turone.115.legende'); ?>
  </div>
  <div class="col-md-8 p3">
<?php t('tbr22turone.112.p'); ?>
<?php t('tbr22turone.113.img'); ?>
<?php t('tbr22turone.116.p'); ?>
  </div>
</div>
<div class="row">
  <div class="col-md-4 p3">
<?php t('tbr22turone.117.img'); ?>
<?php t('tbr22turone.118.legende'); ?>
<?php t('tbr22turone.119.legende'); ?>
  </div>
  <div class="col-md-4 p3">
<?php t('tbr22turone.120.img'); ?>
  </div>
  <div class="col-md-4 p3">
<?php t('tbr22turone.125.img'); ?>
  </div>
</div>
<div class="row">
  <div class="col-md-4 offset-md-1 p3">
<?php t('tbr22turone.121.img'); ?>
<?php t('tbr22turone.122.legende'); ?>
  </div>
  <div class="col-md-3 p3">
<?php t('tbr22turone.123.img'); ?>
<?php t('tbr22turone.124.legende'); ?>
  </div>
  <div class="col-md-3 p3">
<?php t('tbr22turone.126.img'); ?>
<?php t('tbr22turone.127.legende'); ?>
  </div>
</div>
<div class="row">
  <div class="col-md-8 offset-md-2 p-3">
<?php t('tbr22turone.128.p'); ?>
<?php t('tbr22turone.129.h4'); ?>
<?php t('tbr22turone.130.p'); ?>
<?php t('tbr22turone.131.p'); ?>
<?php t('tbr22turone.132.img'); ?>
<?php t('tbr22turone.133.legende'); ?>
<?php t('tbr22turone.134.p'); ?>
<?php t('tbr22turone.135.p'); ?>
<?php t('tbr22turone.136.p'); ?>
<?php t('tbr22turone.137.p'); ?>
<?php t('tbr22turone.138.p'); ?>
<?php t('tbr22turone.144.img'); ?>
<?php t('tbr22turone.145.legende'); ?>
<?php t('tbr22turone.139.p'); ?>
    <div class="row">
      <div class="col-md-8">
<?php t('tbr22turone.142.img'); ?>
<?php t('tbr22turone.143.legende'); ?>
      </div>
      <div class="col-md-4">
<?php t('tbr22turone.140.img'); ?>
<?php t('tbr22turone.141.legende'); ?>
      </div>
    </div>
    <div class="row">
      <div class="col-md-6">
<?php t('tbr22turone.146.p'); ?>
<?php t('tbr22turone.147.p'); ?>
<?php t('tbr22turone.148.p'); ?>
      </div>
      <div class="col-md-6">
<?php t('tbr22turone.149.img'); ?>
      </div>
    </div>
<?php t('tbr22turone.150.img'); ?>
<?php t('tbr22turone.151.legende'); ?>
<?php t('tbr22turone.152.p'); ?>
<?php t('tbr22turone.153.p'); ?>
<?php t('tbr22turone.154.img'); ?>
<?php t('tbr22turone.155.legende'); ?>
<?php t('tbr22turone.156.legende'); ?>
<?php t('tbr22turone.157.legende'); ?>
<?php t('tbr22turone.158.p'); ?>
  </div>
</div>
<div class="row">
  <div class="col-md-4 p3">
<?php t('tbr22turone.160.img'); ?>
<?php t('tbr22turone.161.legende'); ?>
  </div>
  <div class="col-md-8 p3">
<?php t('tbr22turone.159.img'); ?>
  </div>
</div>
<div class="row">
  <div class="col-md-4 p3">
<?php t('tbr22turone.163.img'); ?>
  </div>
  <div class="col-md-8 p3">
<?php t('tbr22turone.162.p'); ?>
  </div>
</div>
<div class="row">
  <div class="col-md-8 p3">
<?php t('tbr22turone.164.img'); ?>
<?php t('tbr22turone.165.legende'); ?>
  </div>
  <div class="col-md-4 p3">
<?php t('tbr22turone.166.p'); ?>
<?php t('tbr22turone.167.p'); ?>
<?php t('tbr22turone.168.p'); ?>
  </div>
</div>
<div class="row">
  <div class="col-md-6 p3">
<?php t('tbr22turone.172.img'); ?>
<?php t('tbr22turone.173.img'); ?>
<?php t('tbr22turone.174.img'); ?>
<?php t('tbr22turone.175.legende'); ?>
<?php t('tbr22turone.176.legende'); ?>
    <div class="row">
      <div class="col-md-6">
<?php t('tbr22turone.177.img'); ?>
      </div>
      <div class="col-md-6">
<?php t('tbr22turone.178.img'); ?>
      </div>
    </div>
<?php t('tbr22turone.179.legende'); ?>
  </div>
  <div class="col-md-6 p3">
<?php t('tbr22turone.169.img'); ?>
<?php t('tbr22turone.170.legende'); ?>
<?php t('tbr22turone.171.img'); ?>
  </div>
</div>
<div class="row">
  <div class="col-md-6 p3">
<?php t('tbr22turone.180.h4'); ?>
<?php t('tbr22turone.181.p'); ?>
<?php t('tbr22turone.182.p'); ?>
<?php t('tbr22turone.187.p'); ?>
  </div>
  <div class="col-md-3 p3">
  <?php t('tbr22turone.195.img'); ?>
<?php t('tbr22turone.196.legende'); ?>
  </div>
  <div class="col-md-3 p3">
<?php t('tbr22turone.185.img'); ?>
<?php t('tbr22turone.186.legende'); ?>
  </div>
</div>
<div class="row">
  <div class="col-md-6 p3">
<?php t('tbr22turone.190.img'); ?>
<?php t('tbr22turone.191.legende'); ?>
<?php t('tbr22turone.183.img'); ?>
<?php t('tbr22turone.184.legende'); ?>
  </div>
  <div class="col-md-6 p3">
<?php t('tbr22turone.188.img'); ?>
<?php t('tbr22turone.189.legende'); ?>
  </div>
</div>
<div class="row">
  <div class="col-md-4 p3">
<?php t('tbr22turone.192.p'); ?>
  </div>
  <div class="col-md-4 p3">
<?php t('tbr22turone.193.img'); ?>
<?php t('tbr22turone.194.legende'); ?>
  </div>
  <div class="col-md-4 p3">
<?php t('tbr22turone.197.img'); ?>
<?php t('tbr22turone.198.legende'); ?>
  </div>
</div>
<div class="row">
  <div class="col-md-3 p3">
<?php t('tbr22turone.199.img'); ?>
<?php t('tbr22turone.200.legende'); ?>
  </div>
  <div class="col-md-9 p3">
<?php t('tbr22turone.201.p'); ?>
<?php t('tbr22turone.202.img'); ?>
<?php t('tbr22turone.203.legende'); ?>
  </div>
</div>
<div class="row">
  <div class="col-md-8 p3">
<?php t('tbr22turone.204.p'); ?>
<?php t('tbr22turone.208.p'); ?>
<?php t('tbr22turone.210.img'); ?>
<?php t('tbr22turone.205.p'); ?>
  </div>
  <div class="col-md-4 p3">
<?php t('tbr22turone.206.img'); ?>
<?php t('tbr22turone.207.legende'); ?>
  </div>
</div>
<div class="row">
  <div class="col-md-4 p3">
<?php t('tbr22turone.211.img'); ?>
<?php t('tbr22turone.212.legende'); ?>
  </div>
  <div class="col-md-8 p3">
<?php t('tbr22turone.209.p'); ?>
<?php t('tbr22turone.213.img'); ?>
<?php t('tbr22turone.214.legende'); ?>
  </div>
</div>
<div class="row">
  <div class="col-md-8 offset-md-2 p3">
<?php t('tbr22turone.215.p'); ?>
<?php t('tbr22turone.216.p'); ?>
<?php t('tbr22turone.217.h4'); ?>
<?php t('tbr22turone.218.p'); ?>
<?php t('tbr22turone.219.img'); ?>
<?php t('tbr22turone.220.legende'); ?>
<?php t('tbr22turone.221.p'); ?>
<?php t('tbr22turone.222.img'); ?>
<?php t('tbr22turone.223.legende'); ?>
<?php t('tbr22turone.224.legende'); ?>
  </div>
</div>


<div class="row">
  <div class="col-md-4 p3">
<?php t('tbr22turone.225.img'); ?>
<?php t('tbr22turone.226.legende'); ?>
  </div>
  <div class="col-md-5 p3">
<?php t('tbr22turone.227.p'); ?>
<?php t('tbr22turone.228.commentaire'); ?>
  </div>
  <div class="col-md-3 p3">
<?php t('tbr22turone.229.img'); ?>
<?php t('tbr22turone.230.legende'); ?>
  </div>
</div>
<div class="row">
  <div class="col-md-8 offset-md-2 p3">
<?php t('tbr22turone.231.p'); ?>
<?php t('tbr22turone.232.p'); ?>
<?php t('tbr22turone.233.p'); ?>
<?php t('tbr22turone.234.img'); ?>
<?php t('tbr22turone.235.legende'); ?>
  </div>
</div>
<div class="row">
  <div class="col-md-4 p3">
<?php t('tbr22turone.236.img'); ?>
<?php t('tbr22turone.237.legende'); ?>
  </div>
  <div class="col-md-4 p3">
<?php t('tbr22turone.238.p'); ?>
<?php t('tbr22turone.239.p'); ?>
  </div>
  <div class="col-md-4 p3">
<?php t('tbr22turone.240.img'); ?>
<?php t('tbr22turone.241.legende'); ?>
  </div>
</div>
<div class="row">
  <div class="col-md-6 p3">
<?php t('tbr22turone.242.h4'); ?>
<?php t('tbr22turone.243.p'); ?>
<?php t('tbr22turone.244.p'); ?>
<?php t('tbr22turone.246.img'); ?>
<?php t('tbr22turone.247.img'); ?>
<?php t('tbr22turone.248.legende'); ?>
<?php t('tbr22turone.249.p'); ?>
<?php t('tbr22turone.250.p'); ?>
<?php t('tbr22turone.251.img'); ?>
<?php t('tbr22turone.252.legende'); ?>
<?php t('tbr22turone.256.img'); ?>
<?php t('tbr22turone.257.legende'); ?>
  </div>
  <div class="col-md-6 p3">
<?php t('tbr22turone.245.img'); ?>
<?php t('tbr22turone.253.p'); ?>
<?php t('tbr22turone.254.p'); ?>
<?php t('tbr22turone.255.p'); ?>
<?php t('tbr22turone.258.img'); ?>
<?php t('tbr22turone.259.legende'); ?>
  </div>
</div>
<div class="row">
  <div class="col-md-8 offset-md-2 p3">
<?php t('tbr22turone.260.img'); ?>
<?php t('tbr22turone.261.legende'); ?>
  </div>
</div>
<div class="row">
  <div class="col-md-6 p3">
<?php t('tbr22turone.262.h4'); ?>
<?php t('tbr22turone.265.p'); ?>
<?php t('tbr22turone.266.p'); ?>
  </div>
  <div class="col-md-3 p3">
<?php t('tbr22turone.263.img'); ?>
<?php t('tbr22turone.264.legende'); ?>
  </div>
  <div class="col-md-3 p3">
<?php t('tbr22turone.267.img'); ?>
<?php t('tbr22turone.268.legende'); ?>
  </div>
</div>
<div class="row">
  <div class="col-md-4 p3">
<?php t('tbr22turone.269.p'); ?>
<?php t('tbr22turone.270.p'); ?>
  </div>
  <div class="col-md-4 p3">
<?php t('tbr22turone.271.p'); ?>
<?php t('tbr22turone.272.img'); ?>
<?php t('tbr22turone.273.legende'); ?>
  </div>
  <div class="col-md-4 p3">
<?php t('tbr22turone.274.p'); ?>
<?php t('tbr22turone.275.img'); ?>
<?php t('tbr22turone.276.legende'); ?>
  </div>
</div>
<div class="row">
  <div class="col-md-3 offset-md-1 p3">
<?php t('tbr22turone.277.p'); ?>
<?php t('tbr22turone.278.img'); ?>
<?php t('tbr22turone.279.legende'); ?>
  </div>
  <div class="col-md-7 p3">
<?php t('tbr22turone.280.p'); ?>
<?php t('tbr22turone.281.p'); ?>
<?php t('tbr22turone.282.img'); ?>
<?php t('tbr22turone.283.legende'); ?>
  </div>
</div>
<div class="row">
  <div class="col-md-8 offset-md-2 p3">
<?php t('tbr22turone.284.h4'); ?>
<?php t('tbr22turone.285.p'); ?>
  </div>
</div>
<div class="row">
  <div class="col-md-2 p3">
<?php t('tbr22turone.286.img'); ?>
<?php t('tbr22turone.287.legende'); ?>
  </div>
  <div class="col-md-4 p3">
<?php t('tbr22turone.288.p'); ?>
<?php t('tbr22turone.289.img'); ?>
<?php t('tbr22turone.290.legende'); ?>
  </div>
  <div class="col-md-4 p3">
<?php t('tbr22turone.291.p'); ?>
<?php t('tbr22turone.292.p'); ?>
<?php t('tbr22turone.293.img'); ?>
<?php t('tbr22turone.294.legende'); ?>
  </div>
  <div class="col-md-2 p3">
<?php t('tbr22turone.295.img'); ?>
<?php t('tbr22turone.296.legende'); ?>
  </div>
</div>
<div class="row">
  <div class="col-md-4 p3">
<?php t('tbr22turone.297.p'); ?>
<?php t('tbr22turone.298.p'); ?>
<?php t('tbr22turone.299.img'); ?>
<?php t('tbr22turone.300.legende'); ?>
  </div>
  <div class="col-md-8 p3">
<?php t('tbr22turone.301.p'); ?>
<?php t('tbr22turone.302.img'); ?>
<?php t('tbr22turone.304.img'); ?>
<?php t('tbr22turone.305.legende'); ?>
  </div>
</div>
<div class="row">
  <div class="col-md-6 p3">
<?php t('tbr22turone.303.img'); ?>
  </div>
  <div class="col-md-4 p3">
<?php t('tbr22turone.306.img'); ?>
  </div>
  <div class="col-md-2 p3">
<?php t('tbr22turone.307.img'); ?>
<?php t('tbr22turone.308.legende'); ?>
  </div>
</div>
<div class="row">
  <div class="col-md-8 offset-md-2 p3">
<?php t('tbr22turone.309.p'); ?>
<?php t('tbr22turone.310.img'); ?>
<?php t('tbr22turone.311.legende'); ?>
  </div>
</div>
<div class="row">
  <div class="col-md-4 p3">
<?php t('tbr22turone.314.img'); ?>
<?php t('tbr22turone.315.legende'); ?>
  </div>
  <div class="col-md-4 p3">
<?php t('tbr22turone.312.p'); ?>
<?php t('tbr22turone.313.p'); ?>
<?php t('tbr22turone.316.p'); ?>
  </div>
  <div class="col-md-4 p3">
<?php t('tbr22turone.317.img'); ?>
<?php t('tbr22turone.318.legende'); ?>
  </div>
</div>
<div class="row">
  <div class="col-md-8 offset-md-2 p3">
<?php t('tbr22turone.319.img'); ?>
<?php t('tbr22turone.320.legende'); ?>
<?php t('tbr22turone.321.legende'); ?>
  </div>
</div>
<div class="row">
  <div class="col-md-4 p3">
<?php t('tbr22turone.322.p'); ?>
<?php t('tbr22turone.323.img'); ?>
<?php t('tbr22turone.324.legende'); ?>
  </div>
  <div class="col-md-4 p3">
  <?php t('tbr22turone.325.img'); ?>
<?php t('tbr22turone.326.legende'); ?>
  </div>
  <div class="col-md-4 p3">
  <?php t('tbr22turone.327.img'); ?>
<?php t('tbr22turone.328.legende'); ?>
  </div>
</div>
<div class="row">
  <div class="col-md-4 p3">
<?php t('tbr22turone.329.h4'); ?>
<?php t('tbr22turone.330.p'); ?>
<?php t('tbr22turone.331.p'); ?>
<?php t('tbr22turone.332.p'); ?>
  </div>
  <div class="col-md-4 p3">
  <?php t('tbr22turone.333.img'); ?>
  </div>
  <div class="col-md-4 p3">
  <?php t('tbr22turone.334.img'); ?>
  </div>
</div>
<div class="row">
  <div class="col-md-8 offset-md-2 p3">
<?php t('tbr22turone.335.img'); ?>
<?php t('tbr22turone.336.legende'); ?>
<?php t('tbr22turone.337.p'); ?>
<?php t('tbr22turone.338.img'); ?>
<?php t('tbr22turone.339.legende'); ?>
    <div class="row">
      <div class="col-md-6 p3">
<?php t('tbr22turone.340.p'); ?>
<?php t('tbr22turone.341.p'); ?>
      </div>
      <div class="col-md-6 p3">
<?php t('tbr22turone.342.img'); ?>
<?php t('tbr22turone.343.legende'); ?>
      </div>
    </div>
  </div>
</div>
<?php t('tbr23lemo.001.h3'); ?>
<div class="row">
  <div class="col-md-8 offset-md-2 p3">
<?php t('tbr23lemo.002.p'); ?>
<?php t('tbr23lemo.003.p'); ?>
<?php t('tbr23lemo.004.h4'); ?>
  </div>
</div>
<div class="row">
  <div class="col-md-4 p3">
<?php t('tbr23lemo.005.p'); ?>
<?php t('tbr23lemo.006.p'); ?>
  </div>
  <div class="col-md-4 p3">
<?php t('tbr23lemo.007.img'); ?>
  </div>
  <div class="col-md-4 p3">
<?php t('tbr23lemo.030.img'); ?>
<?php t('tbr23lemo.031.legende'); ?>
  </div>
</div>
<div class="row">
  <div class="col-md-8 offset-md-2 p3">
<?php t('tbr23lemo.008.img'); ?>
<?php t('tbr23lemo.009.legende'); ?>
<?php t('tbr23lemo.010.p'); ?>
<?php t('tbr23lemo.011.img'); ?>
<?php t('tbr23lemo.012.legende'); ?>
<?php t('tbr23lemo.013.legende'); ?>
<?php t('tbr23lemo.014.legende'); ?>
<?php t('tbr23lemo.015.p'); ?>
<?php t('tbr23lemo.016.h4'); ?>
<?php t('tbr23lemo.017.p'); ?>
<?php t('tbr23lemo.018.img'); ?>
<?php t('tbr23lemo.019.legende'); ?>
<?php t('tbr23lemo.020.p'); ?>
<?php t('tbr23lemo.021.p'); ?>
  </div>
</div>
<div class="row">
  <div class="col-md-3 p3">
<?php t('tbr23lemo.022.img'); ?>
  </div>
  <div class="col-md-3 p3">
<?php t('tbr23lemo.023.img'); ?>
  </div>
  <div class="col-md-3 p3">
<?php t('tbr23lemo.033.img'); ?>
  </div>
  <div class="col-md-3 p3">
<?php t('tbr23lemo.027.img'); ?>
<?php t('tbr23lemo.028.legende'); ?>
  </div>
</div>
<div class="row">
  <div class="col-md-8 offset-md-2 p3">
<?php t('tbr23lemo.024.img'); ?>
<?php t('tbr23lemo.025.legende'); ?>
<?php t('tbr23lemo.026.legende'); ?>
<?php t('tbr23lemo.029.p'); ?>
  </div>
</div>
<div class="row">
  <div class="col-md-4 p3">
<?php t('tbr23lemo.030.img'); ?>
  </div>
  <div class="col-md-4 p3">
<?php t('tbr23lemo.032.img'); ?>
  </div>
  <div class="col-md-4 p3">
<?php t('tbr23lemo.034.img'); ?>
<?php t('tbr23lemo.035.legende'); ?>
  </div>
</div>
<div class="row">
  <div class="col-md-4 offset-md-1 p3">
<?php t('tbr23lemo.036.p'); ?>
<?php t('tbr23lemo.039.p'); ?>
  </div>
  <div class="col-md-4 p3">
<?php t('tbr23lemo.037.img'); ?>
<?php t('tbr23lemo.038.legende'); ?>
  </div>
  <div class="col-md-2 p3">
<?php t('tbr23lemo.040.img'); ?>
<?php t('tbr23lemo.041.legende'); ?>
  </div>
</div>
<div class="row">
  <div class="col-md-8 offset-md-2 p3">
<?php t('tbr23lemo.042.p'); ?>
<?php t('tbr23lemo.043.img'); ?>
<?php t('tbr23lemo.044.legende'); ?> 
<?php t('tbr23lemo.045.h4'); ?>
<?php t('tbr23lemo.046.p'); ?>
<?php t('tbr23lemo.047.img'); ?>
<?php t('tbr23lemo.048.legende'); ?>
<?php t('tbr23lemo.049.p'); ?>
<?php t('tbr23lemo.050.img'); ?>
  </div>
</div>
<div class="row">
  <div class="col-md-4 p3">
<?php t('tbr23lemo.051.img'); ?>
<?php t('tbr23lemo.052.legende'); ?>
  </div>
  <div class="col-md-4 p3">

<?php t('tbr23lemo.053.p'); ?>
<?php t('tbr23lemo.054.p'); ?>
  </div>
  <div class="col-md-4 p3">
<?php t('tbr23lemo.055.img'); ?>
  </div>
</div>
<div class="row">
  <div class="col-md-7 offset-md-1 p3">
<?php t('tbr23lemo.056.img'); ?>
  </div>
  <div class="col-md-3 p3">
<?php t('tbr23lemo.057.img'); ?>
<?php t('tbr23lemo.058.legende'); ?>
  </div>
</div>
<div class="row">
  <div class="col p3">
<?php t('tbr23lemo.059.img'); ?>
<?php t('tbr23lemo.060.legende'); ?>
<?php t('tbr23lemo.061.legende'); ?>
  </div>
</div>
<div class="row">
  <div class="col-md-7 offset-md-1 p3">
<?php t('tbr23lemo.062.p'); ?>
<?php t('tbr23lemo.063.img'); ?>
<?php t('tbr23lemo.064.legende'); ?>
  </div>
  <div class="col-md-3 p3">
<?php t('tbr23lemo.065.p'); ?>
<?php t('tbr23lemo.066.img'); ?>
<?php t('tbr23lemo.067.legende'); ?>
  </div>
</div>
<div class="row">
  <div class="col-md-7 p3">
<?php t('tbr23lemo.068.p'); ?>
<?php t('tbr23lemo.070.img'); ?>
<?php t('tbr23lemo.071.legende'); ?>
  </div>
  <div class="col-md-4 offset-md-1 p3">
<?php t('tbr23lemo.069.img'); ?>
  </div>
</div>
<div class="row">
  <div class="col-md-4 p3">
<?php t('tbr23lemo.072.p'); ?>
<?php t('tbr23lemo.073.img'); ?>
<?php t('tbr23lemo.074.legende'); ?>
  </div>
  <div class="col-md-8 p3">
<?php t('tbr23lemo.076.img'); ?>
<?php t('tbr23lemo.077.legende'); ?>
<?php t('tbr23lemo.075.p'); ?>
  </div>
</div>
<div class="row">
  <div class="col-md-8 offset-md-2 p3">
<?php t('tbr23lemo.078.h4'); ?>
<?php t('tbr23lemo.079.p'); ?>
  </div>
</div>
<div class="row">
  <div class="col-md-2 p3">
<?php t('tbr23lemo.080.p'); ?>
<?php t('tbr23lemo.081.img'); ?>
  </div>
  <div class="col-md-7 p3">
<?php t('tbr23lemo.082.p'); ?>
<?php t('tbr23lemo.083.img'); ?>
  </div>
  <div class="col-md-3 p3">
<?php t('tbr23lemo.084.img'); ?>
<?php t('tbr23lemo.085.legende'); ?>
  </div>
</div>
<div class="row">
  <div class="col-md-8 offset-md-2 p3">
<?php t('tbr23lemo.086.p'); ?>
<?php t('tbr23lemo.087.img'); ?>
<?php t('tbr23lemo.088.legende'); ?>
  </div>
</div>
<div class="row">
  <div class="col-md-4 p3">
<?php t('tbr23lemo.089.p'); ?>
<?php t('tbr23lemo.090.img'); ?>
<?php t('tbr23lemo.092.legende'); ?>
  </div>
  <div class="col-md-4 p3">
<?php t('tbr23lemo.093.p'); ?>
<?php t('tbr23lemo.095.img'); ?>
  </div>
  <div class="col-md-4 p3">
<?php t('tbr23lemo.096.p'); ?>
<?php t('tbr23lemo.097.img'); ?>
<?php t('tbr23lemo.098.legende'); ?>
  </div>
</div>
<div class="row">
  <div class="col-md-8 offset-md-2 p3">
<?php t('tbr23lemo.099.p'); ?>
<?php t('tbr23lemo.100.img'); ?>
<?php t('tbr23lemo.101.legende'); ?>
<?php t('tbr23lemo.102.p'); ?>
<?php t('tbr23lemo.103.p'); ?>
<?php t('tbr23lemo.104.img'); ?>
<?php t('tbr23lemo.105.legende'); ?>
<?php t('tbr23lemo.106.p'); ?>
<?php t('tbr23lemo.107.img'); ?>
<?php t('tbr23lemo.108.legende'); ?>
  </div>
</div>
<div class="row">
  <div class="col-md-3 offset-md-1 p3">


<?php t('tbr23lemo.109.p'); ?>
<?php t('tbr23lemo.110.img'); ?>
<?php t('tbr23lemo.111.legende'); ?>
  </div>

  <div class="col-md-7 p3">

<?php t('tbr23lemo.112.p'); ?>
<?php t('tbr23lemo.113.p'); ?>
<?php t('tbr23lemo.114.img'); ?>
<?php t('tbr23lemo.115.legende'); ?>
  </div>
</div>
<div class="row">
  <div class="col-md-4 p3">
<?php t('tbr23lemo.117.img'); ?>
  </div>
  <div class="col-md-2 p3">
<?php t('tbr23lemo.118.img'); ?>
<?php t('tbr23lemo.119.legende'); ?>
  </div>
  <div class="col-md-6 p3">
<?php t('tbr23lemo.116.p'); ?>
<?php t('tbr23lemo.120.p'); ?>
<?php t('tbr23lemo.121.p'); ?>
  </div>
</div>
<div class="row">
  <div class="col-md-8 offset-md-2 p3">
<?php t('tbr23lemo.122.img'); ?>
<?php t('tbr23lemo.123.img'); ?>
<?php t('tbr23lemo.124.legende'); ?>
<?php t('tbr23lemo.125.p'); ?>
<?php t('tbr23lemo.126.img'); ?>
<?php t('tbr23lemo.127.legende'); ?>
    <div class="row">
      <div class="col-md-6 p3">
<?php t('tbr23lemo.128.p'); ?>
<?php t('tbr23lemo.129.p'); ?>
      </div>
      <div class="col-md-6 p3">
<?php t('tbr23lemo.130.img'); ?>
<?php t('tbr23lemo.131.legende'); ?>
<?php t('tbr23lemo.132.legende'); ?>
      </div>
    </div>
<?php t('tbr23lemo.133.img'); ?>
<?php t('tbr23lemo.134.img'); ?>
<?php t('tbr23lemo.135.legende'); ?>
<?php t('tbr23lemo.136.h4'); ?>
<?php t('tbr23lemo.137.p'); ?>
<?php t('tbr23lemo.138.p'); ?>
<?php t('tbr23lemo.139.img'); ?>
<?php t('tbr23lemo.140.legende'); ?>
  </div>
</div>
<div class="row">
  <div class="col-md-6 p3">
<?php t('tbr23lemo.141.p'); ?>
<?php t('tbr23lemo.144.p'); ?>
<?php t('tbr23lemo.147.p'); ?>
  </div>
  <div class="col-md-4 p3">
<?php t('tbr23lemo.142.img'); ?>
<?php t('tbr23lemo.143.legende'); ?>
  </div>
  <div class="col-md-2 p3">
<?php t('tbr23lemo.145.img'); ?>
<?php t('tbr23lemo.146.legende'); ?>
  </div>
</div>
<div class="row">
  <div class="col-md-8 offset-md-2 p3">
<?php t('tbr23lemo.148.img'); ?>
<?php t('tbr23lemo.149.img'); ?>
  </div>
</div>
<div class="row">
  <div class="col-md-4 p3">
<?php t('tbr23lemo.150.img'); ?>
  </div>
  <div class="col-md-4 p3">
<?php t('tbr23lemo.151.p'); ?>
<?php t('tbr23lemo.152.img'); ?>
  </div>
  <div class="col-md-4 p3">
<?php t('tbr23lemo.153.p'); ?>
<?php t('tbr23lemo.154.img'); ?>
  </div>
</div>
<div class="row">
  <div class="col-md-8 offset-md-2 p3">
<?php t('tbr23lemo.155.img'); ?>
<?php t('tbr23lemo.156.legende'); ?>
<?php t('tbr23lemo.157.img'); ?>
<?php t('tbr23lemo.158.p'); ?>
<?php t('tbr23lemo.159.h4'); ?>
<?php t('tbr23lemo.160.p'); ?>
<?php t('tbr23lemo.161.p'); ?>
<?php t('tbr23lemo.162.p'); ?>
  </div>
</div>
<div class="row">
  <div class="col-md-3 offset-md-1 p3">

<?php t('tbr23lemo.163.img'); ?>
<?php t('tbr23lemo.164.legende'); ?>
  </div>
  <div class="col-md-7 p3">
<?php t('tbr23lemo.165.img'); ?>
<?php t('tbr23lemo.166.legende'); ?>
  </div>
</div>
<div class="row">
  <div class="col-md-4 p3">
<?php t('tbr23lemo.167.p'); ?>
<?php t('tbr23lemo.168.img'); ?>
<?php t('tbr23lemo.169.legende'); ?>
  </div>
  <div class="col-md-4 p3">
<?php t('tbr23lemo.174.p'); ?>
<?php t('tbr23lemo.175.img'); ?>
<?php t('tbr23lemo.176.legende'); ?>
  </div>
  <div class="col-md-4 p3">
<?php t('tbr23lemo.170.p'); ?>
<?php t('tbr23lemo.171.img'); ?>
  </div>
</div>
<div class="row">
  <div class="col-md-8 offset-md-2 p3">
<?php t('tbr23lemo.172.img'); ?>
<?php t('tbr23lemo.173.legende'); ?>
<?php t('tbr23lemo.177.p'); ?>
<?php t('tbr23lemo.178.h4'); ?>
  </div>
</div>
<div class="row">
  <div class="col-md-4 p3">
<?php t('tbr23lemo.179.img'); ?>
<?php t('tbr23lemo.180.legende'); ?>
  </div>
  <div class="col-md-8 p3">
<?php t('tbr23lemo.181.p'); ?>
<?php t('tbr23lemo.182.p'); ?>
<?php t('tbr23lemo.183.img'); ?>
  </div>
</div>
<div class="row">
  <div class="col-md-8 offset-md-2 p3">
<?php t('tbr23lemo.184.img'); ?>
<?php t('tbr23lemo.185.legende'); ?>
  </div>
</div>
<div class="row">
  <div class="col-md-4 p3">
<?php t('tbr23lemo.187.img'); ?>
<?php t('tbr23lemo.188.legende'); ?>
  </div>
  <div class="col-md-4 p3">
<?php t('tbr23lemo.186.p'); ?>
<?php t('tbr23lemo.189.p'); ?>
  </div>
  <div class="col-md-4 p3">
<?php t('tbr23lemo.190.img'); ?>
<?php t('tbr23lemo.191.legende'); ?>
  </div>
</div>
<?php t('tbr24podie.001.h3'); ?>
<div class="row">
  <div class="col-md-8 offset-md-2 p3">
<?php t('tbr24podie.002.p'); ?>
<?php t('tbr24podie.003.p'); ?>
<?php t('tbr24podie.004.h4'); ?>
  </div>
</div>
<div class="row">
  <div class="col-md-4 p3">
<?php t('tbr24podie.005.p'); ?>
<?php t('tbr24podie.006.p'); ?>
<?php t('tbr24podie.007.p'); ?>
  </div>
  <div class="col-md-2 p3">
<?php t('tbr24podie.008.img'); ?>
  </div>
  <div class="col-md-6 p3">
<?php t('tbr24podie.009.img'); ?>
  </div>
</div>
<div class="row">
  <div class="col-md-8 offset-md-2 p3">
<?php t('tbr24podie.010.img'); ?>
<?php t('tbr24podie.011.legende'); ?>
<?php t('tbr24podie.012.h4'); ?>
<?php t('tbr24podie.013.p'); ?>
<?php t('tbr24podie.014.p'); ?>
<?php t('tbr24podie.015.p'); ?>
<?php t('tbr24podie.016.p'); ?>
<?php t('tbr24podie.017.img'); ?>
<?php t('tbr24podie.018.img'); ?>
<?php t('tbr24podie.019.legende'); ?>
<?php t('tbr24podie.020.img'); ?>
<?php t('tbr24podie.021.legende'); ?>
    <div class="row">
      <div class="col-md-6 p3">
<?php t('tbr24podie.022.p'); ?>
<?php t('tbr24podie.023.p'); ?>
<?php t('tbr24podie.024.p'); ?>
      </div>
      <div class="col-md-6 p3">
<?php t('tbr24podie.025.img'); ?>
<?php t('tbr24podie.026.legende'); ?>
      </div>
    </div>
<?php t('tbr24podie.027.img'); ?>
<?php t('tbr24podie.028.legende'); ?>
<?php t('tbr24podie.029.p'); ?>
<?php t('tbr24podie.030.img'); ?>
<?php t('tbr24podie.031.legende'); ?>
<?php t('tbr24podie.032.h4'); ?>
<?php t('tbr24podie.033.p'); ?>
<?php t('tbr24podie.034.img'); ?>
<?php t('tbr24podie.035.legende'); ?>
<?php t('tbr24podie.036.p'); ?>
<?php t('tbr24podie.039.p'); ?>
    <div class="row">
      <div class="col-md-4 p3">
<?php t('tbr24podie.037.img'); ?>
<?php t('tbr24podie.038.legende'); ?>
      </div>
      <div class="col-md-8 p3">
<?php t('tbr24podie.040.img'); ?>
<?php t('tbr24podie.041.legende'); ?>
      </div>
    </div>
<?php t('tbr24podie.042.p'); ?>
<?php t('tbr24podie.043.p'); ?>
<?php t('tbr24podie.044.img'); ?>
<?php t('tbr24podie.045.legende'); ?>
<?php t('tbr24podie.046.h4'); ?>
<?php t('tbr24podie.047.p'); ?>
  </div>
</div>
<div class="row">
  <div class="col-md-8 p3">
<?php t('tbr24podie.048.p'); ?>
<?php t('tbr24podie.049.p'); ?>
<?php t('tbr24podie.050.img'); ?>
<?php t('tbr24podie.051.legende'); ?>
  </div>
  <div class="col-md-4 p3">
<?php t('tbr24podie.056.p'); ?>
<?php t('tbr24podie.057.img'); ?>
<?php t('tbr24podie.058.legende'); ?>
  </div>
</div>
<div class="row">
  <div class="col-md-8 offset-md-2 p3">
<?php t('tbr24podie.052.p'); ?>
<?php t('tbr24podie.053.p'); ?>
<?php t('tbr24podie.054.img'); ?>
<?php t('tbr24podie.055.legende'); ?>
<?php t('tbr24podie.059.p'); ?>
<?php t('tbr24podie.060.img'); ?>
<?php t('tbr24podie.061.legende'); ?>
<?php t('tbr24podie.062.p'); ?>
<?php t('tbr24podie.063.p'); ?>
<?php t('tbr24podie.064.img'); ?>
<?php t('tbr24podie.065.legende'); ?>
<?php t('tbr24podie.066.p'); ?>
<?php t('tbr24podie.067.p'); ?>
<?php t('tbr24podie.068.p'); ?>
  </div>
</div>
<div class="row">
  <div class="col-md-4 p3">
<?php t('tbr24podie.069.img'); ?>
  </div>
  <div class="col-md-8 p3">
<?php t('tbr24podie.070.img'); ?>
<?php t('tbr24podie.071.legende'); ?>
  </div>
</div>
<div class="row">
  <div class="col-md-8 offset-md-2 p3">
<?php t('tbr24podie.072.p'); ?>
<?php t('tbr24podie.073.img'); ?>
<?php t('tbr24podie.074.legende'); ?>
<?php t('tbr24podie.075.p'); ?>
<?php t('tbr24podie.076.img'); ?>
<?php t('tbr24podie.077.legende'); ?>
<?php t('tbr24podie.078.p'); ?>
<?php t('tbr24podie.079.img'); ?>
<?php t('tbr24podie.080.img'); ?>
<?php t('tbr24podie.081.legende'); ?>
<?php t('tbr24podie.082.p'); ?>
<?php t('tbr24podie.083.img'); ?>
<?php t('tbr24podie.084.legende'); ?>
<?php t('tbr24podie.085.img'); ?>
<?php t('tbr24podie.086.legende'); ?>
<?php t('tbr24podie.087.h4'); ?>
<?php t('tbr24podie.088.p'); ?>
<?php t('tbr24podie.089.p'); ?>
<?php t('tbr24podie.090.img'); ?>
<?php t('tbr24podie.091.legende'); ?>
<?php t('tbr24podie.092.p'); ?>
  </div>
</div>
<div class="row">
  <div class="col-md-3 offset-md-1 p3">
<?php t('tbr24podie.093.img'); ?>
  </div>
  <div class="col-md-3 p3">
<?php t('tbr24podie.094.img'); ?>
  </div>
  <div class="col-md-4 p3">
<?php t('tbr24podie.095.img'); ?>
<?php t('tbr24podie.096.legende'); ?>
  </div>
</div>
<div class="row">
  <div class="col-md-8 offset-md-2 p3">
<?php t('tbr24podie.097.img'); ?>
<?php t('tbr24podie.098.legende'); ?>
<?php t('tbr24podie.099.p'); ?>
<?php t('tbr24podie.100.img'); ?>
<?php t('tbr24podie.101.legende'); ?>
  </div>
</div>
<?php t('tbr25tolosa.001.h3'); ?>
<div class="row">
  <div class="col-md-4 p3">
<?php t('tbr25tolosa.002.p'); ?>
<?php t('tbr25tolosa.003.img'); ?>
<?php t('tbr25tolosa.004.legende'); ?>
  </div>
  <div class="col-md-8 p3">
<?php t('tbr25tolosa.005.h4'); ?>
<?php t('tbr25tolosa.006.p'); ?>
<?php t('tbr25tolosa.007.img'); ?>
<?php t('tbr25tolosa.008.legende'); ?>
  </div>
</div>
<div class="row">
  <div class="col-md-8 p3">
<?php t('tbr25tolosa.009.p'); ?>
<?php t('tbr25tolosa.010.img'); ?>
  </div>
  <div class="col-md-4 p3">
<?php t('tbr25tolosa.011.img'); ?>
<?php t('tbr25tolosa.012.legende'); ?>
  </div>
</div>
<div class="row">
  <div class="col-md-8 p3">
<?php t('tbr25tolosa.013.p'); ?>
<?php t('tbr25tolosa.014.img'); ?>
<?php t('tbr25tolosa.015.legende'); ?>
  </div>
  <div class="col-md-4 p3">
<?php t('tbr25tolosa.016.h4'); ?>
<?php t('tbr25tolosa.017.p'); ?>
<?php t('tbr25tolosa.018.img'); ?>
<?php t('tbr25tolosa.019.legende'); ?>
  </div>
</div>
<div class="row">
  <div class="col-md-8 offset-md-2 p3">
<?php t('tbr25tolosa.020.img'); ?>
<?php t('tbr25tolosa.021.legende'); ?>
<?php t('tbr25tolosa.022.p'); ?>
<?php t('tbr25tolosa.026.p'); ?>
  </div>
</div>
<div class="row">
  <div class="col-md-4 p3">
<?php t('tbr25tolosa.023.img'); ?>
  </div>
  <div class="col-md-4 p3">
<?php t('tbr25tolosa.024.img'); ?>
<?php t('tbr25tolosa.025.legende'); ?>
  </div>
  <div class="col-md-4 p3">
<?php t('tbr25tolosa.027.img'); ?>
<?php t('tbr25tolosa.028.legende'); ?>
  </div>
</div>
<div class="row">
  <div class="col-md-8 offset-md-2 p3">
<?php t('tbr25tolosa.029.p'); ?>
<?php t('tbr25tolosa.030.img'); ?>
<?php t('tbr25tolosa.031.legende'); ?>
<?php t('tbr25tolosa.032.p'); ?>
  </div>
</div>
<div class="row">
  <div class="col-md-8 p3">
<?php t('tbr25tolosa.033.img'); ?>
  </div>
  <div class="col-md-4 p3">
<?php t('tbr25tolosa.034.img'); ?>
<?php t('tbr25tolosa.035.legende'); ?>
  </div>
</div>
<div class="row">
  <div class="col-md-8 offset-md-2 p3">
<?php t('tbr25tolosa.036.p'); ?>
<?php t('tbr25tolosa.037.img'); ?>
<?php t('tbr25tolosa.038.legende'); ?>
<?php t('tbr25tolosa.039.p'); ?>
<?php t('tbr25tolosa.040.p'); ?>
<?php t('tbr25tolosa.041.img'); ?>
<?php t('tbr25tolosa.042.legende'); ?>
<?php t('tbr25tolosa.043.p'); ?>
  </div>
</div>
<div class="row">
  <div class="col-md-4 offset-md-1 p3">
<?php t('tbr25tolosa.044.img'); ?>
<?php t('tbr25tolosa.045.legende'); ?>
  </div>
  <div class="col-md-3 p3">
<?php t('tbr25tolosa.046.img'); ?>
<?php t('tbr25tolosa.047.legende'); ?>
  </div>
  <div class="col-md-3 p3">
<?php t('tbr25tolosa.048.img'); ?>
<?php t('tbr25tolosa.049.legende'); ?>
  </div>
</div>
<div class="row">
  <div class="col-md-8 offset-md-2 p3">
<?php t('tbr25tolosa.050.img'); ?>
<?php t('tbr25tolosa.051.legende'); ?>
<?php t('tbr25tolosa.052.h4'); ?>
    <div class="row">
      <div class="col-md-8 p3">
<?php t('tbr25tolosa.053.p'); ?>
<?php t('tbr25tolosa.054.p'); ?>
<?php t('tbr25tolosa.055.commentaire'); ?>
      </div>
      <div class="col-md-4 p3">
<?php t('tbr25tolosa.056.img'); ?>
      </div>
  </div>
<?php t('tbr25tolosa.057.img'); ?>
<?php t('tbr25tolosa.058.legende'); ?>
  </div>
</div>
<div class="row">
  <div class="col-md-4 p3">
<?php t('tbr25tolosa.059.p'); ?>
<?php t('tbr25tolosa.060.img'); ?>
<?php t('tbr25tolosa.061.legende'); ?>
  </div>
  <div class="col-md-3 offset-md-1 p3">
<?php t('tbr25tolosa.062.p'); ?>
<?php t('tbr25tolosa.063.img'); ?>
<?php t('tbr25tolosa.064.legende'); ?>
  </div>
  <div class="col-md-4 p3">
<?php t('tbr25tolosa.065.p'); ?>
<?php t('tbr25tolosa.066.img'); ?>
<?php t('tbr25tolosa.067.legende'); ?>
  </div>
</div>
<div class="row">
  <div class="col-md-8 offset-md-2 p3">
<?php t('tbr25tolosa.068.p'); ?>
<?php t('tbr25tolosa.069.img'); ?>
<?php t('tbr25tolosa.070.legende'); ?>
  </div>
</div>
<?php t('tbr30title.007.h2'); ?>
<div class="row">
  <div class="col-md-8 p3">
<?php t('tbr31modern.001.h3'); ?>
<?php t('tbr31modern.002.p'); ?>
<?php t('tbr31modern.003.p'); ?>
<?php t('tbr31modern.004.p'); ?>
<?php t('tbr31modern.007.p'); ?>
  </div>
  <div class="col-md-4 p3">
<?php t('tbr31modern.005.img'); ?>
<?php t('tbr31modern.006.legende'); ?>
  </div>
</div>
<div class="row">
  <div class="col-md-4 offset-md-1 p3">
<?php t('tbr31modern.008.img'); ?>
  </div>
  <div class="col-md-4 p3">
<?php t('tbr31modern.009.img'); ?>
<?php t('tbr31modern.010.legende'); ?>
  </div>
  <div class="col-md-3 p3">
<?php t('tbr31modern.011.img'); ?>
<?php t('tbr31modern.012.legende'); ?>
  </div>
</div>
<div class="row">
  <div class="col-md-8 offset-md-2 p3">
<?php t('tbr32basilic.001.h3'); ?>
<?php t('tbr32basilic.002.p'); ?>
<?php t('tbr32basilic.003.img'); ?>
<?php t('tbr32basilic.004.legende'); ?>
  </div>
</div>
<div class="row">
  <div class="col-md-4 p3">
<?php t('tbr32basilic.005.p'); ?>
  </div>
  <div class="col-md-4 p3">
<?php t('tbr32basilic.006.img'); ?>
  </div>
  <div class="col-md-4 p3">
<?php t('tbr32basilic.007.img'); ?>
<?php t('tbr32basilic.008.legende'); ?>
  </div>
</div>
<div class="row">
  <div class="col-md-8 offset-md-2 p3">
<?php t('tbr32basilic.009.p'); ?>
<?php t('tbr32basilic.010.img'); ?>
<?php t('tbr32basilic.011.legende'); ?>
<?php t('tbr32basilic.012.p'); ?>
<?php t('tbr32basilic.013.img'); ?>
<?php t('tbr32basilic.014.legende'); ?>
<?php t('tbr33pilgrim.001.h3'); ?>
<?php t('tbr33pilgrim.002.p'); ?>
<?php t('tbr33pilgrim.003.p'); ?>
<?php t('tbr33pilgrim.004.img'); ?>
<?php t('tbr33pilgrim.005.legende'); ?>
<?php t('tbr33pilgrim.006.p'); ?>
<?php t('tbr33pilgrim.007.img'); ?>
<?php t('tbr33pilgrim.008.legende'); ?>
<?php t('tbr33pilgrim.009.p'); ?>
<?php t('tbr33pilgrim.010.img'); ?>
<?php t('tbr33pilgrim.011.legende'); ?>
<?php t('tbr33pilgrim.012.p'); ?>
<?php t('tbr33pilgrim.013.p'); ?>
<?php t('tbr33pilgrim.014.img'); ?>
<?php t('tbr33pilgrim.015.legende'); ?>
<?php t('tbr33pilgrim.016.img'); ?>
<?php t('tbr33pilgrim.017.legende'); ?>
<?php t('tbr33pilgrim.018.p'); ?>
<?php t('tbr33pilgrim.019.img'); ?>
<?php t('tbr33pilgrim.020.legende'); ?>
<?php t('tbr33pilgrim.021.p'); ?>
<?php t('tbr33pilgrim.022.img'); ?>
<?php t('tbr33pilgrim.023.legende'); ?>
<?php t('tbr33pilgrim.024.p'); ?>
<?php t('tbr33pilgrim.025.p'); ?>
<?php t('tbr33pilgrim.026.img'); ?>
<?php t('tbr33pilgrim.027.legende'); ?>
<?php t('tbr33pilgrim.028.p'); ?>
<?php t('tbr33pilgrim.029.p'); ?>
<?php t('tbr33pilgrim.030.img'); ?>
<?php t('tbr33pilgrim.031.legende'); ?>
<?php t('tbr33pilgrim.032.img'); ?>
<?php t('tbr33pilgrim.033.legende'); ?>
<?php t('tbr33pilgrim.034.commentaire'); ?>
<?php t('tbr33pilgrim.035.commentaire'); ?>
<?php t('tbr33pilgrim.036.commentaire'); ?>
  </div>
</div>
</div>
</main>
<footer id="footer">
<div class="container">
  <div class="row py-5">
    <div class="col-md-8 offset-md-2 p3">
<p class="findepage"><?php traduire('footer.disclaimer'); ?></p>
<p class="findepage">©2024–<?php echo date('Y'); ?> — F. de Lancelot</p>
    </div>
  </div>
</div>

</footer>
<script src="https://cdn.jsdelivr.net/npm/aos@2.003.04/dist/aos.js"></script>
</body>
</html>


