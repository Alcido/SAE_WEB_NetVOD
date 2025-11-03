<?php

use iutnc\NetVOD\dispatcher\Dispatcher;

require_once 'loader/AutoLoader.php';
(new iutnc\NetVOD\Loader\AutoLoader("iutnc\\NetVOD\\", __DIR__))->register();
(new Dispatcher())->run();
