<?php


chdir(__DIR__);

require('bootstrap.php');


//=======================================================
/*
 *@todo routeur
 */


$uri=$_SERVER['REQUEST_URI'];

if(preg_match('`moduleview/+`', $uri)) {
    $moduleName=preg_replace('`.*?/moduleview/(.*?)/.*`', '$1', $uri);
    $methodName=preg_replace('`.*?/moduleview/.*?/(.*)`', '$1', $uri);
    $controllerName='\PMD\Capital\Module\\'.$moduleName."\View\Manager";
    $controller=new $controllerName();
    $parameters=array();
    header('Content-type: application/json; charset="utf-8"');
    echo call_user_func_array(array($controller, $methodName), $parameters);
    exit();
}
if(preg_match('`module/.+?/.+?/.+?`', $uri)) {
    $moduleName=preg_replace('`.*?/module/(.*?)/.*`', '$1', $uri);
    $controllerName=preg_replace('`.*?/module/.*?/(.*?)/.*`', '$1', $uri);
    $methodName=preg_replace('`.*?/module/.+?/.+?/([^?]+?)((\?.*)|$)`', '$1', $uri);



    $fullControllerName='\PMD\Capital\Module\\'.$moduleName.'\Controller\\'.$controllerName;


    $parameters=array_merge($_GET, $_POST);

    $controller=new $fullControllerName();


    //print_r($parameters);
    //die('EXIT '.__FILE__.'@'.__LINE__);

    $data=call_user_func_array(array($controller, $methodName), $parameters);

    header('Content-type: application/json; charset="utf-8"');
    echo json_encode($data);
    exit();
}


