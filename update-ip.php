<?php

    include 'include/_includes.php';
    use DynDns\Datamodel\Domain;

    $domainId = $_GET['domain'];
    $key1 = $_GET['key1'];
    $hash2 = $_GET['hash2'];
    $remoteIp = $_SERVER['REMOTE_ADDR'];

    $domain = new Domain($domainId);
    $domain->update($key1, $hash2, $remoteIp);

?>