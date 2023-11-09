<script>
	let months = [ "January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December" ];
	let grams = {};
	let idChoice = "<?php echo $this->input->get('choice');?>";
	let sum = 0;
	let getDates =  '';
	const id_unit = document.querySelector('[name="id_unit"]').value;

	class Store{
		getGrams(){
			$.ajax({
				url:"<?php echo base_url();?>/api/lm/grams?id_unit="+id_unit,
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
			const tmpUnit =  document.querySelector('.type-unit');
			if(event.target.value === 'employee'){
				document.querySelector('[name="id_employee"]').setAttribute('required', true);
				tmpEmployee.classList.remove('d-none');
				tmpCustomer.classList.add('d-none');
				tmpUnit.classList.add('d-none');
			}else if(event.target.value === 'customer'){
				tmpEmployee.classList.add('d-none');
				tmpCustomer.classList.remove('d-none');
				tmpUnit.classList.add('d-none');
				document.querySelector('[name="id_employee"]').value = 0;
				document.querySelector('[name="id_employee"]').removeAttribute('required', true);
			}else if(event.target.value === 'unit'){
				document.querySelector('[name="to_unit"]').setAttribute('required', true);
				tmpEmployee.classList.add('d-none');
				tmpCustomer.classList.add('d-none');
				tmpUnit.classList.remove('d-none');
				document.querySelector('[name="id_employee"]').value = 0;
				document.querySelector('[name="id_employee"]').removeAttribute('required', true);
	
			}else{
				tmpEmployee.classList.add('d-none');
				tmpCustomer.classList.add('d-none');
				tmpUnit.classList.add('d-none');
				document.querySelector('[name="id_employee"]').value = 0;
				document.querySelector('[name="id_employee"]').removeAttribute('required', true);
			}
		}
		appendGram(data){
			grams = data;
			let groupby = [];
            data.forEach(data=>{
                if(!groupby[data.type]){
                    groupby[data.type] = [];
                }
                groupby[data.type].push(data);
            });
            const keys = Object.keys(groupby);
            const values = Object.values(groupby);

            values.forEach((data, i)=>{
                const optGroup = document.createElement('optgroup');
                optGroup.label =  keys[i];
                data.forEach(lm=>{                    
                    const opt = document.createElement('option');
                    opt.textContent =   `${lm.weight} gram Rp ${lm.price_perpcs}`;
                    opt.value = lm.id;
                    optGroup.appendChild(opt);
                })
				document.querySelector('.search-purchase').appendChild(optGroup);
            });
		}
		getChoice(id){
			const getGramById = grams.filter(gram=>{
				return gram.id == id
			});
			if(getGramById.length > 0){
				this.appendChoice(getGramById[0]);
			}
			checkStock();
		}
		appendChoice(data){
			const {id, price_buyback_perpcs, price_perpcs, weight, stock, amount, id_series} = data;
			const append = document.querySelector('[data-append="choice"]');
			const template = document.querySelector('[data-template="choice"]').cloneNode(true);
			template.setAttribute('data-template','choice-cloned');
			template.querySelector('.id_lm_gram').value = id;
			template.querySelector('.price_perpcs').value = price_perpcs;
			template.querySelector('.price_perpcs').setAttribute('name','gram['+sum+'][price_perpcs]');
			template.querySelector('.price_buyback_perpcs').value = price_buyback_perpcs;
			template.querySelector('.price_buyback_perpcs').setAttribute('name','gram['+sum+'][price_buyback_perpcs]');
			template.querySelector('.amount').setAttribute('name','gram['+sum+'][amount]');
			template.querySelector('.amount').value=amount ? amount : 1;
			template.querySelector('.amount').setAttribute('onKeyup','(new Store()).calculate()');
			template.querySelector('.price_perpcs').setAttribute('onKeyup','(new Store()).calculate()');
			template.querySelector('.total').setAttribute('name','gram['+sum+'][total]');
			template.querySelector('[data-post="id_lm_gram"]').textContent = weight;
			template.querySelector('.id_lm_gram').setAttribute('name','gram['+sum+'][id_lm_gram]');
			template.querySelector('[data-post="stock"]').textContent =stock;
			template.querySelector('.stock').value =stock;
			template.querySelector('[data-post="series"]').setAttribute('required', true);
			template.querySelector('[data-post="series"]').setAttribute('name', 'gram['+sum+'][id_series]');
			template.querySelector('[data-post="description"]').setAttribute('name', 'gram['+sum+'][description]');
		
		
			id_series ? 	template.querySelector('[data-post="series"]').value = id_series : '';
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
	
	document.addEventListener('DOMOnload', (new Store()).getGrams());
	const checkStock = () =>{
		const trs = document.querySelector('[ data-append="choice"]').querySelectorAll('[ data-template="choice-cloned" ]');
		trs.forEach(el=>{
			const getStock = parseInt(el.querySelector('.stock').value);
			const getAmount = parseInt(el.querySelector('.amount').value);
			const checkStock = getAmount > getStock;
			if(checkStock){
				el.querySelector('.amount').value = 0;
				swal.fire('stock kurang atau tidak tersedia.. silahkan cek stock anda.');
			}
		})
	}

	const initSelectSeries = () =>{
		const select = document.querySelector('[data-post="series"]');
		if(select){
			$.ajax({
				url:"<?php echo base_url();?>/api/lm/series",
				type:"get",
				dataType:"JSON",
				success:function(response){
					response.data.forEach(data=>{
						const seriesOption = document.createElement('option');
						seriesOption.value = data.id;
						seriesOption.textContent = data.series;
						select.appendChild(seriesOption)
					})
				}
			});
		}
	}
	initSelectSeries();


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
			const id = document.querySelector('[name="id"]').value;
			$.ajax({
				url:id > 0 ?
				"<?php echo base_url();?>/api/lm/transactions/update"
				 : "<?php echo base_url();?>/api/lm/transactions/insert",
				data:data,
				type:"POST",
				success:function(response){
					location.href = '<?php echo base_url();?>/lm/sales';
					
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

	$(document).ready(function(){
		const id = document.querySelector('[name="id"]').value;
		if(id >0){
			$.ajax({
				url:"<?php echo base_url();?>/api/lm/transactions/show/"+id,
				type:"GET",
				dataType:"JSON",
				success:function(res){
					const { address, code, id_employee, id_unit, method, mobile, name, nik, tenor, total, 
					 type_buyer, type_transaction, details, date
					 } = res.data;
					 document.querySelector('[name="address"]').value = address;
					 document.querySelector('[name="code"]').value = code;
					 document.querySelector('[name="id_employee"]').value = id_employee;
					 document.querySelector('[name="id_unit"]').value = id_unit;
					 document.querySelector('[name="method"]').value = method;
					 document.querySelector('[name="mobile"]').value = mobile;
					 document.querySelector('[name="name"]').value = name;
					 document.querySelector('[name="nik"]').value = nik;
	 				 document.querySelector('[name="date"]').setAttribute('min', date);
					 document.querySelector('[name="date"]').value = date;
					 document.querySelector('[name="tenor"]').value = tenor;
					 document.querySelector('[name="total"]').value = total;
					 document.querySelector('[name="type_buyer"]').value = type_buyer;
					 document.querySelector('[name="type_transaction"]').value = type_transaction;
					 document.querySelector('[name="type_buyer"]').dispatchEvent(new Event('change'));
					 document.querySelector('[name="tenor"]').dispatchEvent(new Event('change'));
				},
				complete:function(res){
					const { details } = res.responseJSON.data;	
					if(details.length > 0){
						details.forEach(data=>{
							const getGramById = grams.find(gram=>{
								return gram.id == data.id_lm_gram
							});
							getGramById.amount = data.amount;
							getGramById.price_buyback_perpcs = data.price_buyback_perpcs;
							getGramById.price_perpcs = data.price_perpcs;
							getGramById.id_series = data.id_series;
							getGramById.stock = parseInt(data.amount)+parseInt(getGramById.stock);
							new Store().appendChoice(getGramById);
						})
					}
				}
			})
		}
	})


</script>
