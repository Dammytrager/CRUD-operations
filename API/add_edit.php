<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header("Access-Control-Allow-Headers: X-Requested-With");
$domain=$_SERVER['HTTP_HOST'].'/API';
$prefix='https://';
$json=file_get_contents('php://input');
$data=json_decode($json);
$relative='/API.php?action='.$data->function;

#edit
if($data->function=='edit'){
  $postdata=array(
  'firstname'=>$data->firstname,
  'lastname'=>$data->lastname,
  'user_id'=>$data->user_id
  );

}

#add
else{
      $relative='/API.php?action=firstname';
      $url=$prefix.$domain.$relative;
      $ch=curl_init($url);
      curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
      $response=curl_exec($ch);
      curl_close($ch);
      $response=json_decode($response);

      #if user exists
      if(in_array($data->firstname,$response)){

        $relative='/API.php?action=lastname';
        $url=$prefix.$domain.$relative;
        $ch=curl_init($url);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        $response=curl_exec($ch);
        curl_close($ch);
        $response=json_decode($response);
        if(in_array($data->lastname,$response)){
          $output=array('status'=>411,
                          'message'=>'firstname and lastname exists');
          echo json_encode($output);
          exit();
        }
      }

      $postdata=array(
        'firstname'=>$data->firstname,
        'lastname'=>$data->lastname);
}

$relative='/API.php?action='.$data->function;
$url=$prefix.$domain.$relative;
$ch=curl_init($url);
curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
curl_setopt($ch,CURLOPT_POST,1);
curl_setopt($ch,CURLOPT_POSTFIELDS,$postdata);
$response=curl_exec($ch);
curl_close($ch);
$response=json_decode($response);
$output=array('status'=>'200',
              'message'=>'success',
              'data'=>$response);

echo json_encode($output);

 ?>
