<?php foreach ($inboxes as $inbox):?>
	<div class="kt-inbox__item kt-inbox__item--unread"  data-id="<?php echo $inbox->id;?>" data-type="inbox">
		<div class="kt-inbox__info">
			<div class="kt-inbox__actions">
				<label class="kt-checkbox kt-checkbox--single kt-checkbox--tick kt-checkbox--brand">
					<input type="checkbox" name="checkbox[]" value="<?php echo $inbox->id;?>">
					<span></span>
				</label>
			</div>
			<div class="kt-inbox__sender">
				<span href="#" class="kt-inbox__author"><?php echo $inbox->unit_name;?></span>
			</div>
		</div>
		<div class="kt-inbox__details">
			<div class="kt-inbox__message">
				<?php if($inbox->compose_subject):?>
					<span class="kt-inbox__subject"><?php echo $inbox->compose_subject;?> </span>
				<?php endif;?>
				<?php if($inbox->compose_body):?>
					<span class="kt-inbox__summary"><?php echo $inbox->compose_body;?></span>
				<?php endif;?>
			</div>
		</div>
		<div class="kt-inbox__datetime">
			<?php echo date('d M Y h:i:s A', strtotime($inbox->date_create));?>
		</div>
	</div>
<?php endforeach;?>
