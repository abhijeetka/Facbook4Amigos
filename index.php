<?php
  // Remember to copy files from the SDK's src/ directory to a
  // directory in your application on the server, such as php-sdk/
  require_once('facebook-php-sdk-master/src/facebook.php');
  $toPost = array();
  static $counts =0;
  $feed = "feed";
  $len = 16;
    $base='ABCDEFGHKLMNOPQRSTWXYZabcdefghjkmnpqrstwxyz123456789';
    $max=strlen($base)-1;
  $config = array(
    'appId' => '145411908958941',
    'secret' => 'f3fef75dd09aba370efb8974b3a43f45',
    'fileUpload' => true,
  );

  $facebook = new Facebook($config);
  $user_id = $facebook->getUser();
?>
<html>
  <head>
	<title>The 4-Amigos</title>
  </head>
  <body>

  <?php
    if($user_id) {

      // We have a user ID, so probably a logged in user.
      // If not, we'll get an exception, which we handle below.
      try {
//TechnoHackzs ComputerFrEaKs2592 developernolife tagcloudmarketing happydentindia GadgetsToUse I-am-a-programmer-I-control-Your-Life
        $fb_accounts = $facebook->api('/me/accounts');
        $access_token_post = $fb_accounts['data'][0]['access_token'];
        $user_profile = $facebook->api('/ComputerFrEaKs2592/','GET');        
        $access_token = $facebook->getAccessToken();
        $result = $facebook->api('/'.$user_profile['id'].'/feed/',array('access_token' => $access_token,'limit'=>20));
        //echo '<pre>'.print_r($result).'</pre>';
        
        $currentDate = date('d');         
         
        foreach($result['data'] as $index => $post)
	{
            $toPost[$index] = array();           
            $d1 = new DateTime($post["created_time"]);
            $postCreatedTimestamp = $d1->getTimestamp();
            $postedDate = date('d',$postCreatedTimestamp );
            if (strcmp($postedDate, $currentDate)== 0){
                if (array_key_exists('message', $post)) {
                       $toPost[$index]['message'] = $post['message'];                    
                }
                else{ $toPost[$index]['message'] =' ';}
                 if (array_key_exists('picture', $post)) {
                      $toPost[$index]['picture'] = $post['picture'];
                      echo '<pre>picture link-->'.$post['picture'].'</pre>';
                }
                else{ $toPost[$index]['picture'] =' ';}
                             
                             
            }
            
            // echo $toPost[$index]['message'].'--------------------------------------------------------------------'.$toPost[$index]['picture'];
            }     
            
            
            foreach ($toPost as $key => $value) {
                $img= '';
                $feed = "feed";
                if(empty($value['picture']) == false){
                $url = $value['picture'];
		//create image URL as facebook url for large image is different.
                $url1 = str_ireplace('_s','_n',$url); 
                
                //code for creating random text for file name..
                $activatecode='';
                mt_srand((double)microtime()*1000000);
                while (strlen($activatecode)<$len+1)
                $activatecode.=$base{mt_rand(0,$max)};
                //code ends here for random text generation
                $img = "C:\\Users\\Abhi\\Downloads\\FB\\".$activatecode.".jpg";
                //$handle = fopen($img, 'w') or die('Cannot open file:  '.$img);
               
                //file_put_contents($img, file_get_contents($url));
                getFacebookPhoto($url1, $img);
                $feed = 'photos';               
                }
                try{
                $facebook->api('/TheFour4Amigos/'.$feed, 'POST',
                                    array(                                     
                                      'message' => $value['message'],                                       
                                      'access_token' => $access_token_post,
                                       'source'=>'@'.$img
                                 ));
                          } catch (FacebookApiException $e) {
                $result = "Facebook: Failed";
                error_log($e);
            }
               $counts ++;                                      
             }
        
       
       // echo '<pre>'.  print_r($toPost).'</pre>';

      } catch(FacebookApiException $e) {
        // If the user is logged out, you can have a 
        // user ID even though the access token is invalid.
        // In this case, we'll get an exception, so we'll
        // just ask the user to login again here.
         $login_url = $facebook->getLoginUrl( array(
                       'scope' => 'publish_stream',
                       'redirect_uri' => 'http://localhost/facebookApi/index.php'
                       )); 
        echo 'Please <a href="' . $login_url . '">login.</a>';
        error_log($e->getType());
        error_log($e->getMessage());
      }   
    } else {

      // No user, print a link for the user to login
     $login_url = $facebook->getLoginUrl( array( 'scope' => 'publish_stream',
          'redirect_uri' => 'http://localhost/facebookApi/index.php'
          ) );
      echo 'Please <a href="' . $login_url . '">login.</a>';

    }
    //function to download images...
    function getFacebookPhoto($img,$fullpath) {

            $curl = curl_init();
            curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($curl, CURLOPT_URL, $img);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 0);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            $user_curl_image = curl_exec($curl);
            curl_close($curl);

            if(file_exists($fullpath)){
                @unlink($fullpath);
            }
            $fp = fopen($fullpath,'x');
            fwrite($fp, $user_curl_image);
            fclose($fp);
}
  echo 'The script has successfully posted '.$counts;
  ?>
 
  </body>
</html>