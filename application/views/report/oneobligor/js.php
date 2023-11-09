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
    $('#ktp').select2({ placeholder: "Select No KTP", width: '100%' });
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
        var ktp = $('[name="ktp"]').val();
		// var dateStart = $('[name="date-start"]').val();
		// var dateEnd = $('[name="date-end"]').val();
		// var permit = $('[name="permit"]').val();
        var sort_by = $('[name="sort_by"]').val();
        // var sort_method = $('[name="sort_method"]').val();
        var cif = $('[name="no_sbk"]').val();
        KTApp.block('#form_bukukas .kt-portlet__body', {});
		$.ajax({
			type : 'GET',
			url : "<?php echo base_url("api/transactions/oneobligor/report"); ?>",
			dataType : "json",
			data:{cif,area:area,id_unit:unit,statusrpt:statusrpt,nasabah:nasabah,ktp,sort_by},
			success : function(response,status){
				KTApp.unblockPage();
				if(response.status == true){
					var template = '';
					var no = 1;
					var noa = 0;
					var up = 0;
                    var status="";
					$.each(response.data, function (index, data) {
                        if(data.address == null){
                            data.address = "";
                        }
                        if(data.city == null){
                            data.city = "";
                        }	
                        if(data.province == null){
                            data.province = "";
                        }
                        if(data.job == null || data.job == 0){
                            data.job = "-";
                        }
						template += "<tr class='rowappend'>";
						template += "<td class='text-center'>"+no+"</td>";
						template += "<td class='text-center'>"+data.unit+"</td>";
						template += "<td class='text-center'>"+data.nic+"</td>";
						template += "<td class='text-left'>"+data.nik+"</td>";
						
						template += "<td>"+data.customer_name+"</td>";
						template += "<td class='text-left'>"+data.address+" "+data.city+" "+data.province+"</td>";
						template += "<td class='text-left'>"+data.job+"</td>";
                        template += "<td class='text-right'><a href='#' class='viewcicilan' data-toggle='modal' data-target='#modal_cicilan' data-nik="+data.nik+" data-cif="+data.nic+" data-unit="+data.id_unit+" data-statusrpt="+statusrpt+" data-nasabah="+data.customer_name+" >"+data.noa+"</a></td>";
						// template += "<td class='text-right'>"+convertToRupiah(data.noa)+"</td>";
						template += "<td class='text-right'>"+convertToRupiah(data.up)+"</td>";
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
						noa += parseInt(data.noa);
						up += parseInt(data.up);
					});
					template += "<tr class='rowappend'>";
					template += "<td colspan='7' class='text-right'><strong>Total</strong></td>";
					template += "<td class='text-right'><strong>"+convertToRupiah(noa)+"</strong></td>";
					template += "<td class='text-right'><strong>"+convertToRupiah(up)+"</strong></td>";
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
        var ktp =  document.getElementById('ktp');   
        var cif = $('#no_sbk').val();
    //    var dateStart = $('[name="date-start"]').val();
	// 	var dateEnd = $('[name="date-end"]').val();
        $.ajax({
            type:"GET",
            url:"<?php echo base_url('api/datamaster/units/get_customers_gr_byunit');?>",
            dataType: "JSON",
            data:{unit},
            success:function(res){
                if(res.data.length > 0){
                    $("#no_sbk").empty();
                    var option = document.createElement("option");
                    option.value = "0";
                    option.text = "All";
                    $('#no_sbk').append(option);
                    res.data.forEach(data=>{
                        const opt = document.createElement('option');
                        opt.value = data.no_cif;
                        opt.textContent = `${data.no_cif}-${data.name}`;
                        $('#no_sbk').append(opt);
                    })
                }
            }
        })


        //alert(unit);
        // $.ajax({
		// 	type : 'GET',
		// 	url : "<?php echo base_url("api/datamaster/units/get_customers_gr_byunit"); ?>",
		// 	dataType : "json",
		// 	data:{unit:unit},
		// 	success : function(response,status){
		// 		KTApp.unblockPage();
		// 		if(response.status == true){
        //             $("#nasabah").empty();
        //             var option = document.createElement("option");
        //             option.value = "all";
        //             option.text = "All";
        //             customers.appendChild(option);
		// 			$.each(response.data, function (index, data) {
        //                 //console.log(data);
        //                 var opt = document.createElement("option");
        //                 opt.value = data.name;
        //                 opt.text = data.name;
        //                 customers.appendChild(opt);
		// 			});
					
		// 		}
		// 	},
		// 	error: function (jqXHR, textStatus, errorThrown){
		// 		KTApp.unblockPage();
		// 	},
		// 	complete:function () {
		// 		KTApp.unblock('#form_bukukas .kt-portlet__body', {});
		// 	}
		// });


        // $.ajax({
		// 	type : 'GET',
		// 	url : "<?php echo base_url("api/datamaster/units/get_customers_bycif"); ?>",
		// 	dataType : "json",
		// 	data:{cif},
		// 	success : function(response,status){
		// 		KTApp.unblockPage();
		// 		if(response.status == true){
        //             $("#ktp").empty();
        //             var option = document.createElement("option");
        //             option.value = "all";
        //             option.text = "All";
        //             ktp.appendChild(option);
		// 			$.each(response.data, function (index, data) {
        //                 //console.log(data);
        //                 var opt = document.createElement("option");
        //                 opt.value = data.nik;
        //                 // opt.text = data.nik;
        //                 opt.textContent = `${data.nik}-${data.name}`;
        //                 ktp.appendChild(opt);
		// 			});
					
		// 		}
		// 	},
		// 	error: function (jqXHR, textStatus, errorThrown){
		// 		KTApp.unblockPage();
		// 	},
		// 	complete:function () {
		// 		KTApp.unblock('#form_bukukas .kt-portlet__body', {});
		// 	}
		// });
       
    });    
}

// function initGetKtp(){
//     $("#cif").on('change',function(){
//         // var area = $('#area').val();
//         var cif = $('#cif').val(); 
//         // var customers =  document.getElementById('nasabah');   
//         var ktp =  document.getElementById('ktp');   
//         // var cif = $('#cif').val();
//     //    var dateStart = $('[name="date-start"]').val();
// 	// 	var dateEnd = $('[name="date-end"]').val();
//         $.ajax({
//             type:"GET",
//             url:"<?php echo base_url('api/datamaster/units/get_customers_bycif');?>",
//             dataType: "JSON",
//             data:{cif},
//             success:function(res){
//                 if(res.data.length > 0){
//                     $("#ktp").empty();
//                     var option = document.createElement("option");
//                     option.value = "0";
//                     option.text = "All";
//                     $('#ktp').append(option);
//                     res.data.forEach(data=>{
//                         const opt = document.createElement('option');
//                         opt.value = data.nik;
//                         opt.textContent = `${data.nik}-${data.name}`;
//                         $('#ktp').append(opt);
//                     })
//                 }
//             }
//         })
//    });    
// }
function initUnitNasabah(){
        var unit=$('[name="id_unit"]').val();
        var cif =  document.getElementById('no_sbk');     
        $.ajax({
			type : 'GET',
			url : "<?php echo base_url("api/datamaster/units/get_customers_gr_byunit"); ?>",
			dataType : "json",
			data:{unit:unit},
			success : function(response,status){
				KTApp.unblockPage();
				if(response.status == true){
                    $("#no_sbk").empty();
                    var option = document.createElement("option");
                    option.value = "all";
                    option.text = "All";
                    cif.appendChild(option);
					$.each(response.data, function (index, data) {
                        //console.log(data);
                        var opt = document.createElement("option");
                        opt.value = data.no_cif;
                        // opt.text = data.no_cif;
                        opt.textContent = `${data.no_cif}-${data.name}`;

                        cif.appendChild(opt);
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
    var nik = $(el).attr('data-nik');
    var cif = $(el).attr('data-cif');
    // var area = $(el).attr('data-nik');
    var unit = $(el).attr('data-unit');
    var statusrpt = $(el).attr('data-statusrpt');
    var nasabah = $(el).attr('data-nasabah');
    // var sortby = $(el).attr('data-nik');

    // var unit = $(el).attr('data-unit');
    // var up = $(el).attr('data-up');
    //alert(unit);
    KTApp.block('#form_bukukas .kt-portlet__body', {});
    $.ajax({
			type : 'GET',
			url : "<?php echo base_url("api/transactions/oneobligor/detailOneobligor"); ?>",
			dataType : "json",
			data:{cif,unit,statusrpt,nasabah,nik},
			success : function(response,status){
				KTApp.unblockPage();
				if(response.status == true){
					var template = '';
					var no = 1;
					// var saldo = up;
					// var cicilan = 0;
					$.each(response.data, function (index, data) {
                        // if(data.date_installment =="1970-01-01"){ cicilan=saldo; }else{ cicilan=data.amount;}
                        // saldo -= cicilan;
						template += "<tr class='rowappend_mdl'>";
						// template += "<td class='text-center'>"+no+"</td>";
                        template += "<td class='text-center'>"+data.no_cif+"</td>";
                        template += "<td class='text-center'>"+data.no_sbk+"</td>";
						template += "<td class='text-center'>"+moment(data.date_sbk).format('DD-MM-YYYY')+"</td>";
						template += "<td class='text-center'>"+moment(data.deadline).format('DD-MM-YYYY')+"</td>";
						template += "<td class='text-right'>"+convertToRupiah(data.capital_lease)+"</td>";
                        template += "<td class='text-right'>"+convertToRupiah(data.estimation)+"</td>";
                        template += "<td class='text-right'>"+convertToRupiah(data.admin)+"</td>";
                        template += "<td class='text-right'>"+convertToRupiah(data.amount)+"</td>";
                        if(data.status_transaction=="N"){
                            data.status_transaction="Aktif";
                        }else{
                            data.status_transaction="Lunas";
                        }
                        template += "<td class='text-right'>"+data.status_transaction+"</td>";
                        template += "<td class='text-right'>"+data.description_1+"</td>";
						// template += "<td class='text-right'>"+convertToRupiah()+"</td>";
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

jQuery(document).ready(function() {
    initCariForm();
    initGetNasabah();
    // initGetKtp();
    initUnitNasabah();
     $(document).on("click", ".viewcicilan", function () {
                var el = $(this);
                popView(el);
    });
});

$('[name="no_sbk"]').on('change',function(){
	var cif = $('[name="no_sbk"]').val();
    var unit = $('[name="id_unit"]').val();
    var nasabah =  $('[name="nasabah"]');

	var ktp =  $('[name="ktp"]');
	var url_data = $('#url_get_ktp').val() + '/' + cif + '/' + unit;



	$.get(url_data, function (data, status) {
		var response = JSON.parse(data);
		if (status) {
			$("#ktp").empty();
			ktp.append('<option value="0">All</option>');
			for (var i = 0; i < response.data.length; i++) {
				var opt = document.createElement("option");
				opt.value = response.data[i].nik;
				opt.text = response.data[i].nik;
                // opt.textContent = `${response.data[i].nik}-${response.data[i].name}`;

				ktp.append(opt);
			}
		}
	});

    $.get(url_data, function (data, status) {
		var response = JSON.parse(data);
		if (status) {
			$("#nasabah").empty();
			nasabah.append('<option value="0">All</option>');
			for (var i = 0; i < response.data.length; i++) {
				var opt = document.createElement("option");
				opt.value = response.data[i].name;
				// opt.text = response.data[i].nik;
                opt.textContent = response.data[i].name;

				nasabah.append(opt);
			}
		}
	});
});

// $('[name="cif"]').on('change',function(){
// 	var cif = $('[name="no_sbk"]').val();
//     var unit = $('[name="id_unit"]').val();
// 	var nasabah =  $('[name="nasabah"]');
// 	var url_data = $('#url_get_ktp').val() + '/' + cif + '/' + unit;



// 	$.get(url_data, function (data, status) {
// 		var response = JSON.parse(data);
// 		if (status) {
// 			$("#nasabah").empty();
// 			nasabah.append('<option value="0">All</option>');
// 			for (var i = 0; i < response.data.length; i++) {
// 				var opt = document.createElement("option");
// 				opt.value = response.data[i].name;
// 				// opt.text = response.data[i].nik;
//                 opt.textContent = response.data[i].name;

// 				nasabah.append(opt);
// 			}
// 		}
// 	});
// });

var type = $('[name="area"]').attr('type');
if(type == 'hidden'){
    $('[name="area"]').trigger('change');
}
var typecif = $('[name="no_sbk"]').attr('type');
if(typecif == 'hidden'){
	$('[name="no_sbk"]').trigger('change');
}

</script>
