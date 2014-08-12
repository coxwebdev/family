<?
require_once "utils.php";

if (empty($_SESSION['user'])) {
   redir('login');
}

if (isset($_REQUEST['password'])) {
   if ($_REQUEST['password'] != $_REQUEST['again']) {
      $_SESSION['errorMsg'] = "Passwords do not match";
      redir('changePassword');
   }

   $conn = db_connect();
   $_SESSION['user']['change_password'] = 0;
   changePassword($_SESSION['user'], $_REQUEST['password'], $conn);
   $conn->Close();
   redir("index");
}

?>

<? include_once 'header.php'; ?>
<? include_once 'error.php'; ?>

<div class="newForm">
   <form method="post" action="?">
      <label for="new_password">New Password:</label><input id="password" type="password" name="password" />
      <label for="again">Verify:</label><input id="again" type="password" name="again" />
      <input class="button" type="submit" value="Change Password" />
   </form>
</div>

<? include_once 'footer.php'; ?>
