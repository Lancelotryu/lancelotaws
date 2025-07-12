<head>
<?php $basePath = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/') . '/'; ?>
<base href="<?= htmlspecialchars($basePath, ENT_QUOTES) ?>">

<!--- Meta Website --->
<meta charset="utf-8">
<meta content="width=device-width, initial-scale=1.0" name="viewport">
<title><?php t($metaTitleMap[$section]); ?></title>
<meta name="description" content="<?php t($metaDescMap[$section]); ?>">
<meta name="author" content="<?php echo $site['author']; ?>">
<meta name="keywords" content="<?php t('meta.keywords'); ?>">
<meta name="robots" content="index, follow">

<!--- Meta Social --->
<meta property="og:type" content="website">
<meta property="og:title" content="<?php t($metaTitleMap[$section]); ?>">
<meta property="og:description" content="<?php t($metaDescMap[$section]); ?>">
<meta property="og:image" content="<?php echo $site['coverimg']; ?>">
<meta property="og:url" content="<?php echo $site['url']; ?>">
<meta property="og:site_name" content="<?php echo $site['author']; ?>">
<meta property="og:locale" content="<?= $l === 'fr' ? 'fr_FR' : 'en_US' ?>">

<!--- Meta Twitter --->
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="<?php t($metaTitleMap[$section]); ?>">
<meta name="twitter:description" content="<?php t($metaDescMap[$section]); ?>">
<meta name="twitter:image" content="<?php echo $site['coverimg']; ?>">
<meta name="twitter:site" content="<?php echo $site['twitter']; ?>">

<!--- Fonts --->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<!--- I use a lot of different fonts, I know, but they all make sense.
      Inter is my default font. It can display a wide range of characters in latin & cyrillic;
      Merriweather is nice for cute titles;
      EB Garamond is fancy enough to display poems;
      Fira Code is very useful to display code;
      Cinzel is used for the title of my Poetry compilation
      Courier Prime helps me display my screenplays with this "real screenplay" vibe. --->
<!-- Then the actual stylesheet URL, with no stray spaces and full origin -->
<link
  href="https://fonts.googleapis.com/css2?
    family=Inter:wght@300;400;500;600&
    family=Merriweather:wght@400;700&
    family=EB+Garamond:ital,wght@0,400;0,600;1,400&
    family=Fira+Code:wght@400;500&
    family=Cinzel&
    family=Courier+Prime:wght@400;600;700&
    display=swap"
  rel="stylesheet"
/>

<!--- Frameworks (I don't use big CSS frameworks, only Bootstrap)--->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/flag-icons/7.5.0/css/flag-icons.min.css" rel="stylesheet"/>

<!--- Effects --->
<link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/glightbox/dist/css/glightbox.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css" rel="stylesheet">

<!--- CSS - I use 5 different files so it is easier to sort CSS styles. --->
<link href="assets/css/style.css" rel="stylesheet">
<link href="assets/css/nav.css" rel="stylesheet">
<link href="assets/css/cv.css" rel="stylesheet">
<link href="assets/css/poetry.css" rel="stylesheet">
<link href="assets/css/script.css" rel="stylesheet">

<!--- Favicons --->
<link href="assets/img/favicon.png" rel="icon">
<link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

<?php
$canonical = rtrim($site['url'], '/') . '/' . $l;
  if ($section !== 'home') {$canonical .= '/' . $section;}
?>
<link rel="canonical" href="<?php echo htmlspecialchars($canonical, ENT_QUOTES, 'UTF-8'); ?>">
</head>