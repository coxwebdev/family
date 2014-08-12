<?
require_once "utils.php";

function createPerson($personData) {
   $marriage = array();
   $birthdate = getDateFromObj($personData, 'birth');
   if ($birthdate === false)
      return false;
   $weddingdate = getDateFromObj($personData, 'wedding');
   if ($weddingdate === false) // should be valid even if we are not using it
      return false;

   $person['birthdate'] = $birthdate;
   $person['firstname'] = $personData['firstname'];
   $person['lastname'] = $personData['lastname'];
   $person['middlename'] = $personData['middlename'];
   $person['gender'] = $personData['gender'];
   if (!empty($personData['father_id']))
      $person['father_id'] = $personData['father_id'];
   if (!empty($personData['mother_id']))
      $person['mother_id'] = $personData['mother_id'];
   $person['current_location'] = $personData['current_location'];
   $person['person_id'] = insertObjIntoTable('persons', $person);

   if ($person['gender'] == 'M' && !empty($person['wife_id'])) {
      $marriage['husband_id'] = $person['person_id'];
      $marriage['wife_id'] = $personData['wife_id'];
   } else if ($person['gender'] == 'F' && !empty($person['husband_id'])) {
      $marriage['wife_id'] = $person['person_id'];
      $marriage['husband_id'] = $personData['husband_id'];
   }
   if (!empty($marriage)) {
      $marriage['wedding_date'] = $weddingdate;
      insertObjIntoTable (marriages, $marriage);
   }
   return true;
}

function getDateFromObj($obj, $prefix) {
   if (checkdate($obj[$prefix."month"], $obj[$prefix."day"], $obj[$prefix."year"])) {
      return $obj[$prefix."year"].'-'.$obj[$prefix."month"].'-'.$obj[$prefix."day"];
   }
   return false;
}

function getAgeSQL($column, $columnAlias = '') {
   $sql = " DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW()) - TO_DAYS(".$column.")), '%Y') + 0";
   if (!empty($columnAlias))
      $sql .= " AS ".$columnAlias;
   return $sql;
}

function getDateSQL($column, $columnAlias) {
   return " DATE_FORMAT(".$column.", '%b %d') AS ".$columnAlias;
}

function getBirthDateSQL($column, $columnAlias) {
   return " DATE_FORMAT(".$column.", '%Y-%m-%d') AS ".$columnAlias;
}

function getIndividual($conn, $person_id) {
   return db_select($conn, 'persons p left join marriages m on p.person_id = m.husband_id or p.person_id = m.wife_id', "p.*, h.wife_id, w.husband_id, f.father_id, m.mother_id", "person_id = ?", array($person_id), "p.firstname");
}

function getAllPersons($conn = '') {
   return db_select($conn, 'persons p left join persons f on p.person_id = f.father_id left join persons m on p.person_id = m.mother_id', "distinct p.*, f.father_id, m.mother_id, ".getAgeSQL('p.birthdate', 'age'), "", "", "p.firstname");
}

function getMyFamily($conn = '') {
   return db_select($conn, "persons p", "p.*, ".getAgeSQL("birthdate", "age"), "person_id = ? OR father_id = ? OR mother_id = ? OR person_id IN (select husband_id from marriages where wife_id = ?) OR person_id IN (select wife_id from marriages where husband_id = ?)", array($_SESSION['user']['person_id'], $_SESSION['user']['person_id'], $_SESSION['user']['person_id'], $_SESSION['user']['person_id'], $_SESSION['user']['person_id']), "birthdate");
}

function getMaxAge($conn = '') {
   $result = db_select($conn, "persons p", "MAX(".getAgeSQL("birthdate").") as max_age");
   return $result[0]['max_age'];
}

function getMarriages($conn = '') {
   return db_select($conn, "persons as husband, persons as wife, marriages m", "husband.person_id as husband_id, wife.person_id as wife_id, husband.firstname as husband_name, husband.lastname as lastname, wife.firstname as wife_name, ".getDateSQL("wedding_date", "anniversary").", ".getAgeSQL("wedding_date", "years_married")."", "husband.person_id = m.husband_id AND wife.person_id = m.wife_id", array(), "wedding_date");
}

function doSearch($conn, $where_clause, $where_params, $order_by) {
   return db_select($conn, "persons as p left join marriages as m on p.person_id = m.wife_id or p.person_id = m.husband_id", "p.*, ".getAgeSQL('p.birthdate', 'age').", ".getBirthDateSQL("birthdate", "birthdate"), $where_clause, $where_params, $order_by);
}

function getBirthMonths($conn = '') {
   $results = db_select($conn, "persons", "DISTINCT DATE_FORMAT(birthdate, '%m') as monthnum, DATE_FORMAT(birthdate, '%b') as monthname", "", "", "monthnum");
   $rtnArray = array(''=>'');
   foreach ($results as $result) {
      $rtnArray[$result['monthnum']] = $result['monthname'];
   }
   return $rtnArray;
}


?>
