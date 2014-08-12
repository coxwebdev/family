<?
require_once 'utils.php';

if (!empty($_SESSION['user'])) { ?>
<div class="nav">
   <a href="index.php?p=myFamily"><img src="assets/family.png" alt="My Family" /></a> |
   <a href="index.php?p=marriages"><img src="assets/marriage1.png" alt="Marriages" /></a> |
   <a href="index.php?p=search"><img src="assets/magnifier.png" alt="Search" /></a> |
   <a href="index.php?p=add"><img src="assets/add.png" alt="Add Person" /></a> |
   <a href="logout.php"><img src="assets/power.png" alt="Logout" /></a>
</div>
<? } ?>
