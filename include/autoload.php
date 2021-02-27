<?php

function dyndns_autoloader($class) {
    $classnameComponents = explode('\\', $class);
    if ($classnameComponents[0] != 'DynDns')
        return;
    unset($classnameComponents[0]);
    $classname = array_pop($classnameComponents);
    $path = __DIR__ . '/' . sprintf('%s/class.%s.php',
        strtolower(implode('/', $classnameComponents)),
        $classname);
    require_once $path;
}

spl_autoload_register('dyndns_autoloader', true, true);

?>