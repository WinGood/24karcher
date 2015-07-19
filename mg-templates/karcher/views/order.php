<div class="page-content" id="pageOrder">
<?php
switch ($data['step']) 
{
  case 1:
    mgSEO($data);
    ?>
    <ul class="breadcrumbs bor-b">
      <li><a href="<?=SITE;?>">Главная</a></li>
      <span> / </span>
      <li>Оформление заказа</li>
    </ul><!-- !ul.breadcrumbs -->
    <h1 class="title-page">Оформление заказа</h1>
    <?php if ($data['msg']): ?>
      <div class="comments-msg active error">
        <?php echo $data['msg'] ?>
      </div>   
    <?php endif; ?>
    <?if(!empty($data['body_cart'])):?>
    <form method="post" action="<?php echo SITE?>/cart">
    <div id="cart-wp">
      <table width="100%">
        <thead>
          <tr>
            <td width="7%" class="text-center">№</td>
            <td width="43%">Наименование</td>
            <td width="13%" class="text-center">Цена, руб.</td>
            <td width="11%" class="text-center">Кол-во</td>
            <td width="13%" class="text-center">Сумма, руб.</td>
            <td width="8%" class="text-center">Удалить</td>
          </tr>
        </thead>
        <tbody>
          <?$iteration = 1;?>
          <?foreach($data['body_cart'] as $item):?>
          <tr>
            <td class="text-center"><?=$iteration;?></td>
            <td class="title-good">
              <a target="_blank" href="<?php echo SITE ?>/<?php echo isset($item["category_url"]) ? $item["category_url"] : 'catalog' ?>/<?php echo htmlspecialchars($item["product_url"]) ?>"><?php echo $item["title"] ?></a>
            </td>
            <td class="text-center"><?=$item['price']?></td>
            <td class="text-center">
              <input type="text" class="amount_input zeroToo"  name="item_<?php echo $item['id'] ?>[]" value = "<?php echo $item['countInCart']?>"/>
              <input type="hidden"  name="property_<?php echo $item['id'] ?>[]" value = "<?php echo $item['property'] ?>"/>
            </td>
            <td class="text-center"><?=substr($item['priceInCart'], 0, strpos($item['priceInCart'], 'руб.'));?></td>
            <td class="text-center">
              <input type="checkbox" name="del_<?php echo $item['id'] ?>[]" value="<?=$item['id'];?>">
            </td>
          </tr>
          <?$iteration++;?>
          <?endforeach;?>
        </tbody>
        <tfoot>
          <tr>
            <td colspan="2" class="tfoot-pd">
              <a href="<?=SITE;?>" class="continue-btn">Продолжить покупки</a>
            </td>
            <td class="text-center">
              <button type="submit" name="refresh" class="refresh-btn" title="Пересчитать" value="Пересчитать">Пересчитать</button>
            </td>
            <td class="tfoot-pd text-right">
              <strong>Итого:</strong>
            </td>
            <td class="text-center">
              <strong id="totalSum"><?=$data['total_sum_cart'];?>руб.</strong>
            </td>
            <td></td>
          </tr>
        </tfoot>
      </table>
    </div><!-- !div#cart-wp -->
    </form>
    <form action="<?php echo SITE ?>/order" method="post">
    <div class="cart-box bor-b">
      <h3>Способ доставки</h3>
      <div class="cart-box-body" id="orderDelivery">
        <table width="100%">
          <?if(!empty($data['delivery'])):?>
          <?foreach ($data['delivery'] as $delivery):?>
            <tr>
              <th>
                <input type="radio" id="delivery-type-<?=$delivery['id'];?>" name="delivery" <?php if ($delivery['checked']) echo 'checked' ?>  value="<?php echo $delivery['id'] ?>" data-cost="<?php echo $delivery['cost'] ?> руб.">
              </th>
              <td>
                <label for="delivery-type-<?=$delivery['id'];?>"><?php echo $delivery['name'] ?> (<?php echo $delivery['cost'] ?> руб.)</label>
                <span><?php echo $delivery['description'] ?></span>
              </td>
            </tr>
          <?php endforeach; ?>
          <?endif;?>
        </table>
      </div><!-- !div.cart-box-body -->
    </div><!-- !div.cart-box -->
    <div class="cart-box bor-b">
      <h3>Способ оплаты</h3>
      <div class="cart-box-body">
        <table width="100%">
          <?if(!empty($data['paymentArray'])):?>
          <?foreach($data['paymentArray'] as $id => $payment):?>
          <tr>
            <th>
              <input type="radio" id="pay-type-<?=$payment['id'];?>" type="radio" name="payment" <?php if ($payment['checked']) echo 'checked' ?> value="<?php echo $id ?>">
            </th>
            <td>
              <label for="pay-type-<?=$payment['id'];?>">
                <?=$payment['name'];?>
              </label>
              <span>
                <?
                  switch ($id)
                  {
                    case 3:
                      $descPay = 'Наличным платежом сотруднику нашей службы доставки1';
                    break;
                    case 6:
                      $descPay = 'Наличным платежом сотруднику нашей службы доставки2';
                    break;
                    case 7:
                      $descPay = 'Наличным платежом сотруднику нашей службы доставки3';
                    break;             
                    default:
                      $descPay = '';
                    break;
                  }
                  echo $descPay;
                ?>
              </span>
            </td>
          </tr>
          <?endforeach;?>
          <?endif;?>
        </table>
      </div><!-- !div.cart-box-body -->
    </div><!-- !div.cart-box -->
    <div class="cart-box bor-b">
      <h3>Контактная информация</h3>
      <div class="cart-box-body">
        <div class="add-cmt-form">
          <div class="form-el">
            <label for="txtEmail">Email*:</label>
            <input type="text" id="txtEmail" name="email" value="<?php echo $_POST['email'] ?>">
          </div>
          <div class="form-el">
            <label for="txtPhone">Контактный телефон*:</label>
            <input type="text" id="txtPhone" name="phone" placeholder="_(___) ___ __ __" title="Формат: 1(234) 56 78 90" value="<?php echo $_POST['phone'] ?>">
          </div>
          <div class="form-el">
            <label for="txtName">Ваше имя:</label>
            <input type="text" id="txtName" name="fio" value="<?php echo $_POST['fio'] ?>">
          </div>
          <div class="form-el">
            <label for="txtAddress">Адрес доставки:</label>
            <textarea class="txtar-small" id="txtAddress" name="address"><?php echo $_POST['address'] ?></textarea>
          </div>
          <div class="form-el">
            <label for="txtComm">Комментарий к заказу:</label>
            <textarea class="txtar-small" id="txtComm" name="info"><?php echo $_POST['info'] ?></textarea>
          </div>
          <select name="customer" style="display:none;">
            <?php $selected = $_POST['customer']=="yur"?'selected':'';?>
            <option value="fiz">Физическое лицо</option>
            <option value="yur" <?php echo $selected?>>Юридическое лицо</option>
          </select>
        </div>
        <div class="form-btn clearfix">
          <div class="right text-right">
            <p>Итото без доставки: <strong id="resNoDel"></strong></p>
            <p>Доставка: <strong id="costDel"></strong></p>
            <p>Итого: <strong id="resSumma"></strong></p>
            <button name="toOrder" value="Оформить заказ">Оформить заказ</button>
          </div>
        </div>
      </div><!-- !div.cart-box-body -->
    </div><!-- !div.cart-box -->
    </form>
    <?else:?>
    <div class="white-box">Ваша корзина пуста</div>
    <?endif;?>    

    <?php
    break;
  case 2:
    $data['meta_title'] = 'Оплата заказа';
    mgSEO($data);
    if ($data['msg']):
      ?>

    <ul class="breadcrumbs bor-b">
      <li><a href="<?=SITE;?>">Главная</a></li>
      <span> / </span>
      <li>Оплата заказа</li>
    </ul><!-- !ul.breadcrumbs -->
    <h1 class="title-page">Оплата заказа</h1>

    <div class="comments-msg active error">
      <?php echo $data['msg'] ?>
    </div>
    <?php endif; ?>

    <div class="white-box bor-b">
      <?php if (!$data['pay']&&$data['payment']=='fail'): ?>
        <div class="payment-form-block"><span style="color:red"><?php echo $data['message']; ?></span></div>
      <?php else: ?>
        <div class="payment-form-block"><span style="color:green">Ваша заявка <strong>№ <?php echo $data['id'] ?></strong> принята!</span>
          <br>На Ваш электронный адрес выслано письмо для подтверждения заказа.
          <hr>
          <?$orderId = $data['id'];$phone = $data['orderInfo'][$orderId]['phone'];?>
          [sms]Поступил заказ #<?=$data['id'];?> Телефон: <?=$phone;?> Общая сумма: <?=$data['summ'];?> <?=$data['currency'];?>[/sms]
          <p>Оплатить заказ <b>№ <?php echo $data['id'] ?> </b> на сумму <b><?php echo $data['summ'] ?></b>  <?php echo $data['currency']; ?> </p></div>
          <?
            if($data['payMentView']){
              include($data['payMentView']);     
            }
          ?>
      <?endif;?>    
    </div>

    <?php
    
    break;
  case 3:
    $data['meta_title'] = 'Подтверждение заказа';
    mgSEO($data);
    if ($data['msg']):
      ?>
      <div class="white-box bor-b">
      <?php echo $data['msg'] ?>
      </div>   
    <?php endif;

    if ($data['id']):
      ?>
      <h1 class="title-page">Подтверждение заказа</h1>
      <div class="white-box bor-b">
        <p class="auth-text">Заказ №<?php echo $data['id'] ?> <strong>подтвержден</strong></p>
      </div>
    <?php
    endif;
     //если пользователь не активизирован, то показываем форму задания пароля
    if ($data['active']):
      ?>
    <h1 class="title-page">Добро пожаловать!</h1>
    <div class="cart-box bor-b">
      <div class="cart-box-body">
        <div id="feed-back-ajx-msg" class="active success">Вы успешно зарегистрировались на сайте <?php echo SITE ?> и можете отслеживать заказ в личном кабинете.</div>
        <p><strong>Ваш логин: </strong><?php echo $data['active'] ?></p>
        <p><strong>Задайте пароль для доступа в личный кабинет:</strong></p>
        <div class="add-cmt-form">
          <form action="<?php echo SITE ?>/forgotpass" method="POST">
            <div class="form-el">
              <label for="newPass">Новый пароль (не менее 5 символов):</label>
              <input type="password" id="newPass" name="newPass" style="width:20%;">
            </div>
            <div class="form-el">
              <label for="pass2">Подтвердите новый пароль:</label>
              <input type="password" id="pass2" name="pass2" style="width:20%;">
            </div>
            <div class="form-btn clearfix">
              <button type="submit" class="enter-btn ie7-fix" name="chengePass" value="Сохранить">Сохранить</button>
            </div>
          </form> 
        </div>
      </div>
    </div>
      <?php
    endif;
    break;

  case 4:
    ?>
    <h1 class="title-page">Оплатите заказ № <?php echo $data['id'] ?> на сумму <?php echo $data['summ'] ?> <?php echo $data['currency'] ?></h1>
    <div class="white-box">
    <?php
    $data['meta_title'] = 'Оплата заказа';
    mgSEO($data);
    if($data['payMentView']){
      include($data['payMentView']);
    }else{
    ?>
        <span> Ваш способ не предусматривает оплату электронными деньгами</span><br><span> Вы должны оплатить заказ в соответствии с указанным способом оплаты! </span>
    <?}?>
    </div>
    <?php
}
?>
</div><!-- !div.page-content -->