<?php

    include 'include/_includes.php';
    use DynDns\Datamodel\Domain;

    $domainName = $_GET['name'];
    $key1 = $_GET['key1'];
    $hash2 = $_GET['hash2'];
    $remoteIp = $_SERVER['REMOTE_ADDR'];

    $domain = Domain::byUpdateName()[0];
    $domain->update($key1, $hash2, $remoteIp);

?>