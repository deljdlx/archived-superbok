<?php





function slugify($string) {

    static $driver;

    if(!$driver) {
        $driver=new \Cocur\Slugify\Slugify();
    }

    return $driver->slugify($string);

}


