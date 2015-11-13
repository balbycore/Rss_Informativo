<?php

function conectar(){
	$ora_bd = "(DESCRIPTION =
	  (ADDRESS = (PROTOCOL = TCP)(HOST = orappjma)(PORT = 1521))
	  (CONNECT_DATA = 
		(SID = pjma)
	  )
	)";
	
	$conexao = oci_connect("dba_portalweb", "s3ch2m1P", $ora_bd, "WE8ISO8859P15");

	if (!$conexao) {
		$e = oci_error();
		trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
	}else{
		return $conexao;
	}
}



function desconectar($conn){

	@ocilogoff($conn);
	
}

function consultar($conexao, $consulta) {

	$result = @oci_parse($conexao, $consulta);
	
	if (!$result) { 
	   $oerr = OCIError($result); 
	   echo "Fetch Code 1:".$oerr["message"]; 
	   exit; 
	}
	
	@oci_execute($result, OCI_DEFAULT);
	
	$resp = NULL;
	$a = 0;
	
	while ($row = @oci_fetch_array($result, OCI_BOTH + OCI_RETURN_LOBS)) {
		$resp[$a++] = $row;	
	}
	
	@oci_free_statement($result);	
	
	return $resp;
}

?>
