<?php
require_once("constants.php");

$connection = mysqli_connect(DB_HOST,DB_USER,DB_PASS,DB_NAME);#make connection
if(!$connection){#if connectionfails
    die("could not connect to database" .mysqli_connect_error());#the appended method is for displaying error -and is not secure
}