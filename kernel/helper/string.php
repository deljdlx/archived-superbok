<?php



function slugify($string) {

    static $driver;

    if(!$driver) {
        $driver=new \Cocur\Slugify\Slugify();
    }
    return $driver->slugify($string);
}


function normalizeFilepath($filepath) {
    return str_replace('\\', '/', (string) $filepath);
}

function filepathToClassName($string) {
    return strtolower(str_replace(
        '.php',
        '',
        str_replace('/', '\\', $string)
    ));
}


