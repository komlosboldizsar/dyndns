<?php

function validateIp($ip) {
    $pieces = explode(".", $ip);
    if (count($pieces) != 4)
        return false;
    foreach ($pieces as $piece) {
        $intpiece = (int)$piece;
        if ((string)$intpiece != $piece)
            return false;
        if (($intpiece < 0) || ($intpiece > 255))
            return false;
    }
    return true;
}

?>