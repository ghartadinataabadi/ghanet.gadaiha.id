<script>
"use strict";
// Class definition
var KTMorrisCharts = function() {

    var nasional = function() {
        // BAR CHART
        new Morris.Bar({
            element: 'kt_Nasional',
            data: [{
                    y: 'Jabar',
                    a: 10
                },
                {
                    y: 'Jatim',
                    a: 50
                },
                {
                    y: 'NTB',
                    a: 80
                },
                {
                    y: 'NTT',
                    a: 30
                }                
            ],
            xkey: 'y',
            ykeys: ['a'],
            labels: ['Nasional '],
            barColors: ['#2abe81', '#24a5ff']
        });
    }

    var month = function() {
        // BAR CHART
        new Morris.Bar({
            element: 'kt_Month',
            data: [{
                    y: 'Jabar',
                    a: 50
                },
                {
                    y: 'Jatim',
                    a: 80
                },
                {
                    y: 'NTB',
                    a: 20
                },
                {
                    y: 'NTT',
                    a: 40
                }                
            ],
            xkey: 'y',
            ykeys: ['a'],
            labels: ['Month '],
            barColors: ['#2abe81', '#24a5ff']
        });
    }

    var saldo = function() {
        // BAR CHART
        new Morris.Bar({
            element: 'kt_Saldo',
            data: [{
                    y: 'Jabar',
                    a: 100
                },
                {
                    y: 'Jatim',
                    a: 75
                },
                {
                    y: 'NTB',
                    a: 50
                },
                {
                    y: 'NTT',
                    a: 75
                }                
            ],
            xkey: 'y',
            ykeys: ['a'],
            labels: ['Saldo '],
            barColors: ['#2abe81', '#24a5ff']
        });
    }

    var bank = function() {
        // BAR CHART
        new Morris.Bar({
            element: 'kt_Bank',
            data: [{
                    y: 'Jabar',
                    a: 20
                },
                {
                    y: 'Jatim',
                    a: 60
                },
                {
                    y: 'NTB',
                    a: 80
                },
                {
                    y: 'NTT',
                    a: 50
                }                
            ],
            xkey: 'y',
            ykeys: ['a'],
            labels: ['Bank '],
            barColors: ['#2abe81', '#24a5ff']
        });
    }

    return {
        // public functions
        init: function() {           
            nasional();
            month();
            saldo();
            bank();
        }
    };
}();

jQuery(document).ready(function() {
    KTMorrisCharts.init();
});
</script>