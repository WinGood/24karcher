admin.sortable('.entity-table-tbody', 'slider-images');

var sliderImagesModule = (function() {
	return {
		lang: [],
		lastUploadImg: '',
		init: function(){
			admin.ajaxRequest({
			    mguniqueurl: 'action/seLocalesToPlug',
			    pluginName: 'slider-images'
			  },
			  function(response) {
			    sliderImagesModule.lang = response.data;        
			  }
			);

			$('.add-new-button').bind('click', function(){
				sliderImagesModule.showModal('add');
				return false;
			});

			$('.edit-row a').bind('click', function(){
				sliderImagesModule.showModal('edit', $(this).parent().data('id'));
				return false;
			});

			$('select[name=type]').bind('change', function(){
				sliderImagesModule.changeType($(this).val());
			});

			// Нажатие на кнопку - активности

			$('.visible').bind('click', function(){
				$(this).toggleClass('active');
				var id = $(this).data('id');
				if($(this).hasClass('active'))
				{
					sliderImagesModule.visibleEntity(id, 1); 
					$(this).attr('title', lang.ACT_V_ENTITY);
				}
				else
				{
					sliderImagesModule.visibleEntity(id, 0); 
					$(this).attr('title', lang.ACT_UNV_ENTITY);
				}
				$('#tiptip_holder').hide();
				admin.initToolTip();
			});

			// Удаляет запись

			$('.action-list .delete-row').bind('click', function(){
				var id = $(this).data('id');
				sliderImagesModule.deleteEntity(id);
			});

			// Загрузка изображения

			$('body').on('change', 'input[name="img"]', function(){
				var imgContainer = $(this).parents('.form-el');
				var currentImg = $('.currentImg');
				$(this).parent('form').ajaxForm({
					type: 'POST',
					url: 'ajax',
					data: {
						mguniqueurl: 'action/loadImg',
						pluginHandler: 'slider-images'
					},
					cache: false,
					dataType: 'json',
					success: function(response)
					{
						admin.indication(response.status, response.msg);
						imgContainer.find('img').attr('src', admin.SITE+'/mg-plugins/slider-images/img/slides/'+response.data.img);
						currentImg.val(response.data.img);
						sliderImagesModule.lastUploadImg = response.data.img;
					}
				}).submit();
			});
			
			$('.save-button').bind('click', function(){
				var name_link = $('#txtNameLink').val();
				var url_link  = $('#txtUrlLink').val();
				var desc      = $('#txtDesc').val();
				var id 		  = $(this).attr('id');

				if(name_link != '' && url_link != '' && desc != '')
				{
					admin.ajaxRequest({
						mguniqueurl: 'action/addSlide',
						pluginHandler: 'slider-images',
						type: 'desc',
						img: $('.currentImg').val(),
						name_link: name_link,
						url_link: url_link,
						is_link: 1,
						desc: desc,
						id: id
					}, function(response) {
						admin.indication(response.status, response.msg);
						if(id != '') sliderImagesModule.drawRow(response.data);
					});
					admin.closeModal($('.b-modal'));  
				}
				else
				{
					admin.ajaxRequest({
						mguniqueurl: 'action/addSlide',
						pluginHandler: 'slider-images',
						type: 'img',
						img: sliderImagesModule.lastUploadImg,
						id: id
					}, function(response) {
						admin.indication(response.status, response.msg);
						if(id != '') sliderImagesModule.drawRow(response.data);
					});
					admin.closeModal($('.b-modal'));  
				}
			});

			$('.type-img').hide();
		},
		showModal: function(type, id) {
			switch (type) 
			{
				case 'add':{
			        sliderImagesModule.clearField();
			        $('#modalTitle').text('Добавление слайда');           
			        break;
			    }
			    case 'edit': {
			    	sliderImagesModule.clearField(); 
			    	$('#modalTitle').text('Редактирование слайда');
			    	sliderImagesModule.editSlide(id);          
			    	break;
			    }
			}
		    admin.openModal($('.b-modal'));      
		    $('.b-modal textarea').ckeditor(); 
		},
		editSlide: function(id)
		{
			admin.ajaxRequest({
			    mguniqueurl: 'action/getSlide',
			    pluginHandler: 'slider-images',
			    type: 'img',
			    id:id
			},
			sliderImagesModule.fillFileds()
			);
		},
		fillFileds:function() {
		  return (function(response)
		  {
		  	if(response.data.name_link != null)
		  	{
		  		sliderImagesModule.changeType('desc');
		  		$('input[name="name_link"]').val(response.data.name_link);
		  		$('input[name="url_link"]').val(response.data.url_link);
		  		$('div.previewImgBox img').attr('src', response.data.img);
		  		$('textarea[name="desc"]').val(response.data.desc);
		  		$('.currentImg').val(response.data.name_img);
		  	}
		  	else
		  	{
		  		sliderImagesModule.changeType('img');
		  		$('div.previewImgBox img').attr('src', response.data.img);
		  		$('.currentImg').val(response.data.name_img);
		  	}
		  	$('.save-button').attr('id',response.data.id);
		  })

		},
		clearField: function() {
			sliderImagesModule.changeType('desc');
			$('input[name="name_link"]').val('');
			$('input[name="url_link"]').val('');
			$('textarea[name="desc"]').val('');
			$('.save-button').attr('id','');
			$('.previewImgBox img').attr('src', 'http://placehold.it/1280x340');
			$('.currentImg').val('');
		},
		changeType: function(type) {
			 switch (type) {
			  case 'desc':
			    {
			      $('.type-desc').show();
			      $('.type-img').hide();
			      $('.section-slider-action .slide-editor select[name=type] option[value=img]').prop('selected','selected');
			      break;
			    }
			  case 'img':
			    {
			      $('.type-desc').hide();
			      $('.type-img').show();
			      $('.type-desc input, .type-desc textarea').val('');
			      $('.section-slider-action .slide-editor select[name=type] option[value=html]').prop('selected','selected');		     
			      break;
			    }
			  default:
			    {
			      break;
			    }
			}
		},
		drawRow: function(data)
		{
			var html = '<tr data-id="'+data.row.id+'">';
			if(data.row.type == 'img')
			{
				html += 'td'
				html += '<td>'+data.row.sort+'</td>';
				html += '<td class="type"><img src="'+data.row.img+'"></td>';
				html += '<td>------------</td>';
				html += '<td>------------</td>';
				html += '<td class="actions">';
				    html += '<ul class="action-list">'
				        html += '<li class="edit-row" data-id="'+data.row.id+'">';
				            html += '<a class="tool-tip-bottom" href="javascript:void(0);"></a>';
				        html += '</li>';
				        html += '<li class="visible tool-tip-bottom active" data-id="'+data.row.id+'">';
				            html += '<a href="javascript:void(0);"></a>';
				        html += '</li>';
				        html += '<li class="delete-row" data-id="'+data.row.id+'">';
				            html +='<a class="tool-tip-bottom" href="javascript:void(0);"></a>';
				        html +='</li>';
				    html +='</ul>';
				html +='</td>';
			}
			else
			{
				html += 'td'
				html += '<td>'+data.row.sort+'</td>';
				html += '<td class="type"><img src="'+data.row.img+'"></td>';
				html += '<td><a href="'+admin.SITE+'/'+data.row.url_link+'" class="activity-product-true" target="_blank">'+data.row.name_link+'</a></td>';
				html += '<td><p>'+data.row.desc+'</p></td>';
				html += '<td class="actions">';
				    html += '<ul class="action-list">'
				        html += '<li class="edit-row" data-id="'+data.row.id+'">';
				            html += '<a class="tool-tip-bottom" href="javascript:void(0);"></a>';
				        html += '</li>';
				        html += '<li class="visible tool-tip-bottom active" data-id="'+data.row.id+'">';
				            html += '<a href="javascript:void(0);"></a>';
				        html += '</li>';
				        html += '<li class="delete-row" data-id="'+data.row.id+'">';
				            html +='<a class="tool-tip-bottom" href="javascript:void(0);"></a>';
				        html +='</li>';
				    html +='</ul>';
				html +='</td>';
			}
			html += '</tr>';
			$('.entity-table-tbody').append(html);
		},
		visibleEntity: function(id, val)
		{
			admin.ajaxRequest({
				mguniqueurl: 'action/visibleEntity',
				pluginHandler: 'slider-images',
				id: id,
				invisible: val,
			}, function(response) {
				admin.indication(response.status, response.msg);
			});
		},
		deleteEntity: function(id)
		{
			if(!confirm(lang.DELETE+'?'))
			{
			  return false;
			}

			admin.ajaxRequest({
				mguniqueurl: 'action/deleteEntity',
				pluginHandler: 'slider-images',
				id: id          
			}, function(response){
				admin.indication(response.status, response.msg);
				$('.entity-table-tbody tr[data-id='+id+']').remove();
				if($('.entity-table-tbody tr').length == 0)
				{
					var html ='<tr class="no-results">\
					  <td colspan="3" align="center">'+sliderImagesModule.lang['ENTITY_NONE']+'</td>\
					</tr>';
					$(".entity-table-tbody").append(html);
				};
			})
		}
	}
})();

sliderImagesModule.init();