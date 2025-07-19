<?php
//Some text I use often. If one day I change name, I just have to update this array.
$site = [
  'name'   => 'François JOSSINET',
  'author' => 'F. de Lancelot',
  'url'    => 'https://fdelancelot.com',
  'coverimg' => "https://fdelancelot.com/assets/img/cover.png",
  'year'   => date('Y'),
  'phone' => "+33 6 41 20 52 98",
  'email' => "francois.jo18@gmail.com",
  'linkedinhref' => '<a href="https://www.linkedin.com/in/110033235/">LinkedIn</a>',
  'linkedin' => 'https://www.linkedin.com/in/110033235/',
  'instagram' => 'https://www.instagram.com/fdelancelot/',
  'github' => 'https://www.github.com/fdelancelot/',
  'twitter' => '@FranoisJossine1'
];

//List of poems in English that I display
$ids = ['icare-en', 'ninon-en', 'iris-en', 'neant-en', 'marbre-en', 'kiskis-en', 'pendu-en', 'minsk-en'];

//Array for translation of meta data Title.
$metaTitleMap = [
    'home'    => 'meta.home.title',
    'artwork' => 'meta.artwork.title',
    'poetry'  => 'meta.poetry.title',
    'projects' => 'meta.projects.title',
    'mariup' => 'meta.mariup.title',
    'souven'=> 'meta.souven.title'
];

//Array for translation of meta data Description
$metaDescMap = [
    'home'    => 'meta.home.description',
    'artwork' => 'meta.artwork.description',
    'poetry'  => 'meta.poetry.description',
    'projects' => 'meta.projects.description',
    'mariup' => 'meta.mariup.description',
    'souven' => 'meta.souven.description'
];

//Variables for "Song" type poems.
$markerLabels = [
  'cpl'  => ['fr'=>'Couplet ',      'en'=>'Verse '],
  'rfn'  => ['fr'=>'Refrain',       'en'=>'Chorus'],
  'itl'  => ['fr'=>'Interlude',     'en'=>'Interlude'],
  'pnt'  => ['fr'=>'Pont',          'en'=>'Bridge'],
  'rnv'  => ['fr'=>'Renvoi',        'en'=>'Tag'],
  'int'  => ['fr'=>'Intro',         'en'=>'Intro'],
  'out'  => ['fr'=>'Outro',         'en'=>'Outro'],
];

//Variables to set up icons.
$preicon = '<i class="bi bi-';
$posticon = '"></i>';
$bigicon = ' me-2"></i>';
$navicon = ' navicon"></i>';

//List of icons I use. I can call a function with name of icon and it will display it.
$icon = [
    'geo-alt' => $preicon . 'geo-alt' . $posticon,
    'envelope' => $preicon . 'envelope' . $posticon,
    'telephone' => $preicon . 'telephone' . $posticon,
    'linkedin' => $preicon . 'linkedin' . $posticon,
    'instagram' => $preicon . 'instagram' . $posticon,
    'github' => $preicon . 'github' . $posticon,
    'book' => $preicon . 'book' . $posticon,
    'file-text' => $preicon . 'file-text' . $posticon,
    'play-circle' => $preicon . 'play-circle' . $posticon,
    'camera-reels' => $preicon . 'camera-reels' . $posticon,
    'envelope' => $preicon . 'envelope' . $posticon,
    'feather' => $preicon . 'feather' . $posticon,
    'music-note-beamed' => $preicon . 'music-note-beamed' . $posticon,
    'chevron-double-down' =>  $preicon . 'chevron-double-down' . $posticon,

    'briefcase' => $preicon . 'briefcase' . $bigicon,
    'mortarboard' => $preicon . 'mortarboard' . $bigicon,
    'award' => $preicon . 'award' . $bigicon,

    'house-nav' => $preicon . 'house' . $navicon,
    'tools-nav' => $preicon . 'tools' . $navicon,
    'laptop-nav' => $preicon . 'laptop' . $navicon,
    'music-note-beamed-nav' => $preicon . 'music-note-beamed' . $navicon,
    'feather-nav' => $preicon . 'feather' . $navicon,
    'envelope-nav' => $preicon . 'envelope' . $navicon,
];
?>