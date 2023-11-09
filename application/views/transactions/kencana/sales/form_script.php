<script>
var products = [];
var carts = [];


const initKencanaProduct = (id_unit) => {
    return new Promise( async (resolve, reject) => {
        const {status, data} = await  $.ajax({
            type : 'POST',
            url : "<?php echo base_url("api/kencana/product"); ?>",
            cache: false,
            contentType: false,
            processData: false,
            dataType : "json",
        });  
        if(status === 200){
            products = data;
            products.forEach(product=>{
                const cloned = $('[data-template="product-item"]').clone();
                cloned.removeClass('d-none');
                cloned.find('.btn_cart').attr('onclick',`addCart(${product.id})`);
                cloned.find('.btn_cart').attr('data-prod',`${product.id}`);
                cloned.find('.btn-stock').text(`Stock ${product.stock ? product.stock : '0'} pcs`);
                  cloned.find('.btn-description').text(product.description);
                cloned.find('.btn-price').text(`Rp ${convertToRupiah(product.price_sale)}`);
                if(product.stock < 1){
                    cloned.find('.btn_cart').attr('disabled',true);
                }
                cloned.attr('data-template','product-item-cloned');
                cloned.find('h3').text(`${product.type} dengan karatase ${product.karatase} berat ${product.weight} gram`);
                cloned.find('img').attr('src', product.image_url);
                $('.list-product').append(cloned)
            });
            resolve(data)
        }else{
            reject('Something error');
        }
    });
}

$(".currency").inputmask('decimal', {
    'alias': 'numeric',
    'groupSeparator': ',',
    'autoGroup': true,
    'digits': 2,
    'radixPoint': ".",
    'digitsOptional': false,
    'allowMinus': false,
    'placeholder': ''
});

const addCart = (id) => {
    const product = products.find(prod=> parseInt(prod.id) === parseInt(id));
    const existCart =  carts.find(prod=> parseInt(prod.id) === parseInt(id));
    if(!existCart){
        product.quantity = 1;
        carts.push(product);
    }else{
        if(existCart.quantity+1 >= product.stock){
            $(`[data-prod="${id}"]`).attr('disabled',true);
            event.target.value = 0;
        }else{
            $(`[data-prod="${id}"]`).attr('disabled',false);
        }
        carts = carts.map((cart) => {
            if(cart.id == id){
                return {...cart, quantity: 1+ parseInt(cart.quantity) };
            }
            return {...cart}
        });
    }
    rebuild();
}

const rebuild = () => {
    $('tbody').find('[data-template="cart-item-cloned"]').remove();
    const totalQuantity = carts.reduce((prev, curr) => prev + parseInt(curr.quantity), 0);
    const totalPrice = carts.reduce((prev, curr) => prev + (parseInt(curr.quantity) * parseInt(curr.price_sale)), 0);
    document.querySelector('#total_quantity').value = totalQuantity;
    document.querySelector('#total_price').value = totalPrice;
    carts.forEach(cart=>buildCart(cart));
}

const buildCart = (cart) => {
    const cloned = $('[data-template="cart-item"]').clone();
    cloned.removeClass('d-none');
    cloned.find('[data-post="name"]').text(`${cart.type} dengan karatase ${cart.karatase} berat ${cart.weight} gram`);
    cloned.find('[data-post="id_kencana_product"]').val(cart.id);
    cloned.find('[data-post="id_kencana_product"]').attr('name',`cart[${cart.id}][id_kencana_product]`);
    cloned.find('[data-post="quantity"]').attr('name',`cart[${cart.id}][quantity]`);
    cloned.find('[data-post="quantity"]').attr('onchange',`quantityHandler(event, ${cart.id})`);
    cloned.find('[data-post="price-base"]').attr('name',`cart[${cart.id}][price_base]`);
    cloned.find('[data-post="price-base"]').attr('onchange',`priceBaseHandler(event, ${cart.id})`);
    cloned.find('[data-post="price-base"]').val(cart.price_base);
    cloned.find('[data-post="price-sale"]').attr('name',`cart[${cart.id}][price_sale]`);
    cloned.find('[data-post="price-sale"]').attr('onchange',`priceSaleHandler(event, ${cart.id})`);
    cloned.find('[data-post="price-sale"]').val(cart.price_sale);
    cloned.find('[data-post="description"]').val(cart.description);
    cloned.find('[data-post="description"]').attr('name',`cart[${cart.id}][description]`);
    cloned.find('[data-post="quantity"]').val(cart.quantity);
    cloned.find('[data-post="subtotal"]').val(cart.price_sale*cart.quantity);
    cloned.find('[data-post="subtotal"]').attr('name',`cart[${cart.id}][subtotal]`);
    cloned.attr('data-template','cart-item-cloned');
    $('tbody').append(cloned);
}

