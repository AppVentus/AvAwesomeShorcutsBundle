<?php

// This is a simplified example, which doesn't cover security of uploaded images.
// This example just demonstrate the logic behind the process.


// files storage folder
// Be carefull, this is depending of your assets strategy (symlink or not)
$uploadDir = '/uploads/cms/images/';
$dir = __DIR__.'/../../../../..'.$uploadDir;

$_FILES['file']['type'] = strtolower($_FILES['file']['type']);

if (
    $_FILES['file']['type'] == 'image/png'
    || $_FILES['file']['type'] == 'image/jpg'
    || $_FILES['file']['type'] == 'image/gif'
    || $_FILES['file']['type'] == 'image/jpeg'
    || $_FILES['file']['type'] == 'image/pjpeg')
{
    // setting file's mysterious name
    $filename = md5(date('YmdHis')).'.jpg';
    $file = $dir.$filename;

    // copying
    copy($_FILES['file']['tmp_name'], $file);

    // displaying file
    $array = array(
        'filelink' => $uploadDir.$filename
    );

    echo stripslashes(json_encode($array));

}

?>
