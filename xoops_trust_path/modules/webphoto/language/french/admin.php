<?php
// $Id: admin.php,v 1.2 2009/05/17 08:24:26 ohwada Exp $

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
define('_AM_WEBPHOTO_MYMENU_TPLSADMIN','Templates');
define('_AM_WEBPHOTO_MYMENU_BLOCKSADMIN','Blocs/Permissions');

//define('_AM_WEBPHOTO_MYMENU_MYPREFERENCES','Préférences');

// add for webphoto
define("_AM_WEBPHOTO_MYMENU_GOTO_MODULE" , "Page d'accueil du module" ) ;


// Index (Categories)
//define( "_AM_WEBPHOTO_H3_FMT_CATEGORIES" , "Gestion des Catégories (%s)" ) ;
//define( "_AM_WEBPHOTO_CAT_TH_TITLE" , "Nom" ) ;

define( "_AM_WEBPHOTO_CAT_TH_PHOTOS" , "Images" ) ;
define( "_AM_WEBPHOTO_CAT_TH_OPERATION" , "Actions" ) ;
define( "_AM_WEBPHOTO_CAT_TH_IMAGE" , "Bannière" ) ;
define( "_AM_WEBPHOTO_CAT_TH_PARENT" , "Parent" ) ;

//define( "_AM_WEBPHOTO_CAT_TH_IMGURL" , "URL de la bannière" ) ;

define( "_AM_WEBPHOTO_CAT_MENU_NEW" , "Créer une Catégorie" ) ;
define( "_AM_WEBPHOTO_CAT_MENU_EDIT" , "Editer une Catégorie" ) ;
define( "_AM_WEBPHOTO_CAT_INSERTED" , "Une nouvelle Catégorie a été ajoutée" ) ;
define( "_AM_WEBPHOTO_CAT_UPDATED" , "La Catégorie a été modifiée" ) ;
define( "_AM_WEBPHOTO_CAT_BTN_BATCH" , "Appliquer" ) ;
define( "_AM_WEBPHOTO_CAT_LINK_MAKETOPCAT" , "Créer une nouvelle Catégorie au sommet" ) ;
define( "_AM_WEBPHOTO_CAT_LINK_ADDPHOTOS" , "Ajouter une image dans cette Catégorie" ) ;
define( "_AM_WEBPHOTO_CAT_LINK_EDIT" , "Modifier cette Catégorie" ) ;
define( "_AM_WEBPHOTO_CAT_LINK_MAKESUBCAT" , "Créer une nouvelle Catégorie sous celle-ci" ) ;
define( "_AM_WEBPHOTO_CAT_FMT_NEEDADMISSION" , "%s images sont nécessaires pour être admises" ) ;
define( "_AM_WEBPHOTO_CAT_FMT_CATDELCONFIRM" , "%s sera éffacée avec ses sous-catégories, images et commentaires. Etes-vous certain de vouloir continuer ?" ) ;


// Admission
//define( "_AM_WEBPHOTO_H3_FMT_ADMISSION" , "Images admises (%s)" ) ;
//define( "_AM_WEBPHOTO_TH_SUBMITTER" , "Proposé par" ) ;
//define( "_AM_WEBPHOTO_TH_TITLE" , "Titre" ) ;
//define( "_AM_WEBPHOTO_TH_DESCRIPTION" , "Description" ) ;
//define( "_AM_WEBPHOTO_TH_CATEGORIES" , "Catégorie" ) ;
//define( "_AM_WEBPHOTO_TH_DATE" , "Dernière mise à jour" ) ;


// Photo Manager
//define( "_AM_WEBPHOTO_H3_FMT_PHOTOMANAGER" , "Gestion des photos (%s)" ) ;

define( "_AM_WEBPHOTO_TH_BATCHUPDATE" , "Mise à jour par lot" ) ;
define( "_AM_WEBPHOTO_OPT_NOCHANGE" , "- AUCUN CHANGEMENT -" ) ;
define( "_AM_WEBPHOTO_JS_UPDATECONFIRM" , "Les éléments cochés seront mis à jour. Souhaitez-vous continuer ?" ) ;


// Module Checker
//define( "_AM_WEBPHOTO_H3_FMT_MODULECHECKER" , "myAlbum-P checker (%s)" ) ;

define( "_AM_WEBPHOTO_H4_ENVIRONMENT" , "Contrôle de l'environnement" ) ;
define( "_AM_WEBPHOTO_PHPDIRECTIVE" , "Spécifications PHP" ) ;
define( "_AM_WEBPHOTO_BOTHOK" , "Les deux ok" ) ;
define( "_AM_WEBPHOTO_NEEDON" , "Nécessite on" ) ;

define( "_AM_WEBPHOTO_H4_TABLE" , "Contrôle des Tables" ) ;

