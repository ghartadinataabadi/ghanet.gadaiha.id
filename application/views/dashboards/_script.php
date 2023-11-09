<script>

var KTApp = function() {

    var initPerformanceUnit = function() {
        $('#kt_aside_menu, #kt_header_menu').on('click', '.kt-menu__link[href="unit"]', function(e) {
            //e.preventDefault();
            // $.ajax({
            //     type: "POST",
            //     url: "<?php //base_url('dashboards/performaunit'); ?>",
            //     data: "test",
            //     //dataType : 'html',
            //     cache: false,
            //     success : function(data){
            //         console.log(data);
            //         $('#showresults').append(data);
            //     }
            // });
            var lbltext = "text";
            var url = "<?php base_url('dashboards/performaunit'); ?>";
            e.preventDefault();
            $.get(url,function(data){                
                $("#showresults").html(lbltext);
                //$("#showresults").val("");
                //swal.fire("", "Perfoma Unit");                
            });
        });
    }

    var initDisburse = function() {
        $('#kt_aside_menu, #kt_header_menu').on('click', '.kt-menu__link[href="disburse"]', function(e) {
            swal.fire("", "Disburse");
            e.preventDefault();
        });
    }

    var initTargetBooking = function() {
        $('#kt_aside_menu, #kt_header_menu').on('click', '.kt-menu__link[href="targetbooking"]', function(e) {
            swal.fire("", "Target Booking");
            e.preventDefault();
        });
    }

    var initTargetOutstanding = function() {
        $('#kt_aside_menu, #kt_header_menu').on('click', '.kt-menu__link[href="targetoutstanding"]', function(e) {
            swal.fire("", "Target Outstanding");
            e.preventDefault();
        });
    }

    var initPencairan = function() {
        $('#kt_aside_menu, #kt_header_menu').on('click', '.kt-menu__link[href="pencairan"]', function(e) {
            swal.fire("", "Pencairan");
            e.preventDefault();
        });
    }

    var initPelunasan = function() {
        $('#kt_aside_menu, #kt_header_menu').on('click', '.kt-menu__link[href="pelunasan"]', function(e) {
            swal.fire("", "Pelunasan");
            e.preventDefault();
        });
    }

    var initSaldoKas = function() {
        $('#kt_aside_menu, #kt_header_menu').on('click', '.kt-menu__link[href="kas"]', function(e) {
            swal.fire("", "Saldo Kas");
            e.preventDefault();
        });
    }

    var initSaldoBank = function() {
        $('#kt_aside_menu, #kt_header_menu').on('click', '.kt-menu__link[href="bank"]', function(e) {
            swal.fire("", "Saldo Bank");
            e.preventDefault();
        });
    }


    return {
        init: function() {
            KTApp.initComponents();
        },
        initComponents: function() {
            initPerformanceUnit();
            initDisburse();
            initTargetBooking();
            initTargetOutstanding();
            initPencairan();
            initPelunasan();
            initSaldoKas();
            initSaldoBank();
        },
    };

}();

$(document).ready(function() {
        KTApp.init();
});
</script>