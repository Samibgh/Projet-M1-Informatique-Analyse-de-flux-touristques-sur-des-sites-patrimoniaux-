<?php
 //ConBase1.php
 require("paramsBdd.php");
 
 // Connexion
 $c = new PDO("mysql:host=$host;dbname=$dbname", $login, $password);
 $c->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
 ?>