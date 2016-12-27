<?

require("lib.php");


//соединение с бд
db_connect();

//проверка пользователя
$rules = getrules();
if ($rules == FALSE || $rules == 'n') {
	db_disconnect();
	echo '<html><body onLoad="window.close()"></body></html>';
	exit;
}



if ($rules != 'r') {
	if (isset($_POST['snr'])) {
		@$exitnum = htmlspecialchars($_POST['exitnum']) or die('exitnum not set!');
		@$regdocdate = $_POST['regdocdate'] or die('regdocdate not set!');
		@$docdate = $_POST['docdate'] or '';
		@$fromorg = htmlspecialchars($_POST['fromorg']) or die('fromorg not set!');
		@$controldocdate = $_POST['controldocdate'] or die('controldocdate not set!');
		@$controlcheck = htmlspecialchars($_POST['controlcheck']) or '';
		@$ispolnitel = htmlspecialchars($_POST['ispolnitel']) or die('ispolnitel not set!');
		@$about = htmlspecialchars($_POST['about']) or die('about not set!');
		@$comments = htmlspecialchars($_POST['comments']) or '';
		isset($_POST['uporgflag']) or die('uporgflag not set!');
		@$uporgflag = ((int)$_POST['uporgflag']);
		
		$query = "INSERT joureg SET exitnum='$exitnum',regdocdate='$regdocdate',docdate='$docdate',fromorg='$fromorg',controldocdate='$controldocdate',controlcheck='$controlcheck',ispolnitel='$ispolnitel',about='$about',comments='$comments',uporgflag=$uporgflag";
		$res = mysql_query($query);
		
		db_disconnect();
		
			//header('Location: '.$_SERVER['PHP_SELF']);
		
		echo '<html><body onLoad="opener.location.reload(); window.close();"></body></html>';
		exit;
	}
	
	if (isset($_POST['sr']))
	{
		@$regnum = ((int)$_POST['regnum']) or die('regnum not set!');
		@$exitnum = htmlspecialchars($_POST['exitnum']) or die('exitnum not set!');
		@$regdocdate = $_POST['regdocdate'] or die('regdocdate not set!');
		@$docdate = $_POST['docdate'] or '';
		@$fromorg = htmlspecialchars($_POST['fromorg']) or die('fromorg not set!');
		@$controldocdate = $_POST['controldocdate'] or die('controldocdate not set!');
		@$controlcheck = htmlspecialchars($_POST['controlcheck']) or '';
		@$ispolnitel = htmlspecialchars($_POST['ispolnitel']) or die('ispolnitel not set!');
		@$about = htmlspecialchars($_POST['about']) or die('about not set!');
		@$comments = htmlspecialchars($_POST['comments']) or '';
		isset($_POST['uporgflag']) or die('uporgflag not set!');
		@$uporgflag = ((int)$_POST['uporgflag']);
		
		$query = "UPDATE joureg SET exitnum='$exitnum',regdocdate='$regdocdate',docdate='$docdate',fromorg='$fromorg',controldocdate='$controldocdate',controlcheck='$controlcheck',ispolnitel='$ispolnitel',about='$about',comments='$comments',uporgflag=$uporgflag WHERE regnum=$regnum";
		$res = mysql_query($query);
		
		db_disconnect();
			//header('Location: '.$_SERVER['PHP_SELF']);
		echo '<html><body onLoad="opener.location.reload(); window.close();"></body></html>';
		exit;
	}
}

@$id = (int)$_GET['id'] or null;


if ($id) {
	$query = 'SELECT * FROM joureg WHERE regnum='.$id;
	$result = mysql_query($query) or die("Invalid query: " . mysql_error());
	$result = mysql_fetch_assoc($result);
	extract($result);
} else {
	$exitnum = '';
	$regdocdate = '';
	$docdate = '';
	$fromorg = '';
	$controldocdate = '';
	$controlcheck = '';
	$ispolnitel = '';
	$about = '';
	$comments = '';
	$uporgflag = '';
}




