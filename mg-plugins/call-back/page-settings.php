<div class="section-<?=$pluginName ?>">
	<div class="blocks-plugin">
		<h3>Заявки</h3>
		  <div class="wrapper-entity-setting">
		    <div class="clear"></div>
		    <div class="entity-table-wrap">
		      <div class="clear"></div>
		      <div class="entity-settings-table-wrapper">
		        <table class="widget-table">
		          <thead>
		            <tr>
		              <th style="width:40px">№</th>
		              <th style="width:100px; text-align: center;">Имя</th>
		              <th style="width:100px; text-align: center;">Номер телефона</th>
		              <th style="width:100px; text-align: center;">Комментарий</th>
		              <th style="width:100px; text-align: center;">Дата заявки</th>
		              <th style="width:100px;">Действия</th>
		            </tr>
		          </thead>
		          <tbody class="entity-table-tbody"> 
		            <?php if (empty($entity)): ?>
		              <tr id="no-results">
		                <td colspan="6" align="center"><?=$lang['ENTITY_NONE']; ?></td>
		              </tr>
		                <?php else: ?>
		                  <?php foreach ($entity as $row): ?>
		                  <tr data-id="<?=$row['id']; ?>">
		                    <td><?=$row['id']; ?></td>
		                    <td class="type">                                  
		                      <span class='activity-product-true'> <?=$row['name'] ?></span>   
		                    </td>
		                    <td>
		                      <?=$row['phone']; ?>
		                    </td>
		                    <td>
		                    	<? if(empty($row['comment'])): ?>
		                    	------
		                    	<? else: ?>
		                    	<?=$row['comment']; ?>
		                    	<? endif; ?>
		                    </td>
		                    <td>
		                    	<?=$row['time']; ?>
		                    </td>
		                    <td class="actions">
		                      <ul class="action-list">
		                        <li class="visible tool-tip-bottom  <?=($row['invisible']) ? 'active' : '' ?>" 
		                            data-id="<?=$row['id'] ?>" 
		                            title="<?=($row['invisible']) ? $lang['ACT_V_ENTITY'] : $lang['ACT_UNV_ENTITY']; ?>">
		                          <a href="javascript:void(0);"></a>
		                        </li>
		                        <li class="delete-row" data-id="<?=$row['id'] ?>">
		                          <a class="tool-tip-bottom" href="javascript:void(0);" title="<?=$lang['DELETE']; ?>"></a>
		                        </li>
		                      </ul>
		                    </td>
		                  </tr>
		                <?php endforeach; ?>
		              <?php endif; ?>
		          </tbody>
		        </table>
		      </div>
		    </div>
		    <div class="clear"></div>
		  
		    <?=$pagination ?>
		    <div class="clear"></div>
		  </div>
	</div>
	<div class="blocks-plugin settings">
		<div class="toggleLink">
			<a href="javascript:void(0);">Настройки плагина</a>
		</div>
		<div class="content-settings">
			<div class="setting-box clearfix">
				<p class="title-stg">Отправка заявок на email</p>
				<div class="content-setting">
					<div class="form-el">
						<label for="chbEmail">Включить</label>
						<input type="checkbox" id="chbEmail" name="chbEmail" <?=$config['send_mail'] ? 'checked' : '';?>>
					</div>
					<div class="form-el">
						<label for="txtEmail">Email</label>
						<input type="text" id="txtEmail" name="txtEmail" value="<?=$config['email_address'];?>" <?=!$config['send_mail'] ? 'disabled' : '';?>>
					</div>
					<button class="save-button"><span>Сохранить</span></button>
					<div class="clear"></div>
				</div>
			</div>
		</div>
	</div>
  </div>