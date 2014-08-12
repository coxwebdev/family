<?
require_once "utils.php";

$conn = db_connect();

//debug($_COOKIE);
//debug($_SESSION);

$current_page = 'default';
if (!empty($_REQUEST['p']))
   $current_page = $_REQUEST['p'];
else if (!empty($_SESSION['user']))
   $current_page = 'myFamily';

$title = '';
if ($current_page != 'default') {
   if (empty($_SESSION['user']))
      redirToLogin();

   $title = preg_replace("([A-Z])", " $0", $current_page);
   $title = strtoupper(substr($title, 0, 1)).substr($title, 1);
}

//resetPassword(putUserToDB(createUser('dncox54@gmail.com', 'cox1976', 1)));

//debug($title);
?>

<? include_once 'header.php'; ?>
<? include_once 'nav.php'; ?>
<? include_once 'error.php'; ?>

<? include_once $current_page.'.php'; ?>

<?
$conn->Close();
?>

<? include_once 'footer.php'; ?>
