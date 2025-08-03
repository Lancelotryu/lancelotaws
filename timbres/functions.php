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

function allowtags(
    string $text,
    ?array $siteVars = null,
    array $allowedTags = ['br','i','b','em','sup']
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

function t($clef): void {
    global $l;
    global $t;

    $parts = explode('.', $clef);
    [$section, $number, $tag] = $parts;

    switch ($tag) {
case 'stt':
  echo "<p class=\"stt\">" . allowtags($t[$clef]) . "</p>\n";
  break;
case 'ttauteur':
  echo "<p class=\"ttauteur\">" . allowtags($t[$clef]) . "</p>\n";
  break;
case 'commentaire':
  echo "<p class=\"commentaire\">" . allowtags($t[$clef]) . "</p>\n";
  break;
case 'legende':
  echo "<p class=\"legende\">" . allowtags($t[$clef]) . "</p>\n";
  break;
case 'img':
  echo "<p><img class=\"img-fluid\" src=\"timbres/img/" . allowtags($t[$clef]) . "\"></p>\n";
  break;
case 'citation':
  echo "<p class=\"citation\">" . allowtags($t[$clef]) . "</p>\n";
  break;
case 'auteur':
  echo "<p class=\"auteur\">" . allowtags($t[$clef]) . "</p>\n";
  break;
case 'p':
  echo "<p class=\"contenu\">" . allowtags($t[$clef]) . "</p>\n";
  break;
case 'h1':
  echo "<h1>" . allowtags($t[$clef]) . "</h1>\n";
  break;
case 'h2':
  echo '</div><div class="container"><div class="row"><div class="col">';
  echo "<h2>" . allowtags($t[$clef]) . "</h2>\n</div></div>";
  break;
case 'h3':
  echo "<h3>" . allowtags($t[$clef]) . "</h3>\n";
  break;
case 'h4':
  echo "<h4>" . allowtags($t[$clef]) . "</h4>\n";
  break;
default:
  break;
}}



function traduire(string $key): void {
    global $pdo, $l;
    $stmt = $pdo->prepare("SELECT content FROM translations WHERE lang = :lang AND `key` = :key LIMIT 1");
    $stmt->execute([':lang' => $l, ':key' => $key]);
    $text = $stmt->fetchColumn() ?: "[$key]";
    $text = allowtags($text);
    echo $text;
}
?>