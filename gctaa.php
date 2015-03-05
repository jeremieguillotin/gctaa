<?php
    /*
     Plugin Name: Gestion de Club de Tir &agrave; l'arc
     Plugin URI: http://gctaa.blockweb.org
     Description: Gestion des archers, concours, scores, palmares
     Author: J. GUILLOTIN & T. QUEMENEUR
     Version: 1.0
     Author URI: http://www.blockweb.org
     */
    
    class wp_gctaa{

        public static $DEBUG=true;
        
        function __construct(){
            add_action('init', array(&$this,'init'));
            add_action('admin_menu', array(&$this,'admin_menu'));
            add_action('admin_head', array(&$this,'admin_head'));
            add_filter ('the_content', array(&$this,'insert_resultat_article'));
            register_activation_hook( __FILE__, array(&$this,'activate') );
            // init process for button control
            add_action( 'admin_init', array (&$this, 'addButtons' ) );
            add_action( 'wp_ajax_boutonGctaa_shortcodePrinter', array( &$this, 'wp_ajax_fct' ) );
        }
        
        function insert_resultat_article( $content ) {
            //change $content
            $debut = strpos($content, '[GCTAA-resultat=');
            while  (  $debut  !==  false  )  {
                $fin = strpos($content, ']');
                if  (  $fin  !==  false  )  {
                    $idConcours=substr($content, $debut+16,$fin-$debut-16);
                    $content = substr($content, 0, $debut).Resultat::resultats_concours($idConcours).substr($content, $fin+1);
                }
                $debut = strpos($content, '[GCTAA-resultat=');
            }
            return  $content;
        }
        
        /*
        * The content of the javascript popin for the insertion
        *
        */
        function wp_ajax_fct(){
            echo '<div class="container-fluid">';
            echo '<div class="row">';
            echo '<div class="col-md-12">';
            echo "<h2>GCTAA - Afficher les r&eacute;sultats d'un concours</h2>";
            echo '</div>';
            echo '<div class="col-md-12">';
            echo '<form>';
            $listeConcours = Concours::listeAvecResultat();

            if($listeConcours != null) {
                echo '<div class="form-group">';
                echo '<label for="GCTAA_IdConcours">Concours</label>';
                echo '<select name="GCTAA_IdConcours" id="GCTAA_IdConcours" class="form-control" style="width:100%">';
                foreach ($listeConcours as $concours) {
                    echo '<option class="level-0" value="' . $concours->idconcours() . '">'.Club::libelle($concours->idclub()). ' du ' .Util::JJMMAAAA($concours->datedebut()).'</option>';
                }
                echo '</select>';
                 echo '</div>';
            }
            echo '</div>';
            echo '<div class="col-md-12">';
            echo '<input name="mcb_button" id="mcb_button" type="submit" class="btn btn-primary" value="Ins&eacute;rer les r&eacute;sultats">';
            echo '</div>';
            echo '</form>';
            echo '</div>';
            echo '</div>';
            die();
        }

        /*
        * Add buttons to the tiymce bar
        */
        function addButtons() {
            // Don't bother doing this stuff if the current user lacks permissions
            if ( ! current_user_can('edit_posts') && ! current_user_can('edit_pages') )
                return false;
        
            if ( get_user_option('rich_editing') == 'true') {
                add_filter('mce_external_plugins', array (&$this,'addScriptTinymce' ) );
                add_filter('mce_buttons', array (&$this,'registerTheButton' ) );
            }
        }

        /*
        * Add buttons to the tiymce bar
        *
        */
        function registerTheButton($buttons) {
            array_push($buttons, "|", "boutonGctaa");
            return $buttons;
        }

        /*
        * Load the custom js for the tinymce button
        *

        */
        function addScriptTinymce($plugin_array) {
            $plugin_array['boutonGctaa'] = WP_PLUGIN_URL . '/gctaa/js/tinymce.js';
            return $plugin_array;
        }

        function activate() {
            include "db.class.php";
            Db::createBDD();
            Db::insertExempleBDD();
            Db::insertDataBDD();
        }
        
        function admin_menu(){
            
            add_menu_page("Tir &agrave; l'arc", "Tir &agrave; l'arc", 'manage_options', 'GCTAA', array(&$this,'admin'), plugins_url('gctaa/js/img/boutonGctaa.png'));//images/gctaa-ico.png'));
            
            add_submenu_page('GCTAA', "Vue d'ensemble", "Vue d'ensemble", 'manage_options', 'GCTAA', array(&$this,'admin'));
            
            add_submenu_page('GCTAA', "Liste des Archers", '<i class="fa fa-user fa-fw"></i>&nbsp; Archer', 'manage_options', 'listeArchers', array(&$this,'admin'));
            add_submenu_page( null  , "Ajout d'un Archer", "Ajout d'un Archer", 'manage_options', 'ajouteArcher', array(&$this,'admin'));
            add_submenu_page( null  , "Fiche d'un Archer", "Fiche d'un Archer", 'manage_options', 'ficheArcher', array(&$this,'admin'));

            add_submenu_page('GCTAA', "Liste des Clubs", '<i class="fa fa-users fa-fw"></i>&nbsp; Clubs', 'manage_options', 'listeClubs', array(&$this,'admin'));
            add_submenu_page( null  , "Fiche d'un Club", "Fiche d'un Club", 'manage_options', 'ficheClub', array(&$this,'admin'));
            add_submenu_page( null  , "Importation Club", "Importation Club", 'manage_options', 'importClub', array(&$this,'admin'));
            
            add_submenu_page('GCTAA', "Liste des Concours", '<i class="fa fa-bullseye fa-fw"></i>&nbsp; Concours', 'manage_options', 'listeConcours', array(&$this,'admin'));
            add_submenu_page( null  , "Fiche d'un Concours", "Fiche d'un Concours", 'manage_options', 'ficheConcours', array(&$this,'admin'));
            add_submenu_page( null  , "Importation Concours", "Importation Concours", 'manage_options', 'importConcours', array(&$this,'admin'));
            
            add_submenu_page('GCTAA', "Derniers R&eacute;sultats", '<i class="fa fa-cubes fa-fw"></i>&nbsp; R&eacute;sultats', 'manage_options', 'afficheResultats', array(&$this,'admin'));
            add_submenu_page( null  , "Importation R&eacute;sultats", "Importation R&eacute;sultats", 'manage_options', 'importResultats', array(&$this,'admin'));

            add_submenu_page('GCTAA', "Palmar&egrave;s des Archers du club", '<i class="fa fa-trophy fa-fw"></i>&nbsp; Palmar&egrave;s', 'manage_options', 'palmares', array(&$this,'admin'));
        }
        
        function maj_option($name,$value){
            if ( get_option($name) === false ) {
                add_option($name, $value);
            } else {
                update_option($name, $value);
            }
        }
        
        function admin_head(){
            if(is_admin()){
                echo '<script src="' . WP_PLUGIN_URL . '/gctaa/bootstrap/js/bootstrap.js"></script>';
            }
        }
    
        function admin(){
            switch ($_GET['page']){
                case "listeArchers" :
                    admin_liste_archers($this);
                    break;
                case "ajouteArcher" :
                    admin_ajoute_archer($this);
                    break;
                case "ficheArcher" :
                    admin_fiche_archer($this);
                    break;
                case "listeClubs" :
                    admin_liste_clubs($this);
                    break;
                case "ficheClub" :
                    admin_fiche_club($this);
                    break;
                case "importClub" :
                    admin_import_club($this);
                    break;
                case "listeConcours" :
                    admin_liste_concours($this);
                    break;
                case "ficheConcours" :
                    admin_fiche_concours($this);
                    break;
                case "importConcours" :
                    admin_import_concours($this);
                    break;
                case "afficheResultats" :
                    admin_affiche_resultats($this);
                    break;
                case "importResultats" :
                    admin_import_resultat($this);
                    break;
                default :
                    $this->admin_accueil();
                    break;
            }
        }
        
        function admin_affichemenupage($tailleBouton) {
            
            echo '<p>';
            
            // Gestion des Archers
            echo '<div class="btn-group">';
            echo '<a class="btn '.$tailleBouton.'" href="?page=listeArchers"><i class="fa fa-user fa-fw"></i>&nbsp; Archers</a>';
            echo '<button class="btn '.$tailleBouton.' dropdown-toggle" data-toggle="dropdown">';
            echo '<span class="caret"></span>';
            echo '</button>';
            echo '<ul class="dropdown-menu">';
            echo '<li><a tabindex="-1" href="?page=listeArchers"><i class="fa fa-th-list fa-fw"></i>&nbsp; Liste des Archers</a></li>';
            echo '<li><a tabindex="-1" href="?page=ajouteArcher"><i class="fa fa-plus-square-o fa-fw"></i>&nbsp; Ajouter un Archer</a></li>';
            echo '<li class="divider"></li>';
            echo '<li><a tabindex="-1" href="#"><i class="fa fa-pencil-square-o fa-fw"></i>&nbsp; Inscription concours</a></li>';
            echo '</ul>';
            echo '</div>';
            
            // Gestion des Clubs
            echo '<div class="btn-group">';
            echo '<a class="btn '.$tailleBouton.' btn-inverse" href="?page=listeClubs"><i class="fa fa-users fa-fw"></i>&nbsp; Clubs</a>';
            echo '<button class="btn '.$tailleBouton.' dropdown-toggle btn-inverse " data-toggle="dropdown">';
            echo '<span class="caret"></span>';
            echo '</button>';
            echo '<ul class="dropdown-menu">';
            echo '<li><a tabindex="-1" href="?page=listeClubs"><i class="fa fa-th-list fa-fw"></i>&nbsp; Liste des Clubs</a></li>';
            echo '<li><a tabindex="-1" href="?page=importClub"><i class="fa fa-cloud-download fa-fw"></i>&nbsp; Importer un Club</a></li>';
            echo '</ul>';
            echo '</div>';
            
            // Gestion des Concours
            echo '<div class="btn-group">';
            echo '<a class="btn '.$tailleBouton.' btn-primary " href="?page=listeConcours"><i class="fa fa-bullseye fa-fw"></i>&nbsp;Concours</a>';
            echo '<button class="btn '.$tailleBouton.' dropdown-toggle btn-primary " data-toggle="dropdown">';
            echo '<span class="caret"></span>';
            echo '</button>';
            echo '<ul class="dropdown-menu">';
            echo '<li><a tabindex="-1" href="?page=listeConcours"><i class="fa fa-th-list fa-fw"></i>&nbsp; Liste des Concours</a></li>';
            echo '<li><a tabindex="-1" href="?page=importConcours"><i class="fa fa-cloud-download fa-fw"></i>&nbsp; Importer des Concours</a></li>';
            echo '</ul>';
            echo '</div>';
            
            // Gestion des RŽsultats
            echo '<div class="btn-group">';
            echo '<a class="btn '.$tailleBouton.' btn-danger " href="?page=afficheResultats"><i class="fa fa-cubes fa-fw"></i>&nbsp; R&eacute;sultats</a>';
            echo '<button class="btn '.$tailleBouton.' dropdown-toggle btn-danger " data-toggle="dropdown">';
            echo '<span class="caret"></span>';
            echo '</button>';
            echo '<ul class="dropdown-menu">';
            echo '<li><a tabindex="-1" href="?page=afficheResultats"><i class="fa fa-th-list fa-fw"></i>&nbsp; Liste des R&eacute;sultats</a></li>';
            echo '<li><a tabindex="-1" href="?page=importResultats"><i class="fa fa-cloud-download fa-fw"></i>&nbsp; Importer des R&eacute;sultats</a></li>';
            echo '</ul>';
            echo '</div>';
            
            // Gestion des palmares
            echo '<div class="btn-group">';
            echo '<button class="btn '.$tailleBouton.' btn-warning "><i class="fa fa-trophy"></i> Palmar&egrave;s</button>';
            echo '<button class="btn '.$tailleBouton.' dropdown-toggle btn-warning " data-toggle="dropdown">';
            echo '<span class="caret"></span>';
            echo '</button>';
            echo '<ul class="dropdown-menu">';
            echo '<li>test 1</li>';
            echo '<li>test 2</li>';
            echo '<li>test 3</li>';
            echo '</ul>';
            echo '</div>';
            
          
            
            echo '</p>';
            
            if (wp_gctaa::$DEBUG) {
                echo '<span class="label label-important pull-right">Debug ON</span>';
            }
        }
        
        function admin_accueil(){
            
            echo '<div class="wrap" id="gctaa">';
            echo '  <div class="hero-unit">';
            echo '      <img src="' . WP_PLUGIN_URL . '/gctaa/images/GCTAA-Logo.png" style="margin-left:-60px;margin-top:-60px;margin-bottom:-20px"/>';
            echo '      <h1>Archer, je te salue !</h1><br />';
            echo '      <blockquote>';
            echo "      <p>Un bon archer atteint la cible avant m&ecirc;me d'avoir tir&eacute;.</p>";
            echo '      <small>Zhao Buzhi</small>';
            echo '      </blockquote>';
            
            $this->admin_affichemenupage("btn-large");
            echo '  </div>';
            echo '</div>';
        }
        

        
        
        function afficheListeTypeTir() {
        
        $hidden_field_name = 'mt_submit_hidden';
		$strRetour = '';
		$strRetour = $strRetour . '<table class="widefat" cellspacing="0">';
		$strRetour = $strRetour . '	<thead>';
		$strRetour = $strRetour . '	<tr>';
		$strRetour = $strRetour . '		<th scope="col" >Code</th>';
		$strRetour = $strRetour . '		<th scope="col" >Nom</th>';
		$strRetour = $strRetour . '		<th scope="col" >Ordre</th>';
		$strRetour = $strRetour . '		<th scope="col" colspan="2">Action</th>';
		$strRetour = $strRetour . '	</tr>';
		$strRetour = $strRetour . '	</thead>';
		$strRetour = $strRetour . '	<tbody>';
		
        
        
        
        $listeTypeTir = TypeTir::liste();
        $cpt=0;
        foreach ($listeTypeTir as $typetir) {
            $cpt++;
            if ($cpt % 2 == 0) {
                $strRetour = $strRetour . '<tr>';
            } else {
                $strRetour = $strRetour . '<tr class="alternate">';
            }
            $strRetour = $strRetour . '<td>'.$typetir->code().'</td>';
            $strRetour = $strRetour . '<td>'.$typetir->nom().'</td>';
            $strRetour = $strRetour . '<td>'.$typetir->ordre().'</td>';
            $strRetour = $strRetour . '<td> TAF </td>';
            
        }
        
		$strRetour = $strRetour . '</tbody></table>';
		echo $strRetour;
        
	}
    
        function afficheListeDepartement() {
        $hidden_field_name = 'mt_submit_hidden';
		$strRetour = '';
		$strRetour = $strRetour . '<table class="widefat" cellspacing="0">';
		$strRetour = $strRetour . '	<thead>';
		$strRetour = $strRetour . '	<tr>';
		$strRetour = $strRetour . '		<th scope="col" >Code</th>';
		$strRetour = $strRetour . '		<th scope="col" >Nom</th>';
		$strRetour = $strRetour . '		<th scope="col" >Ligue</th>';
		$strRetour = $strRetour . '		<th scope="col" colspan="2">Action</th>';
		$strRetour = $strRetour . '	</tr>';
		$strRetour = $strRetour . '	</thead>';
		$strRetour = $strRetour . '	<tbody>';
		
        
        
        
        $listeDepartement = Departement::liste();
        $cpt=0;
        foreach ($listeDepartement as $departement) {
            $cpt++;
            if ($cpt % 2 == 0) {
                $strRetour = $strRetour . '<tr>';
            } else {
                $strRetour = $strRetour . '<tr class="alternate">';
            }
            $strRetour = $strRetour . '<td>'.$departement->iddept().'</td>';
            $strRetour = $strRetour . '<td>'.$departement->nom().'</td>';
            $strRetour = $strRetour . '<td>'.$departement->ligue().'</td>';
            $strRetour = $strRetour . '<td> TAF </td>';
            
        }
        
		$strRetour = $strRetour . '</tbody></table>';
		echo $strRetour;
        
	}
    
        function afficheListeCategorie() {
        $hidden_field_name = 'mt_submit_hidden';
		$strRetour = '';
		$strRetour = $strRetour . '<table class="widefat" cellspacing="0">';
		$strRetour = $strRetour . '	<thead>';
		$strRetour = $strRetour . '	<tr>';
		$strRetour = $strRetour . '		<th scope="col" >Code</th>';
		$strRetour = $strRetour . '		<th scope="col" >Type</th>';
		$strRetour = $strRetour . '		<th scope="col" >Saison</th>';
		$strRetour = $strRetour . '		<th scope="col" >Nom</th>';
		$strRetour = $strRetour . '		<th scope="col" >Initiales</th>';
		$strRetour = $strRetour . '		<th scope="col" colspan="2">Action</th>';
		$strRetour = $strRetour . '	</tr>';
		$strRetour = $strRetour . '	</thead>';
		$strRetour = $strRetour . '	<tbody>';
        
        $listeCategorie = Categorie::liste();
        $cpt=0;
        foreach ($listeCategorie as $categorie) {
            $cpt++;
            if ($cpt % 2 == 0) {
                $strRetour = $strRetour . '<tr>';
            } else {
                $strRetour = $strRetour . '<tr class="alternate">';
            }
            $strRetour = $strRetour . '<td>'.$categorie->categorie().'</td>';
            $strRetour = $strRetour . '<td>'.TypeTir::libelle($categorie->type()).'</td>';
            $strRetour = $strRetour . '<td>'.$categorie->saison().'</td>';
            $strRetour = $strRetour . '<td>'.$categorie->nom().'</td>';
            $strRetour = $strRetour . '<td>'.$categorie->initiales().'</td>';
            $strRetour = $strRetour . '<td> TAF </td>';
            $strRetour = $strRetour . '<td> TAF </td>';
        }
        
		$strRetour = $strRetour . '</tbody></table>';
		echo $strRetour;
        
	}
    
    function init(){        
        include "gctaa.class.php";
        include "archer.class.php";
        include "archer.page.php";
        include "depart.class.php";
        include "departement.class.php";
        include "categorie.class.php";
        include "club.class.php";
        include "club.page.php";
        include "concours.class.php";
        include "concours.page.php";
        include "ligue.class.php";
        include "resultat.page.php";
        include "resultat.class.php";
        include "typetir.class.php";
        wp_enqueue_style('gctaa-fa-css', WP_PLUGIN_URL . '/gctaa/font-awesome/css/font-awesome.min.css');
        if(is_admin()) {
            wp_enqueue_style('gctaa-bs-css', WP_PLUGIN_URL . '/gctaa/bootstrap/css/bootstrap.css');
            
        }
    }
    }
	
    if (class_exists('wp_gctaa')) {
	   $wp_gctaa = new wp_gctaa();
	}
	
    ?>