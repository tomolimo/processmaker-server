<?php
/**
 * installServer.php
 *  
 * ProcessMaker Open Source Edition
 * Copyright (C) 2004 - 2008 Colosa Inc.23
 * 
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 * 
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 * 
 * For more information, contact Colosa Inc, 2566 Le Jeune Rd., 
 * Coral Gables, FL, 33134, USA, or email info@colosa.com.
 * 
 */
print " ";
$isWindows = PHP_OS == 'WINNT' ? true : false;

$oJSON   = new Services_JSON();
$action = $_POST['action'];
$dataClient   = $oJSON->decode(stripslashes($_POST['data']));
function find_SQL_Version($my = 'mysql',$infExe)
{
	if(PHP_OS=="WINNT" && !$infExe)
	{
		return false;
	}
	$output = shell_exec($my.' -V');
	preg_match('@[0-9]+\.[0-9]+\.[0-9]+@', $output, $version);
	return $version[0];
}
if($action==="check")
{
	G::LoadClass('Installer');
	$inst = new Installer();
	$siteName="workflow";
//	print_r($dataClient);
	$p1 = (isset($dataClient->ao_admin_pass1))?$dataClient->ao_admin_pass1:'admin';
	$p2 = (isset($dataClient->ao_admin_pass2))?$dataClient->ao_admin_pass2:'admin';
	$s = $inst->create_site(Array(
		'name'	  =>'workflow',
		'path_data'=>$dataClient->path_data,
		'path_compiled'=>$dataClient->path_compiled,
		'admin'=>Array('username'=>(isset($dataClient->ao_admin))?$dataClient->ao_admin:'admin','password'=>$p1),
		'advanced'=>Array(
			'ao_db'=>(isset($dataClient->ao_db) && $dataClient->ao_db===2)?false:true,
			'ao_db_drop'=>(isset($dataClient->ao_db_drop) && $dataClient->ao_db_drop===true)?true:false,
			'ao_db_wf'=>(isset($dataClient->ao_db_wf))?$dataClient->ao_db_wf:'wf_'.$siteName,
			'ao_db_rb'=>(isset($dataClient->ao_db_rb))?$dataClient->ao_db_rb:'rb_'.$siteName,
			'ao_db_rp'=>(isset($dataClient->ao_db_rp))?$dataClient->ao_db_rp:'rp'.$siteName
		),
		'database'=>Array(
			'hostname'=>$dataClient->mysqlH,
			'username'=>$dataClient->mysqlU,
			'password'=>$dataClient->mysqlP
		)
	));
	//print_r($inst);
	//print_r($s);
	$data=null;
	$data->phpVersion	=(version_compare(PHP_VERSION,"5.1.0",">=") && version_compare(PHP_VERSION,"5.3.0","<") )?true:false;
	if(trim($dataClient->mysqlH)=='' || trim($dataClient->mysqlU)=='')
	{
		$con = array('connection'=>false,'grant'=>false,'message'=>'Please complete the input fields (Hostname/Username)');
	}
	$data->mysqlConnection	=$s['result']['database']['connection'];
	$data->grantPriv	=$s['result']['database']['grant'];
	$data->databaseMessage	=$s['result']['database']['message'];
	$data->mysqlVersion	=$s['result']['database']['version'];
	$data->path_data	=$s['result']['path_data'];
	$data->path_compiled	=$s['result']['path_compiled'];
	$data->checkMemory	=(((int)ini_get("memory_limit"))>=40)?true:false;
	#$data->checkmqgpc	=(get_magic_quotes_gpc())?false:true;
	$data->checkPI		=((int)$inst->file_permisions(PATH_CORE."config/paths_installed.php",666)==666 || (!file_exists(PATH_CORE."config/paths_installed.php") && (int)$inst->file_permisions(PATH_CORE."config/",777)==777))?true:false;
	$data->checkDL		=((int)$inst->file_permisions(PATH_CORE."content/languages/",777)==777)?true:false;
	$data->checkDLJ		=((int)$inst->file_permisions(PATH_CORE."js/labels/",777)==777)?true:false;
	$data->ao_db_wf		=$s['result']['database']['ao']['ao_db_wf'];
	$data->ao_db_rb		=$s['result']['database']['ao']['ao_db_rb'];
	$data->ao_db_rp		=$s['result']['database']['ao']['ao_db_rp'];

	$data->ao_admin		=$s['result']['admin']['username'];
	$data->ao_admin_pass=($p1!==$p2)?false:true;
	
	//*Autoinstall Process and Plugins. By JHL
	// March 11th. 2009
	// To enable the way of aoutoinstall process and/or plugins 
	// at same time of initial PM setup
	
	//Get Available autoinstall process
	$data->availableProcess = $inst->getDirectoryFiles(PATH_OUTTRUNK."autoinstall","pm");
	
	//Get Available autoinstall plugins
	$data->availablePlugins = $inst->getDirectoryFiles(PATH_OUTTRUNK."autoinstall","tar");
	
	//End autoinstall
	
	$data->microtime	=microtime(true);
	echo $oJSON->encode($data);
}
else if($action==="install")
{
	print_r("POST:\n");
	print_r($_POST);
	print_r("\n------------------------------------------------\n");
	/*
	 * InstalaciC3n son SIMPLE POST
	 *
	 * Datos necesarios por POST:
	 *
	 *
	 * 	action=install
	 * 	data=	{"mysqlE":"Path/to/mysql.exe",
	 * 		"mysqlH":"Mysqlhostname",
	 * 		"mysqlU":"mysqlUsername",
	 * 		"mysqlP":"mysqlPassword",
	 * 		"path_data":"/path/to/workflow_data/",
	 * 		"path_compiled":"/path/to/compiled/"}
	 *
	 *--------------------------------------------------------------------------------------------------------------
	 *
	 * Pasos para instalar.
	 * 1) Se necesita los datos:
	 * 	$HOSTNAME
	 * 	$USERNAME
	 * 	$PASSWORD
	 * 	$PATH_TO_WORKFLOW_DATA
	 * 	$PATH_TO_COMPILED DATA
	 * 2) crear $PATH_TO_WORKFLOW_DATA
	 * 3) crear $PATH_TO_COMPILED_DATA
	 * 4) Crear el sitio workflow
	 *
	 * 	4.1 Crear el usuario (mysql) wf_workflow , password: sample
	 *		4.1.1 Crear base de datos wf_workflow con el actual usuario
	 *		4.1.2 Darle todos los privilegios sobre la base de datos wf_workflow al usuario wf_workflow
	 *		4.1.3 Dump del archivo processmaker/workflow/engine/data/mysql/schema.sql
	 *		4.1.4 Dump del archivo processmaker/workflow/engine/data/mysql/insert.sql
	 *
	 * 	4.2 Crear el usuario (mysql) wf_rbac, password: sample
	 *		4.2.1 Crear base de datos wf_rbac con el actual usuario
	 *		4.2.2 Darle todos los privilegios sobre la base de datos wf_rbac al usuario wf_rbac
	 *		4.2.3 Dump del archivo processmaker/rbac/engine/data/mysql/schema.sql
	 *		4.2.4 Dump del archivo processmaker/rbac/engine/data/mysql/insert.sql
	 *
	 *	4.3 Crear archivo de configuraciC3n y directorios para el sitio workflow
	 *
	 *		4.3.1 Crer los directorios:
	 *			
	 *			$PATH_TO_WORKFLOW_DATA./sites/workflow/
	 *			$PATH_TO_WORKFLOW_DATA./sites/workflow/cutomFunctions/
	 *			$PATH_TO_WORKFLOW_DATA./sites/workflow/rtfs/
	 *			$PATH_TO_WORKFLOW_DATA./sites/workflow/xmlforms/
	 *			$PATH_TO_WORKFLOW_DATA./sites/workflow/processesImages/
	 *			$PATH_TO_WORKFLOW_DATA./sites/workflow/files/
	 *		4.3.2 Crear el archivo.
	 *
	 *			$PATH_TO_WORKFLOW_DATA./sites/workflow/db.php
	 *
	 *			con el contenido reemplazando $HOSTNAME por el valor definido.
	 *
				<?php
				// Processmaker configuration
				define ('DB_ADAPTER', 'mysql' );
				define ('DB_HOST', $HOSTNAME );
				define ('DB_NAME', 'wf_workflow' );
				define ('DB_USER', 'wf_workflow' );
				define ('DB_PASS', 'sample' );
				define ('DB_RBAC_HOST', $HOSTNAME );
				define ('DB_RBAC_NAME', 'rbac_workflow' );
				define ('DB_RBAC_USER', 'rbac_workflow' );
				define ('DB_RBAC_PASS', 'sample' );
			?>
			
	*	4.4 Crear el archivo workflow/engine/config/paths_installed.php con el contenido.
	*
	*		<?php
			define( 'PATH_DATA', '$PATH_TO_WORKFLOW_DATA' );
			define( 'PATH_C', '$PATH_TO_COMPILED_DATA' );
			?>

			Reemplazando:
	* 	$PATH_TO_WORKFLOW_DATA
	* 	$PATH_TO_COMPILED DATA
	*
	*	4.2 Actualizar archivos de idiomas abriendo la pC!gina (background)
	*
	*		http://ProcessmakerHostname/sysworkflow/en/green/tools/updateTranslation
	*
 	*
 	*
 	*
 	*5) Auto instalar Procesos o Plugins 
 	*5.1 Intalar procesos
 	*5.2 Intalar plugins
	* */
	
	$sp		= "/";
	$dir_data	= $dataClient->path_data;
	$dir_compiled	= $dataClient->path_compiled;

	$dir_data	= (substr($dir_data,-1)==$sp)?$dir_data:$dir_data."/";
	$dir_compiled	= (substr($dir_compiled,-1)==$sp)?$dir_compiled:$dir_compiled."/";
	global $isWindows;

	@mkdir($dir_data."sites",0777,true);
	@mkdir($dir_compiled,0777,true);

	$create_db	="create-db.sql";
	$schema		="schema.sql";

	G::LoadClass('Installer');
	/*$inst = new Installer();
	$s = $inst->create_site(Array(
		'name'	  =>'workflow',
		'path_data'=>$dataClient->path_data,
		'path_compiled'=>$dataClient->path_compiled,
		'database'=>Array(
			'hostname'=>$dataClient->mysqlH,
			'username'=>$dataClient->mysqlU,
			'password'=>$dataClient->mysqlP
		)
	),true);*/

	$inst = new Installer();
	$siteName="workflow";
	$p1 = (isset($dataClient->ao_admin_pass1))?$dataClient->ao_admin_pass1:'admin';
	$p2 = (isset($dataClient->ao_admin_pass2))?$dataClient->ao_admin_pass2:'admin';
	$s = $inst->create_site(Array(
		'name'	  =>'workflow',
		'path_data'=>$dataClient->path_data,
		'path_compiled'=>$dataClient->path_compiled,
		'admin'=>Array('username'=>(isset($dataClient->ao_admin))?$dataClient->ao_admin:'admin','password'=>$p1),
		'advanced'=>Array(
			'ao_db'=>(isset($dataClient->ao_db) && $dataClient->ao_db===2)?false:true,
			'ao_db_drop'=>(isset($dataClient->ao_db_drop) && $dataClient->ao_db_drop===true)?true:false,
			'ao_db_wf'=>(isset($dataClient->ao_db_wf))?$dataClient->ao_db_wf:'wf_'.$siteName,
			'ao_db_rb'=>(isset($dataClient->ao_db_rb))?$dataClient->ao_db_rb:'rb_'.$siteName,
			'ao_db_rp'=>(isset($dataClient->ao_db_rp))?$dataClient->ao_db_rp:'rp'.$siteName
		),
		'database'=>Array(
			'hostname'=>$dataClient->mysqlH,
			'username'=>$dataClient->mysqlU,
			'password'=>$dataClient->mysqlP
		)
	),true);

	//print_r($s);
	print_r($inst->report);
	//die();
	
	$sh=md5(filemtime(PATH_GULLIVER."/class.g.php"));
	$h=G::encrypt($dataClient->mysqlH.$sh.$dataClient->mysqlU.$sh.$dataClient->mysqlP.$sh.$inst->cc_status,$sh);
	$db_text = "<?php\n" .
	"define( 'PATH_DATA', '".$dir_data."' );\n" .
	"define( 'PATH_C',    '".$dir_compiled."' );\n" .
	"define( 'HASH_INSTALLATION','".$h."' );\n" .
	"define( 'SYSTEM_HASH','".$sh."' );\n" .
	"?>";
	$fp = fopen(FILE_PATHS_INSTALLED, "w");
	fputs( $fp, $db_text, strlen($db_text));
	fclose( $fp );
	/* Update languages */
	$update = file_get_contents("http://".$_SERVER['SERVER_NAME'].":".$_SERVER['SERVER_PORT']."/sysworkflow/en/green/tools/updateTranslation");
	print_r("Update language:  => ".((!$update)?$update:"OK")."\n");
	
	/* Heartbeat Enable/Disable */
	if(!isset($dataClient->heartbeatEnabled)) $dataClient->heartbeatEnabled=true;
	$update = file_get_contents("http://".$_SERVER['SERVER_NAME'].":".$_SERVER['SERVER_PORT']."/sysworkflow/en/green/install/heartbeatStatus?status=".$dataClient->heartbeatEnabled);
    print_r("Heartbeat Status:  => ".str_replace("<br>","\n",$update)."\n");
	
	/* Autoinstall Process */
	$update = file_get_contents("http://".$_SERVER['SERVER_NAME'].":".$_SERVER['SERVER_PORT']."/sysworkflow/en/green/install/autoinstallProcesses");
	print_r("Process AutoInstall:  => <br>".str_replace("<br>","\n",$update)."\n"); 
	
	/* Autoinstall Plugins */
	$update = file_get_contents("http://".$_SERVER['SERVER_NAME'].":".$_SERVER['SERVER_PORT']."/sysworkflow/en/green/install/autoinstallPlugins");
	print_r("Plugin AutoInstall:  => <br>".str_replace("<br>","\n",$update)."\n"); 
}
?>
