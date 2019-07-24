/** export.php **/
#!/usr/bin/env php
<?php 

require_once __DIR__ . '/vendor/autoload.php';

use Symfony\Component\Console\Application;
use Src\Convert;

/**
* Author: Harry P <harry2604@gmail.com>
*/

$app = new Application('Export JSON to CSV App', 'v1.0.0');
$app -> add(new Convert());
$app -> run();