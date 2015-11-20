<?php
 
    $regid = "APA91bGw3bxOgYsQLa4BpS0I-w0WzBiChMuDPM6KCGAYo4Cd0fvfvx-ortaLOTtQXLkQVJnCkweaQtjcPl1C33GGjOC2ns8lkUtkEtLzBg6nZjKR-Fap_B0CFmf7EOcYR1UD0WA6Zi-DT1opQe4Nu1PTuYfEaOw-Gw";
 
    // 헤더 부분
    $headers = array(
            'Content-Type:application/json',
            'Authorization:key=AIzaSyB3Y1IyZpjmtZzpqkzcIMn71AbTxJRqaBo'
            );
 
    // 푸시 내용, data 부분을 자유롭게 사용해 클라이언트에서 분기할 수 있음.
    $arr = array();
    $arr['data'] = array();
    $arr['data']['title'] = '푸시 테스트';
    $arr['data']['message'] = '푸시 내용 ABCD~';
    $arr['registration_ids'] = array();
    $arr['registration_ids'][0] = $regid;
 
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://android.googleapis.com/gcm/send');
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS,json_encode($arr));
    $response = curl_exec($ch);
    curl_close($ch);
 
    // 푸시 전송 결과 반환.
    $obj = json_decode($response);
 
    // 푸시 전송시 성공 수량 반환.
    $cnt = $obj->{"success"};
 
    echo $cnt;
?>