<?php 
session_start();
require_once '../../public/libraries/APIGraphFacebook/vendor/autoload.php';
require_once '../../config/config.php';
require_once '../../config/db.php';
$connection = connectDB();
$fb = new Facebook\Facebook([
    'app_id' => APIID,
    'app_secret' => APPSECRET,
    'default_graph_version' =>GRAPHVERSION
]);
$helper = $fb->getRedirectLoginHelper();
try {
    $accessToken = $helper->getAccessToken();
  } catch(Facebook\Exception\ResponseException $e) {
    echo 'Graph returned an error: ' . $e->getMessage();
    exit;
  } catch(Facebook\Exception\SDKException $e) {
    echo 'Facebook SDK returned an error: ' . $e->getMessage();
    exit;
}
if (!isset($accessToken)) {
    if ($helper->getError()) {
      header('HTTP/1.0 401 Unauthorized');
      echo "Error: " . $helper->getError() . "\n";
      echo "Error Code: " . $helper->getErrorCode() . "\n";
      echo "Error Reason: " . $helper->getErrorReason() . "\n";
      echo "Error Description: " . $helper->getErrorDescription() . "\n";
    } else {
      header('HTTP/1.0 400 Bad Request');
      echo 'Bad request';
    }
    exit;
}
$oAuth2Client = $fb->getOAuth2Client();
$tokenMetadata = $oAuth2Client->debugToken($accessToken);
$tokenMetadata->validateAppId(APIID);
$tokenMetadata->validateExpiration();
if (! $accessToken->isLongLived()) {
    try {
      $accessToken = $oAuth2Client->getLongLivedAccessToken($accessToken);
    } catch (Facebook\Exception\SDKException $e) {
      echo "<p>Error getting long-lived access token: " . $e->getMessage() . "</p>\n\n";
      exit;
    }
}
$_SESSION['fb_access_token'] = (string) $accessToken;
$responce = $fb->get('me?fields=accounts{id,name,access_token,fan_count,about,cover{source},photos.limit(1){picture},link}', $_SESSION['fb_access_token']);
$accounts = $responce->getGraphuser();
$accounts = json_decode($accounts['accounts'], true);
$accounts = json_encode($accounts);
$json =  $connection->real_escape_string($accounts);
	try {
		$truncateQuery= $connection->query("TRUNCATE TABLE facebookgraphpages");
		$queryInsert = $connection->query("INSERT INTO facebookgraphpages (JSON)
                                      VALUES ('$json')");
	} catch (PDOException $e) {
		return [];
}
header('Location: '. PATH);