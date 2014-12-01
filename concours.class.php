<?php
    class Concours {
        private $_idconcours;
        private $_idclub;
        private $_type;
        private $_saison;
        private $_datedebut;
        private $_datefin;
        private $_desc;
        private static $_listeDeparts = array();
        private static $_nbDeparts;
        private static $_listeInscriptions = array();
        private static $_nbInscriptions;
        
        public function __construct(array $donnees) {
            $this->hydrate($donnees);
        }
        
        public function hydrate(array $donnees) {
            foreach ($donnees as $key => $value) {
                $key = str_replace( 'co_' , '' , $key);
                $method = 'set'.ucfirst($key);
                if (method_exists($this, $method)) {
                    $this->$method($value);
                }
            }
        }
        
        public function idconcours() { return $this->_idconcours; }
        public function idclub() { return $this->_idclub; }
        public function type() { return $this->_type; }
        public function saison() { return $this->_saison; }
        public function datedebut() { return $this->_datedebut; }
        public function datefin() { return $this->_datefin; }
        public function desc() { return $this->_desc; }
        
        public function setIdconcours($idconcours) { $this->_idconcours = $idconcours; }
        public function setIdclub($idclub) { $this->_idclub = $idclub; }
        public function setType($type) { $this->_type = $type; }
        public function setSaison($saison) { $this->_saison = $saison; }
        public function setDatedebut($date) { $this->_datedebut = $date; }
        public function setDatefin($date) { $this->_datefin = $date; }
        public function setDesc($desc) { $this->_desc = $desc; }

        public static function selectBDD($idconcours) {
            global $wpdb;
            // chargement d'un Club
            $sql = "SELECT  co_idconcours, co_idclub, co_type, co_saison, co_datedebut, co_datefin, co_desc FROM " . $wpdb->prefix . "gctaa_concours WHERE co_idconcours = ".$idconcours;
            $result = $wpdb->get_row($sql, ARRAY_A);
            
            if (!$result) {
                echo mysql_error(). " - " . $sql;
            } else {
                $cpt=0;
                if ($donneesConcours = mysql_fetch_assoc($result))
                {
                    $concours = new Concours($donneesConcours);
                    return $concours;
                }
            }
            return null;
        }
        public function insertBDD() {
            global $wpdb;

            $result = $wpdb->insert(
                $wpdb->prefix . 'gctaa_concours',
                array('co_idconcours' => $this->idconcours(),'co_idclub' => $this->idclub(),'co_type' => $this->type(),'co_saison' => $this->saison(),'co_datedebut' => $this->datedebut(),'co_datefin' => $this->datefin(),'co_desc' => $this->desc()),
                array( '%s', '%s','%s','%s','%s','%s', '%s')
            );

            if (!$result) {
                return mysql_error();
            } else {
                return "";
            }
        }
        public static function updateBDD($idconcours, $concours) {
            global $wpdb;
            $result = $wpdb->update(
                $wpdb->prefix . 'gctaa_concours',
                array( 'co_idclub' => $concours->idclub(),'co_type' => $concours->type(),'co_saison' => $concours->saison(),'co_datedebut' => $concours->datedebut(),'co_datefin' => $concours->datefin(),'co_desc' => $concours->desc()),
                array( 'co_idconcours' => $idconcours),
                array( '%s','%s','%s','%s','%s','%s')
            );

            if (!$result) {
                return mysql_error();
            } else {
                return "";
            }
        }
        public static function deleteBDD($idconcours) {
            global $wpdb;
            // http://codex.wordpress.org/Class_Reference/wpdb#DELETE_Rows
            $result = $wpdb->delete(
                $wpdb->prefix . 'gctaa_concours',
                array( 'co_idconcours' => $idconcours )
            );
            if (!$result) {
                return mysql_error();
            }
            else{
                return "";
            }
        }
           
        
        public function ajouteDepart(Depart $depart) {
            // fonction d'ajout d'un depart dans un concours
            self::$_listeDeparts[self::$_nbDeparts] =
			array('idconcours' => $depart->idconcours(),
                  'numero' => $depart->numero(),
                  'date_debut' => $depart->date_debut(),
                  'heure_debut' => $depart->heure_debut() );
            self::$_nbDeparts++;
        }
        

        
        public static function liste() {
			global $wpdb;
            $sql = "SELECT  co_idconcours, co_idclub, co_type, co_saison, co_datedebut, co_datefin, co_desc FROM " . $wpdb->prefix . "gctaa_concours ORDER BY co_datedebut";
            // on envoie la requête
            $donneesConcours = $wpdb->get_results($sql, ARRAY_A);
			$listeConcour = array();
			$cpt = -1;
            if ( $donneesConcours )
			{
				foreach ( $donneesConcours as $donneesConcour )
				{
					$cpt++;
                    $concours = new Concours($donneesConcour);
                    $listeConcours[$cpt] = $concours;
				}	
			}
			else
			{
				 echo 'erreur';
			}
            return $listeConcours;
        }
        
        public static function afficheListe() {
            $hidden_field_name = 'GCTAA';
            $strRetour = '<table class="table table-bordered table-striped table-condensed table-hover">';
            $strRetour = $strRetour . ' <thead>';
            $strRetour = $strRetour . ' <tr>';
            $strRetour = $strRetour . '     <th>Id</th>';
            $strRetour = $strRetour . '     <th>Club</th>';
            $strRetour = $strRetour . '     <th>Type</th>';      
            $strRetour = $strRetour . '     <th>Saison</th>';
            $strRetour = $strRetour . '     <th>Debut</th>';
            $strRetour = $strRetour . '     <th>Fin</th>';
            $strRetour = $strRetour . '     <th>Info</th>';
            $strRetour = $strRetour . '     <th><i class="icon-wrench"></i> Action</th>';
            $strRetour = $strRetour . ' </tr>';
            $strRetour = $strRetour . ' </thead>';
            $strRetour = $strRetour . ' <tbody>';
            $listeConcours = Concours::liste();
            $cpt=0;
            foreach ($listeConcours as $concours) {
                $strRetour = $strRetour . '<tr>';
                $strRetour = $strRetour . '<td>'.$concours->idconcours().'</td>';
                $strRetour = $strRetour . '<td>'.Club::libelle($concours->idclub()).'</td>';
                $strRetour = $strRetour . '<td>'.TypeTir::libelle($concours->type()).'</td>';
                $strRetour = $strRetour . '<td>'.$concours->saison().'</td>';
                $strRetour = $strRetour . '<td>'.Util::JJMMAAAA($concours->datedebut()).'</td>';
                $strRetour = $strRetour . '<td>'.Util::JJMMAAAA($concours->datefin()).'</td>';
                $strRetour = $strRetour . '<td>'.$concours->desc().'</td>';
                $strRetour = $strRetour . '<td><form name="formListeConcours" method="post" action="?page=ficheConcours"><input type="hidden" name="'.$hidden_field_name.'" value="A"><input type="hidden" name="idconcours" maxlength="10" size="10" value="'.$concours->idconcours().'" /><div class="btn-group"><button class="btn btn-small" type="submit" name="affiche"><i class="icon-user"></i></button><button class="btn btn-small" type="submit" name="modif"><i class="icon-pencil"></i></button><button class="btn btn-small" type="submit" name="supprime" onclick="javascript:check=confirm( \'Effacer ce concours ? \');if(check==false) return false;"><i class="icon-trash"></i></button></div></form></td>';
                $strRetour = $strRetour . '</tr>';
                $cpt++;
            }
            
            if ($cpt == 0) {
                $strRetour = $strRetour . '<tr>';
                $strRetour = $strRetour . '<td colspan="7">Pas de concours enregistr&eacute;</td>';
                $strRetour = $strRetour . '<td><form name="formListeConcours" method="post" action="?page=ajouteConcours"><input type="hidden" name="'.$hidden_field_name.'" value="A"><div class="btn-group"><button class="btn btn-small" type="submit" name="ajoute"><i class="icon-user"></i></button><button class="btn btn-small" type="submit" name="import"><i class="icon-pencil"></i></button></div></form></td>';
                $strRetour = $strRetour . '</tr>';
            }
            
            $strRetour = $strRetour . '</tbody></table>';
            echo $strRetour;
        }
        
        
        public function ajouteInscription(Archer $archer, Depart $depart) {
            self::$_listeInscriptions[self::$_nbInscriptions] =
			array( 'licence' => $archer->licence(),
                  'numero' => $depart->numero());
            self::$_nbInscriptions++;
        }
        
        public function affiche() {
            echo '<table border="1">';
            echo '<tr>';
            echo '<td colspan="3" style="background:#123456;color:#ffffff;">Concours '.$this->_type.'</td>';
            echo '</tr><tr>';
            echo '<td>Saison</td><td>Date debut</td><td>Date fin</td>';
            echo '</tr><tr>';
            echo '<td>'.$this->_saison.'</td>
			<td>'.$this->_date_debut.'</td>
			<td>'.$this->_date_fin.'</td>';
            echo '</tr><tr><td colspan="3">';
            $this->afficheListeDepart();
            echo '</td></tr></table><br />';
            print_r(self::$_listeInscriptions);
            
        }
        
        public function afficheListeDepart() {
            $strRetour = '<table border="1">' ;
            $strRetour = $strRetour . '<tr><td colspan="5" style="background:#123456;color:#ffffff;">Liste des départs du Concours</td></tr>';
            $strRetour = $strRetour . '<tr>
			<td width="100">Numéro</td>
			<td width="200">Date</td>
			<td width="200">Heure</td>
            </tr>';
            
            foreach (self::$_listeDeparts as $key => $donneesDepart)
            {
                $depart = new Depart($donneesDepart);
                $strRetour = $strRetour . '<tr><td>'.$depart->numero().'</td>';
                $strRetour = $strRetour . '<td>'.$depart->date_debut().'</td>';
                $strRetour = $strRetour . '<td>'.$depart->heure_debut().'</td></tr>';
            }
            $strRetour = $strRetour . '</table><br />';
            echo $strRetour;
        }
        
        public static function importConcours($discipline, $ligue, $dateDebut, $dateFin) {

            $url = 'http://ffta-public.cvf.fr/servlet/ResAffichCalend?LIGUE='.$ligue.'&DISCIP='.$discipline.'&DATE_DEB='.$dateDebut.'&DATE_FIN='.$dateFin;
            //$url = 'http://127.0.0.1:8080/wordpress/wp-content/plugins/gctaa/testImportConcours.html';
            
            if (wp_gctaa::$DEBUG) {
                echo '<div class="alert alert-block alert-info">';
                echo '  <button type="button" class="close" data-dismiss="alert">&times;</button>';
                echo '  <h4>Info</h4>';
                echo $url;
                echo '</div>';
            }
            

            $file = fopen ($url , "r");
            $nb = 0;
            
            $hidden_field_name = 'GCTAA';
            echo '<form name="importConcours" method="post" action="">';
            echo '<input type="hidden" name="'.$hidden_field_name.'" value="ImportConcours">';
            echo '<input type="hidden" name="dept" value="'.$discipline.'">';
            echo '<input type="hidden" name="dept" value="'.$ligue.'">';
            
            echo '<table class="table table-bordered table-striped table-condensed table-hover">';
            echo '	<thead>';
            echo '	<tr>';
            echo '		<th>Sel.</th>';
            //echo '		<th>Id</th>';
            echo '		<th>Saison</th>';
            echo '		<th>Debut</th>';
            echo '		<th>Fin</th>';
            echo '		<th>Lieu</th>';
            echo '		<th>Particularit&eacute;</th>';
            if (wp_gctaa::$DEBUG) {
                // echo '  <th>req</th>';
            }
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
                    
                    // REPORTE ou ANNULE
                    $line = fgets ($file);
                    if( Util::stripos($line,'eport') != 0 or Util::stripos($line,'Annul') != 0)
                    {
                        $line = fgets ($file);
                    }
                    // LIEU
                    for ($i = 1; $i <= 3; $i++) {
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
                    if(Util::stripos($line,"sultats non") != 0) {
                        $line = fgets ($file);
                    }
                    $lieu=trim(str_replace('<br>',' ',str_replace('</b>',' ',str_replace('<b>',' ',$line))));
                    
                    // SAISON
                    $line = fgets ($file);
                    $line = fgets ($file);
                    $saison = trim(str_replace('<i>Individuel ','', str_replace('</i>','', $line)));
                    $saison = trim(str_replace('<i>Par Equipe ','', str_replace('</i>','', $saison)));

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
                        //IDCLUB
                        if(Util::stripos($line,'javascript:Fiche(') != 0) {
                            $idClub=trim(str_replace('\');"><img src="../img/Fiche.gif" width="11" height="14" border="0" alt="Fiche organisateur"></a>',' ',str_replace('<a href="javascript:Fiche(\'',' ',$line)));
                        }
                        if(Util::stripos($line,'javascript:FicheEpr(') != 0) {
                            $idConcours=trim(str_replace('\');"><img src="../img/Voir.gif" width="11" height="14" border="0" alt="Informations concours"></a>',' ',str_replace('<a href="javascript:FicheEpr(\'',' ',$line)));
                            break;
                        }
                    }

                    //formatage date
                    $date_debut=Util::JJMMAAAAtoSQL($date_debut);
                    $date_fin=Util::JJMMAAAAtoSQL($date_fin);


                    $reqInsert = $idConcours . ";" . $saison . ";" . $date_debut . ";" . $date_fin . ";" . $idClub . ";" . $desc . ";" . $discipline;
                    
                    echo '<tr>';
                    echo '  <td><input type="checkbox" name="concours[]" value="' . $reqInsert . '" /></td>';
                    
                    //echo '    <td>' . $idConcours . '</td>';
                    echo '  <td>'.$saison.'</td>';
                    echo '  <td>' . $date_debut . '</td>';
                    echo '  <td>' . $date_fin . '</td>';
                    echo '  <td>' . $lieu . '</td>';
                    echo '  <td>'.$desc.'</td>';
                    if (wp_gctaa::$DEBUG) {
                        //echo '  <td>'.$reqInsert.'</td>';
                    }
                    echo '</tr>';
                   
                    
                }
            }
            
            echo '</tbody></table><br />';
            echo '<button type="submit" class="btn btn-primary">Importer ces concours <i class="icon-download icon-white"></i> </button>';
            echo '</form>';
            
            fclose($file);

        }
        
        
        public function afficheListeInscrits() {
            foreach (self::$_listeInscriptions as $key => $donnees)
            {
                echo $donnees;
            }
        }
    }
?>