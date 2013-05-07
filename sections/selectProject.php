<?php

$res = $db->select( "select t0.name as proj_name, t0.id_project, t0.creation_date, t1.name as dev_name, t1.surname from projects t0 join developers t1 on (t0.id_dev = t1.id_dev)" );
if( empty( $res ) )
    echo "<i>nessun progetto presente</i>";
else
{
    echo "<table align='center' class='table1'>
           <tr>
            <th>nome</th>
            <th>data di creazione</th>
            <th>admin</th>
           </tr>";

    foreach( $res as $tupla )
        echo "<tr>
               <td><a href='action.php?redirect=home&action=selectProject&id_project={$tupla['id_project']}'>{$tupla['proj_name']}</a></td>
               <td>".format_date( $tupla['creation_date'] )."</td>
               <td>{$tupla['dev_name']} {$tupla['surname']}</td>
              </tr>";

    echo "</table>";

}
?>
<br /><br />
<a href='?section=newProject'>crea nuovo progetto</a>
