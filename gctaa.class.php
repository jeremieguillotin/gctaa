<?php
    
    class Util {
        public static function JJMMAAAA($date) {
            list($annee, $mois, $jour) = explode('-', $date);
            return $jour."/".$mois."/".$annee;
        }
        
        public static function JJMMAAAAtoSQL($date) {
            list($jour, $mois, $annee) = explode('/', $date);
            return $annee."-".$mois."-".$jour;
        }

        public static function dateValide($date) {
            if ( preg_match("/^((((19|20)(([02468][048])|([13579][26]))-02-29))|((20[0-9][0-9])|(19[0-9][0-9]))-((((0[1-9])|(1[0-2]))-((0[1-9])|(1\d)|(2[0-8])))|((((0[13578])|(1[02]))-31)|(((0[1,3-9])|(1[0-2]))-(29|30)))))$/",$date) === 1 ) {
                return TRUE;
            }
            return FALSE;
        }
        
        public static function stripos($haystack, $needle, $offset = 0) {
            return strpos(strtolower($haystack), strtolower($needle), $offset);
        }
    }
    
        
    
    
    
    
    class Depart {
        private $_numero;
        private $_date_debut;
        private $_heure_debut;
        
        public function __construct(array $donnees) {
            $this->hydrate($donnees);
        }
        
        public function hydrate(array $donnees)
        {
            foreach ($donnees as $key => $value)
            {
                $method = 'set'.ucfirst($key);
                if (method_exists($this, $method)) {
                    $this->$method($value);
                }
            }
        }
        
        public function numero() { return $this->_numero;}
        public function date_debut() { return $this->_date_debut; }
        public function heure_debut() { return $this->_heure_debut; }
        
        public function setNumero($num) { $this->_numero = $num;}
        public function setDate_debut($date) { $this->_date_debut = $date; }
        public function setHeure_debut($heure) { $this->_heure_debut = $heure; }
        
        
        
        public function affiche() {
            echo '<table>';
            echo '<tr>';
            echo '<td colspan="2">Départ n°'.$this->_numero.'</td>';
            echo '</tr><tr>';
            echo '<td>Date</td><td>Heure</td>';
            echo '</tr><tr>';
            echo '<td>'.$this->_date_debut.'</td>
			<td>'.$this->_heure_debut.'</td>';
            echo '</tr>';
            echo '</table>';
            
        }
    }
    
    class TypeTir {
        private $_code;
        private $_nom;
        private $_ordre;
        
        public function __construct(array $donnees) {
            $this->hydrate($donnees);
        }
        
        public function hydrate(array $donnees)
        {
            foreach ($donnees as $key => $value)
            {
                $key = str_replace( 'tt_' , '' , $key);
                $method = 'set'.ucfirst($key);
                if (method_exists($this, $method)) {
                    $this->$method($value);
                }
            }
        }
        
        public function code() { return $this->_code; }
        public function nom() { return $this->_nom; }
        public function ordre() { return $this->_ordre;}
        
        public function setCode($code) { $this->_code = $code; }
        public function setNom($nom) { $this->_nom = $nom; }
        public function setOrdre($ordre) { $this->_ordre = $ordre;}

        public static function liste() {
            $sql = "SELECT tt_code, tt_nom, tt_ordre FROM gctaa_typetir ORDER BY tt_ordre";
            
            // on envoie la requÍte
            $result = mysql_query($sql);
            
            if (!$result) {
                echo mysql_error();
            } else {
                $cpt=-1;
                $listeTypeTir = array();
                while($donneesTypeTir = mysql_fetch_assoc($result))
                {
                    $cpt++;
                    $typeTir = new TypeTir($donneesTypeTir);
                    $listeTypeTir[$cpt] = $typeTir;
                }
            }
            return $listeTypeTir;
        }
        
        public static function libelle($code) {
			global $wpdb;
            $sql = "SELECT tt_nom FROM gctaa_typetir WHERE tt_code ='".$code."'";
            // on envoie la requÍte
            $result = $wpdb->get_var($sql);            
            if ($result) {
                return $code;
            } else {
                return $result;
            }
        }
        
    }
    
    
    
    class Departement {
        private $_iddept;
        private $_nom;
        private $_ligue;
        
        public function __construct(array $donnees) {
            $this->hydrate($donnees);
        }
        
        public function hydrate(array $donnees)
        {
            foreach ($donnees as $key => $value)
            {
                $key = str_replace( 'de_' , '' , $key);
                $method = 'set'.ucfirst($key);
                if (method_exists($this, $method)) {
                    $this->$method($value);
                }
            }
        }
        
        public function iddept() { return $this->_iddept;}
        public function nom() { return $this->_nom; }
        public function ligue() { return $this->_ligue; }
        
        public function setIddept($iddept) { $this->_iddept = $iddept;}
        public function setNom($nom) { $this->_nom = $nom; }
        public function setLigue($ligue) { $this->_ligue = $ligue; }
        
        public static function liste($ligue="") {
            global $wpdb;
	 
			if ( $ligue == "" ) {
                $sql = "SELECT de_iddept, de_nom, de_ligue FROM gctaa_departement ORDER BY de_iddept";
            } else {
                $sql = "SELECT de_iddept, de_nom, de_ligue FROM gctaa_departement WHERE de_ligue = ".$ligue." ORDER BY de_iddept";
            }
			$donneesDepartements = $wpdb->get_results($sql, ARRAY_A);

			$listeDepartement = array();
			$cpt=-1;
			
			if ( $donneesDepartements )
			{
				foreach ( $donneesDepartements as $donneesDepartement )
				{
					$cpt++;
                    $departement = new Departement($donneesDepartement);
                    $listeDepartement[$cpt] = $departement;
				}	
			}
			else
			{
				 echo 'erreur';
			}
            return $listeDepartement;
        }
        
        
        public static function libelle($code) {
			global $wpdb;
            $sql = "SELECT de_nom FROM gctaa_departement WHERE de_iddept ='".$code."'";
            // on envoie la requÍte
            return $result = $wpdb->get_var($sql);
        }
        
        public static function getLigue($code) {
			global $wpdb;
            $sql = "SELECT de_ligue FROM gctaa_departement WHERE de_iddept ='".$code."'";
            // on envoie la requÍte
            return $result = $wpdb->get_var($sql);
        }
    }
    
    class Categorie {
        private $_categorie;
        private $_type;
        private $_saison;
        private $_nom;
        private $_initiales;

        public function __construct(array $donnees) {
            $this->hydrate($donnees);
        }
        
        public function hydrate(array $donnees)
        {
            foreach ($donnees as $key => $value)
            {
                $key = str_replace( 'ct_' , '' , $key);
                $method = 'set'.ucfirst($key);
                if (method_exists($this, $method)) {
                    $this->$method($value);
                }
            }
        }
        
        public function categorie() { return $this->_categorie;}
        public function type() { return $this->_type;}
        public function saison() { return $this->_saison;}
        public function nom() { return $this->_nom;}
        public function initiales() { return $this->_initiales;}

        public function setCategorie($categorie) { $this->_categorie = $categorie;}
        public function setType($type) { $this->_type = $type;}
        public function setSaison($saison) { $this->_saison = $saison;}
        public function setNom($nom) { $this->_nom = $nom;}
        public function setInitiales($initiales) { $this->_initiales = $initiales;}

        public static function liste() {
			global $wpdb;
		 
			$sql = "SELECT ct_categorie, ct_type, ct_saison, ct_nom, ct_initiales FROM gctaa_categories ORDER BY ct_saison DESC, ct_type DESC, ct_categorie";
			$donneesCategories = $wpdb->get_results($sql, ARRAY_A);

			$listeCategorie = array();
			$cpt=-1;
			
			if ( $donneesCategories )
			{
				foreach ( $donneesCategories as $donneesCategorie )
				{
					$cpt++;
					$categorie = new Categorie($donneesCategorie);
					$listeCategorie[$cpt] = $categorie;
				}	
			}
			else
			{
				 echo 'erreur';
			}
			return $listeCategorie;
        }
        
    }

    ?>