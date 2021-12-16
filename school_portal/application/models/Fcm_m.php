<?php
/*
| -----------------------------------------------------
| PRODUCT NAME: 	MENTOR ERP
| -----------------------------------------------------
| AUTHOR:			Kshitij Kumar Singh
| -----------------------------------------------------
| EMAIL:			kshitij.singh@teknikoglobal.com
| -----------------------------------------------------
| COPYRIGHT:		RESERVED BY TEKNIKOGLOBAL
| -----------------------------------------------------
| WEBSITE:			https://www.teknikoglobal.com
| -----------------------------------------------------
*/

class Fcm_m extends MY_Model
{
    function __construct() {
        parent::__construct();
    }
    public function fcmNotification($device_id, $sendData)
    {
        if (!defined('API_ACCESS_KEY')){
            define('API_ACCESS_KEY', 'AAAA2_Z4OH0:APA91bGgWMIQCk-wkpUp_GtJbmDjGrO3_DpL-SiL3fEhv3pH5mXHqf1nEHkLgRXT50IT-iS6PMp372dHF4VK3Jx8KOuAu-dj0m8tihlXEXVXDo6yljYLAS-d3YOXo4HWscaTcOX7MjO6');
        }

        $fields = array
        (
            'to'        => $device_id,
            'data'  => $sendData,
            'notification'  => $sendData,
            'priority' => 'high'
        );


        $headers = array
        (
            'Authorization: key=' . API_ACCESS_KEY,
            'Content-Type: application/json'
        );
        #Send Reponse To FireBase Server
        $ch = curl_init();
        curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' );
        curl_setopt( $ch,CURLOPT_POST, true );
        curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
        curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
        curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
        curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields) );
        $result = curl_exec($ch);
        if($result === false)
            die('Curl failed ' . curl_error($ch));

        curl_close($ch);
        return $result;
    }
}