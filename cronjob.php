<?php

chdir(__DIR__);

include(__DIR__.'/bootstrap.php');




if(is_file('application/'.$argv[1])) {
    echo 'Start script application/'.$argv[1]."\n";
    return include('application/'.$argv[1]);

}
else {
    echo "Job not found\n";
    return -1;
}


