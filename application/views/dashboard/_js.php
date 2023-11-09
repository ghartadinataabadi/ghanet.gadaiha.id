<script>
//globals
var cariForm;
var pencairan;
var pelunasan;
var cariForm;
var maxDis;

<?php 
	if(date('H:i') > '20:00'){
		$date =  date('Y-m-d');
		$_1lastdate = date('Y-m-d', strtotime($date));
		$_2lastdate = date('Y-m-d', strtotime('-1 days', strtotime($date)));
		$month = date('n', strtotime($date));
		$lastmonth = date('n', strtotime($_2lastdate));
		
	}else{
		$date =  date('Y-m-d');
		$_1lastdate = date('Y-m-d', strtotime('-1 days', strtotime($date)));
		$_2lastdate = date('Y-m-d', strtotime('-2 days', strtotime($date)));
		$month = date('n', strtotime('-1 days', strtotime($date)));
		$lastmonth = date('n', strtotime('-1 days', strtotime($_2lastdate)));
	}
?>

var datenow = "<?php echo $date;?>";
var currdate = "<?php echo $_1lastdate;?>";
var lastdate = "<?php echo $_2lastdate;?>";

var currday = "<?php echo date('d', strtotime($_1lastdate)); ?>";
var lastday = "<?php echo date('d', strtotime($_2lastdate)); ?>";
var currmonth =  "<?php echo $month; ?>";
var lastmonth =  "<?php echo $lastmonth; ?>";
var curryears =  "<?php echo date('Y', strtotime(date('Y-m-d'))); ?>";

function timer(){}

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

    $('#area').select2({ placeholder: "Select area", width: '100%' });
    $('#unit').select2({ placeholder: "Select Unit", width: '100%' });
    $('#transaksi').select2({ placeholder: "Select a transaksi", width: '100%' });
    
    //events
    $('#btncari').on('click',function(){
        $('svg').remove();
        var area = $('#area').val();
        var transaksi = $('#transaksi').val();
		var dateStart = $('[name="date-start"]').val();
        KTApp.block('#form_bukukas .kt-portlet__body', {});
        //alert(transaksi);
        if(transaksi=="OUTSTANDING"){
            outstanding();
        }else if(transaksi=="PENCAIRAN"){
            pencairan();
        }else if(transaksi=="PELUNASAN"){
            pelunasan();
        }else if(transaksi == 'SALDOKAS'){
        	saldo()
		}else if(transaksi == 'PENGELUARAN'){
			pengeluaran()
		}else if(transaksi == 'PENDAPATAN'){
			pendapatan()
		}
		else{
            notfound();
        }        
    })
    return {
        validator:validator
    }
}

function totoutstanding() {
	var today = 0;
	var yesterday = 0;
	var max = 0;
	$.ajax({
		url:"<?php echo base_url('api/dashboards/totoutstanding');?>",
		type:"GET",
		dataType:"JSON",
		data:{
			area:'',
			date:currdate,
		},
		success:function (response) {
			$.each(response.data, function (index,unit) {
				today += unit.ost_today;
				yesterday += unit.ost_yesterday;
				if(max > unit.ost_yesterday){ max=max;}else{ max=unit.ost_yesterday;}
			});
			console.log(max);
			//$('#valmax').val(max);
		},
		complete:function () {
			
			//$('#form_outstanding').find('.total-today').text('Rp. '+convertToRupiah(today));
			//$('#form_outstanding').find('.date-today').text(currdate);
			//$('#form_outstanding').find('.total-yesterday').text('Rp. '+convertToRupiah(yesterday));
			//$('#form_outstanding').find('.date-yesterday').text(lastdate);
		},
	});
}

