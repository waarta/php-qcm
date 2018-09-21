<?php
// Singleton de connexion à une base de données
require_once 'myPDO.class.php';
// Paramètre de connexion
myPDO::setConfiguration('mysql:host=mysql;dbname=jonque01_multiple-choice;charset=utf8', 'web', 'web');
