<script>
$('.blocks-plugin .toggleLink').bind('click', function(){
  $(this).closest('.blocks-plugin').find('.content-settings').toggle();
  return false;
}); 
</script>

<div style="padding:15px;">
  <div class="main-settings-container">
    <h4>SMS оповещения</h4>
    <form methot="post" action="">
    <table class="main-settings-list">
      <tbody>
        <tr>
          <td><span>Номер телефона:</span></td>
          <td><input class="nomer" type="text" name="nomer" value="<?=$nomer;?>"></td>
          <td><span>Телефон отображается на сайте</span></td>
        </tr>
        <tr>
          <td><span>Токен:</span></td>
          <td><input class="token" type="text" name="token" value="<?=$token;?>"></td>
          <td><span>Токен</span></td>
        </tr>
      </tbody>
    </table>
    <input type="hidden" name="pluginTitle" value="<?=$_POST['pluginTitle'];?>"/>
    <button value="Применить" class="save-button save-settings"><span>Применить</span></button>
    <div class="clear"></div>
    </form>
  </div>
  <br>
  <div class="blocks-plugin settings">
    <div class="toggleLink">
      <a href="javascript:void(0);">Инструкция по настройке</a>
    </div>
    <div class="content-settings">
      Для получения токена пройдите по ссылке <a href="http://moguta.sms.ru/?panel=register">Регистрация</a>;<br/>
      Пройдите бесплатную регистрацию, подтвердите номер телефона кодом из SMS сообщения (бесплатно);<br/>
      Откройте <a href="http://moguta.sms.ru/?panel=my">Панель</a> и скопируйте Ваш <b>api_id</b> - это и есть токен.<br/>
      <br/>
      Для отправки SMS достаточно добавить в нужном месте шорт код <b>[sms]Текст сообщения[/sms]</b>.<br/>
      По-умолчанию сообщение будет отправлено на Ваш номер.<br/>
      Чтобы отправить смс на другой номер укажите его в шорткоде. Например так <b>[sms nomer="79990001122"]Hellow World![/sms]</b>. Перед отправкой SMS на другие номера ознакомьтесь с тарифами и правилами на сайте <a href="http://moguta.sms.ru/?panel=settings&subpanel=plan">sms.ru</a>.<br/>
      Бесплатная отправка SMS возможна только на свой номер!<br/>
      <br/>
      Полный формат шорт кода: <b>[sms nomer="79180001122" token="a123b45c-d6e7-8h90-g123-k45m6no78901"]Текст сообщения[/sms]</b>.<br/>
      Обязателен только текст сообщения. Номер и токен можно не указывать (они должны быть указаны в настройках плагина)<br/>
      <br/>
      Для получения уведомления о новом заказе откройте шаблон order.php и найдите текст "<i>На Ваш электронный адрес выслано письмо для подтверждения заказа</i>", сразу после него вставьте <b>[sms]Принята заявка #&lt?php echo $data[\'id\']; ?&gt Сумма &lt?php echo $data[\'summ\']; ?&gt &lt?php echo $data[\'currency\']; ?&gt[/sms]</b>
    </div>
  </div>
