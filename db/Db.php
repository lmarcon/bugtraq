<?php

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
/*                                                                       */
/*   DDD      AAA    TTTTTTT   AAA    BBBB      AAA     SSSS   EEEEEEE   */
/*   D  D    A   A      T     A   A   B   B    A   A   S       E         */
/*   D   D  A AAA A     T    A AAA A  B BB    A AAA A   SSSS   EEEE      */
/*   D  D   A     A     T    A     A  B   B   A     A       S  E         */
/*   DDD    A     A     T    A     A  BBBB    A     A  SSSSS   EEEEEEE   */
/*                                                                       */
/* --------------------------------------------------------------------- */
/*   class' name:  db                                                    */
/*  main purpose:  mette a disposizione un'interfaccia per le operazioni */
/*                 da fare sul database.                                 */
/*                                                                       */
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */


/*

Elenco funzioni messe a disposizione da questa classe:

	+---------------------------------------------------------+
	| function db ( )                                         |
	+---------------------------------------------------------+
	| function connect ( )                                    |
	| function disconnect ( )                                 |
	+---------------------------------------------------------+
	| function db_query ( $query )                            |
	| function numRows ( )                                    |
	| function nextRow ( )                                    |
	+---------------------------------------------------------+
	| function insert ( $query )                              |
	| function update( $query, &$row = null )                 |
	| function select ( $query, &$row = null, $name = null )  |
	| function getLine( $num, $name = null )                  |
	| function getAll ( $name = null )                        |
	| function getRows( $name = null )                        |
	| function getQuery( $name = null )                       |
	+---------------------------------------------------------+
	| function insertArray ( $arr, $table )                   |
	| function insertMatrix( $arr, $table )                   |
	+---------------------------------------------------------+
	| function last_insert( $table, $column = null )          |
	+---------------------------------------------------------+

Campi interni alla classe che ha senso accederci manualmente:

	+-------------------------------------------------------------------------------+
	| connected = un valore booleano ci dice se la connessione al database è attiva |
	| db        = la connessione al database                                        |
	| res       = il valore restituito da pg_query() chiamata db_query()            |
	+-------------------------------------------------------------------------------+
	
Breve descrizione per ogni funzione:

	// ---------------------------------------------
	// Costruttore
	// ---------------------------------------------

	function db ( )

		- Tanto inutile quanto essenziale. Però va' chiamata per inizializzare l'oggetto.
	
	// ---------------------------------------------
	// Funzioni di APERTURA e CHIUSURA connessione
	// ---------------------------------------------

	function connect ( )
	function disconnect ( )

		- queste due funzioni possono essere completamente ignorate visto che l'unico metodo
		  che veramente esegue una query in questa classe è db_query, ed egli sa benissimo
		  che si deve connettere prima di eseguire una query; quindi lo farà automaticamente

	// ---------------------------------------------
	// Funzioni di uso generale
	// ---------------------------------------------

	function db_query ( $query )

		- Esegue una query passata come argomento. Si preoccupa di eseguire la connessione al
		  database se questa non è già stata effettuata. Ritorna il valore di ritorno di
		  pg_query() in caso di successo o false in caso di errore. L'errore è echato a schermo.
		  
	function numRows ( )

		- Ritorna il numero di linee dalla precedente query di select effettuata tramite db_query
		  (equivalente di pg_num_rows)

	function nextRow ( )

		- Ritorna un array associativo con la prossima linea della precedente query di select
		  effettuata tramite db_query. (equivalente di pg_fetch_array)
	
	// ---------------------------------------------
	// Wrapper mnemonici
	// ---------------------------------------------
	
	function insert ( $query )

		- esegue la query passata per argomento tramite la db_query(). Si presuppone che la query sia
		  una query di 'insert', ma questo è abbastanza ininfluente
	
	function update( $query, &$row = null )

		- esegue la query passata per argomento tramite la db_query(). Poiché si presuppone che la
		  query sia una query di update, è possibile passare un secondo argomento che conterrà il 
		  numero di tuple modificate dalla query

	// ---------------------------------------------
	// Funzioni di select autogestita
	// ---------------------------------------------

	function select ( $query, $row = null, $name = null )

		- questo metodo facilita l'esecuzione di query di tipo select. Tramite la db_query() esegue la
		  query, dopodiché ritorna un array bidimensionale contenente tutti i risultati selezionati
		  dal database. La prima dimensione è numerica e va da 0 a N-1 (con N = numero di tuple 
		  selezionate), mentre la seconda dimensione è di tipo associativo: la chiave è il nome del
		  campo della tabella.
		  Il secondo parametro è opzionale, e permette di ritornare al chiamante il numero di linee
		  affette dalla query. 
		  Il terzo parametro, ancora opzionale, ci consente di dare un nome a questa select per riprenderla
		  in futuro tramite le apposite funzioni (le prossime 4). Infatti ogni select è memorizzata
		  nell'oggetto, ed è possibile riprenderne i risultati in qualunque momento.

		  Ad ogni modo, se siete nostalgici, potrete sempre usare il vecchio metodo db_query, che vi
		  ritornerà l'oggetto di tipo "risorsa", che dovrete pg_fetchare, dentro un ciclo while

	function getLine( $num, $name = null )

		- ritorna un array associativo contenente la $num-esima tupla. $name è l'eventuale nome passato
		  al metodo select().
	
	function getAll ( $name = null )
		
		- ritorna il risultato che ritorna select() dell'ultima select() chiamata o di quella a cui è
		  stato passato $name uguale a quello passato a questo metodo
	
	function getRows( $name = null )
	
		- ritorna il numero di linee selezionate per l'ultima select() chiamata. Se si passa anche un
		  argomento, questo sarà il nome passato al metodo select()

	function getQuery( $name = null )

		- ritorna l'ultima query passata a select() oppure quella a cui è stato passato $name uguale a
		  quello passato a questo metodo


	// ---------------------------------------------
	// Funzione di inserimento da array associativo
	// ---------------------------------------------

	function insertArray ( $arr, $table )

		- dato un array associativo ($arr), inserisce tutti i suoi valori nella tabella $table. Ci si
		  aspetta che le chiavi dell'array siano uguali ai nomi dei campi della tabella
	
	function insertMatrix( $arr, $table )

		- dato un array di array associativi ($arr), viene richiamato il metodo insertArray() tante
		  volte quanti sono gli array da inserire nel database.


	// ---------------------------------------------
	// Funzione per recuperare dati dell'ultimo inserimento come ad esempio l'id associato.
	// ---------------------------------------------

	function last_insert( $table, $column = null )

		- effettua una query di select tramite pg_query per recuperare l'ultima tupla inserita
		  nella tabella $table. Ritorna un array associativo contenente tutti i campi della tupla,
		  a meno che non sia specificato il nome di un campo come secondo argomento: in quel caso
		  ritorna il valore del campo. Utile per risalire all'id dell'ultima tupla inserita


Esempio di select tramite il metodo select():

	+------------------------------------------------------------------------------+
	| $db = new db();                                                              |
	|                                                                              |
	| $val = $db -> select( "SELECT nome,taglia,eta FROM ragazzePorche", &$row );  |
	|                                                                              |
	| for( $i = 0; $i < $row; $i++ )                                               |
	| {                                                                            |
	|		echo "Ragazza $i-esima:<BR>";                                  |
	|		echo 'Nome =   '.$val[$i]['nome'].'<BR>';                      |
	|		echo 'Taglia = '.$val[$i]['taglia'].'<BR>';                    |
	|		echo 'Eta =    '.$val[$i]['eta'].'<BR>';                       |
	| }                                                                            |
	|                                                                              |
	| echo 'La query che mi ha permesso questa selezione è: '.$db -> getQuery( );  |
	| echo 'Essa mi ha ritornato '.$db -> getRows( ).' linee !';                   |
	+------------------------------------------------------------------------------+

*/



