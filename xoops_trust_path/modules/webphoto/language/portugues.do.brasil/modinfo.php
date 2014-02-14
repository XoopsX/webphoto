<?php
// $Id: modinfo.php,v 1.1 2008/10/13 10:16:04 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

$constpref = strtoupper( '_MI_' . $GLOBALS['MY_DIRNAME']. '_' ) ;

// === define begin ===
if( !defined($constpref."LANG_LOADED") ) 
{

define($constpref."LANG_LOADED" , 1 ) ;

//=========================================================
// same as myalbum
//=========================================================

// The name of this module
define($constpref."NAME","Galeria de Imagens");

// A brief description of this module
define($constpref."DESC","Cria uma seção de imagens onde os usuários podem buscar, enviar e avaliar várias imagens.");

// Names of blocks for this module (Not all module has blocks)
define($constpref."BNAME_RECENT","Últimas Imagens");
define($constpref."BNAME_HITS","Top Imagens");
define($constpref."BNAME_RANDOM","Imagem Aleatória");
define($constpref."BNAME_RECENT_P","Últimas Imagens com Miniaturas");
define($constpref."BNAME_HITS_P","Top Imagens com Miniaturas");

// Config Items
define($constpref."CFG_PHOTOSPATH" , "Percurso para as Imagens" ) ;
define($constpref."CFG_DESCPHOTOSPATH" , "Percurso do diretório onde está instalado o XOOPS.<br />(O primeiro caracter deve ser '/'. O último caracter não deve ser '/'.)<br />As permissões deste diretório deve ser 777 ou 707 no unix." ) ;
define($constpref."CFG_THUMBSPATH" , "Percurso para as miniaturas" ) ;
define($constpref."CFG_DESCTHUMBSPATH" , "Igual a 'Percurso para as Imagen'." ) ;

//define($constpref."CFG_USEIMAGICK" , "Use ImageMagick for treating images" ) ;
//define($constpref."CFG_DESCIMAGICK" , "Not use ImageMagick cause Not work resize or rotate the main photo, and make thumbnails by GD.<br />You'd better use ImageMagick if you can." ) ;

define($constpref."CFG_IMAGINGPIPE" , "Pacote de tratamento das imagens" ) ;
define($constpref."CFG_DESCIMAGINGPIPE" , "Quase todos os ambientes em PHP podem usar GD. Mas GD é funcionalmente inferior a dois outros pacotes.<br />É melhor usar ImageMagick ou NetPBM, se você puder." ) ;
define($constpref."CFG_FORCEGD2" , "Forçar conversão para GD2" ) ;
define($constpref."CFG_DESCFORCEGD2" , "Mesmo se o GD estiver disponível na versão do PHP, isso forçará a conversão para GD2(truecolor).<br />Algumas configurações do PHP falham na criação de miniaturas com GD2<br />Está configuração é relevante unicamente quando for usado GD" ) ;
define($constpref."CFG_IMAGICKPATH" , "Percurso para o ImageMagick" ) ;
define($constpref."CFG_DESCIMAGICKPATH" , "Embora todo o percurso para 'convert' deva estar escrito, deixe isso em branco na maioria dos ambientes.<br />Está configuração é relevante unicamente quando for usado ImageMagick" ) ;
define($constpref."CFG_NETPBMPATH" , "Percurso para o NetPBM" ) ;
define($constpref."CFG_DESCNETPBMPATH" , "Embora todo o percurso para 'pnmscale' deva estar escrito, deixe isso em branco na maioria dos ambientes.<br />Está configuração é relevante unicamente quando for usado NetPBM" ) ;
define($constpref."CFG_POPULAR" , "Hits para ser popular" ) ;
define($constpref."CFG_NEWDAYS" , "Dias entre o ícone mostrado do 'novo'&'atualizado'" ) ;
define($constpref."CFG_NEWPHOTOS" , "Número de imagens como Novo no alto da página" ) ;

//define($constpref."CFG_DEFAULTORDER" , "Default order in category's view" ) ;

define($constpref."CFG_PERPAGE" , "Imagens mostradas por página" ) ;
define($constpref."CFG_DESCPERPAGE" , "Insira os números selecionáveis separados com '|'<br />ex) 10|20|50|100" ) ;
define($constpref."CFG_ALLOWNOIMAGE" , "Permitir o envio sem imagens" ) ;
define($constpref."CFG_MAKETHUMB" , "Fazer miniatura da imagem" ) ;
define($constpref."CFG_DESCMAKETHUMB" , "Quando você mudar 'Não' para 'Sim', é melhor 'Refazer as miniaturas'." ) ;

//define($constpref."CFG_THUMBWIDTH" , "Thumb Image Width" ) ;
//define($constpref."CFG_DESCTHUMBWIDTH" , "The height of thumbs will be decided from the width automatically." ) ;
//define($constpref."CFG_THUMBSIZE" , "Size of thumbnails (pixel)" ) ;

define($constpref."CFG_THUMBRULE" , "Regra de cálculo para a construção das miniaturas" ) ;
define($constpref."CFG_WIDTH" , "Largura máxima da imagem" ) ;
define($constpref."CFG_DESCWIDTH" , "Isto significa que a largura da imagem é redimensionada.<br />Se você usa GD sem truecolor, isso implica em limitar a largura." ) ;
define($constpref."CFG_HEIGHT" , "Altura máxima da imagem" ) ;
define($constpref."CFG_DESCHEIGHT" , "Isto significa que a altura da imagem é redimensionada..<br />Se você usa GD sem truecolor, isso implica em limitar a altura." ) ;
define($constpref."CFG_FSIZE" , "Tamanho máximo do arquivo" ) ;
define($constpref."CFG_DESCFSIZE" , "Limita o tamanho do arquivo enviado.(bytes)" ) ;

//define($constpref."CFG_MIDDLEPIXEL" , "Max image size in single view" ) ;
//define($constpref."CFG_DESCMIDDLEPIXEL" , "Specify (width)x(height)<br />(eg. 480x480)" ) ;

define($constpref."CFG_ADDPOSTS" , "Número adicionado de post de usuários que postaram uma imagens." ) ;
define($constpref."CFG_DESCADDPOSTS" , "Normalmente, 0 ou 1. Abaixo de zero 0 significa 0" ) ;
define($constpref."CFG_CATONSUBMENU" , "Registrar a categoria mais alta no sub-menu" ) ;
define($constpref."CFG_NAMEORUNAME" , "Mostrar o nome de quem postou" ) ;
define($constpref."CFG_DESCNAMEORUNAME" , "Selecionar qual 'nome' é mostrado" ) ;

//define($constpref."CFG_VIEWCATTYPE" , "Type of view in category" ) ;
define($constpref."CFG_VIEWTYPE" , "Type of view " ) ;

//define($constpref."CFG_COLSOFTABLEVIEW" , "Number of columns in table view" ) ;
define($constpref."CFG_COLSOFTABLE" , "Número de colunas na visualização tipo tabela" ) ;

//define($constpref."CFG_ALLOWEDEXTS" , "File extensions that can be uploaded" ) ;
//define($constpref."CFG_DESCALLOWEDEXTS" , "Input extensions with separator '|'. (eg 'jpg|jpeg|gif|png') .<br />All characters must be lowercase. Don't insert periods or spaces<br />Never add php or phtml etc." ) ;
//define($constpref."CFG_ALLOWEDMIME" , "MIME Types can be uploaded" ) ;
//define($constpref."CFG_DESCALLOWEDMIME" , "Input MIME Types with separator '|'. (eg 'image/gif|image/jpeg|image/png')<br />If you want to be checked by MIME Type, leave this blank" ) ;

define($constpref."CFG_USESITEIMG" , "Usar [siteimg] na integração da Administração de Imagens" ) ;
define($constpref."CFG_DESCUSESITEIMG" , "A integração da administração de imagens insere [siteimg] ao invés de [img].<br />Você tem de hackear o módulo.textsanitizer.php para cada módulopara habilitar a tag do [siteimg]" ) ;

define($constpref."OPT_USENAME" , "Nome Real" ) ;
define($constpref."OPT_USEUNAME" , "Nome de Login" ) ;

define($constpref."OPT_CALCFROMWIDTH" , "largura:especificar altura:auto" ) ;
define($constpref."OPT_CALCFROMHEIGHT" , "largura:auto  largura:especificada" ) ;
define($constpref."OPT_CALCWHINSIDEBOX" , "colocar no tamanho especificado no quadrado" ) ;

define($constpref."OPT_VIEWLIST" , "Visualização tipo Lista" ) ;
define($constpref."OPT_VIEWTABLE" , "Visualização tipo tabela" ) ;

// Sub menu titles
//define($constpref."TEXT_SMNAME1","Submit");
//define($constpref."TEXT_SMNAME2","Popular");
//define($constpref."TEXT_SMNAME3","Top Rated");
//define($constpref."TEXT_SMNAME4","My Photos");

// Names of admin menu items
//define($constpref."ADMENU0","Submitted Photos");
//define($constpref."ADMENU1","Photo Management");
//define($constpref."ADMENU2","Add/Edit Categories");
//define($constpref."ADMENU_GPERM","Global Permissions");
//define($constpref."ADMENU3","Check Configuration & Environment");
//define($constpref."ADMENU4","Batch Register");
//define($constpref."ADMENU5","Rebuild Thumbnails");
//define($constpref."ADMENU_IMPORT","Import Images");
//define($constpref."ADMENU_EXPORT","Export Images");
//define($constpref."ADMENU_MYBLOCKSADMIN","Blocks & Groups Admin");
//define($constpref."ADMENU_MYTPLSADMIN","Templates");


// Text for notifications
define($constpref."GLOBAL_NOTIFY", "Global");
define($constpref."GLOBAL_NOTIFYDSC", "Opções de notificação globais");
define($constpref."CATEGORY_NOTIFY", "Categoria");
define($constpref."CATEGORY_NOTIFYDSC", "Opções de notificação aplicáveis à atual categoria de imagens");
define($constpref."PHOTO_NOTIFY", "Imagens");
define($constpref."PHOTO_NOTIFYDSC", "Opções de notificação aplicáveis à imagem atual");

define($constpref."GLOBAL_NEWPHOTO_NOTIFY", "Nova Imagem");
define($constpref."GLOBAL_NEWPHOTO_NOTIFYCAP", "Notifique-me quando novas imagens foram postadas");
define($constpref."GLOBAL_NEWPHOTO_NOTIFYDSC", "Receber notificação quando uma nova descrição de imagem é postada.");
define($constpref."GLOBAL_NEWPHOTO_NOTIFYSBJ", "[{X_SITENAME}] {X_MODULE}: auto-notificação: Nova Imagem");

define($constpref."CATEGORY_NEWPHOTO_NOTIFY", "Nova Imagem");
define($constpref."CATEGORY_NEWPHOTO_NOTIFYCAP", "Notifique-me quando uma nova imagem for postada nesta categoria");
define($constpref."CATEGORY_NEWPHOTO_NOTIFYDSC", "Receber notificação quando uma nova descrição de imagem é postada nesta categoria");
define($constpref."CATEGORY_NEWPHOTO_NOTIFYSBJ", "[{X_SITENAME}] {X_MODULE}: auto-notificação: Nova Imagem");


//=========================================================
// add for webphoto
//=========================================================

// Config Items
define($constpref."CFG_SORT" , "Ordem padrão na visualização tipo lista" ) ;
define($constpref."OPT_SORT_IDA","Número do registro (Menor para o maior)");
define($constpref."OPT_SORT_IDD","Número de Registros (Mais recente para o menos recente)");
define($constpref."OPT_SORT_HITSA","Popularidade (Menores para os maiores Hits)");
define($constpref."OPT_SORT_HITSD","Popularidade (Maiores para os menores Hits)");
define($constpref."OPT_SORT_TITLEA","Título (A to Z)");
define($constpref."OPT_SORT_TITLED","Título (Z to A)");
define($constpref."OPT_SORT_DATEA","Data da atualização (Imagens antigas listas primeiro)");
define($constpref."OPT_SORT_DATED","Data da atualização (Novas imagens listadas primeiro)");
define($constpref."OPT_SORT_RATINGA","Avaliação (Escores mais baixos para escores mais altos)");
define($constpref."OPT_SORT_RATINGD","Avaliação (Escores mais altos pra escores mais baixos)");
define($constpref."OPT_SORT_RANDOM","Aleatória");

define($constpref."CFG_GICONSPATH" , "Percurso para os ícones do Google" ) ;

//define($constpref."CFG_TMPPATH" ,   "Path to temporary" ) ;

define($constpref."CFG_MIDDLE_WIDTH" ,  "Largura da imagem em vista única" ) ;
define($constpref."CFG_MIDDLE_HEIGHT" , "Altura da imagem em vista única" ) ;
define($constpref."CFG_THUMB_WIDTH" ,  "Largura da miniatura" ) ;
define($constpref."CFG_THUMB_HEIGHT" , "Altura da miniatura" ) ;

define($constpref."CFG_APIKEY","Código Google API Key");
define($constpref."CFG_APIKEY_DSC", 'Obtenha o código do API em <br/><a href="http://www.google.com/apis/maps/signup.html" target="_blank">Inscrever-se no Google Maps API</a><br /><br />Para detalhes do parâmetro, veja o que segue<br /><a href="http://www.google.com/apis/maps/documentation/reference.html" target="_blank">Referência Google Maps API</a>' );
define($constpref."CFG_LATITUDE",  "Latitude");
define($constpref."CFG_LONGITUDE", "Longitude");
define($constpref."CFG_ZOOM", "Nível de Zoom");

define($constpref."CFG_USE_POPBOX","Usar PopBox");

define($constpref."CFG_INDEX_DESC", "Texto introdutório na página principal");
define($constpref."CFG_INDEX_DESC_DEFAULT", "Aqui é onde vai sua página introdutória.<br />Você pode editá-la nas Preferências");

// Sub menu titles
define($constpref."SMNAME_SUBMIT","Enviar");
define($constpref."SMNAME_POPULAR","Popular");
define($constpref."SMNAME_HIGHRATE","Melhor avaliada");
define($constpref."SMNAME_MYPHOTO","Minhas Imagens");

// Names of admin menu items
define($constpref."ADMENU_ADMISSION","Imagens Admitidas");
define($constpref."ADMENU_PHOTOMANAGER","Administração de Imagens");
define($constpref."ADMENU_CATMANAGER","Adicionar/Editar Categorias");
define($constpref."ADMENU_CHECKCONFIGS","Checar Configuração");
define($constpref."ADMENU_BATCH","Registro em lote");
define($constpref."ADMENU_REDOTHUMB","Reconstruir miniaturas");
define($constpref."ADMENU_GROUPPERM","Permissões Globais");
define($constpref."ADMENU_IMPORT","Importar Imagens");
define($constpref."ADMENU_EXPORT","Exportar Imagens");

define($constpref."ADMENU_GICONMANAGER","Administração Ícones do Google");
define($constpref."ADMENU_MIMETYPES","Administração tipos de MIME");
define($constpref."ADMENU_IMPORT_MYALBUM","Importar lote do Myalbum");
define($constpref."ADMENU_CHECKTABLES","Checar configuração das tabelas");
define($constpref."ADMENU_PHOTO_TABLE_MANAGE","Administrar Tabela de Imagem");
define($constpref."ADMENU_CAT_TABLE_MANAGE","Administrar Tabela das Categorias");
define($constpref."ADMENU_VOTE_TABLE_MANAGE","Administrar Tabela de Votações");
define($constpref."ADMENU_GICON_TABLE_MANAGE","Administrar Tabela do ícone do Google");
define($constpref."ADMENU_MIME_TABLE_MANAGE","Administrar Tabela do MIME");
define($constpref."ADMENU_TAG_TABLE_MANAGE","Administrar Tabela de Tag");
define($constpref."ADMENU_P2T_TABLE_MANAGE","Administrar Tabela de tags das Imagens");
define($constpref."ADMENU_SYNO_TABLE_MANAGE","Administrar Tabela de Sinônimo");

//---------------------------------------------------------
// v0.20
//---------------------------------------------------------
define( $constpref."CFG_USE_FFMPEG"  , "Usar ffmpeg" ) ;
define( $constpref."CFG_FFMPEGPATH"  , "Percurso para o ffmpeg" ) ;
define( $constpref."CFG_DESCFFMPEGPATH" , "Embora todo o percurso para o 'ffmpeg' deva ser escrito, deixe-o em branco na maioria dos ambientes.<br />Esta configuração é relevante apenas quando 'Usar ffmpeg' é sim" ) ;
define($constpref."CFG_USE_PATHINFO","Usar pathinfo");

//---------------------------------------------------------
// v0.30
//---------------------------------------------------------
define($constpref."CFG_TMPDIR" ,   "Percurso para o temporário" ) ;
define($constpref."CFG_TMPDIR_DSC" , "Preencha todo o percurso (O primeiro caracter deve ser '/'. O último caracter não deve ser '/'.)<br />É recomendado configurar isso fora do diretório raiz.");
define($constpref."CFG_MAIL_HOST"  , "Nome do Servidor de e-mail" ) ;
define($constpref."CFG_MAIL_USER"  , "ID do usuário de e-mai" ) ;
define($constpref."CFG_MAIL_PASS"  , "Senha do e-mail" ) ;
define($constpref."CFG_MAIL_ADDR"  , "Endereço do e-mail" ) ;
define($constpref."CFG_MAIL_CHARSET"  , "Charset do e-mail" ) ;
define($constpref."CFG_MAIL_CHARSET_DSC" , "Insira o Charset com o separador '|'.<br />Se você não precisa checar o tipo de MIME, deixe isso em branco" ) ;
define($constpref."CFG_MAIL_CHARSET_LIST","ISO-8859-1|US-ASCII");
define($constpref."CFG_FILE_DIR"  , "Percurso dos arquivos, via FTP" ) ;
define($constpref."CFG_FILE_DIR_DSC" , "Preencha todo o percurso (O primeiro caracter deve ser '/'. O último caracter não deve ser '/'.)É recomendado configurar isso fora do diretório raiz." ) ;
define($constpref."CFG_FILE_SIZE"  , "Tamanho máximo do arquivo, via FTP (byte)" ) ;
define($constpref."CFG_FILE_DESC"  , "Ajuda descrição do FTP");
define($constpref."CFG_FILE_DESC_DSC"  , "Mostrar na ajuda, quando tiver permissão de 'Postar via FTP' ");
define($constpref."CFG_FILE_DESC_TEXT"  , "
<b>Servidor FTP</b><br />
Host do servidor FTP: xxx<br />
ID do Usuário FTP: xxx<br />
Senha do FTP: xxx<br />" ) ;

define($constpref."ADMENU_MAILLOG_MANAGER","Administração do Maillog");
define($constpref."ADMENU_MAILLOG_TABLE_MANAGE","Administração da Tabela do Maillog");
define($constpref."ADMENU_USER_TABLE_MANAGE","Administração da Tabela Auxiliar do Usuário");

//---------------------------------------------------------
// v0.40
//---------------------------------------------------------
define($constpref."CFG_BIN_PASS" , "Senha de comando" ) ;
define($constpref."CFG_COM_DIRNAME",  "Integração-comentário: nome do diretório do d3forum");
define($constpref."CFG_COM_FORUM_ID", "Integração-comentário: ID do fórum");
define($constpref."CFG_COM_VIEW",     "Vizualização da Integração-comentário");

define($constpref."ADMENU_UPDATE", "Atualizção");
define($constpref."ADMENU_ITEM_TABLE_MANAGE", "Administração da Tabela do Item");
define($constpref."ADMENU_FILE_TABLE_MANAGE", "Administração da Tabela do arquivo");

}
// === define begin ===

?>
