<script>


function convertToRupiah(angka)
{
	var rupiah = '';
	var angkarev = angka.toString().split('').reverse().join('');
	for(var i = 0; i < angkarev.length; i++) if(i%3 == 0) rupiah += angkarev.substr(i,3)+'.';
	return rupiah.split('',rupiah.length-1).reverse().join('');
}


var KTDashboard = function() {
    var currentDate = "<?php $date = date('Y-m-d'); echo $date?>";
    var lastDate = "<?php echo  date('Y-m-d', strtotime($date . " -1 days"));?>";

    // Revenue Change.
    // Based on Morris plugin - http://morrisjs.github.io/morris.js/
    var outstandingchart = function() {
        if ($('#kt_chart_outstanding').length == 0) {
            return;
        }

        var dataBooking = [];
        $.ajax({
            url: "<?php echo base_url('api/dashboards/unitost');?>",
            type:"GET",
            dataType:"JSON",
            data:{date:currentDate},
            success:function(response){
                dataBooking = response.data;
                var i = 0;
                var data = [
                    {
                         up:dataBooking.today_up,
                         noa:dataBooking.today_noa,
                         text:'hari ini',
                    },
                    {
                        up:dataBooking.last_up,
                        noa:dataBooking.last_noa,
                        text:'kemarin',
                    },
                    {
                        up:dataBooking.total_up,
                        noa:dataBooking.total_noa,
                        text:'total',
                    }
                ];
                $('#kt_chart_outstanding').parents('.kt-portlet').find('.kt-widget14__legend').each(function(element){
                    $(this).find('.kt-widget14__stats').text(data[i].text+' Noa : '+data[i].noa+' Rp.'+' '+convertToRupiah(data[i].up));
                    i++;
                });
            },
            error:function(xhr){

            },
            complete:function(){                
                Morris.Donut({
                    element: 'kt_chart_outstanding',
                    data: [{
                            label: "Today",
                            value: dataBooking.today_up
                        },
                        {
                            label: "Yesterday",
                            value: dataBooking.last_up
                        },
                    ],
                    colors: [
                        KTApp.getStateColor('danger'),
                        KTApp.getStateColor('brand'),
                    ],
                });
            }
        });
    }

    var bookingchart = function() {
        if ($('#kt_chart_booking').length == 0) {
            return;
        }
        var dataBooking = [];
        $.ajax({
            url: "<?php echo base_url('api/dashboards/unitbooking');?>",
            type:"GET",
            dataType:"JSON",
            data:{date:currentDate},
            success:function(response){
                dataBooking = response.data;
                var i = 0;
                var data = [
                    {
                         up:dataBooking.today_up,
                         noa:dataBooking.today_noa,
                         text:'hari ini',
                    },
                    {
                        up:dataBooking.last_up,
                        noa:dataBooking.last_noa,
                        text:'kemarin',
                    },
                    {
                        up:dataBooking.total_up,
                        noa:dataBooking.total_noa,
                        text:'total',
                    }
                ];
                $('#kt_chart_booking').parents('.kt-portlet').find('.kt-widget14__legend').each(function(element){
                    $(this).find('.kt-widget14__stats').text(data[i].text+' Noa : '+data[i].noa+' Rp.'+' '+convertToRupiah(data[i].up));
                    i++;
                });
            },
            error:function(xhr){

            },
            complete:function(){
                Morris.Donut({
                    element: 'kt_chart_booking',
                    data: [{
                            label: "Today",
                            value: dataBooking.today_up
                        },
                        {
                            label: "Yesterday",
                            value: dataBooking.last_up
                        }
                    ],
                    colors: [
                        KTApp.getStateColor('danger'),
                        KTApp.getStateColor('brand')
                    ],
                });
            }
        });
    }

    var dpdchart = function() {
        if ($('#kt_chart_dpd').length == 0) {
            return;
        }
        var dataBooking = [];
        $.ajax({
            url: "<?php echo base_url('api/dashboards/unitdpd');?>",
            type:"GET",
            dataType:"JSON",
            data:{date:currentDate},
            success:function(response){
                dataBooking = response.data;
                var i = 0;
                var data = [
                    {
                         up:dataBooking.today_up,
                         noa:dataBooking.today_noa,
                         text:'hari ini',
                    },
                    {
                        up:dataBooking.last_up,
                        noa:dataBooking.last_noa,
                        text:'kemarin',
                    },
                    {
                        up:dataBooking.total_up,
                        noa:dataBooking.total_noa,
                        text:'total',
                    }
                ];
            $('#kt_chart_dpd').parents('.kt-portlet').find('.kt-widget14__legend').each(function(element){
                    $(this).find('.kt-widget14__stats').text(data[i].text+' Noa : '+data[i].noa+' Rp.'+' '+convertToRupiah(data[i].up));
                    i++;
                });
            },
            error:function(xhr){

            },
            complete:function(){                
                Morris.Donut({
                    element: 'kt_chart_dpd',
                    data: [{
                            label: "Today",
                            value: dataBooking.today_up
                        },
                        {
                            label: "Yesterday",
                            value: dataBooking.last_up
                        },
                    ],
                    colors: [
                        KTApp.getStateColor('danger'),
                        KTApp.getStateColor('brand'),
                    ],
                });
            }
        });
    }

    var pencairanchart = function() {
        if ($('#kt_chart_pencairan').length == 0) {
            return;
        }

        var dataBooking = [];
        $.ajax({
            url: "<?php echo base_url('api/dashboards/unitpencairan');?>",
            type:"GET",
            dataType:"JSON",
            data:{date:currentDate},
            success:function(response){
                dataBooking = response.data;
                var i = 0;
                var data = [
                    {
                         up:dataBooking.reg_up,
                         noa:dataBooking.reg_noa,
                         text:'Reguler',
                    },
                    {
                        up:dataBooking.mor_up,
                        noa:dataBooking.mor_noa,
                        text:'Cicilan',
                    },
                    {
                        up:dataBooking.total_up,
                        noa:dataBooking.total_noa,
                        text:'Total',
                    }
                ];
            $('#kt_chart_pencairan').parents('.kt-portlet').find('.kt-widget14__legend').each(function(element){
                    $(this).find('.kt-widget14__stats').text(data[i].text+' Noa : '+data[i].noa+' Rp.'+' '+convertToRupiah(data[i].up));
                    i++;
                });
            },
            error:function(xhr){

            },
            complete:function(){                
                Morris.Donut({
                    element: 'kt_chart_pencairan',
                    data: [{
                            label: "Reguler",
                            value: dataBooking.reg_up
                        },
                        {
                            label: "Cicilan",
                            value: dataBooking.mor_up
                        },
                    ],
                    colors: [
                        KTApp.getStateColor('danger'),
                        KTApp.getStateColor('brand'),
                    ],
                });
            }
        });
    }

    var pelunasanchart = function() {
        if ($('#kt_chart_pelunasan').length == 0) {
            return;
        }
        var dataBooking = [];
        $.ajax({
            url: "<?php echo base_url('api/dashboards/unitpelunasan');?>",
            type:"GET",
            dataType:"JSON",
            data:{date:currentDate},
            success:function(response){
                dataBooking = response.data;
                var i = 0;
                var data = [
                    {
                         up:dataBooking.reg_up,
                         noa:dataBooking.reg_noa,
                         text:'Reguler',
                    },
                    {
                        up:dataBooking.mor_up,
                        noa:dataBooking.mor_noa,
                        text:'Cicilan',
                    },
                    {
                        up:dataBooking.total_up,
                        noa:dataBooking.total_noa,
                        text:'Total',
                    }
                ];
            $('#kt_chart_pelunasan').parents('.kt-portlet').find('.kt-widget14__legend').each(function(element){
                    $(this).find('.kt-widget14__stats').text(data[i].text+' Noa : '+data[i].noa+' Rp.'+' '+convertToRupiah(data[i].up));
                    i++;
                });
            },
            error:function(xhr){

            },
            complete:function(){                
                Morris.Donut({
                    element: 'kt_chart_pelunasan',
                    data: [{
                            label: "Reguler",
                            value:  dataBooking.reg_up
                        },
                        {
                            label: "Cicilan",
                            value: dataBooking.mor_up
                        }
                    ],
                    colors: [
                        KTApp.getStateColor('danger'),
                        KTApp.getStateColor('brand')
                    ],
                });
            }
        });
    }

    var profitchart = function() {
        if ($('#kt_chart_profit').length == 0) {
            return;
        }
        var dataBooking = [];
        $.ajax({
            url: "<?php echo base_url('api/dashboards/unitprofit');?>",
            type:"GET",
            dataType:"JSON",
            data:{date:currentDate},
            success:function(response){
                dataBooking = response.data;
                var i = 0;
                var data = [
                    {
                         up:dataBooking.cash_in,
                         text:'hari ini',
                    },
                    {
                        up:dataBooking.cash_out,
                        text:'kemarin',
                    },
                    {
                        up:dataBooking.total_up,
                        text:'total',
                    }
                ];
            $('#kt_chart_profit').parents('.kt-portlet').find('.kt-widget14__legend').each(function(element){
                    $(this).find('.kt-widget14__stats').text(convertToRupiah(data[i].up));
                    i++;
                });
            },
            error:function(xhr){

            },
            complete:function(){                
                Morris.Donut({
                    element: 'kt_chart_profit',
                    data: [{
                            label: "Pendapatan",
                            value: dataBooking.cash_in
                        },
                        {
                            label: "Pengeluaran",
                            value: dataBooking.cash_out
                        }
                    ],
                    colors: [
                        KTApp.getStateColor('danger'),
                        KTApp.getStateColor('brand')
                    ],
                });
            }
        });
    }

    return {
        // Init demos
        init: function() {
            // init charts
            outstandingchart(); 
            bookingchart(); 
            dpdchart(); 
            pencairanchart();
            pelunasanchart();
            profitchart();

            // demo loading
            var loading = new KTDialog({'type': 'loader', 'placement': 'top center', 'message': 'Loading ...'});
            loading.show();

            setTimeout(function() {
                loading.hide();
            }, 3000);
        }
    };
}();
function initCash(){
    $.ajax({
        url: "<?php echo base_url('api/dashboards/bookcash');?>",
        type:"GET",
        dataType:"JSON",
        success:function(response){
            $('.cash-saldo').text(response.data.amount_balance_final);
            $('.cash-selisih').text(response.data.amount_gap);
            $('.cash-noa').text(response.data.noa);
        },
        error:function(xhr){

        }
    });
}
// Class initialization on page load
jQuery(document).ready(function() {
    KTDashboard.init();
    initCash();
});
</script>