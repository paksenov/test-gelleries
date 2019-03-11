<?php

$time_start = microtime(true);

$db_host = 'db';
$db_user = 'root';
$db_pass = 'root';
$db_name = 'test';

$dbh = new PDO("mysql:dbname=$db_name;host=$db_host", $db_user, $db_pass);

$sth = $dbh->prepare('SELECT id, name FROM gallery ORDER BY id desc LIMIT 50', [PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL]);
$sth->execute();

$sth_images = $dbh->prepare('SELECT id, name FROM image WHERE gallery_id = ? ORDER BY name ASC limit 4');

$res = [];
while ($gallery = $sth->fetch(PDO::FETCH_ASSOC)) {
    $sth_images->execute([
        $gallery['id']
    ]);
    $images = $sth_images->fetchAll(PDO::FETCH_ASSOC);

    $res[] = [
        'id' => $gallery['id'],
        'name' => $gallery['name'],
        'images' => $images
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