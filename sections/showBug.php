<?php
function nextBug( $id_bug, $dir )
{
    $d = $dir == 'next' ? '>' : '<';
    $b = $dir == 'next' ? 'min' : 'max';
    $o = $dir == 'next' ? 'ASC' : 'DESC';

    $q = "SELECT id FROM bugz WHERE id $d $id_bug ORDER BY id $o LIMIT 1";
    $res = $GLOBALS['db']->select( $q );
    if( empty( $res ) ) // abbiamo raggiunto un boundary. cicliamo
        $res = $GLOBALS['db']->select( "SELECT id FROM bugz WHERE id = $b(id)" );

    return $res[0]['id'];        
}
?>
<div align='center'>
<a href='?section=showBug&bug_id=<?php echo nextBug($_GET['bug_id'], "prev"); ?>'>&lt;&lt;</a> 
<input type='text' size='2' style='text-align: center;' maxlength='20' value='<?php echo $_GET['bug_id']; ?>' onkeydown='if(event.keyCode==13)document.location="?section=showBug&bug_id="+this.value' />
</form>
<a href='?section=showBug&bug_id=<?php echo nextBug($_GET['bug_id'], "next"); ?>'>&gt;&gt;</a><br />
<a href='?'>torna alla lista</a><br />
<?php
$res = $db->select( "SELECT * FROM bugz natural join developers WHERE id = {$_GET['bug_id']}" );
// forse alcuni di questi [cambia] dovrebbero essere modificati soltanto dall'autore del commento.
?>
<fieldset><legend>dettagli:</legend>
<form name="stat" action="action.php" method="POST">
<input type='hidden' name='action' value='updBug' />
<input type='hidden' name='redirect' value='referer' />

<font size="+1"><b>#<?php echo $res[0]['id']; ?> <?php echo $res[0]['title']; ?></b></font> <a href='javascript:toggleShow("updTitle")'>[cambia]</a><br />
<div id='updTitle' style='display:none;'><input type='text' size='100' maxlength='255' name='title' value='<?php echo $res[0]['title']; ?>' />
 <input type='submit' value='salva' />
</div>
<table class='table1' align='center'>
 <tr>
  <td>
    <b>autore:</b> <?php echo $res[0]['name'].' '.$res[0]['surname']; ?>
  </td>
  <td>
   <b>tempo stimato (gg/uomo):</b> <?php echo $res[0]['eta']; ?> <?php

    echo "<select name='eta' onchange='window.document.stat.submit()'><option></option>";
    for( $i = 0; $i <= 50; $i++ )
        echo "<option value='$i'>$i</option>";

    echo "</select>";
    ?>
 </td>
  <td rowspan='3'>
    <b>assegnato a:</b> <?php

    $q = "SELECT * FROM bug_dev natural join developers where id = {$res[0]['id']} order by surname";
    $ass = $db->select( $q );
    foreach( $ass as $a )
        $assigned .= '<br />'.$a['name'].' '.$a['surname'];

    echo $assigned.'<br /><br />';
    ?> <a href='javascript:toggleShow("updAssigned")'>[cambia]</a>
    <div id='updAssigned' style='display:none'>
    <select name="assigned[]" multiple size='3'>
    <?php

    $res_dev = $db->select( "select * from developers order by surname" );
    foreach( $res_dev as $tupla )
        echo "<option value='{$tupla['id_dev']}'>{$tupla['surname']} {$tupla['name']}</option>\n";

    ?>
    </select>
    <script language='javascript' type='text/javascript'>
    function unassigna()
    {
        document.getElementById( 'unassign' ).value = 1;
        window.document.forms['stat'].submit();
    }
    </script>
    <input type='hidden' name='unassign' id='unassign' value='0' />
    <input type='button' value='a nessuno' onclick='unassigna()' />
    <input type='submit' value='salva' />
    </div>
  </td>
 </tr>
 <tr>
  <td>
    <b>data inserimento:</b> <?php echo format_date_time($res[0]['dataora']); ?>
  </td>
  <td rowspan='2' align='center'>
    <b>deadline:</b> <?php echo format_date( $res[0]['deadline'] ); ?> <script>DateInput('deadline', true, 'YYYYMMDD')</script> &nbsp;&nbsp;<input type='submit' value='update deadline'><br />
  </td>
 </tr>
 <tr>
  <td>
    <b>ultimo aggiornamento:</b> <?php echo format_date_time($res[0]['lastupdate']); ?><br />
  </td>
 </tr>
