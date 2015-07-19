<?mgSEO($data);?>
<h1 class="title-page">Восстановление пароля</h1>
<div class="cart-box bor-b">
  <div class="cart-box-body">
  	<div id="feed-back-ajx-msg" class="active">На адрес электронной почты будет отправлена инструкция по восстановлению пароля.</div>
    <?php if($data['message']):?>
    	<div id="feed-back-ajx-msg" class="active success"><?php echo $data['message']?></div>
    <?php endif; ?>
    <?php if($data['error']):?>
    	<div id="feed-back-ajx-msg" class="active error"><?php echo $data['error']?></div>
    <?php endif;?>
    <div class="add-cmt-form">
    	<?php switch($data['form']){case 1: ?>  
    	<form action="<?php echo SITE?>/forgotpass" method = "POST">
    		<div class="form-el">
    			<label for="txtEmail">Email:</label>
    			<input type="text" name="email" id="txtEmail" style="width:20%;">
    		</div>
    		<div class="form-btn clearfix">
    			<button type="submit" class="enter-btn ie7-fix" name="forgotpass" value="Отправить">Отправить</button>
    		</div>
    	</form>
    	<?php break; case 2: ?>
    	<form action="<?php echo SITE?>/forgotpass" method="POST">
    		<div class="form-el">
    		  <label for="newPass">Новый пароль (не менее 5 символов):</label>
    		  <input type="password" id="newPass" name="newPass" style="width:20%;">
    		</div>
    		<div class="form-el">
    		  <label for="pass2">Подтвердите новый пароль:</label>
    		  <input type="password" id="pass2" name="pass2" style="width:20%;">
    		</div>
    		<div class="form-btn clearfix">
    		  <button type="submit" name="chengePass" value="Сохранить">Сохранить</button>
    		</div>
    	</form>
    	<?php } ?>
    </div>
    </div>
</div>