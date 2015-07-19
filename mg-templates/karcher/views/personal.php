<? mgSEO($data); ?>
<div class="page-content">
	<ul class="breadcrumbs bor-b">
		<li><a href="<?=SITE;?>">Главная</a></li>
		<span> / </span>
		<li>Личный кабинет</li>
	</ul><!-- !ul.breadcrumbs -->
<? switch($data['status']){ case 1: ?>
<div class="white-box bor-b">
	<span style="color:red">Доступ пользователя к личному кабинету блокирован. Обратитесь к администратору</span>
</div>
<? break; case 2:?>
<div class="add-cmt-form white-box bor-b">
	<p style="color:red">Пользователь не активирован! Для активации пользователя перейдите по ссылке указанной в письме, полученом Вами при регистрации</p>
	<p style="color:black">Запрос повторной активации</p>
	<form action = "<?php echo SITE ?>/registration" method = "POST">
		<input type="text" name="activateEmail" value="Email"></td>
		<input type="submit" style="border:none;display:inline;font-size:12px; padding: 9px 15px;cursor:pointer;" class="enter-btn default-btn add-cmt add-cmt" name="reActivate" value = "Отправить запрос">
	</form>
</div>
<? break; case 3: $userInfo = $data['userInfo']?>
	<h1 class="title-page">Личный кабинет "<?=$userInfo->name?>"</h1>
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
	<div class="comments-msg active">
		<span>В своем кабинете Вы сможете следить за статусами ваших заказов, так же изменять свои личные данные.</span>
	</div>
	<div id="tabs-ftr"> 
		<ul class="clearfix bor-b btn-tabs"> 
		    <li><a href="#psr-info" class="selected">Личные данные</a></li> 
		    <li><a href="#chg-pass">Сменить пароль</a></li>  
		    <li><a href="#hst-order">История заказов</a></li>
	    </ul>
	    <div id="tabs-contents" class="bor-b form-tabs cart-box-body">
		    <div id="psr-info">
		    	<p class="title-tabs">Личные данные:</p>
		    	<form action="<?php echo SITE?>/personal" method = "POST">
			    	<div class="add-cmt-form">
			    		<div class="form-el">
			    			<label for="txtName">Имя:</label>
			    			<input type="text" id="txtName" name="name" value="<?php echo $userInfo->name?>">
			    		</div>
			    		<div class="form-el">
			    			<label for="txtSname">Фамилия:</label>
			    			<input type="text" id="txtSname" name="sname" value="<?php echo $userInfo->sname?>">
			    		</div>
			    		<div class="form-el">
			    			<label for="txtPhone">Телефон:</label>
			    			<input type="text" id="txtPhone" placeholder="_(___) ___ __ __" name="phone" value="<?php echo $userInfo->phone?>" title="Формат: 1(234) 567 89 00">
			    		</div>
			    		<div class="form-el">
			    			<label for="txtAddr">Адрес доставки:</label>
			    			<textarea class="address-area" id="txtAddr" name="address"><?php echo $userInfo->address?></textarea>
			    		</div>
			    		<select name="customer" style="display:none;">
			    		  <?php $selected = $userInfo->inn?'selected':'';?>
			    		  <option value="fiz">Физическое лицо</option>
			    		  <option value="yur" <?php echo $selected?>>Юридическое лицо</option>
			    		</select>
			    	</div>
			    	<div class="form-btn clearfix">
			    		<div class="right text-right">
			    			<button type="submit" name="userData" value ="save">Сохранить</button>
			    		</div>
			    	</div>
		    	</form>
		    </div> 
		    <div id="chg-pass">
		    	<p class="title-tabs">Сменить пароль:</p>
		    	<form action="<?php echo SITE?>/personal" method = "POST">
			    	<div class="add-cmt-form">
			    		<div class="form-el">
			    			<label for="txtOldPass">Старый пароль*:</label>
			    			<input type="password" id="txtOldPass" name="pass">
			    		</div>
			    		<div class="form-el">
			    			<label for="txtNewPass">Новый пароль(не менее 5 символов):</label>
			    			<input type="password" id="txtNewPass" name="newPass">
			    		</div>
			    		<div class="form-el">
			    			<label for="txtPass2">Повторите новый пароль:</label>
			    			<input type="password" id="txtPass2" name="pass2">
			    		</div>
			    	</div>
			    	<div class="form-btn clearfix">
			    		<div class="right text-right">
			    			<button type="submit" name="chengePass" value="save">Сохранить</button>
			    		</div>
			    	</div>	
		    	</form>			    	
		    </div>
		    <div id="hst-order">
		    	<p class="title-tabs">История заказов:</p>
		    	<?if(!empty($data['orderInfo'])):?>
			    	<?foreach ($data['orderInfo'] as $order):?>
			    	    	<div class="add-cmt-form hst-order-box clearfix" id="<?php echo $order['id'] ?>">
			    	    		<div class="clearfix top-info">
			    		    		<div class="left">Заказ <strong>№<?php echo $order['id'] ?></strong> от <?php echo date('d.m.Y', strtotime($order['add_date']))?></div>
			    		    		<div class="right">Cтатус заказа: <strong class="orange"><?php echo $lang[$order['string_status_id']]?></strong></div>
			    	    		</div>
			    	    		<div class="hst-order-box-body">
			    	    			<table width="100%">
			    	    				<thead>
			    	    					<tr>
			    	    						<td>Товар</td>
			    	    						<td>Артикул</td>
			    	    						<td>Цена</td>
			    	    						<td>Количество</td>
			    	    						<td>Сумма</td>
			    	    					</tr>
			    	    				</thead>
			    	    				<tbody>
			    	    					<?$perOrders = unserialize(stripslashes($order['order_content']));?>
			    	    					<?if(!empty($perOrders)):?>
			    	    					<?foreach ($perOrders as $perOrder):?>
			    	    					<tr>
			    	    						<td><a href="<?php echo $perOrder['url']?>" target="_blank"><?php echo $perOrder['name'] ?></a></td>
			    	    						<td><span><?php echo $perOrder['code'] ?></span></td>
			    	    						<td><span><?php echo $perOrder['price'].'  '.$data['currency']; ?></span></td>
			    	    						<td><span><?php echo $perOrder['count'] ?> шт.</span></td>
			    	    						<td><span><?php echo $perOrder['price'] * $perOrder['count'].'  '.$data['currency']; ?></span></td>
			    	    					</tr>
			    	    					<?endforeach;?>
			    	    					<?endif;?>
			    	    				</tbody>
			    	    			</table>
			    	    			<div class="clearfix bottom-info">
			    	    				<?php if(2 > $order['status_id']):?>
			    	    				<div class="left">
			    	    					<div class="left">
				    	    					<form  method="POST" action="<?php echo SITE?>/order">
				    	    						<input type="hidden" name="orderID" value="<?php echo $order['id']?>">
				    	    						<input type="hidden" name="orderSumm" value="<?php echo $order['summ']?>">
				    	    						<input type="hidden" name="paymentId" value="<?php echo $order['payment_id']?>">
				    	    						<button type="submit" name="pay" value="go" class="pay-btn">Оплатить заказ</button>
				    	    					</form>
			    	    					</div>
			    	    				</div>
			    	    				<?php endif;?>
			    	    				<?php if($order['status_id'] < 2):?>
			    	    				<div class="left">
			    	    					<div class="right opt-button">
			    	    						<ul>
			    	    							<li>
			    	    								<i class="icon-delete"></i> <button class="close-order" id="<?php echo $order['id'] ?>" date="<?php echo date('d.m.Y', strtotime($order['add_date']))?>" href="#openModal">Отменить заказ</button>
			    	    							</li>
			    	    							<li>
			    	    								<i class="icon-edit"></i> <button class="change-payment" id="<?php echo $order['id'] ?>" date="<?php echo date('d.m.Y', strtotime($order['add_date']))?>" href="#changePayment">Изменить способ оплаты</button>
			    	    							</li>
			    	    						</ul>
			    	    					</div>
			    	    				</div>
			    	    				<?php endif;?>
			    		    			<div class="right">
			    		    				<ul>
			    		    					<li><strong>Итого: </strong><?php echo $order['summ'].'  '.$data['currency']?></li>
			    		    					<?php if($order['description']): ?>
			    		    						<li><strong>Доставка: </strong><?php echo $order['description']?></li>
			    		    					<?php endif;?>
			    		    					<li><strong>Оплата: </strong><?php echo $order['name']?></li>
			    		    					<?php $totSumm = $order['summ'] + $order['delivery_cost'];?>
			    		    					<?php if($order['delivery_cost']): ?>
			    		    					<li><strong>Стоимость доставки: </strong><?php echo $order['delivery_cost'].'  '.$data['currency']; ?></li>
			    		    					<?php endif;?>
			    		    					<li><strong>Всего к оплате: </strong><?php echo $totSumm.'  '.$data['currency']; ?></li>
			    		    				</ul>
			    		    			</div>
			    	    			</div>
			    	    		</div><!-- !div.hst-order-box-body -->
			    	    	</div><!-- !div.hst-order-box -->
			    	<?endforeach;?>
		    	<?else:?>
		    		<div class="comment bor-b">
		    			<span>У вас пока нет ни одного заказа, вперед за покупками: <a href="<?=SITE;?>" class="orange">Каталог</a> ;)</span>
		    		</div>
		    	<?endif;?>
		    </div>
	    </div><!-- !div#tabs-contents -->
	</div>
	<div style="display:none;">
		<div class="close-reason">
			<!--Эта часть пропадает после закрытия заказа-->
			<div class="close-reason-wrapper modal-wind" id="openModal">
				<p class="order-number title">Отмена заказа №<strong name="orderId" class="orderId"></strong> от <span class="orderDate"></span></p>
				<div class="modal-body">
					<div class="form-el">
						<label for="txtComment">Укажите причину закрытия заказа:</label>
						<textarea class="reason-text" id="txtComment" type="text" name="comment_textarea" style="resize:none;"></textarea>
					</div>
					<div class="btn-box clearfix">
						<button type="submit" class="close-order-btn default-btn" >Отменить заказ</button>
					</div>
				</div>
				<a class="close-order" href="#successModal" name="next"></a>
				<a class="close-order" href="#errorModal" name="error"></a>
			</div>
			<!--Эта часть пропадает после закрытия заказа-->

			<!--Эта часть появляется после закрытия заказа без перезагрузки страницы-->
			<div class="successful-closure modal-wind" id="successModal">
				<p class="order-close-text title">Заказ №<strong class="orderId"></strong> от <span class="orderDate"></span></p>
				<div class="modal-body" style="background:#eee;padding:15px;">
					<p class="order-close-text green-color">Был успешно отменен!</p>
					<p id="order-comm"></p>
					<a href="#" id="close-order-successbtn" onClick="$.fancybox.close();" class="default-btn orange">Выход</a>
				</div>
				<div class="clear"></div>
			</div>
			<!--Эта часть появляется после закрытия заказа без перезагрузки страницы-->
			<div class="successful-closure" id="errorModal">Ошибка</div>
		</div>
		<div class="change-payment">				
			<div class="close-reason-wrapper modal-wind" id="changePayment">
				<p class="order-number title">Выберите способ оплаты для заказа №<strong name="orderId" class="orderId"></strong> от <span class="orderDate"></span></p>	
				<div class="modal-body">
					<div class="form-el">
						 <select class="order-changer-pay">
						 <?php
						 if($data['orderInfo'])
						 {
						 	foreach ($data['paymentList'] as $item) 
						 	{
						 		$delivery = json_decode($item['deliveryMethod']);            
						 	  	if($delivery->{$order['delivery_id']})
						 	  	{
						 	   		echo "<option value='".$item['id']."'>".$item['name'].'</option>';            
						 	  	}
						 	}
						 }
						?>
						 </select>
					</div>
					<button type="submit" class="change-payment-btn default-btn" >Применить</button>
				</div>
	        </div>		
	    </div>
	</div>

<? break;
default :?>
<span style="color:red">Личный кабинет доступен только авторизованым пользователям!</span>
<? }?>
</div><!-- !div.page-content -->