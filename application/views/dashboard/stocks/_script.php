<script>



function initCard(){
    $.ajax({
        url:"<?php echo base_url();?>/api/lm/stocks/unit/<?php echo $this->session->userdata('user')->id_unit;?>",
        type:"GET",
        dataType:"JSON",
        success:function(res){
            let total = 0;
            res.data.forEach(data=>{
                const temp = document.querySelector('[data-template="item"]').cloneNode(true);
                temp.classList.remove('d-none');
                temp.setAttribute('data-template','item-cloned');
                temp.querySelector('[data-post="amount"]').textContent = `${data.total} pcs`;
                temp.querySelector('[data-post="weight"]').textContent = `${data.weight} grams`;
                document.querySelector('.template-grams').appendChild(temp);
                total += parseInt(data.total);
            })
            const temp = document.querySelector('[data-template="item"]').cloneNode(true);
                temp.classList.remove('d-none');
                temp.setAttribute('data-template','item-cloned');
                temp.querySelector('[data-post="amount"]').textContent = `${total} pcs`;
                temp.querySelector('[data-post="weight"]').textContent = `Total`;
                document.querySelector('.template-grams').appendChild(temp);
        }
    })
}


initCard();


</script>