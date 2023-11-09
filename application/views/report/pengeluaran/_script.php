<script>
//globals
var cariForm;

function convertToRupiah(angka)
{
	var rupiah = '';		
	var angkarev = angka.toString().split('').reverse().join('');
	for(var i = 0; i < angkarev.length; i++) if(i%3 == 0) rupiah += angkarev.substr(i,3)+'.';
	return rupiah.split('',rupiah.length-1).reverse().join('');
}

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

function initCariForm(){
    //validator
    var validator = $("#form_bukukas").validate({
        ignore:[],
        rules: {
            area: {
                required: true,
            },
            unit: {
                required: true,
            }
        },
        invalidHandler: function(event, validator) {   
            KTUtil.scrollTop();
        }
    });   

    $('#area').select2({ placeholder: "Please select a area", width: '100%' });
    $('#unit').select2({ placeholder: "Please select a Unit", width: '100%' });
    $('#category').select2({ placeholder: "Please select a Unit", width: '100%' });

    //events
    $('#btncari').on('click',function(){
        $('.rowappend').remove();
        var area = $('[name="area"]').val();
        var unit = $('[name="id_unit"]').val();
        var category = $('#category').val();
		var dateStart = $('[name="date-start"]').val();
		var dateEnd = $('[name="date-end"]').val();
        KTApp.block('#form_bukukas .kt-portlet__body', {});
		$.ajax({
			type : 'GET',
			url : "<?php echo base_url("api/report/pengeluaran"); ?>",
			dataType : "json",
			data:{area,id_unit:unit,category,dateEnd,dateStart},
			success : function(response,status){
				KTApp.unblockPage();
				//if(response.status == true){
					var template = '';
                    var total=0;
                    var no = 0;
					$.each(response.data, function (index, data) {
                    no++;
                    var date = moment(data.date).format('DD-MM-YYYY');
                    template +='<tr class="rowappend">';
                    template +='<td class="text-center">'+no+'</td>';
                    template +='<td class="text-left">'+data.area+'</td>';
                    template +='<td class="text-left">'+data.name+'</td>';
                    template +='<td class="text-center">'+date+'</td>';
                    template +='<td class="text-left">'+data.no_perk+'</td>';
                    template +='<td class="text-left">'+data.description+'</td>';
                    template +='<td class="text-right">'+convertToRupiah(data.amount)+'</td>';
                    template +='</tr>';
                    total +=  parseInt(data.amount);
					});
                    template += '<tr class="rowappend">';
                    template +='<td colspan="4" class="text-right"><b>Total</b></td>';                    
                    template +='<td class="text-right"><b>'+convertToRupiah(total)+'</b></td>';
                    template +='</tr>';
					$('.kt-section__content #tblmodalkerjapusat').append(template);
				//}
			},
			error: function (jqXHR, textStatus, errorThrown){
				KTApp.unblockPage();
			},
			complete:function () {
				KTApp.unblock('#form_bukukas .kt-portlet__body', {});
			}
		});
    })

    $('#btncari_weekly').on('click',function(){
        $('.rowappend').remove();
        var area = $('[name="area"]').val();
        var unit = $('[name="id_unit"]').val();
        var category = $('#category').val();
		var dateEnd = $('[name="date-end"]').val();
        KTApp.block('#form_bukukas .kt-portlet__body', {});
		$.ajax({
			type : 'GET',
			url : "<?php echo base_url("api/report/pengeluaran/weekly"); ?>",
			dataType : "json",
			data:{area:area,id_unit:unit,category:category,dateEnd:dateEnd},
			success : function(response,status){
				//KTApp.unblockPage();
                //console.log(category);
				//if(response.status == true){
				var body = '';
				var head = '';
				var int = 0;
				var summary = [];
				var foot = '';
				$.each(response.data, function (index, data) {
					if(index > 0){
						body += '<tr>';
						body += '<td>'+int+'</td>'
                        body += '<td>'+data.area+'</td>'
						body += '<td>'+data.name+'</td>'
						$.each(data.dates, function (index, date) {
							body += '<td class="text-right">'+convertToRupiah(date)+'</td>';
							if(summary[index]){
								summary[index] = parseInt(summary[index]) + parseInt(date);
							}else{
								summary[index] = parseInt(date);
							}
						});
						body += '</tr>';
					}else{
						head += '<tr>';
						head += '<th>'+data.no+'</th>'
						head += '<th>'+data.area+'</th>'
						head += '<th>'+data.unit+'</th>'
						$.each(data.dates, function (index, date) {
							head += '<th class="text-right">'+date+'</th>';
						})
						head += '</tr>';
					}
					int++;
				});
                foot += '<tr>';
				foot += '<td colspan="3" class="text-right">Total</td>'
				$.each(summary, function (index, date) {
					foot += '<td class="text-right"><b>'+convertToRupiah(date)+'</b></td>';
				});
				foot += '</tr>';

                $('.table').find('tbody').find('tr').remove();
				$('.table').find('thead').find('tr').remove();
				$('.table').find('tfoot').find('tr').remove();
				$('.table').find('thead').html(head);
				$('.table').find('tbody').html(body);
				$('.table').find('tfoot').html(foot);
                    
				//}
			},
			error: function (jqXHR, textStatus, errorThrown){
				KTApp.unblockPage();
			},
			complete:function () {
				KTApp.unblock('#form_bukukas .kt-portlet__body', {});
			}
		});
    })

    $('#btncari_monthly').on('click',function(){
        $('.rowappend').remove();
        var area = $('[name="area"]').val();
        var unit = $('[name="id_unit"]').val();
        var category = $('#category').val();
		var dateEnd = $('[name="date-end"]').val();
        KTApp.block('#form_bukukas .kt-portlet__body', {});
		$.ajax({
			type : 'GET',
			url : "<?php echo base_url("api/report/pengeluaran/monthly"); ?>",
			dataType : "json",
			data:{area:area,id_unit:unit,category:category,dateEnd:dateEnd},
			success : function(response,status){
				//KTApp.unblockPage();
                console.log(category);
				//if(response.status == true){
					var template = '';
                    var total=0;
                    var totalperk=0;
                    var no = 0;
					$.each(response.data, function (index, data) {
                        no++;
                        var month = moment(dateEnd).format('MMMM');
                        template +='<tr class="rowappend" bgcolor="#e6e600">';
                        template +='<td class="text-center"><b>'+no+'</b></td>';
                        template +='<td class="text-left"><b>'+data.area+'</b></td>';
                        template +='<td class="text-left"><b>'+data.name+'</b></td>';
                        template +='<td class="text-center"><b>'+month+'</b></td>';
                        template +='<td class="text-right"><b>'+convertToRupiah(data.amount)+'</b></td>';
                        template +='</tr>';
                        total +=  parseInt(data.amount);
                        $.each(data.perk, function (index, perk) {
                            template +='<tr class="rowappend">';
                            template +='<td class="text-right" colspan="4">'+perk.name_perk+'</td>';
                            template +='<td class="text-right">'+convertToRupiah(perk.amount)+'</td>';
                            template +='</tr>';
                            totalperk += parseInt(perk.amount);
                        });
                        // template += '<tr class="rowappend" bgcolor="#e6e600">';
                        // template +='<td class="text-right" colspan="4"><b>Total</b></td>';
                        // template +='<td class="text-right"><b>'+convertToRupiah(totalperk)+'</b></td>';
                        // template +='</tr>';
					});
                    template += '<tr class="rowappend">';
                    template +='<td colspan="4" class="text-right"><b>Total</b></td>';                    
                    template +='<td class="text-right"><b>'+convertToRupiah(total)+'</b></td>';
                    template +='</tr>';
					$('.kt-section__content #tblmodalkerjapusat').append(template);
				//}
			},
			error: function (jqXHR, textStatus, errorThrown){
				KTApp.unblockPage();
			},
			complete:function () {
				KTApp.unblock('#form_bukukas .kt-portlet__body', {});
			}
		});
    })
    
    return {
        validator:validator
    }
}