//define( "_AM_WEBPHOTO_PHOTOSTABLE" , "Table des photos" ) ;
//define( "_AM_WEBPHOTO_DESCRIPTIONTABLE" , "Table des descriptions" ) ;
//define( "_AM_WEBPHOTO_CATEGORIESTABLE" , "Table des catégories" ) ;
//define( "_AM_WEBPHOTO_VOTEDATATABLE" , "Table des votes" ) ;

define("_AM_WEBPHOTO_COMMENTSTABLE" , "Table des commentaires" ) ;
define("_AM_WEBPHOTO_NUMBEROFPHOTOS" , "Nombre de photos" ) ;
define("_AM_WEBPHOTO_NUMBEROFDESCRIPTIONS" , "Nombre de descriptions" ) ;
define("_AM_WEBPHOTO_NUMBEROFCATEGORIES" , "Nombre de Catégories" ) ;
define("_AM_WEBPHOTO_NUMBEROFVOTEDATA" , "Nomre de votes" ) ;
define("_AM_WEBPHOTO_NUMBEROFCOMMENTS" , "Nombre de commentaires" ) ;

define( "_AM_WEBPHOTO_H4_CONFIG" , "Contrôle de la Configuration" ) ;
define( "_AM_WEBPHOTO_PIPEFORIMAGES" , "Script pour les images / vidéos" ) ;

//define( "_AM_WEBPHOTO_DIRECTORYFORPHOTOS" , "Répertoire des photos" ) ;
//define( "_AM_WEBPHOTO_DIRECTORYFORTHUMBS" , "Répertoire des miniatures" ) ;

define( "_AM_WEBPHOTO_ERR_LASTCHAR" , "Erreur: le dernier caractère ne doit pas être '/'" ) ;
define( "_AM_WEBPHOTO_ERR_FIRSTCHAR" , "Erreur: le premier caractère doit être '/'" ) ;
define( "_AM_WEBPHOTO_ERR_PERMISSION" , "Erreur: vous devez d'abord créer ce répertoire et lui attribuer un CHMOD 777." ) ;
define( "_AM_WEBPHOTO_ERR_NOTDIRECTORY" , "Erreur: ceci n'est pas un répertoire." ) ;
define( "_AM_WEBPHOTO_ERR_READORWRITE" , "Erreur: ce répertoire n'est pas ouvert en écriture ou ne peut pas être ouvert. Vous devez lui attribuer un CHMOD 777." ) ;
define( "_AM_WEBPHOTO_ERR_SAMEDIR" , "Erreur: les chemins d'accès aux photos et aux miniatures doivent être différent." ) ;
define( "_AM_WEBPHOTO_LNK_CHECKGD2" , "Vérifiez que la librairie GD2 fonctionne correctement." ) ;
define( "_AM_WEBPHOTO_CHECKGD2" , "Si la page ne s'affiche pas correctement, n'utilisez pas la librairie GD en mode 'True colors'." ) ;
define( "_AM_WEBPHOTO_GD2SUCCESS" , "Test réussi !<br />Votre environnement devrait vous permettre d'utiliser la librairie GD2 en mode 'True colors'." ) ;

define( "_AM_WEBPHOTO_H4_PHOTOLINK" , "Vérifier les liens des photos et des miniatures" ) ;
define( "_AM_WEBPHOTO_NOWCHECKING" , "Vérification en cours." ) ;

//define( "_AM_WEBPHOTO_FMT_PHOTONOTREADABLE" , "Photo principale (%s) ne peut être lue." ) ;
//define( "_AM_WEBPHOTO_FMT_THUMBNOTREADABLE" , "Miniature (%s) ne peut être lue." ) ;

define( "_AM_WEBPHOTO_FMT_NUMBEROFDEADPHOTOS" , "%s fichiers de photos obsolètes ont été trouvés." ) ;
define( "_AM_WEBPHOTO_FMT_NUMBEROFDEADTHUMBS" , "%s miniatures devraient être re-générées." ) ;
define( "_AM_WEBPHOTO_FMT_NUMBEROFREMOVEDTMPS" , "%s fichiers obsolètes ont été supprimés." ) ;
define( "_AM_WEBPHOTO_LINK_REDOTHUMBS" , "Reconstruire les miniatures" ) ;
define( "_AM_WEBPHOTO_LINK_TABLEMAINTENANCE" , "Vérifier les tables" ) ;


// Redo Thumbnail
//define( "_AM_WEBPHOTO_H3_FMT_RECORDMAINTENANCE" , "myAlbum-P photo maintenance (%s)" ) ;

define( "_AM_WEBPHOTO_FMT_CHECKING" , "Vérification %s ..." ) ;
define( "_AM_WEBPHOTO_FORM_RECORDMAINTENANCE" , "Maintenance des photos, re-génération des miniatures etc." ) ;

