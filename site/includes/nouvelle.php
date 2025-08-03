<?php
//This file helps me injecting the text of short novels in a collapse area.
require_once('functions.php');
$name = preg_replace('/[^a-z0-9_-]/i', '', $_GET['name'] ?? '');
$ryuFile = "../data/nouvelles/{$name}.ryu";
if (!file_exists($ryuFile)) {http_response_code(404); exit('File not found');}
echo convert_novel($ryuFile);
?>