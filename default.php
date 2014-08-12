<?
require_once "utils.php";

if (!isset($conn))
   die('Invalid Request');

if (empty($_SESSION['user'])) {
?>
<div class="login">
<form method="post" action="login.php">
      <label for="email">Email:</label><input id="email" type="email" name="email" />
      <label for="email">Password:</label><input id="password" type="password" name="password" />
      <br />
      <br />
      <div class="submitDiv"><input class="button" type="submit" value="Login" /></div>
      <br />
      <br />
      <div class="small"><a href="forgotPassword.php">Forgot Password?</a></div>
</form>
</div>
<?
}
?>
