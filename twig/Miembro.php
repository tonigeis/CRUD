<?php
class Miembro{
	private $id;
	private $nombre;
	private $apellidos;
	private $fechaNacimiento;

	public function __construct($id, $nombre, $apellidos, $fechaNacimiento){
		$this->id = $id;
      	$this->nombre = $nombre;
      	$this->apellidos = $apellidos;
      	$this->fechaNacimiento = $fechaNacimiento;
    }

	public function getId(){
		return $this->id;
	}

	public function setId($id){
		$this->id = $id;
	}

	public function getNombre(){
		return $this->nombre;
	}

	public function setNombre($nombre){
		$this->nombre = $nombre;
	}

	public function getApellidos(){
		return $this->apellidos;
	}

	public function setApellidos($apellidos){
		$this->apellidos = $apellidos;
	}

	public function getFechaNacimiento(){
		return $this->fechaNacimiento;
	}

	public function setFechaNacimiento($fechaNacimiento){
		$this->fechaNacimiento = $fechaNacimiento;
	}	
}
?>