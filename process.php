<?php
// Access the $_FILES global variable for this specific file being uploaded
// and create local PHP variables from the $_FILES array of information
$fileName = $_FILES["uploaded_file"]["name"]; // The file name
$fileTmpLoc = $_FILES["uploaded_file"]["tmp_name"]; // File in the PHP tmp folder
$fileType = $_FILES["uploaded_file"]["type"]; // The type of file it is
$fileSize = $_FILES["uploaded_file"]["size"]; // File size in bytes
$fileErrorMsg = $_FILES["uploaded_file"]["error"]; // 0 for false... and 1 for true
$fileName = preg_replace('#[^a-z.0-9]#i', '', $fileName); // filter
$kaboom = explode(".", $fileName); // Split file name into an array using the dot
$fileExt = end($kaboom); // Now target the last array element to get the file extension

// START PHP Image Upload Error Handling -------------------------------
if (!$fileTmpLoc) { // if file not chosen
    echo "ERROR: Please browse for a file before clicking the upload button.";
    exit();
} else if($fileSize > 5242880) { // if file size is larger than 5 Megabytes
    echo "ERROR: Your file was larger than 5 Megabytes in size.";
    unlink($fileTmpLoc); // Remove the uploaded file from the PHP temp folder
    exit();
} else if (!preg_match("/.(gif|jpg|png|jpeg)$/i", $fileName) ) {
     // This condition is only if you wish to allow uploading of specific file types    
     echo "ERROR: Your image was not .gif, .jpg, .jpeg or .png.";
     unlink($fileTmpLoc); // Remove the uploaded file from the PHP temp folder
     exit();
} else if ($fileErrorMsg == 1) { // if file upload error key is equal to 1
    echo "ERROR: An error occured while processing the file. Try again.";
    exit();
}
// END PHP Image Upload Error Handling ---------------------------------
// Place it into your "uploads" folder mow using the move_uploaded_file() function
$moveResult = move_uploaded_file($fileTmpLoc, "uploads/$fileName");
// Check to make sure the move result is true before continuing
if ($moveResult != true) {
    echo "ERROR: File not uploaded. Try again.";
    exit();
}
// Include the file that houses all of our custom image functions
include_once("ak_php_img_lib_1.0.php");
// ---------- Start Universal Image Resizing Function --------
$target_file = "uploads/$fileName";
$resized_file = "uploads/resized_$fileName";
$wmax = 500;
$hmax = 500;
ak_img_resize($target_file, $resized_file, $wmax, $hmax, $fileExt);
// ----------- End Universal Image Resizing Function ----------
// ---------- Start Convert to JPG Function --------
if ((strtolower($fileExt) != "jpg")&&(strtolower($fileExt) != "jpeg")) {
    $target_file = "uploads/resized_$fileName";
    $new_jpg = "uploads/resized_".$kaboom[0].".jpg";
    ak_img_convert_to_jpg($target_file, $new_jpg, $fileExt);
}
// ----------- End Convert to JPG Function -----------
// ---------- Start Image Watermark Function --------
if (strtolower($fileExt) != "jpeg")
    $target_file = "uploads/resized_".$kaboom[0].".jpg";
//$target_file = convertImageToGrayscale($target_file, .75);
//$target_file = imagefilter($target_file, IMG_FILTER_GRAYSCALE);
$wtrmrk_file = "red1.png";
if(strtolower($fileExt) == "jpeg"){
    $target_file = $resized_file;
    $new_file = "uploads/protected_".$kaboom[0].".jpeg";
}
else
    $new_file = "uploads/protected_".$kaboom[0].".jpg";
ak_img_watermark($target_file, $wtrmrk_file, $new_file);

$wtrmrk_filesecond = "red2.png";
if(strtolower($fileExt) == "jpeg")
    $last_file =  "uploads/second_".$kaboom[0].".jpeg";
else
    $last_file =  "uploads/second_".$kaboom[0].".jpg";
ak_img_watermarksecond($new_file, $wtrmrk_filesecond, $last_file);



// ----------- End Image Watermark Function -----------
// Display things to the page so you can see what is happening for testing purposes
echo "<img src=$last_file>";
//echo "Shsre this image on Your Social media: $files[]";
echo " 
<!DOCTYPE html>
<html>
<head>
    <title>Share</title>
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css'>
    <style>
        .fa {
  padding: 20px;
  font-size: 30px;
  width: 60px;
  text-align: center;
  text-decoration: none;
  margin: 5px 2px;
}
    
.fa:hover {
    opacity: 0.7;
}

.fa-facebook {
  background: #3B5998;
  color: white;
}
.fa-google {
  background: #dd4b39;
  color: white;
}
.fa-whatsapp {
  background: green;
  color: white;
}
.fa-download {
  background: rgb(16, 17, 16);
  color: white;
}



    </style>    
</head>
<body>

<p>
<center>
<a href='https://lapolo.in/ip/$new_file' download class='fa fa-download'></a></br>
<p>Share on</p></br>
<ul class='list-inline'>
    <li>
<a href='https://www.facebook.com/sharer/sharer.php?u=https://lapolo.in/ip/$new_file' class='fa fa-facebook list-inline-item'></a></li>
<li>
<a href='https://plus.google.com/share?url=https://lapolo.in/ip/$new_file' class='fa fa-google list-inline-item'></a></li>
<li>
<a href='whatsapp://send?text=https://lapolo.in/ip/$new_file' class='fa fa-whatsapp list-inline-item'></li>
</ul>
</center>

</p>
</body>
</html>";
?>
