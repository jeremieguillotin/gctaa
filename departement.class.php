<?php
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
                $sql = "SELECT de_iddept, de_nom, de_ligue FROM " . $wpdb->prefix . "gctaa_departement ORDER BY de_iddept";
            } else {
                $sql = "SELECT de_iddept, de_nom, de_ligue FROM " . $wpdb->prefix . "gctaa_departement WHERE de_ligue = ".$ligue." ORDER BY de_iddept";
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
            $sql = "SELECT de_nom FROM " . $wpdb->prefix . "gctaa_departement WHERE de_iddept ='".$code."'";
            // on envoie la requÍte
            return $result = $wpdb->get_var($sql);
        }
        
        public static function getLigue($code) {
			global $wpdb;
            $sql = "SELECT de_ligue FROM " . $wpdb->prefix . "gctaa_departement WHERE de_iddept ='".$code."'";
            // on envoie la requÍte
            return $result = $wpdb->get_var($sql);
        }
    }
?>