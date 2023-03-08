<?php

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header('Access-Control-Allow-Headers: X-Requested-With');

$tasks = array(
  "Passer le balai",
  "Saluer le Boss",
  "Couper l'ordi"
);

echo json_encode($tasks);