define( "_AM_WEBPHOTO_FAILEDREADING" , "Erreur de lecture." ) ;
define( "_AM_WEBPHOTO_CREATEDTHUMBS" , "Miniature créée." ) ;
define( "_AM_WEBPHOTO_BIGTHUMBS" , "Echec dans création des miniatures. Copiées." ) ;
define( "_AM_WEBPHOTO_SKIPPED" , "Abandon." ) ;
define( "_AM_WEBPHOTO_SIZEREPAIRED" , "(taille des champs réparés.)" ) ;
define( "_AM_WEBPHOTO_RECREMOVED" , "cet enregistrement à été supprimé." ) ;
define( "_AM_WEBPHOTO_PHOTONOTEXISTS" , "La photo principale n'existe pas." ) ;
define( "_AM_WEBPHOTO_PHOTORESIZED" , "La photo principale a été redimensionnée." ) ;

define( "_AM_WEBPHOTO_TEXT_RECORDFORSTARTING" , "Le nombre d'enregistrement commence à" ) ;
define( "_AM_WEBPHOTO_TEXT_NUMBERATATIME" , "Nombre d'enregistrement réalisés simultanément" ) ;
define( "_AM_WEBPHOTO_LABEL_DESCNUMBERATATIME" , "Un nombre trop important peut provoquer une erreur (Time out du serveur)." ) ;

define( "_AM_WEBPHOTO_RADIO_FORCEREDO" , "Force la re-génération de la vignette si elle existe déjà" ) ;
define( "_AM_WEBPHOTO_RADIO_REMOVEREC" , "Supprime les enregistrements qui ne disposent pas de photo principale" ) ;
define( "_AM_WEBPHOTO_RADIO_RESIZE" , "Redimensionne les photos dont la taille dépasse celles indiqués dans les Préférences" ) ;

define( "_AM_WEBPHOTO_FINISHED" , "Terminé" ) ;
define( "_AM_WEBPHOTO_LINK_RESTART" , "Redémarre" ) ;
define( "_AM_WEBPHOTO_SUBMIT_NEXT" , "Suivant" ) ;


// Batch Register
//define( "_AM_WEBPHOTO_H3_FMT_BATCHREGISTER" , "myAlbum-P batch enregistrement (%s)" ) ;


// GroupPerm Global
//define( "_AM_WEBPHOTO_GROUPPERM_GLOBAL" , "Permissions globales" ) ;

define( "_AM_WEBPHOTO_GROUPPERM_GLOBALDESC" , "Configuration des permissions affectées aux Groupes" ) ;
define( "_AM_WEBPHOTO_GPERMUPDATED" , "Les permissions ont été correctement mises à jour" ) ;


// Import
define( "_AM_WEBPHOTO_H3_FMT_IMPORTTO" , "Importation de photos depuis un autre module %s" ) ;
define( "_AM_WEBPHOTO_FMT_IMPORTFROMMYALBUMP" , "Importation vers %s à partir d'un module de type myAlbum-P" ) ;
define( "_AM_WEBPHOTO_FMT_IMPORTFROMIMAGEMANAGER" , "Importation depuis le Gestionnaire d'images de Xoops" ) ;

//define( "_AM_WEBPHOTO_CB_IMPORTRECURSIVELY" , 'Importation incluant les sous-catégories' ) ;
//define( "_AM_WEBPHOTO_RADIO_IMPORTCOPY" , 'Copier les images (les commentaires ne seront pas copiés)' ) ;
//define( "_AM_WEBPHOTO_RADIO_IMPORTMOVE" , 'Déplacer les images (avec les commentaires)' ) ;

define( "_AM_WEBPHOTO_IMPORTCONFIRM" , "Confirmer l'importaion ?" ) ;
define( "_AM_WEBPHOTO_FMT_IMPORTSUCCESS" , "%s images ont été importées." ) ;


// Export
define( "_AM_WEBPHOTO_H3_FMT_EXPORTTO" , "Exportation de photos depuis %s vers un autre modules" ) ;
define( "_AM_WEBPHOTO_FMT_EXPORTTOIMAGEMANAGER" , "Exporter les photos dans le Gestionnaire d'image de Xoops" ) ;
define( "_AM_WEBPHOTO_FMT_EXPORTIMSRCCAT" , "Source" ) ;
define( "_AM_WEBPHOTO_FMT_EXPORTIMDSTCAT" , "Destination" ) ;
define( "_AM_WEBPHOTO_CB_EXPORTRECURSIVELY" , "avec les images dans leurs sous-catégories" ) ;
define( "_AM_WEBPHOTO_CB_EXPORTTHUMB" , "Exporter les miniatures au lieu des images principales" ) ;
define( "_AM_WEBPHOTO_EXPORTCONFIRM" , "Confirmer l'importation ?" ) ;
define( "_AM_WEBPHOTO_FMT_EXPORTSUCCESS" , "%s images ont été exportées" ) ;


