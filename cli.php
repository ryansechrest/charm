<?php

namespace Charm;

if (!isset($argv)) {
    die('Whatcha doin? Ƹ̵̡Ӝ̵̨̄Ʒ');
}

require_once 'Charm.php';

$charm = Charm::init();
$charm->start();
$charm->run($argv);
$charm->finish();