function booking() {
	$('svg').remove();
	$('#graphDisburse').empty();
	var transaction = [];
	var totalYesterday = 0;
	var totalToday = 0;
	var maxDisburse = 0;
	var permit = $('#permit').val();
	KTApp.block('#form_disburse .kt-widget14', {});
	$.ajax({
		url: "<?php echo base_url('api/dashboards/booking');?>",
		type: "GET",
		dataType: "JSON",
		data: {
			area: '',
			date: currday,
			month: currmonth,
			year: curryears,
			permit: permit,
		},
		success: function (response) {
			$.each(response.data, function (index, unit) {
                if(unit.amount!=0){
                    transaction.push({
					y: unit.name,
					a: unit.amount,
					area:unit.id_area
				})
                }			
                totalToday += unit.amount;

				if(maxDisburse > unit.amount){maxDisburse=maxDisburse;}else{maxDisburse=unit.amount;}
			});
			maxDisburse = maxDisburse;
		},
		complete: function () {
			//console.log(permit);
			$('#form_disburse').find('.total-today').text(convertToRupiah(totalToday));
			$('#disbursmax').val(maxDisburse);
			$('#form_disburse').find('.date-today').text(currdate);
			var data = transaction,
					//config manager
					config = {
						type: 'horizontalBar',
						data: data,
						xkey: 'y',
						ykeys: ['a'],
						labels: ['Values'],
						lineColors: ['#6e4ff5', '#f6aa33'],
						resize: true,
						xLabelAngle: '80',
						xLabelMargin: '10',
						parseTime: false,
						gridTextSize: '10',
						gridTextColor: '#5cb85c',
						verticalGrid: true,
						hideHover: 'auto',
						barColors: ['#3578FC', '#FF0000', '#FFD500'],
						// barColors: function (row, series, type) {
						//     if (data[no].area == 1) return "#e2f53b";
						//     else if (data[no].area == 2) return "#FFD500";
						//     else if (data[no].area == 3) return "#FF0000";
						//     else if (data[no].area == 4) return "#000000";
						// 	else  return '#2370b8';
						// }
					};
			config.element = 'graphDisburse';
			new Morris.Bar(config);
			KTApp.unblock('#form_disburse .kt-widget14', {});
		}
	});

	$.ajax({
		url: "<?php echo base_url('api/dashboards/booking');?>",
		type: "GET",
		dataType: "JSON",
		data: {
			area: '',
			date: lastday,
			month: lastmonth,
			year: curryears,
			permit: permit,
		},
		success: function (response) {
			$.each(response.data, function (index, unit) {
				totalYesterday += unit.amount;				
			});
			//console.log(" total kemarin : "+totalYesterday);
		},
		complete: function () {
			$('#form_disburse').find('.total-yesterday').text(convertToRupiah(totalYesterday));
			$('#form_disburse').find('.date-yesterday').text(lastdate);
		}
	});
	
	setTimeout(function(){ 
		var templateJBR="";
		var templateJTM="";
		var templateNTT="";
		var templateNTB="";
		var tempall="";
		var percentage=0;
		var totjabar 	= 0;
		var totjatim 	= 0;
		var totntb 		= 0;
		var totntt 		= 0;
		var totall 		= 0;
		var max = $('#disbursmax').val();
		console.log(max);
		//console.log('test');

		$.ajax({
			url: "<?php echo base_url('api/dashboards/booking');?>",
			type: "GET",
			dataType: "JSON",
			data: {
				area: '',
				date: lastday,
				month: lastmonth,
				year: curryears,
				permit: permit,
			},
			success: function (response) {
				$.each(response.data, function (index, unit) {
				});	
			},
			complete: function () {
			}
		});

	}, 8000);
	
}

function outstanding() {
	$('svg').remove();
    $('#graphOutstanding').empty();
	$('#tblOut').empty();
	var permit = "";
	var pencentage =0;
    var transaction = [];
    var total = 0;
	KTApp.block('#form_outstanding .kt-widget14', {});
	 var today = 0;
	 var yesterday = 0;

    $.ajax({
		url:"<?php echo base_url('api/dashboards/getos');?>",
		type:"GET",
		dataType:"JSON",
		data:{
			area:$('[name="area"]').val(),
			date:datenow,
			permit:$('[name="permit"]').val(),
		},
		success:function (response) {
			var templateJBR="";
			var templateJTM="";
			var templateNTT="";
			var templateNTB="";
			var tempall="";
			var totjabar 	= 0;
			var totjatim 	= 0;
			var totntb 		= 0;
			var totntt 		= 0;
			var totall 		= 0;
			$.each(response.data, function (index,unit) {
				if(unit.os.outReg!=0){
					transaction.push({
					y:unit.name,
					a:unit.os.outReg,
					//b:unit.ost_yesterday.up
				});
				}				
				today += parseInt(unit.os.outReg);
				yesterday += parseInt(unit.os.outReg);
			});
		},
		complete:function () {
			//console.log(permit);
			$('#form_outstanding').find('.total-today').text('Rp. '+convertToRupiah(today));
			$('#form_outstanding').find('.date-today').text(currdate);
			//$('#form_outstanding').find('.total-yesterday').text('Rp. '+convertToRupiah(yesterday));
			//$('#form_outstanding').find('.date-yesterday').text(lastdate);
			var data = transaction,
					//config manager
					config = {
						//type: 'horizontalBar',
						data: data,
						xkey: 'y',
						ykeys: ['a'],
						labels: ['Values'],
						lineColors: ['#6e4ff5', '#f6aa33'],
						resize: true,
						xLabelAngle: '80',
						xLabelMargin: '10',
						parseTime: false,
						gridTextSize: '10',
						gridTextColor: '#5cb85c',
						verticalGrid: true,
						hideHover: 'auto',
						barColors: ['#3578FC','#FF0000', '#FFD500']
					};
			//config element name
			config.element = 'graphOutstanding';
			new Morris.Bar(config);
			KTApp.unblock('#form_outstanding .kt-widget14', {});
		},
	});
}