//---------------------------------------------------------
// move from main.php
//---------------------------------------------------------
define("_AM_WEBPHOTO_BTN_SELECTALL" , "Tout sélectionner" ) ;
define("_AM_WEBPHOTO_BTN_SELECTNONE" , "Ne rien sélectionner" ) ;
define("_AM_WEBPHOTO_BTN_SELECTRVS" , "Inverser la sélection" ) ;
define("_AM_WEBPHOTO_FMT_PHOTONUM" , "%s par page" ) ;

define("_AM_WEBPHOTO_ADMISSION" , "Admettre des photos" ) ;
define("_AM_WEBPHOTO_ADMITTING" , "Photo(s) admise(s)" ) ;
define("_AM_WEBPHOTO_LABEL_ADMIT" , "Admettre les photos cochées" ) ;
define("_AM_WEBPHOTO_BUTTON_ADMIT" , "Admettre" ) ;
define("_AM_WEBPHOTO_BUTTON_EXTRACT" , "Extraire" ) ;

define("_AM_WEBPHOTO_LABEL_REMOVE" , "Supprimer les photos cochées" ) ;
define("_AM_WEBPHOTO_JS_REMOVECONFIRM" , "Confirmer la suppression ?" ) ;
define("_AM_WEBPHOTO_LABEL_MOVE" , "Déplacer les photos cochées dans une autre Catégorie" ) ;
define("_AM_WEBPHOTO_BUTTON_MOVE" , "Déplacer" ) ;
define("_AM_WEBPHOTO_BUTTON_UPDATE" , "Modifier" ) ;
define("_AM_WEBPHOTO_DEADLINKMAINPHOTO" , "L'image principale n'existe pas" ) ;

define("_AM_WEBPHOTO_NOSUBMITTED","Aucun nouvelle photo soumise.");
define("_AM_WEBPHOTO_ADDMAIN","Ajouter une Catégorie principale");
define("_AM_WEBPHOTO_IMGURL","URL de la vignette (optionnelle). L'image sera redimensionnée à 50px de haut. ");
define("_AM_WEBPHOTO_ADD","Ajouter");
define("_AM_WEBPHOTO_ADDSUB","Ajouter une sous-catégorie");
define("_AM_WEBPHOTO_IN","dans");
define("_AM_WEBPHOTO_MODCAT","Modifier la Catégorie");

define("_AM_WEBPHOTO_MODREQDELETED","Demande de modification annulée.");
define("_AM_WEBPHOTO_IMGURLMAIN","URL de la vignette (optionnelle, et seulement disponible pour les Catégories principales). L'image sera redimensionnée à 50px de haut. ");
define("_AM_WEBPHOTO_PARENT","Catégorie parente:");
define("_AM_WEBPHOTO_SAVE","Sauvegarder les modifications");
define("_AM_WEBPHOTO_CATDELETED","Catégorie effacée.");
define("_AM_WEBPHOTO_CATDEL_WARNING","AVERTISSEMEN§T: êtes vous certain de vouloir effacer cette Catégorie ainsi que toutes les photos et tous les commentaires contenus ?");

define("_AM_WEBPHOTO_NEWCATADDED","Nouvelle Catégorie ajoutée !");
define("_AM_WEBPHOTO_ERROREXIST","Erreur: la photo figure déjà dans la base de données");
define("_AM_WEBPHOTO_ERRORTITLE","Erreur: vous devez saisir un titre!");
define("_AM_WEBPHOTO_ERRORDESC","Erreur: vous devez saisir une description!");
define("_AM_WEBPHOTO_WEAPPROVED","Le lien proposé a été enregistré en base de données.");
define("_AM_WEBPHOTO_THANKSSUBMIT","Merci pour votre participation !");
define("_AM_WEBPHOTO_CONFUPDATED","Mise à jour de la configuration effectuée");

define("_AM_WEBPHOTO_PHOTOBATCHUPLOAD","Formulaire concernant les photos déjà téléversées sur le serveur");
define("_AM_WEBPHOTO_PHOTOPATH","Chemin");
define("_AM_WEBPHOTO_TEXT_DIRECTORY","Répertoire");
define("_AM_WEBPHOTO_DESC_PHOTOPATH","Saisir le chemin complet pointant vers le répertoire contenant les photos à enregistrer.");
define("_AM_WEBPHOTO_MES_INVALIDDIRECTORY","Le répertoire indiqué n'est pas valide.");
define("_AM_WEBPHOTO_MES_BATCHDONE","%s photo(s) ont été enregistrées.");
define("_AM_WEBPHOTO_MES_BATCHNONE","Aucune photo n'a été détectée dans le répertoire.");


