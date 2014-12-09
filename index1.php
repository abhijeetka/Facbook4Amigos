<?php
  // Remember to copy files from the SDK's src/ directory to a
  // directory in your application on the server, such as php-sdk/
  require_once('facebook-php-sdk-master/src/facebook.php');

  $config = array(
    'appId' => '145411908958941',
    'secret' => 'f3fef75dd09aba370efb8974b3a43f45',
    'fileUpload' => true,
  );

  $facebook = new Facebook($config);
  $user_id = $facebook->getUser();
//$photo = 'http://www.extremetechblog.com/wp-content/uploads/2012/10/develop-facebook-app.jpg';
 $photo = 'C:/Users/Abhi/Downloads/Hackintosh_Wallpaper_v5_by_Jonzy.png.jpg'; // Path to the photo on the local filesystem
  $message = 'Photo upload via the PHP SDK!';
?>
<html>
  <head></head>
  <body>

  <?php
    if($user_id) {

      // We have a user ID, so probably a logged in user.
      // If not, we'll get an exception, which we handle below.
      try {

        // Upload to a user's profile. The photo will be in the
        // first album in the profile. You can also upload to
        // a specific album by using /ALBUM_ID as the path 
        $ret_obj = $facebook->api('/me/photos', 'POST', array(
                                         'source' => '@' . $photo,
                                         'message' => $message,
                                         )
                                      );
        echo '<pre>Photo ID: ' . $ret_obj['id'] . '</pre>';
        echo '<br /><a href="' . $facebook->getLogoutUrl() . '">logout</a>';
      } catch(FacebookApiException $e) {
        // If the user is logged out, you can have a 
        // user ID even though the access token is invalid.
        // In this case, we'll get an exception, so we'll
        // just ask the user to login again here.
        $login_url = $facebook->getLoginUrl( array(
                       'scope' => 'photo_upload'
                       )); 
        echo 'Please <a href="' . $login_url . '">login.</a>';
        error_log($e->getType());
        error_log($e->getMessage());
      }   
    } else {

      // No user, print a link for the user to login
      // To upload a photo to a user's wall, we need photo_upload  permission
      // We'll use the current URL as the redirect_uri, so we don't
      // need to specify it here.
      $login_url = $facebook->getLoginUrl( array( 'scope' => 'publish_stream') );
      echo 'Please <a href="' . $login_url . '">login.</a>';

    }

  ?>

  </body>
</html>