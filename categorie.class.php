<?php
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
		 
			$sql = "SELECT ct_categorie, ct_type, ct_saison, ct_nom, ct_initiales FROM " . $wpdb->prefix . "gctaa_categories ORDER BY ct_saison DESC, ct_type DESC, ct_categorie";
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

        public static function varResAfficheEpreuve($categorie) {

            // ARME
            if(Util::stripos($categorie,'CL') != 0) {
                $arme = "C";
            } else if(Util::stripos($categorie,'CO') != 0) {
                $arme = "P";
            } else if(Util::stripos($categorie,'BB') != 0) {
                $arme = "B";
            }

            // SEXE
            if(Util::stripos($categorie,'F') != 0) {
                $sexe = "F";
            } else {
                $sexe = "H";
            }

            // CATAGE
            if(Util::stripos($categorie,'B') === 0) {
                $catage ="B";
            } else if(Util::stripos($categorie,'M') ===0) {
                $catage ="M";
            } else if(Util::stripos($categorie,'J') === 0) {
                $catage ="J";
            } else if(Util::stripos($categorie,'V') === 1) {
                $catage ="SV";
            } else if(Util::stripos($categorie,'S') === 0) {
                $catage ="S";
            } else if(Util::stripos($categorie,'V') === 0) {
                $catage ="V";
            } else if(Util::stripos($categorie,'C') === 0) {
                $catage ="C";
            }

            return array( 'SEXE' => $sexe,
                'ARME' => $arme,
                'CATAGE' => $catage
            );
        }
        
    }
?>