<?php
/**
 * Created by PhpStorm.
 * User: Andrew
 * Date: 9/26/2014
 * Time: 5:59 AM
 */
function randomString($length, $type = '') {
    // Select which type of characters you want in your random string
    switch($type) {
        case 'num':
            // Use only numbers
            $salt = '136262215';
            break;
        case 'lower':
            // Use only lowercase letters
            $salt = 'abcdefghijklmnopqrstuvwxyz';
            break;
        default:
            // Use uppercase, lowercase, numbers, and symbols
            $salt = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
            break;
    }
    $rand = '';
    $i = 0;
    while ($i < $length) { // Loop until you have met the length
        $num = rand() % strlen($salt);
        $tmp = substr($salt, $num, 1);
        $rand = $rand . $tmp;
        $i++;
    }
    return $rand; // Return the random string
}

function make_links_clickable($text){
    return preg_replace('!(((f|ht)tp(s)?://)[-a-zA-Zа-яА-Я()0-9@:%_+.~#?&;//=]+)!i', '<a href="$1">$1</a>', $text);
}
$allowedExts = array("gif", "jpeg", "jpg", "png", "txt");


//Сheck that we have a file
if ((!empty($_FILES["file"])) && ($_FILES['file']['error'] == 0)) {





    $temp = explode(".", $_FILES["file"]["name"]);
    $extension = end($temp);
    $random_name = randomString(7);
    if ((($_FILES["file"]["type"] == "image/gif")
            || ($_FILES["file"]["type"] == "image/jpeg")
            || ($_FILES["file"]["type"] == "image/jpg")
            || ($_FILES["file"]["type"] == "image/pjpeg")
            || ($_FILES["file"]["type"] == "image/x-png")
            || ($_FILES["file"]["type"] == "image/png")
            || ($_FILES["file"]["type"] == "text/plain"))
        && ($_FILES["file"]["size"] < 20000000)
        && in_array($extension, $allowedExts)
    ) {
        if ($_FILES["file"]["error"] > 0) {
            echo "Return Code: " . $_FILES["file"]["error"] . "<br>";
            die();
        } else {

            if (file_exists("C:/xampp/htdocs/apps/poptart/secure/uploads/" . $random_name .  "." . $extension)) {
                echo "Some how file exist?";
                die();
            } else {
                move_uploaded_file($_FILES["file"]["tmp_name"],
                    "C:/xampp/htdocs/apps/poptart/secure/uploads/" . $random_name .  "." . $extension);
                echo "Stored At: " . make_links_clickable("https://aurous.me/secure/uploads/" . $random_name .  "." . $extension);
                die();
            }
        }
    } else {
        echo 'Invalid file, supported types. ["gif", "jpeg", "jpg", "png", "txt"]1';
        die();
    }
} else if (!empty($_POST["url"])) {
     $url =  $_POST["url"];

    $filename_arr = explode(".", $url);
    $count_of_elements = count($filename_arr);
    $file_extension = $filename_arr[$count_of_elements - 1];
    if (strpos($url,'pastebin') !== false) {
        preg_match("~http://pastebin.com/([0-9a-zA-Z]+)~", $url, $match);
        $url = "http://pastebin.com/raw.php?i=$match[1]";
        $file_extension = "txt";
    }
    $handle=curl_init($url);

    curl_setopt($handle, CURLOPT_VERBOSE, true);
    curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, false);
    curl_exec($handle);

   $content_type = curl_getinfo($handle, CURLINFO_CONTENT_TYPE);

    $size = curl_getinfo($handle, CURLINFO_CONTENT_LENGTH_DOWNLOAD);
    if (((strpos($content_type,'image/gif') !== false)
            || (strpos($content_type,'image/jpeg') !== false)
            || (strpos($content_type,'image/jpg') !== false)
            || (strpos($content_type,'image/pjpeg') !== false)
            || (strpos($content_type,'image/x-png') !== false)
            || (strpos($content_type,'image/png') !== false)
            || strpos($content_type,'text/plain') !== false)
        && ($size < 20000000)
        && in_array($file_extension, $allowedExts)) {
        $random_name = randomString(7);
             file_put_contents("C:/xampp/htdocs/apps/poptart/secure/uploads/" . $random_name .  "." . $file_extension, file_get_contents($url));
            echo "Stored At: " . make_links_clickable("https://aurous.me/secure/uploads/" . $random_name .  "." . $file_extension);

            die();

    }  else {
    echo 'Invalid file, supported types. ["gif", "jpeg", "jpg", "png", "txt"]';
    die();
}

} else {
    Echo "No file uploaded!";
    die();
}
