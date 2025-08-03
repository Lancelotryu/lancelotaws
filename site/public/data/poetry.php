<h1><?php t('nav.poetry'); ?></h1>

<?php
//We make a query to table "poemes" to get all the poems.
$stmt = $pdo->query("
    SELECT recueil, titre, contenu, id_logique
      FROM poemes
  ORDER BY id_logique ASC
");
$poemes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!--- Short intro to poetry. There we use the functions to count number of poems and number of lines. --->
<section id="intro">
<div class="container" data-aos="fade-up" data-aos-delay="100">
<p class="tldr"><?php t('poetry.intro.tldr'); ?></p>
<p class="contenu"><?php t('poetry.intro.1'); ?></p>
<p class="contenu"><?php echo "<b>" . count($poemes) . "</b> ";  t('poetry.intro.2'); ?></p>
<p class="contenu"><?php echo "<b>" . count_poem_lines($pdo) . "</b> ";  t('poetry.intro.3'); ?></p>
<p class="contenu"><?php t('poetry.intro.4'); ?></p>
<?php if ($l == 'en') {echo '<p class="contenu">'; t('poetry.intro.5'); echo '</p>';} ?>
</div>

<!--- Displaying poems in English --->
<?php
if ($l == 'en') {
  foreach ($ids as $fragment) {
    $id_logique = '13-trad-' . $fragment;
    render_poeme_by_id($poemes, $id_logique);
  }
}
?>
</div>

<!--- Displaying the full compilation in French --->
<p class="titre-igne"><?php t('keyword.igne'); ?></p>
<?php if ($l == 'en') {echo '<p class="auteurfdl">(<i>'; t('keyword.burning'); echo '</i>)</p>';} ?>
<p class="auteurfdl"><?php t('keyword.by'); echo (' ' . $site['author']) ?></p>
<?php render_recueils($poemes); ?>