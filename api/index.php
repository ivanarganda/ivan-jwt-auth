<?php 



$page = isset($_GET['page']) ? $_GET['page'] : 'doc';

$page = preg_replace('/[^a-zA-Z0-9_-]/', '', $page);

// list files in src directory except for doc.php
$files = array_diff(scandir('../src'), ['.', '..', 'doc.php']);

if (in_array("$page.php", $files)) {
    header('Content-Type: application/json');
    header('Access-Control-Allow-Origin: *');
    include "../src/$page.php";
} else {
    header('Content-Type: text/html');
    include "styles.php";
    include "../src/doc.php";
    include "js.php";
}
