<script type="text/javascript">
	function initAlert(){
		AlertUtil = {
			showSuccess : function(message,timeout){
				$("#success_message").html(message);
				if(timeout != undefined){
					setTimeout(function(){
						$("#success_alert_dismiss").trigger("click");
					},timeout)
				}
				$("#success_alert").show();
				KTUtil.scrollTop();
			},
			hideSuccess : function(){
				$("#success_alert_dismiss").trigger("click");
			},
			showFailed : function(message,timeout){
				$("#failed_message").html(message);
				if(timeout != undefined){
					setTimeout(function(){
						$("#failed_alert_dismiss").trigger("click");
					},timeout)
				}
				$("#failed_alert").show();
				KTUtil.scrollTop();
			},
			hideFailed : function(){
				$("#failed_alert_dismiss").trigger("click");
			},
			showFailedDialogAdd : function(message,timeout){
				$("#failed_message_add").html(message);
				if(timeout != undefined){
					setTimeout(function(){
						$("#failed_alert_dismiss_add").trigger("click");
					},timeout)
				}
				$("#failed_alert_add").show();
			},
			hideSuccessDialogAdd : function(){
				$("#failed_alert_dismiss_add").trigger("click");
			},
			showFailedDialogEdit : function(message,timeout){
				$("#failed_message_edit").html(message);
				if(timeout != undefined){
					setTimeout(function(){
						$("#failed_alert_dismiss_edit").trigger("click");
					},timeout)
				}
				$("#failed_alert_edit").show();
			},
			hideSuccessDialogAdd : function(){
				$("#failed_alert_dismiss_edit").trigger("click");
			}
		}
		$("#failed_alert_dismiss").on("click",function(){
			$("#failed_alert").hide();
		})
		$("#success_alert_dismiss").on("click",function(){
			$("#success_alert").hide();
		})
		$("#failed_alert_dismiss_add").on("click",function(){
			$("#failed_alert_add").hide();
		})
		$("#failed_alert_dismiss_edit").on("click",function(){
			$("#failed_alert_edit").hide();
		})
	}

	$('.summary').on('change', function () {
		var summary = 0;
		$('.summary').each(function (index) {
			if($(this).val() == ''){
				$(this).val(0);
			}else{
				summary += parseInt($(this).val()) * parseInt($(this).parents('.form-group').find('.amount').val());
			}
		});
		$('.total').val(summary);
	});

	$(document).ready(function () {
		$('.summary').trigger('change');
		initAlert();
		var id = $('[name="id"]').val();
		getData(id);
	});

	function getData(id){
		if(id){
			$.ajax({
				type : 'GET',
				url : "<?php echo base_url('api/datamaster/bookcash/show/');?>"+id,
				data : $(this).serialize(),
				dataType : "json",
				success : function(response,status){
					$('[name="id"]').val(response.data.id);
					$('[name="id_unit"]').val(response.data.id_unit);
					$('[name="os"]').val(response.data.os);
					$('[name="date"]').val(response.data.date);
					$.each(response.data.detail, function (index, data) {
						$('[name="fraction['+data.id_fraction_of_money+'][summary]"]').val(data.summary);
					});
				},
				error: function (jqXHR, textStatus, errorThrown){
				}
			});
		}
	}

	$('.kt-form').on('submit', function (e) {
		e.preventDefault();
		var id = $('[name="id"]').val();
		var url;
		if(id){
			url = "<?php echo base_url("api/datamaster/bookcash/update"); ?>";
		}else{
			url = "<?php echo base_url("api/datamaster/bookcash/insert"); ?>";
		}
		$.ajax({
			type : 'POST',
			url : url,
			data : $(this).serialize(),
			dataType : "json",
			success : function(data,status){
				$('#modal_add').find('[name="id"]').val("");
				$('#modal_add').find('[name="level"]').val("");
				KTApp.unblock('#modal_add .modal-content');
				if(data.status == true){
					AlertUtil.showSuccess(data.message,5000);
					location.href = '<?php echo base_url("datamaster/bookcash/");?>';
				}else{
					AlertUtil.showFailedDialogAdd(data.message);
				}
			},
			error: function (jqXHR, textStatus, errorThrown){
				KTApp.unblock('#modal_add .modal-content');
				AlertUtil.showFailedDialogAdd("Cannot communicate with server please check your internet connection");
			}
		});
	});
</script>
