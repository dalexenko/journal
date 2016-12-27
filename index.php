<?

session_start();

//echo '<pre>'; print_r($_POST);

//конф файл

//define('shortlen',10);

//echo '<pre>'; print_r($_POST);

require("lib.php");

define('rpp',5);

//выход из системы

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

	
if ($rules == false) {
	db_disconnect();
	echo pform;
	exit;
} else if ($rules == 'n') {
	exit;
}

//удаление записей

if (isset($_POST['delin']) && ($rules='a' or $rules='w')) {
	$id = (int)key($_POST['delin']);
	if ($id) {
		$query = 'DELETE FROM jouregout WHERE regnum="'.$id.'" LIMIT 1';
		$result = mysql_query($query) or die("Invalid query: " . mysql_error());
		$query = 'DELETE FROM joureg WHERE regnum="'.$id.'" LIMIT 1';
		$result = mysql_query($query) or die("Invalid query: " . mysql_error());
		
	}
}

if (isset($_POST['delout']) && ($rules='a' or $rules='w')) {
	$id = (int)key($_POST['delout']);
	if ($id) {
		$query = 'DELETE FROM jouregout WHERE regnum="'.$id.'" LIMIT 1';
		$result = mysql_query($query) or die("Invalid query: " . mysql_error());

	}
}


//установка переменных

$mval = array('in_ly','in_lm','cur','in_nm','in_ny','out_ly','out_lm');
@$newmode1 = key($_POST['setmode']) or null; 

@$newmode2 = $_SESSION['mode'] or null;

if (isset($_SESSION['mode']))
	$oldmode = $_SESSION['mode'];
else
	$oldmode = 'cur';

if ($newmode1 && array_search($newmode1,$mval) !== FALSE)
	$mode = $newmode1;
else if ($newmode2 && array_search($newmode2,$mval) !== FALSE)
	$mode = $newmode2;
else
	$mode = 'cur';


if (isset($_POST['chdir']))
	$dir = key($_POST['chdir']);
else if (isset($_SESSION['dir']))
	$dir = $_SESSION['dir'];
else 
	$dir = 0;
	
if (isset($_GET['p']))
	$p = (int)$_GET['p'];
else if (isset($_SESSION['p']))
	$p = $_SESSION['p'];
else
	$p = 1;
	
if ($mode != $oldmode) {
	$p = 1;
}

$start = ($p-1)*rpp;

$in = array('regnum','exitnum','regdocdate','docdate','fromorg','controldocdate','controlcheck','ispolnitel','about','comments');
$namein = array('Вх.номер','Вих.номер','Вих.дата','Дата','Вiд кого','Вiдп.до','Перевiрка','Виконавець','Тема листа','Коментарi');
$out = array('exitnum','regdocdate','docnum','ispolnitel','comments');
$nameout = array('Вих.номер','Дата','Номер документа','Виконавець','Коментарi');
$shortareain = array('exitnum'=>15,'ispolnitel'=>10,'fromorg'=>15,'about'=>10,'comments'=>10, 'controlcheck'=>10);
$shortareaout = array('ispolnitel'=>35,'comments'=>35);

@$order_in1 = key($_POST['chorder_in']) or null;
@$order_in2 = $_SESSION['order_in'] or null;

if ($order_in1 && array_search($order_in1,$in) !== FALSE)
	$order_in = $order_in1;
 else if ($order_in2 && array_search($order_in2,$in) !== FALSE)
	$order_in = $order_in2;
else
	$order_in = $in[0];
	
	
@$order_out1 = key($_POST['chorder_out']) or null;
@$order_out2 = $_SESSION['order_out'] or null;

if ($order_out1 && array_search($order_out1,$out) !== FALSE)
	$order_out = $order_out1;
else if ($order_out2 && array_search($order_out2,$out) !== FALSE)
	$order_out = $order_out2;
else
	$order_out = $out[0];
if (isset($_POST['find']))
	$find = htmlspecialchars($_POST['find']);
else if (isset($_SESSION['find']))
	$find = htmlspecialchars($_SESSION['find']);
else
	$find = '';

if (substr($mode,0,3) !== 'out') { //in
	if ( isset($_SESSION['order_in']) && $_SESSION['order_in'] != $order_in)
		$dir = 0;
} else { //out
	if ( isset($_SESSION['order_out']) && $_SESSION['order_out'] != $order_out)
		$dir = 0;
}


