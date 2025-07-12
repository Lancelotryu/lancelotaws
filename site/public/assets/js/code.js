// Wait until the DOM is fully loaded before running the script
document.addEventListener('DOMContentLoaded', function () {

(function() {
  "use strict";
// Select the header toggle button element
  const headerToggleBtn = document.querySelector('.header-toggle');

//Toggle header visibility and switch icon classes
  function headerToggle() {
// Show or hide the header by toggling its CSS class
    document.querySelector('#header').classList.toggle('header-show');
// Switch the toggle button icon between hamburger and close
    headerToggleBtn.classList.toggle('bi-list');
    headerToggleBtn.classList.toggle('bi-x');
  }
// Attach click event to the header toggle button
  headerToggleBtn.addEventListener('click', headerToggle);

//Hide mobile navigation when clicking on same-page/hash links
  document.querySelectorAll('#navmenu a').forEach(navLink => {
    navLink.addEventListener('click', () => {
//If the mobile menu is open, close it
      if (document.querySelector('.header-show')) {
        headerToggle();
      }
    });
  });

//Initialize AOS (Animate On Scroll) with custom settings
  function aosInit() {
    AOS.init({
      duration: 600,
      easing: 'ease-in-out',
      once: true,
      mirror: false 
    });
  }
// Run AOS initialization after the page fully loads
  window.addEventListener('load', aosInit);
})();
//Animate the skills items on reveal
  let skillsAnimation = document.querySelectorAll('.skills-animation');
  skillsAnimation.forEach((item) => {
    new Waypoint({
      element: item,
      offset: '80%',
      handler: function(direction) {
        let progress = item.querySelectorAll('.progress .progress-bar');
        progress.forEach(el => {
          el.style.width = el.getAttribute('aria-valuenow') + '%';
        });
      }
    });
  });


//Correct scrolling position upon page load for URLs containing hash links.
  window.addEventListener('load', function(e) {
    if (window.location.hash) {
      if (document.querySelector(window.location.hash)) {
        setTimeout(() => {
          let section = document.querySelector(window.location.hash);
          let scrollMarginTop = getComputedStyle(section).scrollMarginTop;
          window.scrollTo({
            top: section.offsetTop - parseInt(scrollMarginTop),
            behavior: 'smooth'
          });
        }, 100);
      }
    }
  });

//Navmenu Scrollspy
  let navmenulinks = document.querySelectorAll('.navmenu a');

  function navmenuScrollspy() {
    navmenulinks.forEach(navmenulink => {
      if (!navmenulink.hash) return;
      let section = document.querySelector(navmenulink.hash);
      if (!section) return;
      let position = window.scrollY + 200;
      if (position >= section.offsetTop && position <= (section.offsetTop + section.offsetHeight)) {
        document.querySelectorAll('.navmenu a.active').forEach(link => link.classList.remove('active'));
        navmenulink.classList.add('active');
      } else {
        navmenulink.classList.remove('active');
      }
    })
  }
  window.addEventListener('load', navmenuScrollspy);
  document.addEventListener('scroll', navmenuScrollspy);

});


document.addEventListener('DOMContentLoaded', () => {
  // only run this code on pages that actually have a #nouvelle-container
  const container  = document.getElementById('nouvelle-container');
  if (!container) return;
  // Grab key elements
  const content      = document.getElementById('nouvelle-content');    // where we inject the story HTML
  const closeBtn     = document.getElementById('btn-close-nouvelle');  // “Fermer” button
  const anchorNouv   = document.getElementById('shortnovels');         // section to scroll back to
  let   currentNovel = null;                                           // track which story is open

  /**
   * Show the close button & scroll the collapse into view
   */
  container.addEventListener('shown.bs.collapse', () => {
    closeBtn.classList.remove('d-none');
    container.scrollIntoView({ behavior: 'smooth', block: 'start' });
  });

  /**
   * Hide the close button when collapse is closed
   */
  container.addEventListener('hidden.bs.collapse', () => {
    closeBtn.classList.add('d-none');
  });

  /**
   * Close button handler:
   *  - hide the collapse
   *  - clear the currentNovel flag
   *  - scroll back up to the main list
   */
  closeBtn.addEventListener('click', () => {
    const bs = bootstrap.Collapse.getOrCreateInstance(container, { toggle: false });
    bs.hide();
    currentNovel = null;
    if (anchorNouv) {
      anchorNouv.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }
  });

  /**
   * Click handler for each “show-nouvelle” link:
   *  - if clicking the same open story, just close it
   *  - otherwise, fetch the new story HTML and open the collapse
   */
  document.querySelectorAll('.show-nouvelle').forEach(el => {
    el.addEventListener('click', e => {
      e.preventDefault();  // prevent default anchor behavior
      const name = el.dataset.nouvelle;
      const bs   = bootstrap.Collapse.getOrCreateInstance(container, { toggle: false });

      // Toggle off if it's the same story
      if (container.classList.contains('show') && currentNovel === name) {
        bs.hide();
        currentNovel = null;
        return;
      }

      // Otherwise fetch and display new content
      fetch(`../lancelot/includes/nouvelle.php?name=${encodeURIComponent(name)}`)
        .then(response => {
          if (!response.ok) throw new Error('Network error');
          return response.text();
        })
        .then(html => {
          content.innerHTML = html;
          bs.show();
          currentNovel = name;
        })
        .catch(() => {
          content.innerHTML = `
            <p class="text-danger">
              Impossible de charger la nouvelle « ${name} ».
            </p>`;
        });
    });
  });
});
