<?php
/* Getting the .env file from private subnet. */
function load_env($path) {
    if (!file_exists($path)) {
        die("Can't find the .env file : $path");
    }

    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        $line = trim($line);
        if ($line === '' || str_starts_with($line, '#')) continue;
        if (!str_contains($line, '=')) continue;

        list($key, $value) = explode('=', $line, 2);
        $key = trim($key);
        $value = trim($value, "\"' ");

        putenv("$key=$value");
        $_ENV[$key] = $value;
        $_SERVER[$key] = $value;
    }
}


/* Translation functions */

//I put my translated text throught htmlspecialchar() for security.
//But I need some HTML tags, so I use this special fonction to escape HTML tags
//except some useful harmless ones, like <br>, <i>, <em> or <b>
function allowtags(
    string $text,
    ?array $siteVars = null,
    array $allowedTags = ['br','i','b','em']
): string {
    // Si l’appel ne fournit pas $siteVars, on prend le global
    if ($siteVars === null) {
        global $site;
        $siteVars = $site;
    }

    // (1) Remplacements dynamiques
    if (!empty($siteVars)) {
        $repl = [];
        foreach ($siteVars as $var => $value) {
            $repl['$' . $var] = $value;
        }
        $text = strtr($text, $repl);
    }

    // (2) Échappement complet
    $escaped = htmlspecialchars($text, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');

    // (3) Restauration des balises autorisées
    if (!empty($allowedTags)) {
        $tags    = implode('|', array_map('preg_quote', $allowedTags));
        $pattern = "#&lt;(/?(?:$tags))&gt;#i";
        $escaped = preg_replace_callback($pattern, function(array $m) {
            return '<' . strtolower($m[1]) . '>';
        }, $escaped);
    }

    return $escaped;
}


//I use the function "translate" a lot, so I named it "t".
//As t function echoes the variable, I made a t_return when I need to not echo it.
function t_return(string $key): string {
    ob_start();
    t($key);
    return trim(ob_get_clean());
}


//t function gets translation ($l stands for "language") into MySQL database and displays it.
function t(string $key): void {
    global $pdo, $l;

//SQL request
    $stmt = $pdo->prepare("SELECT content FROM translations WHERE lang = :lang AND `key` = :key LIMIT 1");
    $stmt->execute([':lang' => $l, ':key' => $key]);
    $text = $stmt->fetchColumn() ?: "[$key]";
    $text = allowtags($text);
    echo $text;
}


/* Render resume functions */

//Function render_skills displays my skills on the homepage.
function render_skills(PDO $pdo, string $lang = 'en'): void
{
//SQL request
    $stmt = $pdo->prepare("SELECT `key`, content FROM translations WHERE lang = :lang AND `key` LIKE 'skills.%' ORDER BY `key` ASC");
    $stmt->execute([':lang' => $lang]);
    $results = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);

    $sections = [];
//each translation item is identified with a key page.section.subsection.item so I split the key.
    foreach ($results as $key => $content) {
        $parts = explode('.', $key);
        if (count($parts) < 4) continue;

        [$s, $sectionKey, $subKey, $field] = $parts;

        if (!isset($sections[$sectionKey])) {
            $sections[$sectionKey] = [
                'title' => '',
                'items' => []
            ];
        }

        if ($field === 'title') {
            $sections[$sectionKey]['title'] = $content;
        } else {
            if (!isset($sections[$sectionKey]['items'][$subKey])) {
                $sections[$sectionKey]['items'][$subKey] = [];
            }
            $sections[$sectionKey]['items'][$subKey][$field] = $content;
        }
    }

    foreach ([['1support', '2infra'], ['3frontback', '4cross']] as $rowSections) {
        echo "<div class=\"row\">\n";

        foreach ($rowSections as $key) { //Displaying the HTML code
            if (!isset($sections[$key])) continue;
            $section = $sections[$key]; 
            echo "<div class=\"col-lg-6\">"; 
            echo "<div class=\"skills-category\" data-aos=\"fade-up\" data-aos-delay=\"200\">";
            echo "<h3>" . allowtags($section['title']) . "</h3>";
            echo "<div class=\"skills-animation\">";

            foreach ($section['items'] as $item) {
                echo "<div class=\"skill-item\">";
                echo "<div class=\"d-flex justify-content-between align-items-center\">";
                echo "<h4>" . allowtags($item['label'] ?? '') . "</h4>";
                echo "<span class=\"skill-percentage\">" . allowtags($item['level'] ?? '0') . "%</span>";
                echo "</div>";
                echo "<div class=\"progress\">";
                echo "<div class=\"progress-bar\" role=\"progressbar\" aria-valuenow=\"" . allowtags($item['level'] ?? '0') . "\" aria-valuemin=\"0\" aria-valuemax=\"100\"></div>";
                echo "</div>";
                if (isset($item['text'])) {echo "<div class=\"skill-tooltip\">" . allowtags($item['text']) . "</div>";}
                echo "</div>\n";
            }
            echo "</div></div></div>\n";
        }
        echo "</div>\n";
    }
}


