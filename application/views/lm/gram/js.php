<script>
	let months = [ "January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December" ];
	let grams = {};
	let idChoice = "<?php echo $this->input->get('choice');?>";
	let sum = 0;
	let getDates =  '';
	class Store{
		getGrams(){
			$.ajax({
				url:"<?php echo base_url();?>/api/lm/grams",
				type:"GET",
				dataType: "JSON",
				success:function (response) {
					(new Store()).appendGram(response.data);
				},
				complete:function () {
					(new Store()).getChoice(idChoice);
				}
			})
		}
		handleType = (event) =>{
			const tmpEmployee = document.querySelector('.type-employee');
			const tmpCustomer =  document.querySelector('.type-customer');
			if(event.target.value === 'employee'){
				tmpEmployee.classList.remove('d-none');
				tmpCustomer.classList.add('d-none');
			}else if(event.target.value === 'customer'){
				tmpEmployee.classList.add('d-none');
				tmpCustomer.classList.remove('d-none');
				document.querySelector('[name="id_employee"]').value = 0;
			}else{
				tmpEmployee.classList.add('d-none');
				tmpCustomer.classList.add('d-none');
				document.querySelector('[name="id_employee"]').value = 0;
			}
		}
		appendGram(data){
			grams = data;
			data.forEach(data=>{
				const {weight, id, price_perpcs} = data;
				const opt = document.createElement('option');
				opt.text = weight+' Gram'+' Rp '+price_perpcs;
				opt.value = id;
				document.querySelector('.search-purchase').appendChild(opt);
			});
		}
		getChoice(id){
			const getGramById = grams.filter(gram=>{
				return gram.id == id
			});
			if(getGramById.length > 0){
				this.appendChoice(getGramById[0]);
			}
		}
		appendChoice(data){
			const {id, price_buyback_perpcs, price_perpcs, weight} = data;
			const append = document.querySelector('[data-append="choice"]');
			const template = document.querySelector('[data-template="choice"]').cloneNode(true);
			template.setAttribute('data-template','choice-cloned');
			template.querySelector('.id_lm_gram').value = id;
			template.querySelector('.price_perpcs').value = price_perpcs;
			template.querySelector('.price_perpcs').setAttribute('name','gram['+sum+'][price_perpcs]');
			template.querySelector('.price_buyback_perpcs').value = price_buyback_perpcs;
			template.querySelector('.price_buyback_perpcs').setAttribute('name','gram['+sum+'][price_buyback_perpcs]');
			template.querySelector('.amount').setAttribute('name','gram['+sum+'][amount]');
			template.querySelector('.amount').value=1;
			template.querySelector('.amount').setAttribute('onKeyup','(new Store()).calculate()');
			template.querySelector('.total').setAttribute('name','gram['+sum+'][total]');
			template.querySelector('[data-post="id_lm_gram"]').textContent = weight;
			template.querySelector('.id_lm_gram').setAttribute('name','gram['+sum+'][id_lm_gram]');
			template.querySelector('[data-post="price_perpcs"]').textContent =convertToRupiah(price_perpcs);
			template.classList.remove('d-none');
			append.append(template);
			sum++;
			this.calculate();
		}
		calculate(){
			const getElementChoices = document.querySelectorAll('[data-template="choice-cloned"]');
			let total = 0;
			getElementChoices.forEach(element=>{
				const amount = parseInt(element.querySelector('.amount').value);
				const pricePerpcs = parseInt(element.querySelector('.price_perpcs').value);
				element.querySelector('.total').value = amount * pricePerpcs;
				element.querySelector('[data-post="total"]').textContent = convertToRupiah(amount * pricePerpcs);
				total += amount * pricePerpcs;
			});
			document.querySelector('[name="total"]').value = total;
		}
		simulation(){
			const append = document.querySelector('.simulation').querySelector('tbody');
			const tr = append.querySelectorAll('tr');
			if(tr){
				tr.forEach(e=>e.remove())
			}
			const tenor = parseInt(document.querySelector('[name="tenor"]').value);
			const total = parseInt(document.querySelector('[name="total"]').value);
			const installment = total/tenor;
			let amount = 0;
			let date = '';
			if(getDates != ''){
				date = new Date(getDates);
			}else{
				date = new Date();
			}
			let template = '';
			let array = 0;
			for (let i = 0; i<tenor;i++){
				amount += installment;
				const no = i+1;
				const month = months[date.getMonth()];
				template += `<tr><td>${no}</td>
						<td>${month} ${date.getFullYear()}</td>
						<td>${convertToRupiah(installment)}</td>
					</tr>`;
				array++;
				date.setMonth(date.getMonth()+1);
			}
			document.querySelector('.simulation').querySelector('tfoot').innerHTML = `<tr><td colspan="2">Total</td>
						<td>${convertToRupiah(amount)}</td>
					</tr>`;
			append.innerHTML = template;
		}
	}
	document.addEventListener('DOMOnload', (new Store()).getGrams())

	document.querySelector('.search-purchase').addEventListener('change', (e)=>{
		const id = e.target.value;
		(new Store()).getChoice(id);
		document.querySelector('.search-purchase').classList.add('d-none');
	});

	function removeSimulationTr(){
		const tr = document.querySelector('.simulation').querySelector('tbody').querySelectorAll('tr');
		if(tr){
			tr.forEach(e=>e.remove())
		}
	}

	document.querySelector('[name=method]').addEventListener('change', e=> {
		const method = e.target.value;
		if(method === 'INSTALLMENT'){
			document.querySelector('[name="tenor"]').closest('.form-group').classList.remove('d-none');
			document.querySelector('.simulation').classList.remove('d-none');
			(new Store()).simulation();
		}else{
			document.querySelector('[name="tenor"]').closest('.form-group').classList.add('d-none');
			document.querySelector('.simulation').classList.add('d-none');
			removeSimulationTr();
		}
	});

	$('.form-input').on('submit', function (e) {
		e.preventDefault();
		const data = $('.form-input').serialize();
		if(document.querySelector('[name="total"]').value == 0){
			alert('pilih lm ')
		}else{
			$.ajax({
				url:"<?php echo base_url();?>/api/lm/transactions/insert",
				data:data,
				type:"POST",
				success:function(response){
					location.href = '<?php echo base_url();?>/lm/transactions';
				}
			});
		}
	})

	$(document).on('click', '.btn_delete', function () {
		this.closest('td').closest('tr').remove();
		(new Store()).calculate();
	})

	document.querySelector('[name="tenor"]').addEventListener('change', (new Store()).simulation);
	document.querySelector('.btn-plus').addEventListener('click', ()=>{
		document.querySelector('.search-purchase').classList.remove('d-none');
	});

	document.querySelector('[name="type_buyer"]').addEventListener('change', (event) => (new Store()).handleType(event));
</script>