$_SESSION['mode'] = $mode;
$_SESSION['order_in'] = $order_in;
$_SESSION['order_out'] = $order_out;
$_SESSION['dir'] = $dir;
$_SESSION['p'] = $p;
$_SESSION['find'] = $find;

if ($mode == 'out_ly' or $mode == 'out_lm') {
	$f = 'out';
	$fn = &$nameout;
} else { 
	$f = 'in';
	$fn = &$namein;
}

foreach($$f as $k => $v) {
	if ($v == 'uporgflag') continue;
	
	
	//echo '<br><input type="checkbox" name="f_'.$v.'"';
	if (isset($_POST['f_'.$v]))
		$_SESSION['f_'.$v] = 1;
		//echo ' checked';
	if (!isset($_POST['f_'.$v]) and isset($_SESSION['f_'.$v]) and isset($_POST['find']))
		unset($_SESSION['f_'.$v]);
	//echo '>'.$fn[$k];
}

echo '<html><head><link href="tooltip.css" type="text/css" rel="stylesheet"><script language="JavaScript" src="tooltip.js"></script><meta http-equiv="Content-Type" content="text/html; charset=windows-1251"><link href="style.css" type="text/css" rel="stylesheet"></head><body><form method="post" action="'.$_SERVER['PHP_SELF'].'">';

// echo '<input type="hidden" name="mode" value="'.$mode.'">';
// echo '<input type="hidden" name="order_in" value="'.$order_in.'">';
// echo '<input type="hidden" name="order_out" value="'.$order_out.'">';
// echo '<input type="hidden" name="dir" value="'.$dir.'">';


echo '<table border class="inft">';

//шапка таблицы исходящие

if ($mode == 'out_ly' or $mode == 'out_lm') {
	echo '<tr><colgroup><colgroup width=80><colgroup><colgroup><colgroup></tr>';
	echo "<tr align='center'>\n";
	foreach($out as $k => $v) {
		echo '<td>'.$nameout[$k];
		echo '<br>';
		if ($v == $order_out) {
			if ($dir)
				echo '<input type="submit" name="chdir[0]" value="v">';
			else
				echo '<input type="submit" name="chdir[1]" value="^">';
		} else
			echo '<input type="submit" name="chorder_out['.$v.']" value="">';
			
		echo "</td>";
	}
	echo "</tr>\n";
} 

//шапка таблицы входящие
else {
	echo '<tr><colgroup><colgroup width=100><colgroup span=2 width="80"><colgroup width=120><colgroup width="80"></tr>';
	echo "<tr align='center'>\n";
	foreach($in as $k => $v) {
		echo '<td>'.$namein[$k];
		echo '<br>';
		if ($v == $order_in) {
			if ($dir)
				echo '<input type="submit" name="chdir[0]" value="v">';
			else
				echo '<input type="submit" name="chdir[1]" value="^">';
		} else
			echo '<input type="submit" name="chorder_in['.$v.']" value="">';
			
		echo '</td>';
	}
	echo "</tr>\n";
}

//подготовка запросов к бд

if ($mode == 'out_ly' or $mode == 'out_lm') {
	$query = 'SELECT t2.*,t1.uporgflag FROM joureg AS t1, jouregout AS t2 WHERE t1.regnum = t2.regnum';
	if ($mode == 'out_lm')
		$query .= ' AND TO_DAYS(NOW()) - TO_DAYS(t2.regdocdate) <= 30';
	
	$counter = 0;
	foreach($out as $k => $v) {
		if (isset($_POST['f_'.$v])) {
			if (!$counter)
				$query .= ' AND ( ';
			if ($counter)
				$query .= ' OR';
			$query .= ' t2.'.$v.' LIKE \'%'.$find.'%\'';
			$counter++;
		}
	}
	if ($counter)
		$query .= ' )';
	
	$query .= ' ORDER BY t1.uporgflag DESC,'.$order_out;
	
} else if ($mode == 'in_lm' or $mode == 'in_ly') {
	$query = 'SELECT t1.*,t2.exitnum as exitnum2 FROM joureg AS t1, jouregout AS t2 WHERE t1.regnum = t2.regnum';
	if ($mode == 'in_lm')
		$query .= ' AND TO_DAYS(NOW()) - TO_DAYS(t2.regdocdate) <= 30';
	
	
} else {
		$query = 'SELECT t1.*,t2.exitnum as exitnum2 FROM joureg AS t1 LEFT JOIN jouregout AS t2 ON t1.regnum = t2.regnum WHERE t2.regnum IS NULL';
	if ($mode == 'cur')
		 $query .=' AND t1.controldocdate <= NOW()';
	else if ($mode == 'in_nm')
		$query .= ' AND TO_DAYS(t1.controldocdate) - TO_DAYS(NOW()) <= 30 AND TO_DAYS(t1.controldocdate) - TO_DAYS(NOW()) > 0';

}