//---------------------------------------------------------
// move from myalbum_constants.php
//---------------------------------------------------------
// Global Group Permission
define( "_AM_WEBPHOTO_GPERM_INSERTABLE" , "Envoi (avec modération)" ) ;
define( "_AM_WEBPHOTO_GPERM_SUPERINSERT" , "Envoi direct" ) ;
define( "_AM_WEBPHOTO_GPERM_EDITABLE" , "Modification (avec modération)" ) ;
define( "_AM_WEBPHOTO_GPERM_SUPEREDIT" , "Modification directe" ) ;
define( "_AM_WEBPHOTO_GPERM_DELETABLE" , "Suppression (avec modération)" ) ;
define( "_AM_WEBPHOTO_GPERM_SUPERDELETE" , "Suppression directe" ) ;
define( "_AM_WEBPHOTO_GPERM_TOUCHOTHERS" , "Intervention sur les photos d'autres utilisateurs (avec modération)" ) ;
define( "_AM_WEBPHOTO_GPERM_SUPERTOUCHOTHERS" , "Intervention directe sur les photos d'autres utilisateurs" ) ;
define( "_AM_WEBPHOTO_GPERM_RATEVIEW" , "Consulter les votes" ) ;
define( "_AM_WEBPHOTO_GPERM_RATEVOTE" , "Voter" ) ;
define( "_AM_WEBPHOTO_GPERM_TELLAFRIEND" , "En parler à un(e) ami(e)" ) ;

// add for webphoto
define( "_AM_WEBPHOTO_GPERM_TAGEDIT" , "Edit Tag" ) ;

// v0.30
define( "_AM_WEBPHOTO_GPERM_MAIL" , "Envoyer par e-mail" ) ;
define( "_AM_WEBPHOTO_GPERM_FILE" , "Envoyer par FTP" ) ;

//=========================================================
// add for webphoto
//=========================================================

//---------------------------------------------------------
// google icon
// modify from gnavi
//---------------------------------------------------------

// list
define("_AM_WEBPHOTO_GICON_ADD" , "Ajouter une nouvelle icône Google" ) ;
define("_AM_WEBPHOTO_GICON_LIST_IMAGE" , "Icône" ) ;
define("_AM_WEBPHOTO_GICON_LIST_SHADOW" , "Ombre" ) ;
define("_AM_WEBPHOTO_GICON_ANCHOR" , "Point d'ancrage" ) ;
define("_AM_WEBPHOTO_GICON_WINANC" , "Fenêtre d'ancrage" ) ;
define("_AM_WEBPHOTO_GICON_LIST_EDIT" , "Icône d'édition" ) ;

// form
define("_AM_WEBPHOTO_GICON_MENU_NEW" ,  "Icône Ajouter" ) ;
define("_AM_WEBPHOTO_GICON_MENU_EDIT" , "Icône Modifier" ) ;
define("_AM_WEBPHOTO_GICON_IMAGE_SEL" ,  "Icône Sélectionner" ) ;
define("_AM_WEBPHOTO_GICON_SHADOW_SEL" , "Icône Sélectionner une ombre" ) ;
define("_AM_WEBPHOTO_GICON_SHADOW_DEL" , 'Icône Supprimer une ombre' ) ;
define("_AM_WEBPHOTO_GICON_DELCONFIRM" , "Confirmer la suppression de l'icône %s ?" ) ;


//---------------------------------------------------------
// mime type
// modify from wfdownloads
//---------------------------------------------------------

