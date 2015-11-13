<?php
include 'core/config.php';
include 'core/conexao.php';
include 'core/funcoes.php';
require_once 'rss_generator.inc.php';

$conexao = conectar();

$noticias_recentes = getNoticiasRecentes($conexao);

desconectar($conexao);

$rss_channel = new rssGenerator_channel();
$rss_channel->atomLinkHref = '';
$rss_channel->title = 'RSS TJMA';
$rss_channel->link = 'http://mysite.com/news.php';
$rss_channel->description = 'Últimas notícias sobre o Poder Judiciário do Maranhão.';
$rss_channel->language = 'en-us';
$rss_channel->generator = 'PHP RSS Feed Generator';
$rss_channel->managingEditor = 'editor@mysite.com (Alex Jefferson)';
$rss_channel->webMaster = 'webmaster@mysite.com (Vagharshak Tozalakyan)';

foreach($noticias_recentes as $noticia){
		if (!isset($noticia["INT_IMAGEM_ID"])){
			$noticia["INT_IMAGEM_ID"] = "";
		}
		$imagemid = $noticia ["INT_IMAGEM_ID"];
		
		if (!empty ($imagemid)){
			$publicacaoid = $noticia["INT_PUBLICACAO_ID"];
			$strimagem = $noticia["STR_IMAGEM"];
			$url = "http://gerenciador.tjma.jus.br/app/webroot/files/publicacao/$publicacaoid/$strimagem";
			$img = "<a href=\"$url\"><img src=\"$url\"height=\"200px\"></img></a><br/>";
	    }
		$item = new rssGenerator_item();
		$item->title = utf8_encode ($noticia["STR_TITULO"]);
		$item->description = htmlentities($img . $noticia["STR_CONTEUDO"]);
		if(empty($noticia["STR_LINK"])){
			$publicacaoid = $noticia["INT_PUBLICACAO_ID"];
			$siteid = $noticia["INT_SITE_ID"];
			$sessaoid = $noticia["INT_SESSAO_ID"];
			if($siteid == 1){
				$sitenome = "tj";
			}elseif($siteid == 2){
				$sitenome = "cgj";
			}elseif($siteid == 3){
				$sitenome = "esmam";
			}
			$link = "http://www.tjma.jus.br/$sitenome/visualiza/sessao/$sessaoid/publicacao/$publicacaoid";
			
		}
		$item->link = $link;
		$item->guid = $link;
		
		$data = $noticia["DTA_ATUALIZACAO"];
		$dia = substr($data,0,2);
		$mes = substr($data,3,2);
		$ano = substr($data,6,2);
		$ano = "20" . $ano ;
		$data = $ano . "-" . $mes . "-" . $dia;
		$data = new DateTime ($data);
		$strData = date_format ($data, "D, j M Y H:i:s");
		
		$item->pubDate = $strData;
		$rss_channel->items[] = $item;
}

$rss_feed = new rssGenerator_rss();
$rss_feed->encoding = 'UTF-8';
$rss_feed->version = '2.0';
header('Content-Type: text/xml');
echo $rss_feed->createFeed($rss_channel);

?>