<?php

require_once('safeenv.php');

return array (
  'key' => safe_getenv('app.key'),
  'url' => safe_getenv('app.url'),
);