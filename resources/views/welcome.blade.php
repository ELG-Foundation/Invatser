<?php
// Get an array of all loaded extensions
$extensions = get_loaded_extensions();

// Print out each extension
foreach ($extensions as $extension) {
    echo $extension . "\n";
}
?>