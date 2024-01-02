<?php
	require_once "../include/config.php";

	$data = mysqli_fetch_assoc(mysqli_query($db, 'SELECT * FROM users WHERE id = ' .(int)$_GET['id']));
?>

<html>
<head>
	<?php include '../include/html/head.php'; ?>
    <title>Пользователь <?php echo($data['name']); ?></title>
</head>
<body>
	<?php include '../include/html/header.php'; ?>
	<div class="main_app">
		<div class="main">
			<?php if((int)$_GET['id'] == $_SESSION['user']['user_id']): ?>
				<div class="changeuser">
					<a href="upload.php">Изменение аватарки</a>
					 | 
					<a href="edit.php">Настройки</a>
				</div>
			<?php endif ?>
			<table>
				<tr>
					<td>
						<img class="img100" src="<?php echo($data['img100']); ?>">
					</td>
					<td class="info">
						<h1>
							<?php 
								echo($data['name']); 

								if($data['priv'] >= 1){
									echo('<span title="Аккаунт официальный" class="material-symbols-outlined">done</span>');
								}
							?>
						</h1>
					</td>
				</tr>
			</table>
			<h1>Описание: <?php echo($data['descr']); ?></h1>
		</div>
		<h1 class="head">Стена</h1>
		<div class="wall">
			<?php if(isset($_SESSION['user']) and $data['yespost'] == 1 
			or (int)$_GET['id'] == $_SESSION['user']['user_id']): ?>
				<form action="../method/makepost.php" method="post" class="posting" enctype="multipart/form-data">
					<input type="hidden" name="access_token" value="<?php echo($_SESSION['user']['access_token']); ?>">
					<input type="hidden" name="owner_id" value="<?php echo((int)$_GET['id']); ?>">
					<textarea name="text" class="postarea" minlength="3"></textarea>
					<button type="submit" name="do_post" class="do_post">Опубликовать</button>
					<details class="detail">
						<summary>Прикрепить</summary>
						<input type="file" name="file">
					</details>
				</form>
			<?php endif; ?>
			<?php 
				$data = mysqli_query($db, 'SELECT * FROM post WHERE id_user = ' .(int)$_GET['id']. ' ORDER BY pin DESC, date DESC LIMIT 10 OFFSET ' .(int)$_GET['p'] * 10);
			?>
			<?php while($list = mysqli_fetch_assoc($data)): ?>
				<div class="post">

					<?php $user = mysqli_fetch_assoc(mysqli_query($db, 'SELECT name FROM users WHERE id = ' .$list['id_user'])); ?>
					<b>
						<a class="user" href="user.php?id=<?php echo($list['id_user']); ?>">
							<?php echo($user['name']); ?>
						</a>
					</b>
					<?php
						if($list['pin'] == 1){
							echo('Закреплено');
						}
					?>

					<?php if($list['id_user'] == $_SESSION['user']['user_id'] or $list['id_who'] == $_SESSION['user']['user_id']): ?>
						<a href="../method/delpost.php?id=<?php echo($list['id']); ?>">
							<span class="material-symbols-outlined">
								close
							</span>
						</a>

						<a href="../method/pinpost.php?id=<?php echo($list['id']); ?>">
							<span class="material-symbols-outlined">
								push_pin
							</span><br>
						</a>
					<?php endif; ?>

					<span class="date">
						<?php echo(date('d M Y в H:i', $list['date'])); ?>
					</span><br>

					<?php $user = mysqli_fetch_assoc(mysqli_query($db, 'SELECT name FROM users WHERE id = ' .$list['id_who'])); ?>
					<b>От имени: 
						<a class="user" href="user.php?id=<?php echo($list['id_who']); ?>">
							<?php echo($user['name']); ?>
						</a>
					</b>

					<?php 
						if($list['img'] != NULL){
							echo('<img src="' .$list['img']. '">');
						}
					?>

					<p><?php echo(strip_tags($list['post'])); ?></p>
				</div>
			<?php endwhile; ?>
			<?php if((int)$_GET['p'] >= 1): ?>
				<a class="back" href="?id=<?php echo((int)$_GET['id']); ?>&p=<?php echo((int)$_GET['p'] - 1); ?>">Предыдущая страница</a>
			<?php endif; ?>
			<a class="next" href="?id=<?php echo((int)$_GET['id']); ?>&p=<?php echo((int)$_GET['p'] + 1); ?>">Следующая страница</a>
		</div>
	</div>
</body>
</html>