if (substr($mode,0,3) !== 'out')
{
	$counter = 0;
	foreach($in as $k => $v) {
		if (isset($_POST['f_'.$v]) or isset($_SESSION['f_'.$v])) {
			if (!$counter)
				$query .= ' AND ( ';
			if ($counter)
				$query .= ' OR';
			$query .= ' t1.'.$v.' LIKE \'%'.$find.'%\'';
			$counter++;
		}
	}
	if ($counter)
		$query .= ' )';
	$query .= ' ORDER BY t1.uporgflag DESC,'.$order_in;
}

if ($dir)
	$query .= ' DESC';

$result = mysql_query($query) or die("Invalid query: " . mysql_error());
$countrec = mysql_num_rows($result);

$query .= ' LIMIT '.$start.','.rpp;

//вывод информации

if ($mode == 'out_ly' or $mode == 'out_lm')
	$shortarea = &$shortareaout;
else
	$shortarea = &$shortareain;

//echo $query.'<br>';
	
$result = mysql_query($query) or die("Invalid query: " . mysql_error());

while ($res = mysql_fetch_assoc($result)) {
	if ($res['uporgflag'])
		echo '<tr class="uporg">';
	else
		echo '<tr class="downorg">';
	
	
	foreach($res as $k => $v) {
		if ($k == 'regnum' and ($mode == 'out_ly' or $mode == 'out_lm')) continue;
		if ($k == 'uporgflag') continue;
		if ($k == 'exitnum2') continue;
		
		if ((($mode == 'out_ly' or $mode == 'out_lm') and $order_out == $k) or ($order_in == $k and ($mode != 'out_ly' and $mode != 'out_lm'))) {
			echo '<td class="order">';
		} else		
			echo '<td>';
			
		if (array_key_exists($k,$shortarea) !== FALSE) {
			echo '<a title="'.htmlspecialchars($v).'">'.htmlspecialchars(substr($v,0,$shortarea[$k])).'</a>';
		} else
			echo htmlspecialchars($v);
			
		echo '</td>';
	}
	
	
	
	echo '<td>';
	if ($mode == 'out_ly' or $mode == 'out_lm') {
		
		echo '<button value="Редагування" ';
		if ($rules == 'r')
			echo 'style="color:grey;">';
		else
			echo 'onClick="window.open(\'editout.php?id='.$res['exitnum'].'\',\'\',\'width=350,height=220\')">';
		echo '<img src="b_edit.png"></button>';
		echo '</td><td>';
		echo '<button value="Вхiдне" onClick="window.open(\'editin.php?id='.$res['regnum'].'\',\'\',\'width=350,height=380\')"><img src="b_tblimport.png"></button>';
		if ($rules == 'a' or $rules == 'w') {
			echo '</td><td>';
			if (strpos($_SERVER['HTTP_USER_AGENT'],'MSIE') === false)
				echo '<button type=submit value="Видалити" name=delout['.$res['regnum'].'] onclick="if (!confirm(\'Ви впевненi?\')) return false;"><img src="b_drop.png"></button>';
			else
				echo '<input type=image value="Видалити" name=delout['.$res['regnum'].'] onclick="if (!confirm(\'Ви впевненi?\')) return false;" src="b_drop.png">';
		}
	} else {
			echo '<button value="Редагування" ';
		if ($rules == 'r')
			echo 'style="color:grey;">';
		else
			echo 'onClick="window.open(\'editin.php?id='.$res['regnum'].'\',\'\',\'width=350,height=380\')">';
		echo '<img src="b_edit.png"></button>';
		echo '</td><td>';
		if (substr($mode,0,4) == 'in_l') 
			echo '<button value="Вхiдне" onClick="window.open(\'editout.php?id='.$res['exitnum2'].'\',\'\',\'width=350,height=220\')"><img src="b_tblexport.png"></button>';
		else {
				if ($rules == 'a' or $rules == 'w')
					echo '<button value="Вiдповiдь" onClick="window.open(\'editout.php?in='.$res['regnum'].'\',\'\',\'width=350,height=170\')"><img src="b_comment.png"></button>';
				else
					echo '<button value="Вiдповiдь"><img src="b_comment.png"></button>';
		}
		if ($rules == 'a' or $rules == 'w') {
			echo '</td><td>';
			if (strpos($_SERVER['HTTP_USER_AGENT'],'MSIE') === false)
				echo '<button type=submit value="Видалити" name=delin['.$res['regnum'].'] onclick="if (!confirm(\'Ви впевненi?\')) return false;"><img src="b_drop.png"></button>';
			else
				echo '<input type=image value="Видалити" name=delin['.$res['regnum'].'] onclick="if (!confirm(\'Ви впевненi?\')) return false;" src="b_drop.png">';
		}
		
	}
	
	echo '</td></tr>';
}
//=================
 	echo '</table>';