function OSMortage() {
	$('svg').remove();
    $('#graphOutstandingMortage').empty();
	$('#tblOut').empty();
	var permit = "";
	var pencentage =0;
    var transaction = [];
    var total = 0;
	KTApp.block('#form_cicilan .kt-widget14', {});
	 var today = 0;
	 var yesterday = 0;

    $.ajax({
		url:"<?php echo base_url('api/dashboards/getos');?>",
		type:"GET",
		dataType:"JSON",
		data:{
			area:$('[name="area"]').val(),
			date:datenow,
			permit:$('[name="permit"]').val(),
		},
		success:function (response) {
			var templateJBR="";
			var templateJTM="";
			var templateNTT="";
			var templateNTB="";
			var tempall="";
			var totjabar 	= 0;
			var totjatim 	= 0;
			var totntb 		= 0;
			var totntt 		= 0;
			var totall 		= 0;
			$.each(response.data, function (index,unit) {
				if(unit.os.outnonReg!=0){
					transaction.push({
					y:unit.name,
					a:unit.os.outnonReg,
					//b:unit.ost_yesterday.up
				});
				}				
				today += parseInt(unit.os.outnonReg);
				yesterday += parseInt(unit.os.outnonReg);
			});
		},
		complete:function () {
			//console.log(permit);
			$('#form_cicilan').find('.total-today').text('Rp. '+convertToRupiah(today));
			$('#form_cicilan').find('.date-today').text(currdate);
			//$('#form_outstanding').find('.total-yesterday').text('Rp. '+convertToRupiah(yesterday));
			//$('#form_outstanding').find('.date-yesterday').text(lastdate);
			var data = transaction,
					//config manager
					config = {
						//type: 'horizontalBar',
						data: data,
						xkey: 'y',
						ykeys: ['a'],
						labels: ['Values'],
						lineColors: ['#6e4ff5', '#f6aa33'],
						resize: true,
						xLabelAngle: '80',
						xLabelMargin: '10',
						parseTime: false,
						gridTextSize: '10',
						gridTextColor: '#5cb85c',
						verticalGrid: true,
						hideHover: 'auto',
						barColors: ['#3578FC','#FF0000', '#FFD500']
					};
			//config element name
			config.element = 'graphOutstandingMortage';
			new Morris.Bar(config);
			KTApp.unblock('#form_cicilan .kt-widget14', {});
		},
	});
}

