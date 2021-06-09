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
		$sql = ("SELECT clientes.nombre, 
		razonsocial, 
		agentes.codagente AS codigo_agente, 
		agentes.nombre AS agente, 
		clientes.telefono1 AS telefono_cliente, 
		clientes.telefono2 AS telefono_cliente_secundario, 
		dirclientes.direccion, 
		dirclientes.provincia,
		 dirclientes.ciudad FROM `clientes` LEFT JOIN agentes ON agentes.codagente = clientes.codagente INNER JOIN dirclientes ON dirclientes.codcliente = clientes.codcliente");
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
	 * Consulta para generar la cartera de clientes que faltan por pagar
	 */
	public function getReporteClientesCartera()
	{
		$spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();
		$sql = ("SELECT clientes.nombre, clientes.razonsocial, clientes.codagente as codigo_vendedor, agentes.nombre as nombre_vendedor, facturascli.fecha as fecha_facturacion, facturascli.vencimiento as fecha_vencimiento, facturascli.codigo, facturascli.observaciones, facturascli.total FROM `facturascli` INNER JOIN clientes ON clientes.codcliente = facturascli.codcliente left join agentes as agentes on agentes.codagente = clientes.codagente WHERE facturascli.pagada = 0 order by clientes.codagente");
		$this->consulta('SET NAMES utf8');
		# Escribir encabezado de los productos
		$encabezado = ["nombre","razonsocial","codigo_vendedor","nombre_vendedor","fecha_facturacion","fecha_vencimiento","codigo","observaciones","total"];
		# El último argumento es por defecto A1 pero lo pongo para que se explique mejor
		$sheet->fromArray($encabezado, null, 'A1');

		$resultado = $this->consulta($sql);
		$res = $resultado->fetchAll(PDO::FETCH_ASSOC);
		$numeroDeFila = 2;
		foreach ($res as $data ) {
			# Obtener los datos de la base de datos
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
			$sheet->setCellValueByColumnAndRow(1, $numeroDeFila, $nombre);
			$sheet->setCellValueByColumnAndRow(2, $numeroDeFila, $razonsocial);
			$sheet->setCellValueByColumnAndRow(3, $numeroDeFila, $codigo_vendedor);
			$sheet->setCellValueByColumnAndRow(4, $numeroDeFila, $nombre_vendedor);
			$sheet->setCellValueByColumnAndRow(5, $numeroDeFila, $fecha_facturacion);
			$sheet->setCellValueByColumnAndRow(6, $numeroDeFila, $fecha_vencimiento);
			$sheet->setCellValueByColumnAndRow(7, $numeroDeFila, $codigo);
			$sheet->setCellValueByColumnAndRow(8, $numeroDeFila, $observaciones);
			$sheet->setCellValueByColumnAndRow(9, $numeroDeFila, $total);
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
				WHERE DATE(facturascli.fecha) = :fecha
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



