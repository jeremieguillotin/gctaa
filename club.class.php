<?php
    class Club {
        private $_idclub;
        private $_nom;
        private $_ville;
        private $_logo;
        private $_dept;
        private $_ligue;
        
        public function __construct(array $donnees) {
            $this->hydrate($donnees);
        }
        
        public function hydrate(array $donnees) {
            foreach ($donnees as $key => $value) {
                $key = str_replace( 'cl_' , '' , $key);
                $method = 'set'.ucfirst($key);
                if (method_exists($this, $method)) {
                    $this->$method($value);
                } 
            }
        }
        
        public function idclub() { return $this->_idclub; }
        public function nom() { return $this->_nom; }
        public function ville() { return $this->_ville; }
        public function logo() { return $this->_logo; }
        public function dept() { return $this->_dept; }
        public function ligue() { return $this->_ligue; }
        
        public function setIdclub($idclub) { $this->_idclub = $idclub;}
        public function setNom($nom) { $this->_nom = $nom; }
        public function setVille($ville) { $this->_ville = $ville; }
        public function setLogo($logo) { $this->_logo = $logo; }
        public function setDept($dept) { $this->_dept = $dept; }
        public function setLigue($ligue) { $this->_ligue =$ligue; }

        public static function selectBDD($idclub) {
            global $wpdb;
            // chargement d'un Club
            $sql = "SELECT  cl_idclub ,  cl_nom ,  cl_ville ,  cl_dept ,  cl_ligue ,  cl_logo FROM " . $wpdb->prefix . "gctaa_clubs WHERE cl_idclub = ".$idclub;
            // on envoie la requÍte
            $donneesClub = $wpdb->get_row($sql, ARRAY_A);
            
            if (!$donneesClub) {
                echo "Erreur lors du chargement du club (" . $idclub . ") : Club non trouvé.";
            } else {
                $club = new Club($donneesClub);
                return $club;
            }
            return null;
        }

        public function insertBDD() {
            global $wpdb;

            $result = $wpdb->insert(
                $wpdb->prefix . 'gctaa_clubs',
                array( 'cl_idclub' => $this->idclub(),'cl_nom' => $this->nom(),'cl_ville' => $this->ville(),'cl_dept' => $this->dept(),'cl_ligue' => $this->ligue(),'cl_logo' => $this->logo()),
                array( '%s', '%s','%s','%s','%s','%s')
            );

            if ( false === $result ) {
                return "Impossible d'ajouter le club (" . $this->idclub() . ") : " . $wpdb->last_error;
            } else {
                return "";
            }
            
        }
        public static function updateBDD($idclub, $club) {
            global $wpdb;
            $result = $wpdb->update(
                $wpdb->prefix . 'gctaa_clubs',
                array( 'cl_nom' => $club->nom(),'cl_ville' => $club->ville(),'cl_dept' => $club->dept(),'cl_ligue' => $club->ligue(),'cl_logo' => $club->logo()),
                array( 'cl_idclub' => $idclub),
                array( '%s','%s','%s','%s','%s')
            );
            if ( false === $result ) {
                return "Impossible de mettre à jour le club (" . $idclub . ") : " . $wpdb->last_error;
            } elseif ( 0 === $result ) {
                return "Impossible de mettre à jour le club (" . $idclub . ") : Club non trouvé.";
            } elseif ( 0 < $result ) {
                return "";
            }
        }
        public static function deleteBDD($idclub) {
            global $wpdb;
            $result = $wpdb->delete(
                $wpdb->prefix . 'gctaa_clubs',
                array( 'cl_idclub' => $idclub )
            );
            if ( false === $result ) {
                return "Impossible de supprimer le club (" . $idclub . ") : " . $wpdb->last_error;
            } elseif ( 0 === $result ) {
                return "Impossible de supprimer le club (" . $idclub . ") : Club non trouvé.";
            } elseif ( 0 < $result ) {
                return "";
            }
        }
        
        public static function libelle($idclub){
			global $wpdb;

            $sql = "SELECT  cl_nom ,  cl_ville FROM " . $wpdb->prefix . "gctaa_clubs WHERE cl_idclub = '".$idclub."'";
            
            // on envoie la requÍte
            $result = $wpdb->get_row($sql, ARRAY_A);
            
            if (!$result) {
                return $idclub;
            } else {
                return $result['cl_nom'] . " (" . ucwords(strtolower($result['cl_ville'])) . ")";
            }
        }

        public static function libelleVille($idclub){
            global $wpdb;

            $sql = "SELECT cl_ville FROM " . $wpdb->prefix . "gctaa_clubs WHERE cl_idclub = '".$idclub."'";
            
            // on envoie la requÍte
            $result = $wpdb->get_row($sql, ARRAY_A);
            
            if (!$result) {
                return $idclub;
            } else {
                return ucwords(strtolower($result['cl_ville']));
            }
        }

        public static function afficheLogo($idclub){
            global $wpdb;

            $sql = "SELECT cl_logo FROM " . $wpdb->prefix . "gctaa_clubs WHERE cl_idclub = '".$idclub."'";
            
            // on envoie la requÍte
            $result = $wpdb->get_row($sql, ARRAY_A);
            
            if (!$result) {
                return $idclub;
            } else {
                if ($club->logo() == 'nc.png') {
                    $strRetour = '<span class="fa-stack fa-lg"><i class="fa fa-circle fa-stack-2x"></i><i class="fa fa-users fa-stack-1x fa-inverse"></i></span>';
                } else {
                    $strRetour = '<img src="' . $club->logo() . '" width="50px">';
                }
                return $strRetour;
            }
        }
        
        public static function liste() {
			global $wpdb;

            $sql = "SELECT cl_idclub, cl_nom, cl_ville, cl_dept, cl_ligue, cl_logo FROM " . $wpdb->prefix . "gctaa_clubs ORDER BY cl_ville";
            // on envoie la requête
            $donneesClubs = $wpdb->get_results($sql, ARRAY_A);
			$listeClub = array();
			$cpt = -1;
            if ( $donneesClubs )
			{
				foreach ( $donneesClubs as $donneesClub )
				{
					$cpt++;
                    $club = new Club($donneesClub);
                    $listeClub[$cpt] = $club;
				}	
			}
			else
			{
				 echo 'erreur';
			}
            return $listeClub;
        }
        
        public function affiche() {
            echo "ID : ".$this->idclub()."<br />";
            echo "Nom : ".$this->nom()."<br />";
            echo "Ville : ".$this->ville()."<br />";
            echo "Dept : ".$this->dept()."<br />";
            echo "Ligue : ".$this->ligue()."<br />";
            echo "Logo : ".$this->logo()."<br />";
        }
        
        public static function afficheListeClubs() {
            $hidden_field_name = 'GCTAA';
            $strRetour = '<table class="table table-bordered table-striped table-condensed table-hover">';
            $strRetour = $strRetour . '	<thead>';
            $strRetour = $strRetour . '	<tr>';
            $strRetour = $strRetour . '     <th>Logo</th>';
            $strRetour = $strRetour . '		<th>ID Club</th>';
            $strRetour = $strRetour . '		<th>Nom</th>';
            $strRetour = $strRetour . '		<th>Ville</th>';
            $strRetour = $strRetour . '		<th>Departement</th>';
            $strRetour = $strRetour . '		<th>Ligue</th>';
            $strRetour = $strRetour . '		<th><i class="icon-wrench"></i> Action</th>';
            $strRetour = $strRetour . '	</tr>';
            $strRetour = $strRetour . '	</thead>';
            $strRetour = $strRetour . '	<tbody>';
            $listeClub = Club::liste();
            $cpt=0;
            foreach ($listeClub as $club) {
                $strRetour = $strRetour . '<tr>';
                if ($club->logo() == 'nc.png') {
                    $strRetour = $strRetour . '<td class="text-center"><span class="fa-stack fa-lg"><i class="fa fa-circle fa-stack-2x"></i><i class="fa fa-users fa-stack-1x fa-inverse"></i></span></td>';
                } else {
                    $strRetour = $strRetour . '<td><p class="text-center"><img src="' . $club->logo() . '" width="50px"></p></td>';
                }
                
                $strRetour = $strRetour . '<td>'.$club->idclub().'</td>';
                $strRetour = $strRetour . '<td>'.$club->nom().'</td>';
                $strRetour = $strRetour . '<td>'.$club->ville().'</td>';
                $strRetour = $strRetour . '<td>'.Departement::libelle($club->dept()).' ('.$club->dept().')'.'</td>';
                $strRetour = $strRetour . '<td>'.Ligue::libelle($club->ligue()).'</td>';
                $strRetour = $strRetour . '<td><form name="formListeClub" method="post" action="?page=ficheClub"><input type="hidden" name="'.$hidden_field_name.'" value="A"><input type="hidden" name="idclub" maxlength="7" size="7" value="'.$club->idclub().'" /><div class="btn-group"><button class="btn btn-small" type="submit" name="affiche"><i class="fa fa-users"></i></button><button class="btn btn-small" type="submit" name="modif"><i class="icon-pencil"></i></button><button class="btn btn-small" type="submit" name="supprime" onclick="javascript:check=confirm( \'Effacer ce Club ? \');if(check==false) return false;"><i class="icon-trash"></i></button></div></form></td>';
                $strRetour = $strRetour . '</tr>';
            }
            
            $strRetour = $strRetour . '</tbody></table>';
            echo $strRetour;
        }
        
        public static function importClubs($dept) {
            $test=true;
            // URL à ouvrir
            //$url = 'http://127.0.0.1:8080/wordpress/wp-content/plugins/gctaa/testImportClub44.html';
            $url = 'http://ffta-public.cvf.fr/servlet/GP_RechercheClub?LIST_DPT=' . $dept;
            $file = fopen ($url , "r");
            $nb = 0;
            $ligue=Departement::getLigue($dept);
            
            $hidden_field_name = 'GCTAA';
            echo '<form name="importClubs" method="post" action="">';
            echo '<input type="hidden" name="'.$hidden_field_name.'" value="ImportClub">';            
            echo '<input type="hidden" name="dept" value="'.$dept.'">';
            echo '<table class="table table-bordered table-striped table-condensed table-hover">';
            echo '	<thead>';
            echo '	<tr>';
            echo '		<th>Sel.</th>';
            echo '		<th>Id</th>';
            echo '		<th>Nom</th>';
            echo '		<th>Ville</th>';
            if (wp_gctaa::$DEBUG) {
                echo '		<th>Req</th>';
            }
            echo '	</tr>';
            echo '	</thead>';
            echo '	<tbody>';
            
            // $line = fgets ($file);
            while (!feof ($file) and (Util::stripos($line,'[strong]Site[/strong]') == 0)) {
                $line = fgets ($file);
                $line = trim(str_replace('<','[',str_replace('>',']',$line)));
            }
            
            while (!feof ($file)) {
                $line = fgets ($file);
                if(Util::stripos($line,'<tr>') != 0) {
                    // VILLE
                    $line = fgets ($file);
                    $ville = trim(str_replace('<td>',' ',str_replace('</td>',' ',$line)));
                    
                    // ID et NOM
                    $line = fgets ($file);
                    if (Util::stripos($line,'<a href="GP_RechercheFiche') != 0) {
                        $line = trim(str_replace('<a href="GP_RechercheFiche?','',str_replace('&','',$line)));
                        $decoupe1 = explode('=', $line);
                        $listeID = explode('-', str_replace('-INDICE_CLUB','',$decoupe1[2]));
                        
                        
                        
                        list($id, $nomClub) = explode('>', str_replace('"','',$decoupe1[3]));
                        $idClub = trim($listeID[$id]);
                        $nomClub = str_replace("</a", "", $nomClub);
                        $ligue = Departement::getLigue($dept);
                        $reqInsert = $idClub . ";" . $nomClub . ";" . $ville . ";" . $dept . ";" . $ligue . ";nc.png";
                        echo '<tr>';
                        echo '<td><input type="checkbox" name="clubs[]" value="' . $reqInsert . '" /></td>';
                        echo '<td>'.$idClub.'</td>';
                        echo '<td>'.$nomClub.'</td>';
                        echo '<td>'.$ville.'</td>';
                        if (wp_gctaa::$DEBUG) {
                            echo '<td>'.$reqInsert.'</td>';
                        }
                        echo '</tr>';
                    }
                }
            }
            echo '</tbody></table><br />';
            echo '<button type="submit" class="btn btn-primary">Importer ces clubs <i class="icon-download icon-white"></i> </button>';
            echo '</form>';
            fclose($file);
        }
        
        public static function ajouteClubs(array $listeClub) {
            global $wpdb;
            
            // fonction d'ajout d'un archer dans les effectifs du club
            // on crÈe la requÍte SQL
            
            $reqInsert = "";
            foreach ($listeClub as $club) {
                $reqInsert = $reqInsert . "(".$club->idClub() . ", '" . $club->nom() . "', '" . $club->ville() . "', '" . $club->dept() . "', " . $club->ligue() . ", '" . $club->logo() . "');";
            }
            
            $reqInsert = str_replace(");(", "), (", $reqInsert);
            $reqInsert = "INSERT INTO " . $wpdb->prefix . "gctaa_clubs (cl_idclub, cl_nom, cl_ville, cl_dept, cl_ligue, cl_logo) VALUES " . $reqInsert;
            
            $result = mysql_query($reqInsert);
            
            if (!$result) {
                $retour = mysql_error() . "<br />". $reqInsert;
            } else {
                $retour = "Insertion OK";
            }
            
            return $retour;
            
        }
        
    }
    ?>