function pelunasan() {
	$('svg').remove();
	$('#graphPelunasan').empty();
	//var currdate = '2020-07-20';
	var totalCurr = 0;
	var totalLast = 0;
	var transaction = [];
	var permit = $('#permit').val();
	$.ajax({
		url:"<?php echo base_url('api/dashboards/pelunasanpermit');?>",
		type:"GET",
		dataType:"JSON",
		data:{
			area:'',
			date:lastdate,
			permit:permit,
		},
		success:function (response) {
			
			$.each(response.data, function (index,unit) {				
				totalLast += parseInt(unit.amount);
			});			
		},
		complete:function () {
			$('#form_pelunasan').find('.total-yesterday').text(convertToRupiah(totalLast));
			$('#form_pelunasan').find('.date-yesterday').text(lastdate);
		},
	});

	KTApp.block('#form_pelunasan .kt-widget14', {});
	$.ajax({
		url:"<?php echo base_url('api/dashboards/pelunasanpermit');?>",
		type:"GET",
		dataType:"JSON",
		data:{
			area:'',
			date:currdate,
			permit:permit,
		},
		success:function (response) {
			var Tempjabar 	= "";
			var Tempjatim	= "";
			var Tempntt		= "";
			var Tempntb		= "";
			var Tempall		= "";
			var totjabar 	= 0;
			var totjatim 	= 0;
			var totntb 		= 0;
			var totntt 		= 0;
			var totall 		= 0;

			$.each(response.data, function (index,unit) {
                if(unit.repayment!=0){
                    transaction.push({
					y:unit.name,
					a:unit.repayment
				});
                }
				totalCurr += parseInt(unit.repayment);
			});
		},
		complete:function () {
			$('#form_pelunasan').find('.total-today').text(convertToRupiah(totalCurr));
			$('#form_pelunasan').find('.date-today').text(currdate);
			var data = transaction,
					//config manager
					config = {
						data: data,
						xkey: 'y',
						ykeys: ['a'],
						labels: ['Values'],
						lineColors: ['#6e4ff5', '#f6aa33'],
						resize: true,
						xLabelAngle: '80',
						xLabelMargin: '10',
						parseTime: false,
						gridTextSize: '10',
						gridTextColor: '#5cb85c',
						verticalGrid: true,
						hideHover: 'auto',
						barColors: ['#3578FC','#FF0000', '#FFD500']
						// barColors: function (row, series, type) {
						//     if (row.label == "Low") return "#3578FC";
						//     else if (row.label == "Medium") return "#FFD500";
						//     else if (row.label == "High") return "#FF0000";
						//     else if (row.label == "Fatal") return "#000000";
						// }
					};
			//config element name
			config.element = 'graphPelunasan';
			new Morris.Bar(config);
			KTApp.unblock('#form_pelunasan .kt-widget14', {});
		},
	});

}

function pengeluaran() {
	$('svg').remove();
	$('#graphPengeluaran').empty();
	var total = 0;
	var totalYesterday = 0;
	//var currdate = '2020-07-20';
	var transaction = [];
	KTApp.block('#form_pengeluaran .kt-widget14', {});
	$.ajax({
		url:"<?php echo base_url('api/dashboards/pengeluaran');?>",
		type:"GET",
		dataType:"JSON",
		data:{
			area:'',
			date:currdate,
			permit:$('[name="permit"]').val(),
		},
		success:function (response) {

			var Tempjabar 	= "";
			var Tempjatim	= "";
			var Tempntt		= "";
			var Tempntb		= "";
			var Tempall		= "";
			var totjabar 	= 0;
			var totjatim 	= 0;
			var totntb 		= 0;
			var totntt 		= 0;
			var totall 		= 0;

			$.each(response.data, function (index,unit) {
				transaction.push({
					y:unit.name,
					a:unit.amount
				})
				total += parseInt(unit.amount);
				totalYesterday += parseInt(unit.amount_yesterday);
			});				
		},
		complete:function () {			
			$('#form_pengeluaran').find('.date-today').text(currdate);
			$('#form_pengeluaran').find('.total-today').text(convertToRupiah(total));
			$('#form_pengeluaran').find('.date-yesterday').text(lastdate);
			$('#form_pengeluaran').find('.total-yesterday').text(convertToRupiah(totalYesterday));
			var data = transaction,
					//config manager
					config = {
						data: data,
						xkey: 'y',
						ykeys: ['a'],
						labels: ['Values'],
						lineColors: ['#6e4ff5', '#f6aa33'],
						resize: true,
						xLabelAngle: '80',
						xLabelMargin: '10',
						parseTime: false,
						gridTextSize: '10',
						gridTextColor: '#5cb85c',
						verticalGrid: true,
						hideHover: 'auto',
						barColors: ['#3578FC','#FF0000', '#FFD500']
						// barColors: function (row, series, type) {
						//     if (row.label == "Low") return "#3578FC";
						//     else if (row.label == "Medium") return "#FFD500";
						//     else if (row.label == "High") return "#FF0000";
						//     else if (row.label == "Fatal") return "#000000";
						// }
					};
			//config element name
			config.element = 'graphPengeluaran';
			new Morris.Bar(config);
			KTApp.unblock('#form_pengeluaran .kt-widget14', {});
		},
	});

}

