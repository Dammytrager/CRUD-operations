<?php
  header('Access-Control-Allow-Origin: *');
  header('Access-Control-Allow-Methods: GET, POST');
  header("Access-Control-Allow-Headers: X-Requested-With");
  class API {

    var $db='';
    function API(){
      $this->db=mysqli_connect('us-cdbr-iron-east-01.cleardb.net','b7f4921f420f5e','532ec641','heroku_c47063e90ea3bda') or die('check your database parameters');
    }


    //b7f4921f420f5e:532ec641@us-cdbr-iron-east-01.cleardb.net/heroku_c47063e90ea3bda?reconnect=true

    /*Load All Data from Db*/
    function fetch_all(){
      $query='select firstname,lastname,user_id from user_info';
      $result=mysqli_query($this->db,$query);
      $number=mysqli_num_rows($result);
      $data=Array();
      if($number>0){
        while($row=mysqli_fetch_array($result)){
          $data[]=array('firstname'=>$row['firstname'],
                        'lastname'=>$row['lastname'],
                        'user_id'=>$row['user_id']);
        }
      }
      return $data;
    }

    /*Insert Function*/
    function insert($first,$last){
      $query='insert into user_info values (null,"'.$first.'","'.$last.'")';
      $result=mysqli_query($this->db,$query) or die(mysqli_error($this->db));
      if($result){
        $mydata=$this->fetch_all();
        return end($mydata);
      }
      else{
        return 'failure';
      }
    }

    /*Edit function*/
    function edit($first,$last,$id){
      $query='update user_info set firstname="'.$first.'", lastname="'.$last.'" where user_id='.$id;
      $result=mysqli_query($this->db,$query) or die(mysqli_error($this->db));
      if($result){
        $query='select firstname,lastname,user_id from user_info where user_id='.$id;
        $result=mysqli_query($this->db,$query);
        while($row=mysqli_fetch_array($result)){
          $data=array('firstname'=>$row['firstname'],
                        'lastname'=>$row['lastname'],
                        'user_id'=>$row['user_id']);
        }
      }
      return $data;
    }

    /*Delete Function*/
    function delete($id){
      $query='DELETE FROM user_info WHERE user_id='.$id;
      $result=mysqli_query($this->db,$query) or die(mysqli_error($this->db));
      if($result){
        return 'success';
      }
      else{
        return 'failure';
      }
    }

    /*FirstName*/
    function firstname(){
      $query='SELECT firstname FROM user_info';
      $result=mysqli_query($this->db,$query) or die(mysqli_error($this->db));
      $data=Array();
      while ($row=mysqli_fetch_array($result)) {
        $data[]=$row['firstname'];
      }

      return $data;
    }

    /*LastName*/
    function lastname(){
      $query='SELECT lastname FROM user_info';
      $result=mysqli_query($this->db,$query) or die(mysqli_error($this->db));
      $data=Array();
      while ($row=mysqli_fetch_array($result)) {
        $data[]=$row['lastname'];
      }

      return $data;
    }

  }

  /*Action fetch_all*/
  if($_GET['action']=='fetch_all'){
    $api=new API();
    $result=$api->fetch_all();
    echo json_encode($result);
  }


  /*Action add*/
  if($_GET['action']=='add'){
    $api=new API();
    $result=$api->insert($_POST['firstname'],$_POST['lastname']);
    echo json_encode($result);
  }

  /*Action Edit*/
  if($_GET['action']=='edit'){
    $api=new API();
    $result=$api->edit($_POST['firstname'],$_POST['lastname'],$_POST['user_id']);
    echo json_encode($result);
  }

  /*Action Delete*/
  if($_GET['action']=='delete'){
    $api=new API();
    $result=$api->delete($_POST['id']);
    echo $result;
  }

  /*FirstName*/
  if($_GET['action']=='firstname'){
    $api=new API();
    echo json_encode($api->firstname());
  }

  /*lastName*/
  if($_GET['action']=='lastname'){
    $api=new API();
    echo json_encode($api->lastname());
  }


 ?>
