<?php
class dirBase extends folderFile 
{
	protected $path;
	protected $size;
	protected $perms;
	protected $arrayObj;
	protected $arrayFile;
	protected $arrayFolder;
	/**
	 * [__construct description]
	 * @param string $arg [description] - Se espera path
	 */
	public function __construct($arg){
		$this->refreshInfo($arg);
	}
	/**
	 * [refreshInfo description] - Setea las propiedades
	 * @param  string $arg [description] - Se espera path, en su defecto llama a la propiedad path
	 */
	public function refreshInfo($arg = null){
		if(is_null($arg)) $arg = $this->path;
		unset($this->path);
		unset($this->size);
		unset($this->perms);
		unset($this->arrayObj);
		unset($this->arrayFile);
		unset($this->arrayFolder);
		$arrayPath =
		$arrayPathL = array();
		$this->path = $this->__filterPath($arg,'is_dir');
		$this->perms = $this->__getPermsObj($this->path);
		$arrayPath = $this->__getDirR($this->path);
		$filesR = $arrayPath['file'];
		unset($arrayPath);
		$this->size = $this->__getSizeObj($filesR,'is_dir');
		unset($files);
		$arrayPathL = $this->__getDir($this->path);
		if(!empty($arrayPathL['dir'])){
			foreach ($arrayPathL['dir'] as $key => $pathDir) {
				$this->arrayFolder[] = str_replace($this->path, "", $pathDir);
			}
			$this->__createObj($this,$arrayPathL['dir'],'is_dir');
		}
		if(!empty($arrayPathL['file'])){
			foreach ($arrayPathL['file'] as $key => $pathFile) {
				$this->arrayFile[] = str_replace($this->path, "", $pathFile);
			}
			$this->__createObj($this,$arrayPathL['file'],'is_file');
		}
	}
	/**
	 * [deleteFile description] - Elimina archivos, y refresca la informacion 
	 * @param  mix(string, array) $path [description] - Se espera un path o un array de path
	 */
	public function deleteFile($path){
		if(is_array($path)){
			foreach ($path as $newPath) {
				$this->__deleteFile($this->path.$newPath);
			}
		}
		else{
			$this->__deleteFile($this->path.$path);
		}
		$this->refreshInfo();
	}
	/**
	 * [truncateFolder description] - Vacia un o muchos directorio de su contenido (carpetas, archivos) y refresca la informacion 
	 * @param  mix(string, array) $path [description] - Se espera un path o un array de path, por defecto vacia el propio directorio
	 */
	public function truncateFolder($path = null){
		if(is_array($path)){
			foreach ($path as $newPath) {
				$this->__truncateFolderR($this->path.$newPath);
			}
		}
		else{
			$this->__truncateFolderR($this->path.$path);
		}
		$this->refreshInfo();
	}
	/**
	 * [deleteFolder description] - Elimina un o muchos directorio de su contenido (carpetas, archivos) y refresca la informacion
	 * @param  mix(string, array) $path [description] - Se espera un path o un array de path, por defecto elimina el propio directorio
	 */
	public function deleteFolder($path = null){
		if(is_array($path)){
			foreach ($path as $newPath) {
				$this->__deleteFolderR($this->path.$newPath);
			}
		}
		else{
			$this->__deleteFolderR($this->path.$path);
		}
		$this->refreshInfo();
	}
	/**
	 * [copyR description] - copia un objeto (carpetas, archivos) hacia un o muchos directorio y refresca la informacion
	 * @param  mix(string, array) $pathDest [description] - Se espera un path o un array de path, para el destino de la copia  
	 */
	public function copyR($pathDest){
		if(is_array($pathDest)){
			foreach ($pathDest as $newPath) {
				$this->__copyR($this->path, $newPath);
			}
		}
		else{
			$this->__copyR($this->path, $pathDest);
		}		
		$this->refreshInfo();
	}
	/**
	 * [copyR description] - Mueve un objeto (carpetas, archivos) hacia un nuevo directorio o muchos directorios y refresca la informacion
	 * @param  mix(string, array) $path [description] - Se espera un path o un array de path, para poder distribuir en diferentes directorios lo que se desea mover
	 */
	public function moveR($pathDest){
		if(is_array($pathDest)){
			foreach ($pathDest as $newPath) {
				$this->__moveR($this->path, $newPath);
			}
		}
		else{
			$this->__moveR($this->path, $pathDest);
		}		
		$this->refreshInfo();
	}
	/**
	 * [createFolder description] - Crea uno o muchos directorios y refresca la informacion
	 * @param  mix(string, array) $path [description] - Se espera un path o un array de path para crear carpetas
	 */
	public function createFolder($newFolder){
		if(is_array($newFolder)){
			foreach ($newFolder as $newPath) {
				$this->__createFolder($this->path.$newPath);
			}
		}
		else{
			$this->__createFolder($this->path.$newFolder);
		}
		$this->refreshInfo();
	}
	/**
	 * [createFile description] - Crea archivos y carga su contenido y refresca la informacion
	 * @param  mix(string, array) $newFile [description] - Se espera un path o un array de path para crear archivos
	 * @param  mix(string, array) $arg     [description] - Se espera el contenido o un array de contenidos para cargar uno o muchos archivos
	 */
	public function createFile($newFile, $arg = null){
		if(is_array($newFile)){
			for ($i=0; $i < count($newFile); $i++) { 
				if(is_array($arg)){
					$this->__createFile($this->path.$newFile[$i], $arg[$i]);
				}else{
					$this->__createFile($this->path.$newFile[$i], $arg);
				}
			}
		}
		else{
			$this->__createFile($this->path.$newFile, $arg);
		}		
		$this->refreshInfo();
	}
	/**
	 * [getObj description] - Trae un objeto (archivo, carpetas)
	 * @param  string $arg [description] - Se espera un objeto de las propiedades [arrayFile, arrayFolder]. Se puede llamar carpetas dentro de otras ej: 'a/b/c'
	 * @return objet      [description] - Retorna un objeto 
	 */
	public function getObj($arg){
		return $this->__getObj($arg);
	}
	/**
	 * [getInfo description] Refresca la informacion y trae la informacion del modelo.
	 * @return array [description] - Retorna las propiedades (path,size,perms,arrayFile,arrayFolder)
	 */
	public function getInfo(){
		$this->refreshInfo();
		$arrayInfo = array();
		$arrayInfo['path'] = $this->path;
		$arrayInfo['size'] = $this->size;
		$arrayInfo['perms'] = $this->perms;

		if(!empty($this->arrayFile)){
			$arrayInfo['arrayFile'] = $this->arrayFile;
		}
		if(!empty($this->arrayFolder)){
			$arrayInfo['arrayFolder'] = $this->arrayFolder;
		}
		return $arrayInfo;
	}
	/**
	 * [getProperty description] - Trae la propiedad deseada
	 * @param  string $arg [description] - Se espera una propiedad
	 * @return mix(string, array, objet) [description] - Retorna una propiedad
	 */
	public function getProperty($arg){
		return $this->__getProperty($arg);
	}
}