<?php

chdir(__DIR__);

include(__DIR__.'/bootstrap.php');




if(is_file($argv[1])) {
    echo 'Start script '.$argv[1]."\n";
    return include(''.$argv[1]);

}
else {
    echo "Job not found\n";
    return -1;
}


