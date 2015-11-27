<?php
 
    $regid = "APA91bGxPybFaT5-L9iGLvJtfb-OwnmyOMsZ2OJP_C44yK9e79igi1KTRIHScSHk6h1FtjczAE3jZN3SukhgUMD3Ay009G-2NyuoSwY8qnJ92sOvtUEx3p2lB3NV-wjouGQXbSPl8cii";
    $regid2 = "APA91bHEd0cHHFc3gwGfMwGsKgKE5_6ZZtEqw4NvK1py_oIi9pIxcG6QFP0sNAI8zo_VYpTKzUDXuh_m_ATLORDqybZbc4XJ_q-V4yIRUbJeSg1b_-xkt6cbOQWEOVMmjC5OjygA8Vwy";
 
    // 헤더 부분
    $headers = array(
            'Content-Type:application/json',
            'Authorization:key=AIzaSyB3Y1IyZpjmtZzpqkzcIMn71AbTxJRqaBo'
            );
 
    // 푸시 내용, data 부분을 자유롭게 사용해 클라이언트에서 분기할 수 있음.
    $arr = array();
    $arr['data'] = array();
    $arr['data']['title'] = '푸시 테스트';
    $arr['data']['message'] = '1배고파
2배고파
3배고파
4배고파
5배고파
6배고파
7배고파
8배고파
9배고파
10배고파
11배고파
12배고파
';
    $arr['registration_ids'] = array();
    $arr['registration_ids'][0] = $regid;
    $arr['registration_ids'][1] = $regid2;
 
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