<?php
  define( 'SERVER_KEY', 'AAAAGgat0ME:APA91bGBpnepsinfF9z3xZr5_4oepg2yDYo5SelK71MEUGam3bsVNPwUR6MC5M2C-JnRtLjw79MGsk544QdFejdqe1VSXMNTgoa7htI8uhi61UHlQNJ1kO47J774MzkjDSlsxCZQ2yPcczWK2e1ZW7lSwfmDmg1Clw' );
  class Firebase_notification{

  public function sendPushNotificationToGCMSever($fields){
  $path_to_firebase_cm = 'https://fcm.googleapis.com/fcm/send';
  $headers = array(
  'Authorization:key=' . SERVER_KEY,
  'Content-Type:application/json'
  ); 
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $path_to_firebase_cm);
  curl_setopt($ch, CURLOPT_POST, true);
  curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4 );
  curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
  $result = curl_exec($ch);
  curl_close($ch);
  return $result;
  }
  }