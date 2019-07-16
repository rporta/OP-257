<?php
class file extends folderFile
{
	protected $parent;
	protected $path;
	protected $size;
	protected $perms;
	/**
	 * [__construct description]
	 * @param obj $parent [description] - Se espera un objeto pariente
	 * @param string $arg [description] - Se espera path
	 */	
	public function __construct($parent,$arg){
		$this->refreshInfo($parent,$arg);
	}
	/**
	 * [refreshInfo description] - Setea las propiedades
	 * @param obj $parent [description] - Se espera un objeto pariente, en su defecto llama a la propiedad parent
	 * @param  string $arg [description] - Se espera path, en su defecto llama a la propiedad path
	 */		
	public function refreshInfo($parent = null, $arg = null){
		if(is_null($parent)) $parent = $this->parent;
		if(is_null($arg)) $arg = $this->path;
		$this->parent = $parent;
		$this->path = $this->__filterPath($arg,'is_file');
		$this->size = $this->__getSizeObj($this->path,'is_file');
		$this->perms = $this->__getPermsObj($this->path);
	}
	public function editFile($arg){
		$this->__editFile($this->path, $arg);
		$this->parent->refreshInfo();
	} 
	public function truncateFile(){
		$this->__truncateFile($this->path);
		$this->parent->refreshInfo();
	} 
	public function viewFile(){
		$this->parent->refreshInfo();
		return $this->__viewFile($this->path);
	} 
	public function deleteFile(){
		$this->__deleteFile($this->path);
		$this->parent->refreshInfo();
	} 
	public function copyR($pathDest){
		$this->__copyR($this->path, $pathDest);
	}
	public function moveR($pathDest){
		$this->__moveFile($this->path, $pathDest);
		$this->parent->refreshInfo();
	}
	public function getInfo(){
		$this->refreshInfo();
		$arrayInfo = array();
		$arrayInfo['path'] = $this->path;
		$arrayInfo['size'] = $this->size;
		$arrayInfo['perms'] = $this->perms;
		return $arrayInfo;
	}
	public function getProperty($arg){
		return $this->__getProperty($arg);
	}
}