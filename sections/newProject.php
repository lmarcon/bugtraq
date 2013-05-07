<form name='form' action='action.php' method='post'>
<input type='hidden' name='action' value='createProject' />
<input type='hidden' name='redirect' value='' />
nome <em>*</em> <input type='text' name='name' /><br /><br /> <!-- TODO validazione e check nome unico (ajax) -->
descrizione<br /><textarea name='description' cols='60' rows='5' ></textarea><br />
<br />
<input type='submit' value='crea progetto'>
</form>
