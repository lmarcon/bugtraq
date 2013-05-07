<div align='center'>
<fieldset><legend>inserisci nuovo</legend>
<form name="form" action="action.php" method="POST">
<input type='hidden' name='action' value='newBug' />
<input type='hidden' name='redirect' value='' />
<b>titolo:</b><br />
<input type="text" name="title" size="100"><br /><br />
<table align='center'><tr align='center'><td width="33%"><b>deadline:</b>
<script>DateInput('deadline', true, 'YYYYMMDD')</script>
</td>
<td width="33%">
<b>eta - tempo stimato<br />(in gg/uomo):</b><br />
<select name='eta'>
<option></option>
<?php
for( $i = 1; $i <= 50; $i++ )
    echo "<option value='$i'>$i</option>";
?>
</select>
</td>
<td width="33%">
<b>assegnato a:</b><br />
<select name="assigned[]" multiple size='3'>
<?php

$res = $db->select( "select * from developers order by surname" );
foreach( $res as $tupla )
    echo "<option value='{$tupla['id_dev']}'>{$tupla['surname']} {$tupla['name']}</option>\n";

?>
</select>
</td>
</tr>
</table>
<br />

<table align='center'>
<tr align='center'>
<td>
<b>priorit&agrave;:</b>
</td>
<td>
<b>stato:</b>
</td>
<td>
<b>tipo intervento:</b>
</td>
<td>
<b>risoluzione:</b>
</td>
</tr>
<tr>
<td>
<select name="priorita">
<option>0 - NONE</option>
<option>1 - LOW</option>
<option>2 - MEDIUM</option>
<option>3 - HIGH</option>
<option>4 - URGENT</option>
</select>
</td>
<td>
<select name="stato">
  <option></option>
  <option>Unconfirmed</option>
  <option>New</option>
  <option>Assigned</option>
  <option>Pending</option>
  <option>Planned</option>
  <option>Progress</option>
  <option>Resolved</option>
  <option>Verified</option>
  <option>Reopened</option>
  <option>Closed</option>
  <option>Archived</option>
</select>
</td>
<td>
<select name='tipo_intervento'>
  <optgroup label="Analysis & Design">
      <option>Analysis</option>
      <option>Design</option>
      <option>Plan</option>
  </optgroup>
  <optgroup label="Develop & Testing">
      <option>Develop</option>
      <option>Custom</option>
      <option>Test</option>
      <option>Debug</option>
  </optgroup>
  <optgroup label="Deploy & Configure">
      <option>Deploy</option>
      <option>Configure</option>
      <option>Profile</option>
      <option>Access</option>
  </optgroup>
  <optgroup label="Support & Training">
      <option>Issue</option>
      <option>Data</option>
      <option>Training</option>
      <option>Support</option>
      <option>Info</option>
  </optgroup>
  <optgroup label="Corrective Maintenance">
      <option>Defect</option>
      <option>Patch</option>
      <option>Maintenance</option>
  </optgroup>
  <optgroup label="Evolutionary Maintenance">
      <option>Enhancement</option>
      <option>Feature</option>
      <option>Upgrade</option>
  </optgroup>
  <option>Other</option>
</select>
</td>
<td>
    <select name='risoluzione'>
        <option></option>
        <option>Fixed</option>
        <option>Workaround</option>
        <option>Remind</option>
        <option>Later</option>
        <option>Wontfix</option>
        <option>Incomplete</option>
        <option>Expired</option>
        <option>Duplicate</option>
        <option>Invalid</option>
        <option>Worksforme</option>
        <option>Moved</option>
    </select>
</td>
</tr>
</table>
<br />
<br />
<b>descrizione:</b><br />
<textarea name="description" cols="80" rows="15"></textarea><br />
<br />
<input type="submit" name="send_bug" value="inserisci">
</form>
</fieldset>
</div>