// Mimetype Form
define("_AM_WEBPHOTO_MIME_CREATEF", "Créer un Mimetype");
define("_AM_WEBPHOTO_MIME_MODIFYF", "Modifier un Mimetype");
define("_AM_WEBPHOTO_MIME_NOMIMEINFO", "Aucun Mimetypes sélectionner.");
define("_AM_WEBPHOTO_MIME_INFOTEXT", "<ul>Cette rubrique vous permet de<li>créer, modifier ou supprimer facilement un Mimetype</li>
	<li>voir les Mimetypes autorisés pour les administrateurs et pour les utilisateurs</li>
	<li>changer les permissions de téléversements associés à chaque Mimetype.</li></ul>
	");

// Mimetype Database
define("_AM_WEBPHOTO_MIME_DELETETHIS", "Effacer les Mimetypes sélectionnés ?");
define("_AM_WEBPHOTO_MIME_MIMEDELETED", "Mimetype %s a été effacé");
define("_AM_WEBPHOTO_MIME_CREATED", "Les informations relatives au Mimetype ont été créées.");
define("_AM_WEBPHOTO_MIME_MODIFIED", "Les informations relatives au Mimetype ont été modifiées.");

//image admin icon 
define("_AM_WEBPHOTO_MIME_ICO_EDIT","Editer cet élément");
define("_AM_WEBPHOTO_MIME_ICO_DELETE","Effacer cet élément");
define("_AM_WEBPHOTO_MIME_ICO_ONLINE","En ligne");
define("_AM_WEBPHOTO_MIME_ICO_OFFLINE","Hors ligne");

// find mine type
//define("_AM_WEBPHOTO_MIME_FINDMIMETYPE", "Chercher de nouveaux Mimetype:");
//define("_AM_WEBPHOTO_MIME_FINDIT", "Obtenir une extension");

// added for webphoto
define("_AM_WEBPHOTO_MIME_PERMS", "Groupes autorisés");
define("_AM_WEBPHOTO_MIME_ALLOWED", "Mimetypes autorisés");
define("_AM_WEBPHOTO_MIME_NOT_ENTER_EXT", "Extension non enregistrée");

//---------------------------------------------------------
// check config
//---------------------------------------------------------
define("_AM_WEBPHOTO_DIRECTORYFOR_PHOTOS" , "Répertoire pour les photos" ) ;
define("_AM_WEBPHOTO_DIRECTORYFOR_THUMBS" , "Répertoire pour les vignettes" ) ;
define("_AM_WEBPHOTO_DIRECTORYFOR_GICONS" , "Répertoire pour les icônes Google" ) ;
define("_AM_WEBPHOTO_DIRECTORYFOR_TMP" ,    "Répertoire pour les éléments provisoires (mail, log et tmp)" ) ;

//---------------------------------------------------------
// checktable
//---------------------------------------------------------
define("_AM_WEBPHOTO_NUMBEROFRECORED", "Nombre d'enregistrements");

//---------------------------------------------------------
// manage
//---------------------------------------------------------
define("_AM_WEBPHOTO_MANAGE_DESC","<b>Attention</b><br />Le présent gestionnaire ne concerne que ce tableau<br />Il n'affecte pas les autres tableaux");
define("_AM_WEBPHOTO_ERR_NO_RECORD", "Il n'y a aucun enregistrement");

//---------------------------------------------------------
// cat manager
//---------------------------------------------------------
//define("_AM_WEBPHOTO_DSC_CAT_IMGPATH" , "Veuillez télécharger l'image souhaitée pour la Catégorie<br />et indiquer le chemin depuis le dossier d'installation de Xoops.<br />(le premier caractère doit être '/'.)<br />Si rien n'est indiqué, l'icône figurant un dossier sera employée." ) ;
//define("_AM_WEBPHOTO_OPT_CAT_PERM_POST_ALL" , "Tous les Groupes" ) ;

//---------------------------------------------------------
// import
//---------------------------------------------------------
define("_AM_WEBPHOTO_FMT_IMPORTFROM_WEBPHOTO" , "Importer depuis '%s'" ) ;
define("_AM_WEBPHOTO_IMPORT_COMMENT_NO" , "Ne pas copier les commentaires" ) ;
define("_AM_WEBPHOTO_IMPORT_COMMENT_YES" , "Copier les commentaires") ;

//---------------------------------------------------------
// v0.20
//---------------------------------------------------------
define("_AM_WEBPHOTO_PATHINFO_LINK" , "Vérifier que 'Pathinfo' fonctionne correctement sur le serveur" ) ;
define("_AM_WEBPHOTO_PATHINFO_DSC" , "Si la page ne s'affiche pas correctement, vous ne devrez pas employer 'Pathinfo' " ) ;
define("_AM_WEBPHOTO_PATHINFO_SUCCESS" , "Test réussi!<br />Votre environnement devrait vous permettre d'utiliser 'Pathinfo'" ) ;
define("_AM_WEBPHOTO_CAP_REDO_EXIF" , "Acquérir les données Exif" ) ;
define("_AM_WEBPHOTO_RADIO_REDO_EXIF_TRY" , "Acquérir les données Exif lorsque cela n'est pas établi" ) ;
define("_AM_WEBPHOTO_RADIO_REDO_EXIF_ALWAYS" , "Toujours acquérir les données Exif" ) ;

//---------------------------------------------------------
// v0.30
//---------------------------------------------------------
// checkconfigs
define("_AM_WEBPHOTO_DIRECTORYFOR_FILE" , "Répertoire pour les transferts FTP" ) ;
define("_AM_WEBPHOTO_WARN_GEUST_CAN_READ" ,  "Les utilisateurs anonymes ont la permission de lire dans ce répertoire" ) ;
define("_AM_WEBPHOTO_WARN_RECOMMEND_PATH" ,  "Il est conseillé de placer ces éléments dans la zone protégée de votre serveur" ) ;
define("_AM_WEBPHOTO_MULTIBYTE_LINK" , "Vérifier que la fonction 'Charset Convert' fonctionne correctement sur votre serveur)" ) ;
define("_AM_WEBPHOTO_MULTIBYTE_DSC" , "Si la page ne s'affiche pas correctement, vous ne devrez pas employer 'Charset Convert' " ) ;
define("_AM_WEBPHOTO_MULTIBYTE_SUCCESS" , "Etes-vous en mesure de lire cette phrase ? " ) ;

// maillog manager
define("_AM_WEBPHOTO_SHOW_LIST" ,  "Afficher la liste" ) ;
define("_AM_WEBPHOTO_MAILLOG_STATUS_REJECT" ,  "E-mail rejetés" ) ;
define("_AM_WEBPHOTO_MAILLOG_STATUS_PARTIAL" , "E-mail dont certaines pièces jointes ont été rejetées" ) ;
define("_AM_WEBPHOTO_MAILLOG_STATUS_SUBMIT" ,  "E-mail envoyés" ) ;
define("_AM_WEBPHOTO_BUTTON_SUBMIT_MAIL" ,  "Envoyer un e-mail" ) ;
define("_AM_WEBPHOTO_ERR_MAILLOG_NO_ATTACH" ,  "Vous devez sélectionner les fichiers attachés" ) ;

// mimetype
define("_AM_WEBPHOTO_MIME_ADD_NEW" ,  "Ajouter un nouveau Mimetype" ) ;

//---------------------------------------------------------
// v0.40
//---------------------------------------------------------
// index
define("_AM_WEBPHOTO_MUST_UPDATE" , "Vous devez effectuer une mise à jour" ) ;
define("_AM_WEBPHOTO_TITLE_BIN" , "Gestionnaire de commande" ) ;
define("_AM_WEBPHOTO_TEST_BIN" ,  "Exécuter le test !" ) ;

// redothumbs
define("_AM_WEBPHOTO_ERR_GET_IMAGE_SIZE", "Impossible d'obtenir la taille de l'image" ) ;

// checktables
define("_AM_WEBPHOTO_FMT_NOT_READABLE" , "%s (%s) ne peut être lu." ) ;

//---------------------------------------------------------
// v0.50
//---------------------------------------------------------
// config check
define("_AM_WEBPHOTO_DIRECTORYFOR_UPLOADS" , "Répertoire pour les fichiers téléversés" ) ;
define("_AM_WEBPHOTO_DIRECTORYFOR_MEDIAS" , "Répertoire pour les fichiers média" ) ;

// item manager
define("_AM_WEBPHOTO_ITEM_SELECT","Sélectionner l'élément");
define("_AM_WEBPHOTO_ITEM_ADD","Ajouter un élément");
define("_AM_WEBPHOTO_ITEM_LISTING","Voir l'élément");
define("_AM_WEBPHOTO_VOTE_DELETED","Vote effacé");
define("_AM_WEBPHOTO_VOTE_STATS","Statistiques des votes");
define("_AM_WEBPHOTO_VOTE_ENTRY","Votes entrés");
define("_AM_WEBPHOTO_VOTE_USER","Votes des utilisateurs enregistrés");
define("_AM_WEBPHOTO_VOTE_GUEST","Votes des utilisateurs anonymes");
define("_AM_WEBPHOTO_VOTE_TOTAL","Total des votes");
define("_AM_WEBPHOTO_VOTE_USERAVG","Moyenne des votes utilisateurs");
define("_AM_WEBPHOTO_VOTE_USERVOTES","Total des votes utilisateurs");
define("_AM_WEBPHOTO_LOG_VIEW","Voir le fichier Log");
define("_AM_WEBPHOTO_LOG_EMPT","Vider le fichier Log");
define("_AM_WEBPHOTO_PLAYLIST_PATH","Chemin d'accès à la playlist");
define("_AM_WEBPHOTO_PLAYLIST_REFRESH","Mettre à jour le cache de la playlist");
define("_AM_WEBPHOTO_STATUS_CHANGE","Statut d'édition");
define("_AM_WEBPHOTO_STATUS_OFFLINE","Hors ligne");
define("_AM_WEBPHOTO_STATUS_ONLINE","En ligne");
define("_AM_WEBPHOTO_STATUS_AUTO","Publication automatique");

// item form
define("_AM_WEBPHOTO_TIME_NOW","Heure du serveur");

// playlist form
define("_AM_WEBPHOTO_PLAYLIST_ADD", "Ajouter une playlist" ) ;
define("_AM_WEBPHOTO_PLAYLIST_TYPE", "Type de playlist" ) ;
define("_AM_WEBPHOTO_PLAYLIST_FEED_DSC","Saisir l'url du Fil d'information");
define("_AM_WEBPHOTO_PLAYLIST_DIR_DSC","Choisir le nom du répertoire");

// player manager
define("_AM_WEBPHOTO_PLAYER_MANAGER","Gestion du lecteur");
define("_AM_WEBPHOTO_PLAYER_ADD","Ajouter un nouveau lecteur");
define("_AM_WEBPHOTO_PLAYER_MOD","Modifier le lecteur");
define("_AM_WEBPHOTO_PLAYER_CLONE","Dupliquer le lecteur");
define("_AM_WEBPHOTO_PLAYER_ADDED","Nouveau lecteur ajouté");
define("_AM_WEBPHOTO_PLAYER_DELETED","Lecteur effacé");
define("_AM_WEBPHOTO_PLAYER_MODIFIED","Lecteur modifié");
define("_AM_WEBPHOTO_PLAYER_PREVIEW","Prévisualiser");
define("_AM_WEBPHOTO_PLAYER_PREVIEW_DSC","Veuillez sauvegarder vos modifications avant");
define("_AM_WEBPHOTO_PLAYER_PREVIEW_LINK","Prévisualiser la source");
define("_AM_WEBPHOTO_PLAYER_NO_ITEM","Il n'y a aucun élément à lire");
define("_AM_WEBPHOTO_PLAYER_WARNING","AVERTISSEMENT : êtes-vous certain de vouloir supprimer ce lecteur ?<br />Veuillez modifier au préalable tous les éléments utilisant ce lecteur !");
define("_AM_WEBPHOTO_PLAYER_ERR_EXIST","Erreur : un titre identique est déjà enregistré dans la base de données !");
define("_AM_WEBPHOTO_BUTTON_CLONE","Cloner");

//---------------------------------------------------------
// v0.60
//---------------------------------------------------------
// cat form
define("_AM_WEBPHOTO_CAP_CAT_SELECT","Sélectionner la Catégorie de l'image");
define("_AM_WEBPHOTO_DSC_CAT_PATH" , "Indiquez le chemin d'accès depuis le répertoire d'installation de Xoops.<br />(le premier caractère doit être '/'.)" ) ;
define("_AM_WEBPHOTO_DSC_CAT_FOLDER" , "Affiche l'icône figurant un dossier si aucune information n'est entrée" ) ;

//---------------------------------------------------------
// v0.70
//---------------------------------------------------------
define("_AM_WEBPHOTO_RECOMMEND_OFF" , "Off recommamdé" ) ;

//---------------------------------------------------------
// v0.80
//---------------------------------------------------------
define("_AM_WEBPHOTO_TITLE_WAITING" , "Liste des éléments en attente d'approbation" ) ;
define("_AM_WEBPHOTO_TITLE_OFFLINE" , "Liste des éléments désactivés" ) ;
define("_AM_WEBPHOTO_TITLE_EXPIRED" , "Liste des éléments expirés" ) ;

//---------------------------------------------------------
// v0.81
//---------------------------------------------------------
// checkconfigs
define("_AM_WEBPHOTO_QR_CHECK_LINK" , "Vérifier que 'QR Code' fonctionne correctement" ) ;
define("_AM_WEBPHOTO_QR_CHECK_DSC" , "Si la page ne s'affiche pas correctement, vous ne devrez pas utiliser 'QR Code' " ) ;
define("_AM_WEBPHOTO_QR_CHECK_SUCCESS" , "Etes-vous en mesure de voir 'QR Code' correctement ?" ) ;
define("_AM_WEBPHOTO_QR_CHECK_SHOW" , "Afficher les informations de débogage" ) ;
define("_AM_WEBPHOTO_QR_CHECK_INFO" , "Information de débogage" ) ;

//---------------------------------------------------------
// v0.90
//---------------------------------------------------------
// cat form
define("_AM_WEBPHOTO_CAT_PARENT_CAP" , "Permission des Catégories filles" ) ;
define("_AM_WEBPHOTO_CAT_PARENT_FMT" , "Accès aux persmissions des Catégories filles ( %s )" ) ;
define("_AM_WEBPHOTO_CAT_CHILD_CAP"  , "Catégories filles" ) ;
define("_AM_WEBPHOTO_CAT_CHILD_NUM"  , "Nombre de Catégories filles" ) ;
define("_AM_WEBPHOTO_CAT_CHILD_PERM" , "Modifier les permissions des Catégories filles" ) ;

//---------------------------------------------------------
// v1.00
//---------------------------------------------------------
// groupperm
define( "_AM_WEBPHOTO_GPERM_HTML" , "Utiliser le HTML" ) ;

//---------------------------------------------------------
// v1.21
//---------------------------------------------------------
define( "_AM_WEBPHOTO_RSS_DEBUG" , "Visualiser le deboggage RSS " ) ;
define( "_AM_WEBPHOTO_RSS_CLEAR" , "Effacer le cache RSS" ) ;
define( "_AM_WEBPHOTO_RSS_CLEARED" , "Cache RSS effacé" ) ;

//---------------------------------------------------------
// v1.40
//---------------------------------------------------------
define("_AM_WEBPHOTO_TIMELINE_MODULE" , "Module Timeline" ) ;
define("_AM_WEBPHOTO_MODULE_NOT_INSTALL" , "Le module n'est pas installé" ) ;

//---------------------------------------------------------
// v1.50
//---------------------------------------------------------
define("_AM_WEBPHOTO_FILE_CHECK" , "Vérifier la validité du fichier" ) ;
define("_AM_WEBPHOTO_FILE_CHECK_DSC" , "les contrôles sont les fichiers nécessaires à la taille du fichier" ) ;

// === define end ===
}

?>