function pendapatan() {
	$('svg').remove();
	$('#graphPendapatan').empty();
	//var currdate = '2020-07-20';
	var total = 0;
	var totalYesterday = 0;
	KTApp.block('#form_pendapatan .kt-widget14', {});
	var transaction = [];
	$.ajax({
		url:"<?php echo base_url('api/dashboards/pendapatan');?>",
		type:"GET",
		dataType:"JSON",
		data:{
			area:'',
			date:currdate,
			permit:$('[name="permit"]').val(),
		},
		success:function (response) {

			var Tempjabar 	= "";
			var Tempjatim	= "";
			var Tempntt		= "";
			var Tempntb		= "";
			var Tempall		= "";
			var totjabar 	= 0;
			var totjatim 	= 0;
			var totntb 		= 0;
			var totntt 		= 0;
			var totall 		= 0;

			$.each(response.data, function (index,unit) {
				transaction.push({
					y:unit.name,
					a:unit.amount
				});
				total += parseInt(unit.amount);
				totalYesterday += parseInt(unit.amount_yesterday);
			});				
		},
		complete:function () {
			$('#form_pendapatan').find('.date-today').text(currdate);
			$('#form_pendapatan').find('.total-today').text(convertToRupiah(total));
			$('#form_pendapatan').find('.date-yesterday').text(lastdate);
			$('#form_pendapatan').find('.total-yesterday').text(convertToRupiah(totalYesterday));
		
			var data = transaction,
					//config manager
					config = {
						data: data,
						xkey: 'y',
						ykeys: ['a'],
						labels: ['Values'],
						lineColors: ['#6e4ff5', '#f6aa33'],
						resize: true,
						xLabelAngle: '80',
						xLabelMargin: '10',
						parseTime: false,
						gridTextSize: '10',
						gridTextColor: '#5cb85c',
						verticalGrid: true,
						hideHover: 'auto',
						barColors: ['#3578FC','#FF0000', '#FFD500']
						// barColors: function (row, series, type) {
						//     if (row.label == "Low") return "#3578FC";
						//     else if (row.label == "Medium") return "#FFD500";
						//     else if (row.label == "High") return "#FF0000";
						//     else if (row.label == "Fatal") return "#000000";
						// }
					};
			//config element name
			config.element = 'graphPendapatan';
			new Morris.Bar(config);
			KTApp.unblock('#form_pendapatan .kt-widget14', {});
		},
	});
}

function targetBooking(){

	var booking 	= [];
	var target 		= [];
	var percentage 	= [];
	var unitlabel 	= [];
	var tottarget 	 = 0;
	var totrealisasi = 0;
	var permit = $('#permit').val();
	KTApp.block('#form_tarBook .kt-widget14', {});
	$.ajax({
		url:"<?php echo base_url('api/datamaster/unitstarget/gettarget');?>",
		type:"GET",
		dataType:"JSON",
		data:{
			area:'',
			date:currdate,
			month:currmonth,
			year:curryears,
		    permit:permit
		},
		success:function (response) {

			var Tempjabar 	= "";
			var Tempjatim	= "";
			var Tempntt		= "";
			var Tempntb		= "";
			var Tempall		= "";
			var totjabar 	= 0;
			var totjabarreal= 0;
			var totjatim 	= 0;
			var totjatimreal= 0;
			var totntb 		= 0;
			var totntbreal	= 0;
			var totntt 		= 0;
			var totnttreal	= 0;
			var totall 		= 0;
			var status		="";

			$.each(response.data, function (index,unit) {
                if(unit.booking.target!=0 || unit.booking.target!=null){
                    unitlabel.push(unit.name);
                    booking.push(unit.booking.real);		
                    target.push(unit.booking.target);
                    percentage.push(unit.booking.percentage);

                    tottarget += unit.booking.target;			
				    totrealisasi += unit.booking.real;	

                }			
			});
		},
		complete:function () {
			$('#form_tarBook').find('.total-target').text(convertToRupiah(tottarget));
			$('#form_tarBook').find('.total-realisasi').text(convertToRupiah(totrealisasi));			
			//var datapercentage 	= percentage;
			var databooking 	= booking;
			var datatarget 		= target;
			var dataunitlabel 	= unitlabel;

			//console.log(unitlabel);
			var data = [ {
				label: 'Target',
				backgroundColor: '#006699',
				yAxisID: 'A',
				data: datatarget
			}, {
				label: 'Realisasi',
				backgroundColor: '#FFA000',
				yAxisID: 'A',
				data: databooking
			}];
			
			
			var options = {
				tooltips: {
					mode: 'label', 
					label: 'mylabel', 
					callbacks: { 
					label: function(tooltipItem, data) { 
					return tooltipItem.yLabel.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","); }, },
				},						
				scales: {
					xAxes: [{
							stacked: false,
							gridLines: {
								display: false
							}
						}],
					yAxes: [{
							id: 'A',
							stacked: false,						
							ticks: {
								beginAtZero: true,
								callback: function (value) {
									var suffixes = ["", "k", "m", "b","t"];
									var suffixNum = Math.floor((""+value).length/3);
									var shortValue = parseFloat((suffixNum != 0 ? (value / Math.pow(1000,suffixNum)) : value).toPrecision(2));
									if (shortValue % 1 != 0) {
										var shortNum = shortValue.toFixed(1);
									}
									return shortValue+suffixes[suffixNum];
									// valuek = convertToRupiah(value) ;
									// return valuek;
								}
							}
						}]
				}
		};

		var ctx = document.getElementById("graphtarBooking");
		var myChart = new Chart(ctx, {
			type: 'bar',
			data: {
				labels: dataunitlabel,
				datasets: data
			},
			options: options
		});

			KTApp.unblock('#form_tarBook .kt-widget14', {});
		},
	});
}

