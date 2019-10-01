<?php

require('../vendor/autoload.php');

use snow\Account;

$account = new Account();
$account -> logout();

header('location: home.php');

?>