class Db
{
	var $connected;
	var $db;

	var $res;

	
	var $arr;
	var $row;
	var $query;

	// ---------------------------------------------
	// Costruttore
	//  - Database ( )
	// ---------------------------------------------

	function db ( )
	{
		$this -> connected = false;

		$this -> arr = array ( );
		$this -> row = array ( );
		$this -> query = array ( );
	}

	
	// ---------------------------------------------
	// Funzioni di APERTURA e CHIUSURA connessione
	//  - connect ( )
	//  - disconnect ( )
	// ---------------------------------------------

	function connect ( )
	{
		global $db_host, $db_name, $db_user, $db_pass;

		if( $this -> connected )
		{
			return true;
		}
		
		$this -> db = pg_connect( "host='$db_host' dbname='$db_name' user='$db_user' password='$db_pass'" );
		//$this -> db = pg_connect( "dbname='esco_rb' user='mtc'" );

			

		if( $this -> db == false )
		{
			echo 'Errore durante la connesione al database: '.pg_last_error();
			return false;
		}

		$this -> connected = true;
		return true;
	}
	
	function disconnect ( )
	{
		if( $this -> connected && pg_close( $this -> db ) )
		{
			$this -> connected = false;
		}
	}


	// ---------------------------------------------
	// Funzioni di uso generale
	//  - db_query ( $query )
	//  - numRows ( )
	//  - nextRow ( )
	// ---------------------------------------------

