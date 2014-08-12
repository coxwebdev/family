<?
require_once "utils.php";

function loginUser($email, $password, $conn = '') {
   session_destroy();
   session_start();
   $user = getUserFromDB($email, $conn);
   if (empty($user)) {
      return false;
   } else if (passwordMatches($password, $user['password'])) {
      setUserInSession($user, $conn);
      if ($user['change_password'])
         redirAfterHeaders("changePassword");
      redirAfterHeaders("index");
   }
   return false;
}

function setUserInSession($user, $conn = '') {
   $_SESSION['user'] = $user;
   $user['last_login'] = date("Y-m-d H:i:s");
   $user = putUserToDB($user, $conn);
}

function logoutUser() {
   unset($_SESSION['user']);
   unset($_SESSION['superadmin']);
   session_destroy();
   redirToLogin();
}

function changePassword($user, $new_password, $conn = '') {
   $user['password'] = oneWayEncrypt($new_password);
   $user['modified'] = date('Y-m-d H:i:s');
   putUserToDB($user, $conn);
}

function resetPassword($user, $conn = '') {
   $password = generateRandomString(8);
   $user['change_password'] = 1;
   changePassword($user, $password, $conn);
   sendEmail($user['email'], 'New User Account - Cox Family', 'Your password has been reset at <a href="http://family.coxcrew.org">http://family.coxcrew.org</a>. Your username is your email address and your temporary password is: '.$password);
}

function passwordMatches($password, $encrypted) {
   return oneWayEncrypt($password) == $encrypted;
}

function getAllUsers($conn = '') {
   return db_select($conn, "users", "user_id, email, person_id, modified");
}

function getUserFromDB($email, $conn = '') {
   $rows = db_select($conn, "users", "*", "email = ?", array($email));
   foreach ($rows as $row) {
      return $row;
   }
   return '';
}

function getUserByIDFromDB($user_id, $conn = '') {
   $rows = db_select($conn, "users", "*", "user_id = ?", array($user_id));
   foreach ($rows as $row) {
      return $row;
   }
   return '';
}

function createUser($email, $password, $person_id) {
   return array("email"=>$email, "password"=>oneWayEncrypt($password), "person_id"=>$person_id, "change_password"=>0, "created"=>date("Y-m-d H:i:s"));
}

function putUserToDB($user, $conn = '') {
   if (empty($user['user_id'])) {
      $id = insertObjIntoTable('users', $user, $conn);
      $user['user_id'] = $id;
   } else {
      updateObjInTable('users', $user, 'user_id', $conn);
   }
   return $user;
}

?>
