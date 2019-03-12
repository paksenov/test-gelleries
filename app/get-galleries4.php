<?php

$time_start = microtime(true);

$db_host = 'db';
$db_user = 'root';
$db_pass = 'root';
$db_name = 'test';

$dbh = new PDO("mysql:dbname=$db_name;host=$db_host", $db_user, $db_pass);

$sql = '
SELECT q2.* FROM (
  SELECT row_number() OVER (PARTITION BY q.gallery_id ORDER BY q.image_name ASC) rn, q.* FROM (
    SELECT i.gallery_id gallery_id, g.name gallery_name, i.id image_id, i.name image_name FROM image i
    JOIN gallery g ON g.id = i.gallery_id
    WHERE i.gallery_id IN (SELECT id FROM gallery WHERE id > (SELECT (max(id)-100) from gallery))
  ) AS q
) AS q2
WHERE q2.rn <= 4
ORDER BY q2.gallery_id DESC, q2.image_name ASC
';

$sth = $dbh->prepare($sql, [PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL]);
$sth->execute();

$res = [];

while ($item = $sth->fetch(PDO::FETCH_ASSOC)) {
    if (isset($res[$item['gallery_id']])) {
        $res[$item['gallery_id']]['images'][] = [
            'id' => $item['image_id'],
            'name' => $item['image_name'],
        ];
    } else {
        $res[$item['gallery_id']] = [
            'id' => $item['gallery_id'],
            'name' => $item['gallery_name'],
            'images' => [
                'id' => $item['image_id'],
                'name' => $item['image_name'],
            ]
        ];
    }
}

print_r($res);

function convert($size)
{
    $unit=array('b','kb','mb','gb','tb','pb');
    return @round($size/pow(1024,($i=floor(log($size,1024)))),2).$unit[$i];
}

echo 'Mem: ' .convert(memory_get_usage(true)),"\n";

echo 'Total Execution Time: '. (microtime(true) - $time_start),"mcs\n";