<?php

include_once '../classes/User.php';

$user = new User();
$user->login($_POST);

?>