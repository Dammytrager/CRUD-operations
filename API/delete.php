<?php
  $prefix='https://';
  $domain=$_SERVER['HTTP_HOST'].'/API';
  $relative='/API.php?action=delete';
  $url=$prefix.$domain.$relative;
  $json=file_get_contents('php://input');
  $data=json_decode($json);
  $data=array(
    'id'=>$data->id
  );
  $ch=curl_init($url);
  curl_setopt($ch,CURLOPT_POST,1);
  curl_setopt($ch,CURLOPT_POSTFIELDS,$data);
  curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
  $response=curl_exec($ch);
  curl_close($ch);

  if($response=='success'){
    $data=array('status'=>200,'response'=>$response);
  }
  elseif ($response=='failure') {
    $data=array('status'=>403,'response'=>$response);
  }
  echo json_encode($data);
?>
