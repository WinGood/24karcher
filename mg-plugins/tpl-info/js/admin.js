var tplInfoModule = (function() {
	return {
		init: function()
		{
			$('.admin-center').on('change', '.bannerform', function(){        
			  tplInfoModule.addBanner();
			});

			$('.admin-center').on('click', '.save-settings', function(){
			  var tabName = $(this).parent('.main-settings-container').attr('id');
			  admin.ajaxRequest({
				mguniqueurl: "action/editSettings",
				pluginHandler: 'tpl-info',
				options: tplInfoModule.getAllSetting(tabName)
			  },
			  function(response) {
				admin.indication(response.status, response.msg);
				$('.tabs-content').animate({opacity: "hide"}, 1000);
				$('.tabs-content').animate({opacity: "show"}, "slow");
			  }
			 );
			});
		},
		getAllSetting: function(tab) {
		  //собираем из таблицы все инпуты с данными, записываим их в виде нативного кода
		  var obj ='{';
		  $('#'+tab+' .option').each(function(){
			var val = $(this).val();
		   // исключение для кодов счетчиков, т.к. в них можгут встретиться запрещенные символы
		   if($(this).attr('name')!='widgetCode'){ 
			obj+='"'+$(this).attr('name')+'":"'+val+'",';
		   }else{
			 obj+='"'+$(this).attr('name')+'":"",';
		   }
		  });
		  obj+='}';
		  
		
		  obj=eval("(" + obj + ")");
		  
		  //теперь присваиваем текстовое значение объекту
		  obj.widgetCode=$('textarea[name=widgetCode]').val();
		
		  return obj;
		},
		addBanner: function() {
			console.log('asf');
		  $('.bannerform').ajaxForm({
		    type:"POST",
		    url: "ajax",
		    data: {
		      mguniqueurl: 'action/updateBanner',
		      pluginHandler: 'tpl-info'
		    },
		    cache: false,
		    dataType: 'json',
		    success: function(response){
		      admin.indication(response.status, response.msg);
		      $('.banner-img img').attr('src', admin.SITE+'/mg-plugins/tpl-info/img/'+response.data.photoimg);
		      $('#bannerField').val(response.data.photoimg);
		    }
		  }).submit();
		}
	}
})();

tplInfoModule.init();