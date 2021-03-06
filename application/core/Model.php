<?php
class Model{
  private function getClient(){
    try{
      $client = new Google_Client();
      
      if (!$client)
        throw new Exception('Google_Client(Vendor) Error');
      
      $client->setApplicationName('Google Drive API PHP Quickstart');
      $client->setScopes(Google_Service_Drive::DRIVE_METADATA_READONLY);
      $client->setAuthConfig(Config::get('credentials'));
      
      if (!Config::get('credentials'))
        throw new Exception('Credentials file is not found');
      
      $client->setAccessType('offline');
      $client->setPrompt('select_account consent');
      
      // Load previously authorized token from a file, if it exists.
      $tokenPath = Config::get('token');
      
      if (file_exists($tokenPath)) {
          $accessToken = json_decode(file_get_contents($tokenPath), true);
          $client->setAccessToken($accessToken);
      } else
        throw new Exception('Token file is not found');
      
      # GET GOOGLE TOKEN FOR !CLI!
      /*
      // If there is no previous token or it's expired.
      if ($client->isAccessTokenExpired()) {
          // Refresh the token if possible, else fetch a new one.
          if ($client->getRefreshToken()) {
              $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
          } else {
              // Request authorization from the user.
              $authUrl = $client->createAuthUrl();
              printf("Open the following link in your browser:\n%s\n", $authUrl);
              print 'Enter verification code: ';
              $authCode = trim(fgets(STDIN));
      
              // Exchange authorization code for an access token.
              $accessToken = $client->fetchAccessTokenWithAuthCode($authCode);
              $client->setAccessToken($accessToken);
      
              // Check to see if there was an error.
              if (array_key_exists('error', $accessToken)) {
                  throw new Exception(join(', ', $accessToken));
              }
          }
          // Save the token to a file.
          if (!file_exists(dirname($tokenPath))) {
              mkdir(dirname($tokenPath), 0700, true);
          }
          
          file_put_contents($tokenPath, json_encode($client->getAccessToken()));
      }
      */
      
    } catch (Exception $e) {
      Logger::getLogger('log')->log($e);
      return false;
    }
    return $client;
  }
  protected function getInstance(){
    try{
      $client = $this->getClient();
      if (!$client)
        throw new Exception('Google_Client(Vendor) Error(getInstance())');
    } catch (Exception $e) {
      Logger::getLogger('log')->log($e);
      return false;
    }
    return new Google_Service_Drive($client);
  }
  private function __clone() {}
  private function __sleep(){}
  private function __wakeup(){}
}