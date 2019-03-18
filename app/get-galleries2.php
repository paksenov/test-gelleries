<?php

$time_start = microtime(true);

$db_host = 'db';
$db_user = 'root';
$db_pass = 'root';
$db_name = 'test';

$dbh = new PDO("mysql:dbname=$db_name;host=$db_host", $db_user, $db_pass);

$sql = 'SELECT id, name FROM gallery ORDER BY id DESC LIMIT 100';

$sth = $dbh->prepare($sql, [PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL]);
$sth->execute();

$sql_images = [];
$res = [];

while ($gallery = $sth->fetch(PDO::FETCH_ASSOC)) {
    $res[$gallery['id']] = [
        'id' => $gallery['id'],
        'name' => $gallery['name'],
        'images' => []
    ];

    $sql_images[] = '(SELECT gallery_id, id, name FROM image WHERE gallery_id = ' . $gallery['id'] . ' ORDER BY name ASC limit 4)';
}

$sql_images = implode('UNION', $sql_images);
$sth = $dbh->prepare($sql_images, [PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL]);
$sth->execute();

while ($image = $sth->fetch(PDO::FETCH_ASSOC)) {
    $res[$image['gallery_id']]['images'][] = [
        'id' => $image['id'],
        'name' => $image['name']
    ];
}

print_r($res);

function convert($size)
{
    $unit=array('b','kb','mb','gb','tb','pb');
    return @round($size/pow(1024,($i=floor(log($size,1024)))),2).$unit[$i];
}

echo 'Mem: ' .convert(memory_get_usage(true)),"\n";

echo 'Total Execution Time: '. (microtime(true) - $time_start),"mcs\n";