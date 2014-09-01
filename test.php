<?php
use Kir\View\View;

require 'vendor/autoload.php';

$view = new View(__DIR__.'/tests');
echo $view->render('test');