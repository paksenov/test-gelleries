<?php
//include 'vendor/autoload.php';
//
//$faker = Faker\Factory::create();

function csvstr(array $fields) : string
{
    $f = fopen('php://memory', 'rb+');
    if (fputcsv($f, $fields) === false) {
        return false;
    }
    rewind($f);
    $csv_line = stream_get_contents($f);
    fclose($f);
    return rtrim($csv_line);
}

$s = 0;
$l = 10000;
//$m = 100000;
$m = 2000000;

$id = 1;
$i_id = 1;

$f_gallery = fopen('./db/data/gallery.csv', 'w+b');
$f_image = fopen('./db/data/image.csv', 'w+b');

while ($s < $m) {

    $galleries = '';
    $images = '';

    for ($i = 0; $i < $l; $i++) {

        $insert = [
            $id,
            'galleryName '.$id,
        ];
        $galleries .= csvstr($insert)."\n";

//        $images_l = random_int(4, 10);
        $images_l = 5;
        for ($j = 0; $j < $images_l; $j++) {

            $insert = [
                $i_id,
                $id,
                'imageName '.$i_id,
            ];
            $images .= csvstr($insert)."\n";
            $i_id++;
        }
        $id++;
    }

    fwrite($f_gallery, $galleries);
    fwrite($f_image, $images);

    $s += $l;

    echo 'Created galleries: ', $s,"\n";
}

fclose($f_gallery);
fclose($f_image);