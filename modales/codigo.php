<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Document</title>
	
</head>
<div class="advertencia" id="codigo" >
<body>

	<div class="codigo" id="advertencia" style="text-align: center;">
	    <div class="span">
	      <header>
	        <h1>CODIGO</h1>
	      </header>
	      <div>
	      	<p>Ingrese el codigo del pedido:</p>
	      	<input type="number" id="ingcodigo" placeholder="CODIGO" minlength="5" autocomplete="off">
	      </div>
	      <div class="options">
	        <input type="submit" onclick="CerrarModal('codigo')" value="CANCELAR" class="boton-rojo"/>
	        <input type="submit" id="btnAceptar" value="ACEPTAR" class="boton-verde"/>
	      </div>
	    </div>
    </div>
</body>
</div>
</html>