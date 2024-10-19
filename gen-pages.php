<?php

// Authors: Nurudin Imsirovic <realnurudinimsirovic@gmail.com>
// Defines: PHP script for generating pages from a template
// Created: 2024-10-12 06:24 PM
// Updated: 2024-10-12 10:45 PM

// constants
define('CWD', __DIR__);
define('DS', DIRECTORY_SEPARATOR);
define('ROOT_DIR', CWD . DS);
define('PUBLIC_DIR', ROOT_DIR . 'public' . DS);
define('PAGES_DIR', ROOT_DIR . 'pages' . DS);
define('TEMPLATE_FILE', ROOT_DIR . 'template.html');

echo "Script: Lazy Static Site Generator\n";
echo "Current working directory: " . CWD . "\n";
echo "Public directory: " . PUBLIC_DIR . "\n";
echo "Pages directory: " . PAGES_DIR . "\n";
echo "Template file: " . TEMPLATE_FILE . "\n";

/* TODO PSEUDOCODE:

template_directory_content = get contents from directory path |TEMPLATES_DIR|
templates = empty array

for a file in template_directory_content do
  file contents = read file |file| then
    replace placeholders
    run the generator (if it has one)
  end

  store file contents in the templates under key 'file' with no extension
end

*/

// main template (or layout)
$template = file_get_contents(TEMPLATE_FILE);

// pages
$pages = scandir(PAGES_DIR);
unset($pages[0], $pages[1]);

// generator things
$gen_results = null;
$gen_extra = null; // usually an array of key-value pairs

echo "Build started\n";

foreach ($pages as $file) {
  // skip generator
  if (strtolower(substr($file, -13)) == 'generator.php') {
    continue;
  }

  // get page content
  $page_content = file_get_contents(PAGES_DIR . $file);
  $html = str_replace('{{content}}', $page_content, $template);
  $gen_file = PAGES_DIR . $file . '.generator.php';

  // remove file extension
  $page_file = pathinfo($file, PATHINFO_FILENAME);

  // trim underscores (_) from file name
  $page_file = trim($page_file, '_') . '.html';

  echo "Write page: $page_file";

  // has generator
  if (file_exists($gen_file)) {
    echo " ... with generator";
    include($gen_file); // this affects $gen_results

    // replace placeholders
    foreach ($gen_placeholders as $k => $v) {
      $html = str_replace('{{' . $k. '}}', $v, $html);
    }

    // reset generator state
    $gen_results = null;
    $gen_extra = null;
  }

  echo "\n";

  file_put_contents(PUBLIC_DIR . $page_file, $html);
}

echo "Build finished\n";
