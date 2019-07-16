<?php 
class folderFile 
{
	protected function __deleteFile($path){
		if(!is_dir($path)){
			if(!unlink($path)){
				return true;
			}
			else{
				return false;
			}
		}
	}
	protected function __truncateFolderR($path){
		$deletePathBase = false;
		if($this->__deleteR($path, $deletePathBase)){
			return true;
		}
		else{
			return false;
		}
	}
	protected function __deleteFolderR($path){
		if($this->__deleteR($path)){
			return true;
		}
		else{
			return false;
		}
	}
	protected function __deleteR($path, $deletePathBase = true){
		$path = rtrim($path, '/') .'/';
		$currentFolder = opendir($path);
		while(($contentFolder = readdir($currentFolder)) !== false){
			$fullPath = $path . $contentFolder;
			if($contentFolder != '.' && $contentFolder != '..'){
				if(is_dir($fullPath)){
					$this->__deleteR($fullPath);
				}
				else{
					if(!unlink($fullPath)){
						return false;
					}
				}
			}
		}
		closedir($currentFolder);
		if(!rmdir($path)){
			return false;
		}
		else{
			if($deletePathBase){
				return true;
			}
			else{
				if($this->__createFolder($path)){
					return true;
				}
				else{
					return false;
				}
			}
		}
	}
	protected function __copyR($pathSource, $pathDest){
		if(is_dir($pathSource)){
			$currentFolder=opendir($pathSource);
			while($file=readdir($currentFolder)){
				if($file!="." && $file!=".."){
					if(is_dir($pathSource."/".$file)){
						if(!is_dir($pathDest."/".$file)){
							mkdir($pathDest."/".$file);
						}
						$this->__copyR($pathSource."/".$file, $pathDest."/".$file);
					}
					else{
						if(!copy($pathSource."/".$file, $pathDest."/".$file)){
							return false;
						}
					}
				}
			}
			closedir($currentFolder);
			return true;
		}
		else{
			$arrayPathSource = explode("/" ,$pathSource);
			if(substr($pathDest, -1) == "/"){
				$pathDest = $pathDest.end($arrayPathSource);
			}
			else{
				$pathDest = $pathDest."/".end($arrayPathSource);
			}
			if(copy($pathSource, $pathDest)){
				return true;
			}
			else{
				return false;
			}
		}
	}
	protected function __moveFile($pathSource, $pathDest){
		if($this->__copyR($pathSource, $pathDest)){
			if($this->__deleteFile($pathSource)){
				return true;
			}
			else{
				return false;
			}
		}
		else{

			return false;
		}
	}	
	protected function __moveR($pathSource, $pathDest){
		if($this->__copyR($pathSource, $pathDest)){
			if($this->__truncateFolderR($pathSource)){
				return true;
			}
			else{
				return false;
			}
		}
		else{

			return false;
		}
	}
	protected function __createFolder($currentFolderpath, $mode=0777) {
		if(is_dir($currentFolderpath)){
			return false;
		}
		else{
			if(mkdir($currentFolderpath, $mode, true)){
				return true;
			}
			else{
				return false;
			}
		}
	}
	protected function __createFile($file,$arg){
		$newFile = fopen($file, "w+");
		if($newFile == false){
			return false;
		}
		else{
			if(fwrite($newFile,$arg) == false){
				return false;
			}
			else{
				if(fclose($newFile) == false){
					return false;
				}
				else{
					return true;
				}
			}
		}
	}
	protected function __editFile($file,$arg){
		$newFile = fopen($file, "a+");
		if($newFile == false){
			return false;
		}
		else{
			if(fwrite($newFile,$arg) == false){
				return false;
			}
			else{
				if(fclose($newFile) == false){
					return false;
				}
				else{
					return true;
				}
			}
		}
	}
	protected function __truncateFile($file){
		$newFile = fopen($file, "w+");
		if($newFile == false){
			return true;
		}
		else{
			if(fclose($newFile) == false){
				return false;
			}
			else{
				return true;
			}
		}
	}
	protected function __viewFile($file){
		//antes :

		// $content = file_get_contents($file);
		// return $content;

		// ahora :

		$fileContent = "";
		$fp = fopen($file,'r');
		while (!feof($fp)) {
			$fileContent .= fgets($fp);
		}
		return $fileContent;
	}
	protected function __getProperty($arg){
		return $property = $this->$arg;
	}
	protected function __createObj($parent, $arrayPath, $select){
		if(count($arrayPath) > 0){
			foreach ($arrayPath as $path) {
				switch ($select) {
					case 'is_dir': 
					$this->arrayObj[] = new folder($parent,$path);
					break;
					case 'is_file': 
					$this->arrayObj[] = new file($parent,$path);
					break;
				}
			}
		}
	}	
	protected function __getObj($arg){
		$argSearch = explode("/", $arg);
		if (empty(end($argSearch))){
			unset($argSearch[end(array_keys($argSearch))]);
		}
		if(count($argSearch) > 1){
			for ($i=0; $i < count($argSearch); $i++) { 
				if($i==0){
					$obj_ant = $this->__searchObj($argSearch[$i]);
				}
				else{
					$obj_act = $obj_ant->__searchObj($argSearch[$i]);
					$obj_ant = $obj_act;
				}
			}
			return $obj_act;
		}
		else{

			return $this->__searchObj($argSearch[0]);
		}
	}

