<?
require_once "utils.php";

if (empty($_SESSION['user']))
   redirToLogin();
if (!isset($conn))
   die('Invalid Request');

if (!empty($_REQUEST['firstname']) && !empty($_REQUEST['lastname']) && !empty($_REQUEST['birthyear']) && !empty($_REQUEST['gender'])) {
   if (createPerson($_REQUEST) === false) {
      $_SESSION['errorMsg'] = 'Error adding person';
   }
   redir('index', 'add');
}

$allPersons = getAllPersons($conn);
$marriages = getMarriages($conn);
$possibleFathers = array('');
$possibleMothers = array('');
$possibleHusbands = array('');
$possibleWives = array('');
foreach ($allPersons as $person) {
   if ($person['age'] > 16) {
      if ($person['gender'] == 'M') {
         $possibleFathers[$person['person_id']] = $person['firstname'].' '.$person['lastname'];
         $possibleHusbands[$person['person_id']] = $person['firstname'].' '.$person['lastname'];
      }
      else {
         $possibleMothers[$person['person_id']] = $person['firstname'].' '.$person['lastname'];
         $possibleWives[$person['person_id']] = $person['firstname'].' '.$person['lastname'];
      }
   }
}
foreach ($marriages as $marriage) { // eliminate current marriages
   if (isset($possibleHusbands[$marriage['husband_id']]))
      unset($possibleHusbands[$marriage['husband_id']]);
   if (isset($possibleWives[$marriage['wife_id']]))
      unset($possibleWives[$marriage['wife_id']]);
}
$yearRange = array();
$thisYear = date("Y");
for ($i = $thisYear; $i >= 1954; $i--)
   $yearRange[$i] = $i;
$monthRange = array();
for ($i = 1; $i < 12; $i++)
   $monthRange[$i] = date("M", strtotime ($thisYear."-".$i."-1"));
$dayRange = array();
for ($i = 1; $i < 31; $i++)
   $dayRange[$i] = $i;

$_REQUEST['birthmonth'] = date("m");
$_REQUEST['birthday'] = date("d");
$_REQUEST['weddingmonth'] = date("m");
$_REQUEST['weddingday'] = date("d");
?>
<script type="text/javascript">
   function changeGender() {
      var gender = document.getElementById('gender').value;
      if (gender == 'M') {
         document.getElementById('husbandDiv').style.display='none';
         document.getElementById('wifeDiv').style.display='block';
      } else {
         document.getElementById('husbandDiv').style.display='block';
         document.getElementById('wifeDiv').style.display='none';
      }
   }
   function changeAge() {
      var currentYear = <?=$thisYear?>;
      var birthYear = document.getElementById('birthyear').value;
      if (currentYear - birthYear > 16) {
         document.getElementById('spouseDiv').style.display='block';
      } else {
         document.getElementById('spouseDiv').style.display='none';
      }
   }
</script>
<div class="addForm">
   <form action="?p=add" id="addForm" method="post">
      <label for="firstname">First Name:</label> <input type="text" name="firstname" id="firstname" size="15" value="" /><br />
      <label for="middlename">Middle Name:</label> <input type="text" name="middlename" id="firstname" size="15" value="" /><br />
      <label for="lastname">Last Name:</label> <input type="text" name="lastname" id="firstname" size="15" value="" /><br />
      <label for="birthyear">Birth Date:</label> <? drawSelect('birthday', $dayRange, '', 'changeAge'); ?><? drawSelect('birthmonth', $monthRange, '', 'changeAge'); ?><? drawSelect('birthyear', $yearRange, '', 'changeAge'); ?><br />
      <label for="gender">Gender:</label> <? drawSelect('gender', array('M'=>'Male', 'F'=>'Female'), '', 'changeGender'); ?><br />
      <label for="father_id">Father:</label> <? drawSelect('father_id', $possibleFathers); ?><br />
      <label for="mother_id">Mother:</label> <? drawSelect('mother_id', $possibleMothers); ?><br />
      <div id="spouseDiv">
         <div id="wifeDiv">
            <label for="wife_id">Wife:</label> <? drawSelect('wife_id', $possibleWives); ?><br />
         </div>
         <div id="husbandDiv">
            <label for="husband_id">Husband:</label> <? drawSelect('husband_id', $possibleHusbands); ?><br />
         </div>
         <label for="wedding_date">Wedding:</label> <? drawSelect('weddingday', $dayRange); ?><? drawSelect('weddingmonth', $monthRange); ?><? drawSelect('weddingyear', $yearRange); ?><br />
      </div>
      <label for="current_location">Location:</label> <input type="text" name="current_location" id="current_location" size="15" value="" /><br />
      <br />
      <div class="submitDiv"><input class="button" type="submit" value="Search" /></div>
      <br />
   </form>
</div>
<script type="text/javascript">
   changeAge();
   changeGender();
</script>
<?
?>
