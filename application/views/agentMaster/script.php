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


function initAlert() {
    AlertUtil = {
        showSuccess: function(message, timeout) {
            $("#success_message").html(message);
            if (timeout != undefined) {
                setTimeout(function() {
                    $("#success_alert_dismiss").trigger("click");
                }, timeout)
            }
            $("#success_alert").show();
            KTUtil.scrollTop();
        },
        hideSuccess: function() {
            $("#success_alert_dismiss").trigger("click");
        },
        showFailed: function(message, timeout) {
            $("#failed_message").html(message);
            if (timeout != undefined) {
                setTimeout(function() {
                    $("#failed_alert_dismiss").trigger("click");
                }, timeout)
            }
            $("#failed_alert").show();
            KTUtil.scrollTop();
        },
        hideFailed: function() {
            $("#failed_alert_dismiss").trigger("click");
        },
        showFailedDialogAdd: function(message, timeout) {
            $("#failed_message_add").html(message);
            if (timeout != undefined) {
                setTimeout(function() {
                    $("#failed_alert_dismiss_add").trigger("click");
                }, timeout)
            }
            $("#failed_alert_add").show();
        },
        hideSuccessDialogAdd: function() {
            $("#failed_alert_dismiss_add").trigger("click");
        },
        showFailedDialogEdit: function(message, timeout) {
            $("#failed_message_edit").html(message);
            if (timeout != undefined) {
                setTimeout(function() {
                    $("#failed_alert_dismiss_edit").trigger("click");
                }, timeout)
            }
            $("#failed_alert_edit").show();
        },
        hideSuccessDialogAdd: function() {
            $("#failed_alert_dismiss_edit").trigger("click");
        }
    }
    $("#failed_alert_dismiss").on("click", function() {
        $("#failed_alert").hide();
    })
    $("#success_alert_dismiss").on("click", function() {
        $("#success_alert").hide();
    })
    $("#failed_alert_dismiss_add").on("click", function() {
        $("#failed_alert_add").hide();
    })
    $("#failed_alert_dismiss_edit").on("click", function() {
        $("#failed_alert_edit").hide();
    })
}