echo '<html><head>
<script language="JavaScript">
var check = false;
function checkform()
{
	var error = \'\';
	if (document.getElementById(\'exitnum\').value == 0)
		error += \'"Вихiдний номер" не заповнено\n\';
	
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
		error += \'"Вихiдна дата" не вiрна\n\';
	
	ddate = document.getElementById(\'docdate\').value;
	ddate = ddate.split(/\D/);
	if (ddate[1]<10 )
		ddate[1] = ddate[1].substr(1,1);
	if (ddate[2]<10 )
		ddate[2] = ddate[2].substr(1,1);
	ddate = ddate[1] + \'/\' + ddate[2] + \'/\' + ddate[0];
	tmpdate = Date.parse(ddate);
	tmpdate = new Date(tmpdate);
	tmpdate = (tmpdate.getMonth() + 1) + "/" + tmpdate.getDate() + "/" + tmpdate.getFullYear();
	if (ddate != tmpdate)
		error += \'"Дата" не вiрна\n\';
	

	cdate = document.getElementById(\'controldocdate\').value;
	cdate = cdate.split(/\D/);
	if (cdate[1]<10 )
		cdate[1] = cdate[1].substr(1,1);
	if (cdate[2]<10 )
		cdate[2] = cdate[2].substr(1,1);
	cdate = cdate[1] + \'/\' + cdate[2] + \'/\' + cdate[0];
	tmpdate = Date.parse(cdate);
	tmpdate = new Date(tmpdate);
	tmpdate = (tmpdate.getMonth() + 1) + "/" + tmpdate.getDate() + "/" + tmpdate.getFullYear();
	if (cdate != tmpdate)
		error += \'"Вiдправити до" не вiрно\n\';
	
	if (document.getElementById(\'fromorg\').value == 0)
		error += \'"Вiд кого" не заповнено\n\';
	if (document.getElementById(\'ispolnitel\').value == 0)
		error += \'"Виконавець" не заповнено\n\';
	if (document.getElementById(\'about\').value == 0)
		error += \'"Тема листа" не заповнено\n\';
	
	if (error.length > 0)
		alert(error);
	else
		check = true;
}

</script>
<link href="style.css" type="text/css" rel="stylesheet">
<META http-equiv=Content-Type content="text/html; charset=windows-1251"></head><body><form method="post" action="'.$_SERVER['PHP_SELF'].'" onsubmit="checkform(); return check;">';
echo '<input type="hidden" name="regnum" value="'.@$regnum.'">';
echo '<table border>';
//echo '<tr><td>regnum: </td><td><input type="text" name="regnum" value="'.$result['regnum'].'"></td></tr>';
echo '<tr><td>Вих.номер: </td><td><input type="text" name="exitnum" id="exitnum" value="'.$exitnum.'" maxlenght=20></td></tr>';
echo '<tr><td>Вих.дата: </td><td><input type="text" name="regdocdate" id="regdocdate" value="';
	if ($id)
		echo $regdocdate;
	else	
		echo date('Y-m-d');
	echo '"></td></tr>';
echo '<tr><td>Дата: </td><td><input type="text" name="docdate" id="docdate" value="';
	if ($id)
		echo $docdate;
	else
		echo date('Y-m-d');
	echo '"></td></tr>';
echo '<tr><td>Вiд кого: </td><td><input type="text" name="fromorg" id="fromorg" value="'.$fromorg.'"></td></tr>';
echo '<tr><td>Вiдправити до: </td><td><input type="text" name="controldocdate" id="controldocdate" value="';
	if ($id)
		echo $controldocdate;
	else
		echo date('Y-m-d');
	echo'"></td></tr>';
echo '<tr><td>Перевiрка: </td><td><input type="text" name="controlcheck" id="controlcheck" value="'.$controlcheck.'"></td></tr>';
echo '<tr><td>Виконавець: </td><td><input type="text" name="ispolnitel" id="ispolnitel" value="'.$ispolnitel.'"></td></tr>';
echo '<tr><td>Тема листа: </td><td><input type="text" name="about" id="about" value="'.$about.'"></td></tr>';
echo '<tr><td>Коментарi: </td><td><input type="text" name="comments" id="comments" value="'.$comments.'"></td></tr>';
echo '<tr><td>Вища органiзацiя : </td><td><input type="hidden" name="uporgflag" value="0"><input type="checkbox" id="uporgflag" name="uporgflag" value="1"';
	if ($id && $uporgflag)
		echo ' checked';
	echo '></td></tr>';
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
