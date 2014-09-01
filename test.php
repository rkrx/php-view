<?php
use Kir\Templating\View\Helpers\Map;
use Kir\Templating\View\Engines\PhpEngine\Worker;
use Kir\Data\Arrays\RecursiveAccessor\StringPath;

require_once 'vendor/autoload.php';

$worker = new Worker('templates', '.phtml', new Map(['a' => ['b' => ['c' => '<script />']]]));
echo $worker->render('test', array());