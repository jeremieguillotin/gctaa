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
            add_action('init',array(&$this,'init'));
            add_action('admin_menu', array(&$this,'admin_menu'));
            add_action('admin_head',array(&$this,'admin_head'));
            register_activation_hook( __FILE__, array(&$this,'activate') );
            //add_filter ('the_content', 'insert_article');
        }
        
        function insert_article($content) {
            //change $content
            $debut = strpos($content, '[resultat=');
            while  (  $debut  !==  false  )  {
                $fin = strpos($content, ']');
                if  (  $fin  !==  false  )  {
                    $idconcours=substr($content, $debut+10,$fin-$debut-10);
                    $content = substr($content, 0, $debut).$this->resultat($idconcours).substr($content, $fin+1);
                }
                $debut = strpos($content, '[resultat=');
            }
            return  $content;
        }
        
        function activate() {
            include "db.class.php";
            Db::createBDD();
            // Db::insertExempleBDD();
            Db::insertDataBDD();
        }
        
        function admin_menu(){
            
            add_menu_page("Tir &agrave; l'arc", "Tir &agrave; l'arc", 'manage_options', 'GCTAA', array(&$this,'admin'), plugins_url('gctaa/images/gctaa-ico.png'));
            
            add_submenu_page('GCTAA', "Vue d'ensemble", "Vue d'ensemble", 'manage_options', 'GCTAA', array(&$this,'admin'));
            
            add_submenu_page('GCTAA', "Liste des Archers", "Archer", 'manage_options', 'listeArchers', array(&$this,'admin'));
            add_submenu_page( null  , "Ajout d'un Archer", "Ajout d'un Archer", 'manage_options', 'ajouteArcher', array(&$this,'admin'));
            add_submenu_page( null  , "Fiche d'un Archer", "Fiche d'un Archer", 'manage_options', 'ficheArcher', array(&$this,'admin'));

            add_submenu_page('GCTAA', "Liste des Clubs", "Clubs", 'manage_options', 'listeClubs', array(&$this,'admin'));
            add_submenu_page( null  , "Fiche d'un Club", "Fiche d'un Club", 'manage_options', 'ficheClub', array(&$this,'admin'));
            add_submenu_page( null  , "Importation Club", "Importation Club", 'manage_options', 'importClub', array(&$this,'admin'));
            
            add_submenu_page('GCTAA', "Liste des Concours", "Concours", 'manage_options', 'listeConcours', array(&$this,'admin'));
            add_submenu_page( null  , "Fiche d'un Concours", "Fiche d'un Concours", 'manage_options', 'ficheConcours', array(&$this,'admin'));
            add_submenu_page( null  , "Importation Concours", "Importation Concours", 'manage_options', 'importConcours', array(&$this,'admin'));
            
            add_submenu_page('GCTAA', "Derniers R&eacute;sultats", "R&eacute;sultats", 'manage_options', 'resultat', array(&$this,'admin'));
            
            add_submenu_page('GCTAA', "Palmar&egrave;s des Archers du club", "Palmar&egrave;s", 'manage_options', 'palmares', array(&$this,'admin'));
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
                default :
                    $this->admin_accueil();
                    break;
            }
        }
        
        function admin_affichemenupage($tailleBouton) {
            
            echo '<p>';
            
            // Gestion des Archers
            echo '<div class="btn-group">';
            echo '<a class="btn '.$tailleBouton.'" href="?page=listeArchers">Archers</a>';
            echo '<button class="btn '.$tailleBouton.' dropdown-toggle" data-toggle="dropdown">';
            echo '<span class="caret"></span>';
            echo '</button>';
            echo '<ul class="dropdown-menu">';
            echo '<li><a tabindex="-1" href="?page=listeArchers">Liste des Archers</a></li>';
            echo '<li><a tabindex="-1" href="?page=ajouteArcher">Ajouter un Archer</a></li>';
            echo '<li class="divider"></li>';
            echo '<li><a tabindex="-1" href="#">Inscription concours</a></li>';
            echo '</ul>';
            echo '</div>';
            
            // Gestion des Clubs
            echo '<div class="btn-group">';
            echo '<a class="btn '.$tailleBouton.' btn-inverse" href="?page=listeClubs">Clubs</a>';
            echo '<button class="btn '.$tailleBouton.' dropdown-toggle btn-inverse " data-toggle="dropdown">';
            echo '<span class="caret"></span>';
            echo '</button>';
            echo '<ul class="dropdown-menu">';
            echo '<li><a tabindex="-1" href="?page=listeClubs">Liste des Clubs</a></li>';
            echo '<li><a tabindex="-1" href="?page=importClub">Importer un Club</a></li>';
            echo '</ul>';
            echo '</div>';
            
            // Gestion des Concours
            echo '<div class="btn-group">';
            echo '<a class="btn '.$tailleBouton.' btn-primary " href="?page=listeConcours">Concours</a>';
            echo '<button class="btn '.$tailleBouton.' dropdown-toggle btn-primary " data-toggle="dropdown">';
            echo '<span class="caret"></span>';
            echo '</button>';
            echo '<ul class="dropdown-menu">';
            echo '<li><a tabindex="-1" href="?page=listeConcours">Liste des Concours</a></li>';
            echo '<li><a tabindex="-1" href="?page=importConcours">Importer des Concours</a></li>';
            echo '</ul>';
            echo '</div>';
            
            // Gestion des RŽsultats
            echo '<div class="btn-group">';
            echo '<button class="btn '.$tailleBouton.' btn-danger ">R&eacute;sultats</button>';
            echo '<button class="btn '.$tailleBouton.' dropdown-toggle btn-danger " data-toggle="dropdown">';
            echo '<span class="caret"></span>';
            echo '</button>';
            echo '<ul class="dropdown-menu">';
            echo '<li>test 1</li>';
            echo '<li>test 2</li>';
            echo '<li>test 3</li>';
            echo '</ul>';
            echo '</div>';
            
            // Gestion des palmares
            echo '<div class="btn-group">';
            echo '<button class="btn '.$tailleBouton.' btn-warning ">Palmar&egrave;s</button>';
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
    
        
        
        
        
        
    
    function admin_concours(){
        $hidden_field_name = 'mt_submit_hidden';
        ?>
<div class="wrap" id="gctaa">
<h2>Gestion de Club :: Gestion des concours</h2>
<br/>
<?php if ( $erreur <> "" ) {?>
<div class="ui-widget">
<div class="ui-state-error ui-corner-all" style="padding: 0 .7em;">
<p><span class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></span>
<strong>Erreur :</strong> <?php echo $erreur;?></p>
</div>
</div>
<br/>
<?php }?>

<?php if ( $info <> "" ) {?>
<div class="ui-widget">
<div class="ui-state-highlight ui-corner-all" style="margin-top: 20px; padding: 0 .7em;">
<p><span class="ui-icon ui-icon-info" style="float: left; margin-right: .3em;"></span>
<strong>Info : </strong> <?php echo $info;?></p>
</div>
</div>
<br/>
<?php }?>


<div id="tabs">
<ul>
<li><a href="#tabs-1">Liste des Concours</a></li>
<li><a href="#tabs-2">Ajouter un Concours</a></li>
<li><a href="#tabs-3">Modifier un Concours</a></li>
</ul>

<!-- Liste des Concours -->
<div id="tabs-1">
<?php
Concours::importConcours();
 //$this->afficheListeConcours(); ?>
<?php //$this->afficheListeClubs(); ?>
</div>

<!-- Ajout Concours -->
<div id="tabs-2">
<div class="ui-widget">
<div class="ui-state-error ui-corner-all" style="padding: 0 .7em;">
<p><span class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></span>
<strong>Erreur :</strong> TAF : Ajout Concours</p>
</div>
</div>
<br/>
<h3>Import des concours</h3>
<form name="form1" method="post" action="">
<input type="hidden" name="<?php echo $hidden_field_name; ?>" value="I">
<select name="discipline">
<?php
    
    $listeTypeTir = TypeTir::liste();
    foreach ($listeTypeTir as $typeTir) {
        echo '<option value="' . $typeTir->code() . '" >' . $typeTir->nom() . '</option>';
    }
    
    ?>
</select>
<br />
<select name="LIGUE">
<option value="">TOUTES</option>

<?php
    
    $listeLigue = Ligue::liste();
    foreach ($listeLigue as $ligue) {
        echo '<option value="' . $ligue->idffta() . '" >' . $ligue->nom() . '</option>';
    }
    
    ?>
</select>
<br />
Date de début:<input type="text" id="DATE_DEB" name="DATE_DEB" value="29/11/2012" class="datepicker" size="10" maxlength="10"></td>
Date de fin:<input type="text" id="DATE_FIN" name="DATE_FIN" value="31/12/2012" class="datepicker" size="10" maxlength="10"></td>
<script>
new Control.DatePicker('DATE_DEB', { icon: '../img/calendar.png' , locale: 'fr_FR'});
new Control.DatePicker('DATE_FIN', { icon: '../img/calendar.png' , locale: 'fr_FR' });
</script>
<!-- LIGUE=711&DISCIP=S&DATE_DEB=01/09/2012&DATE_FIN=31/08/2013 -->
<p class="submit">
<input type="submit" name="Ajouter cet archer" class="button-primary" value="Importer ces concours" />
</p>
</form>

</div>

<!-- Modif Concours -->
<div id="tabs-3">
<div class="ui-widget">
<div class="ui-state-error ui-corner-all" style="padding: 0 .7em;">
<p><span class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></span>
<strong>Erreur :</strong> TAF : Modif Concours</p>
</div>
</div>
<br/>
</div>

</div>
</div>
<?php
    }
    
    function afficheListeConcours() {
        $test=true;
        // URL à ouvrir
        $url = 'http://ffta-public.cvf.fr/servlet/ResAffichCalend?LIGUE=711&DISCIP=S&DATE_DEB=01/02/2012&DATE_FIN=31/03/2013';
        $url = 'http://127.0.0.1:8080/wordpress/wp-content/plugins/gctaa/testImportConcours.html';
        $file = fopen ($url , "r");
        $nb = 0;
        echo '<table class="widefat" cellspacing="0">';
		echo '	<thead>';
		echo '	<tr>';
		echo '		<th scope="col" >Id</th>';
        echo '		<th scope="col" >Saison</th>';
		echo '		<th scope="col" >Debut</th>';
		echo '		<th scope="col" >Fin</th>';
		echo '		<th scope="col" >Lieu</th>';
		echo '		<th scope="col" >Particularit&eacute;</th>';
		echo '	</tr>';
		echo '	</thead>';
		echo '	<tbody>';
        
        while (!feof ($file)) {
            $line = fgets ($file);
            if(Util::stripos($line,'<td width="20%" valign="top">') != 0)
            {
                // DATE
                $line = fgets ($file);
                $line = trim(str_replace('<br>',' ',$line));
                if(Util::stripos($line,'au') != 0) {
                    $date_debut = substr($line,0,10);
                    $date_fin = substr($line,14,25);
                } else {
                    $date_debut = substr($line,0,10);
                    $date_fin = $date_debut;
                }
                
                // LIEU
                for ($i = 1; $i <= 4; $i++) {
                    $line = fgets ($file);
                }
                $badge = '';
                if(Util::stripos($line,'Target') != 0)
                {
                    $line = fgets ($file, 500);
                    $line = fgets ($file, 500);
                    $badge = 'FITA Target';
                }
                if(Util::stripos($line,'Arrow Head') != 0)
                {
                    $line = fgets ($file, 500);
                    $line = fgets ($file, 500);
                    $badge = 'Arrow Head';
                }
                if(Util::stripos($line,'D2') != 0)
                {
                    $line = fgets ($file, 500);
                    $line = fgets ($file, 500);
                    $badge = 'D2';
                }
                if(Util::stripos($line,'DR') != 0)
                {
                    $line = fgets ($file, 500);
                    $line = fgets ($file, 500);
                    $badge = 'DR';
                }
                if(Util::stripos($line,'Star') != 0)
                {
                    $line = fgets ($file, 500);
                    $line = fgets ($file, 500);
                    $badge = 'FITA Star';
                }
                if(Util::stripos($line,'Résultats non communiqués') != 0) {
                    $line = fgets ($file);
                }
                $lieu=trim(str_replace('<br>',' ',str_replace('</b>',' ',str_replace('<b>',' ',$line))));
                
                // SAISON
                $line = fgets ($file);
                $line = fgets ($file);
                $saison = trim(str_replace('<i>Individuel ','', str_replace('</i>','', $line)));
                
                // CHAMPIONNAT ?
                $line = fgets ($file);
                if( trim($line) != "") {
                    $desc=trim(str_replace('<br>',' - ', str_replace('&nbsp;','', $line)));
                } else {
                    $desc="";
                }
                
                // IDCONCOURS
                for ($i = 1; $i <= 15; $i++) {
                    $line = fgets ($file);
                    if(Util::stripos($line,'javascript:FicheEpr') != 0) {
                        break;
                    }
                }
                $idConcours=trim(str_replace('\');"><img src="../img/Voir.gif" width="11" height="14" border="0" alt="Informations concours"></a>',' ',str_replace('<a href="javascript:FicheEpr(\'',' ',$line)));
                
                
                
                echo '<tr><td>' . $idConcours . '</td><td>'.$saison.'</td><td>' . $date_debut . '</td><td>' . $date_fin . '</td><td>' . $lieu . '</td><td>'.$desc.'</td></tr>';
                /*
                 
                 
                 
                 $line = fgets ($file, 500);
                 $line = fgets ($file, 500);
                 $line = fgets ($file, 500);
                 $line = fgets ($file, 500);
                 // Sauter les target et arrow head
                 
                 
                 // Lieu
                 $line = str_replace('<b>','',$line);
                 $line = str_replace('</b>','',$line);
                 $line = str_replace('<br>','',$line);
                 $line = trim($line);
                 $Lieu  = str_replace(',','',$line);
                 echo '<td>' . $Lieu . '</td>';
                 
                 // Département
                 $line = fgets ($file, 500);
                 $line = trim($line);
                 $separateur = Util::stripos($line,'-');
                 if($separateur == 8)
                 {
                 $departement = substr($line,2,2);
                 $club = substr($line,10);
                 $noclub = substr($line,0,7);
                 }
                 else
                 {
                 $departement = '';
                 $club = substr($line,5);
                 $noclub = '';
                 }
                 $Dept = $ndepart[$departement];
                 echo '<td>' . $departement . ' - ' . $Dept . '</td><td>' . $noclub . '</td><td>' . $nom[$noclub] . '</td>';
                 
                 // Particularité
                 $line = fgets ($file, 500);
                 $line = fgets ($file, 500);
                 $line = trim($line);
                 $Description = '';
                 if(Util::stripos($line,'<br>') != 0)
                 {
                 $Description = substr($line,0,Util::stripos($line,'<br>'));
                 }
                 else
                 {
                 $Description = '';
                 }
                 if(Util::stripos($Description,'mental') != 0)
                 {
                 $Description .= ' de ' . $ndepart[$departement];
                 }
                 if(Util::stripos($Description,'gional') != 0)
                 {
                 $Description = 'Championnat de Ligue';
                 }
                 $Description .= $badge;
                 $Description = str_replace('dé','D&eacute;',$Description);
                 echo '<td>' . $Description . '&nbsp;</td>';
                 
                 // Insertion
                 if(!$test)
                 {
                 $sql = 'insert into ' . $table_concours . ' (DateDebut,DateFin,Lieu,NoClub,Dept,Description,TypeConcours) values (' .
                 '"' . $DateDebut . '","' . $DateFin . '","' . addslashes($Lieu) . '","' . $noclub . '","' . addslashes($Dept) . '","' . addslashes($Description) .
                 '","' . $TypeConcours . '")';
                 $Result=@mysql_query($sql,$db_id) ;
                 if ($Result)
                 {
                 $message = 'Insertion r&eacute;ussie.';
                 }
                 else
                 {
                 $message = 'Echec de l\'insertion: ' . $sql;
                 }
                 
                 }
                 else
                 {
                 $message = '&nbsp;';
                 }
                 echo '<td>' . $message . '</td>';
                 echo '</tr>';
                 */
                
            }
        }
        echo '</tbody></table>';
        fclose($file);
        
        
    }
    
    
    
        
    
    
    function init(){
        if(is_admin()) {
            include "gctaa.class.php";
            include "archer.class.php";
            include "archer.page.php";
            include "club.class.php";
            include "club.page.php";
            include "concours.class.php";
            include "concours.page.php";
            include "ligue.class.php";
            wp_enqueue_style('gctaa-css', WP_PLUGIN_URL . '/gctaa/bootstrap/css/bootstrap.css');
            
        }
    }
    }
	
    if (class_exists('wp_gctaa')) {
	   $wp_gctaa = new wp_gctaa();
	}
	
    ?>