<?php

$gen_placeholders = [
  'blog_list' => '',
];

foreach ($pages as $file) {
  // skip core pages
  if ($file[0] == '_') {
    continue;
  }

  $gen_placeholders['blog_list'] .= <<<EOF
<a href="/$file">$file</a><br>
EOF;
}
