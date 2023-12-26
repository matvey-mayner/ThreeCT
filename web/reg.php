<?php
	require_once "../include/config.php";

	if(isset($_SESSION['user'])){
		header("Location: index.php");
	}

	$checkemail = 'SELECT email FROM users WHERE email = "' .$_POST['email']. '"';
	$checkip = 'SELECT ip FROM users WHERE ip = "' .$_SERVER['REMOTE_ADDR']. '"';
	$createacc = 'INSERT INTO users(name, email, pass, ip, descr) VALUES (
		"' .mysqli_real_escape_string($db, $_POST['username']). '", 
		"' .mysqli_real_escape_string($db, $_POST['email']). '", 
		"' .mysqli_real_escape_string($db, password_hash($_POST['pass'], PASSWORD_DEFAULT)). '", 
		"' .mysqli_real_escape_string($db, $_SERVER['REMOTE_ADDR']). '", 
		"' .mysqli_real_escape_string($db, $_POST['descr']). '"
	)';
							
	if(isset($_POST['do_signup'])){
		$text = array('text' => "");

		if(empty(trim($_POST['username']))){
			$text['text'] = 'Введите свой ник!';
		}
						
		if(empty(trim($_POST['email']))){
			$text['text'] = 'Введите свою email почту!';
		}
						
		if(empty(trim($_POST['pass']))){
			$text['text'] = 'Введите свой пароль!';
		}	
						
		if($_POST['pass2'] != $_POST['pass'] ){
			$text['text'] = 'Повторный пароль введён неверно!';
		}
						
		if((mysqli_num_rows(mysqli_query($db, $checkemail))) != 0){
			$text['text'] = 'Email почта занятя!';
		}	

		if(mysqli_num_rows(mysqli_query($db, $checkip)) != 0){
			$text['text'] = 'Вы уже зарегистрированы!';
		}

		if(empty(trim($text['text']))){
			if(mysqli_query($db, $createacc)){
				$text['text'] = 'Вы успешно зарегистрированы';;
			} else {
				$text['text'] = 'Ошибка сервера';
			}
		}
	}
?>

<html>
	<head>
		<?php include '../include/html/head.php'; ?>
		<title>Регистрация</title>
	</head>
	<body>
		<div class="header">
			<a href="login.php">Войти</a>
		</div>
		<div class="main_app">
			<div class="main">
				<form action="reg.php" method="POST">
					<p>
						<p>Ваш ник (Максимум 16 букв):</p>
						<input type="text" name="username" value="<?php echo $_POST['username']; ?>">
					</p>
					<p>
						<p>Ваша электронная почта:</p>
						<input type="email" name="email" value="<?php echo $_POST['email']; ?>">
					</p>
					<p>
						<p>Ваш пароль (Максимум 20 букв):</p>
						<input type="password" name="pass" value="<?php echo $_POST['pass']; ?>">
					</p>
					<p>
						<p>Повторить ваш пароль:</p>
						<input type="password" name="pass2">
					</p>
					<p>
						<p>Описание вашего аккаунта:</p>
						<textarea name="descr"></textarea>
					</p>
					<p>
						<button type="submit" name="do_signup">Зарегестрироваться</button>
					</p>
				</form>
				<p><?php echo($text['text']); ?></p>
			</div>
		</div>
	</body>
</html>