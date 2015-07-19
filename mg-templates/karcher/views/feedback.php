<?mgSEO($data);?>
<h1 class="new-products-title">Обратная связь</h1>

<div class="pade-desc">
	<?php echo $data['html_content'] ?>
</div>

<div class="msgError">
	<?php	if(!empty($data['error'])){ echo $data['error']; }?>
</div>

<div class="feedback-form-wrapper">
<?php if($data['dislpayForm']){ ?>
	<p class="auth-text">Для связи с нами заполните форму ниже.</p>
	<form action="" method="post">
		<ul class="form-list">
			<li>Ф.И.О.:</li>
			<li><input type="text" name="fio" value="<?php echo !empty($_POST['fio'])?$_POST['fio']:'' ?>"></li>
			<li>Email:<span class="red-star">*</span></li>
			<li><input type="text" name="email" value="<?php echo !empty($_POST['email'])?$_POST['email']:'' ?>"></li>
			<li>Сообщение:<span class="red-star">*</span></li>
			<li><textarea class="address-area" name="message"><?php echo !empty($_POST['message'])?$_POST['message']:'' ?></textarea></li>
		    <li>Введите текст с картинки:</li>
			<li><img style="margin-top: 5px; border: 1px solid gray; background: url('<?php echo PATH_TEMPLATE ?>/images/cap.png');" src = "captcha.html" width="140" height="36"></li>
			<li><input type="text" name="capcha" class="captcha"></li>
		
		</ul>
		<input type="submit" name="send" class="enter-btn" value="Отправить сообщение">
	</form>
	<div class="clear">&nbsp;</div>


<?php } else { ?>
  <div class='successSend'> <?php echo $data['message']?> </div>
<?php }; ?>
</div>