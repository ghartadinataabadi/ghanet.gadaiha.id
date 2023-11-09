<script>

var AlertUtil;

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

function clear(){
    $('#old_pwd').val("");
    $('#new_pwd').val("");
    $('#confirm_pwd').val("");
}

function changepwd(){
    //alert('test');
    $('#modal_changepwd').modal();
    clear();
    var id = $('[name="id"]').val(); 
    $('#userid').val(id);

    var validator = $( "#form_add" ).validate({
        ignore:[],
        rules: {
            old_pwd: {
                required: true,
            },
            new_pwd: {
                required: true,
            },
            confirm_pwd: {
                required: true,
            }
            
        },
        invalidHandler: function(event, validator) {   
            KTUtil.scrollTop();
        }
    });   

    //events
    $("#btn_add_submit").on("click",function(){
        var isValid = $( "#form_add" ).valid();
        if(isValid){
            KTApp.block('#modal_changepwd .modal-content', {});
            $.ajax({
                type : 'POST',
                url : "<?php echo base_url("api/profile/update"); ?>",
                data : $('#form_add').serialize(),
                dataType : "json",
                success : function(data,status){
                    KTApp.unblock('#modal_changepwd .modal-content');
                    if(data.status == true){
                    // datatable.reload();
                        $('#modal_changepwd').modal('hide');
                        AlertUtil.showSuccess(data.message,5000);
                    }else{
                        AlertUtil.showFailedDialogAdd(data.message);
                    }                
                },
                error: function (jqXHR, textStatus, errorThrown){
                    KTApp.unblock('#modal_changepwd .modal-content');
                    AlertUtil.showFailedDialogAdd("Cannot communicate with server please check your internet connection");
                }
            });  
        }
    })

    $('#modal_changepwd').on('hidden.bs.modal', function () {
       validator.resetForm();
    })

    return {
        validator:validator
    }

}

function PersonalInfo(){
       var id = $('[name="id"]').val();        
        console.log(id);
		$.ajax({
			type : 'GET',
			url : "<?php echo base_url("api/profile"); ?>",
			dataType : "json",
			data:{id:id},
			success : function(response,status){
				KTApp.unblockPage();
				if(response.status == true){
                    console.log(response.data.id);
                    $('.kt-widget__username').text((response.data.fullname).toUpperCase());
                    $('.kt-widget__desc').text((response.data.position).toUpperCase());
                    $('.email').text(response.data.email);
                    $('.phone').text(response.data.mobile);
                    $('.area').text(response.data.area);
                    $('.unit').text(response.data.unit_name);
                    $('[name="nik"]').val(response.data.nik); 
                    $('[name="fullname"]').val(response.data.fullname);                     
                    $('[name="areas"]').val(response.data.area); 
                    $('[name="units"]').val(response.data.unit_name); 
                    $('[name="phones"]').val(response.data.mobile); 
                    $('[name="emails"]').val(response.data.email); 
                    $('[name="address"]').val(response.data.address); 
				}
			},
			error: function (jqXHR, textStatus, errorThrown){
				//KTApp.unblockPage();
			},
			complete:function () {
				//KTApp.unblock('#form_bukukas .kt-portlet__body', {});
			}
		});
}

jQuery(document).ready(function() {
    initAlert();
    PersonalInfo();
});

</script>
