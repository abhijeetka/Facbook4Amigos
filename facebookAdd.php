<?php
require_once('facebook-php-sdk-master/src/facebook.php');

//Function to Get Access Token
function get_app_token($appid, $appsecret){
$args = array(
'client_id' => $appid,
'client_secret' => $appsecret,
'grant_type' => 'client_credentials',
'scope'=>'publish_stream'
);

print_r($args);


$ch = curl_init();
print_r($ch);
$url = 'https://graph.facebook.com/oauth/access_token';
//$url = 'http://www.google.com';
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_HEADER, true);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $args);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
$data = curl_exec($ch);
return json_encode($data);
}


//Get App Token
//$token = get_app_token('145411908958941', 'f3fef75dd09aba370efb8974b3a43f45');
$token= '145411908958941|uJxy8SYkbR9nm3kM_D3c1xn88BQ';
print_r($token);

//If the post is not published, print error details
$facebook = new Facebook(array(
    'appId'  => '145411908958941',
    'secret' => 'f3fef75dd09aba370efb8974b3a43f45',
    'cookie' => false,
    ));

try {
$attachment = array('message' => 'This is simple script to post to facebook wall',
            'access_token' => $token,
                    'name' => 'Attachment Name',
                    /*'caption' => 'Attachment Caption',
                    'link' => 'http://apps.facebook.com/TheFour4Amigos/',
                    'description' => 'Description .....',
                    'picture' => 'http://www.google.com/logo.jpg',*/
                    'actions' => array(array('name' => 'Action Text', 
                                      'link' => 'http://apps.facebook.com/TheFour4Amigos/'))
                    );

$result = $facebook->api('/TheFour4Amigos/feed/', 'post', $attachment);
}

//If the post is not published, print error details
catch (FacebookApiException $e) {
echo '<pre>';
print_r($e);
echo '</pre>';
}
?>