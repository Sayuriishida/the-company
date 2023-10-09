<?php

include "../classes/User.php";

// create an object of User class

$user = new User;

// call a method
$user->store($_POST);