</table>
<br />
<table class='table1' align='center'>
 <tr align='center'>
  <th>
    <b>priorit&agrave;: </b>
  </th>
  <th>
    <b>stato:</b>
  </th>
  <th>
    <b>tipo intervento: </b>
  </th>
  <th>
    <b>risoluzione:</b>
  </th>
 </tr>
 <tr align='center'>
  <td>
    <?php echo $res[0]['priorita']; ?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
  </td>
  <td>
    <?php echo $res[0]['status']; ?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
  </td>
  <td>
    <?php echo $res[0]['tipo_intervento']; ?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
  </td>
  <td>
    <?php echo $res[0]['risoluzione']; ?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
  </td>
 </tr>
 <tr align='center'>
  <td>
     <select name="priorita" onchange="window.document.stat.submit();">
      <option></option>
      <option>0 - NONE</option>
      <option>1 - LOW</option>
      <option>2 - MEDIUM</option>
      <option>3 - HIGH</option>
      <option>4 - URGENT</option>
     </select>
  </td>
  <td>
     <select name="status" onchange="window.document.stat.submit();">
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
    <select name='tipo_intervento' onchange="window.document.stat.submit();">
      <option></option>
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
    <select name='risoluzione' onchange="window.document.stat.submit();">
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
<b>completamento:</b> <?php echo trim( $res[0]['completed'] ); ?>% <select name='completed' onchange='window.document.stat.submit();'><option></option><?php
for( $i = 0; $i <= 100; $i+=10 )
    echo "<option value='$i'>$i</option>";
?>
</select>
 <input type="hidden" name="bug_id" value="<?php echo $_GET['bug_id']; ?>" />
</form>
<form name='stat_' action='action.php' method='post'>
<input type='hidden' name='action' value='updBug' />
<input type='hidden' name='redirect' value='referer' />
 <input type="hidden" name="bug_id" value="<?php echo $_GET['bug_id']; ?>" />
<br />
<b>descrizione:</b> <a href='javascript:toggleShow("updDesc")'>[cambia]</a><br />
<div id='updDesc' style='display: none;'><textarea name="description" style='width:98%;' rows="12"><?php echo $res[0]['description']; ?></textarea><br />
<input type='submit' value='salva' />
</div><br />
</form>
<br />
<?php
echo nl2br( $res[0]['description'] );
?>
</div>
</fieldset>
<div align='center'>
<?php

$res = $db->select( "SELECT * FROM commentz natural join developers WHERE id = {$_GET['bug_id']} ORDER BY dataora" );

foreach( $res as $t )
	echo "<br /><fieldset><legend>{$t['name']} {$t['surname']} - ".format_date_time($t['dataora'])."</legend><div align='left'>".nl2br($t['comment'])."
	<hr style='border: 1px solid #777;' /><a class='lnk2' href='javascript:delCom({$t['id_c']}, {$_GET['bug_id']})'><code>&lt;elimina commento&gt;</code></a></div>
	</fieldset>";

?>
<br />
<?php

$r = $db->select( "SELECT count(id_c) FROM commentz WHERE id = {$_GET['bug_id']}" );

echo "<br /><b>{$r[0]['count']}</b> commenti per questo bug.<br />";
?>
<br />
<a class="lnk" href="javascript:show('ins_com');goTo('bottom');">inserisci commento</a><br />
<div style="display:none;" id="ins_com">
<fieldset><legend>commento</legend>
<form name="comment" action="action.php" method="POST">
<input type='hidden' name='action' value='newComment' />
<input type='hidden' name='redirect' value='referer' />
<b>commento:</b><br />
<textarea name="comment" style='width:98%;' rows="12"></textarea><br />
<input type="hidden" name="bug_id" value="<?php echo $_GET['bug_id']; ?>"><br />
<input type="submit" name="send_comment" value="invia commento">
</form>
</fieldset>
</div>
</div>
