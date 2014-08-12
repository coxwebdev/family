<?
require_once "utils.php";

if (empty($_SESSION['user']))
   redirToLogin();
if (!isset($conn))
   die('Invalid Request');
if (empty($_REQUEST['person_id']))
   die('Missing Parameter');

$person = getIndividual($conn, $_REQUEST['person_id']);

?>
