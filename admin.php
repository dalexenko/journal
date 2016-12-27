<?

require("lib.php");


if (isset($_POST['exit'])) {
	setcookie('u','');
	setcookie('p','');
	setcookie('t','');
	header('Location: '.$_SERVER['PHP_SELF']);
}

//соединение с бд
db_connect();

//проверка пользователя
$rules = getrules();

	
if (@$rules != 'a') {
	db_disconnect();
	echo pform;
	exit;
}

if (isset($_GET['del'])) {
	$user = $_GET['del'];
	if (strlen($user))
		mysql_query("DELETE FROM users WHERE user='$user' LIMIT 1");
	header('Location: '.$_SERVER['PHP_SELF']);
}

$result = mysql_query('SELECT user,name,rules FROM users');

echo '<html><head><meta http-equiv="Content-Type" content="text/html; charset=windows-1251"><link href="style.css" type="text/css" rel="stylesheet"></head><body><form method="post" action="'.$_SERVER['PHP_SELF'].'">';
echo '<table border>';

while ($res = mysql_fetch_row($result)) {
	echo '<tr>';
	foreach ($res as $v) {
		echo '<td><a onClick="window.open(\'user.php?user='.$res[0].'\')">';
		if (strlen($v))
			echo $v;
		else
			echo '&nbsp;';
		echo '</a></td>';
	}
	echo '<td><a href="'.$_SERVER['PHP_SELF'].'?del='.$res[0].'">del</a></td></tr>';
}

echo '</table>';

//echo '<a onClick="window.open(\'user.php\')">new</a>';
echo '<button onClick="window.open(\'user.php\',\'\',\'width=240,height=180\')">Новий користувач</button>';
echo '<input type="submit" name="exit" value="Вихiд">';
echo '</body></html>';
db_disconnect();

?>
