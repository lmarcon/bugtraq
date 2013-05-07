<?php
    echo "<a href='?section=newBug'>inserisci bug/task</a>";

	$or = '';

	if( isset( $_GET['O'] ) )
		$or = $_GET['O'];

	switch( $_GET['O'] )
	{
		default:
		case 'lastupdate':
				$sortby = 'lastupdate';
				if( !isset( $_GET['Dlast'] ) || empty( $_GET['Dlast'] ) )
				{	
					$dirLast = 'ASC';
					$dir = 'DESC';
				}
				else
				{
					$dirLast = '';
					$dir = '';
				}
				break;

		case 'stato':
				$sortby = 'status';
				if( isset( $_GET['Dstato'] ) && empty( $_GET['Dstato'] ) )
				{
					$dirStato = 'DESC';
					$dir = 'DESC';
				}
				else
					$dirStato = '';
				break;

		case 'priorita':
				$sortby = 'priorita';
				if( isset( $_GET['Dprio'] ) && empty( $_GET['Dprio'] ) )
				{
					$dirPrio = 'DESC';
					$dir = 'DESC';
				}
				else
					$dirPrio = '';
				break;

        case 'dataora':
				$sortby = 'dataora';
				if( !isset( $_GET['Ddata'] ) || empty( $_GET['Ddata'] ) )
				{	
					$dirData = 'ASC';
					$dir = 'DESC';
				}
				else
				{
					$dirData = '';
					$dir = '';
				}
				break;

        case 'deadline':
				$sortby = 'deadline';
				if( !isset( $_GET['Ddead'] ) || empty( $_GET['Ddead'] ) )
				{	
					$dirDead = 'ASC';
					$dir = 'DESC';
				}
				else
				{
					$dirDead = '';
					$dir = '';
				}
				break;

        case 'completed':
				$sortby = 'completed';
				if( !isset( $_GET['Dcomp'] ) || empty( $_GET['Dcomp'] ) )
				{	
					$dirComp = 'ASC';
					$dir = 'DESC';
				}
				else
				{
					$dirComp = '';
					$dir = '';
				}
				break;

        case 'id':
				$sortby = 'id';
				if( !isset( $_GET['Did'] ) || empty( $_GET['Did'] ) )
				{	
					$dirId = 'ASC';
					$dir = 'DESC';
				}
				else
				{
					$dirId = '';
					$dir = '';
				}
				break;
	}

	$results = "<table class='resT'>
	<tr class='header'>
	 <td><a class='lnk' href='?O=id&amp;Did=$dirId".(isset($_GET['showFixed'])?"&amp;showFixed":"")."'>ID</a></td>
	 <!--<td><a class='lnk' href='?O=dataora&amp;Ddata=$dirData".(isset($_GET['showFixed'])?"&amp;showFixed":"")."'>data</a></td>-->
	 <td><a class='lnk' href='?O=lastupdate&amp;Dlast=$dirLast".(isset($_GET['showFixed'])?"&amp;showFixed":"")."'>ultimo agg.</a></td>
	 <td><a class='lnk' href='?O=priorita&amp;Dprio=$dirPrio".(isset($_GET['showFixed'])?"&amp;showFixed":"")."'>priorit&agrave;</a></td>
     <td><a class='lnk' href='?O=stato&amp;Dstato=$dirStato".(isset($_GET['showFixed'])?"&amp;showFixed":"")."'>stato</a></td>
     <td>autore</td>
     <td style='white-space:nowrap;'>assegnato a</td>
     <td><a class='lnk' href='?O=deadline&amp;Ddead=$dirDead".(isset($_GET['showFixed'])?"&amp;showFixed":"")."'>deadline</a></td>
     <td>gg/uomo</td>
     <td><a class='lnk' href='?O=completed&amp;Dcomp=$dirComp".(isset($_GET['showFixed'])?"&amp;showFixed":"")."'>completamento</a></div>
	 <td>titolo</td>
	 <td>commenti</td>
	</tr>
	 ";
	 
	 if( isset( $_GET['showFixed'] ) )
		$query = "SELECT * FROM bugz natural join developers WHERE status LIKE 'BUG/fixed' OR status LIKE 'TODO/implementato' AND id_project = {$_SESSION['id_project']} ORDER BY $sortby $dir";
	 else
		$query = "SELECT * FROM bugz natural join developers WHERE status NOT LIKE 'BUG/fixed' AND status NOT LIKE 'TODO/implementato' AND id_project = {$_SESSION['id_project']} ORDER BY $sortby $dir";
	
	$res = $db->select( $query );
	$i = 0;
	foreach( $res as $t )
    {
        $assigned = '';
		$r = $db->select( "SELECT count(id_c) FROM commentz WHERE id = {$t['id']}" );
        $trclass = ($i == true)?'trEven':'trOdd';

        $q = "SELECT * FROM bug_dev natural join developers where id = {$t['id']} order by surname";
        $ass = $db->select( $q );
        foreach( $ass as $a )
            $assigned .= $a['name'].' '.$a['surname'].'<br />';
		
		$results .= "<tr class='tr".substr( $t['priorita'], 0, 1 )."'>
				<td>#{$t['id']}</td>
				<td style='white-space:nowrap;'>".format_date_time($t['lastupdate'])."</td>
				<td style='white-space:nowrap;'>{$t['priorita']}</td>
				<td style='white-space:nowrap;'>{$t['status']}</td>
                <td style='white-space:nowrap;'>{$t['name']} {$t['surname']}</td>
                <td style='white-space:nowrap;'>".$assigned."</td>
                <td>".format_date($t['deadline'])."</td>
                <td align='center'>{$t['eta']}</td>
                <td align='center'>".trim($t['completed'])."%<div style='float:left;background-color:green;width:".trim($t['completed'])."px'>&nbsp;</div><br clear='all' /></td>
				<td><a class='lnk' href='?section=showBug&bug_id={$t['id']}'>{$t['title']}</a></td>
				<td align='center'>{$r[0]['count']}</td>
			     </tr>";
		$i++;
	}
	if( $i == 0 )
		$results .= "<tr><td colspan='11' align='center'><i>nessun risultato.</i></td></tr>";
		
	$results .= "</table><br />";
	
	if( isset( $_GET['showFixed'] ) )
		$results .= "<a class='lnk' href='?'>mostra quelli da fixare/implementato</a>";
	else
		$results .= "<a class='lnk' href='?showFixed'>mostra quelli gi&agrave; fixati/implementati</a>";
	

?>
<div style="display:none" align='center'>
<fieldset><legend>ricerca/visualizza</legend>
ricerca nel db:
<input type="text" name="q">
<input type="submit" name="search" value="cerca">
</fieldset>
<br />
</div>
<div align='center'>
<fieldset><legend>risultati</legend>
<?php echo $results; ?>
</fieldset>
</div>
<br />
