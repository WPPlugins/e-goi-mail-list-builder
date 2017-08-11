	<?php $EgoiMailListBuilder = get_option('EgoiMailListBuilderObject'); ?>
	<div class='wrap'>
	<div id="icon-egoi-mail-list-builder-settings" class="icon32"></div>
	<h2>Settings</h2>
	<?php require('donate.php'); ?>
	<?php if($EgoiMailListBuilder->isAuthed())	{
		if(isset($_POST['egoi_mail_list_builder_settings_save'])) {
			//$EgoiMailListBuilder->hide_subscribe = (isset($_POST['egoi_mail_list_builder_settings_hide_subscribe'])) ? true : false;
			$EgoiMailListBuilder->subscribe_enable = (isset($_POST['egoi_mail_list_builder_settings_comments'])) ? true : false;
			$EgoiMailListBuilder->subscribe_text = $_POST['egoi_mail_list_builder_settings_text'];
			$EgoiMailListBuilder->subscribe_list = $_POST['egoi_mail_list_builder_settings_list'];
			if($_POST['egoi_mail_list_builder_settings_list'] == -1) {
				$EgoiMailListBuilder->subscribe_enable = false;
			}
			$EgoiMailListBuilder->double_opt_in = (isset($_POST['egoi_mail_list_builder_settings_double_opt_in'])) ? true : false;
			update_option('EgoiMailListBuilderObject',$EgoiMailListBuilder);
		}
		$result = $EgoiMailListBuilder->getLists();
		update_option('EgoiMailListBuilderObject',$EgoiMailListBuilder);
		egoi_mail_list_builder_admin_notices();
	?>
	<form name='egoi_mail_list_builder_settings_form' method='post' action='<?php echo $_SERVER['REQUEST_URI']; ?>'>
	<table class="form-table">
		<tr>
			<th colspan="2">
				<h3>"Post comment" section</h3>
			</th>
		</tr>
		<tr>
			<th>
				<label for="egoi_mail_list_builder_settings_comments">Add a "Sign me up" checkbox</label>
			</th>
			<td>
				<input type='checkbox' size='60' name='egoi_mail_list_builder_settings_comments' <?php if($EgoiMailListBuilder->subscribe_enable) echo "checked";?>/>
			</td>
		</tr>
		<tr>
			<th>
				<label for="egoi_mail_list_builder_settings_text">Checkbox text</label>
			</th>
			<td>
				<input type='text' size='60' name='egoi_mail_list_builder_settings_text'  value='<?php echo $EgoiMailListBuilder->subscribe_text; ?>'/>
			</td>
		</tr>
		<tr>
			<th>
				<label for="egoi_mail_list_builder_settings_list">Mailing list</label>
			</th>
			<td>
				<select name='egoi_mail_list_builder_settings_list'>
					<option value="-1" selected>Select a List</option>
					<?php
					for($x = 0;$x < count($result); $x++) {	?>
						<option value='<?php echo $result[$x]['listnum']; ?>' <?php if($result[$x]['listnum'] == $EgoiMailListBuilder->subscribe_list){ echo "selected"; } ?>><?php echo $result[$x]['title']; ?></option>
					<?php }	?>
				</select>
			</td>
		</tr>
		<tr>
			<th colspan="2">
				<h3>Shortcode section</h3>
			</th>
		</tr>
		<tr>
			<td colspan="2">
				<p>Select the widget you want starting on the index 1, top to bottom, from the new sidebar called <a href="<?php echo admin_url('widgets.php'); ?>">'Egoi Widget Shortcode Area'</a></p>
				<i>shortcode use case: [egoi_subscribe widget_index="1"]</i>
			</td>
		</tr>
		<tr>
			<th colspan="2">
				<h3>General settings</h3>
			</th>
		</tr>
		<tr>
			<th>
				<label for="egoi_mail_list_builder_settings_double_opt_in">Enable Single Opt-In</label>
			</th>
			<td>
				<input type='checkbox' size='60' name='egoi_mail_list_builder_settings_double_opt_in' <?php if($EgoiMailListBuilder->double_opt_in == 1) echo "checked";?>/>
			</td>
		</tr>
		<!--<tr>
			<td>
				<label for="egoi_mail_list_builder_settings_hide_subscribe">Hide Subscribe Check Box</label>
			</td>
			<td>
				<input type='checkbox' size='60' name='egoi_mail_list_builder_settings_hide_subscribe' <?php //if($EgoiMailListBuilder->hide_subscribe == 1) echo "checked";?>/>
			</td>
		</tr>-->
		<tr>
			<th colspan="2">
				<input type="submit" class='button-primary' name="egoi_mail_list_builder_settings_save" id="egoi_mail_list_builder_settings_save" value="Save" />
			</th>
		</tr>
	</table>


	</form>
	<?php }	?>