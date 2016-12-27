<?

require("lib.php");


//соединение с бд
db_connect();

$rules = getrules();

if ($rules != 'a') {
	db_disconnect();
	//var_dump($rules);
	exit;
}



@$user = $_GET['user'] or null;

if (isset($_POST['sr'])) {
	$user = $_POST['user'];
	@$password = $_POST['password'] or null;
	@$name = $_POST['name'] or '';
	@$rules = $_POST['rules'] or 'n';
	
	$query = "UPDATE users SET name='$name',rules='$rules'";
	if ($password)
		$query .= ",password='$password'";
	$query .= " WHERE user='$user'";
	mysql_query($query) or die("Invalid query: " . mysql_error());
	
	echo '<html><body onLoad="opener.location.reload(); window.close();"></body></html>';
	exit;
}

if (isset($_POST['snr'])) {
	//echo '<pre>'; print_r($_POST);
	$user = $_POST['user'];
	$password = $_POST['password'];
	@$name = $_POST['name'] or '';
	@$rules = $_POST['rules'] or 'n';
	
	if (strlen($user) and strlen($password)) {
		$query = "INSERT users VALUES ('$user','$password','$name','$rules')";
		mysql_query($query) or die("Invalid query: " . mysql_error());
	}
	
	echo '<html><body onLoad="opener.location.reload(); window.close();"></body></html>';
	exit;
}

$r = array('n','r','w','a');

if ($user) {
	$result = mysql_query("SELECT user,name,rules FROM users WHERE user='$user'");
	if (mysql_num_rows($result) != 1)
		die('error');
	$result = mysql_fetch_assoc($result);
	extract($result);
} else {
	$user = '';
	$password = '';
	$name = '';
	$rules = 'n';
}
		


?>

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
</head>
<body>
<form method="post" action="<? echo $_SERVER['PHP_SELF'] ?>">

<table border>
<tr>
	<td>
		Логiн
	</td>
	<td>
		<input type="text" name="user" value="<? echo $user ?>" <? if ($user) echo 'readonly' ?>>
	</td>
</tr>
<tr>
	<td>
		П.I.Б.
	</td>
	<td>
		<input type="text" name="name" value="<? echo $name ?>">
	</td>
</tr>
<tr>
	<td>
		Пароль
	</td>
	<td>
		<input type="text" name="password">
	</td>
</tr>
<tr>
	<td>
		Права
	</td>
	<td>
		<select name="rules">
		<?
			foreach ($r as $v) {
				echo '<option value="'.$v.'"';
				if ($v == $rules)
					echo ' selected';
				echo '>'.$v.'</option>';
			}
			
		?>
		</select>
	</td>
</tr>

<tr>
	<td colspan="2">
	<? if ($user) { ?>
		<input type="submit" name="sr" value="Записати змiни">
	<? } else { ?>
		<input type="submit" name="snr" value="Записати">
	<? } ?>
		
	</td>
</td>

</table>

</form>
</body>
</html>

<?
db_disconnect();
?>
