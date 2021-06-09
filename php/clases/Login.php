<?php


require_once '../conexion/Crud.php';
/**
*	Clase diseñada para el funcionamiento del Login y Registro 
*/

class Login extends Crud
{
	
	public function userLogin($usu,$pass)
	{
		
		try
		{
			$pass_hash= sha1($pass); //Password encryption 
			$sql = ("SELECT `nick`, codagente FROM `fs_users` WHERE nick = :usu AND password = :pass ");

			$parametros = array('usu' => $usu , 'pass' => $pass_hash); 
			$resultado = $this->consulta($sql,$parametros);
			$count=$resultado->rowCount();
			$resultado = $resultado->fetch(PDO::FETCH_ASSOC);
			//return $count;
			if($count <> 0)
			{
				return $this->sendJson(true, $resultado);
			}else{
				return $this->sendJson(false, 'Error');
			}

		}
		catch(Exception $e) {
			return $this->sendJson(false, $e->getMessage());
		}

	}
}



