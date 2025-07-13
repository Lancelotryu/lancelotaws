<!--- I have automated most of the sections of this page in PHP.
      The finale page is quite long, but the code is pretty short.
I have several function:
* render_blocks for the basic blocks
* render_skills for the skills sections
* render_cv_block for the left column of the resume
* print_cv_items for the right column of the resume  --->

<!--- Hero section --->
    <section id="hero">
<div class="container">
  <div class="row">
    <div class="col-lg-6" data-aos="fade-right" data-aos-delay="100">
      <h1><?php echo $site['name']; ?></h1>
      <?php render_blocks($pdo, 'home.hero', $l); ?>
<div class="text-center">
      <a href="/<?= $l ?>/projects" class="btn btn-lancelot"><?php t('button.projects'); ?></a>
      <a href="/<?= $l ?>/artwork" class="btn btn-lancelot"><?php t('button.artwork'); ?></a>
</div>
    </div>
    <div class="col-lg-6" data-aos="fade-left" data-aos-delay="200">
        <img src="/assets/img/profile/lancelot.png" alt="<?php echo $site['name']; ?>" class="profile-image">
    </div>
  </div>
</div>
    </section>

<!--- About Section --->
    <section id="about">
<div class="container section-title" data-aos="fade-up"><h2><?php t('home.section.about'); ?></h2></div>
<div class="container" data-aos="fade-up" data-aos-delay="100">
<?php render_blocks($pdo, 'home.about', $l); ?>
</div>
    </section>

<!--- Skills Section --->
    <section id="skills" class="skills section">
<div class="container section-title" data-aos="fade-up"><h2><?php t('home.section.skills'); ?></h2></div>
<div class="container" data-aos="fade-up" data-aos-delay="100">
<?php render_skills($pdo, $l); ?>
</div>
    </section>

<!--- Resume Section --->
    <section id="resume" class="resume section">
<div class="container section-title" data-aos="fade-up"><h2><?php t('home.section.resume'); ?></h2>
<h3><?php echo $site['name']; ?></h3>
<h5><?php t('cv.intro.label'); ?></h5>
<p class="blabla"><?php t('cv.intro.text'); ?></p>
</div>

<div class="container" data-aos="fade-up" data-aos-delay="100">
<div class="row gy-4">

<!-- Left column with summary & contact -->
<div class="col-lg-4">
<div class="resume-side" data-aos="fade-right" data-aos-delay="100">
<div class="profile-img round-frame mb-4"><img src="assets/img/profile/cv.png" alt="Profile" class="img-fluid round-frame"></div>
<h3 class="mt-4"><?php t('cv.contact.title'); ?></h3>
<ul class="contact-info list-unstyled">
<li><?php icon('geo-alt'); t('cv.contact.location'); ?></li>
<li><?php icon('envelope'); echo $site['email']; ?></li>
<li><?php icon('telephone'); echo $site['phone']; ?></li>
<li><?php icon('linkedin'); echo $site['linkedinhref']; ?></li>
</ul>

<?php render_cv_block($pdo, $l); ?>
</div></div>
<div class="col-lg-8 ps-4 ps-lg-5">

<!--- Right column with experience & education --->
<?php
print_cv_items('xp', 'briefcase', 100, $l);
print_cv_items('educ', 'mortarboard', 200, $l);
print_cv_items('certif', 'award', 300, $l);
?>
<!--- Some non indented closing div --->
</div></div></div></div>
    </section>

<!--- Contact Section --->
    <section id="contact" class="contact section">
<div class="container section-title" data-aos="fade-up"><h2><?php t('home.section.contact'); ?></h2></div>
<div class="container">
<div class="row">
  <div class="col-md-8 offset-md-2">
    <div class="row">
      <div class="col-4">
        <h4><?php icon('geo-alt'); echo "&nbsp;&nbsp;"; t('home.contact.location.label'); ?></h4>
        <p><?php t('home.contact.location.text'); ?></p>
      </div>
      <div class="col-4">
        <h4><?php icon('telephone'); echo "&nbsp;&nbsp;"; t('home.contact.phone.label'); ?></h4>
        <p><?php echo $site['phone']; ?></p>
      </div>
      <div class="col-4">
        <h4><?php icon('envelope'); echo "&nbsp;&nbsp;"; t('home.contact.email.label'); ?></h4>
        <p><?php echo $site['email']; ?></p>
      </div>
    </div>
    <form action="forms/contact.php" method="post" class="php-email-form">
      <div class="row gy-4 mt-5">
        <div class="col-md-6">
<input type="text" name="name" class="form-control" placeholder="<?php t('home.contact.touch.yourname'); ?>" required="">
        </div>
        <div class="col-md-6 ">
<input type="email" class="form-control" name="email" placeholder="<?php t('home.contact.touch.youremail'); ?>" required="">
        </div>
        <div class="col-12">
<input type="text" class="form-control" name="subject" placeholder="<?php t('home.contact.touch.subject'); ?>" required="">
        </div>
        <div class="col-12">
<textarea class="form-control" name="message" rows="6" placeholder="<?php t('home.contact.touch.message'); ?>" required=""></textarea>
        </div>
        <div class="col-12 text-center">
          <div class="loading"><?php t('home.contact.touch.loading'); ?></div>
          <div class="error-message"></div>
          <div class="sent-message"><?php t('home.contact.touch.thankyou'); ?></div>
          <button type="submit" class="btn btn-lancelot"><?php t('home.contact.touch.send'); ?></button>
        </div>
      </div>
    </form>
  </div>
</div>
</div>
    </section>