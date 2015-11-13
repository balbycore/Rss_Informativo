<?php

include 'core/config.php';
include 'core/conexao.php';
include 'core/funcoes.php';


$conexao = conectar();

$noticias_recentes = getNoticiasRecentes($conexao);

print_r($noticias_recentes);

desconectar();

?>