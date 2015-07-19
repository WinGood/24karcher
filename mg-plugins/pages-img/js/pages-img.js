var pageImgModule = (function() {
	return {

		field: '<label><form method="post"><span class="custom-text">Изображение страницы:</span><input type="file" name="imgPage"></form></label>',
		fieldCurrent: '<label><span class="custom-text">Текущее изображение:</span><input type="text" name="currentImg" disabled></label>',
		countPage: '',
		flag: '',
		pageIdEdit: '',

		init: function()
		{
			admin.ajaxRequest({
				mguniqueurl: 'action/getCountPage',
				pluginHandler: 'pages-img'
			}, function(response) {
				pageImgModule.countPage = response.data.count;
			});

			$('#add-page-wrapper input[name="url"]').parent().after(pageImgModule.fieldCurrent + pageImgModule.field);

			// Загрузка изображения

			$('input[name="imgPage"]').bind('change', function(){
				$(this).parent('form').ajaxForm({
					type: 'POST',
					url: 'ajax',
					data: {
						mguniqueurl: 'action/loadImg',
						pluginHandler: 'pages-img'
					},
					cache: false,
					dataType: 'json',
					success: function(response)
					{
						admin.indication(response.status, response.msg);
						$('input[name="currentImg"]').val(response.data.img);
					}
				}).submit();
			});

			// Добавление страницы

			$('.add-new-button').bind('click', function(){
				pageImgModule.flag = 'add';
			});

			// Редактирование страницы

			$('.edit-sub-cat').bind('click', function(){
				pageImgModule.pageIdEdit = $(this).attr('id');
				pageImgModule.flag = 'edit';
				admin.ajaxRequest({
					mguniqueurl: 'action/getImg',
					pluginHandler: 'pages-img',
					id: pageImgModule.pageIdEdit,
					img: $('input[name="currentImg"]').val()
				}, function(response) {
					$('input[name="currentImg"]').val(response.data.img);
				});	
			});

			// Добавление в БД изображения

			$('#add-page-wrapper .save-button').bind('click', function(){
				var title  = $('#add-page-wrapper input[name="title"]').val();
				var url    = $('#add-page-wrapper input[name="url"]').val();
				if(title != '' && url != '')
				{
					if(pageImgModule.flag == 'edit')
					{
						admin.ajaxRequest({
							mguniqueurl: 'action/editImg',
							pluginHandler: 'pages-img',
							img: $('input[name="currentImg"]').val(),
							id_page: pageImgModule.pageIdEdit
						}, function(response) {
							admin.indication(response.status, response.msg);
						});	
					}
					else if(pageImgModule.flag == 'add')
					{
						admin.ajaxRequest({
							mguniqueurl: 'action/addImg',
							pluginHandler: 'pages-img',
							img: $('input[name="currentImg"]').val(),
							id_page: pageImgModule.countPage
						}, function(response) {
							admin.indication(response.status, response.msg);
						});	
					}
				}
			});
		}
	}
})();

pageImgModule.init();