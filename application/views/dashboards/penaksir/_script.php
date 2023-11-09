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

function karatase(){

	var karateselabel 	= [];
	var karatase 		= [];
	KTApp.block('#form_karatase .kt-widget14', {});
	$.ajax({
		url:"<?php echo base_url('api/dashboards/karatase');?>",
		type:"GET",
		dataType:"JSON",
		data:{
			area:'',
			month:currmonth,
		},
		success:function (response) {

			$.each(response.data, function (index,unit) {
				//karateselabel.push(unit.labelkaratase);
				karatase.push(unit.karatase);				
			});			

		},
		complete:function () {
			//$('#form_tarBook').find('.total-target').text(convertToRupiah(tottarget));
			//$('#form_tarBook').find('.total-realisasi').text(convertToRupiah(totrealisasi));			
			//var datapercentage 	= percentage;
			var datakaratase 		= karatase;
			//var datakarataselabel 	= karateselabel;

			//console.log(unitlabel);
			var data = [ {
				label: 'Karatase',
				backgroundColor: '#006699',
				yAxisID: 'A',
				data: datakaratase
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
									// var suffixes = ["", "k", "m", "b","t"];
									// var suffixNum = Math.floor((""+value).length/3);
									// var shortValue = parseFloat((suffixNum != 0 ? (value / Math.pow(1000,suffixNum)) : value).toPrecision(2));
									// if (shortValue % 1 != 0) {
									// 	var shortNum = shortValue.toFixed(1);
									// }
									// return shortValue+suffixes[suffixNum];
									// valuek = convertToRupiah(value) ;
									return value;
								}
							}
						}]
				}
		};

		var ctx = document.getElementById("grapKaratase");
		var myChart = new Chart(ctx, {
			type: 'bar',
			data: {
				labels: datakaratase,
				datasets: data
			},
			options: options
		});

			KTApp.unblock('#form_karatase .kt-widget14', {});
		},
	});
}

function initCash(){
    var saldo=0;
    var saldoselisih=0;
    var outstanding=0;
    var upregular=0;
    var upcicilan=0;
    var saldocicilan=0;
    var dpd=0;
    var dpdnoa=0;
    var gr_regular=0;
    var gr_mortages=0;
    KTApp.block('#form_cardgramasi .kt-portlet', {});
    KTApp.block('#form_cardOut .kt-portlet', {}); 
    //KTApp.block('#form_cardDPD .kt-portlet', {}); 
       
    $.ajax({
        url: "<?php echo base_url('api/dashboards/SummaryUnit');?>",
        type:"GET",
        dataType:"JSON",
        success:function(response){
            saldo        = response.data.saldo;
            saldounit    = response.data.saldounit;
            saldoselisih = parseInt(response.data.saldo) - parseInt(response.data.saldounit);
            outstanding  = response.data.outstanding;
            dpd          = response.data.dpd;
            upregular    = response.data.upreguler;
            upcicilan    = response.data.upcicilan;
            saldocicilan = response.data.saldocicilan;
            dpdnoa       = response.data.noadpd;
            noareg       = response.data.noareguler;
            noaunreg     = response.data.noa_cicilan;
            gramasi   	= response.data.gramasi;
            gr_regular   = response.data.gr_regular;
            gr_mortages  = response.data.gr_mortages;           
        },
        error:function(xhr){
            $('.cash-saldo').text(0);
        },
        complete:function(){
            $('.Outstanding').empty();
            $('.Outstanding').text(convertToRupiah(outstanding));
            $('.upregular').text(convertToRupiah(upregular));
            $('.noareg').text(convertToRupiah(noareg));            
            $('.noacicilan').text(convertToRupiah(noaunreg));            
            $('.gramregular').text(convertToRupiah(gr_regular));            
			$('.grammortages').text(convertToRupiah(gr_mortages)); 
			$('.totGramasi').text(convertToRupiah(gramasi)); 			           
          KTApp.unblock('#form_cardgramasi .kt-portlet', {}); 
          KTApp.unblock('#form_cardOut .kt-portlet', {});  
          //KTApp.unblock('#form_cardDPD .kt-portlet', {});  
          //$("#message_alert").show();                    
        }
    });
}

jQuery(document).ready(function() {
	//karatase();
	initCash();

});

</script>
