<?php
// $Id: admin.php,v 1.1 2008/10/13 10:16:04 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

// === define begin ===
if( !defined("_AM_WEBPHOTO_LANG_LOADED") ) 
{

define("_AM_WEBPHOTO_LANG_LOADED" , 1 ) ;

//=========================================================
// base on myalbum
//=========================================================

// menu
define('_AM_WEBPHOTO_MYMENU_TPLSADMIN','Modelos');
define('_AM_WEBPHOTO_MYMENU_BLOCKSADMIN','Permissões/Blocos');

//define('_AM_WEBPHOTO_MYMENU_MYPREFERENCES','Preferences');

// add for webphoto
define("_AM_WEBPHOTO_MYMENU_GOTO_MODULE" , "Ir ao Módulo" ) ;


// Index (Categories)
//define( "_AM_WEBPHOTO_H3_FMT_CATEGORIES" , "Categories Manager (%s)" ) ;
//define( "_AM_WEBPHOTO_CAT_TH_TITLE" , "Name" ) ;

define( "_AM_WEBPHOTO_CAT_TH_PHOTOS" , "Imagens" ) ;
define( "_AM_WEBPHOTO_CAT_TH_OPERATION" , "Operação" ) ;
define( "_AM_WEBPHOTO_CAT_TH_IMAGE" , "Banner" ) ;
define( "_AM_WEBPHOTO_CAT_TH_PARENT" , "Categoria Pai" ) ;

//define( "_AM_WEBPHOTO_CAT_TH_IMGURL" , "URL of Banner" ) ;

define( "_AM_WEBPHOTO_CAT_MENU_NEW" , "Criando uma Categoria" ) ;
define( "_AM_WEBPHOTO_CAT_MENU_EDIT" , "Editando uma Categoria" ) ;
define( "_AM_WEBPHOTO_CAT_INSERTED" , "Uma categoria é adicionada" ) ;
define( "_AM_WEBPHOTO_CAT_UPDATED" , "A categoria é modificada" ) ;
define( "_AM_WEBPHOTO_CAT_BTN_BATCH" , "Aplicar" ) ;
define( "_AM_WEBPHOTO_CAT_LINK_MAKETOPCAT" , "Criar uma nova categoria no topo" ) ;
define( "_AM_WEBPHOTO_CAT_LINK_ADDPHOTOS" , "Adiconar uma imagem nesta categoria" ) ;
define( "_AM_WEBPHOTO_CAT_LINK_EDIT" , "Editar esta categoria" ) ;
define( "_AM_WEBPHOTO_CAT_LINK_MAKESUBCAT" , "Criar uma nova categoria sob esta categoria" ) ;
define( "_AM_WEBPHOTO_CAT_FMT_NEEDADMISSION" , "%s imagens são necessárias para ser admitida" ) ;
define( "_AM_WEBPHOTO_CAT_FMT_CATDELCONFIRM" , "%s será deletada com suas sub-categorias, imagens e comentários. OK?" ) ;


// Admission
//define( "_AM_WEBPHOTO_H3_FMT_ADMISSION" , "Admitting images (%s)" ) ;
//define( "_AM_WEBPHOTO_TH_SUBMITTER" , "Submitter" ) ;
//define( "_AM_WEBPHOTO_TH_TITLE" , "Title" ) ;
//define( "_AM_WEBPHOTO_TH_DESCRIPTION" , "Description" ) ;
//define( "_AM_WEBPHOTO_TH_CATEGORIES" , "Category" ) ;
//define( "_AM_WEBPHOTO_TH_DATE" , "Last update" ) ;


// Photo Manager
//define( "_AM_WEBPHOTO_H3_FMT_PHOTOMANAGER" , "Photo Manager (%s)" ) ;

define( "_AM_WEBPHOTO_TH_BATCHUPDATE" , "Checagem coletiva da atualização das imagens" ) ;
define( "_AM_WEBPHOTO_OPT_NOCHANGE" , "- NÃO ALTERE -" ) ;
define( "_AM_WEBPHOTO_JS_UPDATECONFIRM" , "Checagem dos itens que serão atualizados. OK?" ) ;


// Module Checker
//define( "_AM_WEBPHOTO_H3_FMT_MODULECHECKER" , "myAlbum-P checker (%s)" ) ;

define( "_AM_WEBPHOTO_H4_ENVIRONMENT" , "Ambiente de Checagem" ) ;
define( "_AM_WEBPHOTO_PHPDIRECTIVE" , "Diretivas do PHP" ) ;
define( "_AM_WEBPHOTO_BOTHOK" , "ambos ok" ) ;
define( "_AM_WEBPHOTO_NEEDON" , "precisam de" ) ;

define( "_AM_WEBPHOTO_H4_TABLE" , "Tabela de Checagem" ) ;

//define( "_AM_WEBPHOTO_PHOTOSTABLE" , "Photos table" ) ;
//define( "_AM_WEBPHOTO_DESCRIPTIONTABLE" , "Descriptions table" ) ;
//define( "_AM_WEBPHOTO_CATEGORIESTABLE" , "Categories table" ) ;
//define( "_AM_WEBPHOTO_VOTEDATATABLE" , "Votedata table" ) ;

define("_AM_WEBPHOTO_COMMENTSTABLE" , "Tabela de Comentários" ) ;
define("_AM_WEBPHOTO_NUMBEROFPHOTOS" , "Número de Imagens" ) ;
define("_AM_WEBPHOTO_NUMBEROFDESCRIPTIONS" , "Número de Descrições" ) ;
define("_AM_WEBPHOTO_NUMBEROFCATEGORIES" , "Número de Categorias" ) ;
define("_AM_WEBPHOTO_NUMBEROFVOTEDATA" , "Número de votações" ) ;
define("_AM_WEBPHOTO_NUMBEROFCOMMENTS" , "Número de comentários" ) ;

define( "_AM_WEBPHOTO_H4_CONFIG" , "Configuração da Checagem" ) ;
define( "_AM_WEBPHOTO_PIPEFORIMAGES" , "Pipe para as imagens" ) ;

//define( "_AM_WEBPHOTO_DIRECTORYFORPHOTOS" , "Directory for Photos" ) ;
//define( "_AM_WEBPHOTO_DIRECTORYFORTHUMBS" , "Directory for Thumbnails" ) ;

define( "_AM_WEBPHOTO_ERR_LASTCHAR" , "Erro: O último caracter não pode ser '/'" ) ;
define( "_AM_WEBPHOTO_ERR_FIRSTCHAR" , "Erro: O primeiro caracter deve ser '/'" ) ;
define( "_AM_WEBPHOTO_ERR_PERMISSION" , "Erro: Voc~e primeiro deve criar e aplicar chmod 777 neste diretório, via ftp ou shell." ) ;
define( "_AM_WEBPHOTO_ERR_NOTDIRECTORY" , "Erro: edste não é um diretório." ) ;
define( "_AM_WEBPHOTO_ERR_READORWRITE" , "Erro: Este diretório não tem permissões de escrita e leitura. Você deve alterar as permissões do diretório para 777." ) ;
define( "_AM_WEBPHOTO_ERR_SAMEDIR" , "Erro: O percurso das imagens não dever ser o mesmo percurso das miniaturas" ) ;
define( "_AM_WEBPHOTO_LNK_CHECKGD2" , "Verificque se 'GD2'está trabalhando corretamente sob seu GD incluido no PHP" ) ;
define( "_AM_WEBPHOTO_CHECKGD2" , "Se a página linkada aqui não aparecer corretamente, você não deve usar o GD no modo truecolor." ) ;
define( "_AM_WEBPHOTO_GD2SUCCESS" , "Sucesso!<br />Provavelmente você pode usar o GD2 (truecolor) neste ambiente." ) ;

define( "_AM_WEBPHOTO_H4_PHOTOLINK" , "Checar link das Imagens e Miniaturas" ) ;
define( "_AM_WEBPHOTO_NOWCHECKING" , "Checando agora." ) ;

//define( "_AM_WEBPHOTO_FMT_PHOTONOTREADABLE" , "a main photo (%s) is not readable." ) ;
//define( "_AM_WEBPHOTO_FMT_THUMBNOTREADABLE" , "a thumbnail (%s) is not readable." ) ;

define( "_AM_WEBPHOTO_FMT_NUMBEROFDEADPHOTOS" , "%s Arquivo de imagem morta foi encontrado." ) ;
define( "_AM_WEBPHOTO_FMT_NUMBEROFDEADTHUMBS" , "%s miniaturas devem ser reconstruidas." ) ;
define( "_AM_WEBPHOTO_FMT_NUMBEROFREMOVEDTMPS" , "%s lixo de arquivos foram removidos." ) ;
define( "_AM_WEBPHOTO_LINK_REDOTHUMBS" , "reconstruir miniaturas" ) ;
define( "_AM_WEBPHOTO_LINK_TABLEMAINTENANCE" , "manutenção das tabelas" ) ;


// Redo Thumbnail
//define( "_AM_WEBPHOTO_H3_FMT_RECORDMAINTENANCE" , "myAlbum-P photo maintenance (%s)" ) ;

define( "_AM_WEBPHOTO_FMT_CHECKING" , "checando %s ..." ) ;
define( "_AM_WEBPHOTO_FORM_RECORDMAINTENANCE" , "manutenção das imagens como novo arranjo das miniaturas, etc." ) ;

define( "_AM_WEBPHOTO_FAILEDREADING" , "a leitura falhou." ) ;
define( "_AM_WEBPHOTO_CREATEDTHUMBS" , "criada uma miniatura." ) ;
define( "_AM_WEBPHOTO_BIGTHUMBS" , "falhou a feitura de uma miniatura. copiada." ) ;
define( "_AM_WEBPHOTO_SKIPPED" , "Pulou." ) ;
define( "_AM_WEBPHOTO_SIZEREPAIRED" , "(reparado o tamanho dos campos do registro.)" ) ;
define( "_AM_WEBPHOTO_RECREMOVED" , "este registro foi removido." ) ;
define( "_AM_WEBPHOTO_PHOTONOTEXISTS" , "A foto principal não existe." ) ;
define( "_AM_WEBPHOTO_PHOTORESIZED" , "A foto principal foi redimencionada." ) ;

define( "_AM_WEBPHOTO_TEXT_RECORDFORSTARTING" , "número do registro começando com" ) ;
define( "_AM_WEBPHOTO_TEXT_NUMBERATATIME" , "número de registros processados por vez" ) ;
define( "_AM_WEBPHOTO_LABEL_DESCNUMBERATATIME" , "Um número muito grande pode tirar o servidor do ar." ) ;

define( "_AM_WEBPHOTO_RADIO_FORCEREDO" , "forçar a reelaboração mesmo se a miniatura existir" ) ;
define( "_AM_WEBPHOTO_RADIO_REMOVEREC" , "remover registros que não apresentam link para a imagem principal" ) ;
define( "_AM_WEBPHOTO_RADIO_RESIZE" , "redimencionar fotos maiores que os pixels especificados nas preferências correntes" ) ;

define( "_AM_WEBPHOTO_FINISHED" , "concluido" ) ;
define( "_AM_WEBPHOTO_LINK_RESTART" , "reiniciado" ) ;
define( "_AM_WEBPHOTO_SUBMIT_NEXT" , "próximo" ) ;


// Batch Register
//define( "_AM_WEBPHOTO_H3_FMT_BATCHREGISTER" , "myAlbum-P batch register (%s)" ) ;


// GroupPerm Global
//define( "_AM_WEBPHOTO_GROUPPERM_GLOBAL" , "Global Permissions" ) ;

define( "_AM_WEBPHOTO_GROUPPERM_GLOBALDESC" , "Configurar os previlégios dos grupos para este módulo" ) ;
define( "_AM_WEBPHOTO_GPERMUPDATED" , "As permissões foram mudadas com sucesso" ) ;


// Import
define( "_AM_WEBPHOTO_H3_FMT_IMPORTTO" , 'Importação de imagens de outros módulo para %s' ) ;
define( "_AM_WEBPHOTO_FMT_IMPORTFROMMYALBUMP" , 'Importando de "%s" como módulo tipo myAlbum-P' ) ;
define( "_AM_WEBPHOTO_FMT_IMPORTFROMIMAGEMANAGER" , 'Importação do administrador de imagens do XOOPS' ) ;

//define( "_AM_WEBPHOTO_CB_IMPORTRECURSIVELY" , 'Importing sub-categories recursively' ) ;
//define( "_AM_WEBPHOTO_RADIO_IMPORTCOPY" , 'Copy images (comments will not be copied)' ) ;
//define( "_AM_WEBPHOTO_RADIO_IMPORTMOVE" , 'Move images (comments will be copied)' ) ;

define( "_AM_WEBPHOTO_IMPORTCONFIRM" , 'Confirmar importação. OK?' ) ;
define( "_AM_WEBPHOTO_FMT_IMPORTSUCCESS" , 'Você importou %s imagens' ) ;


// Export
define( "_AM_WEBPHOTO_H3_FMT_EXPORTTO" , 'Exportando imagens de %s para outro módulo' ) ;
define( "_AM_WEBPHOTO_FMT_EXPORTTOIMAGEMANAGER" , 'Exportando para o administrador de imagens do XOOPS' ) ;
define( "_AM_WEBPHOTO_FMT_EXPORTIMSRCCAT" , 'Fonte' ) ;
define( "_AM_WEBPHOTO_FMT_EXPORTIMDSTCAT" , 'Destinação' ) ;
define( "_AM_WEBPHOTO_CB_EXPORTRECURSIVELY" , 'com imagens em suas sub-categorias' ) ;
define( "_AM_WEBPHOTO_CB_EXPORTTHUMB" , 'Exportar miniaturas ao invés das imagens principais' ) ;
define( "_AM_WEBPHOTO_EXPORTCONFIRM" , 'Confirmar exportação. OK?' ) ;
define( "_AM_WEBPHOTO_FMT_EXPORTSUCCESS" , 'Voc~e exportou %s imagens' ) ;


//---------------------------------------------------------
// move from main.php
//---------------------------------------------------------
define("_AM_WEBPHOTO_BTN_SELECTALL" , "Secionar tudo" ) ;
define("_AM_WEBPHOTO_BTN_SELECTNONE" , "Não selecionar" ) ;
define("_AM_WEBPHOTO_BTN_SELECTRVS" , "Selecionar Reverso" ) ;
define("_AM_WEBPHOTO_FMT_PHOTONUM" , "%s em todas as páginas" ) ;

define("_AM_WEBPHOTO_ADMISSION" , "Admissão de Imagens" ) ;
define("_AM_WEBPHOTO_ADMITTING" , "Imagens permitidas" ) ;
define("_AM_WEBPHOTO_LABEL_ADMIT" , "Admitir as imagens que você assinalou" ) ;
define("_AM_WEBPHOTO_BUTTON_ADMIT" , "Admitir" ) ;
define("_AM_WEBPHOTO_BUTTON_EXTRACT" , "extrair" ) ;

define("_AM_WEBPHOTO_LABEL_REMOVE" , "Remover as imagens assinaladas" ) ;
define("_AM_WEBPHOTO_JS_REMOVECONFIRM" , "Remoção OK?" ) ;
define("_AM_WEBPHOTO_LABEL_MOVE" , "Mudar a categoria das imagens assinaladas" ) ;
define("_AM_WEBPHOTO_BUTTON_MOVE" , "Mover" ) ;
define("_AM_WEBPHOTO_BUTTON_UPDATE" , "Modificar" ) ;
define("_AM_WEBPHOTO_DEADLINKMAINPHOTO" , "A imagem principal não existe" ) ;

define("_AM_WEBPHOTO_NOSUBMITTED","Não há novas imgens enviadas.");
define("_AM_WEBPHOTO_ADDMAIN","Adicionar um categoria principal");
define("_AM_WEBPHOTO_IMGURL","URL da imagem (OPCIONAL A altura da imagem será redimencionada para 50): ");
define("_AM_WEBPHOTO_ADD","Adicionar");
define("_AM_WEBPHOTO_ADDSUB","Adicionar uma sub-categoria");
define("_AM_WEBPHOTO_IN","no");
define("_AM_WEBPHOTO_MODCAT","Modificar Categoria");

define("_AM_WEBPHOTO_MODREQDELETED","Requisição de modificação foi deletada.");
define("_AM_WEBPHOTO_IMGURLMAIN","URL da imagem (OPCIONAL e somente válido para as categorias principais. A altura da imagem será redimencionada para 50): ");
define("_AM_WEBPHOTO_PARENT","Categoria Pai:");
define("_AM_WEBPHOTO_SAVE","Salvar altereções");
define("_AM_WEBPHOTO_CATDELETED","Categoria deletada.");
define("_AM_WEBPHOTO_CATDEL_WARNING","AVISO: Você tem certeza que deseja deletar esta categoria e TODAS suas imagens e comentários?");

define("_AM_WEBPHOTO_NEWCATADDED","Nova categoria adicionada com sucesso!");
define("_AM_WEBPHOTO_ERROREXIST","ERRO: A foto que você forneceu já está em nosso banco de dados!");
define("_AM_WEBPHOTO_ERRORTITLE","ERRO: Você precisa informar um título!");
define("_AM_WEBPHOTO_ERRORDESC","ERRO: Você precisa informar uma descrição!");
define("_AM_WEBPHOTO_WEAPPROVED","Nós aprovamos seu envio de link enviado para a imagem em nosso banco de dados.");
define("_AM_WEBPHOTO_THANKSSUBMIT","Agradecemos por sua submissão!");
define("_AM_WEBPHOTO_CONFUPDATED","Configuração atualizada com sucesso!");

define("_AM_WEBPHOTO_PHOTOBATCHUPLOAD","Registrar imagens já enviadas ao servidor");
define("_AM_WEBPHOTO_PHOTOPATH","Percurso");
define("_AM_WEBPHOTO_TEXT_DIRECTORY","Diretorio");
define("_AM_WEBPHOTO_DESC_PHOTOPATH","Digite o percurso integral do diretório incluindo imagens registradas");
define("_AM_WEBPHOTO_MES_INVALIDDIRECTORY"," Foi especificado um diretório inválido.");
define("_AM_WEBPHOTO_MES_BATCHDONE","%s imgem(ns) foram registradas.");
define("_AM_WEBPHOTO_MES_BATCHNONE","Nenhuma foto foi encontrada no diretório.");


//---------------------------------------------------------
// move from myalbum_constants.php
//---------------------------------------------------------
// Global Group Permission
define( "_AM_WEBPHOTO_GPERM_INSERTABLE" , "Postar (necessita aprovação)" ) ;
define( "_AM_WEBPHOTO_GPERM_SUPERINSERT" , "Super Post" ) ;
define( "_AM_WEBPHOTO_GPERM_EDITABLE" , "Editar (necessita aprovação)" ) ;
define( "_AM_WEBPHOTO_GPERM_SUPEREDIT" , "Super Editar" ) ;
define( "_AM_WEBPHOTO_GPERM_DELETABLE" , "Deletar (necessita aprovação)" ) ;
define( "_AM_WEBPHOTO_GPERM_SUPERDELETE" , "Super Deletar" ) ;
define( "_AM_WEBPHOTO_GPERM_TOUCHOTHERS" , "Tocar imagens postadas por outros" ) ;
define( "_AM_WEBPHOTO_GPERM_SUPERTOUCHOTHERS" , "Super tocar outros" ) ;
define( "_AM_WEBPHOTO_GPERM_RATEVIEW" , "Ver avaliação" ) ;
define( "_AM_WEBPHOTO_GPERM_RATEVOTE" , "Votar" ) ;
define( "_AM_WEBPHOTO_GPERM_TELLAFRIEND" , "Diga a um amigo" ) ;

// add for webphoto
define( "_AM_WEBPHOTO_GPERM_TAGEDIT" , "Editar Tag" ) ;

// v0.30
define( "_AM_WEBPHOTO_GPERM_MAIL" , "Postar via e-mail" ) ;
define( "_AM_WEBPHOTO_GPERM_FILE" , "Postar via FTP" ) ;

//=========================================================
// add for webphoto
//=========================================================

//---------------------------------------------------------
// google icon
// modify from gnavi
//---------------------------------------------------------

// list
define("_AM_WEBPHOTO_GICON_ADD" , "Adicionar um novo ícone do Google" ) ;
define("_AM_WEBPHOTO_GICON_LIST_IMAGE" , 'ícone' ) ;
define("_AM_WEBPHOTO_GICON_LIST_SHADOW" , 'Sombra' ) ;
define("_AM_WEBPHOTO_GICON_ANCHOR" , 'Ponto Âncora' ) ;
define("_AM_WEBPHOTO_GICON_WINANC" , 'Janela Âncora' ) ;
define("_AM_WEBPHOTO_GICON_LIST_EDIT" , 'Editar Ícone' ) ;

// form
define("_AM_WEBPHOTO_GICON_MENU_NEW" ,  "Adicionar Ícone" ) ;
define("_AM_WEBPHOTO_GICON_MENU_EDIT" , "Editar Ícone" ) ;
define("_AM_WEBPHOTO_GICON_IMAGE_SEL" ,  "Selecionar Ícone da Imagem" ) ;
define("_AM_WEBPHOTO_GICON_SHADOW_SEL" , "Seleciona Ícone da Sombra" ) ;
define("_AM_WEBPHOTO_GICON_SHADOW_DEL" , 'Deletar Ícone da Sombra' ) ;
define("_AM_WEBPHOTO_GICON_DELCONFIRM" , "Confirmar exclusão do ícone %s ?" ) ;


//---------------------------------------------------------
// mime type
// modify from wfdownloads
//---------------------------------------------------------

// Mimetype Form
define("_AM_WEBPHOTO_MIME_CREATEF", "Criar Mimetype");
define("_AM_WEBPHOTO_MIME_MODIFYF", "Modificar Mimetype");
define("_AM_WEBPHOTO_MIME_NOMIMEINFO", "Não foi selecionado mimetypes.");
define("_AM_WEBPHOTO_MIME_INFOTEXT", "<ul><li>Novos mimetypes podem ser criados, editados ou deletados facilmente através deste formulário.</li>
	<li>Ver mimetypes exibidos enviados pelo Admin e usuários.</li>
	<li>Alterar o status dos mimetype enviados.</li></ul>
	");

// Mimetype Database
define("_AM_WEBPHOTO_MIME_DELETETHIS", "Deletar os Mimetype selecionados?");
define("_AM_WEBPHOTO_MIME_MIMEDELETED", "Mimetype %s está sendo deletado");
define("_AM_WEBPHOTO_MIME_CREATED", "Criado informações do Mimetype");
define("_AM_WEBPHOTO_MIME_MODIFIED", "Modificado as informações do Mimetype");

//image admin icon 
define("_AM_WEBPHOTO_MIME_ICO_EDIT","Editar este item");
define("_AM_WEBPHOTO_MIME_ICO_DELETE","Deletar este item");
define("_AM_WEBPHOTO_MIME_ICO_ONLINE","Online");
define("_AM_WEBPHOTO_MIME_ICO_OFFLINE","Offline");

// find mine type
//define("_AM_WEBPHOTO_MIME_FINDMIMETYPE", "Find New Mimetype:");
//define("_AM_WEBPHOTO_MIME_FINDIT", "Get Extension!");

// added for webphoto
define("_AM_WEBPHOTO_MIME_PERMS", "Grupos permitidos");
define("_AM_WEBPHOTO_MIME_ALLOWED", "Mimetype permitidos");
define("_AM_WEBPHOTO_MIME_NOT_ENTER_EXT", "Não informar a extensão");

//---------------------------------------------------------
// check config
//---------------------------------------------------------
define("_AM_WEBPHOTO_DIRECTORYFOR_PHOTOS" , "Diretorio para imagens" ) ;
define("_AM_WEBPHOTO_DIRECTORYFOR_THUMBS" , "Diretório para miniaturas" ) ;
define("_AM_WEBPHOTO_DIRECTORYFOR_GICONS" , "Diretório para ícones do Google" ) ;
define("_AM_WEBPHOTO_DIRECTORYFOR_TMP" ,    "Diretório temporário" ) ;

//---------------------------------------------------------
// checktable
//---------------------------------------------------------
define("_AM_WEBPHOTO_NUMBEROFRECORED", "Número de registros");

//---------------------------------------------------------
// manage
//---------------------------------------------------------
define("_AM_WEBPHOTO_MANAGE_DESC","<b>Cuidado</b><br />A administração desta tabela somente<br />Não muda as tabelas relacionadas");
define("_AM_WEBPHOTO_ERR_NO_RECORD", "Não há registros");

//---------------------------------------------------------
// cat manager
//---------------------------------------------------------
define("_AM_WEBPHOTO_DSC_CAT_IMGPATH" , "Percurso do diretório onde o XOOPS está instaldo.<br />(O primeiro caracter deve ser '/'.)" ) ;
define("_AM_WEBPHOTO_OPT_CAT_PERM_POST_ALL" , "Todos os grupos" ) ;

//---------------------------------------------------------
// import
//---------------------------------------------------------
define("_AM_WEBPHOTO_FMT_IMPORTFROM_WEBPHOTO" , 'Importando do "%s" como tipo de módulo webphoto' ) ;
define("_AM_WEBPHOTO_IMPORT_COMMENT_NO" , "Não copiar comentários" ) ;
define("_AM_WEBPHOTO_IMPORT_COMMENT_YES" , "Copiar comentários") ;

//---------------------------------------------------------
// v0.20
//---------------------------------------------------------
define("_AM_WEBPHOTO_PATHINFO_LINK" , "Verificar se 'Pathinfo' está trabalhando corretamente em seu servidor" ) ;
define("_AM_WEBPHOTO_PATHINFO_DSC" , "Se a página linkada aqui não mostrar corretamente, você não deve usar o 'Pathinfo' " ) ;
define("_AM_WEBPHOTO_PATHINFO_SUCCESS" , "Sucesso!<br />Provavelmente você pode usar o 'Pathinfo' em seu servidor" ) ;
define("_AM_WEBPHOTO_CAP_REDO_EXIF" , "Obter Exif" ) ;
define("_AM_WEBPHOTO_RADIO_REDO_EXIF_TRY" , "Obter quando não configurado" ) ;
define("_AM_WEBPHOTO_RADIO_REDO_EXIF_ALWAYS" , "Obter sempre" ) ;

//---------------------------------------------------------
// v0.30
//---------------------------------------------------------
// checkconfigs
define("_AM_WEBPHOTO_DIRECTORYFOR_FILE" , "Diretório para FTP do arquivo" ) ;
define("_AM_WEBPHOTO_WARN_GEUST_CAN_READ" ,  "Usuários anônimos podem ler o arquivo neste diretório" ) ;
define("_AM_WEBPHOTO_WARN_RECOMMEND_PATH" ,  "Recomendado configurá-lo, exceto sob o diretório raiz" ) ;
define("_AM_WEBPHOTO_MULTIBYTE_LINK" , "Verificar se 'Charset Convert' está trabalhando corretamenteem seu servidor)" ) ;
define("_AM_WEBPHOTO_MULTIBYTE_DSC" , "Se a página linkada aqui não mostrar corretamente, você não deve usar o 'Charset Convert' " ) ;
define("_AM_WEBPHOTO_MULTIBYTE_SUCCESS" , "Você pode ler corretamente este sentença, sem caracter distorcido? " ) ;

// maillog manager
define("_AM_WEBPHOTO_SHOW_LIST" ,  "Mostrar lista" ) ;
define("_AM_WEBPHOTO_MAILLOG_STATUS_REJECT" ,  "E-mail Rejeitado" ) ;
define("_AM_WEBPHOTO_MAILLOG_STATUS_PARTIAL" , "O e-mail que foi rejeitado contém algum arquivo anexo" ) ;
define("_AM_WEBPHOTO_MAILLOG_STATUS_SUBMIT" ,  "E-mal submetido" ) ;
define("_AM_WEBPHOTO_BUTTON_SUBMIT_MAIL" ,  "Submeter e-mail" ) ;
define("_AM_WEBPHOTO_ERR_MAILLOG_NO_ATTACH" ,  "Voc~e deve selecionar os arquivos anexos" ) ;

// mimetype
define("_AM_WEBPHOTO_MIME_ADD_NEW" ,  "Adicionar novo tipo de MIME" ) ;

//---------------------------------------------------------
// v0.40
//---------------------------------------------------------
// index
define("_AM_WEBPHOTO_MUST_UPDATE" , "Você deve atualizar" ) ;
define("_AM_WEBPHOTO_TITLE_BIN" , "Administrar comando" ) ;
define("_AM_WEBPHOTO_TEST_BIN" ,  "Executar teste" ) ;

// redothumbs
define("_AM_WEBPHOTO_ERR_GET_IMAGE_SIZE", "não é possivel obter o tamanho da imagem" ) ;

// checktables
define("_AM_WEBPHOTO_FMT_NOT_READABLE" , "%s (%s) não é permitida a leitura." ) ;

// === define end ===
}

?>
