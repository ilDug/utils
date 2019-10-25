<?php
    require_once __DIR__ . '/../../classes/rsa-key-chain.php';
    $kc = new KeyChain();
    echo $kc->public;
?>