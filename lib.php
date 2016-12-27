<?
//lib

// глобальные переменные
$table = "users"; //таблица с паролем и логином
$database = "journal"; //имя базы данных
$dbhost = "localhost"; //хост базы данных
$dblogin = "root"; //логин для базы данных
$dbpassw = ""; //пароль для базы данных


define('pform','<html><head><meta http-equiv="Content-Type" content="text/html; charset=windows-1251"></head><body><form method="post"><table align="center"><tr><td>Логiн</td><td><input type="text" name="user" maxleght="30"></td></tr><tr><td>Пароль</td><td><input name="pass" type="password"></td></tr><tr><td colspan=2><input type="submit" value="Вхiд"></td></tr></table></form></body></html>');

function db_connect()
{
	global $table, $login, $passw, $database, $dbhost,$dblogin, $dbpassw; // глобальные преременные, описаны в var_auth.inc.php
  mysql_connect($dbhost, $dblogin, $dbpassw) or die ("Could not connect $dbhost");
  mysql_select_db ($database) or die ("Could not select database $database");
	mysql_query('set chracter set cp1251');
}

function db_disconnect()
{
  mysql_close();
}


function getrules() {

	if (isset($_COOKIE['u']) and isset($_COOKIE['p']) and isset($_COOKIE['t'])) {
	
		$safe_user = strtr(addslashes($_COOKIE['u']),array('_' => '\_', '%' => '\%'));
		$t = (int) $_COOKIE['t'];
		$r = mysql_query("SELECT password,rules FROM users WHERE user LIKE '$safe_user'");
		if (mysql_num_rows($r) == 1) {
			$res = mysql_fetch_row($r);
			$hash = md5($safe_user.$res[0].$_SERVER['REMOTE_ADDR'].$t);
			if ($hash == $_COOKIE['p']) {
				$curtime = time();
				if (($curtime-$t) > 15*60) {
					setcookie('t','');
					return false;
				}
				$newhash = md5($safe_user.$res[0].$_SERVER['REMOTE_ADDR'].$curtime);
				setcookie('p',$newhash);
				setcookie('t',$curtime);
				return $res[1];
			}
		}
	}
	
	if (isset($_POST['user']) and isset($_POST['pass'])) {
		$safe_user = strtr(addslashes($_POST['user']),array('_' => '\_', '%' => '\%'));
		$r = mysql_query("SELECT password,rules FROM users WHERE user LIKE '$safe_user'");
		if (mysql_num_rows($r) == 1) {
			$res = mysql_fetch_row($r);
			//var_dump($res); exit;
			if ($res[0] !== md5($_POST['pass'])) return false;
			$newtime = time();
			$hash = md5($safe_user.$res[0].$_SERVER['REMOTE_ADDR'].$newtime);
			setcookie('u',$safe_user);
			setcookie('p',$hash);
			setcookie('t',$newtime);
			//return $res[1];
			header('Location: '.$_SERVER['PHP_SELF']);
		}
				
			 
	}
	
	return false;
}

?>
