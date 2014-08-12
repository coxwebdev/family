<?
require_once "utils.php";

if (empty($_SESSION['user']))
   redirToLogin();
if (!isset($conn))
   die('Invalid Request');

$allPersons = getAllPersons($conn);
$parents = array('');
$persons = array('');
foreach ($allPersons as $person) {
   $persons[$person['person_id']] = $person;
   if (!empty($person['father_id']) || !empty($person['mother_id']))
      $parents[$person['person_id']] = $person['firstname'].' '.$person['lastname'];
}
$ageRange = array();
for ($i = 0; $i <= getMaxAge($conn); $i++)
   $ageRange[$i] = $i;
$defaultSort = 'p.firstname';
$order_by = (empty($_REQUEST['order_by'])) ? $defaultSort : $_REQUEST['order_by'];
$current_location = (empty($_REQUEST['current_location'])) ? '' : str_replace('%', '', $_REQUEST['current_location']);
?>
<script type="text/javascript">
   function doSortBy(sort, switchOrder) {
      var orderBy = document.getElementById('order_by');
      if (orderBy.value == sort && switchOrder)
         orderBy.value = sort + ' DESC';
      else
         orderBy.value = sort;
      document.getElementById('submitForm').submit();
   }
</script>
<div class="searchForm">
   <form action="?p=search" id="submitForm" method="post">
      <label for="parent_id">Parent:</label> <?   drawSelect('parent_id', $parents); ?><br />
      <label for="birth_month">Birth Month:</label> <?   drawSelect('birth_month', getBirthMonths($conn)); ?><br />
      <label for="age_low">Age:</label> <?   drawSelect('age_low', $ageRange); ?> to: <?   drawSelect('age_high', array_reverse($ageRange, true)); ?><br />
      <label for="gender">Gender:</label> <?   drawSelect('gender', array('', 'M'=>'Male', 'F'=>'Female')); ?><br />
      <label for="current_location">Location:</label> <input type="text" name="current_location" id="current_location" size="15" value="<?=$current_location?>" /><br />
      <input type="hidden" id="order_by" name="order_by" value="<?=$order_by?>" />
      <br />
      <div class="submitDiv"><input class="button" type="submit" value="Search" onclick="doSortBy('<?=$defaultSort?>', false);" /></div>
      <br />
   </form>
</div>
<?
//debug($_REQUEST);
$query_wheres = array();
$query_params = array();
if (!empty($_REQUEST['parent_id'])) {
   $query_wheres[] = '(p.father_id = ? OR p.mother_id = ?)';
   $query_params[] = $_REQUEST['parent_id'];
   $query_params[] = $_REQUEST['parent_id'];
}
if (!empty($_REQUEST['birth_month'])) {
   $query_wheres[] = "DATE_FORMAT(p.birthdate, '%m') = ?";
   $query_params[] = $_REQUEST['birth_month'];
}
if (!empty($_REQUEST['gender'])) {
   $query_wheres[] = "gender = ?";
   $query_params[] = $_REQUEST['gender'];
}
if (!empty($_REQUEST['current_location'])) {
   $query_wheres[] = "current_location like ?";
   $query_params[] = '%'.$_REQUEST['current_location'].'%';
}
if (isset($_REQUEST['age_low'])) {
   $query_wheres[] = getAgeSQL('birthdate')." >= ?";
   $query_params[] = $_REQUEST['age_low'];
}
if (isset($_REQUEST['age_high'])) {
   $query_wheres[] = getAgeSQL('birthdate')." <= ?";
   $query_params[] = $_REQUEST['age_high'];
}

if (!empty($query_params)) {
   $results = doSearch($conn, implode(' AND ', $query_wheres), $query_params, $order_by);
   $headers = array("firstname"=>'First Name', "lastname"=>"Last Name", "age"=>'Age', "birthdate"=>'Birth Date', "gender"=>'M/F', "current_location"=>"Location");
   echo '<br />';
   foreach ($headers as $key => $value) {
      $headers[$key] = '<a href="javascript:void(0);" onclick="doSortBy(\''.$key.'\', true);">'.$value.'</a>';
   }
   ?>
<div id="numResults">Number of Results: <?=sizeof($results)?></div>
   <?
   drawTable($results, $headers, '', 'person_id');
}

?>
