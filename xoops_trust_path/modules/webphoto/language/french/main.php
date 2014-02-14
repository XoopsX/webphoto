<?php
// $Id: main.php,v 1.2 2009/05/17 08:24:26 ohwada Exp $

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
define("_WEBPHOTO_SUBMITTER","Proposé par");
define("_WEBPHOTO_NOMATCH_PHOTO","Aucune photo ne correspond à votre recherche");

define("_WEBPHOTO_ICON_NEW","Nouveau");
define("_WEBPHOTO_ICON_UPDATE","Mise à jour");
define("_WEBPHOTO_ICON_POPULAR","Populaire");
define("_WEBPHOTO_ICON_LASTUPDATE","Dernière mise à jour");
define("_WEBPHOTO_ICON_HITS","Affichages");
define("_WEBPHOTO_ICON_COMMENTS","Commentaires");

define("_WEBPHOTO_SORT_IDA","Numéro d'enregistrement (classement par ID croissant)");
define("_WEBPHOTO_SORT_IDD","Numéro d'enregistrement (classement par ID décroissant)");
define("_WEBPHOTO_SORT_HITSA","Popularité (ordre croissant)");
define("_WEBPHOTO_SORT_HITSD","Popularité (ordre décroissant)");
define("_WEBPHOTO_SORT_TITLEA","Titre (ordre alphabétique)");
define("_WEBPHOTO_SORT_TITLED","Titre (odre ante alhabétique))");
define("_WEBPHOTO_SORT_DATEA","Date de mise à jour (ordre chronologique)");
define("_WEBPHOTO_SORT_DATED","Date de mise à jour (ordre ante chronologique)");
define("_WEBPHOTO_SORT_RATINGA","Note (des plus basses aux plus hautes)");
define("_WEBPHOTO_SORT_RATINGD","Note (des plus hautes aux plus basses)");
define("_WEBPHOTO_SORT_RANDOM","Au hasard");

define("_WEBPHOTO_SORT_SORTBY","Trié par :");
define("_WEBPHOTO_SORT_TITLE","Titre");
define("_WEBPHOTO_SORT_DATE","Date de mise à jour");
define("_WEBPHOTO_SORT_HITS","Popularité");
define("_WEBPHOTO_SORT_RATING","Note");
define("_WEBPHOTO_SORT_S_CURSORTEDBY","Elements actuellement classés par : %s");

define("_WEBPHOTO_NAVI_PREVIOUS","Précédent");
define("_WEBPHOTO_NAVI_NEXT","Suivant");
define("_WEBPHOTO_S_NAVINFO" , "Photo No. %s - %s (de %s affichages)" ) ;
define("_WEBPHOTO_S_THEREARE","Il y a actuellement <b>%s</b> images dans notre base de données.");
define("_WEBPHOTO_S_MOREPHOTOS","Plus de photos de %s");
define("_WEBPHOTO_ONEVOTE","1 vote");
define("_WEBPHOTO_S_NUMVOTES","%s votes");
define("_WEBPHOTO_ONEPOST","1 commentaire");
define("_WEBPHOTO_S_NUMPOSTS","%s commentaires");
define("_WEBPHOTO_VOTETHIS","Voter");
define("_WEBPHOTO_TELLAFRIEND","En parler à un(e) ami(e)");
define("_WEBPHOTO_SUBJECT4TAF","Une photo pour vous !");


//---------------------------------------------------------
// submit
//---------------------------------------------------------
// only "Y/m/d" , "d M Y" , "M d Y" can be interpreted
define("_WEBPHOTO_DTFMT_YMDHI" , "d M Y H:i" ) ;

define("_WEBPHOTO_TITLE_ADDPHOTO","Ajouter une photo");
define("_WEBPHOTO_TITLE_PHOTOUPLOAD","Envoyer une photo");
define("_WEBPHOTO_CAP_MAXPIXEL","Taille maximale (pixels)");
define("_WEBPHOTO_CAP_MAXSIZE","Poids maximal (bytes)");
define("_WEBPHOTO_CAP_VALIDPHOTO","Valider");
define("_WEBPHOTO_DSC_TITLE_BLANK","Laisser le champ vide pour utiliser le nom du fichier en tant que titre");

define("_WEBPHOTO_RADIO_ROTATETITLE" , "Rotation de l'image" ) ;
define("_WEBPHOTO_RADIO_ROTATE0" , "Ne pas pivoter" ) ;
define("_WEBPHOTO_RADIO_ROTATE90" , "90° vers la droite" ) ;
define("_WEBPHOTO_RADIO_ROTATE180" , "180°" ) ;
define("_WEBPHOTO_RADIO_ROTATE270" , "90° vers la gauche" ) ;

define("_WEBPHOTO_SUBMIT_RECEIVED","Nous avons reçu votre image. Merci !");
define("_WEBPHOTO_SUBMIT_ALLPENDING","Toutes les photos proposées sont vérifiées avant publication.");

define("_WEBPHOTO_ERR_MUSTREGFIRST","Désolé, vous ne disposez pas des permissions requises pour effactuer cette action.<br />Veuillez vous identifier ou vous créer un compte.");
define("_WEBPHOTO_ERR_MUSTADDCATFIRST","Désolé, aucune Catégorie n'est disponible.<br />Veuillez créer d'abord créer une Catégorie");
define("_WEBPHOTO_ERR_NOIMAGESPECIFIED","Aucune photo n'a été téléversée");
define("_WEBPHOTO_ERR_FILE","Les photos sont trop volumineuses ou un problème de configuration est survenu");
define("_WEBPHOTO_ERR_FILEREAD","Les photos ne peuvent pas être atteintes.");
define("_WEBPHOTO_ERR_TITLE","Vous devez entrer un 'Titre' ");


//---------------------------------------------------------
// edit
//---------------------------------------------------------
define("_WEBPHOTO_TITLE_EDIT","Modifier une photo");
define("_WEBPHOTO_TITLE_PHOTODEL","Supprimer une photo");
define("_WEBPHOTO_CONFIRM_PHOTODEL","Supprimer la photo ?");
define("_WEBPHOTO_DBUPDATED","Mise à jour de la Base de données effectuée avec succès !");
define("_WEBPHOTO_DELETED","Supprimée !");


//---------------------------------------------------------
// rate
//---------------------------------------------------------
define("_WEBPHOTO_RATE_VOTEONCE","Merci de ne pas voter pour une même ressource plus d'une fois.");
define("_WEBPHOTO_RATE_RATINGSCALE","L'échelle de notation va de 01 à 10, 10 étant la meilleure évaluation possible.");
define("_WEBPHOTO_RATE_BEOBJECTIVE","Afin de préserver la pertinence des classements, merci d'être objectif");
define("_WEBPHOTO_RATE_DONOTVOTE","Veuillez ne pas voter pour vos propres ressources.");
define("_WEBPHOTO_RATE_IT","Noter");
define("_WEBPHOTO_RATE_VOTEAPPRE","Votre note est enregistrée");
define("_WEBPHOTO_RATE_S_THANKURATE","Merci d'avoir pris quelques instants pour évaluer cette ressources sur %s.");

define("_WEBPHOTO_ERR_NORATING","Aucune note sélectionnée");
define("_WEBPHOTO_ERR_CANTVOTEOWN","Vous n'êtes pas autorisé à voter pour vos propres ressources.<br />Toutes les évaluations sont enregistrées et contrôlées.");
define("_WEBPHOTO_ERR_VOTEONCE","Ne votez pas plus d'une fois pour la même ressources.<br />Toutes les évaluations sont enregistrées et contrôlées.");


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
//define("_WEBPHOTO_LATESTLIST","Latest Listings");

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


//---------------------------------------------------------
// move from myalbum_constants.php
//---------------------------------------------------------
// Caption
define("_WEBPHOTO_CAPTION_TOTAL" , "Total:" ) ;
define("_WEBPHOTO_CAPTION_GUESTNAME" , "Invité" ) ;
define("_WEBPHOTO_CAPTION_REFRESH" , "Rafraîchir" ) ;
define("_WEBPHOTO_CAPTION_IMAGEXYT" , "Dimensions (type)" ) ;
define("_WEBPHOTO_CAPTION_CATEGORY" , "Catégorie" ) ;


//=========================================================
// add for webphoto
//=========================================================

//---------------------------------------------------------
// database table items
//---------------------------------------------------------