function initCariForm() {
    //validator
    var validator = $("#form_bukukas").validate({
        ignore: [],
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

    $('#area').select2({
        placeholder: "Select area",
        width: '100%'
    });
    $('#unit').select2({
        placeholder: "Select Unit",
        width: '100%'
    });
    $('#status').select2({
        placeholder: "Select a status",
        width: '100%'
    });
    //events
    $('#btncari').on('click', function() {
        $('.rowappend').remove();
        var area_id = $('[name="area_id"]').val();
        var branch_id = $('[name="branch_id"]').val();
        var unit_id = $('[name="unit_id"]').val();
        var referral_code = $('[name="referral_code"]').val();

        var page = $('[name="page"]').val();
        KTApp.block('#form_bukukas .kt-portlet__body', {});
        $.ajax({
            type: 'GET',
            url: "<?php echo base_url("api/gcore/agentMaster"); ?>",
            dataType: "json",
            data: {
                area_id: area_id,
                branch_id: branch_id,
                unit_id: unit_id,
                referral_code: referral_code,
                page: page
            },
            success: function(response, status) {
                var totalPagination = response.data.total_page;
                console.log('page : ',totalPagination);

                $('[data-item="page-cloned"]').remove();
                for(let i =1; i <= totalPagination; i++){
                    let template = $('[data-item="page"]').clone();
                    template.attr('data-item','page-cloned');
                    template.attr('onclick',`pagination(${i})`);
                    template.find('a').text(i);
                    template.removeClass('d-none');
                    $(".pagination").append(template);
                }
                KTApp.unblockPage();
                // if (response.status == true) {
                    var template = '';
                    var no = 1;
                    var amount = 0;
                    var admin = 0;
                    var totalTaksiran = 0;
                    var totalUp = 0;
                    var totalAdmin = 0;
                    var totalTafsiran = 0;
                    var status = "";
                    var catatan = '';
                    var rate = '';
                    var totalAngsuran = 0;
                    var totalSisaUp = 0;
                    let sisa = 0;
                    var merk = '';
                    var monthly_fee = '';
                    var count_fee = '';
                    var totalFee = 0;
                    var totalFeeGha = 0;
                    var totalSewa = 0;

                    for (const data of response.data.data) {
                        
                      console.log('data', data);
                        template += "<tr class='rowappend'>";
                        template += "<td class='text-center'>" + no + "</td>";
                        template += "<td class='text-left'>" + data.office_name + "</td>";
                        template += "<td class='text-right'>" + data.agent_type + "</td>";
                        template += "<td class='text-right'>" + data.referral_code + "</td>";
                        template += "<td class='text-left'>" + data.identity_number + "</td>";
                        template += "<td class='text-left'>" + (data.npwp_number ? data.npwp_number : '-')  + "</td>";
                        template += "<td>" + data.name + "</td>";
                        template += "<td class='text-left'>" + data.birth_place + "</td>";
                        template += "<td class='text-left'>" + data.birth_date + "</td>";
                        template += "<td class='text-right'>" + data.phone_number + "</td>";
                        template += "<td class='text-center'>" + data.address + "</td>";
                        template += "<td class='text-right'>" + data.bank_name + "</td>";
                        template += "<td class='text-right'>" + data.account_number + "</td>";
                        template += '</tr>';
                        no++;                        
                       
                    };
                    
                    $('.kt-section__content table').append(template);
                // }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                KTApp.unblockPage();
            },
            complete: function() {
                KTApp.unblock('#form_bukukas .kt-portlet__body', {});
            }
        });

        var area_id = $('[name="area_id"]').val();
        var branch_id = $('[name="branch_id"]').val();
        var unit_id = $('[name="unit_id"]').val();
        var referral_code = $('[name="referral_code"]').val();
       
        var page = $('[name="page"]').val();
        $.ajax({
            type: 'GET',
            url: "<?php echo base_url("api/gcore/agentMaster/export"); ?>",
            dataType: "json",
            data: {
                area_id: area_id,
                branch_id: branch_id,
                unit_id: unit_id,
                referral_code: referral_code,
               
                page: page
            },
            success: function(response, status) {
                console.log('urlllllllllll', response.data)
                console.log('urlllllllllll', response.data.url)
                var queryString = buildQueryString(response.data.dataArray);
                var getUrl = response.data.url + "?" + queryString;
                console.log('getUrl', getUrl);
                var url = $('[name="url_export"]').val(getUrl);
                var btnexport_csv = document.getElementById("btnexport_csv");
                btnexport_csv.href = getUrl;

                
            },
            error: function(jqXHR, textStatus, errorThrown) {
                KTApp.unblockPage();
            },
            complete: function() {
                KTApp.unblock('#form_bukukas .kt-portlet__body', {});
            }
        });
    })


    return {
        validator: validator
    }
}

const pagination = (id)=>{
    $('[name="page"]').val(id);
     $('#btncari').trigger('click');
}

function buildQueryString(params) {
    var queryString = '';
    for (var key in params) {
        if (params.hasOwnProperty(key)) {
            if (queryString.length > 0) {
                queryString += '&';
            }
            queryString += encodeURIComponent(key) + '=' + encodeURIComponent(params[key]);
        }
    }
    return queryString;
}

function date_between(start, end) {
    var date1 = new Date(`${start}`);
    var date2 = new Date(`${end}`);

    // To calculate the time difference of two dates 
    var Difference_In_Time = date2.getTime() - date1.getTime();

    // To calculate the no. of days between two dates 
    var Difference_In_Days = Difference_In_Time / (1000 * 3600 * 24);
    return Difference_In_Days + 1;
}

$(document).ready(function() {
    $('#btncari').trigger('click');
})




const initArea = () => {
    $.ajax({
        type: 'GET',
        url: "<?php echo base_url("api/gcore/datamaster/areas"); ?>",
        dataType: "json",
        success: function(res) {
            let template = '<option value="all">All</option>';
            res.data.forEach(res => {
                console.log(res.id)
                console.log('iya cabang')
                template += `<option value="${res.id}">${res.name}</option>`;
            })
            $('[name="area_id"]').append(template);
        },
        error: function(e) {
            console.log(e);
        }
    });
}

const initCabang = () => {
    let area_id = $('#area_id').val();
    $.ajax({
        type: 'GET',
        url: "<?php echo base_url("api/gcore/datamaster/branchies"); ?>/" + area_id,
        dataType: "json",
        success: function(res) {
            let template = '<option value="all">All</option>';
            res.data.forEach(res => {
                console.log(res.id)
                console.log('iya')
                template += `<option value="${res.id}">${res.name}</option>`;
            })
            $('[name="branch_id"]').append(template);
        },
        error: function(e) {
            console.log(e);
        }
    });
}

const initUnit = () => {
    let branch_id = $('#branch_id').val();
    $.ajax({
        type: 'GET',
        url: "<?php echo base_url("api/gcore/datamaster/units"); ?>/" + branch_id,
        dataType: "json",
        success: function(res) {
            let template = '<option value="all">All</option>';
            res.data.forEach(res => {
                console.log(res.id)
                console.log('iya')
                template += `<option value="${res.id}">${res.name}</option>`;
            })
            $('[name="unit_id"]').append(template);
        },
        error: function(e) {
            console.log(e);
        }
    });
}

$('[name="area_id"]').on('change', function() {
    let area_id = $(this).val();
    $('[name="branch_id"]').empty();
    $('[name="unit_id"]').empty();
    $.ajax({
        type: 'GET',
        url: "<?php echo base_url("api/gcore/datamaster/branchies"); ?>/" + area_id,
        dataType: "json",
        success: function(res) {
            let template = '<option value="all">All</option>';
            res.data.forEach(res => {
                template += `<option value="${res.id}">${res.name}</option>`;
            })
            $('[name="branch_id"]').append(template);
            $('[name="unit_id"]').append(template);
            
        },
        error: function(e) {
            console.log(e);
        }
    });
})

var type = $('[name="area_id"]').attr('type');
if (type == 'hidden') {
    $('[name="area"]').trigger('change');
}



$('[name="branch_id"]').on('change', function() {
    let branch_id = $(this).val();
    $('[name="unit_id"]').empty();
    $.ajax({
        type: 'GET',
        url: "<?php echo base_url("api/gcore/datamaster/units"); ?>/" + branch_id,
        dataType: "json",
        success: function(res) {
            let template = '<option value="all">All</option>';
            res.data.forEach(res => {
                template += `<option value="${res.id}">${res.name}</option>`;
            })
            $('[name="unit_id"]').append(template);
        },
        error: function(e) {
            console.log(e);
        }
    });
})

var typecabang = $('[name="branch_id"]').attr('type');
if (typecabang == 'hidden') {
    $('[name="cabang"]').trigger('change');
}

// $('[name="unit_id"]').on('change', function() {
//     let unit_id = $(this).val();
//     $('[name="unit_id"]').empty();
//     $.ajax({
//         type: 'GET',
//         url: "<?php echo base_url("api/gcore/agent"); ?>/" + branch_id,
//         dataType: "json",
//         success: function(res) {
//             let template = '<option value="all">All</option>';
//             res.data.forEach(res => {
//                 template += `<option value="${res.id}">${res.name}</option>`;
//             })
//             $('[name="unit_id"]').append(template);
//         },
//         error: function(e) {
//             console.log(e);
//         }
//     });
// })

// var typeunit = $('[name="unit_id"]').attr('type');
// if (typeunit == 'hidden') {
//     $('[name="unit"]').trigger('change');
// }


// $('#btnexport_csv').on('click', function(e){
    
// });

// document.getElementById("btnexport_csv").addEventListener("click", function() {
//     var url = $('[name="url_export"]').val();
//     console.log('btn', url);
//     // var form = document.getElementById("form_bukukas");
//     // form.action = url;
//     window.location.href = url;
    
// });

$('#btnexport_pdf').on('click', function(e) {
    var area = $('[name="area_id"]').val() ? $('[name="area_id"]').val() : '';
    var cabang = $('[name="branch_id"]').val() ? $('[name="branch_id"]').val() : '';
    var unit = $('[name="unit_id"]').val() ? $('[name="unit_id"]').val() : '';
    var date = $('[name="date"]').val() ? $('[name="date"]').val() : '';
    console.log(date);
    window.location.href =
        `<?php echo base_url('report/gcore/pdf');?>?area=${area}&cabang=${cabang}&unit=${unit}&date=${date}`;
});


//view Angsuran
function popView(el){
    $('.rowappend_mdl').remove();
    var pawn_id = $(el).attr('data-id');
    var sge = $(el).attr('data-sge');
    var up = $(el).attr('data-up');
    var dateEnd = $(el).attr('data-dateEnd');
    
    //alert(unit);
    KTApp.block('#form_bukukas .kt-portlet__body', {});
    $.ajax({
			type : 'GET',
			url : "<?php echo base_url("api/gcore/kp/cicilan"); ?>",
			dataType : "json",
			data:{pawn_id:pawn_id,sge:sge, up:up, dateEnd:dateEnd},
			success : function(response,status){
				KTApp.unblockPage();
				if(response.status == true){
					var template = '';
					var no = 1;
					var saldo = up;
					var cicilan = 0;

                    template += "<tr class='rowappend_mdl'>";
						template += "<td colspan='6' class='text-center'><strong>"+ 'Pinjaman Awal' +"</strong></td>";
                        
						template += "<td class='text-right'>"+convertToRupiah(saldo)+"</td>";
						template += '</tr>';

					$.each(response.data, function (index, data) {
                        // if(data.date_installment =="1970-01-01"){ cicilan=saldo; }else{ cicilan=data.amount;}
                        saldo -= data.installment_amount;
						template += "<tr class='rowappend_mdl'>";
						template += "<td class='text-center'>"+no+"</td>";
                        template += "<td class='text-center'>"+sge+"</td>";
                        if(dateEnd >= data.payment_date){
                            template += "<td class='text-center'>"+moment(data.payment_date).format('DD-MM-YYYY')+"</td>";
                        }else{
                            template += "<td class='text-center'>"+ '-' +"</td>";
                        }
						
                        // if(data.date_installment ==null || data.date_installment =="1970-01-01"){ var datePayment=" Pelunasan"; }else{ var datePayment = moment(data.date_installment).format('DD-MM-YYYY');}
						template += "<td class='text-center'>"+moment(data.due_date).format('DD-MM-YYYY')+"</td>";
                        if(dateEnd >= data.payment_date){
                            template += "<td class='text-right'>"+convertToRupiah(data.installment_amount)+"</td>";
                            template += "<td class='text-right'>"+convertToRupiah(data.installment_fee)+"</td>";
                            template += "<td class='text-right'>"+convertToRupiah(saldo)+"</td>";
                        }else{
                            template += "<td class='text-right'>"+ '-' +"</td>";
                            template += "<td class='text-right'>"+ '-' +"</td>";
                            template += "<td class='text-right'>"+ '-' +"</td>";
                        }
						
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
    var level = $('#level').val();

    initCariForm();
    
    // if(level == 'cabang'){
        initCabang();
        initArea();
        initUnit();
        $(document).on("click", ".viewcicilan", function () {
                var el = $(this);
                popView(el);
    });
});
</script>