//This function displays the whole section of translated text.
//For a key page.section.subsection.item, it will retrieve all the keys with the specified section.
function render_blocks(PDO $pdo, string $prefix, string $lang = 'en'): void
{
    $stmt = $pdo->prepare("
        SELECT `key`, content 
        FROM translations 
        WHERE lang = :lang 
        AND `key` LIKE :prefix 
        ORDER BY `key` ASC
    ");
    $stmt->execute([
        ':lang' => $lang,
        ':prefix' => "$prefix.%"
    ]);
    $results = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);

    $tldrKey = $prefix . '.tldr'; //Displays the .tldr item in the beginning if it exists.
    if (isset($results[$tldrKey])) {echo "<div class=\"tldr\">" . $results[$tldrKey] . "</div>\n";
        unset($results[$tldrKey]); //We remove the .tldr item so it is not displayed again later
    }

    foreach ($results as $key => $content) {
        $suffix = substr($key, strlen($prefix) + 1);
        if (ctype_digit($suffix)) { //Displays only "integer" translation items.
            echo "<p class=\"contenu\">" . allowtags($content) . "</p>\n";
        }
    }
}


//Same role as render_block function, but special one for the left column of my resume.
function render_cv_block(PDO $pdo, string $lang = 'en'): void
{
    $stmt = $pdo->prepare("
SELECT `key`, content 
FROM translations 
WHERE lang = :lang 
  AND (
    `key` LIKE 'cv.1tech.%' OR
    `key` LIKE 'cv.2creative.%' OR
    `key` LIKE 'cv.3interests.%'
  )
ORDER BY `key` ASC
    ");
    $stmt->execute([':lang' => $lang]);
    $results = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);

    $sections = [];

    foreach ($results as $key => $content) {
        $parts = explode('.', $key);

        if (count($parts) < 4) continue;
        $sectionId = $parts[1];
        $subKey    = $parts[2] ?? null;
        $field     = $parts[3] ?? null;

        if ($subKey === 'title') {//Check if it's subsection title
            $sections[$sectionId]['title'] = $content;
        }
        elseif ($field === 'label') {//Other items (label or text)
            $sections[$sectionId]['items'][$subKey]['label'] = $content;
        } elseif ($field === 'text') {
            $sections[$sectionId]['items'][$subKey]['text'] = $content;
        }
    }

    foreach ($sections as $section) {//Displays the HTML
        echo "<h3>" . allowtags($section['title'] ?? '') . "</h3>\n";
        foreach ($section['items'] as $item) {
            echo "<h5 class=\"competence\">🔹" . allowtags($item['label'] ?? '') . "</h5>\n";
            if (isset($item['text'])) {
                echo "<p>" . allowtags($item['text']) . "</p>\n";
            } elseif (isset($item['multi'])) {
                foreach ($item['multi'] as $line) {
                    echo "<p>" . allowtags($line) . "</p>\n";
                }
            }
        }
    }
}


