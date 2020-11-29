<?php

require_once __DIR__ . '/../autoload.php';

session_start();
\app\Routes::run();
