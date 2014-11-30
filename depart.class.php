<?php
    class Depart {
        private $_idconcours;
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
        
        public function idconcours() { return $this->_idconcours; }
        public function numero() { return $this->_numero;}
        public function date_debut() { return $this->_date_debut; }
        public function heure_debut() { return $this->_heure_debut; }
        
        public function setIdconcours($idconcours) { $this->_idconcours = $idconcours; }
        public function setNumero($num) { $this->_numero = $num;}
        public function setDate_debut($date) { $this->_date_debut = $date; }
        public function setHeure_debut($heure) { $this->_heure_debut = $heure; }
        
        public static function liste($concours) {
            global $wpdb;
            
            $sql = "SELECT dc_idconcours, dc_numero, dc_date_debut, dc_heure_debut FROM " . $wpdb->prefix . "gctaa_departs_concours WHERE dc_idconcours = ".$concours. " ORDER BY dcnumero";
           
			$donneesDeparts = $wpdb->get_results($sql, ARRAY_A);
            
			$listeDeparts = array();
			$cpt=-1;
			
			if ( $donneesDepartements )
			{
				foreach ( $donneesDeparts as $donneesDepart )
				{
					$cpt++;
                    $depart = new Depart($donneesDepart);
                    $listeDeparts[$cpt] = $depart;
				}
			}
			else
			{
                echo 'erreur Liste Départ';
			}
            return $listeDeparts;
        }
        
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
?>