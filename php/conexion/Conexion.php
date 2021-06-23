<?php
/**
* 
*/
require_once 'ConfigBD.php';
class Conexion
{
	private $conexion;

	public function getConexion()
	{
		if (!isset($this->conexion)) {
			try {
				
				$this->conexion = new PDO(DRIVER .':host='. HOST_DB . ';dbname=' . NAME_DB , USER_DB, PASS_DB);
				$this->conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				return $this->conexion;
			} catch (PDOException $e) {
				die( $e->getMessage());
			}
		}
	}

	public function backup(){
		
		$fecha = date("Y-m-d"); //Obtenemos la fecha y hora para identificar el respaldo
	
		//cambiar ruta
		$salida_sql = '"C:\Users\yesid no niichan\Documents\yesid\Backup_'.$fecha.'.sql"'; 
		
		//Comando para genera respaldo de MySQL, enviamos las variales de conexion y el destino
		$dump = "mysqldump -h " . HOST_DB . " -u ". USER_DB . " --opt " . NAME_DB . " > " . $salida_sql;
		echo $dump;
		system($dump, $output); //Ejecutamos el comando para respaldo
		
		// $zip = new ZipArchive(); //Objeto de Libreria ZipArchive
		
		// //Construimos el nombre del archivo ZIP Ejemplo: mibase_20160101-081120.zip
		// $salida_zip = $db_name.'_'.$fecha.'.zip';
		
		// if($zip->open($salida_zip,ZIPARCHIVE::CREATE)===true) { //Creamos y abrimos el archivo ZIP
		// 	$zip->addFile($salida_sql); //Agregamos el archivo SQL a ZIP
		// 	$zip->close(); //Cerramos el ZIP
		// 	unlink($salida_sql); //Eliminamos el archivo temporal SQL
		// 	header ("Location: $salida_zip"); // Redireccionamos para descargar el Arcivo ZIP
		// 	} else {
		// 	echo 'Error'; //Enviamos el mensaje de error
		// }
	}
}