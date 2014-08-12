<?
require_once "utils.php";

if (!isset($conn))
   die('Invalid Request');

$users = getAllUsers($conn);

drawTable($users, array("email"=>"Email", "superadmin"=>"Is Super Admin?", "modified"=>"Last Modified"), '', 'user_id', true, true);

?>
<br />
<div class="newForm">
   <form method="post" action="index.php?p=users">
      <fieldset>
         <legend>Add New User</legend>
         <div class="formCol">
            <label for="email">Email:</label><input id="email" type="text" name="email" />
         </div>
         <div class="formCol">
            <label for="superadmin">Make Super Admin?</label><?=drawCheckboxes("superadmin", array('1'=>'Yes'))?>
         </div>
         <div class="formCol">
            <br /><input class="button" type="submit" value="Add User" />
         </div>
      </fieldset>
   </form>
</div>