//навигационная панель

//echo '<br>';
echo '<div align="center">';
$pages = ceil($countrec/rpp);

for ($i=1; $i<=$pages; $i++)
	if ($i == $p)
		echo $i.'&nbsp;';
	else
		echo '<a href="' . $_SERVER['PHP_SELF'] . '?p=' . $i . '">' . $i . '</a>&nbsp;';

echo '</div>';
//echo '<br>';

echo '<table><tr><td>';

echo '<table>';
echo '<tr>';

echo '<td>Вхiднi </td><td><input type=';
if ($mode == 'in_ly')
	echo '"button" style="color: white"';
else
	echo '"submit"';
echo ' name="setmode[in_ly]" value="Усi"></td>';

echo '<td><input type=';
if ($mode == 'in_lm')
	echo '"button" style="color: white"';
else
	echo '"submit"';
echo ' name="setmode[in_lm]" value="30 днiв"></td>';

echo '<td rowspan=2><input type=';
if ($mode == 'cur')
	echo '"button" style="color: white"';
else
	echo '"submit"';
echo ' name="setmode[cur]" value="Сьогоднi"></td>';


echo '<td rowspan=2><input type=';
if ($mode == 'in_nm')
	echo '"button" style="color: white"';
else
	echo '"submit"';
echo ' name="setmode[in_nm]" value="30 днiв"></td>';

echo '<td rowspan=2><input type=';
if ($mode == 'in_ny')
	echo '"button" style="color: white"';
else
	echo '"submit"';
echo ' name="setmode[in_ny]" value="Усi"></td>';

echo '<td rowspan=2> Без вiдповiдi</td>';
echo '</tr><tr>';
echo '<td>Вихiднi </td><td><input type=';
if ($mode == 'out_ly')
	echo '"button" style="color: white"';
else
	echo '"submit"';
echo ' name="setmode[out_ly]" value="Усi"></td>';

echo '<td><input type=';
if ($mode == 'out_lm')
	echo '"button" style="color: white"';
else
	echo '"submit"';
echo ' name="setmode[out_lm]" value="30 днiв"></td>';
echo '</tr>';
echo '</table>';

echo '</td><td width="200" align="right">';

if ($mode == 'cur') {
	echo '<input type="button" value="Новий лист" ';
	if ($rules == 'r')
		echo 'style="color:white;">';
	else
		echo 'onClick="window.open(\'/editin.php\',\'\',\'width=350,height=370\')">';
}

echo '</td><td width="200" align="right">';
	echo '<input type="submit" name="exit" value="Вихiд">';

echo '</td></tr></table>';


if ($mode == 'out_ly' or $mode == 'out_lm') {
	$f = 'out';
	$fn = &$nameout;
} else { 
	$f = 'in';
	$fn = &$namein;
}

foreach($$f as $k => $v) {
	if ($v == 'uporgflag') continue;
	
	
	echo '<br><input type="checkbox" name="f_'.$v.'"';
	if (isset($_POST['f_'.$v]) or isset($_SESSION['f_'.$v]))
		echo ' checked';
	echo '>'.$fn[$k];
}


echo '<br><input type="text" name="find" value="'.$find.'"><button type="submit" value="Пошук"><img src="b_search.png"></button>';
echo '</form></body></html>';
	
db_disconnect();