function targetOutstanding(){

	var booking 	 = [];
	var target 		 = [];
	var percentage 	 = [];
	var unitlabel 	 = [];
	var tottarget 	 = 0;
	var totrealisasi = 0;
	var status		 ="";
	var permit = $('#permit').val();
	KTApp.block('#form_tarout .kt-widget14', {});
	$.ajax({
		url:"<?php echo base_url('api/datamaster/unitstarget/gettarget');?>",
		type:"GET",
		dataType:"JSON",
		data:{
			area:'',
			month:currmonth,
			permit:permit,
		},
		success:function (response) {

			var Tempjabar 	= "";
			var Tempjatim	= "";
			var Tempntt		= "";
			var Tempntb		= "";
			var Tempall		= "";
			var totjabar 	= 0;
			var totjabarreal= 0;
			var totjatim 	= 0;
			var totjatimreal= 0;
			var totntb 		= 0;
			var totntbreal	= 0;
			var totntt 		= 0;
			var totnttreal	= 0;
			var totall 		= 0;

			$.each(response.data, function (index,unit) {
				unitlabel.push(unit.name);
				booking.push(unit.outstanding.real);		
				target.push(unit.outstanding.target);
				percentage.push(unit.outstanding.percentage);	
				tottarget +=unit.outstanding.target; 		
				totrealisasi +=unit.outstanding.real; 	
			});			
		},
		complete:function () {
			$('#form_tarout').find('.total-target').text(convertToRupiah(tottarget));
			$('#form_tarout').find('.total-realisasi').text(convertToRupiah(totrealisasi));			
			var datapercentage 	= percentage;
			var databooking 	= booking;
			var datatarget 		= target;
			var dataunitlabel 	= unitlabel;
			//console.log(unitlabel);
			var data = [{
				label: 'Target',
				backgroundColor: '#512DA8',
				yAxisID: 'A',
				data: datatarget
			}, {
				label: 'Realisasi',
				backgroundColor: '#FFA000',
				yAxisID: 'A',
				data: databooking
			}];			
			
			var options = {
				tooltips: {
					mode: 'label', 
					label: 'mylabel', 
					callbacks: { 
					label: function(tooltipItem, data) { 
					return tooltipItem.yLabel.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","); }, },
				},						
				scales: {
					xAxes: [{
							stacked: false,
							gridLines: {
								display: false
							}
						}],
					yAxes: [{
							id: 'A',
							stacked: false,						
							ticks: {
								beginAtZero: true,
								callback: function (value) {
									var suffixes = ["", "k", "m", "b","t"];
									var suffixNum = Math.floor((""+value).length/3);
									var shortValue = parseFloat((suffixNum != 0 ? (value / Math.pow(1000,suffixNum)) : value).toPrecision(2));
									if (shortValue % 1 != 0) {
										var shortNum = shortValue.toFixed(1);
									}
									return shortValue+suffixes[suffixNum];
									//valuek = convertToRupiah(value) ;
									//return valuek;
								}
							}
						}]
				}
		};

		var ctx = document.getElementById("graphtarOutstanding");
		var myChart = new Chart(ctx, {
			type: 'bar',
			data: {
				labels: dataunitlabel,
				datasets: data
			},
			options: options
		});

			KTApp.unblock('#form_tarout .kt-widget14', {});
		},
	});
}

function notfound(){
    $("#graph").empty();
    var div = document.getElementById('graph');
    div.innerHTML += '<div class="alert alert-success" role="alert"><strong>Well done! </strong> &nbsp&nbsp Graph not found</div>';
    KTApp.unblock('#form_bukukas .kt-portlet__body', {});
}

jQuery(document).ready(function() {
	booking();
	outstanding();
	OSMortage();
	pelunasan();
    pengeluaran();
	pendapatan();	
	//targetBooking();
	//targetOutstanding();
});

</script>
