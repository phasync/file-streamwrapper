<?php
require('vendor/autoload.php');

phasync::run(function() {

    phasync::go(function() {
        while(true) {
            echo "."; 
            phasync::sleep();
        }
    });
    echo "DOING FILE_GET_CONTENTS\n";
    file_get_contents(__FILE__);
    echo "DONE\n";
    die();
});