function login(usuario, pass) {

	$.ajax({
		url: 'php/ejecutar/login.php',
		type: 'POST',
        dataType: 'JSON',
		data: {user: usuario , pass: pass },
		success: function(data)
        {
            
            json = data;
			console.log(json);
        	if (json.estado){
                id_user = json.data.codagente;
                nombre_u = json.data.nick;
                $.post('php/ejecutar/sesion.php', {id: id_user, nombre: nombre_u }, function(data, textStatus, xhr) {
					location.href ="main.php";
                });
        		
        	}else{ 
        		$("#msg").text('Error Al Iniciar Sesion');
        	}
        	
        }
	});
	
}


$(document).ready(function() {
	$('#iniciar').on('click', function() {
		login($('#usuarioI').val(), $('#passI').val());
	});


	$('#registrar').on('click', function() {
		registro($('#usuarioR').val(), $('#passR').val(), $('#correo').val());
	});

	$('#cerrarM').on('click', function() {
		$("#alerta-usuario").css('display', 'none');
	});
});