<?php


 function autoNumber($table,$param){
  include "config_connect.php";
  global $forward;
  $query = "SELECT MAX($param) as max_id FROM $table ORDER BY $param";
  $result = mysqli_query($conn, $query);
  $data = mysqli_fetch_array($result);
  $id_max = $data['max_id'];
  $sort_num = $id_max;
  $sort_num++;
  $new_code = sprintf("%06s", $sort_num);
  return $new_code;
 }



	?>