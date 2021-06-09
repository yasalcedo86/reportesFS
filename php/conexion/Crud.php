<?php
require_once 'Conexion.php';

/**
* 
*/
class Crud extends Conexion
{
	public $conx;

	function __construct()
	{
		$this->conx = $this->getConexion();
	}

	public function lastId()
	{
		return $this->conx->lastInsertId();
	}
	public function consulta($sql,$parametros = "")
	{		
		try {
			$con = $this->conx->prepare($sql);
		
			if ($parametros === "") {
				$con->execute();
			}else{
				$con->execute($parametros);
			}
			return $con;

		} catch (Exception $e) {
			die('{"error":{"text":'. $e->getMessage() .'}}');
		}
		
	}

	public function consultaN($sql,$parametros = "")
	{
		$con = $this->conx->prepare($sql);
		if ($parametros === "") {
			$con->execute();
		}else{
			$con->execute($parametros);
		}
	}

	public function sendJson($stado, $data) {
		$array = [
			"estado" => $stado,
			"data" => $data
		];
		return json_encode ($array); 
	}

	public function getBackup() {
		$this->backup();
		return false;
	}

	

}


