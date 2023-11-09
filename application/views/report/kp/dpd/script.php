<script>
//globals
var cariForm;

// function convertToRupiah(angka)
// {
// 	var rupiah = '';
// 	var angkarev = angka.toString().split('').reverse().join('');
// 	for(var i = 0; i < angkarev.length; i++) if(i%3 == 0) rupiah += angkarev.substr(i,3)+'.';
// 	return rupiah.split('',rupiah.length-1).reverse().join('');
// }


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
        var date = $('[name="date"]').val();
        var lastdate = "";

        var statusrpt = $('#status').val();
        var dateStart = $('[name="date-start"]').val();
        var dateEnd = $('[name="date-end"]').val();
        var packet = $('[name="packet"]').val();
        KTApp.block('#form_bukukas .kt-portlet__body', {});
        $.ajax({
            type: 'GET',
            url: "<?php echo base_url("api/gcore/kp/dpd"); ?>",
            dataType: "json",
            data: {
                area_id: area_id,
                date: date,
                branch_id: branch_id,
                unit_id: unit_id,
                dateStart: dateStart,
                dateEnd: dateEnd,
                packet
            },
            success: function(response, status) {
                KTApp.unblockPage();
                if (response.status == true) {
                    var template = '';
                    var no = 1;
                    var amount = 0;
                    var admin = 0;
                    var totalDPD = 0;
                    var totalDenda = 0;
                    var totalPelunasan = 0;
                    var totalTafsiran = 0;
                    var status = "";
                    var sewa = 0;

                    $.each(response.data, function(index, data) {
                          var date_dpd = moment(data.due_date).add(8, 'days');
                        var dpd = parseInt(date_between(`${data.due_date}`,
                            "<?php echo date('Y/m/d');?>")) - 7;
                            
                         if(dpd < 1){
                                dpd = dpd - 1;
                            }
                            
                        template += "<tr class='rowappend'>";
                        template += "<td class='text-center'>" + no + "</td>";
                        template += "<td class='text-center'>" + data.office_name + "</td>";
                        template += "<td class='text-right'>" + data.product_name +
                            "</td>";
                        template += "<td class='text-center'>" + data.sge + "</td>";
                        template += "<td class='text-center'>" + moment(data.contract_date)
                            .format('DD-MM-YYYY') + "</td>";
                        template += "<td class='text-center'>" + moment(data.due_date)
                            .format('DD-MM-YYYY') + "</td>";
                        template += "<td class='text-center'>" + moment(date_dpd)
                            .format('DD-MM-YYYY') + "</td>";
                            
                        if (data.date_repayment != null) {
                            var DateRepayment = moment(data.repayment_date).format(
                                'DD-MM-YYYY');
                        } else {
                            var DateRepayment = "-";
                        }
                        template += "<td class='text-center'>" + DateRepayment + "</td>";
                        template += "<td>" + data.customer_name + "</td>";
                        template += "<td>" + data.address + "</td>";
                        template += "<td>" + data.phone_number + "</td>";
                        template += "<td class='text-center'>" + data.interest_rate +
                            "</td>";
                        template += "<td class='text-right'>" + convertToRupiah(data
                            .estimated_value) + "</td>";
                        template += "<td class='text-right'>" + convertToRupiah(data
                            .admin_fee) + "</td>";
                        template += "<td class='text-right'>" + convertToRupiah(data
                            .loan_amount) + "</td>";
                        if (data.payment_status == true) {
                            status = "Lunas";
                        } else if (data.payment_status == false) {
                            status = "Aktif";
                        }
                        template += "<td class='text-center'>" + dpd + "</td>";
                        if(data.product_name == 'Gadai Opsi Bulanan'){
                            sewa = Math.ceil(data.tafsiran_sewa / 4);
                        }else{
                            sewa = Math.ceil(data.tafsiran_sewa);
                        }
                         template += "<td class='text-right'>" + convertToRupiah(sewa) + "</td>";
                        template += "<td class='text-right'>" + convertToRupiah(
                            calculateDenda1(data.loan_amount, dpd, data.interest_rate)) + "</td>";
                        var up = parseInt(calculateDenda1(data.loan_amount, dpd, data.interest_rate));
                        var calcup = up + parseInt(sewa) + parseInt(data.loan_amount);
                        template += "<td class='text-right'>" + convertToRupiah(calcup) +
                            "</td>";
                         
                        template += '</tr>';
                        no++;
                        totalDenda += calculateDenda1(data.loan_amount, dpd, data.interest_rate);
                        amount += parseInt(data.loan_amount);
                        admin += parseInt(data.admin_fee);
                        totalTafsiran += parseInt(sewa);
                        totalPelunasan += parseInt(calcup);
                        totalDPD += parseInt(date_between(data.due_date,
                            "<?php echo date('Y/m/d');?>"));
                    });
                    template += "<tr class='rowappend'>";
                    template += "<td colspan='11' class='text-right'>Total</td>";
                    template += "<td class='text-right'>" + convertToRupiah(admin) + "</td>";
                    template += "<td class='text-right'>" + convertToRupiah(amount) + "</td>";
                    template += "<td class='text-right'>" + totalDPD + "</td>";
                    template += "<td class='text-right'>" + convertToRupiah(totalTafsiran) +
                    "</td>";
                    template += "<td class='text-right'>" + convertToRupiah(totalDenda) + "</td>";
                    template += "<td class='text-right'>" + convertToRupiah(totalPelunasan) +
                        "</td>";
                    template += "<td class='text-right'></td>";
                    template += '</tr>';
                    $('.kt-section__content table').append(template);
                }
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

// $('#btnexport_csv').on('click', function(e){
//     var area = $('[name="area_id"]').val() ? $('[name="area_id"]').val() : '';
//     var cabang = $('[name="branch_id"]').val() ? $('[name="branch_id"]').val() : '';
//     var unit = $('[name="unit_id"]').val() ? $('[name="unit_id"]').val() : '';
//     var date = $('[name="date"]').val() ? $('[name="date"]').val() : '';
//     window.location.href = `<?php echo base_url('report/gcore/export_dpd');?>?area=${area}&cabang=${cabang}&unit=${unit}&date=${date}`;
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

function calculateDenda1(up, dpd, rate) {
		var sumDay = dpd;

		if(sumDay > 0){
						
			var lebih = sumDay % 30;
			var bulan = (sumDay - lebih) / 30 ;
            console.log('bulan',bulan);
            if(lebih > 0){
                var calculate1 = (up * rate / 100) / 30 * lebih;
                var calculate2 = (up * rate / 100) * bulan;
                            console.log('denda1',calculate1)
                            console.log('denda2',calculate2)

                var data = Math.ceil(parseInt(calculate1 + calculate2));
            }else{
                 var calculate2 = up * rate / 100 * bulan;
                var data = Math.ceil(calculate2);
            }
			
			 

			// modusCalculate = calculate % 500;

			
            // console.log('denda',data)
			return data;

		}
		return 0;
	}

jQuery(document).ready(function() {
    initCariForm();
    initCabang();
        initArea();
        initUnit();
});
</script>