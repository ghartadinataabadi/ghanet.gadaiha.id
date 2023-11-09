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

    $('#area').select2({ placeholder: "Select a area", width: '100%' });
    $('#unit').select2({ placeholder: "Select a Unit", width: '100%' });
    $('#status').select2({ placeholder: "Select a Status", width: '100%' });
    $('#nasabah').select2({ placeholder: "Select a Customers", width: '100%' });
    $('#sort_method').select2({ placeholder: "Select a Sort", width: '100%' });
    $('#sort_by').select2({ placeholder: "Select a Sort", width: '100%' });
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
        console.log(unit);
        KTApp.block('#form_bukukas .kt-portlet__body', {});
		$.ajax({
			type : 'GET',
			url : "<?php echo base_url("api/transactions/mortages/report"); ?>",
			dataType : "json",
			data:{area:area,id_unit:unit,statusrpt:statusrpt,nasabah:nasabah,dateStart:dateStart,dateEnd:dateEnd,permit:permit, sort_by, sort_method},
			success : function(response,status){
				KTApp.unblockPage();
				if(response.status == true){
					var template = '';
					var no = 1;
					var amount = 0;
                    var admin = 0;
                    var totcicilan = 0;
                    var jumcicilan=0;
                    var saldocicilan=0;
					var status = "";
					$.each(response.data, function (index, data) {
						template += "<tr class='rowappend'>";
						template += "<td class='text-center'>"+no+"</td>";
						template += "<td class='text-center'>"+data.unit_name+"</td>";
						template += "<td class='text-center'>"+data.nic+"</td>";
						template += "<td class='text-center'><a href='#' class='viewcicilan' data-toggle='modal' data-target='#modal_cicilan' data-id="+data.no_sbk+" data-unit="+data.id_unit+" data-up="+data.amount_loan+">"+data.no_sbk+"</a></td>";
						template += "<td class='text-center'>"+moment(data.date_sbk).format('DD-MM-YYYY')+"</td>";
						template += "<td class='text-center'>"+moment(data.deadline).format('DD-MM-YYYY')+"</td>";
						template += "<td>"+data.customer_name+"</td>";
						template += "<td class='text-center'>"+data.capital_lease+"</td>";
						template += "<td class='text-right'>"+convertToRupiah(data.estimation)+"</td>";
						template += "<td class='text-right'>"+convertToRupiah(data.amount_admin)+"</td>";
						template += "<td class='text-right'>"+convertToRupiah(data.amount_loan)+"</td>";
                        if(data.status_transaction=="L"){ status="Lunas";}else if(data.status_transaction=="N"){status="Aktif";}
                        template += "<td class='text-center'>"+data.cicilan+"</td>";
                        if(data.status_transaction=="L"){ saldocicilan="0";}else if(data.status_transaction=="N"){jumcicilan= parseInt(data.cicilan) * parseInt(data.installment); saldocicilan=parseInt(data.amount_loan)-parseInt(jumcicilan);}

                        template += "<td class='text-right'>"+convertToRupiah(saldocicilan)+"</td>";
                        template += "<td class='text-center'>"+status+"</td>";
                        template += "<td class='text-right'>";
                        if(data.description_1!=null){template += "- " + data.description_1;}
                        if(data.description_2!=null){template += "<br>- " + data.description_2;}
                        if(data.description_3!=null){template += "<br>- " + data.description_3;}
                        if(data.description_4!=null){template += "<br>- " + data.description_4;}
                        template +="</td>";
						template += '</tr>';
						no++;
						amount      += parseInt(data.amount_loan);
                        admin       += parseInt(data.amount_admin);
                        totcicilan  +=parseInt(saldocicilan);
					});
					template += "<tr class='rowappend'>";
					template += "<td colspan='9' class='text-right'>Total</td>";
					template += "<td class='text-right'>"+convertToRupiah(admin)+"</td>";
					template += "<td class='text-right'>"+convertToRupiah(amount)+"</td>";
					template += "<td class='text-right'></td>";
					template += "<td class='text-right'>"+convertToRupiah(totcicilan)+"</td>";
					template += "<td class='text-right'></td>";
					template += "<td class='text-right'></td>";
					template += '</tr>';
					$('.kt-section__content .table').append(template);
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
            console.log(response);
            if (status) {
                $("#unit").empty();
				var opt = document.createElement("option");
				opt.value = "0";
				opt.text = "All"
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

function initGetNasabah(){
    $("#unit").on('change',function(){
        var area = $('[name="area"]').val();
        var unit = $('#id_unit').val(); 
        var customers =  document.getElementById('nasabah');     
        //alert(unit);
        $.ajax({
			type : 'GET',
			url : "<?php echo base_url("api/datamaster/units/get_customers_gc_byunit"); ?>",
			dataType : "json",
			data:{unit:unit,area:area},
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
			url : "<?php echo base_url("api/datamaster/units/get_customers_gc_byunit"); ?>",
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

function popView(el){
    $('.rowappend_mdl').remove();
    var nosbk = $(el).attr('data-id');
    var unit = $(el).attr('data-unit');
    var up = $(el).attr('data-up');
    //alert(unit);
    KTApp.block('#form_bukukas .kt-portlet__body', {});
    $.ajax({
			type : 'GET',
			url : "<?php echo base_url("api/transactions/repaymentmortage/get_byid"); ?>",
			dataType : "json",
			data:{nosbk:nosbk,unit:unit},
			success : function(response,status){
				KTApp.unblockPage();
				if(response.status == true){
					var template = '';
					var no = 1;
					var saldo = up;
					var cicilan = 0;
					$.each(response.data, function (index, data) {
                        if(data.date_installment =="1970-01-01"){ cicilan=saldo; }else{ cicilan=data.amount;}
                        saldo -= cicilan;
						template += "<tr class='rowappend_mdl'>";
						template += "<td class='text-center'>"+no+"</td>";
                        template += "<td class='text-center'>"+data.no_sbk+"</td>";
						template += "<td class='text-center'>"+moment(data.date_kredit).format('DD-MM-YYYY')+"</td>";
                        if(data.date_installment ==null || data.date_installment =="1970-01-01"){ var datePayment=" Pelunasan"; }else{ var datePayment = moment(data.date_installment).format('DD-MM-YYYY');}
						template += "<td class='text-center'>"+datePayment+"</td>";
						template += "<td class='text-right'>"+convertToRupiah(cicilan)+"</td>";
                        template += "<td class='text-right'>"+convertToRupiah(data.sewa_modal)+"</td>";
						template += "<td class='text-right'>"+convertToRupiah(saldo)+"</td>";
						template += '</tr>';
						no++;
					});
					$('.kt-portlet__body #mdl_vwcicilan').append(template);
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

jQuery(document).ready(function() {
    initCariForm();
    initGetNasabah();
    initUnitNasabah();

    $(document).on("click", ".viewcicilan", function () {
                var el = $(this);
                popView(el);
    });
});

</script>
