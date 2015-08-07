<?php
chdir(__DIR__);



date_default_timezone_set('Europe/Paris');

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


//=======================================================
/*
 *@todo routeur
 */


$uri=$_SERVER['REQUEST_URI'];

if(preg_match('`module/+`', $uri)) {


    $moduleName=preg_replace('`.*?/module/(.*?)/.*`', '$1', $uri);
    $methodName=preg_replace('`.*?/module/.*?/(.*)`', '$1', $uri);

    $controllerName='\PMD\Capital\Module\\'.$moduleName."\View\Manager";

    $controller=new $controllerName();

    $parameters=array();

    header('Content-type: application/json; charset="utf-8"');
    echo call_user_func_array(array($controller, $methodName), $parameters);


    exit();
}







