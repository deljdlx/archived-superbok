<?php
chdir(__DIR__);



date_default_timezone_set('Europe/Paris');







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


include('vendor/autoload.php');


spl_autoload_register(function($calledClassName) {

    static $classIndex;

    $folders=array(
        'PMD\Capital'=>__DIR__.'/application',
        'PMD'=>__DIR__.'/kernel'
    );


    if(!$classIndex) {
        foreach ($folders as $namespace=>$folder) {

            $folder=normalizeFilepath($folder);

            $dir_iterator = new \RecursiveDirectoryIterator($folder);
            $iterator = new \RecursiveIteratorIterator($dir_iterator, \RecursiveIteratorIterator::SELF_FIRST);

            foreach ($iterator as $file) {
               if(strrpos($file, '.php')) {

                   $fileName=str_replace('\\', '/', (string) $file);
                    $className=filepathToClassName(str_replace($folder, $namespace, $fileName));
                    $classIndex[$className]=(string) $file;
                }
            }
        }
    }

    $normalizedClassName=strtolower($calledClassName);

    if(isset($classIndex[$normalizedClassName])) {
        include($classIndex[$normalizedClassName]);
    }
});