	function db_query ( $query )
	{
		if( $this -> connect( ) == false )
		{
			return false;
		}
		
		$this -> res = pg_query( $this -> db, $query );

		if( $this -> res == false )
		{
			echo "Errore nell'eseguire la query.<BR>Query = \"$query\"<BR>Errore = \"".pg_last_error().'"';
			return false;
		}

		return $this -> res;
	}

	function numRows ( )
	{
		return pg_num_rows( $this -> res );
	}

	function nextRow ( )
	{
		return pg_fetch_assoc( $this -> res );
	}

	
	// ---------------------------------------------
	// Wrapper mnemonici
	//  - insert ( $query )
	//  - update ( $query, $row = null )
	// ---------------------------------------------
	
	function insert ( $query )
	{
		return $this -> db_query ( $query );
	}

	function update( $query, $row = null )
	{
		$ret = $this -> db_query ( $query );

		if( $ret != false )
		{
			$row = pg_affected_rows( $this -> res );
		}

		return $ret;
	}
	

	// ---------------------------------------------
	// Funzioni di select autogestita
	//  - select  ( $query, $row = null, $name = null )
	//  - getLine ( $num, $name = null )
	//  - getAll  ( $name = null )
	//  - getRow  ( $name = null )
	//  - getQuery( $name = null )
	// ---------------------------------------------

	function select ( $query, $row = null, $name = null )
	{
		if( $this -> db_query( $query ) == false )
		{
			return false;
		}

		$row = pg_num_rows( $this -> res );
		

		$ret = array ( );

		while( ($r =  $this -> nextRow ( )) != false )
		{
			$ret[] = $r;
		}

		if( $name == null )
		{
			$name = count( $this -> arr );
		}

		$this -> arr[$name] = $ret;
		$this -> row[$name] = $row;
		$this -> query[$name] = $query;

		return $ret;
	}
	
	function getLine( $num, $name = null )
	{
		if( $name == null )
		{
			$name = 0;
		}
		return $this -> arr[$name] [$num];
	}

	function getAll ( $name = null )
	{
		if( $name == null )
		{
			$name = 0;
		}
		return $this -> arr[$name];
	}

	function getRows( $name = null )
	{
		if( $name == null )
		{
			$name = 0;
		}
		return $this -> row[$name];
	}

	function getQuery( $name = null )
	{
		if( $name == null )
		{
			$name = 0;
		}
		return $this -> query[$name];
	}
	

	// ---------------------------------------------
	// Funzione di inserimento da array associativo
	//  - insertArray ( $arr, $table )
	//  - insertMatrix( $arr, $table )
	// ---------------------------------------------
	function insertArray ( $arr, $table )
	{
		$fields = "";
		$values = "";

		while ( $val = current($arr) )
		{
			$fld = key($arr);

			if( !empty( $fields ) )
			{
				$fields .= ", ";
				$values .= ", ";
			}

			$fields .= '"'.ereg_replace( '"', '\"', $fld ).'"';
			$values .= "'".ereg_replace( "'", "\'", $val )."'";

			next($arr);
		}
		
		return $this -> db_query( "INSERT INTO $table ( $fields ) VALUES ( $values )" );
	}

	function insertMatrix( $arr, $table )
	{
		for( $i = 0, $rows = count( $arr ); $i < $rows; $i++ )
		{
			$this -> insertArray ( $arr[$i], $table );
		}
	}


	// ---------------------------------------------
	// Funzione per recuperare dati dell'ultimo inserimento
	// come ad esempio l'id associato.
	//  - last_insert( $table, $column = null )
	// ---------------------------------------------
	function last_insert( $table, $column = null )
	{
		$oid = pg_last_oid ( $this -> res );
		
		if( $column == null )
		{
			$column = '*';
		}

		$res = pg_query( $this -> db, "SELECT $column FROM $table WHERE oid = $oid" );
		$tupla = pg_fetch_array( $res );

		if( $column == '*' )
		{
			return $tupla;
		}
		return $tupla[$column];
	}
}


function    q ( $str, $ch = "'" )
{
	return ereg_replace( $ch, "\\".$ch, $str );
}

$db = new Db ( );
$db -> connect ( );

?>
