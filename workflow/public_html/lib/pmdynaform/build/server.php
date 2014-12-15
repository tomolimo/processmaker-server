<?php
/*	var_dump($_FILES);
  $str = file_get_contents('php://input');
  echo $filename = md5(time().uniqid()).".jpg";
  file_put_contents("img/".$filename,$str);
  //unlink("uploads/".$filename);*/
?>


<?php
 print_r($_FILES);
foreach ($_FILES["images"]["error"] as $key => $error) {
  if ($error == UPLOAD_ERR_OK) {
    $name = $_FILES["images"]["name"][$key];
    sleep(5);
    move_uploaded_file( $_FILES["images"]["tmp_name"][$key], "img/" . $_FILES['images']['name'][$key]);
  }
}
 
echo "<h2>Successfully Uploaded Images</h2>";