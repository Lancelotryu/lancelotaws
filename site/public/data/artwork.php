<h1><?php t('nav.artwork'); ?></h1>

<!--- A short intro to my artwork projects --->
<section id="intro">
<div class="container" data-aos="fade-up" data-aos-delay="100">
<p class="contenu"><?php t('artwork.intro.1'); ?></p>
<p class="contenu"><?php t('artwork.intro.2'); ?></p>
<p class="contenu"><?php t('artwork.intro.3'); ?></p>
</div>

<div class="container px-5">
<div class="row justify-content-center g-3 text-center">
<?php
//I know, that's a big array to generate just 6 buttons.
render_section_buttons([
  ['id' => 'music', 'label' => 'artwork.title.music', 'icon' => 'music-note-beamed'],
  ['id' => 'poetry', 'label' => 'artwork.title.poetry', 'icon' => 'feather'],
  ['id' => 'scripts', 'label' => 'artwork.title.scripts', 'icon' => 'camera-reels'],
  ['id' => 'youtube', 'label' => 'artwork.title.youtube', 'icon' => 'play-circle'],
  ['id' => 'shortnovels', 'label' => 'artwork.title.shortnovels', 'icon' => 'file-text'],
  ['id' => 'books', 'label' => 'artwork.title.books', 'icon' => 'book'],
], $l);
?>
</div></div>
</section>

<!--- Music section --->
<section id="music">
<div class="container section-title" data-aos="fade-up"><h2><?php t('artwork.title.music'); ?></h2></div>
<div class="container" data-aos="fade-up" data-aos-delay="100">
<p class="tldr"><?php t('artwork.music.tldr'); ?></p>
<!--- Buttons that link to the SoundCloud of the bands
      Dystopie does not have a SoundCloud yet, so it is an empty link --->
  <div class="text-center">
     <a href="https://soundcloud.com/jamestown-2" class="btn btn-lancelot">Jamestown</a>
      <a href="https://soundcloud.com/stellarvorebm" class="btn btn-lancelot">Stellarvore</a>
      <a href="#" class="btn btn-lancelot">Dystopie</a>
  </div>
<!--- Rendering the Music blocks
      Just one line to render so many blocks, amazing, right ? --->
<?php echo $l;?>
<?php render_artwork_block($pdo, 'artwork.music'); ?>
</div></section>

<!--- Poetry section --->
<section id="poetry">
<div class="container section-title" data-aos="fade-up"><h2><?php t('artwork.title.poetry'); ?></h2></div>
<div class="container" data-aos="fade-up" data-aos-delay="100">
<p class="tldr"><?php t('artwork.poetry.tldr'); ?></p>
<div class="text-center">
<!--- Direct link to the Poetry page, managed by JavaScript --->
<a href="<?= $basePath . $l?>/poetry" class="btn btn-lancelot"><?php t('nav.poetry'); ?></a></li>

</div>
<!--- Rendering Poetry block --->
<?php render_artwork_block($pdo, 'artwork.poetry'); 

if ($l == "en"){//We display the following only for English readers.
echo '<p class="contenu">';
t('artwork.only.1');
echo "<br />\n";
t('artwork.only.2');
echo "</p><div class=\"text-center\">\n";
echo "<button class=\"btn btn-lancelot gap-2\" type=\"button\"";
echo "data-bs-toggle=\"collapse\" data-bs-target=\"#englishPoetry\" aria-expanded=\"false\" aria-controls=\"englishPoetry\">\n";
icon('chevron-double-down'); echo "&nbsp; ";
t("artwork.only.3");
echo "&nbsp; "; icon('chevron-double-down');//And below, a nice Bootstrap Collapse to display poems in English for those who are interested.
echo "</button></div><div class=\"collapse mt-3\" id=\"englishPoetry\">\n";

$stmt = $pdo->query("SELECT recueil, titre, contenu, id_logique FROM poemes");
$poemes = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($ids as $fragment) {//a nice foreach loop to display the English translated poems.
    $id_logique = '13-trad-' . $fragment;
    render_poeme_by_id($poemes, $id_logique);
}
echo "</div>";
}
?>
</div></section>

<!--- Scripts section --->
<section id="scripts">
<div class="container section-title" data-aos="fade-up"><h2><?php t('artwork.title.scripts'); ?></h2></div>
<div class="container" data-aos="fade-up" data-aos-delay="100">
<p class="tldr"><?php t('artwork.scripts.tldr'); ?></p>
<div class="text-center">
      <a href="<?= $basePath . $l?>/mariup" class="btn btn-lancelot"><?php t('button.mariupol'); ?></a>
      <a href="<?= $basePath . $l?>/souven" class="btn btn-lancelot"><?php t('button.soleils'); ?></a>
