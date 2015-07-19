<? mgSEO($data); ?>
<div class="page-content">
	<ul class="breadcrumbs bor-b">
		<li><a href="<?=SITE;?>">Главная</a></li>
		<span> / </span>
		<li>Регистрация</li>
	</ul><!-- !ul.breadcrumbs -->
	<h1 class="title-page">Регистрация пользователя</h1>
	<?if(empty($data['message']) and empty($data['error'])):?>
	<div class="comments-msg active">Заполните форму ниже, чтобы получить дополнительные возможности в нашем интерент-магазине.</div>
	<?endif;?>
	<?php if($data['message']):?>
		<div class="comments-msg active success">
			<?php echo $data['message']?></span>
		</div>
	<?php endif; ?>

	<?php if($data['error']):?>
		<div class="comments-msg active error">
			<?php echo $data['error']?></span>
		</div>
	<?php endif;?>

	<?php if($data['form']):?>
	<div class="cart-box-body">
		<form action="<?php echo SITE?>/registration" method="POST">
			<div class="add-cmt-form">
				<div class="form-el">
					<label for="">Email*:</label>
					<input type = "text" name = "email" value = "<?php echo $_POST['email']?>">
				</div>
				<div class="form-el">
					<label for="">Пароль*:</label>
					<input type="password" name="pass">
				</div>
				<div class="form-el">
					<label for="">Подтвердите пароль*:</label>
					<input type="password" name="pass2">
				</div>
				<div class="form-el">
					<label for="">Имя:</label>
					<input type="text" name="name" value = "<?php echo $_POST['name']?>">
				</div>
				<div class="form-el">
					<label for="">Введите текст с картинки:</label>
					<img style="margin-top: 5px; border: 1px solid gray; background: url('<?php echo PATH_TEMPLATE ?>/images/cap.png');" src = "captcha.html" width="140" height="36">
					<br>
					<input type="text" name="capcha" class="captcha">
				</div>
				<div class="form-btn">
					<button type="submit" name="registration">Зарегистрироваться</button>
				</div>
			</div>
		</form>
	</div>
	<?endif;?>
</div>