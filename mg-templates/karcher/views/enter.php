<? mgSEO($data); ?>
<div class="page-content">
	<ul class="breadcrumbs bor-b">
		<li><a href="<?=SITE;?>">Главная</a></li>
		<span> / </span>
		<li>Авторизация пользователя</li>
	</ul><!-- !ul.breadcrumbs -->
	<h1 class="title-page">Авторизация пользователя</h1>
	<div class="clearfix form-login-user">
		<?if(!empty($data['msgError'])):?>
		<div class="comments-msg active error"><?=$data['msgError'];?></div>
		<?endif;?>
		<?if(!empty($data['message'])):?>
		<div class="comments-msg active success"><?=$data['message'];?></div>
		<?endif;?>
		<div class="left">
			<div class="cart-box bor-b">
				<h3>Новый пользователь</h3>
				<div class="cart-box-body">
					<form action="<?php echo SITE?>/enter" method="POST">
						<div class="add-cmt-form">
							<div class="welcome-text bor-b">
								<p>Создание учетной записи в нашем интерент-магазине даст вам массу преимуществ перед обычными покупателями. Вы сможете следить за своими заказами, вносить изменения в ваши данные покупателя.</p>
							</div>
							<div class="form-el">
								<label for="txtEmailReg">Email*:</label>
								<input type="text" id="txtEmailReg" type="text" name="email" value="<?php echo $_POST['email']?>">
							</div>
							<div class="form-el">
								<label for="txtPassReg">Пароль*:</label>
								<input type="password" id="txtPassReg" name="pass">
							</div>
							<div class="form-el">
								<label for="txtPassReg2">Подтвердите пароль*:</label>
								<input type="password" name="pass2" id="txtPassReg2">
							</div>
							<div class="form-el">
								<label for="txtName">Ваше имя:</label>
								<input type="text" name="name" value="<?php echo $_POST['name']?>" id="txtName">
							</div>
							<div class="form-el">
								<label for="capcha">Введите текст с картинки:</label>
								<img style="margin-top: 5px; border: 1px solid gray; background: url('<?php echo PATH_TEMPLATE ?>/images/cap.png');" src = "captcha.html" width="140" height="36">
								<br>
								<input type="text" name="capcha" id="capcha" class="captcha">
							</div>
						</div>
						<div class="form-btn clearfix">
							<div class="right text-right">
								<button type="submit" name="registration">Регистрация</button>
							</div>
						</div>
					</form>
				</div><!-- !div.cart-box-body -->
			</div><!-- !div.cart-box -->
		</div>
		<div class="right">
			<div class="cart-box bor-b">
				<h3>Вход</h3>
				<div class="cart-box-body">
					<form action="<?php echo SITE ?>/enter" method="POST">
						<div class="add-cmt-form">
							<div class="welcome-text bor-b">
								<p>Если у вас уже есть аккаунт, то тогда вводите свой логин и пароль.</p>
							</div>
							<div class="form-el">
								<label for="txtEmail">Email*:</label>
								<input type="text" id="txtEmail" name="email" value="<?php echo !empty($_POST['email'])?$_POST['email']:'' ?>">
							</div>
							<div class="form-el">
								<label for="txtPass">Пароль*:</label>
								<input type="password" id="txtPass" name="pass">
							</div>
						</div>
						<div class="form-btn clearfix">
							<div class="left">
								<a href="<?php echo SITE ?>/forgotpass" class="orange">Забыли пароль?</a>
							</div>
							<div class="right text-right">
								<button type="submit">Вход</button>
							</div>
						</div>
					</form>
				</div><!-- !div.cart-box-body -->
			</div><!-- !div.cart-box -->
		</div>
	</div>	
</div><!-- !div.page-content -->