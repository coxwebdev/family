<?
require_once "utils.php";

if (isset($_REQUEST['email'])) {
   $conn = db_connect();
   $user = loginUser($_REQUEST['email'], $_REQUEST['password'], $conn);
   $conn->Close();
   if ($user === false) {
      $_SESSION['errorMsg'] = 'Invalid Login';
      redirToLogin();
   } else {
      redir("index");
   }
}

?>
