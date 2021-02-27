<?php

    // Logging
    define('ERROR_REPORTING_LEVEL', (E_ERROR | E_WARNING | E_PARSE | E_NOTICE));
    define('ERROR_LOG_PATH', __DIR__ . '/../logs');
    define('ACCESS_ERROR_LOG_PATH', __DIR__ . '/../logs');

    // MySQL database data
    define('MYSQL_HOST', '127.0.0.1');
    define('MYSQL_USERNAME', 'dyndns');
    define('MYSQL_PASSWORD', 'dyndns');
    define('MYSQL_DATABASE', 'dyndns');

    // Update key2 time-to-live
    define('KEY2_VALID_TIME_PRE', 5);
    define('KEY2_VALID_TIME_POST', 10);

?>