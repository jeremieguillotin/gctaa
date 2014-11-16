<?php
    class Ligue {
        private $_idffta;
        private $_code;
        private $_nom;
        
        public function __construct(array $donnees) {
            $this->hydrate($donnees);
        }
        
        public function hydrate(array $donnees)
        {
            foreach ($donnees as $key => $value)
            {
                $key = str_replace( 'li_' , '' , $key);
                $method = 'set'.ucfirst($key);
                if (method_exists($this, $method)) {
                    $this->$method($value);
                }
            }
        }
        
        public function toString() {
            return $this->_idffta . " - " .  $this->_code . " - " . $this->_nom;
        }
        public function idffta() { return $this->_idffta;}
        public function code() { return $this->_code; }
        public function nom() { return $this->_nom; }
        
        public function setIdffta($idffta) { $this->_idffta = $idffta;}
        public function setCode($code) { $this->_code = $code; }
        public function setNom($nom) { $this->_nom = $nom; }
        
        public static function liste() {
			global $wpdb;
		 
			$sql = "SELECT li_idffta, li_code, li_nom FROM " . $wpdb->prefix . "gctaa_ligue ORDER BY li_nom";
			$donneesLigues = $wpdb->get_results($sql, ARRAY_A);

			$listeLigues = array();
			$cpt=-1;
			
			if ( $donneesLigues )
			{
				foreach ( $donneesLigues as $donneesLigue )
				{
					$cpt++;
					$ligue = new Ligue($donneesLigue);
					$listeLigue[$cpt] = $ligue;
				}	
			}
			else
			{
				 echo 'erreur';
			}
			return $listeLigue;
		}
        
        public static function libelle($code) {
			global $wpdb;
            $sql = "SELECT li_nom FROM " . $wpdb->prefix . "gctaa_ligue WHERE li_idffta ='".$code."'";
            return $result = $wpdb->get_var($sql);
        }
        
        public static function afficheListeLigue() {
            $hidden_field_name = 'mt_submit_hidden';
            $strRetour = '';
            $strRetour = $strRetour . '<table class="widefat" cellspacing="0">';
            $strRetour = $strRetour . '	<thead>';
            $strRetour = $strRetour . '	<tr>';
            $strRetour = $strRetour . '		<th scope="col" >Id FFTA</th>';
            $strRetour = $strRetour . '		<th scope="col" >Code</th>';
            $strRetour = $strRetour . '		<th scope="col" >Ligue</th>';
            $strRetour = $strRetour . '		<th scope="col" >Liste des d&eacute;partements</th>';
            $strRetour = $strRetour . '		<th scope="col" colspan="2">Action</th>';
            $strRetour = $strRetour . '	</tr>';
            $strRetour = $strRetour . '	</thead>';
            $strRetour = $strRetour . '	<tbody>';
                
            $listeLigue = Ligue::liste();
            $cpt=0;
            foreach ($listeLigue as $ligue) {
                $cpt++;
                if ($cpt % 2 == 0) {
                    $strRetour = $strRetour . '<tr>';
                } else {
                    $strRetour = $strRetour . '<tr class="alternate">';
                }
                $strRetour = $strRetour . '<td>'.$ligue->idffta().'</td>';
                $strRetour = $strRetour . '<td>'.$ligue->code().'</td>';
                $strRetour = $strRetour . '<td>'.$ligue->nom().'</td>';
                //liste dept
                $listeDept = Departement::liste($ligue->idffta());
                $strDept = "";
                foreach ($listeDept as $dept) {
                    $strDept = $strDept ."#".$dept->iddept() . "#";
                }
                $strDept=str_replace('##',', ',$strDept);
                $strDept=str_replace('#','',$strDept);
                $strRetour = $strRetour . '<td>'.$strDept.'</td>';
                
                $strRetour = $strRetour . '<td> TAF </td>';
                $strRetour = $strRetour . '<td> TAF </td>';
                
            }
            
            $strRetour = $strRetour . '</tbody></table>';
            echo $strRetour;
        }
        
    }
?>