// photo table
define("_WEBPHOTO_PHOTO_TABLE" , "Tableau des photos" ) ;
define("_WEBPHOTO_PHOTO_ID" , "ID de la photo" ) ;
define("_WEBPHOTO_PHOTO_TIME_CREATE" , "Date de création" ) ;
define("_WEBPHOTO_PHOTO_TIME_UPDATE" , "Date de mise à jour" ) ;
define("_WEBPHOTO_PHOTO_CAT_ID" ,  "ID de la Catégorie" ) ;
define("_WEBPHOTO_PHOTO_GICON_ID" , "ID de la vignette" ) ;
define("_WEBPHOTO_PHOTO_UID" ,   "ID de l'utilisateur" ) ;
define("_WEBPHOTO_PHOTO_DATETIME" ,  "Date de la photo" ) ;
define("_WEBPHOTO_PHOTO_TITLE" , "Titre de la photo" ) ;
define("_WEBPHOTO_PHOTO_PLACE" , "Localisation" ) ;
define("_WEBPHOTO_PHOTO_EQUIPMENT" , "Equipement" ) ;
define("_WEBPHOTO_PHOTO_FILE_URL" ,  "Url du fichier" ) ;
define("_WEBPHOTO_PHOTO_FILE_PATH" , "Chemin d'accès au fichier" ) ;
define("_WEBPHOTO_PHOTO_FILE_NAME" , "Nom du fichier" ) ;
define("_WEBPHOTO_PHOTO_FILE_EXT" ,  "Extension du fichier" ) ;
define("_WEBPHOTO_PHOTO_FILE_MIME" ,  "MIME TYPE du fichier" ) ;
define("_WEBPHOTO_PHOTO_FILE_MEDIUM" ,  "Type de format du fichier" ) ;
define("_WEBPHOTO_PHOTO_FILE_SIZE" , "Taille du fichier" ) ;
define("_WEBPHOTO_PHOTO_CONT_URL" ,    "Url de la photo" ) ;
define("_WEBPHOTO_PHOTO_CONT_PATH" ,   "Chemin d'accès à la photo" ) ;
define("_WEBPHOTO_PHOTO_CONT_NAME" ,   "Nom de la photo" ) ;
define("_WEBPHOTO_PHOTO_CONT_EXT" ,    "Extension de la photo" ) ;
define("_WEBPHOTO_PHOTO_CONT_MIME" ,   "MIME TYPE de la photo" ) ;
define("_WEBPHOTO_PHOTO_CONT_MEDIUM" , "Type de format de la photo" ) ;
define("_WEBPHOTO_PHOTO_CONT_SIZE" ,   "Taille de la photo" ) ;
define("_WEBPHOTO_PHOTO_CONT_WIDTH" ,  "Largeur de la photo" ) ;
define("_WEBPHOTO_PHOTO_CONT_HEIGHT" , "Hauteur de la photo" ) ;
define("_WEBPHOTO_PHOTO_CONT_DURATION" , "Durée de la vidéo" ) ;
define("_WEBPHOTO_PHOTO_CONT_EXIF" , "Information Exif" ) ;
define("_WEBPHOTO_PHOTO_MIDDLE_WIDTH" ,  "Largeur moyenne de l'image" ) ;
define("_WEBPHOTO_PHOTO_MIDDLE_HEIGHT" , "Hauteur moyenne de l'image" ) ;
define("_WEBPHOTO_PHOTO_THUMB_URL" ,    "Url de la miniature" ) ;
define("_WEBPHOTO_PHOTO_THUMB_PATH" ,   "Chemin d'accès à la miniature" ) ;
define("_WEBPHOTO_PHOTO_THUMB_NAME" ,   "Nom de la miniature" ) ;
define("_WEBPHOTO_PHOTO_THUMB_EXT" ,    "Extension de la miniature" ) ;
define("_WEBPHOTO_PHOTO_THUMB_MIME" ,   "MIME TYPE de la miniature" ) ;
define("_WEBPHOTO_PHOTO_THUMB_MEDIUM" , "Type de format de la miniature" ) ;
define("_WEBPHOTO_PHOTO_THUMB_SIZE" ,   "Taille de la miniature" ) ;
define("_WEBPHOTO_PHOTO_THUMB_WIDTH" ,  "Largeur de la miniature" ) ;
define("_WEBPHOTO_PHOTO_THUMB_HEIGHT" , "Hauteur de la miniature" ) ;
define("_WEBPHOTO_PHOTO_GMAP_LATITUDE" ,  "Latitude de la carte Googlemap" ) ;
define("_WEBPHOTO_PHOTO_GMAP_LONGITUDE" , "Longitude de la carte Googlemap" ) ;
define("_WEBPHOTO_PHOTO_GMAP_ZOOM" ,      "Pourcentage du zoom" ) ;
define("_WEBPHOTO_PHOTO_GMAP_TYPE" ,      "Type de carte" ) ;
define("_WEBPHOTO_PHOTO_PERM_READ" , "Permission de consulter" ) ;
define("_WEBPHOTO_PHOTO_STATUS" ,   "Statut" ) ;
define("_WEBPHOTO_PHOTO_HITS" ,     "Affichages" ) ;
define("_WEBPHOTO_PHOTO_RATING" ,   "Notes" ) ;
define("_WEBPHOTO_PHOTO_VOTES" ,    "Votes" ) ;
define("_WEBPHOTO_PHOTO_COMMENTS" , "Commentaires" ) ;
define("_WEBPHOTO_PHOTO_TEXT1" ,  "texte1" ) ;
define("_WEBPHOTO_PHOTO_TEXT2" ,  "texte2" ) ;
define("_WEBPHOTO_PHOTO_TEXT3" ,  "texte3" ) ;
define("_WEBPHOTO_PHOTO_TEXT4" ,  "texte4" ) ;
define("_WEBPHOTO_PHOTO_TEXT5" ,  "texte5" ) ;
define("_WEBPHOTO_PHOTO_TEXT6" ,  "texte6" ) ;
define("_WEBPHOTO_PHOTO_TEXT7" ,  "texte7" ) ;
define("_WEBPHOTO_PHOTO_TEXT8" ,  "texte8" ) ;
define("_WEBPHOTO_PHOTO_TEXT9" ,  "texte9" ) ;
define("_WEBPHOTO_PHOTO_TEXT10" , "texte10" ) ;
define("_WEBPHOTO_PHOTO_DESCRIPTION" ,  "Description de la photo" ) ;
define("_WEBPHOTO_PHOTO_SEARCH" ,  "Rechercher" ) ;

// category table
define("_WEBPHOTO_CAT_TABLE" , "Tableau des Catégories" ) ;
define("_WEBPHOTO_CAT_ID" ,          "ID de la Catégorie" ) ;
define("_WEBPHOTO_CAT_TIME_CREATE" , "Date de création" ) ;
define("_WEBPHOTO_CAT_TIME_UPDATE" , "Date de mise à jour" ) ;
define("_WEBPHOTO_CAT_GICON_ID" ,  "ID de la vignette" ) ;
define("_WEBPHOTO_CAT_FORUM_ID" ,  "ID du forum" ) ;
define("_WEBPHOTO_CAT_PID" ,    "ID associé" ) ;
define("_WEBPHOTO_CAT_TITLE" ,  "Titre de la Catégorie" ) ;
define("_WEBPHOTO_CAT_IMG_PATH" , "Chemin d'accès à l'image de la Catégorie" ) ;
define("_WEBPHOTO_CAT_IMG_MODE" , "Mode d'affichage de l'image" ) ;
define("_WEBPHOTO_CAT_ORIG_WIDTH" ,  "Largeur originale de l'image" ) ;
define("_WEBPHOTO_CAT_ORIG_HEIGHT" , "Hauteur originale de l'image" ) ;
define("_WEBPHOTO_CAT_MAIN_WIDTH" ,  "Largeur de l'image dans la Catégorie principale" ) ;
define("_WEBPHOTO_CAT_MAIN_HEIGHT" , "Hauteur de l'image dans la Catégorie principale" ) ;
define("_WEBPHOTO_CAT_SUB_WIDTH" ,   "Largeur de l'image dans la sous-catégorie" ) ;
define("_WEBPHOTO_CAT_SUB_HEIGHT" ,  "Hauteur de l'image dans la sous-catégorie" ) ;
define("_WEBPHOTO_CAT_WEIGHT" , "Poids" ) ;
define("_WEBPHOTO_CAT_DEPTH" ,  "Profondeur" ) ;
define("_WEBPHOTO_CAT_ALLOWED_EXT" , "Extensions autorisées" ) ;
define("_WEBPHOTO_CAT_ITEM_TYPE" ,      "Type de données" ) ;
define("_WEBPHOTO_CAT_GMAP_MODE" ,      "Mode d'affichage de la carte Googlemap" ) ;
define("_WEBPHOTO_CAT_GMAP_LATITUDE" ,  "Latitude de la carte" ) ;
define("_WEBPHOTO_CAT_GMAP_LONGITUDE" , "Longitude de la carte" ) ;
define("_WEBPHOTO_CAT_GMAP_ZOOM" ,      "Pourcentage du zoom" ) ;
define("_WEBPHOTO_CAT_GMAP_TYPE" ,      "Type de carte" ) ;
define("_WEBPHOTO_CAT_PERM_READ" , "Permission de consulter" ) ;
define("_WEBPHOTO_CAT_PERM_POST" , "Permission de proposer" ) ;
define("_WEBPHOTO_CAT_TEXT1" ,  "texte1" ) ;
define("_WEBPHOTO_CAT_TEXT2" ,  "texte2" ) ;
define("_WEBPHOTO_CAT_TEXT3" ,  "texte3" ) ;
define("_WEBPHOTO_CAT_TEXT4" ,  "texte4" ) ;
define("_WEBPHOTO_CAT_TEXT5" ,  "texte5" ) ;
define("_WEBPHOTO_CAT_DESCRIPTION" ,  "Description de la Catégorie" ) ;

// vote table
define("_WEBPHOTO_VOTE_TABLE" , "Table des votes" ) ;
define("_WEBPHOTO_VOTE_ID" ,          "ID du vote" ) ;
define("_WEBPHOTO_VOTE_TIME_CREATE" , "Date du vote" ) ;
define("_WEBPHOTO_VOTE_TIME_UPDATE" , "Mise à jour du vote" ) ;
define("_WEBPHOTO_VOTE_PHOTO_ID" , "ID de la photo" ) ;
define("_WEBPHOTO_VOTE_UID" ,      "ID de l'utilisateur" ) ;
define("_WEBPHOTO_VOTE_RATING" ,   "Notes" ) ;
define("_WEBPHOTO_VOTE_HOSTNAME" , "Adresse IP" ) ;

// google icon table
define("_WEBPHOTO_GICON_TABLE" , "Tableau des icônes Google" ) ;
define("_WEBPHOTO_GICON_ID" ,          "ID de l'icône" ) ;
define("_WEBPHOTO_GICON_TIME_CREATE" , "Date de création" ) ;
define("_WEBPHOTO_GICON_TIME_UPDATE" , "Date de mise à jour" ) ;
define("_WEBPHOTO_GICON_TITLE" ,     "Titre de l'icône" ) ;
define("_WEBPHOTO_GICON_IMAGE_PATH" ,  "Chemin d'accès à l'icône" ) ;
define("_WEBPHOTO_GICON_IMAGE_NAME" ,  "Extension de l'image" ) ;
define("_WEBPHOTO_GICON_SHADOW_PATH" , "Chemin d'accès masqué" ) ;
define("_WEBPHOTO_GICON_SHADOW_NAME" , "Nom caché" ) ;
define("_WEBPHOTO_GICON_SHADOW_EXT" ,  "Extension cachée" ) ;
define("_WEBPHOTO_GICON_IMAGE_WIDTH" ,  "Largeur de l'image" ) ;
define("_WEBPHOTO_GICON_IMAGE_HEIGHT" , "Hauteur de l'image" ) ;
define("_WEBPHOTO_GICON_SHADOW_WIDTH" ,  "Largeur de l'ombre" ) ;
define("_WEBPHOTO_GICON_SHADOW_HEIGHT" , "Hauteur de l'ombre" ) ;
define("_WEBPHOTO_GICON_ANCHOR_X" , "Point d'ancrage sur X" ) ;
define("_WEBPHOTO_GICON_ANCHOR_Y" , "Point d'ancrage sur Y" ) ;
define("_WEBPHOTO_GICON_INFO_X" , "Largeur de la fenêtre d'information" ) ;
define("_WEBPHOTO_GICON_INFO_Y" , "Hauteur de la fenêtre d'information" ) ;

// mime type table
define("_WEBPHOTO_MIME_TABLE" , "Tableau des MIME TYPES" ) ;
define("_WEBPHOTO_MIME_ID" ,          "ID du MIME TYPE" ) ;
define("_WEBPHOTO_MIME_TIME_CREATE" , "Date de création" ) ;
define("_WEBPHOTO_MIME_TIME_UPDATE" , "Date de mise à jour" ) ;
define("_WEBPHOTO_MIME_EXT" ,   "Extension" ) ;
define("_WEBPHOTO_MIME_MEDIUM" ,  "Format" ) ;
define("_WEBPHOTO_MIME_TYPE" ,  "MIME Type" ) ;
define("_WEBPHOTO_MIME_NAME" ,  "Nom du MIME TYPE" ) ;
define("_WEBPHOTO_MIME_PERMS" , "Permission" ) ;

// added in v0.20
define("_WEBPHOTO_MIME_FFMPEG" , "Option ffmpeg" ) ;

// tag table
define("_WEBPHOTO_TAG_TABLE" , "Tableau des Tags" ) ;
define("_WEBPHOTO_TAG_ID" ,          "ID du tag" ) ;
define("_WEBPHOTO_TAG_TIME_CREATE" , "Date de création" ) ;
define("_WEBPHOTO_TAG_TIME_UPDATE" , "Date de mise à jour" ) ;
define("_WEBPHOTO_TAG_NAME" ,   "Nom du tag" ) ;

// photo-to-tag table
define("_WEBPHOTO_P2T_TABLE" , "Tableau de relation Photo / Tags" ) ;
define("_WEBPHOTO_P2T_ID" ,          "ID du Photo-tag" ) ;
define("_WEBPHOTO_P2T_TIME_CREATE" , "Date de création" ) ;
define("_WEBPHOTO_P2T_TIME_UPDATE" , "Date de mise à jour" ) ;
define("_WEBPHOTO_P2T_PHOTO_ID" , "ID de la photo" ) ;
define("_WEBPHOTO_P2T_TAG_ID" ,   "ID du tag" ) ;
define("_WEBPHOTO_P2T_UID" ,      "ID de l'utilisateur" ) ;

