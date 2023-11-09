<script type="text/javascript">
	$('.can-access').on('change', function () {
		var id_menu = $(this).data('menu');
		var id_level = $(this).data('level');
		var can_access = $(this).val();
		$.ajax({
			type : 'POST',
			url :"<?php echo base_url('api/site-settings/privileges/insert');?>",
			data : {
				id_menu:id_menu,
				id_level:id_level,
				can_access:can_access
			},
			dataType : "json",
			success : function(response,status){
				console.log(response);
			}
		});
	});
</script>
