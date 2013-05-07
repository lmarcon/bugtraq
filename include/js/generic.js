function delCom( id_c, bug_id )
{
    if( confirm( "confermi l'eliminazione del commento?" ) )
	    window.document.location = "action.php?redirect=referer&action=delComment&id_c=" + id_c + "&bug_id=" + bug_id;
}

function show( id )
{
	if( window.document.getElementById( id ) )
		window.document.getElementById( id ).style.display = 'inline';
}
function hide( id )
{
	if( window.document.getElementById( id ) )
		window.document.getElementById( id ).style.display = 'none';
}
function goTo( where )
{
	window.document.location = '#' + where;
}

function toggleShow( id )
{
	var c = document.getElementById( id );

	if( c )
		c.style.display = c.style.display == 'none' ? 'block' : 'none';
}