// synonym table
define("_WEBPHOTO_SYNO_TABLE" , "Table des synonymes" ) ;
define("_WEBPHOTO_SYNO_ID" ,          "ID du synonyme" ) ;
define("_WEBPHOTO_SYNO_TIME_CREATE" , "Date de création" ) ;
define("_WEBPHOTO_SYNO_TIME_UPDATE" , "Date de mise à jour" ) ;
define("_WEBPHOTO_SYNO_WEIGHT" , "Poids" ) ;
define("_WEBPHOTO_SYNO_KEY" , "Clé" ) ;
define("_WEBPHOTO_SYNO_VALUE" , "Synonyme" ) ;


//---------------------------------------------------------
// title
//---------------------------------------------------------
define("_WEBPHOTO_TITLE_LATEST","Les derniers");
define("_WEBPHOTO_TITLE_SUBMIT","Soumettre");
define("_WEBPHOTO_TITLE_POPULAR","Populaire");
define("_WEBPHOTO_TITLE_HIGHRATE","Les mieux notés");
define("_WEBPHOTO_TITLE_MYPHOTO","Mes photos");
define("_WEBPHOTO_TITLE_RANDOM","Photos au hasard");
define("_WEBPHOTO_TITLE_HELP","Aide");
define("_WEBPHOTO_TITLE_CATEGORY_LIST", "Liste des Catégories");
define("_WEBPHOTO_TITLE_TAG_LIST",  "Liste des tags");
define("_WEBPHOTO_TITLE_TAGS",  "Tag");
define("_WEBPHOTO_TITLE_USER_LIST", "Auteur de la liste");
define("_WEBPHOTO_TITLE_DATE_LIST", "Date de la liste de photos");
define("_WEBPHOTO_TITLE_PLACE_LIST","Localisation de la liste de photos");
define("_WEBPHOTO_TITLE_RSS","RSS");

define("_WEBPHOTO_VIEWTYPE_LIST", "Type de liste");
define("_WEBPHOTO_VIEWTYPE_TABLE", "Tableau des types");

define("_WEBPHOTO_CATLIST_ON",   "Afficher la Catégorie");
define("_WEBPHOTO_CATLIST_OFF",  "Masquer la Catégorie");
define("_WEBPHOTO_TAGCLOUD_ON",  "Afficher le nuage de tags");
define("_WEBPHOTO_TAGCLOUD_OFF", "Masquer le nuage de tags");
define("_WEBPHOTO_GMAP_ON",  "Afficher Googlemap");
define("_WEBPHOTO_GMAP_OFF", "Masquer Googlemap");

define("_WEBPHOTO_NO_TAG","Ne pas entrer de tags");

//---------------------------------------------------------
// google maps
//---------------------------------------------------------
define("_WEBPHOTO_TITLE_GET_LOCATION", "Paramètres de latitude et de longitude");
define("_WEBPHOTO_GMAP_DESC", "Afficher la miniature au clic sur le marqueur de la carte");
define("_WEBPHOTO_GMAP_ICON", "Icones Googlemap");
define("_WEBPHOTO_GMAP_LATITUDE", "Latitude Googlemap");
define("_WEBPHOTO_GMAP_LONGITUDE","Longitude Googlemap");
define("_WEBPHOTO_GMAP_ZOOM","Zoom Googlemap");
define("_WEBPHOTO_GMAP_ADDRESS",  "Adresse");
define("_WEBPHOTO_GMAP_GET_LOCATION", "Obtenir la latitude et la longitude");
define("_WEBPHOTO_GMAP_SEARCH_LIST",  "Liste de recherche");
define("_WEBPHOTO_GMAP_CURRENT_LOCATION",  "Localisation actuelle");
define("_WEBPHOTO_GMAP_CURRENT_ADDRESS",  "Adresse actuelle");
define("_WEBPHOTO_GMAP_NO_MATCH_PLACE",  "Aucun emplacement ne correspond");
define("_WEBPHOTO_GMAP_NOT_COMPATIBLE", "Ne pas afficher Googlemap dans le navigateur");
define("_WEBPHOTO_JS_INVALID", "Ne pas utiliser de Javascript dans le navigateur");
define("_WEBPHOTO_IFRAME_NOT_SUPPORT","Ne pas utiliser d'iframe dans le navigateur");

//---------------------------------------------------------
// search
//---------------------------------------------------------
define("_WEBPHOTO_SR_SEARCH","Rechercher");

//---------------------------------------------------------
// popbox
//---------------------------------------------------------
define("_WEBPHOTO_POPBOX_REVERT", "Cliquer sur l'image pour développer");

//---------------------------------------------------------
// tag
//---------------------------------------------------------
define("_WEBPHOTO_TAGS","Tags");
define("_WEBPHOTO_EDIT_TAG","Modifier les Tags");
define("_WEBPHOTO_DSC_TAG_DIVID", "séparez par une virgule (,) les tags que vous souhaitez utiliser");
define("_WEBPHOTO_DSC_TAG_EDITABLE", "Vous êtes seulement autorisés à modifier vos propres tags");

//---------------------------------------------------------
// submit form
//---------------------------------------------------------
define("_WEBPHOTO_CAP_ALLOWED_EXTS", "Extensions autorisées");
define("_WEBPHOTO_CAP_PHOTO_SELECT","Sélectionner l'image principale");
define("_WEBPHOTO_CAP_THUMB_SELECT", "Sélectionner la miniature");
define("_WEBPHOTO_DSC_THUMB_SELECT", "Créée depuis l'image principale lorsqu'aucune vignette n'est sélectionnée");
define("_WEBPHOTO_DSC_SET_DATETIME",  "Indiquez la date de la photo");

//define("_WEBPHOTO_DSC_SET_TIME_UPDATE", "Set update time");

define("_WEBPHOTO_DSC_PIXCEL_RESIZE", "Redimensionner automatiquement en cas de dépassement de la taille");
define("_WEBPHOTO_DSC_PIXCEL_REJECT", "Téléversement impossible en cas de dépassement de la taille");
define("_WEBPHOTO_BUTTON_CLEAR", "Effacer");
define("_WEBPHOTO_SUBMIT_RESIZED", "Photo redimensionnée car dépassant les limites paramétrées");

// PHP upload error
// http://www.php.net/manual/en/features.file-upload.errors.php
define("_WEBPHOTO_PHP_UPLOAD_ERR_OK", "Aucune erreur, le fichier a été téléversé avec succès.");
define("_WEBPHOTO_PHP_UPLOAD_ERR_INI_SIZE", "Les fichiers téléversés dépassent la limite de la configuration (upload_max_filesize).");
define("_WEBPHOTO_PHP_UPLOAD_ERR_FORM_SIZE", "Le fichier téléversé dépasse la limite de %s .");
define("_WEBPHOTO_PHP_UPLOAD_ERR_PARTIAL", "Le téléversement a été partiellement effectué");
define("_WEBPHOTO_PHP_UPLOAD_ERR_NO_FILE", "Aucun fichier n'a pu être téléversé.");
define("_WEBPHOTO_PHP_UPLOAD_ERR_NO_TMP_DIR", "Un répertoire temporaire est manquant.");
define("_WEBPHOTO_PHP_UPLOAD_ERR_CANT_WRITE", "Echec d'écriture sur le disque.");
define("_WEBPHOTO_PHP_UPLOAD_ERR_EXTENSION", "Le téléversement a été interrompu en raison de l'extension du fichier.");

// upload error
define("_WEBPHOTO_UPLOADER_ERR_NOT_FOUND", "Le fichier téléversé n'a pas été trouvé");
define("_WEBPHOTO_UPLOADER_ERR_INVALID_FILE_SIZE", "Taille du fichier invalide");
define("_WEBPHOTO_UPLOADER_ERR_EMPTY_FILE_NAME", "Le nom du fichier est absent");
define("_WEBPHOTO_UPLOADER_ERR_NO_FILE", "Aucun fichier téléversé");
define("_WEBPHOTO_UPLOADER_ERR_NOT_SET_DIR", "Le répertoire pour les téléversement n'a pas été précisé");
define("_WEBPHOTO_UPLOADER_ERR_NOT_ALLOWED_EXT", "L'extension n'est pas autorisée");
define("_WEBPHOTO_UPLOADER_ERR_PHP_OCCURED", "Une erreur est survenue : erreur #");
define("_WEBPHOTO_UPLOADER_ERR_NOT_OPEN_DIR", "Echec à l'ouverture du répertoire : ");
define("_WEBPHOTO_UPLOADER_ERR_NO_PERM_DIR", "Echerc à l'ouverture-écriture du répertoire : ");
define("_WEBPHOTO_UPLOADER_ERR_NOT_ALLOWED_MIME", "MIMETYPE non autorisé : ");
define("_WEBPHOTO_UPLOADER_ERR_LARGE_FILE_SIZE", "Le poids de l'image excède la limite : ");
define("_WEBPHOTO_UPLOADER_ERR_LARGE_WIDTH", "La largeur du fichier doit être inférieure à ");
define("_WEBPHOTO_UPLOADER_ERR_LARGE_HEIGHT", "La hauteur du fichier doit être inférieure à ");
define("_WEBPHOTO_UPLOADER_ERR_UPLOAD", "Echec lors du téléversement du fichier : ");

//---------------------------------------------------------
// help
//---------------------------------------------------------
define("_WEBPHOTO_HELP_DSC", "Aide / description du module");

define("_WEBPHOTO_HELP_PICLENS_TITLE", "PicLens");
define("_WEBPHOTO_HELP_PICLENS_DSC", '
Piclens est une extension firefox réalisée par Cooliris<br />
qui vous permet d\'installer une visionneuse de photo sur votre site<br /><br />
<b>Mise en place</b><br />
(1) Télécharger Firefox<br />
<a href="http://www.mozilla-japan.org/products/firefox/" target="_blank">
http://www.mozilla-japan.org/products/firefox/
</a><br /><br />
(2) Télécharger Piclens<br />
<a href="http://www.piclens.com/" target="_blank">
http://www.piclens.com/
</a><br /><br />
(3) Accéder au module Webphoto<br />
http://THIS-SITE/modules/webphoto/ <br /><br />
(4) Cliquer sur le bouton situé à l\extrémité droite du navigateur Firefox<br />
Note : lorsque l\'icône est noire, vous ne pouvez pas activer Piclens<br />' );

//
// dummy lines , adjusts line number for Japanese lang file.
//

