/**
 * Модуль для  раздела "Новости".
 */

var partner = (function () {
  return {

    /**
     * Инициализирует обработчики для кнопок и элементов раздела.
     */
    init: function() {
   
      
      // Вызов модального окна при нажатии на кнопку изменения.
      $('.admin-center').on('click', '.section-parnters .edit-row', function(){
        partner.openModalWindow('edit', $(this).attr('id'));
      });

      // Удаления.
      $('.admin-center').on('click', '.section-parnters .delete-order', function(){
        partner.deleteNews($(this).attr('id'));
      });

      // Выплата.
      $('body').on('click', '#add-partners-wrapper .save-button', function(){
        partner.paymentToPartner($(this).attr('id'));
      });
      
      // Сохраняет базовые настроки
      $('.admin-center').on('click', '.section-parnters .base-setting-save', function() {
       
        var obj = '{';
        $('.section-parnters .list-option input').each(function() {     
          obj += '"' + $(this).attr('name') + '":"' + $(this).val() + '",';
        });
        obj += '}';    

        //преобразуем полученные данные в JS объект для передачи на сервер
        var data =  eval("(" + obj + ")");

        admin.ajaxRequest({
          mguniqueurl: "action/saveBaseOption", // действия для выполнения на сервере
          pluginHandler: 'partners-program', // плагин для обработки запроса
          data: data // id записи
        },

        function(response) {
          admin.indication(response.status, response.msg);     
          admin.refreshPanel();
        }

        );
        
      }); 
      
      // Показывает панель с настройками.
      $('.admin-center').on('click', '.section-parnters .show-property-order', function() {
          $('.property-order-container').slideToggle(function() {      
          $('.widget-table-action').toggleClass('no-radius');
        });
      });

    },


    /**
     * Открывает модальное окно.
     * type - тип окна, либо для создания нового товара, либо для редактирования старого.
     */
    openModalWindow: function(type, id) {

      switch (type) {
        case 'edit':{
          partner.clearFileds();
          $('#modalTitle').text('Подробная информация');
          partner.editPage(id);
          break;
        }
        case 'add':{
          $('#modalTitle').text('Добавить партнера');
          partner.clearFileds();
          break;
        }
        default:{
          partner.clearFileds();
          break;
        }
      }

      // Вызов модального окна.
      admin.openModal($('.b-modal'));

    },

   
    /**
     * Производит списание средств со счета партнера, заносит информацию в историю по выплатам
     */
    paymentToPartner: function(id) {
 
     var summ = parseFloat($('input[name="payment"]').val());
     if(isNaN(summ)){ 
        admin.indication('error', 'Введите сумму выплаты');
        return false;      
     }
     if( summ>parseFloat($('.add-product-form-wrapper .exitbalance').text()) || summ==0){ 
        admin.indication('error', 'Введите допустимую к выплате сумму, нельзя выплатить больше чем заработано партнером');
        return false;      
      }
      
    if(confirm('Действительно хотите произвести выплату?')){
      // отправка данных на сервер для сохранеиня
      admin.ajaxRequest(
       {
          pluginHandler: 'partners-program', // имя папки в которой лежит данный плагин
          actionerClass: "Partner", // класс News в partner.php - в папке плагина
          action: "paymentToPartner", // название действия в пользовательском  классе News
          partner_id:id,
          summ:summ
       },
        function(response) {
          admin.indication(response.status, response.msg);          
          $('.partner-tbody tr[id='+id+'] td[class=payments_amount]').text(response.data.amount);
          admin.closeModal($('.b-modal'));
        }
      );
    }
    },
    /**
     * Получает данные о новости с сервера и заполняет ими поля в окне.
     */
    editPage: function(id) {
      admin.ajaxRequest({
          pluginHandler: 'partners-program', // имя папки в которой лежит данный плагин
          actionerClass: "Partner", // класс News в partner.php - в папке плагина
          action: "getPartnerBalanse", // название действия в пользовательском  классе News
          id:id
      },
      partner.fillFileds()
      );
    },


   

   /**
    * Заполняет поля модального окна данными
    */
    fillFileds:function() {
      return (function(response) {  
        $('.add-product-form-wrapper .balance').text(response.data.balance+' руб. ');     
        $('.add-product-form-wrapper .amount').text(response.data.amount+' руб. ');   
        $('.add-product-form-wrapper .exitbalance').text(response.data.exitbalance+' руб. ');        
        $('.save-button').attr('id',response.data.id);
      })

    },


   /**
    * Чистит все поля модального окна
    */
    clearFileds:function() {
      $('.add-product-form-wrapper .balance').text('');     
      $('.add-product-form-wrapper .amount').text(''); 
      $('.add-product-form-wrapper .exitbalance').text(''); 
      $('input[name="payment"]').val('')
      $('.save-button').attr('id','');
    },

  }
})();

// инициализациямодуля при подключении
partner.init();