//Function to displays the right column of my resume.
function print_cv_items($section, $nameicon, $nbr, $lang): void {
$allowed = ['xp', 'educ', 'certif'];
if (!in_array($section, $allowed, true)) {
    throw new InvalidArgumentException("Invalid section: $section");
}
    global $icon;
    global $pdo;
    $sql = "SELECT `key`, `content`
            FROM translations
            WHERE lang = :lang
              AND `key` LIKE :pattern";

    $pattern = "cv.$section.%";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'lang' => $lang,
        'pattern' => $pattern
    ]);
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $entries = [];
    foreach ($rows as $row) {
        $parts = explode('.', $row['key']);
        if (count($parts) < 4) continue;

        $id = $parts[2];
        $field = $parts[3];

        $entries[$id][$field] = $row['content'];
    }

    ksort($entries);

    echo ('<div class="resume-section" data-aos="fade-up" data-aos-delay="' . $nbr . '">');
    echo ('<h3>' . $icon[$nameicon]);
    t('cv.' . $section . '.title');
    echo ('</h3>');

    foreach ($entries as $data) {
        $label    = $data['label']    ?? '';
        $year    = $data['year']    ?? '';
        $company  = $data['company']  ?? '';
        $school  = $data['school']  ?? '';
        $location = $data['location'] ?? '';

        echo '<div class="resume-item">';
        echo "  <h4>$label</h4>";
        echo "  <h5>$year</h5>\n";
        if ($school) {echo '  <p class="company"><i class="bi bi-building"></i> ' . allowtags($school) . '</p>' . PHP_EOL;}
        if ($location) {echo '  <p class="company"><i class="bi bi-building"></i> ' . allowtags($company) . ' (' . allowtags($location) . ')</p>' . PHP_EOL;}
        echo "<ul>\n";

        foreach ($data as $k => $v) {
            if (is_int($k)) {echo "<li>" . allowtags($v) . "</li>\n";}
        }
        echo "  </ul>\n";
        echo "</div>\n";
    }
}


//Last function for resume part, displaying Education & Certifications
function print_education_items(string $lang = 'en'): void {
        $id    = $parts[2];
        $field = $parts[3];
    foreach ($entries as $data) {
        $label  = $data['label']  ?? '';

        echo '<div class="resume-item">' . PHP_EOL;
        echo "  <h4>$label</h4>";
        echo "  <h5>$year</h5>";
        if ($school) {echo '  <p class="company"><i class="bi bi-building"></i> ' . allowtags($school) . '</p>' . PHP_EOL;}
        echo "</div>\n";
    }
}


//Displays icons in the var.php file.
function icon(string $name): void {
  global $icon;
  echo $icon[$name];
}


/* Render poetry */

//An extremely useful function which count how many poetry lines I wrote.
function count_poem_lines(PDO $pdo): int
{
    $sql  = "SELECT contenu FROM poemes
             WHERE id_logique NOT LIKE '%-en'";//we remove poems with ID -en because it's just translated poems
    $stmt = $pdo->query($sql);

    $totalLines = 0;

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $content = $row['contenu'];

        //We split lines
        $lines = preg_split('/\r\n|\n|\r/', $content);

        foreach ($lines as $line) {
            $trimmed = ltrim($line);
            if ($trimmed === '') {
                continue;// ignore empty lines
            }
            // ignore if starts by <p, <h3, <h4 ou ² because it will line with no valid text
            if (preg_match('/^(<p|<h3|<h4|²)/i', $trimmed)) {
                continue;
            }
            $totalLines++;
        }
    }

    return $totalLines;
}


//A short function to count how many poems I have written.
function count_poemes(PDO $pdo): int
{
    //We just count how many entries in the table
    $stmt = $pdo->query("SELECT COUNT(*) AS total FROM poemes");
    $total = (int) $stmt->fetchColumn();
		$total = $total -7; //I remove 7 for the 7 poems that are just translated in English
    return $total;
}
//This function convert the tags ²int, ²rfn, ²itl to proper text like [Intro], [verse] etc.
function replace_markers(string $html, string $lang = 'en'): string
{
    global $markerLabels;

    return preg_replace_callback(
// Match a <p class="strophe"> that begins with ²keyNum<br/>, 
// capture key, number, and the rest of the paragraph content
        '~<p\s+class="strophe">\s*²([a-z]{3})(\d*)(?:<br\s*/?>)\s*([\s\S]*?)</p>~i',
        function(array $m) use ($markerLabels, $lang) {
            [$all, $key, $num, $body] = $m;

// build the label
            $label = $markerLabels[$key][$lang] ?? strtoupper($key);
            if (in_array($key, ['cpl','rfn'], true) && $num !== '') {
                $label .= ' ' . $num;
            }

            // output: marker paragraph + strophe paragraph with only the body
            return
                '<p class="marqueur">[' . htmlspecialchars($label, ENT_QUOTES) . ']</p>' . "\n"
              . '<p class="strophesong">' . PHP_EOL . $body . '</p>';
        },
        $html
    );
}


