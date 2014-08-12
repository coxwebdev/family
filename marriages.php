<?
require_once "utils.php";

if (!isset($conn) || empty($_SESSION['user']))
   die('Invalid Request');

$myfamily = getMarriages($conn);
$headers = array("lastname"=>"Surname", "husband_name"=>'<img src="assets/user.png" alt="Husband" />', "wife_name"=>'<img src="assets/user_female.png" alt="Wife" />', "anniversary"=>'<img src="assets/marriage1.png" alt="Anniversary" />', "years_married"=>"Years");
drawTable($myfamily, $headers);

?>
