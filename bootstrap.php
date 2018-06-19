<?php
/**
 * Created by aimer.
 * User: aimer
 * Date: 2018/6/13
 * Time: 上午8:43
 */

use Illuminate\Database\Capsule\Manager as Capsule;

// BASE_PATH
define('BASE_PATH', __DIR__);

// Autoload
require BASE_PATH . '/vendor/autoload.php';

// Eloquent ORM

$capsule = new Capsule;

$capsule->addConnection(require BASE_PATH . '/config/database.php');

$capsule->bootEloquent();

// whoops Error alert

$whoops = new \Whoops\Run;

$whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);

$whoops->register();