</div>
<!--- Again, one line to display all what I need --->
<?php render_artwork_block($pdo, 'artwork.scripts'); ?>
</div></section>

<!--- YouTube section --->
<section id="youtube">
<div class="container section-title" data-aos="fade-up"><h2><?php t('artwork.title.youtube'); ?></h2></div>
<div class="container" data-aos="fade-up" data-aos-delay="100">
<p class="tldr"><?php t('artwork.youtube.tldr'); ?></p>
<div class="text-center"><a href="https://www.youtube.com/channel/UCNGahH81lzRPN-4NGmIthrg" class="btn btn-lancelot">Le Cénacle</a></div>
<?php render_artwork_block($pdo, 'artwork.youtube'); ?>
</div></section>

<!--- Short Novels section --->
<section id="shortnovels">
<div class="container section-title" data-aos="fade-up"><h2><?php t('artwork.title.shortnovels'); ?></h2></div>
<div class="container" data-aos="fade-up" data-aos-delay="100">
<p class="tldr"><?php t('artwork.shortnovels.tldr'); ?></p>
<?php
render_shortnovels($pdo, $l);
if ($l === 'en') {//Again, only for English readers
  echo "<div class=\"text-center mt-5\"><p class=\"contenu\">";
  t('artwork.only.4');
  echo '</p><a href="#" class="btn btn-outline-primary show-nouvelle" data-nouvelle="kiskis-en">';
  icon('chevron-double-down'); echo "&nbsp; "; 
  t('artwork.only.5');
  echo "&nbsp; ";   icon('chevron-double-down');
  echo "</a></div>\n";
}
?>

<!--- The invisible collapse where the short novels will appear. --->
<div class="text-center mt-5">
  <div class="collapse" id="nouvelle-container">
    <div class="card card-body" id="nouvelle-content">
      <h4><?php t('global.shortn'); ?></h4>
      <p class="text-muted"></p>
    </div>
    <div class="d-flex justify-content-end mt-3">
      <button type="button" id="btn-close-nouvelle" class="btn btn-secondary d-none">Fermer</button>
    </div>
  </div>
</div>
<!--- Some unindented div --->
</div></div></div></div></div></section>

<!--- Books section --->
<section id="books">
  <div class="container section-title" data-aos="fade-up"><h2><?php t('artwork.title.books'); ?></h2></div>
  <div class="container" data-aos="fade-up" data-aos-delay="100">
    <p class="tldr"><?php t('artwork.books.tldr'); ?></p>
    <p class="contenu"><?php t('artwork.book.1'); ?></p>
    <p class="contenu"><?php t('artwork.book.2'); ?></p>
    <p class="contenu"><?php t('artwork.book.3'); ?></p>
    <?php render_artwork_block($pdo, 'artwork.books'); ?>
  </div>
</section>
<!---- Press section --->
<section id="press">
  <div class="container section-title" data-aos="fade-up"><h2><?php t('artwork.presse.title'); ?></h2></div>
  <div class="container" data-aos="fade-up" data-aos-delay="100">
    <p class="tldr"><?php t('artwork.presse.tldr'); ?></p>
    <p class="contenu"><?php t('artwork.comment'); ?></p>
    <?php render_artwork_block($pdo, 'artwork.presse'); ?>
</section>

<!--- Outro section --->
<section id="outro">
  <div class="container section-title" data-aos="fade-up"><h2><?php t('artwork.title.outro'); ?></h2></div>
  <div class="container" data-aos="fade-up" data-aos-delay="100">
    <p class="contenu"><?php t('artwork.outro.1'); ?></p>
    <p class="contenu"><?php t('artwork.outro.2'); ?></p>
    <p class="contenu"><span class="vinun">• <?php t('artwork.outro.3'); ?></span></p>
    <p class="contenu"><span class="vinun">• <?php t('artwork.outro.4'); ?></span></p>
    <p class="contenu"><span class="vinun">• <?php t('artwork.outro.5'); ?></span></p>
    <p class="contenu"><?php t('artwork.outro.6'); ?></p>
    <p class="contenu"><?php t('artwork.outro.7'); ?></p>
  </div>
</section>