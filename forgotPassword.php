<?
require_once "utils.php";

if (isset($_REQUEST['email'])) {
   $conn = db_connect();
   $user = getUserFromDB($_REQUEST['email'], $conn);
   if (!empty($user)) {
      resetPassword($user, $conn);
   } else {
      $_SESSION['errorMsg'] = 'User Not Found';
   }
   $conn->Close();
   redirToLogin();
}

?>

<? include_once 'header.php'; ?>
<? include_once 'error.php'; ?>

<div class="newForm">
   <form method="post" action="?">
      <label for="email">Email:</label><input id="email" type="text" name="email" />
      <input class="button" type="submit" value="Reset Password" />
   </form>
</div>

<? include_once 'footer.php'; ?>
