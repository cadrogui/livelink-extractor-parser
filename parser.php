<?php
/*
Author: Mikel Carozzi
E-mail: m.carozzi@gmail.com
License: http://www.gnu.org/licenses/gpl-3.0-standalone.html
*/

ini_set('memory_limit', '30000M');
set_time_limit(0);
error_reporting('E_ALL');

$basePath = "livelink-path-to-clone";
$f = $basePath. "xml/" .$argv[1]. ".xml";

$gestor = fopen($f, "r");
$contenido = fread($gestor, filesize($f));
fclose($gestor);

$xml = simplexml_load_string($contenido);

echo "-------------------------------------------------------\r\n";

$rootDir = "root-dir-to-create-livelink-tree";

$cmd = $argv[2];
$fileTarget = $argv[3];

parse($xml, 1, $rootDir, $cmd, $fileTarget);

/*

tree = create only a empty tree of the livelink 
file = create a full clone of the livelink

*/

function parse($xml, $level=1, $rootDir, $cmd, $fileTarget){	
				
		switch($cmd){
			
			case("tree"):
				
				foreach($xml->llnode as $node){
					
					echo trim(str_repeat("+ ", $level). "[" .strtoupper(defineFileType($node['mimetype'])). "] " .$node->name. "." .defineFileType($node['mimetype'])). "\r\n";
							
					if($node['objname'] != 'Document' && $node['objname'] != 'E-mail'){
						$absDir =  $rootDir.$node->name. "/";
									
						mkdir($absDir, 0777, true);
						parse($node, $level+1, $absDir, $cmd); 
						
					}else{
						
						$absDir =  $rootDir.$node->name. "/"; 
						
						$extFile = defineFileType($node['mimetype']);
			
						if(substr(strrchr($node->name,'.'),1) !=""){
							$file = str_replace('.  ', '',$rootDir.$node->name);
						}else{
							$file = str_replace('  ', '',$rootDir.$node->name. "." .$extFile);
						}
			
						file_put_contents(
							$file, 
							base64_decode($node->version->content)
						);
						
						parse($node, $level+1, $absDir, $cmd); 
						
					}
				
				}
				
			break;

			case('file'):
						
				if(!file_exists($basePath.$fileTarget. "_" .$cmd)){
					mkdir($basePath.$cmd. "_" .$fileTarget, 0777, true);
					$path = $basePath.$cmd. "_" .$fileTarget. "/";
				}
							
				foreach($xml->llnode as $node){
							
					if($node['objname'] = "Document"){						
												
						$extFile = defineFileType($node['mimetype']);
			
						if(substr(strrchr($node->name,'.'),1) !=""){
							$file = str_replace('.  ', '',$node->name);
						}else{
							$file = str_replace('  ', '',$node->name. "." .$extFile);
						}
						
						if($extFile == $fileTarget){							
							echo trim(str_repeat("+ ", $level). "[" .strtoupper(defineFileType($node['mimetype'])). "] " .$node->name. "." .defineFileType($node['mimetype'])). "\r\n";

							file_put_contents(
								$basePath.$cmd. "_" .$fileTarget. "/".$node->name, 
								base64_decode($node->version->content)
							);
						}
												
						parse($node, $level+1, $absDir, $cmd, $fileTarget); 
					}
				}
			
			break;
		}
}

function defineFileType($mimetype){
	switch($mimetype){
		case('application/pdf'):
			return "pdf";
		break;
		
		case('application/msword'):
			return "doc";
		break;

		case('application/vnd.openxmlformats-officedocument.wordprocessingml.document'):
			return "doc";
		break;
				
		case('application/excel'):
			return "xls";
		break;
		
		case('application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'):
			return "xlsx";
		break;
		
		case('application/vnd.ms-excel'):
			return "xls";
		break;
		
		case('image/jpeg'):
			return "jpeg";
		break;
		
		case('image/pjpeg'):
			return "jpeg";
		break;
		
		case('image/png'):
			return "png";
		break;
		
		case('image/gif'):
			return "gif";
		break;
		
		case('application/pdf'):
			return "pdf";
		break;
		
		case('application/vnd.ms-outlook-template'):
			return "msg";
		break;
		
		case('text/plain'):
			return "txt";
		break;
		
		case('application/zip'):
			return "zip";
		break;
		
		case('application/x-rar-compressed'):
			return "rar";
		break;
				
		default:
			return "folder";
		break;
	}
}

?>