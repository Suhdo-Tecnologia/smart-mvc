<?php

namespace Suhdo;
use PDO;

class Mysql{

	private function Connect(){

		try {

		  return new PDO('mysql:host='.MYSQL_HOST.';dbname='.MYSQL_DB, MYSQL_USER, MYSQL_PASS);

		} catch(PDOException $e) {
		  
		  echo 'Mysql Error: ' . $e->getMessage();

		}

	}


	public static function Query($sql=null){

		$conn = new Mysql;
		$pdo = $conn->Connect();
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		return $pdo->query($sql);
	}


	public static function Read($sql=null){
		$consulta = Mysql::Query($sql);
		//$result = (object) array('lines'=>$consulta->rowCount(),'result'=> []);
		$result = array();
		while($data = $consulta->fetch(PDO::FETCH_ASSOC)){
			$result[] = (object)$data;
		}

		return (object) array('count'=>$consulta->rowCount(), 'result'=>(object) $result);


	}



	public static function Create($table='cadastros',$supressError=false){



		$fields = Mysql::Read("select column_name,data_type,character_maximum_length from information_schema.columns where table_name = '".$table."';");
		$data_fields = array();
		$data_fields_length = array();
		foreach($fields->result as $field){
			$data_fields[$field->column_name] = $field->data_type;
			$data_fields_length[$field->column_name] = $field->character_maximum_length;
		}



		$_POST['updated'] = date('Y-m-d H:i:s');
		if(postVar('status')<=1) $_POST['status'] = 1;

		$pdo_fields = array();
		$Query_str =  "INSERT INTO ".$table." (";

		foreach($_POST as $index=>$value){
			if(array_key_exists($index,$data_fields)){
				$Query_str .= $index.',';
			}
		}

		$Query_str = substr($Query_str,0,-1);
		$Query_str .=  " ) VALUES (";

		//print_r($_POST);

		if(postVar('status')<=1) $_POST['status'] = 1;

		foreach($_POST as $index=>$value){

			if(array_key_exists($index,$data_fields)){
				$Query_str .= ':'.$index.',';
				$pdo_fields[':'.$index] = Mysql::FormatField($value,$data_fields[$index],$data_fields_length[$index]);
			}
		}

		$Query_str = substr($Query_str,0,-1);
		$Query_str .=  ")";

		try {
		  $conn = new Mysql;
		  $pdo = $conn->Connect();
		  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		  $stmt = $pdo->prepare($Query_str);
		  $stmt->execute($pdo_fields);

		  if($stmt->rowCount()>0)
		  	return $pdo->lastInsertId();  //$stmt->rowCount();
		  else
		  	return 0;

		} catch(PDOException $e) {
		  if($supressError==false)
			  echo 'Mysql Error: ' . $e->getMessage();
		  else
			  return 'Mysql Error: ' . $e->getMessage();
		}


	}



	public static function Update($table='cadastros',$where_field='id',$supressError=false){


		$fields = Mysql::Read("select column_name,data_type,character_maximum_length from information_schema.columns where table_name = '".$table."';");
		$data_fields = array();
		$data_fields_length = array();
		foreach($fields->result as $field){
			$data_fields[$field->column_name] = $field->data_type;
			$data_fields_length[$field->column_name] = $field->character_maximum_length;
		}

		$_POST['updated'] = date('Y-m-d H:i:s');
		//$_POST['status'] = 1;
		
		if(postVar('status')<=1) $_POST['status'] = 1;

		$pdo_fields = array();
		$Query_str =  "UPDATE ".$table." SET ";


		foreach($_POST as $index=>$value){
			if(array_key_exists($index,$data_fields)){

			$Query_str .= $index.'= :'.$index.',';
			$pdo_fields[':'.$index] = Mysql::FormatField($value,$data_fields[$index],$data_fields_length[$index]);
			}
		}

		$Query_str = substr($Query_str,0,-1);
		$Query_str .=  ' WHERE '.$where_field."= :".$where_field;


		try {
		  $conn = new Mysql;
		  $pdo = $conn->Connect();
		  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		  $stmt = $pdo->prepare($Query_str);
		  $stmt->execute($pdo_fields);

		  return $stmt->rowCount();

		} catch(PDOException $e) {
		  if($supressError==false)
			  echo 'Mysql Error: ' . $e->getMessage();
		  else
			  return 'Mysql Error: ' . $e->getMessage();
		}


	}


	public static function Remove($table='cadastros',$where_field='id'){


	}

	private static function FormatField($field_value='',$field_type='',$field_length=255,$field_mask=''){

		switch($field_type){

			case "int":
				return (int) $field_value."";
			break;

			case "varchar":
				return (string) "".substr($field_value,0,$field_length)."";
			break;

			case "date":
				if($field_value=='') return '';
				return (string) "".date('Y-m-d',strtotime(datef($field_value)))."";
			break;

			default:
				return (string) "".$field_value."";
			break;

		}

	}


}

/*

tipo cadastro - select
exibit aceite
rsvp
timeline

*/

?>