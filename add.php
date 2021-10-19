<?php
require_once 'inc/function.php';
require_once 'inc/headers.php';

//jostain syystä laittaa 0, ellei tämä allaoleva ole toistamiseen omanaan add.php:ssa.
// Mikäli toimii sinulla eri tavalla, niin tää on se kohta mikä lisätty ettei tulosta joka add kerralla lisäksi nollaa.
if ($_SERVER['REQUEST_METHOD']=== 'OPTIONS') {
    return 0;
}

$input = json_decode(file_get_contents('php://input'));
$description = filter_var($input->description,FILTER_SANITIZE_STRING);
$amount = filter_var($input->amount,FILTER_SANITIZE_STRING);

try {
    $db = openDb();

    $query = $db->prepare('insert into item(description, amount) values (:description, :amount)');
    $query->bindValue(':description',$description,PDO::PARAM_STR);
    $query->bindValue(':amount',$amount,PDO::PARAM_INT);
    $query->execute();

    header('HTTP/1.1 200 OK');
    $data = array('id' => $db->lastInsertId(), 'description' => $description, 'amount' => $amount);
    print json_encode($data);
   } catch (PDOException $pdoex) {
    print json_encode($error);
}