//A full poem is stocked in one MySQL entry, already in HTML
//So it should be very easy to display it.
//However, I center each poem in a <div> "poem-box".
//For aesthetic reasons, I need to exclude the title from the <div>.
//And that's how a 3 lines function becomes a 20 lines function.
//But notice how naming the variables in French is elegant.
function render_poeme(array $poeme): void
{
    //replace all tags
    $lang = $poeme['lang'] ?? 'fr';
    $contenu = replace_markers($poeme['contenu'], $lang);

    $lignes = explode("\n", $contenu);
    $nouveauContenu = '';
    $premierP = false;

    foreach ($lignes as $ligne) {
        $ligneTrim = ltrim($ligne);

        if (str_starts_with($ligneTrim, '<h3') || str_starts_with($ligneTrim, '<h4')) {
            $nouveauContenu .= $ligne . "\n";
        } elseif (!$premierP && str_starts_with($ligneTrim, '<p')) {
            $nouveauContenu .= "<div class=\"poeme-box\">\n";
            $nouveauContenu .= $ligne . "\n";
            $premierP = true;
        } else {
            $nouveauContenu .= $ligne . "\n";
        }
    }

    echo $nouveauContenu;
    echo "</div>";
}


//To displays all the poems, I need to retrieve them from the MySQL database.
//I also need to retrieve the titles of each "recueil" (compilation?)
//And as I display poems in English separately, I exclude the folder in English.
function render_recueils(array $poemes): void
{
    $dernier_recueil = null;
    foreach ($poemes as $poeme) {
        if ($poeme['recueil'] == '13 - Traductions') {continue;} //excluding English folder
        if ($poeme['recueil'] !== $dernier_recueil) {
            if ($dernier_recueil !== null) {
                echo "</section>";
            }
//To give a nice section ID in HTML, I need to process the $titre (title) like that :
            $titre = preg_replace('/^\d+\s*-\s*/', '', $poeme['recueil']);
            $titre = mb_strtolower($titre);
            $titre = iconv('UTF-8', 'ASCII//TRANSLIT', $titre);
            $titre = preg_replace('/[^a-z]/', '', $titre);
            $titre = substr($titre, 0, 7);
            echo "<section id=\"" . $titre . "\">";
            echo "<div class=\"container section-title\">";
            echo "<h2 class=\"recueil\">" . substr($poeme['recueil'], 5) . "</h2></div>";
             $dernier_recueil = $poeme['recueil'];
        }
        render_poeme($poeme);
    }
//dernier_recueil means last_compilation
    if ($dernier_recueil !== null) {
        echo "</section>";
    }
}


//A short function if I need to render a poem by using his MySQL ID.
function render_poeme_by_id(array $poemes, string $id_logique): void
{
    $id_logique = trim($id_logique);
    foreach ($poemes as $poeme) {
        if (trim($poeme['id_logique']) === $id_logique) {
            render_poeme($poeme);
            return;
        }
    }
}


//I display many different things on the Artwork page, but in a quite simple way.
//So this function alone is enough to display most of the sections of the Artwork page.
function render_artwork_block(PDO $pdo, string $prefix): void
{
    global $l;  // 'en' or 'fr'
    
    // Pull only the rows for the current language
    $stmt = $pdo->prepare("
        SELECT `key`, content
          FROM translations
         WHERE `lang` = :lang
           AND `key` LIKE :prefix
         ORDER BY `key` ASC
    ");
    $stmt->execute([
        ':lang'   => $l,                // will match your `lang` column
        ':prefix' => $prefix . '.%'     // e.g. "artwork.music.%"
    ]);
    $translations = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);

    $blocs = [];
//Exploding keys
foreach ($translations as $key => $content) {
    $parts = explode('.', $key); // ex: artwork.music.jamestown.1
    if (count($parts) < 4) continue;

    $section = $parts[1];         // music
    $bloc = $parts[2];         // jamestown
    $suffix = $parts[3];       // label, 1, 2...

    if ($suffix === 'tldr') continue;

    if (!isset($blocs[$bloc])) {
        $blocs[$bloc] = [
            'label' => '',
            'website' => null,
            'lines' => []
        ];
    }

    if ($suffix === 'label') {
        $blocs[$bloc]['label'] = $content;
    } elseif ($suffix === 'website') {
        $blocs[$bloc]['website'] = $content;
    } elseif (ctype_digit($suffix)) {
        $blocs[$bloc]['lines'][] = $content;
    }
}

    $index = 0;
uksort($blocs, 'strnatcmp');
    foreach ($blocs as $name => $data) {
        $index++;

        $textCol = 'col-md-8';
        $imgCol  = 'col-md-4';

        if ($index % 2 === 0) {
            $textCol .= ' order-md-2';
            $imgCol  .= ' order-md-1';
        }

        echo '<div class="row align-items-center my-5">';
        echo '<div class="' . $textCol . '">';//Displaying the text from translation table.
        if ($data['label']) {
            echo "<h3>" . allowtags($data['label']) . "</h3>\n";
        }
if (!empty($data['website'])) {
    echo '<p><a href="' . allowtags($data['website']) . '" target="_blank" rel="noopener">';
    t('artwork.link'); echo "</a></p>\n";
}
        foreach ($data['lines'] as $line) {
            echo "<p>" . allowtags($line) . "</p>\n";
        }
        echo "</div>\n";
        $imgPath = "assets/img/" . $section . "/" . $name . '.png';//Displaying the picture
        echo '<div class="' . $imgCol . ' text-center">';
        if ($imgPath) {
            echo '<img src="' . $imgPath . '" alt="' . $name . '" class="img-fluid">';
        } else {
            echo "<p class=\"text-muted\">Image manquante : ' . $name . '</p>\n";
        }
        echo "</div>\n";
        echo "</div>\n";
    }
}


