<?php

define('DATA_DIR', dirname(__DIR__) . '/data');
define('TARGET_DIR', dirname(__DIR__) . '/src/data');


function transfer($file_path) {
    $lines = file($file_path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    $rv = array();
    array_shift($lines);

    foreach($lines as $line) {
      list(,,$code, $value) = explode("\t", $line);
      $rv[$code] = $value;
    }
    ob_start();
    echo "<?php \nreturn ";
    var_export($rv);
    echo ";\n";
    $content = ob_get_clean();
    $file_name = str_replace('tsv', 'php', basename($file_path));
    echo "Write {$file_name}", PHP_EOL;
    file_put_contents(TARGET_DIR . '/' . $file_name, $content);
}

foreach (glob(DATA_DIR . '/*.tsv') as $file) {
    transfer($file);
}
