<script>


    var reviewverified = function() {
        
    }


function convertToRupiah(angka)
{
	var rupiah = '';
	var angkarev = angka.toString().split('').reverse().join('');
	for(var i = 0; i < angkarev.length; i++) if(i%3 == 0) rupiah += angkarev.substr(i,3)+'.';
	return rupiah.split('',rupiah.length-1).reverse().join('');
}

var KTDashboard = function() { 

    var chartpenaksir = function() {
        var totnoa = 0;
        var totlm = 0;
        var tot_jewel=0;
        var verified =0;
        var unverified =0;
        var lastverified=0;
        var totgramation_bruto=0;
        var totgramation_net=0;

        KTApp.block('#form_noa', {});
        KTApp.block('#form_last_verified', {});
        KTApp.block('#form_gramation', {});
        KTApp.block('#form_lm', {});
        KTApp.block('#form_verified', {});
        $.ajax({
            url: "<?php echo base_url('api/dashboards/summarypenaksir');?>",
            type:"GET",
            dataType:"JSON",
            data:{date:''},
            success:function(response){
                console.log(response.data.tot_qty);
                totnoa      = response.data.tot_noa;
                totlm       = response.data.tot_lm;
                totjwl      = response.data.tot_jewel;
                verified    = response.data.verified;
                unverified  = response.data.unverified;
                lastverified = response.data.lastverified;
                totgramation_bruto = parseFloat(response.data.tot_lm_bruto)+parseFloat(response.data.tot_jewel_bruto);
                totgramation_net = parseFloat(response.data.tot_lm_net)+parseFloat(response.data.tot_jewel_net);
                
            },
            error:function(xhr){

            },
            complete:function(){

                if ($('#kt_chart_transaction').length == 0) {
                    return;
                }
                Morris.Donut({
                    element: 'kt_chart_transaction',
                    data: [{
                            label: "Logam Mulia",
                            value: totlm
                        },
                        {
                            label: "Perhiasan",
                            value: totjwl
                        }
                    ],
                    colors: [
                        KTApp.getStateColor('success'),
                        KTApp.getStateColor('brand'),
                    ],
                });

                if ($('#kt_chart_verified').length == 0) {
                    return;
                }

                Morris.Donut({
                    element: 'kt_chart_verified',
                    data: [{
                            label: "Verified",
                            value: verified
                        },
                        {
                            label: "Unverified",
                            value: unverified
                        }
                    ],
                    colors: [
                        KTApp.getStateColor('warning'),
                        KTApp.getStateColor('danger'),
                    ],
                });

                $('#form_noa').find('.total-noa').text((totnoa));
                $('#form_last_verified').find('.total-lastdayverified').text((lastverified));
                $('#form_gramation').find('.total-gramation').text((totgramation_bruto));                
                $('#form_lm').find('.total-lm').text(+convertToRupiah(totlm));
                $('#form_lm').find('.total-jewel').text(+convertToRupiah(totjwl));
                $('#form_verified').find('.total-verified').text(+convertToRupiah(verified));
                $('#form_verified').find('.total-unverified').text(+convertToRupiah(unverified));
                KTApp.unblock('#form_noa', {});
                KTApp.unblock('#form_last_verified', {});
                KTApp.unblock('#form_gramation', {});
                KTApp.unblock('#form_lm', {});
                KTApp.unblock('#form_verified', {});
            }
        });
    }

    return {
        // Init demos
        init: function() {
            // init charts
            chartpenaksir(); 
            // demo loading
            // var loading = new KTDialog({'type': 'loader', 'placement': 'top center', 'message': 'Loading ...'});
            // loading.show();

            // setTimeout(function() {
            //     loading.hide();
            // }, 3000);
        }
    };
}();

    


jQuery(document).ready(function() {
	//reviewtransaction();
    //reviewverified();
    KTDashboard.init();
});

</script>
