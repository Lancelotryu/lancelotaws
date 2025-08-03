<header id="header" class="header d-flex flex-column justify-content-center">
<i class="header-toggle d-xl-none bi bi-list"></i>
<div class="header-container d-flex flex-column align-items-start">
<nav id="navmenu" class="navmenu">
  <ul id="menu">
        <li><a href="/<?= $l ?>/home"><?php icon('house-nav'); t('nav.home'); ?></a></li>
        <li><a href="/<?= $l ?>/home#skills"><?php icon('tools-nav'); t('nav.skills'); ?></a></li>
        <li><a href="/<?= $l ?>/projects"><?php icon('laptop-nav'); t('nav.projects'); ?></a></li>
        <li><a href="/<?= $l ?>/artwork"><?php icon('music-note-beamed-nav'); t('nav.artwork'); ?></a></li>
        <li><a href="/<?= $l ?>/poetry"><?php icon('feather-nav'); t('nav.poetry'); ?></a></li>
        <li><a href="/<?= $l ?>/home#contact"><?php icon('envelope-nav'); t('nav.contact'); ?></a></li>
  </ul>
</nav>
<div class="social-links text-center">
      <a href="<?php echo "/en/" . $section; ?>" title="English"><span class="fi fi-gb"></span></a>
      <a href="<?php echo "/fr/" . $section; ?>" title="Français"><span class="fi fi-fr"></span></a>
      <a href="<?php echo $site['instagram']; ?>"><?php icon('instagram'); ?></a>
      <a href="<?php echo $site['github']; ?>"><?php icon('github'); ?></a>
      <a href="<?php echo $site['linkedin']; ?>"><?php icon('linkedin'); ?></a>
</div>
</div>
</header>