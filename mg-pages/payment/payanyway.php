<div class="payment-form-block">


<form method="post" action="https://www.moneta.ru/assistant.htm">
<input type="hidden" name="MNT_ID" value="<?php echo $data['paramArray'][0]['value']?>">
<input type="hidden" name="MNT_TRANSACTION_ID" value="<?php echo $data['id'] ?>">
<input type="hidden" name="MNT_CURRENCY_CODE" value="<?php echo (MG::getSetting('currencyShopIso')=="RUR")?"RUB":MG::getSetting('currencyShopIso');?>">
<input type="hidden" name="MNT_AMOUNT" value="<?php echo sprintf("%01.2f", $data['summ']);?>">
<input type="hidden" name="MNT_SIGNATURE" value="<?php echo $data['paramArray']['sign'] ?>">
<input type="submit" value="Оплатить заказ">
</form>

<p>
 <em>
 Вы можете изменить способ оплаты данного заказа из Вашего личного кабинета в разделе "<a href="<?php echo SITE?>/personal">История заказов</a>".
 </em>
 </p>
</div>