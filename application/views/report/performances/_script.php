<script>
	function initGraph(){
		$.ajax({
			url:"<?php echo base_url('api/transactions/regularpawns/performance');?>",
			dataType:'JSON',
			type:'GET',
			success:function(response){
					initBooking(response.data.booking);
					initOs(response.data.outstanding);
					initDpd(response.data.dpd);
					initPengeluaran(response.data.pengeluaran);
					initPendapatan(response.data.pendapatan);
					initPelunasan(response.data.repayment);
			}
		})
	}

	function initBooking(booking){
		let data = [];
		let i = 0;
		let tr = '<tr><td>Booking</td>'
		$.each(booking, function(index, os){			
			data.push({ x: new Date(<?php echo date('Y');?>, i, 1), y: parseInt(os/1000000), indexLabel: `${convertToRupiah(parseInt(os/1000000))}`, markerType: "triangle",  markerColor: "#6B8E23" });
			i++;
			tr += `<td>${convertToRupiah(os)}</td>`;
		})
		tr += '</tr>';
		$('.table-performance').find('tbody').append(tr);
		var chart = new CanvasJS.Chart("graph-booking", {
			theme: "light1", // "light1", "light2", "dark1", "dark2"
			animationEnabled: true,
			title:{
				text: "Booking Nasional - <?php echo date('Y');?>"   
			},
			axisX: {
				interval: 1,
				intervalType: "month",
				valueFormatString: "MMM"
			},
			axisY:{
				title: "Harga dalam juta",
				includeZero: true,
			},
			data: [{        
				type: "line",
				markerSize: 12,
				xValueFormatString: "MMM, YYYY",
				yValueFormatString: "###.#",
				dataPoints: data
			}]
		});
		chart.render();
	}

	function initPelunasan(pelunasan){
		let data = [];
		let i = 0;
		let tr = '<tr><td>Pelunasan</td>'
		$.each(pelunasan, function(index, os){			
			data.push({ x: new Date(<?php echo date('Y');?>, i, 1), y: parseInt(os/1000000), indexLabel: `${convertToRupiah(parseInt(os/1000000))}`, markerType: "triangle",  markerColor: "#6B8E23" });
			tr += `<td>${convertToRupiah(os)}</td>`;
			i++;
		})
		tr += '</tr>';
		$('.table-performance').find('tbody').append(tr);
		var chart = new CanvasJS.Chart("graph-rate", {
			theme: "light1", // "light1", "light2", "dark1", "dark2"
			animationEnabled: true,
			title:{
				text: "Pelunasan Nasional - <?php echo date('Y');?>"   
			},
			axisX: {
				interval: 1,
				intervalType: "month",
				valueFormatString: "MMM"
			},
			axisY:{
				title: "Harga dalam juta",
				includeZero: true,
			},
			data: [{        
				type: "line",
				markerSize: 12,
				xValueFormatString: "MMM, YYYY",
				yValueFormatString: "###.#",
				dataPoints: data
			}]
		});
		chart.render();
	}

	function initPengeluaran(pengeluaran){
		let data = [];
		let i = 0;
		let tr = '<tr><td>Pengeluaran</td>'
		$.each(pengeluaran, function(index, os){			
			data.push({ x: new Date(<?php echo date('Y');?>, i, 1), y: parseInt(os/1000000), indexLabel: `${convertToRupiah(parseInt(os/1000000))}`, markerType: "triangle",  markerColor: "#6B8E23" });
			i++;
			tr += `<td>${convertToRupiah(os)}</td>`;
		})
		tr += '</tr>';
		$('.table-performance').find('tbody').append(tr);
		var chart = new CanvasJS.Chart("graph-pengeluaran", {
			theme: "light1", // "light1", "light2", "dark1", "dark2"
			animationEnabled: true,
			title:{
				text: "Pengeluaran Nasional - <?php echo date('Y');?>"   
			},
			axisX: {
				interval: 1,
				intervalType: "month",
				valueFormatString: "MMM"
			},
			axisY:{
				title: "Harga dalam juta",
				includeZero: true,
			},
			data: [{        
				type: "line",
				markerSize: 12,
				xValueFormatString: "MMM, YYYY",
				yValueFormatString: "###.#",
				dataPoints: data
			}]
		});
		chart.render();
	}

	function initPendapatan(pendapatan){
		let data = [];
		let i = 0;
		let tr = '<tr><td>Pendapatan</td>'
		$.each(pendapatan, function(index, os){			
			data.push({ x: new Date(<?php echo date('Y');?>, i, 1), y: parseInt(os/1000000), indexLabel: `${convertToRupiah(parseInt(os/1000000))}`, markerType: "triangle",  markerColor: "#6B8E23" });
			i++;
			tr += `<td>${convertToRupiah(os)}</td>`;
		})
		tr += '</tr>';
		$('.table-performance').find('tbody').append(tr);
		var chart = new CanvasJS.Chart("graph-pendapatan", {
			theme: "light1", // "light1", "light2", "dark1", "dark2"
			animationEnabled: true,
			title:{
				text: "Pendapatan Nasional - <?php echo date('Y');?>"   
			},
			axisX: {
				interval: 1,
				intervalType: "month",
				valueFormatString: "MMM"
			},
			axisY:{
				title: "Harga dalam juta",
				includeZero: true,
			},
			data: [{        
				type: "line",
				markerSize: 12,
				xValueFormatString: "MMM, YYYY",
				yValueFormatString: "###.#",
				dataPoints: data
			}]
		});
		chart.render();
	}

	function initDpd(dpd){
		let data = [];
		let i = 0;
		
		let tr = '<tr><td>DPD</td>'
		$.each(dpd, function(index, os){			
			data.push({ x: new Date(<?php echo date('Y');?>, i, 1), y: parseInt(os/1000000), indexLabel: `${convertToRupiah(parseInt(os/1000000))}`, markerType: "triangle",  markerColor: "#6B8E23" });
			i++;
			
			tr += `<td>${convertToRupiah(os)}</td>`;
		})
		tr += '</tr>';
		$('.table-performance').find('tbody').append(tr);
		var chart = new CanvasJS.Chart("graph-dpd", {
			theme: "light1", // "light1", "light2", "dark1", "dark2"
			animationEnabled: true,
			title:{
				text: "Dpd Nasional - <?php echo date('Y');?>"   
			},
			axisX: {
				interval: 1,
				intervalType: "month",
				valueFormatString: "MMM"
			},
			axisY:{
				title: "Harga dalam juta",
				includeZero: true,
			},
			data: [{        
				type: "line",
				markerSize: 12,
				xValueFormatString: "MMM, YYYY",
				yValueFormatString: "###.#",
				dataPoints: data
			}]
		});
		chart.render();
	}

	function initOs(outstandings){		
		let data = [];
		let i = 0;
		
		let tr = '<tr><td>Outstanding</td>'
		$.each(outstandings, function(index, os){			
			data.push({ x: new Date(<?php echo date('Y');?>, i, 1), y: parseInt(os/1000000), indexLabel: `${convertToRupiah(parseInt(os/1000000))}`, markerType: "triangle",  markerColor: "#6B8E23" });
			i++;
			tr += `<td>${convertToRupiah(os)}</td>`;
		})
		tr += '</tr>';
		$('.table-performance').find('tbody').append(tr);
		var chart = new CanvasJS.Chart("graph-os", {
			theme: "light1", // "light1", "light2", "dark1", "dark2"
			animationEnabled: true,
			title:{
				text: "Oustanding Nasional - <?php echo date('Y');?>"   
			},
			axisX: {
				interval: 1,
				intervalType: "month",
				valueFormatString: "MMM"
			},
			axisY:{
				title: "Harga dalam juta",
				includeZero: true,
			},
			data: [{        
				type: "line",
				markerSize: 12,
				xValueFormatString: "MMM, YYYY",
				yValueFormatString: "###.#",
				dataPoints: data
			}]
		});
		chart.render();

	}
	$(document).ready(function(){
		initGraph();
	})
</script>