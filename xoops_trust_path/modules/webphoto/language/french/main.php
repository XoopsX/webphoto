<?php
// $Id: main.php,v 1.1 2009/03/07 07:34:24 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

// === define begin ===
if( !defined("_MB_WEBPHOTO_LANG_LOADED") ) 
{

define("_MB_WEBPHOTO_LANG_LOADED" , 1 ) ;

//=========================================================
// base on myalbum
//=========================================================

define("_WEBPHOTO_CATEGORY","Catégorie");
define("_WEBPHOTO_SUBMITTER","Participant");
define("_WEBPHOTO_NOMATCH_PHOTO","Aucune photo correspondant à votre demande");

define("_WEBPHOTO_ICON_NEW","Nouveau");
define("_WEBPHOTO_ICON_UPDATE","Mise à jour");
define("_WEBPHOTO_ICON_POPULAR","Popularité");
define("_WEBPHOTO_ICON_LASTUPDATE","Dernière mise à jour");
define("_WEBPHOTO_ICON_HITS","Classement");
define("_WEBPHOTO_ICON_COMMENTS","Commentaires");

define("_WEBPHOTO_SORT_IDA","Record Number (Du plus petit ID au plus grand)");
define("_WEBPHOTO_SORT_IDD","Record Number (Du plus grand ID au plus petit)");
define("_WEBPHOTO_SORT_HITSA","Popularité (Du moins au plus populaire)");
define("_WEBPHOTO_SORT_HITSD","Popularité (Du plus au moins populaire)");
define("_WEBPHOTO_SORT_TITLEA","Titre (A à Z)");
define("_WEBPHOTO_SORT_TITLED","Titre (Z à A))");
define("_WEBPHOTO_SORT_DATEA","Date de mise à jour (La plus ancienne photo en premier)");
define("_WEBPHOTO_SORT_DATED","Date de mise à jour (La plus récente photo en premier)");
define("_WEBPHOTO_SORT_RATINGA","Evaluation (de la plus basse à la plus haute note)");
define("_WEBPHOTO_SORT_RATINGD","Evaluation (de la plus haute à la plus basse note)");
define("_WEBPHOTO_SORT_RANDOM","Aléatoire");

define("_WEBPHOTO_SORT_SORTBY","Trier par:");
define("_WEBPHOTO_SORT_TITLE","Titre");
define("_WEBPHOTO_SORT_DATE","Date de mise à jour");
define("_WEBPHOTO_SORT_HITS","Popularité");
define("_WEBPHOTO_SORT_RATING","Evaluation");
define("_WEBPHOTO_SORT_S_CURSORTEDBY","Les photos sont actuellement triés par: %s");

define("_WEBPHOTO_NAVI_PREVIOUS","Précédent");
define("_WEBPHOTO_NAVI_NEXT","Suivante");
define("_WEBPHOTO_S_NAVINFO" , "Photo N°. %s - %s (de %s photos populaire)" ) ;
define("_WEBPHOTO_S_THEREARE","Il y a <b>%s</b> Images dans notre base de données.");
define("_WEBPHOTO_S_MOREPHOTOS","Plus de photos de %s");
define("_WEBPHOTO_ONEVOTE","1 vote");
define("_WEBPHOTO_S_NUMVOTES","%s votes");
define("_WEBPHOTO_ONEPOST","1 message");
define("_WEBPHOTO_S_NUMPOSTS","%s messages");
define("_WEBPHOTO_VOTETHIS","Vote this");
define("_WEBPHOTO_TELLAFRIEND","Envoyer à un contact");
define("_WEBPHOTO_SUBJECT4TAF","Une photo pour vous");


//---------------------------------------------------------
// submit
//---------------------------------------------------------
// only "Y/m/d" , "d M Y" , "M d Y" can be interpreted
define("_WEBPHOTO_DTFMT_YMDHI" , "d M Y H:i" ) ;

define("_WEBPHOTO_TITLE_ADDPHOTO","Ajouter une photo");
define("_WEBPHOTO_TITLE_PHOTOUPLOAD","Envoyer une photo");
define("_WEBPHOTO_CAP_MAXPIXEL","Nombre maximum de pixel");
define("_WEBPHOTO_CAP_MAXSIZE","Taille maximun du fichier (byte)");
define("_WEBPHOTO_CAP_VALIDPHOTO","Valider");
define("_WEBPHOTO_DSC_TITLE_BLANK","Laissez le titre vide pour utiliser le nom du fichier comme titre");

define("_WEBPHOTO_RADIO_ROTATETITLE" , "Rotation de l'image" ) ;
define("_WEBPHOTO_RADIO_ROTATE0" , "Reste fixe" ) ;
define("_WEBPHOTO_RADIO_ROTATE90" , "tourner à droite" ) ;
define("_WEBPHOTO_RADIO_ROTATE180" , "tourner à 180 degré" ) ;
define("_WEBPHOTO_RADIO_ROTATE270" , "tourner à gauche" ) ;

define("_WEBPHOTO_SUBMIT_RECEIVED","Nous avons reçu votre photo. Merci de votre participation!");
define("_WEBPHOTO_SUBMIT_ALLPENDING","Toutes les photos sont affichées en attente de vérification.");

define("_WEBPHOTO_ERR_MUSTREGFIRST","Désolé, vous n'avez pas la permission d'effectuer cette action.<br /> vous devez vous inscrire d'abord!");
define("_WEBPHOTO_ERR_MUSTADDCATFIRST","Désolé, il n'y a pas de catégories d'ajouter pour le moment.<br /> vous devez en créer une d'abord!");
define("_WEBPHOTO_ERR_NOIMAGESPECIFIED","Aucune photo n'a été téléchargé");
define("_WEBPHOTO_ERR_FILE","Les photos sont trop volumineuses ou il y a un problème avec la configuration");
define("_WEBPHOTO_ERR_FILEREAD","Le format photos n'est pas lisible.");
define("_WEBPHOTO_ERR_TITLE","Vous devez entrer un 'Titre' ");


//---------------------------------------------------------
// edit
//---------------------------------------------------------
define("_WEBPHOTO_TITLE_EDIT","Modifier photo");
define("_WEBPHOTO_TITLE_PHOTODEL","Supprimer la photo");
define("_WEBPHOTO_CONFIRM_PHOTODEL","Voulez-vous vraiment supprimer la photo?");
define("_WEBPHOTO_DBUPDATED","Base de données mis à jour avec succès!");
define("_WEBPHOTO_DELETED","Supprimé!");


//---------------------------------------------------------
// rate
//---------------------------------------------------------
define("_WEBPHOTO_RATE_VOTEONCE","Ne voter pas plusieurs fois pour la même ressource.");
define("_WEBPHOTO_RATE_RATINGSCALE","L'échelle est 1 - 10, avec 1 étant faible et 10 étant excellent.");
define("_WEBPHOTO_RATE_BEOBJECTIVE","Soyez objectif, les notes 1 et 10 doivent être exceptionnelles.");
define("_WEBPHOTO_RATE_DONOTVOTE","Ne votez pas pour vos propres ressources.");
define("_WEBPHOTO_RATE_IT","A voter!");
define("_WEBPHOTO_RATE_VOTEAPPRE","Votre vote est appréciée.");
define("_WEBPHOTO_RATE_S_THANKURATE","Merci de prendre le temps de noter cette photo %s.");

define("_WEBPHOTO_ERR_NORATING","Aucun vote n'est retenu.");
define("_WEBPHOTO_ERR_CANTVOTEOWN","Vous ne pouvez pas voter sur les ressources que vous avez soumises.<br /> Tous les votes sont enregistrés et examinés.");
define("_WEBPHOTO_ERR_VOTEONCE","Voter pour la ressource qu'une fois seulement. <br /> Tous les votes sont enregistrés et examinés.");

//---------------------------------------------------------
// movo to admin.php
//---------------------------------------------------------
// New in myAlbum-P

// only "Y/m/d" , "d M Y" , "M d Y" can be interpreted
//define( "_WEBPHOTO_DTFMT_YMDHI" , "d M Y H:i" ) ;

//define( "_WEBPHOTO_NEXT_BUTTON" , "Next" ) ;
//define( "_WEBPHOTO_REDOLOOPDONE" , "Done." ) ;

//define( "_WEBPHOTO_BTN_SELECTALL" , "Select All" ) ;
//define( "_WEBPHOTO_BTN_SELECTNONE" , "Select None" ) ;
//define( "_WEBPHOTO_BTN_SELECTRVS" , "Select Reverse" ) ;
//define( "_WEBPHOTO_FMT_PHOTONUM" , "%s every page" ) ;

//define( "_WEBPHOTO_AM_ADMISSION" , "Admit Photos" ) ;
//define( "_WEBPHOTO_AM_ADMITTING" , "Admitted photo(s)" ) ;
//define( "_WEBPHOTO_AM_LABEL_ADMIT" , "Admit the photos you checked" ) ;
//define( "_WEBPHOTO_AM_BUTTON_ADMIT" , "Admit" ) ;
//define( "_WEBPHOTO_AM_BUTTON_EXTRACT" , "extract" ) ;

//define( "_WEBPHOTO_AM_PHOTOMANAGER" , "Photo Manager" ) ;
//define( "_WEBPHOTO_AM_PHOTONAVINFO" , "Photo No. %s-%s (out of %s photos hit)" ) ;
//define( "_WEBPHOTO_AM_LABEL_REMOVE" , "Remove the photos checked" ) ;
//define( "_WEBPHOTO_AM_BUTTON_REMOVE" , "Remove!" ) ;
//define( "_WEBPHOTO_AM_JS_REMOVECONFIRM" , "Remove OK?" ) ;
//define( "_WEBPHOTO_AM_LABEL_MOVE" , "Change category of the checked photos" ) ;
//define( "_WEBPHOTO_AM_BUTTON_MOVE" , "Move" ) ;
//define( "_WEBPHOTO_AM_BUTTON_UPDATE" , "Modify" ) ;
//define( "_WEBPHOTO_AM_DEADLINKMAINPHOTO" , "The main image don't exist" ) ;


//---------------------------------------------------------
// not use
//---------------------------------------------------------
// New MyAlbum 1.0.1 (and 1.2.0)
//define("_WEBPHOTO_MOREPHOTOS","More Photos from %s");
//define("_WEBPHOTO_REDOTHUMBS","Redo Thumbnails (<a href='redothumbs.php'>re-start</a>)");
//define("_WEBPHOTO_REDOTHUMBS2","Rebuild Thumbnails");
//define("_WEBPHOTO_REDOTHUMBSINFO","Too large a number may lead to server time out.");
//define("_WEBPHOTO_REDOTHUMBSNUMBER","Number of thumbs at a time");
//define("_WEBPHOTO_REDOING","Redoing: ");
//define("_WEBPHOTO_BACK","Return");
//define("_WEBPHOTO_ADDPHOTO","Add Photo");


//---------------------------------------------------------
// movo to admin.php
//---------------------------------------------------------
// New MyAlbum 1.0.0
//define("_WEBPHOTO_PHOTOBATCHUPLOAD","Register photos uploaded to the server already");
//define("_WEBPHOTO_PHOTOUPLOAD","Photo Upload");
//define("_WEBPHOTO_PHOTOEDITUPLOAD","Photo Edit and Re-upload");
//define("_WEBPHOTO_MAXPIXEL","Max pixel size");
//define("_WEBPHOTO_MAXSIZE","Max file size(byte)");
//define("_WEBPHOTO_PHOTOTITLE","Title");
//define("_WEBPHOTO_PHOTOPATH","Path");
//define("_WEBPHOTO_TEXT_DIRECTORY","Directory");
//define("_WEBPHOTO_DESC_PHOTOPATH","Type the full path of the directory including photos to be registered");
//define("_WEBPHOTO_MES_INVALIDDIRECTORY","Invalid directory is specified.");
//define("_WEBPHOTO_MES_BATCHDONE","%s photo(s) have been registered.");
//define("_WEBPHOTO_MES_BATCHNONE","No photo was detected in the directory.");
//define("_WEBPHOTO_PHOTODESC","Description");
//define("_WEBPHOTO_PHOTOCAT","Category");
//define("_WEBPHOTO_SELECTFILE","Select photo");
//define("_WEBPHOTO_NOIMAGESPECIFIED","Error: No photo was uploaded");
//define("_WEBPHOTO_FILEERROR","Error: Photos are too big or there is a problem with the configuration");
//define("_WEBPHOTO_FILEREADERROR","Error: Photos are not readable.");

//define("_WEBPHOTO_BATCHBLANK","Leave title blank to use file names as title");
//define("_WEBPHOTO_DELETEPHOTO","Delete?");
//define("_WEBPHOTO_VALIDPHOTO","Valid");
//define("_WEBPHOTO_PHOTODEL","Delete photo?");
//define("_WEBPHOTO_DELETINGPHOTO","Deleting photo");
//define("_WEBPHOTO_MOVINGPHOTO","Moving photo");

//define("_WEBPHOTO_STORETIMESTAMP","Don't touch timestamp");

//define("_WEBPHOTO_POSTERC","Poster: ");
//define("_WEBPHOTO_DATEC","Date: ");
//define("_WEBPHOTO_EDITNOTALLOWED","You're not allowed to edit this comment!");
//define("_WEBPHOTO_ANONNOTALLOWED","Anonymous users are not allowed to post.");
//define("_WEBPHOTO_THANKSFORPOST","Thanks for your submission!");
//define("_WEBPHOTO_DELNOTALLOWED","You're not allowed to delete this comment!");
//define("_WEBPHOTO_GOBACK","Go Back");
//define("_WEBPHOTO_AREYOUSURE","Are you sure you want to delete this comment and all comments under it?");
//define("_WEBPHOTO_COMMENTSDEL","Comment(s) Deleted Successfully!");

// End New


//---------------------------------------------------------
// not use
//---------------------------------------------------------
//define("_WEBPHOTO_THANKSFORINFO","Thank you for the information. We'll look into your request shortly.");
//define("_WEBPHOTO_BACKTOTOP","Back to Photo Top");
//define("_WEBPHOTO_THANKSFORHELP","Thank you for helping to maintain this directory's integrity.");
//define("_WEBPHOTO_FORSECURITY","For security reasons your user name and IP address will also be temporarily recorded.");

//define("_WEBPHOTO_MATCH","Match");
//define("_WEBPHOTO_ALL","ALL");
//define("_WEBPHOTO_ANY","ANY");
//define("_WEBPHOTO_NAME","Name");
//define("_WEBPHOTO_DESCRIPTION","Description");

//define("_WEBPHOTO_MAIN","Main");
//define("_WEBPHOTO_NEW","New");
//define("_WEBPHOTO_UPDATED","Updated");
//define("_WEBPHOTO_POPULAR","Popular");
//define("_WEBPHOTO_TOPRATED","Top Rated");

//define("_WEBPHOTO_POPULARITYLTOM","Popularity (Least to Most Hits)");
//define("_WEBPHOTO_POPULARITYMTOL","Popularity (Most to Least Hits)");
//define("_WEBPHOTO_TITLEATOZ","Title (A to Z)");
//define("_WEBPHOTO_TITLEZTOA","Title (Z to A)");
//define("_WEBPHOTO_DATEOLD","Date (Old Photos Listed First)");
//define("_WEBPHOTO_DATENEW","Date (New Photos Listed First)");
//define("_WEBPHOTO_RATINGLTOH","Rating (Lowest Score to Highest Score)");
//define("_WEBPHOTO_RATINGHTOL","Rating (Highest Score to Lowest Score)");
//define("_WEBPHOTO_LIDASC","Record Number (Smaller to Bigger)");
//define("_WEBPHOTO_LIDDESC","Record Number (Smaller is latter)");

//define("_WEBPHOTO_NOSHOTS","No Screenshots Available");
//define("_WEBPHOTO_EDITTHISPHOTO","Edit This Photo");

//define("_WEBPHOTO_DESCRIPTIONC","Description");
//define("_WEBPHOTO_EMAILC","Email");
//define("_WEBPHOTO_CATEGORYC","Category");
//define("_WEBPHOTO_SUBCATEGORY","Sub-category");
//define("_WEBPHOTO_LASTUPDATEC","Last Update");

//define("_WEBPHOTO_HITSC","Hits");
//define("_WEBPHOTO_RATINGC","Rating");
//define("_WEBPHOTO_NUMVOTES","%s votes");
//define("_WEBPHOTO_NUMPOSTS","%s posts");
//define("_WEBPHOTO_COMMENTSC","Comments");
//define("_WEBPHOTO_RATETHISPHOTO","Rate it");
//define("_WEBPHOTO_MODIFY","Modify");
//define("_WEBPHOTO_VSCOMMENTS","View/Send Comments");

//define("_WEBPHOTO_DIRECTCATSEL","SELECT A CATEGORY");
//define("_WEBPHOTO_THEREARE","There are <b>%s</b> Images in our Database.");
//define("_WEBPHOTO_LATESTLIST","Dernières listes");

//define("_WEBPHOTO_VOTEAPPRE","Your vote is appreciated.");
//define("_WEBPHOTO_THANKURATE","Thank you for taking the time to rate a photo here at %s.");
//define("_WEBPHOTO_VOTEONCE","Please do not vote for the same resource more than once.");
//define("_WEBPHOTO_RATINGSCALE","The scale is 1 - 10, with 1 being poor and 10 being excellent.");
//define("_WEBPHOTO_BEOBJECTIVE","Please be objective, if everyone receives a 1 or a 10, the ratings aren't very useful.");
//define("_WEBPHOTO_DONOTVOTE","Do not vote for your own resource.");
//define("_WEBPHOTO_RATEIT","Rate It!");

//define("_WEBPHOTO_RECEIVED","We received your Photo. Thank you!");
//define("_WEBPHOTO_ALLPENDING","All photos are posted pending verification.");

//define("_WEBPHOTO_RANK","Rank");
//define("_WEBPHOTO_SUBCATEGORY","Sub-category");
//define("_WEBPHOTO_HITS","Hits");
//define("_WEBPHOTO_RATING","Rating");
//define("_WEBPHOTO_VOTE","Vote");
//define("_WEBPHOTO_TOP10","%s Top 10"); // %s is a photo category title

//define("_WEBPHOTO_SORTBY","Sort by:");
//define("_WEBPHOTO_TITLE","Title");
//define("_WEBPHOTO_DATE","Date");
//define("_WEBPHOTO_POPULARITY","Popularity");
//define("_WEBPHOTO_CURSORTEDBY","Photos currently sorted by: %s");
//define("_WEBPHOTO_FOUNDIN","Found in:");
//define("_WEBPHOTO_PREVIOUS","Previous");
//define("_WEBPHOTO_NEXT","Next");
//define("_WEBPHOTO_NOMATCH","No photo matches your request");

//define("_WEBPHOTO_CATEGORIES","Categories");
//define("_WEBPHOTO_SUBMIT","Submit");
//define("_WEBPHOTO_CANCEL","Cancel");

//define("_WEBPHOTO_MUSTREGFIRST","Sorry, you don't have permission to perform this action.<br>Please register or login first!");
//define("_WEBPHOTO_MUSTADDCATFIRST","Sorry, there are no categories to add to yet.<br>Please create a category first!");
//define("_WEBPHOTO_NORATING","No rating selected.");
//define("_WEBPHOTO_CANTVOTEOWN","You cannot vote on the resource you submitted.<br>All votes are logged and reviewed.");
//define("_WEBPHOTO_VOTEONCE2","Vote for the selected resource only once.<br>All votes are logged and reviewed.");


//---------------------------------------------------------
// move to admin.php
//---------------------------------------------------------
//%%%%%%	Module Name 'MyAlbum' (Admin)	  %%%%%
//define("_WEBPHOTO_PHOTOSWAITING","Photos Waiting for Validation");
//define("_WEBPHOTO_PHOTOMANAGER","Photo Management");
//define("_WEBPHOTO_CATEDIT","Add, Modify, and Delete Categories");
//define("_WEBPHOTO_GROUPPERM_GLOBAL","Global Permissions");
//define("_WEBPHOTO_CHECKCONFIGS","Check Configs & Environment");
//define("_WEBPHOTO_BATCHUPLOAD","Batch Register");
//define("_WEBPHOTO_GENERALSET","Preferences");
//define("_WEBPHOTO_REDOTHUMBS2","Rebuild Thumbnails");

//define("_WEBPHOTO_DELETE","Delete");
//define("_WEBPHOTO_NOSUBMITTED","No New Submitted Photos.");
//define("_WEBPHOTO_ADDMAIN","Add a MAIN Category");
//define("_WEBPHOTO_IMGURL","Image URL (OPTIONAL Image height will be resized to 50): ");
//define("_WEBPHOTO_ADD","Add");
//define("_WEBPHOTO_ADDSUB","Add a SUB-Category");
//define("_WEBPHOTO_IN","in");
//define("_WEBPHOTO_MODCAT","Modify Category");

//define("_WEBPHOTO_MODREQDELETED","Modification Request Deleted.");
//define("_WEBPHOTO_IMGURLMAIN","Image URL (OPTIONAL and Only valid for main categories. Image height will be resized to 50): ");
//define("_WEBPHOTO_PARENT","Parent Category:");
//define("_WEBPHOTO_SAVE","Save Changes");
//define("_WEBPHOTO_CATDELETED","Category Deleted.");
//define("_WEBPHOTO_CATDEL_WARNING","WARNING: Are you sure you want to delete this Category and ALL its Photos and Comments?");
//define("_WEBPHOTO_YES","Yes");
//define("_WEBPHOTO_NO","No");
//define("_WEBPHOTO_NEWCATADDED","New Category Added Successfully!");
//define("_WEBPHOTO_ERROREXIST","ERROR: The Photo you provided is already in the database!");
//define("_WEBPHOTO_ERRORTITLE","ERROR: You need to enter a TITLE!");
//define("_WEBPHOTO_ERRORDESC","ERROR: You need to enter a DESCRIPTION!");
//define("_WEBPHOTO_WEAPPROVED","We approved your link submission to the photo database.");
//define("_WEBPHOTO_THANKSSUBMIT","Thank you for your submission!");
//define("_WEBPHOTO_CONFUPDATED","Configuration Updated Successfully!");


//------------------------------------------------ ---------
// Passage de myalbum_constants.php
//------------------------------------------------ ---------
// Légende
define("_WEBPHOTO_CAPTION_TOTAL","Total:");
define("_WEBPHOTO_CAPTION_GUESTNAME","Visiteur");
define("_WEBPHOTO_CAPTION_REFRESH","rafraîchir");
define("_WEBPHOTO_CAPTION_IMAGEXYT","Taille (Type)");
define("_WEBPHOTO_CAPTION_CATEGORY","Catégorie");


//================================================ =========
// Ajouter pour webphoto
//================================================ =========

//------------------------------------------------ ---------
// Table de base de données articles
//------------------------------------------------ ---------

// Tableau photo
define("_WEBPHOTO_PHOTO_TABLE","Table de photos");
define("_WEBPHOTO_PHOTO_ID","Photo ID");
define("_WEBPHOTO_PHOTO_TIME_CREATE","Créer l'heure");
define("_WEBPHOTO_PHOTO_TIME_UPDATE","Mise à jour Time");
define("_WEBPHOTO_PHOTO_CAT_ID","ID de catégorie");
define("_WEBPHOTO_PHOTO_GICON_ID","Icon ID");
define("_WEBPHOTO_PHOTO_UID","ID de l'utilisateur");
define("_WEBPHOTO_PHOTO_DATETIME","Photo Datetime");
define("_WEBPHOTO_PHOTO_TITLE","Titre de photo");
define("_WEBPHOTO_PHOTO_PLACE","Place");
define("_WEBPHOTO_PHOTO_EQUIPMENT","Equipement");
define("_WEBPHOTO_PHOTO_FILE_URL","Dossier Web (URL)");
define("_WEBPHOTO_PHOTO_FILE_PATH","Chemin du fichier");
define("_WEBPHOTO_PHOTO_FILE_NAME","Nom de fichier");
define("_WEBPHOTO_PHOTO_FILE_EXT","Extension du fichier");
define("_WEBPHOTO_PHOTO_FILE_MIME","Fichier de type MIME");
define("_WEBPHOTO_PHOTO_FILE_MEDIUM","Type de fichier moyenne");
define("_WEBPHOTO_PHOTO_FILE_SIZE","Taille du fichier");
define("_WEBPHOTO_PHOTO_CONT_URL","L'URL de la photo");
define("_WEBPHOTO_PHOTO_CONT_PATH","Le chemin de la photo");
define("_WEBPHOTO_PHOTO_CONT_NAME","Nom de la photo");
define("_WEBPHOTO_PHOTO_CONT_EXT","Photo Extension");
define("_WEBPHOTO_PHOTO_CONT_MIME","photo de type MIME");
define("_WEBPHOTO_PHOTO_CONT_MEDIUM","Photo Support Type");
define("_WEBPHOTO_PHOTO_CONT_SIZE","Photo Taille du fichier");
define("_WEBPHOTO_PHOTO_CONT_WIDTH","Largeur de l'image photo");
define("_WEBPHOTO_PHOTO_CONT_HEIGHT","Hauteur de l'image photo");
define("_WEBPHOTO_PHOTO_CONT_DURATION","Durée de la vidéo Time");
define("_WEBPHOTO_PHOTO_CONT_EXIF","Exif Information");
define("_WEBPHOTO_PHOTO_MIDDLE_WIDTH","Moyen-Largeur de l'image");
define("_WEBPHOTO_PHOTO_MIDDLE_HEIGHT","Moyen-Hauteur de l'image");
define("_WEBPHOTO_PHOTO_THUMB_URL","pouce Web");
define("_WEBPHOTO_PHOTO_THUMB_PATH","Thumb Path");
define("_WEBPHOTO_PHOTO_THUMB_NAME","Pouce Nom");
define("_WEBPHOTO_PHOTO_THUMB_EXT","Extension du pouce");
define("_WEBPHOTO_PHOTO_THUMB_MIME","Thumb MIME type");
define("_WEBPHOTO_PHOTO_THUMB_MEDIUM","Pouce Meduim Type");
define("_WEBPHOTO_PHOTO_THUMB_SIZE","Pouce Taille du fichier");
define("_WEBPHOTO_PHOTO_THUMB_WIDTH","Pouce Largeur de l'image");
define("_WEBPHOTO_PHOTO_THUMB_HEIGHT","Pouce Hauteur de l'image");
define("_WEBPHOTO_PHOTO_GMAP_LATITUDE","GoogleMap Latitude");
define("_WEBPHOTO_PHOTO_GMAP_LONGITUDE","GoogleMap Longitude");
define("_WEBPHOTO_PHOTO_GMAP_ZOOM","GoogleMap Zoom");
define("_WEBPHOTO_PHOTO_GMAP_TYPE","Type GoogleMap");
define("_WEBPHOTO_PHOTO_PERM_READ","Lire la permission");
define("_WEBPHOTO_PHOTO_STATUS","Status");
define("_WEBPHOTO_PHOTO_HITS","Hits");
define("_WEBPHOTO_PHOTO_RATING","Note");
define("_WEBPHOTO_PHOTO_VOTES","Votes");
define("_WEBPHOTO_PHOTO_COMMENTS","commentaire");
define("_WEBPHOTO_PHOTO_TEXT1","Text1");
define("_WEBPHOTO_PHOTO_TEXT2","Text2");
define("_WEBPHOTO_PHOTO_TEXT3","Text3");
define("_WEBPHOTO_PHOTO_TEXT4","Text4");
define("_WEBPHOTO_PHOTO_TEXT5","Text5");
define("_WEBPHOTO_PHOTO_TEXT6","Text6");
define("_WEBPHOTO_PHOTO_TEXT7","Text7");
define("_WEBPHOTO_PHOTO_TEXT8","Text8");
define("_WEBPHOTO_PHOTO_TEXT9","Text9");
define("_WEBPHOTO_PHOTO_TEXT10","Text10");
define("_WEBPHOTO_PHOTO_DESCRIPTION","Photo Description");
define("_WEBPHOTO_PHOTO_SEARCH","Recherche");

// Tableau de la catégorie
define("_WEBPHOTO_CAT_TABLE","Catégorie Table");
define("_WEBPHOTO_CAT_ID","ID de catégorie");
define("_WEBPHOTO_CAT_TIME_CREATE","Créer l'heure");
define("_WEBPHOTO_CAT_TIME_UPDATE","Mise à jour Time");
define("_WEBPHOTO_CAT_GICON_ID","Icon ID");
define("_WEBPHOTO_CAT_FORUM_ID","Forum ID");
define("_WEBPHOTO_CAT_PID","Parent ID");
define("_WEBPHOTO_CAT_TITLE","Titre Catégorie");
define("_WEBPHOTO_CAT_IMG_PATH","Catégorie Image Path");
define("_WEBPHOTO_CAT_IMG_MODE","Mode d'affichage d'images");
define("_WEBPHOTO_CAT_ORIG_WIDTH","Image Original Width");
define("_WEBPHOTO_CAT_ORIG_HEIGHT","Image Original Taille");
define("_WEBPHOTO_CAT_MAIN_WIDTH","Largeur de l'image dans la catégorie principale");
define("_WEBPHOTO_CAT_MAIN_HEIGHT","Hauteur de l'image dans la catégorie principale");
define("_WEBPHOTO_CAT_SUB_WIDTH","Largeur de l'image dans la sous catégorie");
define("_WEBPHOTO_CAT_SUB_HEIGHT","Hauteur de l'image dans la sous catégorie");
define("_WEBPHOTO_CAT_WEIGHT","Poids");
define("_WEBPHOTO_CAT_DEPTH","Profondeur");
define("_WEBPHOTO_CAT_ALLOWED_EXT","Extensions autorisées");
define("_WEBPHOTO_CAT_ITEM_TYPE","Type d'élément");
define("_WEBPHOTO_CAT_GMAP_MODE","Voir GoogleMap Mode");
define("_WEBPHOTO_CAT_GMAP_LATITUDE","GoogleMap Latitude");
define("_WEBPHOTO_CAT_GMAP_LONGITUDE","GoogleMap Longitude");
define("_WEBPHOTO_CAT_GMAP_ZOOM","GoogleMap Zoom");
define("_WEBPHOTO_CAT_GMAP_TYPE","Type GoogleMap");
define("_WEBPHOTO_CAT_PERM_READ","Lire la permission");
define("_WEBPHOTO_CAT_PERM_POST","Post Autorisation");
define("_WEBPHOTO_CAT_TEXT1","text1");
define("_WEBPHOTO_CAT_TEXT2","text2");
define("_WEBPHOTO_CAT_TEXT3","Text3");
define("_WEBPHOTO_CAT_TEXT4","Text4");
define("_WEBPHOTO_CAT_TEXT5","text5");
define("_WEBPHOTO_CAT_DESCRIPTION","Description");

// Tableau de vote
define("_WEBPHOTO_VOTE_TABLE","Vote Table");
define("_WEBPHOTO_VOTE_ID","Vote ID");
define("_WEBPHOTO_VOTE_TIME_CREATE","Créer l'heure");
define("_WEBPHOTO_VOTE_TIME_UPDATE","Mise à jour Time");
define("_WEBPHOTO_VOTE_PHOTO_ID","Photo ID");
define("_WEBPHOTO_VOTE_UID","User ID");
define("_WEBPHOTO_VOTE_RATING","Note");
define("_WEBPHOTO_VOTE_HOSTNAME","adresse IP");

// Google icône de table
define("_WEBPHOTO_GICON_TABLE","Google Icon Table");
define("_WEBPHOTO_GICON_ID","Icon ID");
define("_WEBPHOTO_GICON_TIME_CREATE","Créer l'heure");
define("_WEBPHOTO_GICON_TIME_UPDATE","Mise à jour Time");
define("_WEBPHOTO_GICON_TITLE","Icon Titre");
define("_WEBPHOTO_GICON_IMAGE_PATH","Image Path");
define("_WEBPHOTO_GICON_IMAGE_NAME","Nom de l'image");
define("_WEBPHOTO_GICON_IMAGE_EXT","Image Extntion");
define("_WEBPHOTO_GICON_SHADOW_PATH","Shadow Path");
define("_WEBPHOTO_GICON_SHADOW_NAME","Shadow Name");
define("_WEBPHOTO_GICON_SHADOW_EXT","Shadow Extension");
define("_WEBPHOTO_GICON_IMAGE_WIDTH","Largeur de l'image");
define("_WEBPHOTO_GICON_IMAGE_HEIGHT","Hauteur de l'image");
define("_WEBPHOTO_GICON_SHADOW_WIDTH","Shadow Hauteur");
define("_WEBPHOTO_GICON_SHADOW_HEIGHT","Shadow Size Y");
define("_WEBPHOTO_GICON_ANCHOR_X","X Taille Anchor");
define("_WEBPHOTO_GICON_ANCHOR_Y","Y Taille Anchor");
define("_WEBPHOTO_GICON_INFO_X","Taille WindowInfo X");
define("_WEBPHOTO_GICON_INFO_Y","Taille WindowInfo Y");

// Type mime table
define("_WEBPHOTO_MIME_TABLE","Type MIME Table");
define("_WEBPHOTO_MIME_ID","MIME ID");
define("_WEBPHOTO_MIME_TIME_CREATE","Créer l'heure");
define("_WEBPHOTO_MIME_TIME_UPDATE","Mise à jour Time");
define("_WEBPHOTO_MIME_EXT","Extension");
define("_WEBPHOTO_MIME_MEDIUM","Meduim Type");
define("_WEBPHOTO_MIME_TYPE","Type MIME");
define("_WEBPHOTO_MIME_NAME","MIME Name");
define("_WEBPHOTO_MIME_PERMS","autorisation");

// Ajouté dans v0.20
define("_WEBPHOTO_MIME_FFMPEG","ffmpeg option");

// Tag table
define("_WEBPHOTO_TAG_TABLE","Table Tag");
define("_WEBPHOTO_TAG_ID","ID Tag");
define("_WEBPHOTO_TAG_TIME_CREATE","Créer l'heure");
define("_WEBPHOTO_TAG_TIME_UPDATE","Mise à jour Time");
define("_WEBPHOTO_TAG_NAME","Tag Name");

// Photo-de-table tag
define("_WEBPHOTO_P2T_TABLE","Photo Tag liaison Table");
define("_WEBPHOTO_P2T_ID","Photo-Tag ID");
define("_WEBPHOTO_P2T_TIME_CREATE","Créer l'heure");
define("_WEBPHOTO_P2T_TIME_UPDATE","Mise à jour Time");
define("_WEBPHOTO_P2T_PHOTO_ID","Photo ID");
define("_WEBPHOTO_P2T_TAG_ID","ID Tag");
define("_WEBPHOTO_P2T_UID","User ID");

// Synonyme table
define("_WEBPHOTO_SYNO_TABLE","Synonyme Table");
define("_WEBPHOTO_SYNO_ID","Synonyme ID");
define("_WEBPHOTO_SYNO_TIME_CREATE","Créer l'heure");
define("_WEBPHOTO_SYNO_TIME_UPDATE","Mise à jour Time");
define("_WEBPHOTO_SYNO_WEIGHT","Poids");
define("_WEBPHOTO_SYNO_KEY","clés");
define("_WEBPHOTO_SYNO_VALUE","Synonyme");


//------------------------------------------------ ---------
// Titre
//------------------------------------------------ ---------
define("_WEBPHOTO_TITLE_LATEST","Dernière");
define("_WEBPHOTO_TITLE_SUBMIT","Valider");
define("_WEBPHOTO_TITLE_POPULAR","populaire");
define("_WEBPHOTO_TITLE_HIGHRATE","TopRated");
define("_WEBPHOTO_TITLE_MYPHOTO","Mes photos");
define("_WEBPHOTO_TITLE_RANDOM","Photo aléatoire");
define("_WEBPHOTO_TITLE_HELP","Aide");
define("_WEBPHOTO_TITLE_CATEGORY_LIST","Liste de catégories");
define("_WEBPHOTO_TITLE_TAG_LIST","Tag List");
define("_WEBPHOTO_TITLE_TAGS","Tag");
define("_WEBPHOTO_TITLE_USER_LIST","Submitter List");
define("_WEBPHOTO_TITLE_DATE_LIST","Photo Date list");
define("_WEBPHOTO_TITLE_PLACE_LIST","Photo Place List");
define("_WEBPHOTO_TITLE_RSS","RSS");

define("_WEBPHOTO_VIEWTYPE_LIST","type de liste");
define("_WEBPHOTO_VIEWTYPE_TABLE","Table Type");

define("_WEBPHOTO_CATLIST_ON","Voir la catégorie");
define("_WEBPHOTO_CATLIST_OFF","Masquer la catégorie");
define("_WEBPHOTO_TAGCLOUD_ON","Afficher Tag Cloud");
define("_WEBPHOTO_TAGCLOUD_OFF","Hide Tag Cloud");
define("_WEBPHOTO_GMAP_ON","Voir la GoogleMap");
define("_WEBPHOTO_GMAP_OFF","Masquer GoogleMap");

define("_WEBPHOTO_NO_TAG","Not Set Tag");

//------------------------------------------------ ---------
// Google maps
//------------------------------------------------ ---------
define("_WEBPHOTO_TITLE_GET_LOCATION","Réglage de la latitude et la longitude");
define("_WEBPHOTO_GMAP_DESC","Afficher l'image du pouce, alors cliquez sur le marqueur dans GoogleMaps");
define("_WEBPHOTO_GMAP_ICON","GoogleMap Icons");
define("_WEBPHOTO_GMAP_LATITUDE","GoogleMap Latitude");
define("_WEBPHOTO_GMAP_LONGITUDE","GoogleMap Longitude");
define("_WEBPHOTO_GMAP_ZOOM","GoogleMap Zoom");
define("_WEBPHOTO_GMAP_ADDRESS","Address");
define("_WEBPHOTO_GMAP_GET_LOCATION","Get latitude et longitude");
define("_WEBPHOTO_GMAP_SEARCH_LIST","recherche liste");
define("_WEBPHOTO_GMAP_CURRENT_LOCATION","Situation actuelle");
define("_WEBPHOTO_GMAP_CURRENT_ADDRESS","Adresse actuelle");
define("_WEBPHOTO_GMAP_NO_MATCH_PLACE","Il n'y a pas de place matched");
define("_WEBPHOTO_GMAP_NOT_COMPATIBLE","Ne pas afficher les Google Maps dans votre navigateur web");
define("_WEBPHOTO_JS_INVALID","Ne pas utiliser JavaScript dans votre navigateur web");
define("_WEBPHOTO_IFRAME_NOT_SUPPORT","Ne pas utiliser la balise iframe dans votre navigateur web");

//------------------------------------------------ ---------
// Recherche
//------------------------------------------------ ---------
define("_WEBPHOTO_SR_SEARCH","Recherche");

//------------------------------------------------ ---------
// Popbox
//------------------------------------------------ ---------
define("_WEBPHOTO_POPBOX_REVERT","Cliquez sur l'image pour rétrécir.");

//------------------------------------------------ ---------
// Tag
//------------------------------------------------ ---------
define("_WEBPHOTO_TAGS","tags");
define("_WEBPHOTO_EDIT_TAG","Edit tags");
define("_WEBPHOTO_DSC_TAG_DIVID","diviser par une virgule (,) si vous souhaitez utiliser deux ou plusieurs tags");
define("_WEBPHOTO_DSC_TAG_EDITABLE","Vous pouvez modifier uniquement les balises qui vous avez publié");

//------------------------------------------------ ---------
// Présenter le formulaire
//------------------------------------------------ ---------
define("_WEBPHOTO_CAP_ALLOWED_EXTS","Extensions autorisées");
define("_WEBPHOTO_CAP_PHOTO_SELECT","Sélectionnez l'image");
define("_WEBPHOTO_CAP_THUMB_SELECT","Sélectionnez l'onglet image");
define("_WEBPHOTO_DSC_THUMB_SELECT","créer à partir de l'image, quand ce n'est pas de sélectionner");
define("_WEBPHOTO_DSC_SET_DATETIME","Set photo datetime");

// define ( "_WEBPHOTO_DSC_SET_TIME_UPDATE", "Ensemble de mise à jour de temps");

define("_WEBPHOTO_DSC_PIXCEL_RESIZE","Redimensionner automatiquement si plus grande que cette taille");
define("_WEBPHOTO_DSC_PIXCEL_REJECT","Impossible de télécharger, si plus grande que cette taille");
define("_WEBPHOTO_BUTTON_CLEAR","Effacer");
define("_WEBPHOTO_SUBMIT_RESIZED","Resized, parce que la photo est trop grand");

// Upload d'erreur PHP
// Http://www.php.net/manual/en/features.file-upload.errors.php
define("_WEBPHOTO_PHP_UPLOAD_ERR_OK","Il n'y a pas d'erreur, le fichier téléchargé avec succès.");
define("_WEBPHOTO_PHP_UPLOAD_ERR_INI_SIZE","Le fichier dépasse la upload_max_filesize.");
define("_WEBPHOTO_PHP_UPLOAD_ERR_FORM_SIZE","Le fichier% s est supérieur.");
define("_WEBPHOTO_PHP_UPLOAD_ERR_PARTIAL","Le fichier n'a été que partiellement téléchargé.");
define("_WEBPHOTO_PHP_UPLOAD_ERR_NO_FILE","Aucun fichier a été téléchargé.");
define("_WEBPHOTO_PHP_UPLOAD_ERR_NO_TMP_DIR","Missing un dossier temporaire.");
define("_WEBPHOTO_PHP_UPLOAD_ERR_CANT_WRITE","Impossible d'écrire le fichier sur le disque.");
define("_WEBPHOTO_PHP_UPLOAD_ERR_EXTENSION","File upload par arrêté d'extension.");

// Upload d'erreur
define("_WEBPHOTO_UPLOADER_ERR_NOT_FOUND","fichier non trouvé");
define("_WEBPHOTO_UPLOADER_ERR_INVALID_FILE_SIZE","Taille de fichier non valide");
define("_WEBPHOTO_UPLOADER_ERR_EMPTY_FILE_NAME","Nom du fichier est vide");
define("_WEBPHOTO_UPLOADER_ERR_NO_FILE","Aucun fichier téléchargé");
define("_WEBPHOTO_UPLOADER_ERR_NOT_SET_DIR","Upload répertoire non défini");
define("_WEBPHOTO_UPLOADER_ERR_NOT_ALLOWED_EXT","Extension pas autorisé");
define("_WEBPHOTO_UPLOADER_ERR_PHP_OCCURED","Une erreur s'est produite: Erreur #");
define("_WEBPHOTO_UPLOADER_ERR_NOT_OPEN_DIR","Failed opening répertoire:");
define("_WEBPHOTO_UPLOADER_ERR_NO_PERM_DIR","Failed opening répertoire avec la permission d'écriture:");
define("_WEBPHOTO_UPLOADER_ERR_NOT_ALLOWED_MIME","type MIME pas permis:");
define("_WEBPHOTO_UPLOADER_ERR_LARGE_FILE_SIZE","La taille du fichier trop grand:");
define("_WEBPHOTO_UPLOADER_ERR_LARGE_WIDTH","largeur de dossier doit être plus petit que");
define("_WEBPHOTO_UPLOADER_ERR_LARGE_HEIGHT","File la hauteur doit être inférieure à");
define("_WEBPHOTO_UPLOADER_ERR_UPLOAD","Impossible de télécharger le fichier:");

//------------------------------------------------ ---------
// Help
//------------------------------------------------ ---------
define("_WEBPHOTO_HELP_DSC","Ceci est la description de l'application qui fonctionne sur votre PC");

define("_WEBPHOTO_HELP_PICLENS_TITLE","PicLens");
define("_WEBPHOTO_HELP_PICLENS_DSC","
PicLens est l'addon qui Cooliris Inc prévoit FireFox <br />
Il s'agit de la visionneuse de photos dans le site Web <br /> <br />
<b> Définition </ b> <br />
(1) Téléchargez FireFox <br />
<a href='http://www.mozilla-japan.org/products/firefox/' target='_blank'>
http://www.mozilla-japan.org/products/firefox/
</ a> <br /> <br />
(2) Télécharger PicLens addon <br />
<a href='http://www.piclens.com/' target='_blank'>
http://www.piclens.com/
</ a> <br /> <br />
(3) Voir webphoto dans webphoto <br />
http://THIS-SITE/modules/webphoto/ <br /> <br />
(4) Cliquez sur l'icône bleue en haut à droite de Firefox <br />
Vous ne pouvez pas utiliser PicLens, lorsque l'icône est noir <br /> ");

//
// Lignes de mannequin, ajuste le numéro de ligne pour le Japonais lang fichier.
// 

define("_WEBPHOTO_HELP_MEDIARSSSLIDESHOW_TITLE","Media RSS Slide Show");
define("_WEBPHOTO_HELP_MEDIARSSSLIDESHOW_DSC","
'Media RSS Slide Show' est le gadget Google Desktop <br />
Cela montre des photos à partir de l'Internet avec le diaporama <br /> <br />
<b> Définition </ b> <br />
(1) Télécharger le 'Google Desktop' <br />
<a href='http://desktop.google.co.jp/' target='_blank'>
http://desktop.google.co.jp/
</ a> <br /> <br />
(2) Télécharger 'Media RSS Slide Show' gadget <br />
<a href='http://desktop.google.com/plugins/i/mediarssslideshow.html' target='_blank'>
http://desktop.google.com/plugins/i/mediarssslideshow.html
</ a> <br /> <br />
(3) Modifier 'URL de MediaRSS' dans le texte suivant, en utilisant l'option du gadget <br /> ");

//------------------------------------------------ ---------
// Autres
//------------------------------------------------ ---------
define("_WEBPHOTO_RANDOM_MORE","Plus de photos au hasard");
define("_WEBPHOTO_USAGE_PHOTO","la grande photo Popup, lorsque cliquez sur la vignette");
define("_WEBPHOTO_USAGE_TITLE","Allez à la page de photos, cliquez sur la photo lorsque le titre");
define("_WEBPHOTO_DATE_NOT_SET","Non défini Photo Date");
define("_WEBPHOTO_PLACE_NOT_SET","Not Set Photo Place");
define("_WEBPHOTO_GOTO_ADMIN","Goto Admin Control");

//------------------------------------------------ ---------
// Search for Japanese
//------------------------------------------------ ---------
define("_WEBPHOTO_SR_CANDICATE","Candicate pour la recherche");
define("_WEBPHOTO_SR_ZENKAKU","Zenkaku");
define("_WEBPHOTO_SR_HANKAKU","Hanhaku");

define("_WEBPHOTO_JA_KUTEN","");
define("_WEBPHOTO_JA_DOKUTEN","");
define("_WEBPHOTO_JA_PERIOD","");
define("_WEBPHOTO_JA_COMMA","");

//------------------------------------------------ ---------
// V0.20
//------------------------------------------------ ---------
define("_WEBPHOTO_TITLE_VIDEO_THUMB_SEL","Select aperçu de la vidéo");
define("_WEBPHOTO_TITLE_VIDEO_REDO","Re-créer des vignettes à partir de Flash et vidéo envoyée");
define("_WEBPHOTO_CAP_REDO_THUMB","Créer Thumbnail");
define("_WEBPHOTO_CAP_REDO_FLASH","Cretae Flash Video");
define("_WEBPHOTO_ERR_VIDEO_FLASH","Impossible de créer des vidéos Flash");
define("_WEBPHOTO_ERR_VIDEO_THUMB","Substituted avec l'icône, car ne peut pas créer de vignette de la vidéo");
define("_WEBPHOTO_BUTTON_SELECT","Select");

define("_WEBPHOTO_DSC_DOWNLOAD_PLAY","après le téléchargement Play");
define("_WEBPHOTO_ICON_VIDEO","Vidéo");
define("_WEBPHOTO_HOUR","heure");
define("_WEBPHOTO_MINUTE","min");
define("_WEBPHOTO_SECOND","sec");

//------------------------------------------------ ---------
// V0.30
//------------------------------------------------ ---------
// User table
define("_WEBPHOTO_USER_TABLE","User Aux Table");
define("_WEBPHOTO_USER_ID","Aux User ID");
define("_WEBPHOTO_USER_TIME_CREATE","Créer l'heure");
define("_WEBPHOTO_USER_TIME_UPDATE","Mise à jour Time");
define("_WEBPHOTO_USER_UID","Uesr ID");
define("_WEBPHOTO_USER_CAT_ID","ID de catégorie");
define("_WEBPHOTO_USER_EMAIL","Email Address");
define("_WEBPHOTO_USER_TEXT1","text1");
define("_WEBPHOTO_USER_TEXT2","text2");
define("_WEBPHOTO_USER_TEXT3","Text3");
define("_WEBPHOTO_USER_TEXT4","Text4");
define("_WEBPHOTO_USER_TEXT5","text5");

// Maillog
define("_WEBPHOTO_MAILLOG_TABLE","maillog Table");
define("_WEBPHOTO_MAILLOG_ID","maillog ID");
define("_WEBPHOTO_MAILLOG_TIME_CREATE","Créer l'heure");
define("_WEBPHOTO_MAILLOG_TIME_UPDATE","Mise à jour Time");
define("_WEBPHOTO_MAILLOG_PHOTO_IDS","Photo identifiants");
define("_WEBPHOTO_MAILLOG_STATUS","Status");
define("_WEBPHOTO_MAILLOG_FROM","De Mail Address");
define("_WEBPHOTO_MAILLOG_SUBJECT","Objet");
define("_WEBPHOTO_MAILLOG_BODY","Body");
define("_WEBPHOTO_MAILLOG_FILE","Nom de fichier");
define("_WEBPHOTO_MAILLOG_ATTACH","Attach Files");
define("_WEBPHOTO_MAILLOG_COMMENT","commentaire");

// Mail enregistrer
define("_WEBPHOTO_TITLE_MAIL_REGISTER","Mail Registre");
define("_WEBPHOTO_MAIL_HELP","S'il vous plaît se référer' Aide 'pour l'utilisation");
define("_WEBPHOTO_CAT_USER","User Name");
define("_WEBPHOTO_BUTTON_REGISTER","REGISTER");
define("_WEBPHOTO_NOMATCH_USER","Il n'y a pas d'utilisateur");
define("_WEBPHOTO_ERR_MAIL_EMPTY","Vous devez entrer' Mail ");
define("_WEBPHOTO_ERR_MAIL_ILLEGAL","Illegal format de mail");

// Récupérer mail
define("_WEBPHOTO_TITLE_MAIL_RETRIEVE","Retrieve Mail");
define("_WEBPHOTO_DSC_MAIL_RETRIEVE","Récupérer les mails du serveur de mail");
define("_WEBPHOTO_BUTTON_RETRIEVE","Recherche");
define("_WEBPHOTO_SUBTITLE_MAIL_ACCESS","Accès au serveur de courrier");
define("_WEBPHOTO_SUBTITLE_MAIL_PARSE","Analyse du courrier reçu");
define("_WEBPHOTO_SUBTITLE_MAIL_PHOTO","la soumission ci-joint les photos de mails");
define("_WEBPHOTO_TEXT_MAIL_ACCESS_TIME","Dans la limitation de l'accès");
define("_WEBPHOTO_TEXT_MAIL_RETRY","Access 1 minute plus tard");
define("_WEBPHOTO_TEXT_MAIL_NOT_RETRIEVE","ne peut pas récupérer le courrier. <br /> probablement temporaire de communication. <br /> S'il vous plaît réessayer après un certain temps");
define("_WEBPHOTO_TEXT_MAIL_NO_NEW","Il n'y a pas de nouveaux messages");
define("_WEBPHOTO_TEXT_MAIL_RETRIEVED_FMT","%s Récupérée mails");
define("_WEBPHOTO_TEXT_MAIL_NO_VALID","Il n'y a pas de mail valide");
define("_WEBPHOTO_TEXT_MAIL_SUBMITED_FMT","%s Submited photos");
define("_WEBPHOTO_GOTO_INDEX","Aller à la page principale du module");

// I.php
define("_WEBPHOTO_TITLE_MAIL_POST","Post Mail");

// File
define("_WEBPHOTO_TITLE_SUBMIT_FILE","Ajouter une photo à partir d'un fichier");
define("_WEBPHOTO_CAP_FILE_SELECT","Select File");
define("_WEBPHOTO_ERR_EMPTY_FILE","Vous devez sélectionner le fichier");
define("_WEBPHOTO_ERR_EMPTY_CAT","Vous devez sélectionner la catégorie");
define("_WEBPHOTO_ERR_INVALID_CAT","non valide de catégorie");
define("_WEBPHOTO_ERR_CREATE_PHOTO","Ne peut créer photo");
define("_WEBPHOTO_ERR_CREATE_THUMB","Impossible de créer un pouce d'image");

// Help
define("_WEBPHOTO_HELP_MUST_LOGIN","S'il vous plaît vous connecter, si vous voulez le lire plus en détail");
define("_WEBPHOTO_HELP_NOT_PERM","Vous n'avez pas l'autorisation. S'il vous plaît contacter le webmaster");

define("_WEBPHOTO_HELP_MOBILE_TITLE","téléphone portable");
define("_WEBPHOTO_HELP_MOBILE_DSC","Vous pouvez regarder la photo et la vidéo dans le téléphone mobile <br/> de la taille de l'écran est de 240x320");
define("_WEBPHOTO_HELP_MOBILE_TEXT_FMT","
<b> Web Access </ b> <br />
<a href='{MODULE_URL}/i.php' target='_blank'> MODULE_URL () / i.php </ a> ");

define("_WEBPHOTO_HELP_MAIL_TITLE","Mobile Mail");
define("_WEBPHOTO_HELP_MAIL_DSC","Vous pouvez envoyer la photo et la vidéo par e-mail à partir du téléphone mobile");
define("_WEBPHOTO_HELP_MAIL_GUEST","Ceci est l'échantillon. Vous pouvez regarder la REAL mail, si vous avez la permission.");

define("_WEBPHOTO_HELP_FILE_TITLE","Post FTP");
define("_WEBPHOTO_HELP_FILE_DSC","Vous pouvez poster de grande taille de la photo et la vidéo, lorsque vous téléchargez le fichier par FTP");
define("_WEBPHOTO_HELP_FILE_TEXT_FMT","
<b> photo </ b> <br />
(1) Télécharger le fichier au serveur FTP <br />
(2) Cliquez sur <a href='{MODULE_URL}/index.php?fct=submit_file' target='_blank'> Ajouter une photo à partir d'un fichier </ a> <br />
(3) Sélectionnez le fichier téléchargé et post ");

// Check mail
// For Japanese
define("_WEBPHOTO_MAIL_DENY_TITLE_PREG","");
define("_WEBPHOTO_MAIL_AD_WORD_1","");
define("_WEBPHOTO_MAIL_AD_WORD_2","");

//------------------------------------------------ ---------
// V0.40
//------------------------------------------------ ---------
// Point de table
define("_WEBPHOTO_ITEM_TABLE","Point Table");
define("_WEBPHOTO_ITEM_ID","Point ID");
define("_WEBPHOTO_ITEM_TIME_CREATE","Créer l'heure");
define("_WEBPHOTO_ITEM_TIME_UPDATE","Mise à jour Time");
define("_WEBPHOTO_ITEM_CAT_ID","ID de catégorie");
define("_WEBPHOTO_ITEM_GICON_ID","Icon GoogleMap ID");
define("_WEBPHOTO_ITEM_UID","User ID");
define("_WEBPHOTO_ITEM_KIND","Type de fichier");
define("_WEBPHOTO_ITEM_EXT","Extension du fichier");
define("_WEBPHOTO_ITEM_DATETIME","Photo Datetime");
define("_WEBPHOTO_ITEM_TITLE","Titre de photos");
define("_WEBPHOTO_ITEM_PLACE","Place");
define("_WEBPHOTO_ITEM_EQUIPMENT","Equipement");
define("_WEBPHOTO_ITEM_GMAP_LATITUDE","GoogleMap Latitude");
define("_WEBPHOTO_ITEM_GMAP_LONGITUDE","GoogleMap Longitude");
define("_WEBPHOTO_ITEM_GMAP_ZOOM","GoogleMap Zoom");
define("_WEBPHOTO_ITEM_GMAP_TYPE","Type GoogleMap");
define("_WEBPHOTO_ITEM_PERM_READ","Lire la permission");
define("_WEBPHOTO_ITEM_STATUS","Status");
define("_WEBPHOTO_ITEM_HITS","Hits");
define("_WEBPHOTO_ITEM_RATING","Note");
define("_WEBPHOTO_ITEM_VOTES","Votes");
define("_WEBPHOTO_ITEM_DESCRIPTION","Photo Description");
define("_WEBPHOTO_ITEM_EXIF","Exif Information");
define("_WEBPHOTO_ITEM_SEARCH","Recherche");
define("_WEBPHOTO_ITEM_COMMENTS","Commentaires");
define("_WEBPHOTO_ITEM_FILE_ID_1","Fichier ID: Content");
define("_WEBPHOTO_ITEM_FILE_ID_2","Fichier ID: Thumbnail");
define("_WEBPHOTO_ITEM_FILE_ID_3","Fichier ID: Moyen");
define("_WEBPHOTO_ITEM_FILE_ID_4","Fichier ID: Flash Video");
define("_WEBPHOTO_ITEM_FILE_ID_5","Fichier ID: Docomo Video");
define("_WEBPHOTO_ITEM_FILE_ID_6","file6");
define("_WEBPHOTO_ITEM_FILE_ID_7","file7");
define("_WEBPHOTO_ITEM_FILE_ID_8","file8");
define("_WEBPHOTO_ITEM_FILE_ID_9","file9");
define("_WEBPHOTO_ITEM_FILE_ID_10","file10");
define("_WEBPHOTO_ITEM_TEXT_1","text1");
define("_WEBPHOTO_ITEM_TEXT_2","text2");
define("_WEBPHOTO_ITEM_TEXT_3","Text3");
define("_WEBPHOTO_ITEM_TEXT_4","Text4");
define("_WEBPHOTO_ITEM_TEXT_5","text5");
define("_WEBPHOTO_ITEM_TEXT_6","text6");
define("_WEBPHOTO_ITEM_TEXT_7","text7");
define("_WEBPHOTO_ITEM_TEXT_8","text8");
define("_WEBPHOTO_ITEM_TEXT_9","text9");
define("_WEBPHOTO_ITEM_TEXT_10","text10");

// Table de fichiers
define("_WEBPHOTO_FILE_TABLE","File Table");
define("_WEBPHOTO_FILE_ID","Fichier ID");
define("_WEBPHOTO_FILE_TIME_CREATE","Créer l'heure");
define("_WEBPHOTO_FILE_TIME_UPDATE","Mise à jour Time");
define("_WEBPHOTO_FILE_ITEM_ID","Point ID");
define("_WEBPHOTO_FILE_KIND","Type de fichier");
define("_WEBPHOTO_FILE_URL","URL");
define("_WEBPHOTO_FILE_PATH","Chemin du fichier");
define("_WEBPHOTO_FILE_NAME","Nom de fichier");
define("_WEBPHOTO_FILE_EXT","Extension du fichier");
define("_WEBPHOTO_FILE_MIME","MIME type");
define("_WEBPHOTO_FILE_MEDIUM","Medium Type");
define("_WEBPHOTO_FILE_SIZE","Taille du fichier");
define("_WEBPHOTO_FILE_WIDTH","Largeur de l'image");
define("_WEBPHOTO_FILE_HEIGHT","Hauteur de l'image");
define("_WEBPHOTO_FILE_DURATION","Durée de la vidéo Time");

// Type de fichier (pour les admin checktables)
define("_WEBPHOTO_FILE_KIND_1","Contenu");
define("_WEBPHOTO_FILE_KIND_2","vignette");
define("_WEBPHOTO_FILE_KIND_3","Moyen");
define("_WEBPHOTO_FILE_KIND_4","Flash Video");
define("_WEBPHOTO_FILE_KIND_5","Docomo Video");
define("_WEBPHOTO_FILE_KIND_6","file6");
define("_WEBPHOTO_FILE_KIND_7","file7");
define( "_WEBPHOTO_FILE_KIND_8","file8");
define("_WEBPHOTO_FILE_KIND_9","file9");
define("_WEBPHOTO_FILE_KIND_10","file10");

// Index
define("_WEBPHOTO_MOBILE_MAILTO","Envoyer l'URL à la téléphonie mobile");

// I.php
define("_WEBPHOTO_TITLE_MAIL_JUDGE","juge le transporteur mobile");
define("_WEBPHOTO_MAIL_MODEL","mobile");
define("_WEBPHOTO_MAIL_BROWSER","Web Browser");
define("_WEBPHOTO_MAIL_NOT_JUDGE","Je ne peux pas juger de l'opérateur mobile");
define("_WEBPHOTO_MAIL_TO_WEBMASTER","Mail de webmaster");

// Help
define("_WEBPHOTO_HELP_MAIL_POST_FMT","
Préparer <b> </ b> <br />
Inscrivez votre adresse e-mail de téléphone mobile <br />
<a href='{MODULE_URL}/index.php?fct=mail_register' target='_blank'> Inscrivez-Mail Addrtess </ a> <br /> <br />
<b> photo </ b> <br />
Envoyer un mail à l'adresse de la fixation des fllowing fichier photo. <br />
MAIL_ADDR <a href='mailto:{MAIL_ADDR}'> () </ a> () MAIL_GUEST <br /> <br />
<b> Rotation de photo </ b> <br />
Vous pouvez tourner la photo de droite ou de gauche, puisque vous entrez le terme de 'Sujet' comme suit <br />
  @ R: tourner à droite <br />
  L @: tourner à gauche <br /> <br /> ");
define("_WEBPHOTO_HELP_MAIL_SUBTITLE_RETRIEVE","mail <b> récupérer et de présenter la photo </ b> <br />");
define("_WEBPHOTO_HELP_MAIL_RETRIEVE_FMT","
Cliquez <a href='{MODULE_URL}/i.php?op=post' target='_blank'> Post Mail </ a>, après avoir envoyé un courriel quelques secondes. <br />
Webphoto récupérer le mail qui vous a envoyé, présenter et de montrer la photo ci-jointe <br /> ");
define("_WEBPHOTO_HELP_MAIL_RETRIEVE_TEXT","Webphoto récupérer le mail qui vous a envoyé, présenter et de montrer la photo ci-jointe <br />");
define("_WEBPHOTO_HELP_MAIL_RETRIEVE_AUTO_FMT","
Le courrier est automatiquement% s secondes plus tard, lorsque vous envoyez un mail. <br />
S'il vous plaît, cliquez <a href='{MODULE_URL}/i.php?op=post' target='_blank'> Post Mail </ a>, si elle n'est pas présentée. <br /> ");

//------------------------------------------------ ---------
// V0.50
//------------------------------------------------ ---------
// Point de table
define("_WEBPHOTO_ITEM_TIME_PUBLISH","Heure de publication");
define("_WEBPHOTO_ITEM_TIME_EXPIRE","Expired Time");
define("_WEBPHOTO_ITEM_PLAYER_ID","Player ID");
define("_WEBPHOTO_ITEM_FLASHVAR_ID","FlashVar ID");
define("_WEBPHOTO_ITEM_DURATION","Durée de la vidéo Time");
define("_WEBPHOTO_ITEM_DISPLAYTYPE","Type d'affichage");
define("_WEBPHOTO_ITEM_ONCLICK","Action lorsque click thumbnail");
define("_WEBPHOTO_ITEM_SITEURL","Site Web");
define("_WEBPHOTO_ITEM_ARTIST","Artiste");
define("_WEBPHOTO_ITEM_ALBUM","Album");
define("_WEBPHOTO_ITEM_LABEL","Label");
define("_WEBPHOTO_ITEM_VIEWS","Vues");
define("_WEBPHOTO_ITEM_PERM_DOWN","Permission Télécharger");
define("_WEBPHOTO_ITEM_EMBED_TYPE","Type Plugin");
define("_WEBPHOTO_ITEM_EMBED_SRC","Plug-in Web Param");
define("_WEBPHOTO_ITEM_EXTERNAL_URL","External URL");
define("_WEBPHOTO_ITEM_EXTERNAL_THUMB","External Thumbnail URL");
define("_WEBPHOTO_ITEM_PLAYLIST_TYPE","Type de lecture");
define("_WEBPHOTO_ITEM_PLAYLIST_FEED","Playlist Feed URL");
define("_WEBPHOTO_ITEM_PLAYLIST_DIR","Répertoire Playlist");
define("_WEBPHOTO_ITEM_PLAYLIST_CACHE","Playlist Cache Name");
define("_WEBPHOTO_ITEM_PLAYLIST_TIME","Playlist Cache Time");
define("_WEBPHOTO_ITEM_CHAIN","chaîne");
define("_WEBPHOTO_ITEM_SHOWINFO","Afficher l'information");

// Lecteur de table
define("_WEBPHOTO_PLAYER_TABLE", "Table Player");
define("_WEBPHOTO_PLAYER_ID", "Player ID");
define("_WEBPHOTO_PLAYER_TIME_CREATE", "Créer l'heure");
define("_WEBPHOTO_PLAYER_TIME_UPDATE", "Mise à jour Time");
define("_WEBPHOTO_PLAYER_TITLE", "Titre Player");
define("_WEBPHOTO_PLAYER_STYLE", "Style Option");
define("_WEBPHOTO_PLAYER_WIDTH", "Largeur Player");
define("_WEBPHOTO_PLAYER_HEIGHT", "Hauteur Player");
define("_WEBPHOTO_PLAYER_DISPLAYWIDTH", "Display Width");
define("_WEBPHOTO_PLAYER_DISPLAYHEIGHT", "Hauteur d'affichage");
define("_WEBPHOTO_PLAYER_SCREENCOLOR", "Couleur d'écran");
define("_WEBPHOTO_PLAYER_BACKCOLOR", "Back Color");
define("_WEBPHOTO_PLAYER_FRONTCOLOR", "Front Color");
define("_WEBPHOTO_PLAYER_LIGHTCOLOR", "Light Color");

// FlashVar table
define("_WEBPHOTO_FLASHVAR_TABLE","FlashVra Table");
define("_WEBPHOTO_FLASHVAR_ID","FlashVar ID");
define("_WEBPHOTO_FLASHVAR_TIME_CREATE","Créer l'heure");
define("_WEBPHOTO_FLASHVAR_TIME_UPDATE","Update Time" ) ;
define("_WEBPHOTO_FLASHVAR_ITEM_ID","Item ID");
define("_WEBPHOTO_FLASHVAR_WIDTH","Player Width");
define("_WEBPHOTO_FLASHVAR_HEIGHT","Player Height");
define("_WEBPHOTO_FLASHVAR_DISPLAYWIDTH","Display Width");
define("_WEBPHOTO_FLASHVAR_DISPLAYHEIGHT","Display Height");
define("_WEBPHOTO_FLASHVAR_IMAGE_SHOW","Show Image");
define("_WEBPHOTO_FLASHVAR_SEARCHBAR","Searchbar");
define("_WEBPHOTO_FLASHVAR_SHOWEQ","Show Equalizer");
define("_WEBPHOTO_FLASHVAR_SHOWICONS","Activity Icons");
define("_WEBPHOTO_FLASHVAR_SHOWNAVIGATION","Show Navigation");
define("_WEBPHOTO_FLASHVAR_SHOWSTOP","Show Stop");
define("_WEBPHOTO_FLASHVAR_SHOWDIGITS","Show Digits");
define("_WEBPHOTO_FLASHVAR_SHOWDOWNLOAD","Show Download");
define("_WEBPHOTO_FLASHVAR_USEFULLSCREEN","Full Screen Button");
define("_WEBPHOTO_FLASHVAR_AUTOSCROLL","Scroll Bar Off");
define("_WEBPHOTO_FLASHVAR_THUMBSINPLAYLIST","Thumbnails");
define("_WEBPHOTO_FLASHVAR_AUTOSTART","Auto Start");
define("_WEBPHOTO_FLASHVAR_REPEAT","Repeat");
define("_WEBPHOTO_FLASHVAR_SHUFFLE","Shuffle");
define("_WEBPHOTO_FLASHVAR_SMOOTHING","Smoothing");
define("_WEBPHOTO_FLASHVAR_ENABLEJS","Enable Javascript");
define("_WEBPHOTO_FLASHVAR_LINKFROMDISPLAY","Link from Display");
define("_WEBPHOTO_FLASHVAR_LINK_TYPE","Screen Hyperlink");
define("_WEBPHOTO_FLASHVAR_BUFFERLENGTH","Bufferlength");
define("_WEBPHOTO_FLASHVAR_ROTATETIME","Image Rotation Time");
define("_WEBPHOTO_FLASHVAR_VOLUME","volume");
define("_WEBPHOTO_FLASHVAR_LINKTARGET","Link Target");
define("_WEBPHOTO_FLASHVAR_OVERSTRETCH","Stretch Image/Video");
define("_WEBPHOTO_FLASHVAR_TRANSITION","Slide Show Transition");
define("_WEBPHOTO_FLASHVAR_SCREENCOLOR","Screen Color");
define("_WEBPHOTO_FLASHVAR_BACKCOLOR","Back Color");
define("_WEBPHOTO_FLASHVAR_FRONTCOLOR","Front Color");
define("_WEBPHOTO_FLASHVAR_LIGHTCOLOR","Light Color");
define("_WEBPHOTO_FLASHVAR_TYPE","Type");
define("_WEBPHOTO_FLASHVAR_FILE","Media File");
define("_WEBPHOTO_FLASHVAR_IMAGE","Preview Image");
define("_WEBPHOTO_FLASHVAR_LOGO","Player Logo Image");
define("_WEBPHOTO_FLASHVAR_LINK","Screen Hyperlink");
define("_WEBPHOTO_FLASHVAR_AUDIO","Audio Program");
define("_WEBPHOTO_FLASHVAR_CAPTIONS","Captions URL");
define("_WEBPHOTO_FLASHVAR_FALLBACK","Fallback URL");
define("_WEBPHOTO_FLASHVAR_CALLBACK","Callback URL");
define("_WEBPHOTO_FLASHVAR_JAVASCRIPTID","JavaScript ID");
define("_WEBPHOTO_FLASHVAR_RECOMMENDATIONS","Recommendations");
define("_WEBPHOTO_FLASHVAR_STREAMSCRIPT","Stream Script URL");
define("_WEBPHOTO_FLASHVAR_SEARCHLINK","Search Link");

// log file
define("_WEBPHOTO_LOGFILE_LINE","Line");
define("_WEBPHOTO_LOGFILE_DATE","Date");
define("_WEBPHOTO_LOGFILE_REFERER","Referer");
define("_WEBPHOTO_LOGFILE_IP","IP Address");
define("_WEBPHOTO_LOGFILE_STATE","State");
define("_WEBPHOTO_LOGFILE_ID","ID");
define("_WEBPHOTO_LOGFILE_TITLE","Title");
define("_WEBPHOTO_LOGFILE_FILE","File");
define("_WEBPHOTO_LOGFILE_DURATION","Duration");

// item option
define("_WEBPHOTO_ITEM_KIND_UNDEFINED","Undefined");
define("_WEBPHOTO_ITEM_KIND_NONE","No Media");
define("_WEBPHOTO_ITEM_KIND_GENERAL","General");
define("_WEBPHOTO_ITEM_KIND_IMAGE","Image (jpg,gif,png)");
define("_WEBPHOTO_ITEM_KIND_VIDEO","Video (wmv,mov,flv...");
define("_WEBPHOTO_ITEM_KIND_AUDIO","Audio (mp3...)");
define("_WEBPHOTO_ITEM_KIND_EMBED","Embed Plugin");
define("_WEBPHOTO_ITEM_KIND_EXTERNAL_GENERAL","External General");
define("_WEBPHOTO_ITEM_KIND_EXTERNAL_IMAGE","External Image");
define("_WEBPHOTO_ITEM_KIND_PLAYLIST_FEED","PlayList Web Feed");
define("_WEBPHOTO_ITEM_KIND_PLAYLIST_DIR", "PlayList Media directory");

define("_WEBPHOTO_ITEM_DISPLAYTYPE_GENERAL","General");
define("_WEBPHOTO_ITEM_DISPLAYTYPE_IMAGE","Image (jpg,gif,png)");
define("_WEBPHOTO_ITEM_DISPLAYTYPE_EMBED","Embed Plugin");
define("_WEBPHOTO_ITEM_DISPLAYTYPE_SWFOBJECT","FlashPlayer (swf)");
define("_WEBPHOTO_ITEM_DISPLAYTYPE_MEDIAPLAYER","MediaPlayer (jpg,gif,png,flv,mp3)");
define("_WEBPHOTO_ITEM_DISPLAYTYPE_IMAGEROTATOR","ImageRotator (jpg,gif,png)");

define("_WEBPHOTO_ITEM_ONCLICK_PAGE","Detail Page");
define("_WEBPHOTO_ITEM_ONCLICK_DIRECT","Direct Link");
define("_WEBPHOTO_ITEM_ONCLICK_POPUP","Image Popup");

define("_WEBPHOTO_ITEM_PLAYLIST_TYPE_DSC","What is the media file type?");
define("_WEBPHOTO_ITEM_PLAYLIST_TYPE_NONE","None");
define("_WEBPHOTO_ITEM_PLAYLIST_TYPE_IMAGE","Image (jpg,gif,png)");
define("_WEBPHOTO_ITEM_PLAYLIST_TYPE_AUDIO","Audio (mp3)");
define("_WEBPHOTO_ITEM_PLAYLIST_TYPE_VIDEO","Video (flv)");
define("_WEBPHOTO_ITEM_PLAYLIST_TYPE_FLASH","Flash (swf)");

define("_WEBPHOTO_ITEM_SHOWINFO_DESCRIPTION","Description");
define("_WEBPHOTO_ITEM_SHOWINFO_LOGOIMAGE","Thumbnail");
define("_WEBPHOTO_ITEM_SHOWINFO_CREDITS","Credits");
define("_WEBPHOTO_ITEM_SHOWINFO_STATISTICS","Statistics");
define("_WEBPHOTO_ITEM_SHOWINFO_SUBMITTER","Submitter");
define("_WEBPHOTO_ITEM_SHOWINFO_POPUP","PopUp");
define("_WEBPHOTO_ITEM_SHOWINFO_TAGS","Tags");
define("_WEBPHOTO_ITEM_SHOWINFO_DOWNLOAD","Download");
define("_WEBPHOTO_ITEM_SHOWINFO_WEBSITE","Site");
define("_WEBPHOTO_ITEM_SHOWINFO_WEBFEED","Feed");

define("_WEBPHOTO_ITEM_STATUS_WAITING","Waiting Approval");
define("_WEBPHOTO_ITEM_STATUS_APPROVED","Appoved");
define("_WEBPHOTO_ITEM_STATUS_UPDATED","Online (Updated)");
define("_WEBPHOTO_ITEM_STATUS_OFFLINE","Off Line");
define("_WEBPHOTO_ITEM_STATUS_EXPIRED","Expired");

// player option
define("_WEBPHOTO_PLAYER_STYLE_MONO","Monochrome");
define("_WEBPHOTO_PLAYER_STYLE_THEME","Color from Theme");
define("_WEBPHOTO_PLAYER_STYLE_PLAYER","Custom Player");
define("_WEBPHOTO_PLAYER_STYLE_PAGE","Custom Player/Page");

// flashvar desc
define("_WEBPHOTO_FLASHVAR_ID_DSC","[Basics] <br />Use this to set the RTMP stream identifier with the mediaplayer. <br />The ID will also be sent to statistics callbacks. <br />If you play a playlist, you can set an id for every entry. ");
define("_WEBPHOTO_FLASHVAR_HEIGHT_DSC","[Basics] ");
define("_WEBPHOTO_FLASHVAR_WIDTH_DSC","[Basics] ");
define("_WEBPHOTO_FLASHVAR_DISPLAYHEIGHT_DSC","[Playlist] [mediaplayer] <br />Set this smaller as the height to show a playlist below the display. <br />If you set it the same as the height, the controlbar will auto-hide on top of the video. ");
define("_WEBPHOTO_FLASHVAR_DISPLAYWIDTH_DSC","[Playlist] [mediaplayer] <br />Bottom tracks:<br /> Screen = Player<br /> Side tracks:<br />Screen < Player ");
define("_WEBPHOTO_FLASHVAR_DISPLAY_DEFAULT","when 0, use value of the player.");
define("_WEBPHOTO_FLASHVAR_SCREENCOLOR_DSC","[Colors] <br />[imagerotator] change this to your HTML page's color make images of different sizes blend nicely. ");
define("_WEBPHOTO_FLASHVAR_BACKCOLOR_DSC","[Colors] <br />Backgroundcolor of the controls");
define("_WEBPHOTO_FLASHVAR_FRONTCOLOR_DSC","[Colors] <br />Texts &amp; buttons color of the controls");
define("_WEBPHOTO_FLASHVAR_LIGHTCOLOR_DSC","[Colors] <br />Rollover color of the controls");
define("_WEBPHOTO_FLASHVAR_COLOR_DEFAULT","when blank, use value of the player.");
define("_WEBPHOTO_FLASHVAR_SEARCHBAR_DSC","[Basics] <br />Set this to false to hide the searchbar below the display. <br />You can set the search destination with the searchlink flashvar. ");
define("_WEBPHOTO_FLASHVAR_IMAGE_SHOW_DSC","[Basics] <br />true = Show preview image");
define("_WEBPHOTO_FLASHVAR_IMAGE_DSC","[Basics] <br />If you play a sound or movie, set this to the url of a preview image. <br />When using a playlist, you can set an image for every entry. ");
define("_WEBPHOTO_FLASHVAR_FILE_DSC","[Basics] <br />Sets the location of the file or playlist to play. <br />The imagerotator only plays playlists. ");
define("_WEBPHOTO_FLASHVAR_LOGO_DSC","[Display] <br />Set this to an image that can be put as a watermark logo in the top right corner of the display. <br />Transparent PNG files give the best results. ");
define("_WEBPHOTO_FLASHVAR_OVERSTRETCH_DSC","[Display] <br />Sets how to stretch images/movies to make them fit the display. <br />false (default) = fit the display. <br />true = stretch them proportionally to fill the display. <br />fit = stretch them disproportionally<br />none = keep original dimensions. ");
define("_WEBPHOTO_FLASHVAR_SHOWEQ_DSC","[Display] <br />Set this to true to show a (fake) equalizer at the bottom of the display. <br />Nice for MP3 files. ");
define("_WEBPHOTO_FLASHVAR_SHOWICONS_DSC","[Display] <br />Set this to false to hide the activity icon and play button in the middle of the display. ");
define("_WEBPHOTO_FLASHVAR_TRANSITION_DSC","[Display] [imagerotator] <br />Sets the transition to use between images. ");
define("_WEBPHOTO_FLASHVAR_SHOWNAVIGATION_DSC","[Controlbar] <br />Set this to false to completely hide the controlbar. ");
define("_WEBPHOTO_FLASHVAR_SHOWSTOP_DSC","[Controlbar] [mediaplayer] <br />Set this to true to show a stop button in the controlbar. ");
define("_WEBPHOTO_FLASHVAR_SHOWDIGITS_DSC","[Controlbar] [mediaplayer] <br />Set this to false to hide the elapsed/remaining digits in the controlbar. ");
define("_WEBPHOTO_FLASHVAR_SHOWDOWNLOAD_DSC","[Controlbar] [mediaplayer] <br />Set this to true to show a button in the player controlbar which links to the link flashvar. ");
define("_WEBPHOTO_FLASHVAR_USEFULLSCREEN_DSC","[Controlbar] <br />Set this to false to hide the fullscreen button and disable fullscreen. ");
define("_WEBPHOTO_FLASHVAR_AUTOSCROLL_DSC","[Playlist] [mediaplayer] <br />Set this to true to automatically scroll through the playlist on rollover, instead of using a scrollbar. ");
define("_WEBPHOTO_FLASHVAR_THUMBSINPLAYLIST_DSC","[Playlist] [mediaplayer] <br />Set this to false to hide preview images in the display");
define("_WEBPHOTO_FLASHVAR_AUDIO_DSC","[Playback] <br />Assigns an additional, synchronized MP3. <br />Use this for a closed audio description or director's comments with the mediaplayer or background music with the rotator. <br />When using the mediaplayer and a playlist, you can assign audio to every entry. ");
define("_WEBPHOTO_FLASHVAR_AUTOSTART_DSC","[Playback] <br />Set this to true in the player to automatically start playing when the page loads, <br />or set this to false with the rotator to prevent the automatic rotation.");
define("_WEBPHOTO_FLASHVAR_BUFFERLENGTH_DSC","[Playback] [mediaplayer] <br />Sets the number of seconds a video should be buffered before the players starts playback.<br />Set this small for fast connections or short videos and big for slow connections. ");
define("_WEBPHOTO_FLASHVAR_CAPTIONS_DSC","[Playback] [mediaplayer] <br />Captions should be in TimedText format. <br />When using a playlist, you can assign captions for every entry. ");
define("_WEBPHOTO_FLASHVAR_FALLBACK_DSC","[Playback] [mediaplayer] <br />If you play an MP4 file, set here the location of an FLV fallback. <br />It'll automatically be picked by older flash player. ");
define("_WEBPHOTO_FLASHVAR_REPEAT_DSC","[Playback] <br />Set this to true to automatically rpeat playback of all files. <br />Set this to list to playback an entire playlist once. ");
define("_WEBPHOTO_FLASHVAR_ROTATETIME_DSC","[Playback] <br />Sets the number of seconds an image is played back. ");
define("_WEBPHOTO_FLASHVAR_SHUFFLE_DSC","[Playback] <br />Set this to true to playback a playlist in random order. ");
define("_WEBPHOTO_FLASHVAR_SMOOTHING_DSC","[Playback] [mediaplayer] <br />Set this to false to turn of the smoothing of video. <br />Quality will decrease, but performance will increase. <br />Good for HD files and slower computers. ");
define("_WEBPHOTO_FLASHVAR_VOLUME_DSC","[Playback] <br />sets the startup volume for playback of sounds, movies and audiotracks. ");
define("_WEBPHOTO_FLASHVAR_ENABLEJS_DSC","[External] <br />Set this to true to enable javascript interaction. <br />This'll only work online! <br />Javascript interaction includes playback control, asynchroneous loading of media files and return of track information. ");
define("_WEBPHOTO_FLASHVAR_JAVASCRIPTID_DSC","[External] <br />If you interact with multiple mediaplayers/rotators in javascript, use this flashvar to give each of them a unique ID. ");
define("_WEBPHOTO_FLASHVAR_LINK_TYPE_DSC","[External] <br />This link is assigned to the display, logo and link button. <br >when None, assign nothing. <br />Else, assign a webpage to open. ");

//define("_WEBPHOTO_FLASHVAR_LINK_DSC","[External] <br />Set this to an external URL or downloadeable version of the file. <br />This link is assigned to the display, logo and link button. <br />With playlists, set links for every entry in the XML. ");

define("_WEBPHOTO_FLASHVAR_LINKFROMDISPLAY_DSC","[External] <br />Set this to true to make a click on the display result in a jump to the webpage assigned to the link flashvar. ");
define("_WEBPHOTO_FLASHVAR_LINKTARGET_DSC","[External] <br />Set this to the frame you want hyperlinks to open in. <br />Set it to _blank to open links in a new window or _top to open in the top frame. ");
define("_WEBPHOTO_FLASHVAR_CALLBACK_DSC","[External] <br />Set this to a serverside script that can process statistics. <br />The player will send it a POST every time an item starts/stops. <br />To send callbacks automatically to Google Analytics, set this to urchin or analytics. ");
define("_WEBPHOTO_FLASHVAR_RECOMMENDATIONS_DSC","[External] [mediaplayer] <br />Set this to an XML with items you want to recommend. <br />The thumbs will show up when the current movie stops playing, just like Youtube. ");
define("_WEBPHOTO_FLASHVAR_SEARCHLINK_DSC","[External] [mediaplayer] <br />Sets the destination of the searchbar. <br />The default is 'search.longtail.tv', but you can set other destinations. <br />Use the searchbar flashvar to hide the bar altogether. ");
define("_WEBPHOTO_FLASHVAR_STREAMSCRIPT_DSC","[External] [mediaplayer] <br />Set this to the URL of a script to use for http streaming movies. <br />The parameters file and pos are sent to the script. <br />If you use LigHTTPD streaming, set this to lighttpd. ");
define("_WEBPHOTO_FLASHVAR_TYPE_DSC","[External] [mediaplayer] <br />the mediaplayer which determines the type of file to play based upon the last three characters of the file flashvar. <br />This doesn't work with database id's or mod_rewrite, so you can set this flashvar to the correct filetype. <br />If not sure, the player assumes a playlist is loaded. ");

// flashvar option
define("_WEBPHOTO_FLASHVAR_LINK_TYPE_NONE","None");
define("_WEBPHOTO_FLASHVAR_LINK_TYPE_SITE","Website URL");
define("_WEBPHOTO_FLASHVAR_LINK_TYPE_PAGE","Detail Page");
define("_WEBPHOTO_FLASHVAR_LINK_TYPE_FILE","Media File");
define("_WEBPHOTO_FLASHVAR_LINKTREGET_SELF","Self Window");
define("_WEBPHOTO_FLASHVAR_LINKTREGET_BLANK","New Window");
define("_WEBPHOTO_FLASHVAR_OVERSTRETCH_FALSE","False");
define("_WEBPHOTO_FLASHVAR_OVERSTRETCH_FIT","Fit");
define("_WEBPHOTO_FLASHVAR_OVERSTRETCH_TRUE","True");
define("_WEBPHOTO_FLASHVAR_OVERSTRETCH_NONE","None");
define("_WEBPHOTO_FLASHVAR_TRANSITION_OFF","Slide Show Player Off");
define("_WEBPHOTO_FLASHVAR_TRANSITION_FADE","Fade");
define("_WEBPHOTO_FLASHVAR_TRANSITION_SLOWFADE","Slow Fade");
define("_WEBPHOTO_FLASHVAR_TRANSITION_BGFADE","Background Fade");
define("_WEBPHOTO_FLASHVAR_TRANSITION_CIRCLES","Circles");
define("_WEBPHOTO_FLASHVAR_TRANSITION_BLOCKS","Blocks");
define("_WEBPHOTO_FLASHVAR_TRANSITION_BUBBLES","Bubbles");
define("_WEBPHOTO_FLASHVAR_TRANSITION_FLASH","Flash");
define("_WEBPHOTO_FLASHVAR_TRANSITION_FLUIDS","Fluids");
define("_WEBPHOTO_FLASHVAR_TRANSITION_LINES","Lines");
define("_WEBPHOTO_FLASHVAR_TRANSITION_RANDOM","Random");

// edit form
define("_WEBPHOTO_CAP_DETAIL","Show Detail");
define("_WEBPHOTO_CAP_DETAIL_ONOFF","Ouvert/Fermer");
define("_WEBPHOTO_PLAYER","Player");
define("_WEBPHOTO_EMBED_ADD", "Add Embed Plugin" ) ;
define("_WEBPHOTO_EMBED_THUMB","La source extérieure fournira un aperçu.");
define("_WEBPHOTO_ERR_EMBED","Vous devez configurer le plugin");
define("_WEBPHOTO_ERR_PLAYLIST","Vous devez définir la playlist");

// sort
define("_WEBPHOTO_SORT_VOTESA","Votes (Least)");
define("_WEBPHOTO_SORT_VOTESD","Votes (Most)");
define("_WEBPHOTO_SORT_VIEWSA","Media Views (Least)");
define("_WEBPHOTO_SORT_VIEWSD","Media Views (Most)");

// flashvar form
define("_WEBPHOTO_FLASHVARS_FORM","FlashVars");
define("_WEBPHOTO_FLASHVARS_LIST","Liste des variables Flash");
define("_WEBPHOTO_FLASHVARS_LOGO_SELECT","Sélectionnez un jeu de logo");
define("_WEBPHOTO_FLASHVARS_LOGO_UPLOAD","Envoyer un jeu de logo ");
define("_WEBPHOTO_FLASHVARS_LOGO_DSC","[Display] <br />Les jeux de logos sont ");
define("_WEBPHOTO_BUTTON_COLOR_PICKUP","Couleur");
define("_WEBPHOTO_BUTTON_RESTORE","Restaurer par défaut");

// Playlist Cache 
define("_WEBPHOTO_PLAYLIST_STATUS_REPORT","Rapport de situation");
define("_WEBPHOTO_PLAYLIST_STATUS_FETCHED","Ce flux a été récupéré et mis en cache.");
define("_WEBPHOTO_PLAYLIST_STATUS_CREATED","Une nouvelle liste de lecture a été mise en cache");
define("_WEBPHOTO_PLAYLIST_ERR_CACHE","[ERREUR] la création de fichier cache");
define("_WEBPHOTO_PLAYLIST_ERR_FETCH","Impossible de récupérer le flux. <br /> confirmer l'emplacement et le flux d'actualisation du cache.");
define("_WEBPHOTO_PLAYLIST_ERR_NODIR","L'annuaire des médias n'existe pas");
define("_WEBPHOTO_PLAYLIST_ERR_EMPTYDIR","L'annuaire des médias est vide");
define("_WEBPHOTO_PLAYLIST_ERR_WRITE","Impossible d'écrire dans le fichier (voir permission chmod)");

define("_WEBPHOTO_USER",  "utilisateu" ) ;
define("_WEBPHOTO_OR",  "OU" ) ;

//---------------------------------------------------------
// v0.60
//---------------------------------------------------------
// item table
//define("_WEBPHOTO_ITEM_ICON" , "Icon Name" ) ;

define("_WEBPHOTO_ITEM_EXTERNAL_MIDDLE" , "External Middle URL" ) ;

// cat table
define("_WEBPHOTO_CAT_IMG_NAME" , "Catégorie de nom de l'image" ) ;

// edit form
define("_WEBPHOTO_CAP_MIDDLE_SELECT", "Sélectionnez une image de taille moyenne");

//---------------------------------------------------------
// v0.70
//---------------------------------------------------------
// item table
define("_WEBPHOTO_ITEM_CODEINFO", "Code Info");
define("_WEBPHOTO_ITEM_PAGE_WIDTH",  "Largeur de page");
define("_WEBPHOTO_ITEM_PAGE_HEIGHT", "Hauteur de page");
define("_WEBPHOTO_ITEM_EMBED_TEXT",  "Intégrer");

// item option
define("_WEBPHOTO_ITEM_CODEINFO_CONT","médias");
define("_WEBPHOTO_ITEM_CODEINFO_THUMB","Petite Image");
define("_WEBPHOTO_ITEM_CODEINFO_MIDDLE","Moyen Image");
define("_WEBPHOTO_ITEM_CODEINFO_FLASH","Flash Video");
define("_WEBPHOTO_ITEM_CODEINFO_DOCOMO","Autres formats Videos");
define("_WEBPHOTO_ITEM_CODEINFO_PAGE","URL");
define("_WEBPHOTO_ITEM_CODEINFO_SITE","Site");
define("_WEBPHOTO_ITEM_CODEINFO_PLAY","Liste de sélection");
define("_WEBPHOTO_ITEM_CODEINFO_EMBED","Intégrer");
define("_WEBPHOTO_ITEM_CODEINFO_JS","Script");

define("_WEBPHOTO_ITEM_PLAYLIST_TIME_HOUR", "1 heure");
define("_WEBPHOTO_ITEM_PLAYLIST_TIME_DAY",  "1 jour");
define("_WEBPHOTO_ITEM_PLAYLIST_TIME_WEEK", "1 semaine");
define("_WEBPHOTO_ITEM_PLAYLIST_TIME_MONTH","1 mois");

// photo
define("_WEBPHOTO_DOWNLOAD","Télécharger");

// file_read
define("_WEBPHOTO_NO_FILE", "Le fichier n'existe pas");

//---------------------------------------------------------
// v0.80
//---------------------------------------------------------
// item table
define("_WEBPHOTO_ITEM_ICON_NAME" ,   "Nom de l'icône" ) ;
define("_WEBPHOTO_ITEM_ICON_WIDTH" ,  "Largeur de l'icône" ) ;
define("_WEBPHOTO_ITEM_ICON_HEIGHT" , "Hauteur de l'icône" ) ;

// item form
define("_WEBPHOTO_DSC_SET_ITEM_TIME_UPDATE",  "mis à jour du délai de publication");
define("_WEBPHOTO_DSC_SET_ITEM_TIME_PUBLISH", "Réglez le délai de publication");
define("_WEBPHOTO_DSC_SET_ITEM_TIME_EXPIRE",  "Le délai autorisé est terminé");

//---------------------------------------------------------
// v0.81
//---------------------------------------------------------
// vote option
define("_WEBPHOTO_VOTE_RATING_1", "1");
define("_WEBPHOTO_VOTE_RATING_2", "2");
define("_WEBPHOTO_VOTE_RATING_3", "3");
define("_WEBPHOTO_VOTE_RATING_4", "4");
define("_WEBPHOTO_VOTE_RATING_5", "5");
define("_WEBPHOTO_VOTE_RATING_6", "6");
define("_WEBPHOTO_VOTE_RATING_7", "7");
define("_WEBPHOTO_VOTE_RATING_8", "8");
define("_WEBPHOTO_VOTE_RATING_9", "9");
define("_WEBPHOTO_VOTE_RATING_10","10");

//---------------------------------------------------------
// v0.90
//---------------------------------------------------------
// edit form
define("_WEBPHOTO_GROUP_PERM_ALL" , "Tous les groupes" ) ;

//---------------------------------------------------------
// v1.00
//---------------------------------------------------------
// item table
define("_WEBPHOTO_ITEM_EDITOR", "Editor");
define("_WEBPHOTO_ITEM_DESCRIPTION_HTML",   "HTML Mots-clés");
define("_WEBPHOTO_ITEM_DESCRIPTION_SMILEY", "Smiley icons");
define("_WEBPHOTO_ITEM_DESCRIPTION_XCODE",  "Codes");
define("_WEBPHOTO_ITEM_DESCRIPTION_IMAGE",  "Images");
define("_WEBPHOTO_ITEM_DESCRIPTION_BR",     "saut de ligne");

// edit form
define("_WEBPHOTO_TITLE_EDITOR_SELECT", "Sélectionnez un Editeur");
define("_WEBPHOTO_CAP_DESCRIPTION_OPTION", "Options");
define("_WEBPHOTO_CAP_HTML",   "Activer les balises HTML");
define("_WEBPHOTO_CAP_SMILEY", "Activer les smileys icônes");
define("_WEBPHOTO_CAP_XCODE",  "Activer les codes");
define("_WEBPHOTO_CAP_IMAGE",  "Activer l'image");
define("_WEBPHOTO_CAP_BR",     "Activer le saut de ligne");

// === define end ===
}
// jodconverter
define("_WEBPHOTO_JODCONVERTER_JUNK_WORDS", "Jod convertisseur junk mot");
?>