<?php
// $Id: blocks.php,v 1.1 2008/10/13 10:16:04 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

$constpref = strtoupper( '_BL_' . $GLOBALS['MY_DIRNAME']. '_' ) ;

// === define begin ===
if( !defined($constpref."LANG_LOADED") ) 
{

define($constpref."LANG_LOADED" , 1 ) ;

//=========================================================
// same as myalbum
//=========================================================

define($constpref."BTITLE_TOPNEW","Fotos Recentes");
define($constpref."BTITLE_TOPHIT","Top Fotos");
define($constpref."BTITLE_RANDOM","Foto Aleatória");
define($constpref."TEXT_DISP","Mostrar");
define($constpref."TEXT_STRLENGTH","Comprimento máximo da título da foto");
define($constpref."TEXT_CATLIMITATION","Limite por categoria");
define($constpref."TEXT_CATLIMITRECURSIVE","com Sub-categorias");
define($constpref."TEXT_BLOCK_WIDTH","Máximo a mostrar");
define($constpref."TEXT_BLOCK_WIDTH_NOTES","(se você configurar isto como 0, a imagem miniatura é mostrada no seu tamanho original.)");
define($constpref."TEXT_RANDOMCYCLE","Trocando o ciclo das imagens aleatórias (sec)");
define($constpref."TEXT_COLS","Colunas de Fotos");

//---------------------------------------------------------
// v0.20
//---------------------------------------------------------
define($constpref."POPBOX_REVERT", "Clique na imagem para encolhê-la.");

//---------------------------------------------------------
// v0.30
//---------------------------------------------------------
define($constpref."TEXT_CACHETIME", "Tempo em Cache");

// === define end ===
}

?>