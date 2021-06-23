<?php 
include '../clases/Reportes.php';
$reportes = new Reportes();
$data = $_POST['data'];
switch ($_POST['action']) {
	case 'clientes':
		echo $reportes->getReporteClientes();
		break;
	case 'diarioVentas':
		echo $reportes->getReporteVentasDiario($data);
		break;
	case 'clientesCartera':
		echo $reportes->getReporteClientesCartera();
		break;
	case 'quincenal':
		echo $reportes->getReporteQuincena($data);
		break;
	case 'backup':
		$reportes->getBackup();
		echo "false";
		break;
}

 ?>