function getReporte(reporte, data = '') {
	$.ajax({
		url: 'php/ejecutar/getDatos.php',
		type: 'POST',
		data: { 
			'action': reporte,
			'data': data
		},
	})
	.done(function(data) {
		if (data == 'true'){
			window.open('reporte/reporte.xlsx' , '_blank');
		}
	})
	.fail(function() {
		console.log("error");
	})
	.always(function() {
		console.log("complete");
	});
	
}
