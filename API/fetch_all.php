<?php
  $domain=$_SERVER['HTTP_HOST'].'/CRUD/API/';
  $prefix='https://';
  $relative='API.php?action=fetch_all';
  $url=$prefix.$domain.$relative;
  $ch=curl_init($url);
  curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
  $result=curl_exec($ch);
  curl_close($ch);
  $result=json_decode($result);
  if($result){
    $data=$result;
  }
  else {
    $data='no data found';
  }
  $output = array('status' =>200 ,
                    'message'=>'sucessful',
                      'data'=>$data);
  echo json_encode($output);
 ?>
