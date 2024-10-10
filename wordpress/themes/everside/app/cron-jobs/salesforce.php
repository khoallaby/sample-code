<?php

namespace App;

ini_set('memory_limit','256M');
require dirname(__FILE__) . '/../../../../../wp-load.php';



try {
    $salesforce = new Salesforce();
    $salesforce->run_cron();


} catch (\Exception $e) {
    echo $e->getMessage();
}



die();

