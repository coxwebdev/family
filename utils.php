<?
// Pickem

function isDebug() {
   return true;
}

if (isDebug()) {
   error_reporting(E_ALL);
   ini_set("display_errors", 1);
}

session_start();
require_once 'person.php';
require_once 'adodb5/adodb.inc.php';
require_once 'PHPMailer-master/PHPMailerAutoload.php';

require_once 'user.php';
require_once 'htmlStructures.php';

function db_connect() {
   $db_type = "mysqli";
   $db_server = 'localhost';
   $db_name = "if4id7bh_coxfam";
   $db_username = "if4id7bh_family";
   $db_password = rawurlencode("My(043&ZDeAS");
   $dsn_options='?persist=0&fetchmode=2';
   return NewADOConnection("$db_type://$db_username:$db_password@$db_server/$db_name$dsn_options");
}

function getSelectOtherID() {
   return "~Other~";
}

function db_select($conn, $table, $columns = "*", $where_clause = "", $where_params = "", $orderby = "") {
   if ($conn == '')
      $conn = db_connect();

   $sql = "SELECT ".$columns." FROM ".$table;
   if ($where_clause != "")
      $sql .= " WHERE ".$where_clause;
   if (!empty($orderby))
      $sql .= " ORDER BY ".$orderby;
//   debug($sql);
//   debug($where_params);

   if ($where_params == "")
      $rs = $conn->Execute($sql);
   else
      $rs = $conn->Execute($sql, $where_params);

   if($rs === false) {
      debug('Wrong SQL: ' . $sql . ' Error: ' . $conn->ErrorMsg() . debug($where_params));
      return array();
   }
   $rows = $rs->GetRows();
   $rs->Close();
   return $rows;
}

function insertObjIntoTable($table, $obj, $conn = '') {
   return db_insert($table, array_keys($obj), array_values($obj), $conn);
}

function db_insert($table, $cols, $values, $conn = '') {
   if ($conn == '')
      $conn = db_connect();
   if($table == '')
      debug('No table specified');

   $sql = "INSERT INTO ".$table." ( ".implode(",", $cols)." ) VALUES ( ";
   foreach ($values as $tmp)
      $sql .= "?,";
   $sql = substr($sql, 0, -1).")";
   if($conn->Execute($sql, $values) === false) {
      debug('Wrong SQL: ' . $sql . ' Error: ' . $conn->ErrorMsg());
      debug($values);
   }
   return $conn->Insert_ID();
}

function updateObjInTable($table, $obj, $key_col, $conn = '') {
   $updates = array();
   foreach ($obj as $key => $val) {
      if ($key != $key_col)
         $updates[$key] = $val;
   }
   $where_clause = $key_col." = ?";
   $where_params = array($obj[$key_col]);
   return db_update($table, $updates, $where_clause, $where_params, $conn);
}

function db_update($table, $updates, $where_clause, $where_params, $conn = '') {
   if ($conn == '')
      $conn = db_connect();
   if($table == '')
      debug('No table specified');

   $sql = "UPDATE ".$table." SET ";
   $params = array();
   foreach ($updates as $key => $value) {
      $sql .= $key." = ?, ";
      $params[] = $value;
   }
   $sql = substr($sql, 0, -2)." WHERE ".$where_clause;
   if($conn->Execute($sql, array_merge($params, $where_params)) === false) {
      debug('Wrong SQL: ' . $sql . ' Error: ' . $conn->ErrorMsg() . debug($where_params));
   }
   return $conn->Affected_Rows();
}

function deleteObjInTable($table, $obj, $key_col, $conn = '') {
   return db_delete($table, $key_col." = ?", array($obj[$key_col]), $conn);
}

function deleteObjInJoinTable($table, $obj, $key_cols, $conn = '') {
   $where = '';
   $val_array = array();
   foreach ($key_cols as $key_col) {
      if (!empty($where))
         $where .= " AND ";
      $where .= $key_col." = ? ";
      $val_array[] = $obj[$key_col];
   }
   return db_delete($table, $where, $val_array, $conn);
}

function db_delete($table, $where_clause = "", $where_params = "", $conn = '') {
   if ($conn == '')
      $conn = db_connect();
   if($table == '')
      debug('No table specified');

   $sql = "DELETE FROM ".$table." WHERE ".$where_clause;
   $rs = "";
   if ($where_params == "")
      $rs = $conn->Execute($sql);
   else
      $rs = $conn->Execute($sql, $where_params);

   if($rs === false) {
      debug('Wrong SQL: ' . $sql . ' Error: ' . $conn->ErrorMsg() . debug($where_params));
   }
   return $conn->Affected_Rows();
}

function sendEmail($to, $subject, $body) {
   $mail = new PHPMailer;

   $mail->isSMTP();                                      // Set mailer to use SMTP
   $mail->Host = 'server308.webhostingpad.com';          // Specify main and backup server
   $mail->SMTPAuth = true;                               // Enable SMTP authentication
   $mail->Port = '465';
   $mail->Username = 'noreply@coxcrew.org';              // SMTP username
   $mail->Password = ';m(s2w,TE6?_';                     // SMTP password
   $mail->SMTPSecure = 'ssl';                            // Enable encryption, 'ssl' also accepted

   $mail->From = 'noreply@coxcrew.org';
   $mail->FromName = 'Cox Crew Family';
   $mail->addAddress($to);                               // Name is optional
   $mail->isHTML(true);                                  // Set email format to HTML

   $mail->Subject = $subject;
   $mail->Body    = $body;
   $mail->AltBody = $body;

   if(!$mail->send()) {
      debug('Message could not be sent: '.$mail->ErrorInfo);
      die();
   }
//   debug($mail->isError());
//   debug($mail->ErrorInfo);
//   die();
}

function generateRandomString($length) {
   $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
   $randomString = '';
   for ($i = 0; $i < $length; $i++) {
      $randomString .= $characters[rand(0, strlen($characters) - 1)];
   }
   return $randomString;
}

function shuffle_assoc($array) {
   $keys = array_keys($array);
   shuffle($keys);
   $new = array();
   foreach($keys as $key) {
      $new[$key] = $array[$key];
   }
   return $new;
}

function purgeString($str) {
   $blacklist = explode(file_get_contents("blacklist.txt"), ",");
   return str_replace($blacklist, '', $str);
}

function oneWayEncrypt($string) {
   return crypt($string, "T2w890hfSLkSF02$)(2kdsSFDna20~");
}

function getEncryptionKey() {
   return md5('fwj!@_fdsLK39)20c');
}

function encrypt($string) {
   $key = getEncryptionKey();
   return base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $key, $string, MCRYPT_MODE_CBC, md5(md5($key))));
}

function decrypt($string) {
   $key = getEncryptionKey();
   return trim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $key, base64_decode($string), MCRYPT_MODE_CBC, md5(md5($key))));
}

function redirToLogin() {
   redir("index");
}

function redir($page, $p = '') {
   if (headers_sent())
      die("Headers already sent");
   header("Location: $page.php".((!empty($p))?"?p=".$p:""));
   die();
}

function redirAfterHeaders($page, $p = '') {
   echo '<meta http-equiv="refresh" content="0; url=/'.$page.'.php'.((!empty($p))?"?p=".$p:"").'" />';
   die();
}

function debug($data, $return = false) {
   if (!isDebug())
      return;

   if ($return)
      return debug_backtrace().print_r($data, true);

   echo '<pre>';
	debug_print_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
	print_r($data);
	echo '</pre>';
}


?>
