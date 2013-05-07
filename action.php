<?php
session_start();

// create table commentz ( id_c serial, id integer, dataora char(12), author varchar, comment text );

if( !isset( $_COOKIE['author'] ) ) // autore
        setcookie("author", $_POST['author'], time()+86400*365);

include( "db/config.inc" );
include( "db/Db.php" );

switch( $_REQUEST['action'] )
{
	case 'com_insert':

		$db->insert( "INSERT INTO commentz ( id, dataora, author, comment ) VALUES (
				'{$_POST['bug_id']}',
				'".date( 'YmdHi' )."',
				'{$_POST['author']}',
				'{$_POST['comment']}'
			)" );

		$db->update( "UPDATE bugz SET lastupdate = '".date('YmdHi')."' WHERE id = {$_POST['bug_id']}" );
		break;

	case 'bug_insert':

		$db->insert( "INSERT INTO bugz ( dataora, author, status, title, description, lastupdate, priorita ) VALUES (
				'".date( 'YmdHi' )."',
				'{$_POST['author']}',
				'{$_POST['stato']}',
				'{$_POST['title']}',
				'{$_POST['description']}',
				'".date( 'YmdHi' )."',
				'{$_POST['priorita']}'
			)" );
        break;

    case 'login':
        $res = $db->select( "select * from developers where email ilike '{$_REQUEST['email']}'" );
        {
            if( empty( $res ) )
                break;

            if( md5( $_REQUEST['pwd'] ) != $res[0]['pwd'] )
                break;

            $_SESSION['auth']['id_dev'] = $res[0]['id_dev'];
            $_SESSION['auth']['email'] = $res[0]['email'];
            $_SESSION['auth']['name'] = $res[0]['name'];
            $_SESSION['auth']['surname'] = $res[0]['surname'];
        }

        break;

    case 'logout':
        unset( $_SESSION['auth'] );
        unset( $_SESSION['id_project'] );
        break;

    case 'createProject':
        $db->insert( "insert into projects ( creation_date, name, description, id_dev ) values ( '".date( 'YmdHi' )."', '{$_REQUEST['name']}', '{$_REQUEST['description']}', '{$_SESSION['auth']['id_dev']}' )" );
        $_SESSION['id_project'] = $db->last_insert( 'projects', 'id_project' );
        $res = $db->select( "select * from projects where id_project = {$_SESSION['id_project']}" );
        $_SESSION['name_project'] = $res[0]['name'];
        break;

    case 'selectProject':
        $_SESSION['id_project'] = $_REQUEST['id_project'];
        $res = $db->select( "select * from projects where id_project = {$_REQUEST['id_project']}" );
        $_SESSION['name_project'] = $res[0]['name'];
        break;

    case 'newBug':
        $q = "INSERT INTO bugz ( dataora, id_project, id_dev, status, title, description, lastupdate, priorita, deadline, completed, eta, tipo_intervento, risoluzione ) VALUES (
                '".date( 'YmdHi' )."',
                '{$_SESSION['id_project']}',
                '{$_SESSION['auth']['id_dev']}',
                '{$_REQUEST['stato']}',
                '{$_REQUEST['title']}',
                '{$_REQUEST['description']}',
                '".date( 'YmdHi' )."',
                '{$_REQUEST['priorita']}',
                '{$_REQUEST['deadline']}',
                '0',
                '{$_REQUEST['eta']}',
                '{$_REQUEST['tipo_risoluzione']}',
                '{$_REQUEST['risoluzione']}'
            )";

				$db->insert($q);

        if( !empty( $_REQUEST['assigned'] ) )
        {
            $bug_id = $db->last_insert( 'bugz', 'id' );
            foreach( $_REQUEST['assigned'] as $as )
                $db->insert( "INSERT INTO bug_dev VALUES ( $as, $bug_id )" );
        }

        break;

    case 'newComment':
        $db->insert( "INSERT INTO commentz ( id, dataora, id_dev, comment ) VALUES (
                '{$_REQUEST['bug_id']}',
                '".date( 'YmdHi' )."',
                '{$_SESSION['auth']['id_dev']}',
                '{$_REQUEST['comment']}'
            )" );

        $db->update( "UPDATE bugz SET lastupdate = '".date('YmdHi')."' WHERE id = {$_POST['bug_id']}" );
        break;

    case 'delComment':
        $db->update( "DELETE FROM commentz WHERE id_c = {$_REQUEST['id_c']}" );
        break;

    case 'updBug': // queste forse sono da separare in casi diversi TODO
        if( !empty( $_POST['priorita'] ) )
            $db->update( "UPDATE bugz SET priorita = '{$_POST['priorita']}', lastupdate = '".date( 'YmdHi' )."' WHERE id = {$_POST['bug_id']}" );
            
        if( !empty( $_POST['status'] ) )
            $db->update( "UPDATE bugz SET status = '{$_POST['status']}', lastupdate = '".date( 'YmdHi' )."' WHERE id = {$_POST['bug_id']}" );
            
        if( is_numeric( $_POST['eta'] ) )
            $db->update( "UPDATE bugz SET eta = '{$_POST['eta']}', lastupdate = '".date( 'YmdHi' )."' WHERE id = {$_POST['bug_id']}" );
            
        if( !empty( $_POST['deadline'] ) )
            $db->update( "UPDATE bugz SET deadline = '{$_POST['deadline']}', lastupdate = '".date( 'YmdHi' )."' WHERE id = {$_POST['bug_id']}" );

        if( is_numeric( $_POST['completed'] ) )
            $db->update( "UPDATE bugz SET completed = '{$_POST['completed']}', lastupdate = '".date( 'YmdHi' )."' WHERE id = {$_POST['bug_id']}" );
            
        if( !empty( $_POST['assigned'] ) || $_POST['unassign'] == 1 )
        {
            $db->update( "delete from bug_dev where id = {$_POST['bug_id']}" );
            if( !empty( $_REQUEST['assigned'] ) )
                foreach( $_REQUEST['assigned'] as $as )
                    $db->insert( "INSERT INTO bug_dev VALUES ( $as, {$_POST['bug_id']} )" );
        }

        if( !empty( $_POST['description'] ) )
            $db->update( "UPDATE bugz SET description = '{$_POST['description']}', lastupdate = '".date( 'YmdHi' )."' WHERE id = {$_POST['bug_id']}" );
            
        if( !empty( $_POST['title'] ) )
            $db->update( "UPDATE bugz SET title = '{$_POST['title']}', lastupdate = '".date( 'YmdHi' )."' WHERE id = {$_POST['bug_id']}" );
            
        if( !empty( $_POST['tipo_intervento'] ) )
            $db->update( "UPDATE bugz SET tipo_intervento = '{$_POST['tipo_intervento']}', lastupdate = '".date( 'YmdHi' )."' WHERE id = {$_POST['bug_id']}" );
            
        if( !empty( $_POST['risoluzione'] ) )
            $db->update( "UPDATE bugz SET risoluzione = '{$_POST['risoluzione']}', lastupdate = '".date( 'YmdHi' )."' WHERE id = {$_POST['bug_id']}" );

        break;
}

if( empty( $_REQUEST['redirect'] ) )
    header( "Location: index.php?project_id={$_POST['project_id']}&bug_id={$_POST['bug_id']}#bottom" );
elseif( $_REQUEST['redirect'] == 'referer' )
    header( "Location: {$_SERVER['HTTP_REFERER']}" );
elseif( $_REQUEST['redirect'] == 'home' )
    header( "Location: index.php" );
else
    header( "Location: index.php?section={$_REQUEST['redirect']}" );
?>
