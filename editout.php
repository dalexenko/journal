<?


require("lib.php");


//соединение с бд
db_connect();

//проверка пользователя
$rules = getrules();
if ($rules == FALSE or $rules == 'n') {
	db_disconnect();
	echo '<html><body onLoad="window.close()"></body></html>';
	exit;
}

if ($rules != 'r') {
	if (isset($_POST['snr'])) {
		@$regdocdate = $_POST['regdocdate'] or die('regdocdate not set!');
		@$docnum = htmlspecialchars($_POST['docnum']) or '';
		@$regnum = ((int)$_POST['regnum']) or die('regnum not set!');
		@$ispolnitel = htmlspecialchars($_POST['ispolnitel']) or die('ispolnitel not set!');
		@$comments = htmlspecialchars($_POST['comments']) or '';
		
		$query = "INSERT jouregout SET regdocdate='$regdocdate',docnum='$docnum',regnum='$regnum',ispolnitel='$ispolnitel',comments='$comments'" ;
		$res = mysql_query($query);
		
		db_disconnect();
		
			//header('Location: '.$_SERVER['PHP_SELF']);
		echo '<html><body onLoad="opener.location.reload(); window.close();"></body></html>';
		exit;
	}
	
	if (isset($_POST['sr']))
	{
		
		@$exitnum = ((int)$_POST['exitnum']) or die('exitnum not set!');
		@$regdocdate = $_POST['regdocdate'] or die('regdocdate not set!');
		@$docnum = htmlspecialchars($_POST['docnum']) or '';
		@$regnum = ((int)$_POST['regnum']) or die('regnum not set!');
		@$ispolnitel = htmlspecialchars($_POST['ispolnitel']) or die('ispolnitel not set!');
		@$comments = htmlspecialchars($_POST['comments']) or '';
		//isset($_POST['uporgflag']) or die('uporgflag not set!');
		// @$uporgflag = ((int)$_POST['uporgflag']);
		
		//$query = "UPDATE jouregout SET regdocdate='$regdocdate',docnum='$docnum',regnum='$regnum',ispolnitel='$ispolnitel',comments='$comments',uporgflag=$uporgflag WHERE exitnum=$exitnum" ;
		$query = "UPDATE jouregout SET regdocdate='$regdocdate',docnum='$docnum',regnum='$regnum',ispolnitel='$ispolnitel',comments='$comments' WHERE exitnum=$exitnum" ;
		$res = mysql_query($query);
		
		
		db_disconnect();
			//header('Location: '.$_SERVER['PHP_SELF']);
		echo '<html><body onLoad="opener.location.reload(); window.close();"></body></html>';
		exit;
	}
}

@$id = (int)$_GET['id'] or null;
@$in = (int)$_GET['in'] or null;
if (!$id and !$in) exit;
if ($id and $in) exit;
 
if ($id) {
	$query = 'SELECT * FROM jouregout WHERE exitnum='.$id;
	$result = mysql_query($query) or die("Invalid query: " . mysql_error());
	$result = mysql_fetch_assoc($result);
	extract($result);
} else {
	$exitnum = '';
	$regdocdate = '';
	$docnum = '';
	$regnum = '';
	$ispolnitel = '';
	$comments = '';
}

echo '<html><head>
<script language="JavaScript">
var check = false;
function checkform()
{
	var error = \'\';
	odate = document.getElementById(\'regdocdate\').value;
	odate = odate.split(/\D/);
	if (odate[1]<10 )
		odate[1] = odate[1].substr(1,1);
	if (odate[2]<10 )
		odate[2] = odate[2].substr(1,1);
	odate = odate[1] + \'/\' + odate[2] + \'/\' + odate[0];
	tmpdate = Date.parse(odate);
	tmpdate = new Date(tmpdate);
	tmpdate = (tmpdate.getMonth() + 1) + "/" + tmpdate.getDate() + "/" + tmpdate.getFullYear();
	if (odate != tmpdate)
		error += \'"Дата" не вiрна\n\';
	
	if (document.getElementById(\'docnum\').value == 0)
		error += \'"Номер документа" не заповнено\n\';
	
	if (document.getElementById(\'ispolnitel\').value == 0)
		error += \'"Виконавець" не заповнено\n\';
	
	if (error.length > 0)
		alert(error);
	else
		check = true;
}
</script>
<link href="style.css" type="text/css" rel="stylesheet">
<META http-equiv=Content-Type content="text/html; charset=windows-1251"></head><body><form method="post" action="'.$_SERVER['PHP_SELF'].'" onsubmit="checkform(); return check;">';
echo '<input type="hidden" name="regnum" value="';
	if ($in)
		echo $in;
	else 
		echo $regnum;
	echo '">';

echo '<table border>';

if (!$in)
	echo '<tr><td>Вих.номер: </td><td><input type="text" name="exitnum" id="exitnum" value="'.$exitnum.'"readonly></td></tr>';
echo '<tr><td>Дата: </td><td><input type="text" id="regdocdate" name="regdocdate" value="';
	if ($id)
		echo $regdocdate;
	else
		echo date('Y-m-d');
	echo '"></td></tr>';
echo '<tr><td>Номер документа: </td><td><input type="text" id="docnum" name="docnum" value="'.$docnum.'" maxlenght="20"></td></tr>';
echo '<tr><td>Виконавець: </td><td><input type="text" id="ispolnitel" name="ispolnitel" value="'.$ispolnitel.'"></td></tr>';
echo '<tr><td>Коментарi: </td><td><input type="text" id="comments" name="comments" value="'.$comments.'"></td></tr>';

echo '<tr><td></td><td>';
if ($id) {
	if ($rules == 'r')
		echo '<input type="button" style="color:grey;" value="Записати змiни">';
	else
		echo '<input type="submit" name="sr" value="Записати змiни">';
}
else
	echo '<input type="submit" name="snr" value="Записати">';
	
echo '<input type="button" value="Закрити" onClick="window.close()"></td></tr>';
echo '</table></form></body><html>';




db_disconnect();
?>