define("_WEBPHOTO_HELP_MEDIARSSSLIDESHOW_TITLE", "Media RSS Slide Show");
define("_WEBPHOTO_HELP_MEDIARSSSLIDESHOW_DSC", '
"Media RSS Slide Show" est une Gadget Google qui fonctionne avec Google Desktop<br />
Il permet d\'afficher sur votre bureau un diaporama de vos galeries d\'images<br /><br />
<b>Mise en place</b><br />
(1) Télécharger "Google Desktop"<br />
<a href="http://desktop.google.co.jp/" target="_blank">
http://desktop.google.co.jp/
</a><br /><br />
(2) Télécharger le Gadget "Media RSS  Slide Show"<br />
<a href="http://desktop.google.com/plugins/i/mediarssslideshow.html" target="_blank">
http://desktop.google.com/plugins/i/mediarssslideshow.html
</a><br /><br />
(3) Modifier "URL of MediaRSS" dans les options du Gadget<br />' );

//---------------------------------------------------------
// others
//---------------------------------------------------------
define("_WEBPHOTO_RANDOM_MORE","Plus de photos aléatoires");
define("_WEBPHOTO_USAGE_PHOTO","Au clic sur la miniature, afficher dans une Popup la photo grand format");
define("_WEBPHOTO_USAGE_TITLE","Au clic sur le titre, déplacer la photo sur la page");
define("_WEBPHOTO_DATE_NOT_SET","Ne pas indiquer de date pour la photo");
define("_WEBPHOTO_PLACE_NOT_SET","Ne pas indiquer de localisation pour la phot");
define("_WEBPHOTO_GOTO_ADMIN", "Se rendre dans la zone d'administration");

//---------------------------------------------------------
// search for Japanese
//---------------------------------------------------------
define("_WEBPHOTO_SR_CANDICATE","Nomminé pour la recherche");
define("_WEBPHOTO_SR_ZENKAKU","Zenkaku");
define("_WEBPHOTO_SR_HANKAKU","Hanhaku");

define("_WEBPHOTO_JA_KUTEN",   "");
define("_WEBPHOTO_JA_DOKUTEN", "");
define("_WEBPHOTO_JA_PERIOD",  "");
define("_WEBPHOTO_JA_COMMA",   "");

//---------------------------------------------------------
// v0.20
//---------------------------------------------------------
define("_WEBPHOTO_TITLE_VIDEO_THUMB_SEL", "Sélectionner une miniature vidéo");
define("_WEBPHOTO_TITLE_VIDEO_REDO","Re-générer le Flash et la miniature à partir de la vidéo téléversée");
define("_WEBPHOTO_CAP_REDO_THUMB","Créer une miniature");
define("_WEBPHOTO_CAP_REDO_FLASH","Cretae Flash Video");
define("_WEBPHOTO_ERR_VIDEO_FLASH", "Impossible de créer une vidéo Flash");
define("_WEBPHOTO_ERR_VIDEO_THUMB", "La miniature ne pouvant être créée à partir de la vidéo, une icône de remplacement est utilisée");
define("_WEBPHOTO_BUTTON_SELECT", "Sélectionner");

define("_WEBPHOTO_DSC_DOWNLOAD_PLAY","Lire après le téléchargement");
define("_WEBPHOTO_ICON_VIDEO", "Vidéo");
define("_WEBPHOTO_HOUR", "heure");
define("_WEBPHOTO_MINUTE", "min.");
define("_WEBPHOTO_SECOND", "sec.");

//---------------------------------------------------------
// v0.30
//---------------------------------------------------------
// user table
define("_WEBPHOTO_USER_TABLE" , "Tableau des utilisateurs auxiliaires" ) ;
define("_WEBPHOTO_USER_ID" ,          "ID des utilisateurs auxiliaires" ) ;
define("_WEBPHOTO_USER_TIME_CREATE" , "Date de création" ) ;
define("_WEBPHOTO_USER_TIME_UPDATE" , "Date de mise à jour" ) ;
define("_WEBPHOTO_USER_UID" , "ID de l'utilisateur" ) ;
define("_WEBPHOTO_USER_CAT_ID" , "ID de la Catégorie" ) ;
define("_WEBPHOTO_USER_EMAIL" , "Adresse e-mail" ) ;
define("_WEBPHOTO_USER_TEXT1" ,  "texte1" ) ;
define("_WEBPHOTO_USER_TEXT2" ,  "texte2" ) ;
define("_WEBPHOTO_USER_TEXT3" ,  "texte3" ) ;
define("_WEBPHOTO_USER_TEXT4" ,  "texte4" ) ;
define("_WEBPHOTO_USER_TEXT5" ,  "texte5" ) ;

// maillog
define("_WEBPHOTO_MAILLOG_TABLE" , "Tableau d'enregistement des e-mails" ) ;
define("_WEBPHOTO_MAILLOG_ID" ,          "ID de l'enregistrement" ) ;
define("_WEBPHOTO_MAILLOG_TIME_CREATE" , "Date de création" ) ;
define("_WEBPHOTO_MAILLOG_TIME_UPDATE" , "Date de mise à jour" ) ;
define("_WEBPHOTO_MAILLOG_PHOTO_IDS" , "ID des photos" ) ;
define("_WEBPHOTO_MAILLOG_STATUS" , "Statut" ) ;
define("_WEBPHOTO_MAILLOG_FROM" , "Provenance de l'e-mail" ) ;
define("_WEBPHOTO_MAILLOG_SUBJECT" , "Sujet" ) ;
define("_WEBPHOTO_MAILLOG_BODY" ,  "Message" ) ;
define("_WEBPHOTO_MAILLOG_FILE" ,  "Patronyme" ) ;
define("_WEBPHOTO_MAILLOG_ATTACH" ,  "Fichiers joints" ) ;
define("_WEBPHOTO_MAILLOG_COMMENT" ,  "Commentaires" ) ;

// mail register
define("_WEBPHOTO_TITLE_MAIL_REGISTER" ,  "Enregistrement d'adresses e-mails" ) ;
define("_WEBPHOTO_MAIL_HELP" ,  "Merci de consulter la rubrique d'aide" ) ;
define("_WEBPHOTO_CAT_USER" ,  "Nom de l'utilisateur" ) ;
define("_WEBPHOTO_BUTTON_REGISTER" ,  "ENREGISTRER" ) ;
define("_WEBPHOTO_NOMATCH_USER","Aucun utilisateur ne correspond");
define("_WEBPHOTO_ERR_MAIL_EMPTY","Vous devez indiquer une adresse");
define("_WEBPHOTO_ERR_MAIL_ILLEGAL","Format d'e-mail incorrect");

// mail retrieve
define("_WEBPHOTO_TITLE_MAIL_RETRIEVE" ,  "Récupération d'e-mails" ) ;
define("_WEBPHOTO_DSC_MAIL_RETRIEVE" ,  "Récupérer une adresse e-mail depuis le serveur" ) ;
define("_WEBPHOTO_BUTTON_RETRIEVE" ,  "RECUPERER" ) ;
define("_WEBPHOTO_SUBTITLE_MAIL_ACCESS" ,  "Accéder au serveur d'e-mails" ) ;
define("_WEBPHOTO_SUBTITLE_MAIL_PARSE" ,  "Analyser les e-mails réceptionnés" ) ;
define("_WEBPHOTO_SUBTITLE_MAIL_PHOTO" ,  "Soumettre les photos attachés aux e-mails" ) ;
define("_WEBPHOTO_TEXT_MAIL_ACCESS_TIME" ,  "Limitation d'accès" ) ;
define("_WEBPHOTO_TEXT_MAIL_RETRY"  ,  "Accéder au bout d'une minute" ) ;
define("_WEBPHOTO_TEXT_MAIL_NOT_RETRIEVE" ,  "Impossible de récupérer les e-mails.<br />La communication est probablement interrompue provisoirement.<br />Merci de patienter un moment avant de tenter une nouvelle récupération" ) ;
define("_WEBPHOTO_TEXT_MAIL_NO_NEW" ,  "Aucun nouvel e-mail trouvé" ) ;
define("_WEBPHOTO_TEXT_MAIL_RETRIEVED_FMT" ,  "%s e-mails récupérés" ) ;
define("_WEBPHOTO_TEXT_MAIL_NO_VALID" ,  "Aucun e-mail valide" ) ;
define("_WEBPHOTO_TEXT_MAIL_SUBMITED_FMT" ,  "%s photos proposées" ) ;
define("_WEBPHOTO_GOTO_INDEX" ,  "Se rendre en page d'accueil du module" ) ;

// i.php
define("_WEBPHOTO_TITLE_MAIL_POST" ,  "Envoyer par e-mail" ) ;

// file
define("_WEBPHOTO_TITLE_SUBMIT_FILE" , "Ajouter une photo depuis un fichier" ) ;
define("_WEBPHOTO_CAP_FILE_SELECT", "Sélectionner un fichier");
define("_WEBPHOTO_ERR_EMPTY_FILE" , "Vous devez sélectionner un fichier" ) ;
define("_WEBPHOTO_ERR_EMPTY_CAT" , "Vous devez sélectionner une Catégorie" ) ;
define("_WEBPHOTO_ERR_INVALID_CAT" , "Catégorie non valide" ) ;
define("_WEBPHOTO_ERR_CREATE_PHOTO" , "Impossible de créer la photto" ) ;
define("_WEBPHOTO_ERR_CREATE_THUMB" , "Impossible de créer la miniature" ) ;

// help
define("_WEBPHOTO_HELP_MUST_LOGIN","Merci de bien vouloir vous connecter si vous voulez accéder à davantage de détails");
define("_WEBPHOTO_HELP_NOT_PERM", "Les permissions requises ne vous ont pas été accordées. Veuillez contacter le webmestre");

define("_WEBPHOTO_HELP_MOBILE_TITLE", "Téléphone modile");
define("_WEBPHOTO_HELP_MOBILE_DSC", "Vous pouvez consulter la photo et la vidéo sur votre téléphone mobile<br/>Les dimensions de l'écran doivent être de 240x320 ");
define("_WEBPHOTO_HELP_MOBILE_TEXT_FMT", '
<b>URL d\'accès</b><br />
<a href="{MODULE_URL}/i.php" rel="external">{MODULE_URL}/i.php</a>');

define("_WEBPHOTO_HELP_MAIL_TITLE", "E-mail via mobile");
define("_WEBPHOTO_HELP_MAIL_DSC", "Vous pouvez transmettre vos photo et vos vidéos par e-mail via votre téléphone mobile");
define("_WEBPHOTO_HELP_MAIL_GUEST", "Ceci est un exemple. Si vous disposez des droits requis, vous pouvez accéder à la véritable adresse e-mail.");

define("_WEBPHOTO_HELP_FILE_TITLE", "Transmission par FTP");
define("_WEBPHOTO_HELP_FILE_DSC", "Les transferts par FTP vous permettent de téléverser des photos et des vidéos de grandes tailles");
define("_WEBPHOTO_HELP_FILE_TEXT_FMT", '
<b>Envoyer un fichier</b><br />
(1) Téléverser le fichier sur le serveur via FTP<br />
(2) Cliquer sur <a href="{MODULE_URL}/index.php?fct=submit_file" rel="external">Ajouter une photo depuis le fichier</a><br />
(3) Sélectionner le fichier téléversé et Valider' );

// mail check
// for Japanese
define("_WEBPHOTO_MAIL_DENY_TITLE_PREG", "" ) ;
define("_WEBPHOTO_MAIL_AD_WORD_1", "" ) ;
define("_WEBPHOTO_MAIL_AD_WORD_2", "" ) ;

//---------------------------------------------------------
// v0.40
//---------------------------------------------------------
// item table
define("_WEBPHOTO_ITEM_TABLE" , "Tableau des ressources" ) ;
define("_WEBPHOTO_ITEM_ID" , "ID de l'élément" ) ;
define("_WEBPHOTO_ITEM_TIME_CREATE" , "Date de création" ) ;
define("_WEBPHOTO_ITEM_TIME_UPDATE" , "Date de mise à jour" ) ;
define("_WEBPHOTO_ITEM_CAT_ID" ,  "ID de la Catégorie" ) ;
define("_WEBPHOTO_ITEM_GICON_ID" , "ID de l'icône Googlemap" ) ;
define("_WEBPHOTO_ITEM_UID" ,   "ID de l'utilisateur" ) ;
define("_WEBPHOTO_ITEM_KIND" , "Type de ressource" ) ;
define("_WEBPHOTO_ITEM_EXT" ,  "Extension du fichier" ) ;
define("_WEBPHOTO_ITEM_DATETIME" ,  "Date de la photo" ) ;
define("_WEBPHOTO_ITEM_TITLE" , "Titre photo" ) ;
define("_WEBPHOTO_ITEM_PLACE" , "Localisation" ) ;
define("_WEBPHOTO_ITEM_EQUIPMENT" , "Equipment" ) ;
define("_WEBPHOTO_ITEM_GMAP_LATITUDE" ,  "Latitude Googlemap" ) ;
define("_WEBPHOTO_ITEM_GMAP_LONGITUDE" , "Longitude Googlemap" ) ;
define("_WEBPHOTO_ITEM_GMAP_ZOOM" ,      "Pourcentage du zoom" ) ;
define("_WEBPHOTO_ITEM_GMAP_TYPE" ,      "Type de carte" ) ;
define("_WEBPHOTO_ITEM_PERM_READ" , "Permission de consulter" ) ;
define("_WEBPHOTO_ITEM_STATUS" ,   "Statut" ) ;
define("_WEBPHOTO_ITEM_HITS" ,     "Affichages" ) ;
define("_WEBPHOTO_ITEM_RATING" ,   "Notes" ) ;
define("_WEBPHOTO_ITEM_VOTES" ,    "Votes" ) ;
define("_WEBPHOTO_ITEM_DESCRIPTION" ,  "Description de la photo" ) ;
define("_WEBPHOTO_ITEM_EXIF" , "Informations Exif" ) ;
define("_WEBPHOTO_ITEM_SEARCH" ,  "Rechercher" ) ;
define("_WEBPHOTO_ITEM_COMMENTS" , "Commentaires" ) ;
define("_WEBPHOTO_ITEM_FILE_ID_1" ,  "ID du fichier: Contenu" ) ;
define("_WEBPHOTO_ITEM_FILE_ID_2" ,  "ID du fichier: Miniature" ) ;
define("_WEBPHOTO_ITEM_FILE_ID_3" ,  "ID du fichier: MIddle" ) ;
define("_WEBPHOTO_ITEM_FILE_ID_4" ,  "ID du fichier: Vidéo Flash" ) ;
define("_WEBPHOTO_ITEM_FILE_ID_5" ,  "ID du fichier: Vidéo Docomo" ) ;
define("_WEBPHOTO_ITEM_FILE_ID_6" ,  "ID du fichier: PDF" ) ;
define("_WEBPHOTO_ITEM_FILE_ID_7" ,  "ID du fichier: Flash au format swf" ) ;
define("_WEBPHOTO_ITEM_FILE_ID_8" ,  "fichier8" ) ;
define("_WEBPHOTO_ITEM_FILE_ID_9" ,  "fichier9" ) ;
define("_WEBPHOTO_ITEM_FILE_ID_10" , "fichier10" ) ;
define("_WEBPHOTO_ITEM_TEXT_1" ,  "texte1" ) ;
define("_WEBPHOTO_ITEM_TEXT_2" ,  "texte2" ) ;
define("_WEBPHOTO_ITEM_TEXT_3" ,  "texte3" ) ;
define("_WEBPHOTO_ITEM_TEXT_4" ,  "texte4" ) ;
define("_WEBPHOTO_ITEM_TEXT_5" ,  "texte5" ) ;
define("_WEBPHOTO_ITEM_TEXT_6" ,  "texte6" ) ;
define("_WEBPHOTO_ITEM_TEXT_7" ,  "texte7" ) ;
define("_WEBPHOTO_ITEM_TEXT_8" ,  "texte8" ) ;
define("_WEBPHOTO_ITEM_TEXT_9" ,  "texte9" ) ;
define("_WEBPHOTO_ITEM_TEXT_10" , "texte10" ) ;

// file table
define("_WEBPHOTO_FILE_TABLE" , "Tableau des fichiers" ) ;
define("_WEBPHOTO_FILE_ID" , "ID du fichier" ) ;
define("_WEBPHOTO_FILE_TIME_CREATE" , "Date de création" ) ;
define("_WEBPHOTO_FILE_TIME_UPDATE" , "Date de mise à jour" ) ;
define("_WEBPHOTO_FILE_ITEM_ID" ,  "ID de l'élément" ) ;
define("_WEBPHOTO_FILE_KIND" , "Type de fichier" ) ;
define("_WEBPHOTO_FILE_URL" ,    "URL" ) ;
define("_WEBPHOTO_FILE_PATH" ,   "Chemin d'accès au fichier" ) ;
define("_WEBPHOTO_FILE_NAME" ,   "Nom du fichier" ) ;
define("_WEBPHOTO_FILE_EXT" ,    "Extension du fichier" ) ;
define("_WEBPHOTO_FILE_MIME" ,   "MIME type" ) ;
define("_WEBPHOTO_FILE_MEDIUM" , "Type de format" ) ;
define("_WEBPHOTO_FILE_SIZE" ,   "Taille du fichier" ) ;
define("_WEBPHOTO_FILE_WIDTH" ,  "Largeur de l'image" ) ;
define("_WEBPHOTO_FILE_HEIGHT" , "Hauteur de l'image" ) ;
define("_WEBPHOTO_FILE_DURATION" , "Durée de la vidéo" ) ;

// file kind ( for admin checktables )
define("_WEBPHOTO_FILE_KIND_1" ,  "Contenu" ) ;
define("_WEBPHOTO_FILE_KIND_2" ,  "Miniature" ) ;
define("_WEBPHOTO_FILE_KIND_3" ,  "MIddle" ) ;
define("_WEBPHOTO_FILE_KIND_4" ,  "Vidéo Flash" ) ;
define("_WEBPHOTO_FILE_KIND_5" ,  "Vidéo Docomo" ) ;
define("_WEBPHOTO_FILE_KIND_6" ,  "PDF" ) ;
define("_WEBPHOTO_FILE_KIND_7" ,  "Flash au format swf" ) ;
define("_WEBPHOTO_FILE_KIND_8" ,  "fichier8" ) ;
define("_WEBPHOTO_FILE_KIND_9" ,  "fichier9" ) ;
define("_WEBPHOTO_FILE_KIND_10" , "fichier10" ) ;

// index
define("_WEBPHOTO_MOBILE_MAILTO" , "Transmettre l'URL sur téléphone mobile" ) ;

// i.php
define("_WEBPHOTO_TITLE_MAIL_JUDGE" ,  "Evaluer l'Opérateur mobile" ) ;
define("_WEBPHOTO_MAIL_MODEL", "Opérateur mobile" ) ;
define("_WEBPHOTO_MAIL_BROWSER", "Navigateur" ) ;
define("_WEBPHOTO_MAIL_NOT_JUDGE", "Impossible d'évaluer l'Opérateur mobile" ) ;
define("_WEBPHOTO_MAIL_TO_WEBMASTER", "E-mail pour le webmestre" ) ;

// help
define("_WEBPHOTO_HELP_MAIL_POST_FMT", '
<b>Préparation</b><br />
Enregistrez votre adresse e-mail liée à votre téléphone mobile<br />
<a href="{MODULE_URL}/index.php?fct=mail_register" rel="external">Enregistrer l\'adresse e-mail</a><br /><br />
<b>Envoyer une photo</b><br />
Transmettre l\'e-mail à l\'adresse suivante avec la photo en pièce jointe.<br />
<a href="mailto:{MAIL_ADDR}">{MAIL_ADDR}</a> {MAIL_GUEST} <br /><br />
<b>Faire pivoter les photos</b><br />
Vous pouvez faire pivoter les photos vers la droite et vers la gauche en précisant à la fin du Sujet de l\'e-mail<br />
 R@ : pivoter vers la droite <br />
 L@ : pivoter vers la gauche<br /><br />' );
define("_WEBPHOTO_HELP_MAIL_SUBTITLE_RETRIEVE", "<b>Récupérer les e-mails et leurs documents joints</b><br />" );
define("_WEBPHOTO_HELP_MAIL_RETRIEVE_FMT", '
Cliquer sur <a href="{MODULE_URL}/i.php?op=post" rel="external">Envois par e-mail</a>, quelques secondes après la transmission de l\'e-mail.<br />
Webphoto va récupérer l\'e-mail que vous avez envoyé et afficher la photo proposée<br />' );
define("_WEBPHOTO_HELP_MAIL_RETRIEVE_TEXT", "Webphoto récupère le mail que vous avez envoyé et affiche la photo transmise en pièce jointe<br />" );
define("_WEBPHOTO_HELP_MAIL_RETRIEVE_AUTO_FMT", '
L\'e-mail est traité automatiquement %s secondes après la transmission de l\'e-mail.<br />
Merci de cliquer <a href="{MODULE_URL}/i.php?op=post" rel="external">Evoyer par e-mail</a>, si l\'e-mail n\'est pas traité.<br />' );

//---------------------------------------------------------
// v0.50
//---------------------------------------------------------
// item table
define("_WEBPHOTO_ITEM_TIME_PUBLISH" , "Date de publication" ) ;
define("_WEBPHOTO_ITEM_TIME_EXPIRE"  , "Date de suspension" ) ;
define("_WEBPHOTO_ITEM_PLAYER_ID" ,    "ID du lecteur" ) ;
define("_WEBPHOTO_ITEM_FLASHVAR_ID" ,  "ID de la variable FlashVar" ) ;
define("_WEBPHOTO_ITEM_DURATION" , "Durée de la vidéo" ) ;
define("_WEBPHOTO_ITEM_DISPLAYTYPE", "Type d'affichage");
define("_WEBPHOTO_ITEM_ONCLICK","Action au clic sur la miniature");
define("_WEBPHOTO_ITEM_SITEURL", "URL du site");
define("_WEBPHOTO_ITEM_ARTIST", "Artiste");
define("_WEBPHOTO_ITEM_ALBUM", "Album");
define("_WEBPHOTO_ITEM_LABEL", "Label");
define("_WEBPHOTO_ITEM_VIEWS", "Affichages");
define("_WEBPHOTO_ITEM_PERM_DOWN" , "Përmission de télécharger" ) ;
define("_WEBPHOTO_ITEM_EMBED_TYPE" ,  "Type de Plugin" ) ;
define("_WEBPHOTO_ITEM_EMBED_SRC" ,   "URL de paramétrage du Plugin" ) ;
define("_WEBPHOTO_ITEM_EXTERNAL_URL" , "URL extérieure" ) ;
define("_WEBPHOTO_ITEM_EXTERNAL_THUMB" , "URL extérieure pour la miniature" ) ;
define("_WEBPHOTO_ITEM_PLAYLIST_TYPE",  "Type de Playlist" ) ;
define("_WEBPHOTO_ITEM_PLAYLIST_FEED",  "URL du flux de la Playlist" ) ;
define("_WEBPHOTO_ITEM_PLAYLIST_DIR",   "Répertoire de la Playlist" ) ;
define("_WEBPHOTO_ITEM_PLAYLIST_CACHE", "Nom du cache de la Playlist" ) ;
define("_WEBPHOTO_ITEM_PLAYLIST_TIME",  "Délais de mise en cache de la Playlist" ) ;
define("_WEBPHOTO_ITEM_CHAIN", "Chaîne");
define("_WEBPHOTO_ITEM_SHOWINFO", "Afficher les informations");

// player table
define("_WEBPHOTO_PLAYER_TABLE","Tableau de lecteurs vidéo");
define("_WEBPHOTO_PLAYER_ID","ID du lecteur");
define("_WEBPHOTO_PLAYER_TIME_CREATE" , "Date de création" ) ;
define("_WEBPHOTO_PLAYER_TIME_UPDATE" , "Date de mise à jour" ) ;
define("_WEBPHOTO_PLAYER_TITLE","Nom du lecteur");
define("_WEBPHOTO_PLAYER_STYLE","Apparence");
define("_WEBPHOTO_PLAYER_WIDTH","Largeur du lecteur");
define("_WEBPHOTO_PLAYER_HEIGHT","Hauteur du lecteur");
define("_WEBPHOTO_PLAYER_DISPLAYWIDTH","Afficher la largeur");
define("_WEBPHOTO_PLAYER_DISPLAYHEIGHT","Afficher la hauteur");
define("_WEBPHOTO_PLAYER_SCREENCOLOR","Couleur de l'écran");
define("_WEBPHOTO_PLAYER_BACKCOLOR","Couleur de fond");
define("_WEBPHOTO_PLAYER_FRONTCOLOR","Couleur de premier plan");
define("_WEBPHOTO_PLAYER_LIGHTCOLOR","Couleur de l'éclairage");

// FlashVar table
define("_WEBPHOTO_FLASHVAR_TABLE","Tableau de FlashVar");
define("_WEBPHOTO_FLASHVAR_ID","ID de FlashVar");
define("_WEBPHOTO_FLASHVAR_TIME_CREATE" , "Date de création" ) ;
define("_WEBPHOTO_FLASHVAR_TIME_UPDATE" , "Date de mise à jour" ) ;
define("_WEBPHOTO_FLASHVAR_ITEM_ID","ID de l'élément");
define("_WEBPHOTO_FLASHVAR_WIDTH","Largur du lecteur");
define("_WEBPHOTO_FLASHVAR_HEIGHT","Hauteur du lecteur");
define("_WEBPHOTO_FLASHVAR_DISPLAYWIDTH","Afficher la largeur");
define("_WEBPHOTO_FLASHVAR_DISPLAYHEIGHT","Afficher la hauteur");
define("_WEBPHOTO_FLASHVAR_IMAGE_SHOW","Afficher l'image");
define("_WEBPHOTO_FLASHVAR_SEARCHBAR","Barre de recherche");
define("_WEBPHOTO_FLASHVAR_SHOWEQ","Afficher les égaliseurs");
define("_WEBPHOTO_FLASHVAR_SHOWICONS","Icônes d'activité");
define("_WEBPHOTO_FLASHVAR_SHOWNAVIGATION","Afficher la navigation");
define("_WEBPHOTO_FLASHVAR_SHOWSTOP","Afficher la fonction Stop");
define("_WEBPHOTO_FLASHVAR_SHOWDIGITS","Afficher le compteur");
define("_WEBPHOTO_FLASHVAR_SHOWDOWNLOAD","Afficher la fonction télécharger");
define("_WEBPHOTO_FLASHVAR_USEFULLSCREEN","Afficher le bouton plein écran");
define("_WEBPHOTO_FLASHVAR_AUTOSCROLL","Barre de défilement inactive");
define("_WEBPHOTO_FLASHVAR_THUMBSINPLAYLIST","Miniatures");
define("_WEBPHOTO_FLASHVAR_AUTOSTART","Démarrage automatique de la lecture");
define("_WEBPHOTO_FLASHVAR_REPEAT","Fonction Répéter");
define("_WEBPHOTO_FLASHVAR_SHUFFLE","Fonction Lecture léatoire");
define("_WEBPHOTO_FLASHVAR_SMOOTHING","Fonction Atténuation");
define("_WEBPHOTO_FLASHVAR_ENABLEJS","Activer le javascript");
define("_WEBPHOTO_FLASHVAR_LINKFROMDISPLAY","Lien depuis Afficher");
define("_WEBPHOTO_FLASHVAR_LINK_TYPE","Hyperliens sur l'écran");
define("_WEBPHOTO_FLASHVAR_BUFFERLENGTH","Longueur du tampon");
define("_WEBPHOTO_FLASHVAR_ROTATETIME","Durée de rotation de l'image");
define("_WEBPHOTO_FLASHVAR_VOLUME","volume");
define("_WEBPHOTO_FLASHVAR_LINKTARGET","Cible du lien");
define("_WEBPHOTO_FLASHVAR_OVERSTRETCH","Elasticité de l'image / vidéo");
define("_WEBPHOTO_FLASHVAR_TRANSITION","Transition du diaporama");
define("_WEBPHOTO_FLASHVAR_SCREENCOLOR","Couleur de l'écran");
define("_WEBPHOTO_FLASHVAR_BACKCOLOR","Couleur de fond");
define("_WEBPHOTO_FLASHVAR_FRONTCOLOR","Couleur du premier plan");
define("_WEBPHOTO_FLASHVAR_LIGHTCOLOR","Couleur de l'éclairage");
define("_WEBPHOTO_FLASHVAR_TYPE","Type");
define("_WEBPHOTO_FLASHVAR_FILE","Fichier media");
define("_WEBPHOTO_FLASHVAR_IMAGE","Prévisualiser l'image");
define("_WEBPHOTO_FLASHVAR_LOGO","Logo associé au lecteur");
define("_WEBPHOTO_FLASHVAR_LINK","Liens sur l'écran");
define("_WEBPHOTO_FLASHVAR_AUDIO","Programme automatique");
define("_WEBPHOTO_FLASHVAR_CAPTIONS","Légendes des URL");
define("_WEBPHOTO_FLASHVAR_FALLBACK","URL de retour");
define("_WEBPHOTO_FLASHVAR_CALLBACK","URL de rappel");
define("_WEBPHOTO_FLASHVAR_JAVASCRIPTID","ID du javascript");
define("_WEBPHOTO_FLASHVAR_RECOMMENDATIONS","Recommandations");
define("_WEBPHOTO_FLASHVAR_STREAMSCRIPT","URL de diffusion du script");
define("_WEBPHOTO_FLASHVAR_SEARCHLINK","Lien de recherche");

// log file
define("_WEBPHOTO_LOGFILE_LINE","Ligne");
define("_WEBPHOTO_LOGFILE_DATE","Date");
define("_WEBPHOTO_LOGFILE_REFERER","Lien référent");
define("_WEBPHOTO_LOGFILE_IP","Adresse IP");
define("_WEBPHOTO_LOGFILE_STATE","Etat");
define("_WEBPHOTO_LOGFILE_ID","ID");
define("_WEBPHOTO_LOGFILE_TITLE","Titre");
define("_WEBPHOTO_LOGFILE_FILE","Fichier");
define("_WEBPHOTO_LOGFILE_DURATION","Durée");

// item option
define("_WEBPHOTO_ITEM_KIND_UNDEFINED","Indéfini");
define("_WEBPHOTO_ITEM_KIND_NONE","Aucun média");
define("_WEBPHOTO_ITEM_KIND_GENERAL","General");
define("_WEBPHOTO_ITEM_KIND_IMAGE","Image (jpg,gif,png)");
define("_WEBPHOTO_ITEM_KIND_VIDEO","Vidéo (wmv,mov,flv...");
define("_WEBPHOTO_ITEM_KIND_AUDIO","Audio (mp3...)");
define("_WEBPHOTO_ITEM_KIND_EMBED","Plugin EMBED");
define("_WEBPHOTO_ITEM_KIND_EXTERNAL_GENERAL","Externe (général)");
define("_WEBPHOTO_ITEM_KIND_EXTERNAL_IMAGE","Externe (image)");
define("_WEBPHOTO_ITEM_KIND_PLAYLIST_FEED","Flux de la playlist");
define("_WEBPHOTO_ITEM_KIND_PLAYLIST_DIR", "Répertoire de la playlist");

define("_WEBPHOTO_ITEM_DISPLAYTYPE_GENERAL","Général");
define("_WEBPHOTO_ITEM_DISPLAYTYPE_IMAGE","Image (jpg,gif,png)");
define("_WEBPHOTO_ITEM_DISPLAYTYPE_EMBED","Plugin EMBED");
define("_WEBPHOTO_ITEM_DISPLAYTYPE_SWFOBJECT","FlashPlayer (swf)");
define("_WEBPHOTO_ITEM_DISPLAYTYPE_MEDIAPLAYER","MediaPlayer (jpg,gif,png,flv,mp3)");
define("_WEBPHOTO_ITEM_DISPLAYTYPE_IMAGEROTATOR","ImageRotator (jpg,gif,png)");

define("_WEBPHOTO_ITEM_ONCLICK_PAGE","Page de détail");
define("_WEBPHOTO_ITEM_ONCLICK_DIRECT","Lien direct");
define("_WEBPHOTO_ITEM_ONCLICK_POPUP","Image Popup");

define("_WEBPHOTO_ITEM_PLAYLIST_TYPE_DSC","Quel est le type de ressource ?");
define("_WEBPHOTO_ITEM_PLAYLIST_TYPE_NONE","Aucun");
define("_WEBPHOTO_ITEM_PLAYLIST_TYPE_IMAGE","Image (jpg,gif,png)");
define("_WEBPHOTO_ITEM_PLAYLIST_TYPE_AUDIO","Audio (mp3)");
define("_WEBPHOTO_ITEM_PLAYLIST_TYPE_VIDEO","Vidéo (flv)");
define("_WEBPHOTO_ITEM_PLAYLIST_TYPE_FLASH","Flash (swf)");

define("_WEBPHOTO_ITEM_SHOWINFO_DESCRIPTION","Description");
define("_WEBPHOTO_ITEM_SHOWINFO_LOGOIMAGE","Miniature");
define("_WEBPHOTO_ITEM_SHOWINFO_CREDITS","Crédits");
define("_WEBPHOTO_ITEM_SHOWINFO_STATISTICS","Statistiques");
define("_WEBPHOTO_ITEM_SHOWINFO_SUBMITTER","Proposé par");
define("_WEBPHOTO_ITEM_SHOWINFO_POPUP","PopUp");
define("_WEBPHOTO_ITEM_SHOWINFO_TAGS","Tags");
define("_WEBPHOTO_ITEM_SHOWINFO_DOWNLOAD","Télécharger");
define("_WEBPHOTO_ITEM_SHOWINFO_WEBSITE","Site");
define("_WEBPHOTO_ITEM_SHOWINFO_WEBFEED","Flux");

define("_WEBPHOTO_ITEM_STATUS_WAITING","En attente d'approbation");
define("_WEBPHOTO_ITEM_STATUS_APPROVED","Approuvé");
define("_WEBPHOTO_ITEM_STATUS_UPDATED","En ligne (mis à jour)");
define("_WEBPHOTO_ITEM_STATUS_OFFLINE","Désactivé");
define("_WEBPHOTO_ITEM_STATUS_EXPIRED","Expiré");

// player option
define("_WEBPHOTO_PLAYER_STYLE_MONO","Monochrome");
define("_WEBPHOTO_PLAYER_STYLE_THEME","Couleur du thème");
define("_WEBPHOTO_PLAYER_STYLE_PLAYER","Lecteur personnalisé");
define("_WEBPHOTO_PLAYER_STYLE_PAGE","Lecteur / page personnalisé");

// flashvar desc
define("_WEBPHOTO_FLASHVAR_ID_DSC","[Paramètres de base] <br />A utiliser pour indiquer l'idebntifiant de diffusion RTMP du lecteur.<br />L'ID sera également utilisé pour les statistiques.<br />Si vous lisez une playlist, vous pouvez indiquer un ID pour chaque élément. ");
define("_WEBPHOTO_FLASHVAR_HEIGHT_DSC","[Paramètres de base] ");
define("_WEBPHOTO_FLASHVAR_WIDTH_DSC","[Paramètres de base]  ");
define("_WEBPHOTO_FLASHVAR_DISPLAYHEIGHT_DSC","[Playlist] [mediaplayer] <br />Paramétrer une dimension inférieure à la hauteur afin de voir la playlist. <br />Si vous conservez une valeur identique à la hauteur, les commandes du lecteur seront automatiquement masquées au sommet de la vidéo. ");
define("_WEBPHOTO_FLASHVAR_DISPLAYWIDTH_DSC","[Playlist] [mediaplayer] <br />Pistes du bas :<br /> Ecran = lecteur<br /> Pistes latérales :<br />Ecran < au lecteur ");
define("_WEBPHOTO_FLASHVAR_DISPLAY_DEFAULT","si la valeur indiquée est 0, celle du lecteur sera utilisée.");
define("_WEBPHOTO_FLASHVAR_SCREENCOLOR_DSC","[Couleurs] <br />[imagerotator] Modifier ce paramètre couleur dans le code HTML de votre page permet d'améliorer la mise en page lorsque les images sont de tailles différentes. ");
define("_WEBPHOTO_FLASHVAR_BACKCOLOR_DSC","[Couleurs] <br />Couleur de fond des commandes");
define("_WEBPHOTO_FLASHVAR_FRONTCOLOR_DSC","[Couleurs] <br />Couleur du texte des commandes");
define("_WEBPHOTO_FLASHVAR_LIGHTCOLOR_DSC","[Couleurs] <br />Couleur de survol des commandes");
define("_WEBPHOTO_FLASHVAR_COLOR_DEFAULT","Si aucune valeur n'est indiquée, celle du lecteur sera utilisée.");
define("_WEBPHOTO_FLASHVAR_SEARCHBAR_DSC","(Paramètres de base) <br />Sélectionner False pour masquer la barre de recherche sous le lecteur. <br />Vous pouvez indiquer le lien de la page de résultat en utilisant le lien de recherhce du FlashVar. ");
define("_WEBPHOTO_FLASHVAR_IMAGE_SHOW_DSC","(Paramètres de base) <br />True = l'image de prévisualisation s'affiche");
define("_WEBPHOTO_FLASHVAR_IMAGE_DSC","(Paramètres de base) <br />Si vous lisez un son ou une vidéo, indiquez l'url de l'image de prévisualisation. <br />Si vous lisez une playlist, vous pouvez indiquer une image pour chaque élément. ");
define("_WEBPHOTO_FLASHVAR_FILE_DSC","(Paramètres de base) <br />Indiquer l'emplacement du fichier ou de la playlist à lire. <br />L'Imagerotator ne reconnaît que les playlists. ");
define("_WEBPHOTO_FLASHVAR_LOGO_DSC","(Affichage) <br />Indiquer une image qui pourra être employée comme filigrane (visible en haut à droite). <br />Pour un meilleur rendu, ne image png transparente est recommandée. ");
define("_WEBPHOTO_FLASHVAR_OVERSTRETCH_DSC","(Affichage) <br />Indiquer comment éleragir l'image ou la vidéo dans la zone de lecture. <br />false (par défaut) = la zone de lecture est entièrement utilisée. <br />true = la zone de lecture est utilisée en conservant les proportions du média. <br />fit = élargissement du média stretch sans conserver les proportions<br />none = les dimensions originales sont conservées. ");
define("_WEBPHOTO_FLASHVAR_SHOWEQ_DSC","(Affichage) <br />Sélectionner true pour afficher un égaliseur (fictif) au pied de la zone de lecture. <br />Recommandé pour la lecture de fichiers MP3. ");
define("_WEBPHOTO_FLASHVAR_SHOWICONS_DSC","(Affichage) <br />Sélectionner false afin de masquer l'icône d'activité et le bouton de lecture situé au centre de la zone de lecture. ");
define("_WEBPHOTO_FLASHVAR_TRANSITION_DSC","(Affichage) [Imagerotator] <br />Sélectionner la transition à utiliser entre les images. ");
define("_WEBPHOTO_FLASHVAR_SHOWNAVIGATION_DSC","[Barre de contrôle] <br />Sélectionner false to completely hide the controlbar. ");
define("_WEBPHOTO_FLASHVAR_SHOWSTOP_DSC","[Barre de contrôle] [mediaplayer] <br />Sélectionner true pour afficher le bouton Stop dans la barre de contrôle. ");
define("_WEBPHOTO_FLASHVAR_SHOWDIGITS_DSC","[Barre de contrôle] [mediaplayer] <br />Sélectionner false pour masquer le compteur de lecture dans la barre de contrôle. ");
define("_WEBPHOTO_FLASHVAR_SHOWDOWNLOAD_DSC","[Barre de contrôle] [mediaplayer] <br />Sélectionner true pour afficher dans la barre de contrôle le boutton permettant de relier le media à FlashVar. ");
define("_WEBPHOTO_FLASHVAR_USEFULLSCREEN_DSC","[Barre de contrôle] <br />Sélectionner false pour masquer le bouton Plein écran (le mode lecture plein écran sera également désactivé). ");
define("_WEBPHOTO_FLASHVAR_AUTOSCROLL_DSC","[Playlist] [mediaplayer] <br />Sélectionner true  pour pouvoir faire défiler verticalement le média sans recourir aux ascenseurs. ");
define("_WEBPHOTO_FLASHVAR_THUMBSINPLAYLIST_DSC","[Playlist] [mediaplayer] <br />Sélectionner false pour masquer la prévisualisation dans la zone de lecture");
define("_WEBPHOTO_FLASHVAR_AUDIO_DSC","(Lecture) <br />Assigne un MP3 supplémentaire synchrone. <br />A utiliser pour achever une description audio, un commentaire réalisé sur fond sonore ou encore une musique associée à Imagerotator. <br />When using the mediaplayer and a playlist, you can assign audio to every entry. ");
define("_WEBPHOTO_FLASHVAR_AUTOSTART_DSC","(Lecture) <br />Sélectionner true pour lancer le média dès son chargement<br />ou sélectionner false lorsque le média est associé à Imagerotator (afin d'empêcher sa rotation automatique).");
define("_WEBPHOTO_FLASHVAR_BUFFERLENGTH_DSC","(Lecture)  [mediaplayer] <br />Indiquer le délais (n secondes) durant lequel la vidéo devra être mise en mémoire tampon avant d'être lue.<br />Indiquer une valeur réduite pour les connections rapides ou pour les vidéos de courte durée.  ");
define("_WEBPHOTO_FLASHVAR_CAPTIONS_DSC","(Lecture) [mediaplayer] <br />Les légendes devraient être au format Timedtext. <br />Lorsque vous utiliser un lecteur, vous pouvez indiquer une légende pour chacune des ressource lue. ");
define("_WEBPHOTO_FLASHVAR_FALLBACK_DSC","(Lecture) [mediaplayer] <br />Si vous lisez un fichier MP4, indiquer l'emplacement d'un FLV. <br />Il sera automatiquement employé par un lecteur flash, qui devient alors compatible. ");
define("_WEBPHOTO_FLASHVAR_REPEAT_DSC","(Lecture) <br />Sélectionner true pour déclencher automatiquement la relecture de tous les fichiers. <br />A utiliser pour lire à nouveau en intégralité une playlist. ");
define("_WEBPHOTO_FLASHVAR_ROTATETIME_DSC","(Lecture) <br />Indiquer le nombre de secondes durant lesquelle l'image sera lue. ");
define("_WEBPHOTO_FLASHVAR_SHUFFLE_DSC","(Lecture) <br />Sélectionner true pour lire les élements d'une playlist au hasard. ");
define("_WEBPHOTO_FLASHVAR_SMOOTHING_DSC","(Lecture) [mediaplayer] <br />Sélectionner false pour désactiver la fonction de lissage de la vidéo. <br />La qualité de la vidéo sera réduite mais les performances meilleures. <br />Ce paramètre est recommandé pour les films en haute résolution ou pour les ordinateurs anciens ");
define("_WEBPHOTO_FLASHVAR_VOLUME_DSC","(Lecture) <br />Paramètre le volume initial de la lecture (sons, vidéos et fichiers musicaux). ");
define("_WEBPHOTO_FLASHVAR_ENABLEJS_DSC","(Intéraction web)<br />Sélectionner true pour autoriser l'intéraction javascript. <br />Ce paramètre ne peut fonctionner qu'avec une connection<br />L'intéraction javascript inclut le commandes de lecture, le chargement asynchrone des médias et l'affichage des informations relatives au média. ");
define("_WEBPHOTO_FLASHVAR_JAVASCRIPTID_DSC","(Intéraction web)<br />Si vous mettez en relation plusieurs lecteurs / diaporamas en javascript, utiliser le Flashvar pour les singulariser avec un ID propre. ");
define("_WEBPHOTO_FLASHVAR_LINK_TYPE_DSC","(Intéraction web)<br />Ce lien est assigné à l'affichage, au logo et au bouton Lien. <br >Lorsqu'aucune information n'est saisie, aucun lien n'est assigné. <br />Si une valeur est précisée, alors une page sera accessible. ");

//define("_WEBPHOTO_FLASHVAR_LINK_DSC","(Intéraction web)<br />Set this to an external URL or downloadeable version of the file. <br />This link is assigned to the display, logo and link button. <br />With playlists, set links for every entry in the XML. ");

define("_WEBPHOTO_FLASHVAR_LINKFROMDISPLAY_DSC","(Intéraction web)<br />Sélectionner true pour que le clic sur le média redirige sur la page assignée par Flashvar. ");
define("_WEBPHOTO_FLASHVAR_LINKTARGET_DSC","(Intéraction web)<br />Indiquer la destination (frame) dans laquelle vous souhaitez voir s'ouvrir le lien. <br />Indiquer _blank pour ouvrir dans une nouvelle fenêtre ou _top pour ouvrir dans la fenêtre active. ");
define("_WEBPHOTO_FLASHVAR_CALLBACK_DSC","(Intéraction web)<br />Indiquer le script chargé de collecter les statistiques. <br />Le lecteur enverra une valeur à chaque déclenchement et arrêtde lecture. <br />Pour transmettre les informations à Google Analytics automatiquement, indiquer urchin ou analytics. ");
define("_WEBPHOTO_FLASHVAR_RECOMMENDATIONS_DSC","(Intéraction web)[mediaplayer] <br />Indiquez un fichier XML contenant la liste des ressources que vous souhaitez recommander <br />Les vignettes s'afficheront à l'arrêt de la vidéo (exemple sur Youtube). ");
define("_WEBPHOTO_FLASHVAR_SEARCHLINK_DSC","(Intéraction web)[mediaplayer] <br />Indiquer la destination de la page de résultat des recherches <br />Par défaut il s'agit de 'search.longtail.tv', mais vous pouvez indiquer des destinations différente. <br />Appliquer Flashvar à la barre de recherche pour la masquer totalement. ");
define("_WEBPHOTO_FLASHVAR_STREAMSCRIPT_DSC","(Intéraction web)[mediaplayer] <br />Indiquer l'URL du script à exécuter pour utiliser le streaming vidéo. <br />Les paramètres du fichier et sa position seront transmis au script. <br />Si vous utilisez le streaming LigHTTPD, indiquez lighttpd. ");
define("_WEBPHOTO_FLASHVAR_TYPE_DSC","(Intéraction web)[mediaplayer] <br />Le lecteur qui détermine le type de fichier à jouer se base sur les 3 derniers caractères du Flashvar. <br />Ce paramétrage ne fonctionne pas avec l'ID enregistré en base de données et avec le mod_rewrite sur On. Vous devez dans ce cas indiquer le Flashvar avec le Type de fichier approprié. <br />Si vous n'êtes pas sûr, le lecteur considèrera qu'une la plylist est chargée. ");

// flashvar option
define("_WEBPHOTO_FLASHVAR_LINK_TYPE_NONE","Aucun");
define("_WEBPHOTO_FLASHVAR_LINK_TYPE_SITE","URL du site");
define("_WEBPHOTO_FLASHVAR_LINK_TYPE_PAGE","Page de détail");
define("_WEBPHOTO_FLASHVAR_LINK_TYPE_FILE","Fichier média");
define("_WEBPHOTO_FLASHVAR_LINKTREGET_SELF","Même fenêtre");
define("_WEBPHOTO_FLASHVAR_LINKTREGET_BLANK","nouvelle fenêtre");
define("_WEBPHOTO_FLASHVAR_OVERSTRETCH_FALSE","Faux");
define("_WEBPHOTO_FLASHVAR_OVERSTRETCH_FIT","Remplir");
define("_WEBPHOTO_FLASHVAR_OVERSTRETCH_TRUE","Vrai");
define("_WEBPHOTO_FLASHVAR_OVERSTRETCH_NONE","Aucun");
define("_WEBPHOTO_FLASHVAR_TRANSITION_OFF","Diaporama du lecture désactivé");
define("_WEBPHOTO_FLASHVAR_TRANSITION_FADE","Exctinction progressive");
define("_WEBPHOTO_FLASHVAR_TRANSITION_SLOWFADE","Exctinction progressive et lente");
define("_WEBPHOTO_FLASHVAR_TRANSITION_BGFADE","Extinction de l'arrière plan");
define("_WEBPHOTO_FLASHVAR_TRANSITION_CIRCLES","Cercles");
define("_WEBPHOTO_FLASHVAR_TRANSITION_BLOCKS","Blocs");
define("_WEBPHOTO_FLASHVAR_TRANSITION_BUBBLES","Bulles");
define("_WEBPHOTO_FLASHVAR_TRANSITION_FLASH","Flash");
define("_WEBPHOTO_FLASHVAR_TRANSITION_FLUIDS","Fluide");
define("_WEBPHOTO_FLASHVAR_TRANSITION_LINES","Lignes");
define("_WEBPHOTO_FLASHVAR_TRANSITION_RANDOM","Aléatoire");

// edit form
define("_WEBPHOTO_CAP_DETAIL","Afficher les détails");
define("_WEBPHOTO_CAP_DETAIL_ONOFF","Activé/Désactivé");
define("_WEBPHOTO_PLAYER","Lecteur");
define("_WEBPHOTO_EMBED_ADD", "Ajouter une extension EMBED" ) ;
define("_WEBPHOTO_EMBED_THUMB","Une source extérieure fournit la miniature.");
define("_WEBPHOTO_ERR_EMBED","Vous devez indiquer un Plugin");
define("_WEBPHOTO_ERR_PLAYLIST","Vous devez indiquer une playlist");

// sort
define("_WEBPHOTO_SORT_VOTESA","Votes (mini)");
define("_WEBPHOTO_SORT_VOTESD","Votes (maxi)");
define("_WEBPHOTO_SORT_VIEWSA","Affichage des médias (mini)");
define("_WEBPHOTO_SORT_VIEWSD","Affichage des médias (maxi)");

// flashvar form
define("_WEBPHOTO_FLASHVARS_FORM","FlashVars");
define("_WEBPHOTO_FLASHVARS_LIST","Liste des variables flash");
define("_WEBPHOTO_FLASHVARS_LOGO_SELECT","Sélectionner un logo de lecteur");
define("_WEBPHOTO_FLASHVARS_LOGO_UPLOAD","Charger un logo de lecteur");
define("_WEBPHOTO_FLASHVARS_LOGO_DSC","(Affichage) <br />Les logos de lecteur sont en place");
define("_WEBPHOTO_BUTTON_COLOR_PICKUP","Couleur");
define("_WEBPHOTO_BUTTON_RESTORE","Restaurer les valeurs par défaut");

// Playlist Cache 
define("_WEBPHOTO_PLAYLIST_STATUS_REPORT","Compte rendu du statut");
define("_WEBPHOTO_PLAYLIST_STATUS_FETCHED","Ce flux a été récupéré et mis en cache.");
define("_WEBPHOTO_PLAYLIST_STATUS_CREATED","Une nouvelle playlist a été mise en cache");
define("_WEBPHOTO_PLAYLIST_ERR_CACHE","[ERREUR] lors de la création du cache");
define("_WEBPHOTO_PLAYLIST_ERR_FETCH","Impossible de récupérer le flux. <br />Veuillez confirmer l'emplacement du flux et mettre à jour le cache.");
define("_WEBPHOTO_PLAYLIST_ERR_NODIR","Le répertoire des médias n'existe pas");
define("_WEBPHOTO_PLAYLIST_ERR_EMPTYDIR","Le répertoire des médias est vide");
define("_WEBPHOTO_PLAYLIST_ERR_WRITE","Impossible de créer le fichier de cache");

define("_WEBPHOTO_USER",  "Utilisateur" ) ;
define("_WEBPHOTO_OR",  "ou" ) ;

//---------------------------------------------------------
// v0.60
//---------------------------------------------------------
// item table
//define("_WEBPHOTO_ITEM_ICON" , "Icon Name" ) ;

define("_WEBPHOTO_ITEM_EXTERNAL_MIDDLE" , "URL extérieur (middle)" ) ;

// cat table
define("_WEBPHOTO_CAT_IMG_NAME" , "Nom de la Catégorie d'images" ) ;

// edit form
define("_WEBPHOTO_CAP_MIDDLE_SELECT", "Sélectionner l'image (middle)");

//---------------------------------------------------------
// v0.70
//---------------------------------------------------------
// item table
define("_WEBPHOTO_ITEM_CODEINFO", "Info code");
define("_WEBPHOTO_ITEM_PAGE_WIDTH",  "Largeur de la page");
define("_WEBPHOTO_ITEM_PAGE_HEIGHT", "Hauteur de la page");
define("_WEBPHOTO_ITEM_EMBED_TEXT",  "Embed");

// item option
define("_WEBPHOTO_ITEM_CODEINFO_CONT","Média");
define("_WEBPHOTO_ITEM_CODEINFO_THUMB","Miniature de l'image");
define("_WEBPHOTO_ITEM_CODEINFO_MIDDLE","Image (middle)");
define("_WEBPHOTO_ITEM_CODEINFO_FLASH","Vidéo Flash");
define("_WEBPHOTO_ITEM_CODEINFO_DOCOMO","Vidéo Docomo");
define("_WEBPHOTO_ITEM_CODEINFO_PAGE","URL");
define("_WEBPHOTO_ITEM_CODEINFO_SITE","Site");
define("_WEBPHOTO_ITEM_CODEINFO_PLAY","Playlist");
define("_WEBPHOTO_ITEM_CODEINFO_EMBED","Embed");
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
define("_WEBPHOTO_DSC_SET_ITEM_TIME_UPDATE",  "Indiquer la date de mise à jour");
define("_WEBPHOTO_DSC_SET_ITEM_TIME_PUBLISH", "Indiquer la date de publication");
define("_WEBPHOTO_DSC_SET_ITEM_TIME_EXPIRE",  "Indiquer la date de suspension");

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
define("_WEBPHOTO_GROUP_PERM_ALL" , "Tous les Groupes" ) ;

//---------------------------------------------------------
// v1.00
//---------------------------------------------------------
// item table
define("_WEBPHOTO_ITEM_EDITOR", "Editeur");
define("_WEBPHOTO_ITEM_DESCRIPTION_HTML",   "Tags HTML");
define("_WEBPHOTO_ITEM_DESCRIPTION_SMILEY", "Smiley");
define("_WEBPHOTO_ITEM_DESCRIPTION_XCODE",  "XOOPS codes");
define("_WEBPHOTO_ITEM_DESCRIPTION_IMAGE",  "Images");
define("_WEBPHOTO_ITEM_DESCRIPTION_BR",     "Linebreak (br)");

// edit form
define("_WEBPHOTO_TITLE_EDITOR_SELECT", "Sélectionner un éditeur");
define("_WEBPHOTO_CAP_DESCRIPTION_OPTION", "Options");
define("_WEBPHOTO_CAP_HTML",   "Activer les Tags HTML");
define("_WEBPHOTO_CAP_SMILEY", "Activer les Smiley");
define("_WEBPHOTO_CAP_XCODE",  "Activer les XOOPS codes");
define("_WEBPHOTO_CAP_IMAGE",  "Activer les images");
define("_WEBPHOTO_CAP_BR",     "Activer linebreak (br)");

//---------------------------------------------------------
// v1.10
//---------------------------------------------------------
// item table
define("_WEBPHOTO_ITEM_WIDTH",  "Largeur de l'image");
define("_WEBPHOTO_ITEM_HEIGHT", "Hauteur de l'image");
define("_WEBPHOTO_ITEM_CONTENT", "Contenu du text");

//---------------------------------------------------------
// v1.20
//---------------------------------------------------------
// item option
define("_WEBPHOTO_ITEM_CODEINFO_PDF","PDF");
define("_WEBPHOTO_ITEM_CODEINFO_SWF","Flash swf");

// form
define("_WEBPHOTO_ERR_PDF", "Impossible de créer le fichier PDF");
define("_WEBPHOTO_ERR_SWF", "Impossible de créer le fichier swf");

// jodconverter
define("_WEBPHOTO_JODCONVERTER_JUNK_WORDS", "");

//---------------------------------------------------------
// v1.30
//---------------------------------------------------------
define("_WEBPHOTO_TITLE_MAP",  "Googlemap");
define("_WEBPHOTO_MAP_LARGE", "Voir la carte grand format");

// timeline
define("_WEBPHOTO_TITLE_TIMELINE",  "Chronologie");
define("_WEBPHOTO_TIMELINE_ON",  "Afficher la chronologie");
define("_WEBPHOTO_TIMELINE_OFF", "Masquer la chronologie");
define("_WEBPHOTO_TIMELINE_SCALE_WEEK",   "1 semaine") ;
define("_WEBPHOTO_TIMELINE_SCALE_MONTH",  "1 mois") ;
define("_WEBPHOTO_TIMELINE_SCALE_YEAR",   "1 an") ;
define("_WEBPHOTO_TIMELINE_SCALE_DECADE", "10 ans") ;
define("_WEBPHOTO_TIMELINE_LARGE", "Afficher la chronologie étendue");
define("_WEBPHOTO_TIMELINE_CAUTION_IE", "Des problèmes d'affichage peuvent survenir avec vec Internet Expoler. Veuillez essayer d'autres navigateurs (Firefox, Opera, Safari).");

// item option
define("_WEBPHOTO_ITEM_CODEINFO_SMALL","Image réduite");

// edit form
define("_WEBPHOTO_CAP_SMALL_SELECT", "Sélectionner une image réduite");

// === define end ===
}

?>