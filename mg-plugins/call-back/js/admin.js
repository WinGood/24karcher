var callBackModule = (function() {
	return {
		lang: [],
		init: function() {
			admin.ajaxRequest({
			    mguniqueurl: 'action/seLocalesToPlug',
			    pluginName: 'call-back'
			  }, function(response) {
			    lang = response.data;
			  }
			);

			// Нажатие на кнопку - активности
			$('.action-list .visible').bind('click', function(){
				$(this).toggleClass('active');
				var id = $(this).data('id');
				if($(this).hasClass('active'))
				{
					callBackModule.visibleEntity(id, 1); 
					$(this).attr('title', lang.ACT_V_ENTITY);
				}
				else
				{
					callBackModule.visibleEntity(id, 0); 
					$(this).attr('title', lang.ACT_UNV_ENTITY);
				}
				$('#tiptip_holder').hide();
				admin.initToolTip();
			});

			// Сохранение настроек
			$('.save-button').bind('click', function(){
				var onEmail  = $('#chbEmail').prop('checked');
				var adrEmail = $('#txtEmail').val();
				if(adrEmail == '')
				{
					alert('Не заполнено поле Email');
					return false;
				}
				callBackModule.updateConfig(onEmail, adrEmail);
			});

			// Удаляет запись
			$('.action-list .delete-row').bind('click', function(){
				var id = $(this).data('id');
				callBackModule.deleteEntity(id);
			});

			$('.content-settings .setting-box .title-stg').bind('click', function(){
				$(this).closest('.setting-box').find('.content-setting').toggle();
			});

			$('.blocks-plugin .toggleLink').bind('click', function(){
				$(this).closest('.blocks-plugin').find('.content-settings').toggle();
				return false;
			});	
		},

		visibleEntity: function(id, val)
		{
			admin.ajaxRequest({
				mguniqueurl: 'action/visibleEntity',
				pluginHandler: 'call-back',
				id: id,
				invisible: val,
			}, function(response) {
				admin.indication(response.status, response.msg);
			});
		},

		updateConfig: function(onEmail, adrEmail)
		{
			if(onEmail)
				onEmail = 1;
			else
				onEmail = 0;

			admin.ajaxRequest({
				mguniqueurl: 'action/updateConfig',
				pluginHandler: 'call-back',
				send_mail: onEmail,
				email_address: adrEmail
			}, function(response){
				admin.indication(response.status, response.msg);
			})			
		},

		deleteEntity: function(id)
		{
			if(!confirm(lang.DELETE+'?'))
			{
			  return false;
			}

			admin.ajaxRequest({
				mguniqueurl: 'action/deleteEntity',
				pluginHandler: 'call-back',
				id: id          
			}, function(response){
				admin.indication(response.status, response.msg);
				$('.entity-table-tbody tr[data-id='+id+']').remove();
				if($('.entity-table-tbody tr').length == 0)
				{
					var html ='<tr class="no-results">\
					  <td colspan="3" align="center">'+callBackModule.lang['ENTITY_NONE']+'</td>\
					</tr>';
					$(".entity-table-tbody").append(html);
				};
			})
		}
	}
})();

callBackModule.init();