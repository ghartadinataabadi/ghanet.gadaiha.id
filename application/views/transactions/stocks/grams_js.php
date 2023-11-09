<script>
    $('#area').select2({ placeholder: "Select Area", width: '100%' });
    $('#unit').select2({ placeholder: "Select Unit", width: '100%' });
    $('#status').select2({ placeholder: "Select a Status", width: '100%' });

    const searchHandler = (event)=>{
        event.preventDefault();
        $.ajax({
            url:"<?php echo base_url();?>/api/lm/stocks/grams",
            data:{
                date_start:event.target.querySelector('[name="date_start"]').value,
                date_end:event.target.querySelector('[name="date_end"]').value,
                id_unit:event.target.querySelector('[name="id_unit"]').value,
            },
            dataType:"JSON",
            type:"GET",
            success:function(res){
                const cloneds = document.querySelector('.table').querySelectorAll('[data-template="item-cloned"]');
                if(cloneds.length > 0){
                    cloneds.forEach(el=>el.remove());
                }
                let totalWeight = 0;
                let totalPieces = 0;
                res.data.forEach(data=>{
                    const table = document.querySelector('.table').querySelector('[data-template="item"]').cloneNode(true);
                    table.classList.remove('d-none');
                    table.setAttribute('data-template','item-cloned');
                    table.querySelector('[data-post="weight"]').textContent = data.weight+' grams';
                    table.querySelector('[data-post="stock_begin"]').textContent = data.stock_begin;
                    table.querySelector('[data-post="stock_in"]').textContent = data.stock_in;
                    table.querySelector('[data-post="total"]').textContent = data.total;
                    table.querySelector('[data-post="stock_out"]').textContent = data.stock_out;
                    table.querySelector('[data-post="total-weight"]').textContent = (data.total*data.weight).toFixed(2);
                    document.querySelector('.table').querySelector('tbody').appendChild(table);
                    totalWeight += data.total*data.weight;
                    totalPieces += parseInt(data.total);
                })
                let tr = '<tr data-template="item-cloned">'
                tr += `<td colspan="4">Total</td>`
                tr += `<td >${totalPieces}</td>`
                tr += `<td>${totalWeight.toFixed(2)}</td>`
                tr += '</tr>'
                $('.table-grams').find('tbody').append(tr);
            }
        })
    }

    $('#form_bukukas').trigger('submit');

    const excel = (event) =>{
        const date_start = event.target.closest('form').querySelector('[name="date_start"]').value;
        const date_end = event.target.closest('form').querySelector('[name="date_end"]').value;
        const id_unit =  event.target.closest('form').querySelector('[name="id_unit"]').value;
        window.location.href = `<?php echo base_url('transactions/stocks/export');?>?date_start=${date_start}&date_end=${date_end}&id_unit=${id_unit}`
    }
    const pdf = (event) =>{
        const date_start = event.target.closest('form').querySelector('[name="date_start"]').value;
        const date_end = event.target.closest('form').querySelector('[name="date_end"]').value;
        const id_unit =  event.target.closest('form').querySelector('[name="id_unit"]').value;
        window.location.href = `<?php echo base_url('transactions/stocks/pdf');?>?date_start=${date_start}&date_end=${date_end}&id_unit=${id_unit}`
    }

    const uploadStock = ()=>{
        $('.modal-stock').modal('show');
    }

    const viewStockImage = ()=>{
        const id_unit = "<?php echo $this->session->userdata('user')->id_unit;?>";
        $('.modal-stock-image').find('tbody').find('tr').remove();
        $.ajax({
            type: 'GET',
            url: "<?php echo base_url('api/lm/stocks/images');?>",
            data:{id_unit},
            dataType:"JSON",
            success : function(res){   
                let template = '';
                if(res.data){
                    res.data.forEach(data=>{
                        template += `<tr>`;
                        template += `<td>${data.unit}</td>`;
                        template += `<td><image src="<?php echo base_url('storage/stocks');?>/${data.filename}"
                            class="img-fluid"
                            style="width:50%"
                         /></td>`;
                         template += `<td>${data.date}</td>`;
                        template += `</tr>`;
                    })
                }
                $('.modal-stock-image').find('tbody').append(template);
            },
            complete:function(res){
                $('.modal-stock-image').modal('show');
            },
            error: function (jqXHR, textStatus, errorThrown){
           }
        }); 
      
    }

    const fileHander = (event)=>{
        const file = event.target.files[0];
        const size = file.size;
        const type = file.type.split('/')[0];
        const id_unit = "<?php echo $this->session->userdata('user')->id_unit;?>";
        $('.modal-stock').find('#btn_add_submit').attr('disabled',true);
        if(size > 1000000){
            swal.fire('Perhatikan','Ukuran image lebih dari 1mb','error')
            return;
        }
        if(type !== 'image'){
            swal.fire('Perhatikan','File harus berupa image','error')
            return;
        }
        const form = new FormData();
        form.append('image', file);
        form.append('id_unit', id_unit);
        $.ajax({
            type : 'POST',
            url : "<?php echo base_url('api/lm/stocks/upload');?>",
            data :form,
            processData: false,
            contentType: false,
            typeData:"JSON",
            success : function(res){   
                swal.fire('Perhatikan','File Success diUpload','success')
            },
            complete:function(res){
                $('.modal-stock').find('#btn-miss').attr('disabled',false);
            },
            error: function (jqXHR, textStatus, errorThrown){
           }
        }); 
    }


</script>