<script>
    var basicToken = window.btoa('1234clientsecret:')
    var urlRepot = 'https://yoreport.gadaihartadinataabadi.com';
    var months = ['Januari', 'Februari', 'Maret', 'April', 
    'Mei', 'Juni', 'Juli','Agustus','September', 'Oktober', 'November', 'Desember'];

    estimatornoa()
    function estimatornoa() {
        $('#graphEstimatorNoa').empty();
        var total = 0;
        var totalYesterday = 0;
        KTApp.block('#form_estimator_noa .kt-widget14', {});
        var transaction = [];
        $.ajax({
            url:`${urlRepot}/report_estimator_higher_loan`,
            type:"GET",
            dataType:"JSON",
            // data:{
            //     month: new Date().getMonth()+1,
            //     year: new Date().getFullYear(),
            // },
            headers: {
                "Authorization": `Basic ${basicToken}`,
            },
            success:function (response) {                
                $('#form_estimator_noa').find('a').attr('href', `${urlRepot}/outputfiles/${response.link.split('/')[response.link.split('/').length-1]}`)
                const transaction = response.data.map((data) => ({
                    y: `${months[data.month-1]} - ${data.name}`,
                    a: data.loan                                                                                                                                                                                    
                }));            
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
                        };
                config.element = 'graphEstimatorNoa';
                new Morris.Bar(config);
                KTApp.unblock('#form_estimator_noa .kt-widget14', {});
            },
        });
    }

    os()
    function os() {
        $('#graphOS').empty();
        var total = 0;
        var totalYesterday = 0;
        KTApp.block('#form_os .kt-widget14', {});
        var transaction = [];
        $.ajax({
            url:`${urlRepot}/report_outstanding`,
            type:"GET",
            dataType:"JSON",
            data:{
                month: new Date().getMonth()+1,
                year: new Date().getFullYear(),
            },
            headers: {
                "Authorization": `Basic ${basicToken}`,
            },
            success:function (response) {                
                $('#form_os').find('a').attr('href', `${urlRepot}/outputfiles/${response.link.split('/')[response.link.split('/').length-1]}`)
                const transaction = response.data.map((data) => ({
                    y: `${months[data.month-1]} - ${data.year}`,
                    a: data.os
                }));            
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
                        };
                config.element = 'graphOS';
                new Morris.Bar(config);
                KTApp.unblock('#form_os .kt-widget14', {});
            },
        });
    }

    admin()
    function admin() {
        $('#graphAdmin').empty();
        var total = 0;
        var totalYesterday = 0;
        KTApp.block('#form_admin .kt-widget14', {});
        var transaction = [];
        $.ajax({
            url:`${urlRepot}/report_admin_monthly`,
            type:"GET",
            dataType:"JSON",
            headers: {
                "Authorization": `Basic ${basicToken}`,
            },
            success:function (response) {     
                console.log(response);           
                $('#form_admin').find('a').attr('href', `${urlRepot}/outputfiles/${response.link.split('/')[response.link.split('/').length-1]}`)
                const transaction = response.data.map((data) => ({
                    y: `${months[data.month-1]} - ${data.year}`,
                    a: data.sum_of_admin
                }));            
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
                        };
                config.element = 'graphAdmin';
                new Morris.Bar(config);
                KTApp.unblock('#form_admin .kt-widget14', {});
            },
        });
    }
    privateFee()
    function privateFee() {
        $('#graphPrivateFee').empty();
        var total = 0;
        var totalYesterday = 0;
        KTApp.block('#form_private_fee .kt-widget14', {});
        var transaction = [];
        $.ajax({
            url:`${urlRepot}/report_private_fee_monthly`,
            type:"GET",
            dataType:"JSON",
            headers: {
                "Authorization": `Basic ${basicToken}`,
            },
            data:{
                // year: new Date().getFullYear(),
            },
            success:function (response) {
                $('#form_private_fee').find('a').attr('href', `${urlRepot}/outputfiles/${response.link.split('/')[response.link.split('/').length-1]}`)
                const transaction = response.data.map((data) => ({
                    y: `${months[data.month-1]} - ${data.year}`,
                    a: data.fee
                }));            
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
                        };
                config.element = 'graphPrivateFee';
                new Morris.Bar(config);
                KTApp.unblock('#form_private_fee .kt-widget14', {});
            },
        });
    }
    user()
    function user() {
        $('#graphUser').empty();
        var total = 0;
        var totalYesterday = 0;
        KTApp.block('#form_user .kt-widget14', {});
        var transaction = [];
        $.ajax({
            url:`${urlRepot}/report_user_monthly`,
            type:"GET",
            dataType:"JSON",
            headers: {
                "Authorization": `Basic ${basicToken}`,
            },
            data:{
                // year: new Date().getFullYear(),
            },
            success:function (response) {
                $('#form_user').find('a').attr('href', `${urlRepot}/outputfiles/${response.link.split('/')[response.link.split('/').length-1]}`)
                const transaction = response.data.map((data) => ({
                    y: `${months[data.month-1]} - ${data.year}`,
                    a: data.count
                }));            
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
                        };
                config.element = 'graphUser';
                new Morris.Bar(config);
                KTApp.unblock('#form_user .kt-widget14', {});
            },
        });
    }
    disbust()
    function disbust() {
        $('#graphDisbust').empty();
        var total = 0;
        var totalYesterday = 0;
        KTApp.block('#form_disbust .kt-widget14', {});
        var transaction = [];
        $.ajax({
            url:`${urlRepot}/report_disbust_monthly`,
            type:"GET",
            dataType:"JSON",
            headers: {
                "Authorization": `Basic ${basicToken}`,
            },
            data:{
                // year: new Date().getFullYear(),
            },
            success:function (response) {
                $('#form_disbust').find('a').attr('href', `${urlRepot}/outputfiles/${response.link.split('/')[response.link.split('/').length-1]}`)
                const transaction = response.data.map((data) => ({
                    y: `${months[data.month-1]} - ${data.year}`,
                    a: data.sum_of_loan
                }));            
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
                        };
                config.element = 'graphDisbust';
                new Morris.Bar(config);
                KTApp.unblock('#form_disbust .kt-widget14', {});
            },
        });
    }
    repayment()
    function repayment() {
        $('#graphRepayment').empty();
        var total = 0;
        var totalYesterday = 0;
        KTApp.block('#form_repayment .kt-widget14', {});
        var transaction = [];
        $.ajax({
            url:`${urlRepot}/report_repayment_monthly`,
            type:"GET",
            dataType:"JSON",
            headers: {
                "Authorization": `Basic ${basicToken}`,
            },
            data:{
                // year: new Date().getFullYear(),
            },
            success:function (response) {
                $('#form_repayment').find('a').attr('href', `${urlRepot}/outputfiles/${response.link.split('/')[response.link.split('/').length-1]}`)
                const transaction = response.data.map((data) => ({
                    y: `${months[data.month-1]} - ${data.year}`,
                    a: data.sum_of_repayment
                }));            
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
                        };
                config.element = 'graphRepayment';
                new Morris.Bar(config);
                KTApp.unblock('#form_repayment .kt-widget14', {});
            },
        });
    }

    dpd()
    function dpd() {
        $('#graphDpd').empty();
        var total = 0;
        var totalYesterday = 0;
        KTApp.block('#form_dpd .kt-widget14', {});
        var transaction = [];
        $.ajax({
            url:`${urlRepot}/report_dpd`,
            type:"GET",
            dataType:"JSON",
            headers: {
                "Authorization": `Basic ${basicToken}`,
            },
            data:{
                date: `${new Date().getFullYear()}-${new Date().getMonth()+1}-${new Date().getDate()}`,
            },
            success:function (response) {
                $('#form_dpd').find('a').attr('href', `${urlRepot}/outputfiles/${response.link.split('/')[response.link.split('/').length-1]}`)
                const units = {};
                response.data.forEach((data) => {
                    units[data.unit] = units[data.unit]+1 || 1; 
                });
                const transaction = Object.keys(units).map((unit, index) => ({
                    y: unit,
                    a: units[unit],
                }))
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
                        };
                config.element = 'graphDpd';
                new Morris.Bar(config);
                KTApp.unblock('#form_dpd .kt-widget14', {});
            },
        });
    }
</script>