	protected function __searchObj($arg){
		foreach ($this->arrayObj as $key => $obj) {
			$search = (explode("/", $obj->__getProperty('path')));
			foreach ($search as $k => $v) {
				if ($v == $arg){
					return $this->arrayObj[$key];
				}
			}
		}		
	}

	protected function __getObjBK($arg){
		foreach ($this->arrayObj as $key => $obj) {
			$search = (explode("/", $obj->__getProperty('path')));
			if (empty(end($search))){
				unset($search[end(array_keys($search))]);
			}
			if (end($search) == $arg){
				return $this->arrayObj[$key];
			}
		}
	}
	/**
	 * [getSize description] - calcula el peso (carpeta | archivo)
	 * @param  string $arg [description] - Espera la propiedad path (carpetas | archivos)
	 * @param  string $arg2  [description] - Define el objeto a procesar (carpetas | archivos) valores [ is_dir | is_file ]
	 * @return string [description] - Devuelve el tamaño del objeto  
	 */
	protected function __getSizeObj($arg,$arg2){
		$bytes = NULL;
		switch ($arg2) {
			case 'is_dir':
			if(count($arg) > 0){
				foreach ($arg as $path) {
					$bytes += filesize($path);
				} 
			}
			break;
			case 'is_file': 
			$bytes = filesize($arg);
			break;
		}
		if ($bytes >= 1073741824)
		{
			$bytes = number_format($bytes / 1073741824, 2) . ' GB';
		}
		elseif ($bytes >= 1048576)
		{
			$bytes = number_format($bytes / 1048576, 2) . ' MB';
		}
		elseif ($bytes >= 1024)
		{
			$bytes = number_format($bytes / 1024, 2) . ' KB';
		}
		elseif ($bytes > 1)
		{
			$bytes = $bytes . ' bytes';
		}
		elseif ($bytes == 1)
		{
			$bytes = $bytes . ' byte';
		}
		else
		{
			$bytes = '0 bytes';
		}
		return $bytes;
	}
	/**
	 * [getPerms description] - Trae todo los permisos completos
	 * @param  string $arg [description] - Se espera la propiedad path
	 * @return string      [description] - Devuelve Array permisos
	 */
	protected function __getPermsObj($arg){
		$perms = NULL;
		$arrayPerms = array();
		$fullPerms = fileperms($arg);
		switch ($perms & 0xF000) {
			case 0xC000: // Socket
			$info = 's';
			break;
			case 0xA000: // Enlace simbólico
			$info = 'l';
			break;
			case 0x8000: // Normal
			$info = 'r';
			break;
			case 0x6000: // Bloque especial
			$info = 'b';
			break;
			case 0x4000: // Directorio
			$info = 'd';
			break;
			case 0x2000: // Carácter especial
			$info = 'c';
			break;
			case 0x1000: // Tubería FIFO pipe
			$info = 'p';
			break;
			default: // Desconocido
			$info = 'u';
		}
		// Propietario
		$info .= (($fullPerms & 0x0100) ? 'r' : '-');
		$info .= (($fullPerms & 0x0080) ? 'w' : '-');
		$info .= (($fullPerms & 0x0040) ? (($fullPerms & 0x0800) ? 's' : 'x' ) : (($fullPerms & 0x0800) ? 'S' : '-'));
		// Grupo
		$info .= (($fullPerms & 0x0020) ? 'r' : '-');
		$info .= (($fullPerms & 0x0010) ? 'w' : '-');
		$info .= (($fullPerms & 0x0008) ? (($fullPerms & 0x0400) ? 's' : 'x' ) : (($fullPerms & 0x0400) ? 'S' : '-'));
		// Mundo
		$info .= (($fullPerms & 0x0004) ? 'r' : '-');
		$info .= (($fullPerms & 0x0002) ? 'w' : '-');
		$info .= (($fullPerms & 0x0001) ? (($fullPerms & 0x0200) ? 't' : 'x' ) : (($fullPerms & 0x0200) ? 'T' : '-'));	
		$arrayPerms['octal'] = substr(sprintf('%o', fileperms($arg)), -4);
		$arrayPerms['string'] = $info;
		return $arrayPerms;
	}
	/**
	 * [getDirR description] - Busca todo los archivos y carpetas de un directorio recursivamente
	 * @param  string $dir      [description] - Se espera la propiedad path
	 * @param  array  &$results [description] - Array Recursivo (carpetas, archivos) 
	 * @return array           [description] - Devuelve Array path (archivos, carpetas)
	 */
	protected function __getDirR($dir, &$results = array()){
		$arrayFullDir = scandir($dir);
		foreach($arrayFullDir as $element){
			$path = realpath($dir.DIRECTORY_SEPARATOR.$element);
			if(!is_dir($path)) {
				$results['file'][] = $this->__filterPath($path, 'is_file');
			} 
			else if($element != '.' && $element != '..') {
				$this->__getDirR($path, $results);
				$results['dir'][] = $this->__filterPath($path, 'is_dir');
			}
		}
		return $results;
	}
	/**
	 * [getDirR description] - Busca todo los archivos y carpetas de un directorio recursivamente
	 * @param  string $dir      [description] - Se espera la propiedad path
	 * @return array           [description] - Devuelve Array path (archivos, carpetas)
	 */
	protected function __getDir($dir){
		$results = array();
		$arrayFullDir = scandir($dir);
		foreach($arrayFullDir as $element){
			$path = realpath($dir.DIRECTORY_SEPARATOR.$element);
			if(!is_dir($path)) {
				$results['file'][] = $this->__filterPath($path, 'is_file');
			} 
			else if($element != '.' && $element != '..') {
				$results['dir'][] = $this->__filterPath($path, 'is_dir');
			}
		}

		return $results;
	}
	/**
	 * [filterPath description] - Filtra la propiedad path si esta tiene una url incorrecta desde la creacion del objeto Directorio
	 * @param  string $arg  [description] - Se espera la propiedad path
	 * @param  string $arg2 [description] - Define el objeto a procesar (carpetas | archivos) valores [ is_dir | is_file ]
	 * @return string       [description] - Devuelve la propiedad path
	 */
	protected function __filterPath($arg,$arg2){
		
		$result = str_replace('\\', '/', $arg); 
		switch ($arg2) {
			case 'is_dir': 
			if(is_dir($arg)) {
				$result = $this->__autoCompletePath($result);
			}
			else{
				$isProyect = false;
				$arrayDir = explode('/', $result);
				$arrayProyect = explode('/',$this->__getOrigin('get_proyect'));
				$arraySubProyect = explode('/', $this->__getOrigin('get_sub_proyect'));
				for ($i=0; $i < 4; $i++) { 
					if($arrayDir[$i] == $arrayProyect[$i]){
						$isProyect = true;
					}
					else{
						$isProyect = false;
					}
				}
				if($isProyect){
					if ($arrayDir[4] == $arraySubProyect[4]) {
						$result = $this->__getOrigin('get_sub_proyect');
						if ($arrayDir[5] !== $arraySubProyect[5]) {
							$result = $this->__getOrigin('get_sub_proyect');
						}
					}
					else{
						$result = $this->__getOrigin('get_proyect');
					}
				}
				else{
					$result = $this->__getOrigin('get_proyect');

				}
			}
			$result = $this->__autoCompletePath($result);
			break;
			case 'is_file': 
			$result;
			break;
		}
		return $result;
	}
	/**
	 * [autoCompletePath description] - Se encarga de completar la propiedad path con el caracter '/' si es que no lo tiene
	 * @param  string $arg [description] - Se espera la propiedad path
	 * @return string      [description] - Devuelve la propiedad path
	 */
	protected function __autoCompletePath($arg){
		if(substr($arg, -1) !== '/' ){
			return $arg .= '/';
		}
		else{
			return $arg;
		}
	}
	/**
	 * [getOrigin description] - Se encarga de buscar el origen de un proyecto 
	 * @param  string $arg  [description] - Define que path se desea traer (proyecto  | sub proyecto) valores [ 'get_proyect' | 'get_sub_proyect' ]
	 * @return string       [description] - Devuelve la propiedad path
	 */
	protected function __getOrigin($arg){
		$path = dirname(__FILE__);
		$path = str_replace('\\', '/', $path); 
		$arrayPath = explode('/', $path);
		unset($arrayPath[end(array_keys($arrayPath))]);
		unset($arrayPath[end(array_keys($arrayPath))]);
		$path = implode('/', $arrayPath);
		switch ($arg) {
			case 'get_proyect':
			$return = $path;
			break;
			case 'get_sub_proyect':
			$return = $path.'/proyect';
			break;
		}
		return $return;
	}
}