//A short function to display the 6 buttons of the Artwork page.
function render_section_buttons(array $sections, string $lang = 'en'): void
{
    echo '<div class="row g-3 justify-content-center">';
    foreach ($sections as $section) {
        $id = allowtags($section['id']);
        $label = t_return($section['label']); //translated name of the button
        $icon = allowtags($section['icon']);

        echo '<div class="col-6 col-md-4">';
        echo '<a href="' . $lang . '/artwork#' . $id . '" class="btn btn-lancelot w-100 d-flex align-items-center justify-content-center gap-2">';
        icon($icon);
        echo ' ' . $label . '</a>';
        echo '</div>';
    }
    echo '</div>';
}


/* A function that convert short novels in plain text to HTML.
   There are some markdown inspired tags in the plain text.

#…                    => <h4>…</h4>
regular lines         => <p class="nouvelle">…</p> (margin-top: 1em)
lines tagged >=       => <p class="paragraphe"></p> (margin-top: 2em)
lies tagged >==       => <p class="gparagraphe"></p> (margin-top: 3em)
<< and >>             => French quotation marks «  »
classic markdown *…*  => italic <em>…</em> */

function convert_novel(string $filePath): string
{
    if (!is_readable($filePath)) {return '<p><em>Fichier introuvable ou non lisible.</em></p>';}

//Read and normalize return to line
    $raw = file_get_contents($filePath);
    $raw = str_replace(["\r\n", "\r"], "\n", trim($raw));
    $lines = explode("\n", $raw);
    $html = '';

    foreach ($lines as $line) {
        $txt = trim($line);
        if ($txt === '') {
            continue;//Ignoring empty lines
        }

        if (mb_substr($txt, 0, 1) === '#') {//Title
            $titre = htmlspecialchars(mb_substr($txt, 1), ENT_QUOTES, 'UTF-8');
            $html .= "<h4>$titre</h4>\n";
            continue;
        }

        if ($txt === '>=') {//Transforming the tags into empty lines with CSS
            $html .= "<p class=\"paragraphe\"></p>\n";
            continue;
        }
        if ($txt === '>==') {
            $html .= "<p class=\"gparagraphe\"></p>\n";
            continue;
        }

        $escaped = htmlspecialchars($txt, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
        $escaped = str_replace(['<<', '>>'], ['&laquo; ', ' &raquo;'], $escaped); //French quotation marks
        $escaped = preg_replace('/\*(.+?)\*/u', '<em>$1</em>', $escaped); //Italic
        $html .= sprintf("<p class=\"nouvelle\">%s</p>\n", $escaped); //And finally regular paragraphs

    }
    return $html; //returning a nice HTML content ready to use.
}


//Converting the shortnovels was just the beginning, we have to render the page with them.
function render_shortnovels(PDO $pdo, string $lang, string $imageBasePath = 'assets/img/snovels/', string $ryuPathBase = 'data/nouvelles/'): void
{
// 1. Get translations from database
    $stmt = $pdo->prepare("
        SELECT `key`, content
        FROM translations
        WHERE `key` LIKE 'artwork.snovels.%'
        ORDER BY `key` ASC
    ");
    $stmt->execute();
    $translations = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);

    $blocs = [];

// 2. Splitting keys (as usual...)
    foreach ($translations as $key => $content) {
        $parts = explode('.', $key);
        if (count($parts) < 4) continue;

        $bloc = $parts[2];     // ex: 'sixmillions'
        $suffix = $parts[3];   // ex: title, imgcom, text, hashtags

        if (!isset($blocs[$bloc])) {
            $blocs[$bloc] = [
                'title' => '',
                'imgcom' => '',
                'text' => '',
                'hashtags' => ''
            ];
        }

        if (array_key_exists($suffix, $blocs[$bloc])) {
            $blocs[$bloc][$suffix] = $content;
        }
    }

// 3. Displays Bootstrap cards with novel summary in it.
    echo "<div class=\"row row-cols-1 row-cols-md-3 g-4\">\n";

    foreach ($blocs as $name => $data) {
        $trimname = substr($name, 1);
        $cardId = 'collapse_' . $trimname;
        $imgPath = $imageBasePath . $name . '.png';
        $ryuPath = $ryuPathBase . $trimname . '.ryu';
        $ryuContent = file_exists($ryuPath) ? convert_novel($ryuPath) : '<p><em>Missing file.</em></p>';
        echo "<div class=\"col\">\n";
        echo "<div class=\"card h-100 shadow-sm\">\n";

//The picture illustrating the short novel
        if ($imgPath) {
            echo "<a href=\"javascript:void(0)\" class=\"show-nouvelle text-decoration-none\" data-nouvelle=\"" . $trimname . "\">\n";
            echo "<img src=\"" . $imgPath . "\" class=\"card-img-top\" alt=\"" . allowtags($data['imgcom']) . "\"></a>\n";
        }
        echo "<div class=\"card-body\">\n";

//The hashtags for short novel description
        if (!empty($data['hashtags'])) {
            echo "<p class=\"card-text\"><i>" . allowtags($data['hashtags']) . "</i></p>\n";
        }

//The clickable title
       echo "<h5 class=\"card-title\">\n";
       echo "<a href=\"javascript:void(0)\" class=\"show-nouvelle text-decoration-none\" data-nouvelle=\"" . $trimname . "\">\n";
       echo allowtags($data['title']);
       echo "</a></h5>\n";
//The short summary
        echo "<p class=\"card-text\">" . nl2br(allowtags($data['text'])) . "</p>\n";
        echo "</div></div></div>\n";
    }

    echo "</div>\n";
}

function render_projects(PDO $pdo): void
{
	global $l;
    $stmt = $pdo->prepare("
        SELECT `key`, content 
        FROM translations 
        WHERE lang = :lang 
        AND `key` LIKE 'projects.%'
    ");
    $stmt->execute([':lang' => $l]);
    $results = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);

    // sorting by number
    $ordered = [];

    foreach ($results as $key => $content) {
        $parts = explode('.', $key);
        if (count($parts) !== 3) continue;

        [, $index, $tag] = $parts;
        if (!isset($ordered[$index])) {
            $ordered[$index] = [];
        }
        $ordered[$index][$tag] = $content;
    }

    ksort($ordered, SORT_NUMERIC);

    foreach ($ordered as $block) {
        foreach ($block as $tag => $content) {
            switch ($tag) {
                case 'ulopen':
                    echo "<ul>\n";
                    break;
                case 'ulclose':
                    echo "</ul>\n";
                    break;
                case 'code':
                    echo "<p class=\"ducode\">" . allowtags($content) . "</p>\n";
                    break;
                case 'codei':
                    echo "<p class=\"ducodei\">" . allowtags($content) . "</p>\n";
                    break;
                case 'quotation':
                    echo "<p class=\"citation\">" . allowtags($content) . "</p>\n";
                    break;
                case 'tldr':
                    echo "<div class=\"tldr\">" . allowtags($content) . "</div>\n";
                    break;
                case 'li':
                    echo "<li>" . allowtags($content) . "</li>\n";
                    break;
                case 'p':
                    echo "<p class=\"contenu\">" . allowtags($content) . "</p>\n";
                    break;
                case 'h1':
                case 'h2':
                case 'h3':
                case 'h4':
                case 'h5':
                    echo "<$tag>" . allowtags($content) . "</$tag>\n";
                    break;
                default:
                    // Ignore unrecognized tags silently
                    break;
            }
        }
    }
}
?>