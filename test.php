<?php

require_once 'myPDO.mysql.multiple-choice.include.php';

$stmt = myPDO::getInstance()->prepare(<<<SQL
    SELECT *
    FROM questionnaire
    ORDER BY text
SQL
);

$stmt->execute();

header('Content-Type: text/html; charset=utf8');
var_dump($stmt->fetchAll());
