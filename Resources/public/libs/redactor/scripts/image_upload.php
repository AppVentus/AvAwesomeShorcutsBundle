<?php

// This is a simplified example, which doesn't cover security of uploaded images.
// This example just demonstrate the logic behind the process.

// files storage folder
// Be carefull, this is depending of your assets strategy (symlink or not)
$uploadImagesDir = '/images';
$uploadCmsDir = '/cms';
$uploadBase = '/uploads';
$uploadDir = $uploadBase.$uploadCmsDir.$uploadImagesDir;

$dir = $_SERVER['DOCUMENT_ROOT'];

$_FILES['file']['type'] = strtolower($_FILES['file']['type']);

if (
    $_FILES['file']['type'] == 'image/png'
    || $_FILES['file']['type'] == 'image/jpg'
    || $_FILES['file']['type'] == 'image/gif'
    || $_FILES['file']['type'] == 'image/jpeg'
    || $_FILES['file']['type'] == 'image/pjpeg') {
    //create dir if it does not exists
    if (!file_exists($dir.$uploadBase)) {
        mkdir($dir.$uploadBase);
    }
    if (!file_exists($dir.$uploadBase.$uploadCmsDir)) {
        mkdir($dir.$uploadBase.$uploadCmsDir);
    }
    if (!file_exists($dir.$uploadDir)) {
        mkdir($dir.$uploadDir);
    }

    // setting file's mysterious name
    $filename = md5(date('YmdHis')).'.jpg';
    $file = $dir.$uploadDir.'/'.$filename;

    // copying
    move_uploaded_file($_FILES['file']['tmp_name'], $file);

    // displaying file
    $array = [
        'filelink' => $uploadDir.'/'.$filename,
    ];

    echo stripslashes(json_encode($array));
}
