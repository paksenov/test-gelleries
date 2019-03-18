<?php

$time_start = microtime(true);

$db_host = 'db';
$db_user = 'root';
$db_pass = 'root';
$db_name = 'test';

$dbh = new PDO("mysql:dbname=$db_name;host=$db_host", $db_user, $db_pass);

$sth = $dbh->prepare('SELECT id, name FROM gallery ORDER BY id DESC LIMIT 100', [PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL]);
$sth->execute();

$galleries_id = [];
$res = [];

while ($gallery = $sth->fetch(PDO::FETCH_ASSOC)) {
    $res[$gallery['id']] = [
        'id' => $gallery['id'],
        'name' => $gallery['name'],
        'images' => []
    ];

    $galleries_id[] = $gallery['id'];
}

$sql_images = 'SELECT 
       w.gallery_id gallery_id, 
       w.id id, 
       w.name name 
FROM (
	SELECT 
	       row_number() OVER (PARTITION BY gallery_id ORDER by name) AS row_number, 
	       id, 
	       name, 
	       gallery_id 
	FROM 
	     image 
     WHERE 
           gallery_id IN ('.implode(',', $galleries_id).')
) as w
WHERE 
    w.row_number <= 4';

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