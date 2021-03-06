<?php


require_once '../conexion/Crud.php';
require '../../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Reportes extends Crud
{

	/**
	 * Reporte para sacar el listado de todos los clientes que pertenecen a la empresa
	 */
	public function getReporteClientes()
	{
		$spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();
		$sql = ("SELECT 
		clientes.nombre,
		razonsocial,
		agentes.codagente AS codigo_agente,
		agentes.nombre AS agente,
		clientes.telefono1 AS telefono_cliente,
		clientes.telefono2 AS telefono_cliente_secundario,
		dirclientes.direccion,
		dirclientes.provincia,
		dirclientes.ciudad
	FROM
		`clientes`
			LEFT JOIN
		agentes ON agentes.codagente = clientes.codagente
			INNER JOIN
		dirclientes ON dirclientes.codcliente = clientes.codcliente
		where clientes.debaja = 0;");
		$this->consulta('SET NAMES utf8');
		# Escribir encabezado de los productos
		$encabezado = ["nombre","razonsocial","codigo_agente","agente","telefono_cliente","telefono_cliente_secundario","direccion","provincia","ciudad"];
		# El último argumento es por defecto A1 pero lo pongo para que se explique mejor
		$sheet->fromArray($encabezado, null, 'A1');

		$resultado = $this->consulta($sql);
		$res = $resultado->fetchAll(PDO::FETCH_ASSOC);
		$numeroDeFila = 2;
		foreach ($res as $data ) {
			# Obtener los datos de la base de datos
			$nombre = $data['nombre'];
			$razonsocial = $data['razonsocial'];
			$codigo_agente = $data['codigo_agente'];
			$agente = $data['agente'];
			$telefono_cliente = $data['telefono_cliente'];
			$telefono_cliente_secundario = $data['telefono_cliente_secundario'];
			$direccion = $data['direccion'];
			$provincia = $data['provincia'];
			$ciudad = $data['ciudad'];
			# Escribirlos en el documento
			$sheet->setCellValueByColumnAndRow(1, $numeroDeFila, $nombre);
			$sheet->setCellValueByColumnAndRow(2, $numeroDeFila, $razonsocial);
			$sheet->setCellValueByColumnAndRow(3, $numeroDeFila, $codigo_agente);
			$sheet->setCellValueByColumnAndRow(4, $numeroDeFila, $agente);
			$sheet->setCellValueByColumnAndRow(5, $numeroDeFila, $telefono_cliente);
			$sheet->setCellValueByColumnAndRow(6, $numeroDeFila, $telefono_cliente_secundario);
			$sheet->setCellValueByColumnAndRow(7, $numeroDeFila, $direccion);
			$sheet->setCellValueByColumnAndRow(8, $numeroDeFila, $provincia);
			$sheet->setCellValueByColumnAndRow(9, $numeroDeFila, $ciudad);
			$numeroDeFila++;
		}
		$writer = new Xlsx($spreadsheet);
		$writer->save('../../reporte/reporte.xlsx');
		echo "true";
	}


	/**
	 * consulta pare reporte de venta diario por vendedor asignado
	 */
	public function getReporteVentasDiario($data)
	{
		$spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();
		$sql = ("SELECT 
					facturascli.codpago,
					clientes.nombre,
					clientes.razonsocial,
					clientes.codagente AS codigo_vendedor,
					agentes.nombre AS nombre_vendedor,
					facturascli.fecha AS fecha_facturacion,
					facturascli.vencimiento AS fecha_vencimiento,
					facturascli.codigo,
					facturascli.observaciones,
					facturascli.total
				FROM
					`facturascli`
						INNER JOIN
					clientes ON clientes.codcliente = facturascli.codcliente
						LEFT JOIN
					agentes AS agentes ON agentes.codagente = clientes.codagente
				WHERE
					DATE(facturascli.fecha) = :fecha
				ORDER BY clientes.codagente;");
				
		$this->consulta('SET NAMES utf8');
		# Escribir encabezado de los productos
		$encabezado = ["codpago","nombre","razonsocial","codigo_vendedor","nombre_vendedor","fecha_facturacion","fecha_vencimiento","codigo","observaciones","total","fecha_pago"];
		# El último argumento es por defecto A1 pero lo pongo para que se explique mejor
		$sheet->fromArray($encabezado, null, 'A1');
		$parametros = array('fecha'=>$data);
		$resultado = $this->consulta($sql,$parametros);
		$res = $resultado->fetchAll(PDO::FETCH_ASSOC);
		$numeroDeFila = 2;
		foreach ($res as $data ) {
			# Obtener los datos de la base de datos
			$codpago = $data['codpago'];
			$nombre = $data['nombre'];
			$razonsocial = $data['razonsocial'];
			$codigo_vendedor = $data['codigo_vendedor'];
			$nombre_vendedor = $data['nombre_vendedor'];
			$fecha_facturacion = $data['fecha_facturacion'];
			$fecha_vencimiento = $data['fecha_vencimiento'];
			$codigo = $data['codigo'];
			$observaciones = $data['observaciones'];
			$total = $data['total'];
			# Escribirlos en el documento
			$sheet->setCellValueByColumnAndRow(1, $numeroDeFila, $codpago);
			$sheet->setCellValueByColumnAndRow(2, $numeroDeFila, $nombre);
			$sheet->setCellValueByColumnAndRow(3, $numeroDeFila, $razonsocial);
			$sheet->setCellValueByColumnAndRow(4, $numeroDeFila, $codigo_vendedor);
			$sheet->setCellValueByColumnAndRow(5, $numeroDeFila, $nombre_vendedor);
			$sheet->setCellValueByColumnAndRow(6, $numeroDeFila, $fecha_facturacion);
			$sheet->setCellValueByColumnAndRow(7, $numeroDeFila, $fecha_vencimiento);
			$sheet->setCellValueByColumnAndRow(8, $numeroDeFila, $codigo);
			$sheet->setCellValueByColumnAndRow(9, $numeroDeFila, $observaciones);
			$sheet->setCellValueByColumnAndRow(10, $numeroDeFila, $total);
			$numeroDeFila++;
		}
		$writer = new Xlsx($spreadsheet);
		$writer->save('../../reporte/reporte.xlsx');
		echo "true";
	}

	/**
	 * Consulta para generar la cartera de clientes que faltan por pagar
	 */
	public function getReporteClientesCartera()
	{
		$spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();
		$sql = "SELECT
					ag.nombre,
					cli.nombrecliente,
					clientes.razonsocial,
					cli.codigo,
					cli.codpago,
					cli.fecha,
					cli.vencimiento,
					TIMESTAMPDIFF(DAY, CURRENT_DATE(), cli.vencimiento) AS dias_vencidos,
					IF(Date(cli.vencimiento) < current_date(), 'Vencido', dayname(cli.vencimiento)) as dias,
					cli.observaciones,
					cli.total
				FROM
					facturascli cli
					inner join clientes on clientes.codcliente = cli.codcliente
					inner join agentes ag on ag.codagente = clientes.codagente 
				WHERE YEAR(vencimiento) >= '2020' and cli.pagada = 0;";
				
		$this->consulta('SET NAMES utf8');
		# Escribir encabezado de los productos
		$encabezado = ["nombre","nombrecliente","razonsocial","codigo","codpago","fecha","vencimiento","dias_vencidos","dias","observaciones","total"];
		# El último argumento es por defecto A1 pero lo pongo para que se explique mejor
		$sheet->fromArray($encabezado, null, 'A1');
		$resultado = $this->consulta($sql);
		$res = $resultado->fetchAll(PDO::FETCH_ASSOC);
		$numeroDeFila = 2;
		foreach ($res as $data ) {
			# Obtener los datos de la base de datos
			$nombre = $data['nombre'];
			$nombrecliente = $data['nombrecliente'];
			$razonsocial = $data['razonsocial'];
			$codigo = $data['codigo'];
			$codpago = $data['codpago'];
			$fecha = $data['fecha'];
			$vencimiento = $data['vencimiento'];
			$dias_vencidos = $data['dias_vencidos'];
			$dias = $data['dias'];
			$observaciones = $data['observaciones'];
			$total = $data['total'];
			# Escribirlos en el documento
			$sheet->setCellValueByColumnAndRow(1, $numeroDeFila, $nombre);
			$sheet->setCellValueByColumnAndRow(2, $numeroDeFila, $nombrecliente);
			$sheet->setCellValueByColumnAndRow(3, $numeroDeFila, $razonsocial);
			$sheet->setCellValueByColumnAndRow(4, $numeroDeFila, $codigo);
			$sheet->setCellValueByColumnAndRow(5, $numeroDeFila, $codpago);
			$sheet->setCellValueByColumnAndRow(6, $numeroDeFila, $fecha);
			$sheet->setCellValueByColumnAndRow(7, $numeroDeFila, $vencimiento);
			$sheet->setCellValueByColumnAndRow(8, $numeroDeFila, $dias_vencidos);
			$sheet->setCellValueByColumnAndRow(9, $numeroDeFila, $dias);
			$sheet->setCellValueByColumnAndRow(10, $numeroDeFila, $observaciones);
			$sheet->setCellValueByColumnAndRow(11, $numeroDeFila, $total);
			$numeroDeFila++;
		}
		$writer = new Xlsx($spreadsheet);
		$writer->save('../../reporte/reporte.xlsx');
		echo "true";
	}

	public function getReporteQuincena($data)
	{
		$spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();
		$sql = "SELECT 
				facturascli.codpago,
				clientes.nombre,
				clientes.razonsocial,
				clientes.codagente AS codigo_vendedor,
				agentes.nombre AS nombre_vendedor,
				facturascli.fecha AS fecha_facturacion,
				facturascli.vencimiento AS fecha_vencimiento,
				facturascli.codigo,
				facturascli.observaciones,
				facturascli.total,
				co_asientos.fecha AS fecha_pago
				FROM
					`facturascli`
						INNER JOIN
					clientes ON clientes.codcliente = facturascli.codcliente
						LEFT JOIN
					agentes AS agentes ON agentes.codagente = clientes.codagente
						INNER JOIN
					co_asientos ON co_asientos.idasiento = facturascli.idasientop
				WHERE
					facturascli.pagada = 1
						AND DATE(facturascli.fecha) >= :fecha1
						AND DATE(facturascli.fecha) <= :fecha2
				ORDER BY clientes.codagente;";
		$this->consulta('SET NAMES utf8');
		# Escribir encabezado de los productos
		$encabezado = ["codpago","nombre","razonsocial","codigo_vendedor","nombre_vendedor","fecha_facturacion","fecha_vencimiento","codigo","observaciones","total","fecha_pago"];
		# El último argumento es por defecto A1 pero lo pongo para que se explique mejor
		$sheet->fromArray($encabezado, null, 'A1');
		$parametros = array('fecha1'=>$data['dateini'], 'fecha2'=>$data['datefin']);
		$resultado = $this->consulta($sql,$parametros);
		$res = $resultado->fetchAll(PDO::FETCH_ASSOC);
		$numeroDeFila = 2;
		foreach ($res as $data ) {
			# Obtener los datos de la base de datos
			$codpago = $data['codpago'];
			$nombre = $data['nombre'];
			$razonsocial = $data['razonsocial'];
			$codigo_vendedor = $data['codigo_vendedor'];
			$nombre_vendedor = $data['nombre_vendedor'];
			$fecha_facturacion = $data['fecha_facturacion'];
			$fecha_vencimiento = $data['fecha_vencimiento'];
			$codigo = $data['codigo'];
			$observaciones = $data['observaciones'];
			$total = $data['total'];
			$fecha_pago = $data['fecha_pago'];
			# Escribirlos en el documento
			$sheet->setCellValueByColumnAndRow(1, $numeroDeFila, $codpago);
			$sheet->setCellValueByColumnAndRow(2, $numeroDeFila, $nombre);
			$sheet->setCellValueByColumnAndRow(3, $numeroDeFila, $razonsocial);
			$sheet->setCellValueByColumnAndRow(4, $numeroDeFila, $codigo_vendedor);
			$sheet->setCellValueByColumnAndRow(5, $numeroDeFila, $nombre_vendedor);
			$sheet->setCellValueByColumnAndRow(6, $numeroDeFila, $fecha_facturacion);
			$sheet->setCellValueByColumnAndRow(7, $numeroDeFila, $fecha_vencimiento);
			$sheet->setCellValueByColumnAndRow(8, $numeroDeFila, $codigo);
			$sheet->setCellValueByColumnAndRow(9, $numeroDeFila, $observaciones);
			$sheet->setCellValueByColumnAndRow(10, $numeroDeFila, $total);
			$sheet->setCellValueByColumnAndRow(11, $numeroDeFila, $fecha_pago);
			$numeroDeFila++;
		}
		$writer = new Xlsx($spreadsheet);
		$writer->save('../../reporte/reporte.xlsx');
		echo "true";
	}

	
	


	public function verPedidoDetalles($id)
	{
		try
		{
			$sql = ("SELECT * FROM `encargo_detalles` INNER JOIN encargos ON encargos.encargo_id = encargo_detalles.detalles_encargo_id WHERE `detalles_encargo_id`=:id");
			$this->consulta('SET NAMES utf8');
			$parametros = array('id'=>$id);
			$resultado = $this->consulta($sql,$parametros);
			$res = $resultado->fetchAll(PDO::FETCH_ASSOC);
			return json_encode($res, JSON_UNESCAPED_UNICODE);
			
		}
		catch(PDOException $e) {
			echo  $e->getMessage();
		}
	}
	public function verPedido($id)
	{
		try
		{
			$sql = ("SELECT * FROM `encargos` WHERE `encargo_id` = :id");
			$this->consulta('SET NAMES utf8');
			$parametros = array('id'=>$id);
			$resultado = $this->consulta($sql,$parametros);
			$res = $resultado->fetchAll(PDO::FETCH_ASSOC);
			return json_encode($res, JSON_UNESCAPED_UNICODE);
			
		}
		catch(PDOException $e) {
			echo  $e->getMessage();
		}
	}

}



