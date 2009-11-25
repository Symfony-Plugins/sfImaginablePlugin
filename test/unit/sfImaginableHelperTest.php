<?php
include(dirname(__FILE__).'/../bootstrap.php');
require_once(dirname(__FILE__).'/../../lib/helper/sfImaginableHelper.php');

$t = new lime_test(5, new lime_output_color());
$t->diag('Testing _parse_file_size_limit_to_bytes function:');
$t->is(_parse_file_size_limit_to_bytes('3 mb'), '3145728', 'First test (3 mb), result should be 3145728 bytes)');
$t->is(_parse_file_size_limit_to_bytes('3mb'), '3145728', 'Same as first, no space');
$t->is(_parse_file_size_limit_to_bytes('3 MB'), '3145728', 'Same as first, capital letters');
$t->is(_parse_file_size_limit_to_bytes('41fngsnsr'), '3145728', 'Random gibberish, defaults "app_sf_imaginable_file_size_limit" or 3 MB');
$t->is(_parse_file_size_limit_to_bytes(''), 3145728, 'Empty value should default to 3MB');