jQuery(document).ready(function() {     
    initCariForm();  
    //initGetUnit(); 
});

$('[name="area"]').on('change',function(){
        var area = $('[name="area"]').val();
        var units =  $('[name="id_unit"]');
        var url_data = $('#url_get_unit').val() + '/' + area;
        $.get(url_data, function (data, status) {
            var response = JSON.parse(data);
            if (status) {
                $("#unit").empty();
				var opt = document.createElement("option");
				opt.value = "0";
				opt.text = "All";
				units.append(opt);
                for (var i = 0; i < response.data.length; i++) {
                    var opt = document.createElement("option");
                    opt.value = response.data[i].id;
                    opt.text = response.data[i].name;
                    units.append(opt);
                }
            }
        });
});
var type = $('[name="area"]').attr('type');
if(type == 'hidden'){
    $('[name="area"]').trigger('change');
}

$('[name="cabang"]').on('change',function(){
	var cabang = $('[name="cabang"]').val();
	var units =  $('[name="id_unit"]');
	var url_data = $('#url_get_units').val() + '/' + cabang;
	$.get(url_data, function (data, status) {
		var response = JSON.parse(data);
		if (status) {
			$("#unit").empty();
			units.append('<option value="0">All</option>');
			for (var i = 0; i < response.data.length; i++) {
				var opt = document.createElement("option");
				opt.value = response.data[i].id;
				opt.text = response.data[i].name;
				units.append(opt);
			}
		}
	});
});

var typecabang = $('[name="cabang"]').attr('type');
if(typecabang == 'hidden'){
	$('[name="cabang"]').trigger('change');
}

</script>
