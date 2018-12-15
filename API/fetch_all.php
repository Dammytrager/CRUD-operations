<?php
  header('Access-Control-Allow-Origin: *');
  header('Access-Control-Allow-Methods: GET, POST');
  header("Access-Control-Allow-Headers: X-Requested-With");
  $domain=$_SERVER['HTTP_HOST'].'/API';
  $prefix='https://';
  $relative='/API.php?action=fetch_all';
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
