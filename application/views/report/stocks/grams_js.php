<script>
    $('#area').select2({ placeholder: "Select Area", width: '100%' });
    $('#id_unit').select2({ placeholder: "Select Unit", width: '100%' });
    $('#status').select2({ placeholder: "Select a Status", width: '100%' });
    $('#cabang').select2({ placeholder: "Select a cabang", width: '100%' });

    $('[name="area"]').on('change',function(){
            var area = $('[name="area"]').val();
            var cabang =  $('[name="cabang"]');
            var url_data = '<?php echo base_url();?>/api/datamaster/cabang?id_area='+area;
            $.get(url_data, function (data, status) {
                var response = JSON.parse(data);
                if (status) {
                    $("#unit").empty();
                    $("#cabang").empty();
                    var opt = document.createElement("option");
                    opt.value = "0";
                    opt.text = "All";
                    cabang.append(opt)
                    for (var i = 0; i < response.data.length; i++) {
                        var opt = document.createElement("option");
                        opt.value = response.data[i].id;
                        opt.text = response.data[i].cabang;
                        cabang.append(opt);
                    }
                }
            });
    });

    $('[name="cabang"]').on('change',function(){
            var cabang = $('[name="cabang"]').val();
            var unit =  $('[name="id_unit"]');
            var url_data = '<?php echo base_url();?>/api/datamaster/units?id_cabang='+cabang;
            $.get(url_data, function (data, status) {
                var response = JSON.parse(data);
                if (status) {
                    $("#unit").empty();
                    for (var i = 0; i < response.data.length; i++) {
                        var opt = document.createElement("option");
                        opt.value = response.data[i].id;
                        opt.text = response.data[i].name;
                        unit.append(opt);
                    }
                }
            });
    });

    const searchHandler = (event)=>{
        event.preventDefault();
        const id_area =   $('[name="area"]').val();
        const id_unit =   $('[name="id_unit"]').val();
        const id_cabang =   $('[name="cabang"]').val();
        $.ajax({
            url:"<?php echo base_url();?>api/lm/stocks/grams",
            data:{
                date_start:event.target.querySelector('[name="date_start"]').value,
                date_end:event.target.querySelector('[name="date_end"]').value,
                id_area, id_unit, id_cabang
            },
            dataType:"JSON",
            type:"GET",
            success:function(res){
                const cloneds = document.querySelector('.table').querySelectorAll('[data-template="item-cloned"]');
                if(cloneds.length > 0){
                    cloneds.forEach(el=>el.remove());
                }
                res.data.forEach(data=>{
                    const table = document.querySelector('.table').querySelector('[data-template="item"]').cloneNode(true);
                    table.classList.remove('d-none');
                    table.setAttribute('data-template','item-cloned');
                    table.querySelector('[data-post="weight"]').textContent = data.weight+' grams';
                    table.querySelector('[data-post="unit"]').textContent = data.unit;
                    table.querySelector('[data-post="stock_begin"]').textContent = data.stock_begin;
                    table.querySelector('[data-post="stock_in"]').textContent = data.stock_in;
                    table.querySelector('[data-post="total"]').textContent = data.total;
                    table.querySelector('[data-post="stock_out"]').textContent = data.stock_out;
                    table.querySelector('[data-post="price"]').textContent = convertToRupiah(data.price);
                    table.querySelector('[data-post="detail"]').setAttribute('onclick',`detailStock("${data.unit}", ${data.id})`)
                    document.querySelector('.table').querySelector('tbody').appendChild(table);
                })
            }
        })
    }

    $('[name="cabang"]').trigger('change');
    $('[name="id_unit"]').trigger('change');
    $('[name="area"]').trigger('change');


    const excel = (event) =>{
        const date_start = event.target.closest('form').querySelector('[name="date_start"]').value;
        const date_end = event.target.closest('form').querySelector('[name="date_end"]').value;
        const id_unit = $('[name="id_unit"]').val();
        const id_cabang = $('[name="cabang"]').val();
        const id_area = $('[name="area"]').val();
        window.location.href = `<?php echo base_url('report/stockslm/export');?>?date_start=${date_start}&date_end=${date_end}&id_area=${id_area}&id_unit=${id_unit}&id_cabang=${id_cabang}`
    }

    const pdf = (event) =>{
        const date = event.target.closest('form').querySelector('[name="date_end"]').value;
        const date_start = event.target.closest('form').querySelector('[name="date_start"]').value;
        const id_unit = $('[name="id_unit"]').val();
        const id_cabang = $('[name="cabang"]').val();
        const id_area = $('[name="area"]').val();
        window.location.href = `<?php echo base_url('report/stockslm/pdf');?>?date=${date}&date_start=${date_start}&id_area=${id_area}&id_unit=${id_unit}&id_cabang=${id_cabang}`
    }
    
  

    const detailStock = (unit, weight)=>{
        const dateStart = $('[name="date_start"]').val();
        const dateEnd = $('[name="date_end"]').val();
        window.location.href = 
        `<?php echo base_url('report/stockslm/detail?unit=');?>${unit}&weight=${weight}&date_start=${dateStart}&date_end=${dateEnd}`;
    }

    $(document).ready(function(){        
        $('#form_bukukas').trigger('submit');
    })
</script>