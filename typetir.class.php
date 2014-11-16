<?php
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
            $sql = "SELECT tt_code, tt_nom, tt_ordre FROM " . $wpdb->prefix . "gctaa_typetir ORDER BY tt_ordre";
            
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
            $sql = "SELECT tt_nom FROM " . $wpdb->prefix . "gctaa_typetir WHERE tt_code ='".$code."'";
            // on envoie la requÍte
            $result = $wpdb->get_var($sql);            
            if ($result) {
                return $code;
            } else {
                return $result;
            }
        }
        
    }
?>