const quantityHandler =  (event, id) => {
    const quantity = parseInt(event.target.value);
    const product = products.find(f=>f.id == id);
    if(quantity >= product.stock){
        $(`[data-prod="${id}"]`).attr('disabled',true);
        event.target.value = 0;
    }else{
        $(`[data-prod="${id}"]`).attr('disabled',false);
    }
    carts = carts.map(cart=>{
        if(cart.id == id){
            return {...cart, quantity};
        }
        return cart;
    });
    rebuild();
}
const priceSaleHandler =  (event, id) => {
    const price_sale = parseInt(event.target.value);
    carts = carts.map(cart=>{
        if(cart.id == id){
            return {...cart, price_sale};
        }
        return cart;
    });
    rebuild();
}
const priceBaseHandler =  (event, id) => {
    const price_base = parseInt(event.target.value);
    carts = carts.map(cart=>{
        if(cart.id == id){
            return {...cart, price_base};
        }
        return cart;
    });
    rebuild();
}

const submitHandler = (event) => {
    event.preventDefault();
    var formdata = new FormData(event.target);
    var id = $('[name="id"]').val();
    var url = id ?  "<?php echo base_url("api/kencana/sales/update"); ?>" :  "<?php echo base_url("api/kencana/sales/insert"); ?>";
    $.ajax({
            type : 'POST',
            url : url,
            data : formdata,
			cache: false,
			contentType: false,
			processData: false,
            dataType : "json",
            success : function(res,status){
                KTApp.unblock('#modal_add .modal-content');
                if(res.data){
                    window.location.href = '<?php echo base_url('transactions/kencana/sales');?>';
                }else{
                    Swal.fire(res.message);
                }
            },
            error: function (jqXHR, textStatus, errorThrown){
                console.log('error')
                Swal.fire('Something wrong, please try again later');
           }
        });  
}

const initUnit = () => {
    return new Promise( async (resolve, reject) => {
        const {status, data} = await  $.ajax({
            type : 'POST',
            url : "<?php echo base_url("api/datamaster/units"); ?>",
            cache: false,
            contentType: false,
            processData: false,
            dataType : "json",
        });  
        if(status){
            $('[name="id_unit"]').find('option').remove();
            const option = data.reduce((prev, curr) => 
            prev + `<option value="${curr.id}">${curr.name}</option>` 
             , '');
            $('[name="id_unit"]').append(option);
            resolve(data)
        }else{
            reject('Something error');
        }
    });
}

const checkEdited = async () => {
    const id = $('[name="id"]').val();
    if(id === '') return;
    const {data} = await  $.ajax({
        type : 'GET',
        url : `<?php echo base_url("api/kencana/sales/show/"); ?>${id}`,
        cache: false,
        contentType: false,
        processData: false,
        dataType : "json",
    });  
    const { customer_name, customer_nik, customer_address, customer_phone, id_unit, products } = data;
    $('[name="id"]').val(id);
    $('[name="id_unit"]').val(id_unit);
    $('[name="customer_name"]').val(customer_name);
    $('[name="customer_nik"]').val(customer_nik);
    $('[name="customer_address"]').val(customer_address);
    $('[name="customer_phone"]').val(customer_phone);
    carts = products;
    rebuild();
}

$(document).ready(async function(){
    await initUnit();
    await checkEdited();
    const idUnit = '<?php echo $this->session->userdata('user')->id_unit;?>'
    await initKencanaProduct(idUnit);
});
</script>