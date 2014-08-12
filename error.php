<?
if (!empty($_SESSION['errorMsg'])) {
?>
<div class="errorMsg"><?=$_SESSION['errorMsg']?></div>
<?
}
$_SESSION['errorMsg'] = '';
?>