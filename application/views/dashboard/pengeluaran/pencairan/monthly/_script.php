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

    $('#area').select2({ placeholder: "Select Area", width: '100%' });
    $('#unit').select2({ placeholder: "Select Unit", width: '100%' });
    $('#status').select2({ placeholder: "Select a Status", width: '100%' });
    $('#nasabah').select2({ placeholder: "Select a Nasabah", width: '100%' });
    $('#sort_method').select2({ placeholder: "Select a Sort", width: '100%' });
    $('#sort_by').select2({ placeholder: "Select a Sort", width: '100%' });
    $('#no_sbk').select2({ placeholder: "Select a No SBK", width: '100%' });
    //events
    $('#btncari').on('click',function(){
        $('.rowappend').remove();
        var area = $('[name="area"]').val();
        var unit = $('[name="id_unit"]').val();
        var nasabah = $('#nasabah').val();
        var statusrpt = $('#status').val();
		var dateStart = $('[name="date-start"]').val();
		var dateEnd = $('[name="date-end"]').val();
		var permit = $('[name="permit"]').val();
        var sort_by = $('[name="sort_by"]').val();
        var sort_method = $('[name="sort_method"]').val();
        var no_sbk = $('[name="no_sbk"]').val();
        KTApp.block('#form_bukukas .kt-portlet__body', {});
		$.ajax({
			type : 'GET',
			url : "<?php echo base_url("api/transactions/regularpawns/reportpencairan"); ?>",
			dataType : "json",
			data:{no_sbk,area:area,id_unit:unit,statusrpt:statusrpt,nasabah:nasabah,dateStart:dateStart,dateEnd:dateEnd,permit:permit, sort_by, sort_method},
			success : function(response,status){
				KTApp.unblockPage();
				if(response.status == true){
					var template = '';
					var no = 1;
					var amount = 0;
					var admin = 0;
                    var status="";
					$.each(response.data, function (index, data) {
						template += "<tr class='rowappend'>";
						template += "<td class='text-center'>"+no+"</td>";
						template += "<td class='text-left'>"+data.unit+"</td>";
						template += "<td class='text-center'>"+data.nic+"</td>";
						template += "<td class='text-center'>"+data.no_sbk+"</td>";
						template += "<td class='text-center'>"+moment(data.date_sbk).format('DD-MM-YYYY')+"</td>";
                        //template += "<td class='text-center'>"+moment(data.deadline).format('DD-MM-YYYY')+"</td>";
                        //if(data.date_repayment!=null){ var DateRepayment = moment(data.date_repayment).format('DD-MM-YYYY');}else{ var DateRepayment = "-";}
						//template += "<td class='text-center'>"+DateRepayment+"</td>";
						template += "<td>"+data.customer_name+"</td>";
						template += "<td class='text-center'>"+data.capital_lease+"</td>";
						template += "<td class='text-right'>"+convertToRupiah(data.estimation)+"</td>";
						template += "<td class='text-right'>"+convertToRupiah(data.admin)+"</td>";
						template += "<td class='text-right'>"+convertToRupiah(data.amount)+"</td>";
                        // if(data.status_transaction=="L"){ status="Lunas";}
                        // else if(data.status_transaction=="N"){ status="Aktif";}
                        // template += "<td class='text-center'>"+status+"</td>";
                        // template += "<td class='text-right'>";
                        // if(data.description_1!=null){template += "- " + data.description_1;}
                        // if(data.description_2!=null){template += "<br>- " + data.description_2;}
                        // if(data.description_3!=null){template += "<br>- " + data.description_3;}
                        // if(data.description_4!=null){template += "<br>- " + data.description_4;}
                        // template +="</td>";
						template += '</tr>';
						no++;
						amount += parseInt(data.amount);
						admin += parseInt(data.admin);
					});
					template += "<tr class='rowappend'>";
					template += "<td colspan='8' class='text-right'>Total</td>";
					template += "<td class='text-right'>"+convertToRupiah(admin)+"</td>";
					template += "<td class='text-right'>"+convertToRupiah(amount)+"</td>";
					template += "<td class='text-right'></td>";
					template += "<td class='text-right'></td>";
					template += '</tr>';
					$('.kt-section__content table').append(template);
				}
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

function initGetNasabah(){
    $("#unit").on('change',function(){
        var area = $('#area').val();
        var unit = $('#unit').val(); 
        var customers =  document.getElementById('nasabah');   
        var dateStart = $('[name="date-start"]').val();
		var dateEnd = $('[name="date-end"]').val();
        $.ajax({
            type:"GET",
            url:"<?php echo base_url('api/transactions/regularpawns');?>",
            dataType: "JSON",
            data:{unit, dateStart, dateEnd},
            success:function(res){
                if(res.data.length > 0){
                    $("#no_sbk").empty();
                    var option = document.createElement("option");
                    option.value = "0";
                    option.text = "All";
                    $('#no_sbk').append(option);
                    res.data.forEach(data=>{
                        const opt = document.createElement('option');
                        opt.value = data.no_sbk;
                        opt.textContent = `${data.no_sbk}-${data.customer}`;
                        $('#no_sbk').append(opt);
                    })
                }
            }
        })


        //alert(unit);
        $.ajax({
			type : 'GET',
			url : "<?php echo base_url("api/datamaster/units/get_customers_gr_byunit"); ?>",
			dataType : "json",
			data:{unit:unit},
			success : function(response,status){
				KTApp.unblockPage();
				if(response.status == true){
                    $("#nasabah").empty();
                    var option = document.createElement("option");
                    option.value = "all";
                    option.text = "All";
                    customers.appendChild(option);
					$.each(response.data, function (index, data) {
                        //console.log(data);
                        var opt = document.createElement("option");
                        opt.value = data.nik;
                        opt.text = data.name;
                        customers.appendChild(opt);
					});
					
				}
			},
			error: function (jqXHR, textStatus, errorThrown){
				KTApp.unblockPage();
			},
			complete:function () {
				KTApp.unblock('#form_bukukas .kt-portlet__body', {});
			}
		});
       
    });    
}

function initUnitNasabah(){
        var unit=$('[name="id_unit"]').val();
        var customers =  document.getElementById('nasabah');     
        $.ajax({
			type : 'GET',
			url : "<?php echo base_url("api/datamaster/units/get_customers_gr_byunit"); ?>",
			dataType : "json",
			data:{unit:unit},
			success : function(response,status){
				KTApp.unblockPage();
				if(response.status == true){
                    $("#nasabah").empty();
                    var option = document.createElement("option");
                    option.value = "all";
                    option.text = "All";
                    customers.appendChild(option);
					$.each(response.data, function (index, data) {
                        //console.log(data);
                        var opt = document.createElement("option");
                        opt.value = data.nik;
                        opt.text = data.name;
                        customers.appendChild(opt);
					});					
				}
			},
			error: function (jqXHR, textStatus, errorThrown){
				KTApp.unblockPage();
			},
			complete:function () {
				KTApp.unblock('#form_bukukas .kt-portlet__body', {});
			}
		});
}


jQuery(document).ready(function() {
    initCariForm();
    initGetNasabah();
    initUnitNasabah();
});

var type = $('[name="area"]').attr('type');
if(type == 'hidden'){
    $('[name="area"]').trigger('change');
}

</script>
