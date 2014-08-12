<?
function drawTable($data, $headers, $cell_formatting = '', $keyCol = '', $allowEdit = false, $allowDelete = false) {
?>
<table class="table">
   <tr>
<?
   if (!empty($links)) {
      foreach ($links as $key => $val) {
?>
      <th class="rowHead"><?=$val?></th>
<?
      }
   }
   if ($allowEdit && !empty($keyCol)) {
?>
      <th class="rowHead" width="20">Edit</th>
<?
   }
   if ($allowDelete && !empty($keyCol)) {
?>
      <th class="rowHead" width="20">Delete</th>
<?
   }
   foreach ($headers as $key => $val) {
?>
      <th class="rowHead"><?=$val?></th>
<?
   }
?>
   </tr>
<?
   $odd = true;
   $matches = array();
   foreach ($data as $row_data) {
?>
   <tr>
<?
      if (!empty($links)) {
         foreach ($links as $key => $val) {
?>
      <td class="<?=($odd) ? 'odd' : 'even'?>"><a href="<?=$key?><?=(strpos($key, "?")===false)?"?":"&"?><?=$keyCol?>=<?=$row_data[$keyCol]?>"><?=$val?></a></td>
<?
         }
      }
      if ($allowEdit && !empty($keyCol)) {
?>
      <td class="<?=($odd) ? 'odd' : 'even'?>"><a href="index.php?action=edit&<?=$keyCol?>=<?=$row_data[$keyCol]?>"><img class="icnEdit" src="assets/spacer.gif" width="16" height="16" /></a></td>
<?
      }
      if ($allowDelete && !empty($keyCol)) {
?>
      <td class="<?=($odd) ? 'odd' : 'even'?>"><a href="index.php?action=delete&<?=$keyCol?>=<?=$row_data[$keyCol]?>"><img class="icnDelete" src="assets/spacer.gif" width="16" height="16" /></a></td>
<?
      }

      foreach ($headers as $key => $val) {
         $value = $row_data[$key];
         if ($value == '0000-00-00' || $value == '0000-00-00 00:00:00')
            $value = '-';
//         if (preg_match('/(\d\d\d\d\-\d\d\-\d\d)/', $value, $matches))
//            $value = str_replace($matches[1], date("m/d/Y", strtotime($matches[1])), $value);
         if (preg_match('/(\d\d\:\d\d\:\d\d)/', $value, $matches))
            $value = str_replace($matches[1], date("g:i a", strtotime($matches[1])), $value);

         if ($cell_formatting == 'example') {
?>
      <td class="<?=($odd) ? 'odd' : 'even'?>"><?=$value?></td>
<?
         } else {
?>
      <td class="<?=($odd) ? 'odd' : 'even'?>"><?=$value?></td>
<?
         }
      }
      $odd = !$odd;
?>
   </tr>
<?
   }
?>
</table>
<?
}


function drawSelect($name, $options, $showOtherText = '', $onChangeFunction = '') {
   $otherID = getSelectOtherID();
   if (!empty($showOtherText)) { ?>
<script type="text/javascript">
   function showHide<?=$name?>Div() {
      var showHideDiv = document.getElementById('<?=$name?>OtherDiv');
      showHideDiv.style.display='none';
      if (document.getElementById('<?=$name?>').value == '<?=$otherID?>') {
         showHideDiv.style.display='block';
      }
   }
</script>
<? } ?>
<select name="<?=$name?>" id="<?=$name?>"<? if (!empty($showOtherText)) { ?> onchange="showHide<?=$name?>Div();"<? } else if (!empty($onChangeFunction)) { ?> onchange="<?=$onChangeFunction?>();"<? } ?>>
<?
   foreach ($options as $key => $val) {
?>
   <option value="<?=$key?>"<? if (isset($_REQUEST[$name]) && $_REQUEST[$name] == $key) echo ' selected="selected"'; ?>><?=$val?></option>
<?
   }
   if (!empty($showOtherText)) {
?>
   <option value="<?=$otherID?>"><?=$showOtherText?></option>
<? } ?>
</select>
<?
   if (!empty($showOtherText)) { ?>
<div id="<?=$name?>OtherDiv" style="display: none;">Enter your Things: <input type="text" name="<?=$name?>Other" id="<?=$name?>Other" /></div>
<? }
}


function drawCheckboxes($name, $options) {
   foreach ($options as $key => $val) {
      $id = 'chbx'.$name.'_'.$key;
?>
<div class="checkboxDiv" id="div_<?=$id?>"><input type="checkbox" name="<?=$name?>[]" id="<?=$id?>" value="<?=$key?>" /><label for="<?=$id?>"><?=$val?></label></div>
<?
   }
}

?>
