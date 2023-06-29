<?php
$suc = '';
$er_name = '#ccc';
$er_phone = '#ccc';
$dir_uplode = 'files';
$logs = 'logs';
$ser_add = $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['SERVER_NAME'];
$to = 'admin@yemorkovin.ru';

if(isset($_POST['add_sub'])){
	$name = $_POST['firstname'];
	$phone = $_POST['phone'];
	$spisok = $_POST['spisok'];
	$message = $_POST['message'];
	if(empty($phone)){
		$er_phone = '#f00';
	}
	if(empty($name)){
		$er_name = '#f00';
	}
	if(!empty($phone) && !empty($name)){
		if ($_FILES && $_FILES["file"]["error"]== UPLOAD_ERR_OK)
		{
			$name_file = $_FILES["file"]["name"];
			$date_now = date('Y_m_d-H_m_s');
			
			$ext = end(explode('.', $name_file));
			if(!is_dir($dir_uplode)){
			    mkdir($dir_uplode);
			}
			move_uploaded_file($_FILES["file"]["tmp_name"], $dir_uplode.'/'.$date_now.'.'.$ext);
			$link = $ser_add.'/'.$dir_uplode.'/'.$date_now.'.'.$ext;
			$str_log = 'Время: '.date('d-m-Y h:m:s')."\n";
			$str_log .= 'Имя: '.$name."\n";
			$str_log .= 'Телефон: '.$phone."\n";
			$str_log .= 'Регион: '.$spisok."\n";
			$str_log .= 'Ссылка на файл : '.$link."\n";
			$str_log .= "__________________________________\n";
			
			$str_html = 'Время: '.date('d-m-Y h:m:s')."<br />";
			$str_html .= 'Имя: '.$name."<br />";
			$str_html .= 'Телефон: '.$phone."<br />";
			$str_html .= 'Регион: '.$spisok."<br />";
			$str_html .= '<a href="'.$link.'" >Ссылка на файл</a>';
			
			file_put_contents('log.txt', $str_log, FILE_APPEND);
				$subject = "Отправка на почту";
				$charset = "utf-8";
				$headerss ="Content-type: text/html; charset=$charset\r\n";
				$headerss.="MIME-Version: 1.0\r\n";
				$headerss.="Date: ".date('D, d M Y h:i:s O')."\r\n";
				$msg = $str_html;
				mail($to, $subject, $msg, $headerss);
			$suc = 'Ваша заявка принята';
			
		}
	}
}


?>
<!DOCTYPE html>
<html>
<head>
<style>
* {
    box-sizing: border-box;
}

#fname{
    width: 100%;
    padding: 12px;
    border: 1px solid <?=$er_name?>;
    border-radius: 4px;
    resize: vertical;
}
#phone{
    width: 100%;
    padding: 12px;
    border: 1px solid <?=$er_phone?>;
    border-radius: 4px;
    resize: vertical;
}
 select, textarea {
    width: 100%;
    padding: 12px;
    border: 1px solid #ccc;
    border-radius: 4px;
    resize: vertical;
}

label {
    padding: 12px 12px 12px 0;
    display: inline-block;
}

input[type=submit] {
    background-color: #4CAF50;
    color: white;
    padding: 12px 20px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    float: right;
}

input[type=submit]:hover {
    background-color: #45a049;
}

.container {
    border-radius: 5px;
    background-color: #f2f2f2;
    padding: 20px;
}

.col-25 {
    float: left;
    width: 25%;
    margin-top: 6px;
}

.col-75 {
    float: left;
    width: 75%;
    margin-top: 6px;
}

/* Clear floats after the columns */
.row:after {
    content: "";
    display: table;
    clear: both;
}

/* Responsive layout - when the screen is less than 600px wide, make the two columns stack on top of each other instead of next to each other */
@media screen and (max-width: 600px) {
    .col-25, .col-75, input[type=submit] {
        width: 100%;
        margin-top: 0;
    }
}
</style>
</head>
<body>
<?php if (empty($suc)) {?>
<h2>Форма</h2>
<div class="container">
  <form action="" method="post" enctype="multipart/form-data">
    <div class="row">
      <div class="col-25">
        <label for="fname">Имя:</label>
      </div>
      <div class="col-75">
        <input type="text" onblur="changename()" id="fname" name="firstname" placeholder="Ваше имя...">
      </div>
    </div>
    <div class="row">
      <div class="col-25">
        <label for="phone">Телефон:</label>
      </div>
      <div class="col-75">
        <input type="number" onblur="changephone()" id="phone" name="phone" placeholder="Ваш телефон...">
      </div>
    </div>
    <div class="row">
      <div class="col-25">
        <label for="spisok">Выпадающий список:</label>
      </div>
      <div class="col-75">
        <select id="spisok" name="spisok">
          <option value="1">1</option>
          <option value="2">2</option>
          <option value="3">3</option>
        </select>
      </div>
    </div>
	 <div class="row">
      <div class="col-25">
        <label for="file">Файл:</label>
      </div>
      <div class="col-75">
		<input type="file" id="uploadimage" name="file">
      </div>
    </div>
    <div class="row">
      <div class="col-25">
        <label for="message">Сообщение</label>
      </div>
      <div class="col-75">
        <textarea id="message" name="message" placeholder="Ваше сообщение.." style="height:200px"></textarea>
      </div>
    </div>
    <div class="row">
      <input type="submit" value="Отправить" name="add_sub">
    </div>
  </form>
</div>
<?php }else {?>
<h2 align="center"><?=$suc?></h2>
<?php } ?>
<script>
function changename(){
	if(document.getElementById('fname').value == ''){
		document.getElementById('fname').style.borderColor = 'red';
	}else{
		document.getElementById('fname').style.borderColor = '#ccc';
	}
}
function changephone(){
	if(document.getElementById('phone').value == ''){
		document.getElementById('phone').style.borderColor = 'red';
	}else{
		document.getElementById('phone').style.borderColor = '#ccc';
	}
}
</script>
</body>
</html>



