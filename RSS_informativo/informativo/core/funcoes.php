<?php

function getNoticiasRecentes($conexao){
	
	$DATAINICIAL = date('d/m/Y ', strtotime("-15 days"));
	$DATAFINAL = date('d/m/Y ', strtotime("now"));
		
	$query = 
	" 
					SELECT 
						P.STR_TITULO,
						P.STR_CONTEUDO,
						P.STR_LINK,
						P.DTA_ATUALIZACAO,
						P.INT_PUBLICACAO_ID,
						P.INT_SESSAO_ID,
						I.INT_IMAGEM_ID,
						I.STR_IMAGEM,
						I.STR_LEGENDA,
						A.INT_SITE_ID
						
					FROM
						TB_PUBLICACAO P
					INNER JOIN dba_portalweb.tb_sessao S on (S.int_sessao_id = P.Int_Sessao_Id)
					INNER JOIN dba_portalweb.tb_area A on (A.int_area_id = S.Int_area_Id)
					LEFT JOIN
						TB_IMAGEM I ON I.INT_MODULO_CODIGO_ID = P.INT_PUBLICACAO_ID 
					WHERE
						(A.INT_SITE_ID = 1)
					AND
						P.DTA_ATUALIZACAO BETWEEN '$DATAINICIAL' AND '$DATAFINAL'
					ORDER BY 
						P.INT_PUBLICACAO_ID
	";
		
	return consultar($conexao, $query);
	
}

function getBancoImagens($pagina, $quantidade, $bd){
	


	$inicio = $pagina  * $quantidade;
	$fim = ($pagina + 1) * $quantidade;

	$query   = "
			SELECT * FROM (
				SELECT topn.*, ROWNUM rnum FROM (
					SELECT 
						P.STR_TITULO,
						P.STR_CONTEUDO,
						I.INT_IMAGEM_ID,
						I.STR_IMAGEM,
						I.STR_LEGENDA
					FROM
						TB_PUBLICACAO P
					LEFT JOIN
						TB_IMAGEM I ON I.INT_MODULO_CODIGO_ID = P.INT_PUBLICACAO_ID 
					WHERE
						P.INT_SESSAO_ID = 1148
					ORDER BY 
						I.INT_IMAGEM_ID DESC
				) topn
				WHERE 
					ROWNUM <= $fim
			)
			WHERE
				rnum > $inicio
	";
	


	return arraySQL($query, $bd);

}

function echoArray($array = NULL){
	print "<pre>\n";
	print_r($array);
	print "</pre>\n";
}

function strleft($s1, $s2) { 
	return substr($s1, 0, $s2)."..."; 
}

?>
