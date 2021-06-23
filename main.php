<?php session_start();
if (!isset($_SESSION['user_id'])) {
   header("location: index.php");
}
 ?>
<!DOCTYPE html>
<html lang="es">
  <head>
    <title>Pantalla Principal</title>
    <link rel="stylesheet" href="css/styles.css" type="text/css"/>
    <link rel="stylesheet" type="text/css" href="css/modales.css">

    
    <meta charset="utf-8">
  </head>
  <body>
    <?php   include 'modales/r_cliente.php';
            include 'modales/advertencia.php';
    ?>
</div>
    <style>
      input[type=button]{
        background-color: #58C44D;
        border: none;
        color: white;
        padding: 12px 22px;
        text-decoration: none;
        margin: 4px 2px;
        cursor: pointer;
      }
      input[type=date]{
        padding: 8px 8px;
        margin: 4px 2px;
      }
    </style>
    <header class="principal">

      <div onclick="abrirModal('advertencia');" class="back"><img src="img/Back.svg"/>

        <p>SALIR</p>
      </div>
      <h1>PANTALLA PRINCIPAL</h1>
    </header>
    <div id="container_main">
      <div class="reportes_div" >
        Generar respaldo de la base de datos: <br>
        <input type="button" value="generar" id="reporte_0" onclick="getReporte('backup')">
      </div>
      <div class="reportes_div" >
        Consulta para sacar el listado de todos los clientes que pertenecen a la empresa: <br>
        <input type="button" value="generar" id="reporte_1" onclick="getReporte('clientes')">
      </div>
      <div class="reportes_div" >
        Consulta para generar la cartera de clientes que faltan por pagar: <br>
        <input type="button" value="generar" id="reporte_2" onclick="getReporte('clientesCartera')">
      </div>
      <div class="reportes_div" >
        consulta pare reporte de venta diario por vendedor asignado: <br>
        <label >Fecha: </label>
        <input type="date" id="dateReport3" value="<?php echo date("Y-m-d"); ?>">
        <input type="button" value="generar" id="reporte_3">
      </div>
      <div class="reportes_div" >
        Reporte quincenal o mensual: <br>
        <label >Fecha inicial: </label>
        <input type="date" id="dateinitial4" value="<?php echo date("Y-m-d"); ?>">
        <label >Fecha final: </label>
        <input type="date" id="datefinal4" value="<?php echo date("Y-m-d"); ?>">
        <input type="button" value="generar" id="reporte_4" >
      </div>
    </div>
  </body>
</html>
<script src="js/jquery.js"></script>
<script src="js/modal.js"></script>
<script src="js/funciones.js"></script>
<script>
  $("#reporte_3").click( function() {
    getReporte('diarioVentas', $("#dateReport3").val());
  });
  $("#reporte_4").click( function() {
    const data = {
      dateini: $("#dateinitial4").val(),
      datefin: $("#datefinal4").val()
    };
    getReporte('quincenal', data);
  })
</script>