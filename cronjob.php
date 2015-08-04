<?php

chdir(__DIR__);

include(__DIR__.'/bootstrap.php');


echo 'application/script/'.$argv[1]."\n";

if(is_file('application/script/'.$argv[1])) {
    return include('application/script/'.$argv[1]);

}
else {
    echo "Job not found\n";
    return -1;
}


