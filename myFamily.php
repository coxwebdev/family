<?
require_once "utils.php";

if (empty($_SESSION['user']))
   redirToLogin();
if (!isset($conn))
   die('Invalid Request');

$myfamily = getMyFamily($conn);
$headers = array("firstname"=>"First Name", "lastname"=>"Last Name", "age"=>"Age", "gender"=>"Gender");
drawTable($myfamily, $headers);

?>
