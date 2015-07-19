<script type="text/javascript">
includeJS('../mg-plugins/partners-program/js/partners.js');
</script>
<link rel='stylesheet' href='../mg-plugins/partners-program/css/style.css' type='text/css' />﻿

 <div class="section-parnters">
      <div class="widget-table-action">
          <a href="javascript:void(0);" class="show-property-order tool-tip-top" title="Настройки выплат"><span>Настройки выплат</span></a>
          <div class="clear"></div>
      </div>
      
      <div class="property-order-container">    
        <h2>Настройки выплат:</h2>
          <form  class="base-setting" name="base-setting" method="POST">       
              <ul class="list-option">
                  <li><label><span>Процент выплат от заказов:</span> <input type="text" name="percent" value="<?php echo $options["percent"]?>" style="width:30px;"> %</label></li>
                  <li><label><span>Минимальная сумма для вывода:</span> <input type="text" name="exitMoneyLimit" value="<?php echo $options["exitMoneyLimit"]?>" style="width:80px;"> <?php echo MG::getSetting('currency');?></label></li>
              </ul>
              <div class="clear"></div>
          </form>
          <div class="clear"></div>
        <a href="javascript:void(0);" class="base-setting-save custom-btn"><span>Сохранить</span></a>
        <div class="clear"></div>
      </div>

      <div class="b-modal hidden-form" id="add-partners-wrapper">
        <div class="product-table-wrapper add-news-form">
          <div class="widget-table-title">
            <h4 class="pages-table-icon" id="modalTitle"><?php echo $lang['NEWS_MODAL_TITLE'];?></h4>
            <div class="b-modal_close tool-tip-bottom" title="<?php echo $lang['T_TIP_CLOSE_MODAL'];?>"></div>
          </div>
          <div class="widget-table-body">
            <div class="add-product-form-wrapper">
                <div class="partners-payment-block">
                    <table class="widget-table">
                        <thead>
                        <tr>
                            <th>Заработано партнером</th>
                            <th>Всего выплачено</th>
                            <th>Доступно для вывода</th>
                        </tr>
                        </thead>
                        <tbody class="partner-tbody">
                        <tr>
                            <td class="email"><span class="balance">0</span></td>
                            <td class="email"><span class="amount">0</span></td>
                            <td class="percent"><span class="exitbalance">0</span></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
              <br/>
              <p class="notify-message">Кнопка <span class="bold-text">"Выплатить"</span> нажимается только после реальной выплаты агенту, выплата производится вне системы. Данным действием в базу заносится информация о выплате и ее дате для истории, а у партнера списывается сумма со счета</p>
              <br/>
              <label for="amount"><span>Выплатить:</span>
                <input type="text" name="payment" class="price-input"><div class="errorField"><?php echo $lang['ERROR_EMPTY'];?></div> руб.</label>
              
              <button class="save-button tool-tip-bottom" title="Нажать для занесения в таблицу выплат"><span>Выплатить</span></button>
                <div class="clear"></div>
            </div>
          </div>
        </div>
      </div>

    <!-- Тут заканчивается Верстка модального окна -->


    <!-- Тут начинается  Верстка таблицы  -->
    <div class="widget-table-body partners-table-body">
  
      <div class="main-settings-container">
        <table class="widget-table product-table">
          <thead>
            <tr>
              <th>id</th>
              <th>e-mail</th>
              <th>Всего заказов</th>
              <th>Коммисия в %</th>
              <th>Выплачено</th>
              <th class="actions">Действия</th>
            </tr>
          </thead>
          <tbody class="partner-tbody">

          <?php
          if(!empty($partners)){
          foreach($partners as $data){ ?>
              <tr id="<?php echo $data['id'] ?>">
                <td class="id">
                 <?php echo $data['id'] ?>
                </td>
                <td class="email"><?php echo $data['email'] ?></td>
                <td class="email"><?php echo $data['count_orders'] ?></td>
                <td class="percent"><?php echo $data['percent'] ?></td>
                 <td class="payments_amount"><?php echo $data['payments_amount'] ?></td>               
                <td class="actions">
                  <ul class="action-list">
                    <li class="edit-row" id="<?php echo $data['id'] ?>"><a class="tool-tip-bottom" href="#" title="<?php echo $lang['EDIT'];?>"></a></li>
                    <li class="delete-order" id="<?php echo $data['id'] ?>"><a class="tool-tip-bottom" href="#"  title="<?php echo $lang['DELETE'];?>"></a></li>
                  </ul>
                </td>
              </tr>
           <?php }
          }else{
          ?>

           <tr class="noneNews"><td colspan="5">Нет партнеров</td></tr>

         <?php }?>

          </tbody>
        </table>
      </div>

      <?php echo $pagination ?>
      <div class="clear"></div>
   </div>
 </div>
