<?php
    
class Archer {
    private $_licence;
    private $_nom;
    private $_prenom;
    private $_date_naissance;
    private $_email;
    private $_photo;
    
    public function __construct(array $donnees) {
        $this->hydrate($donnees);
    }
    
    public function hydrate(array $donnees) {
        foreach ($donnees as $key => $value) {
            $key = str_replace( 'ar_' , '' , $key);
            $method = 'set'.ucfirst($key);
            if (method_exists($this, $method)) {
                $this->$method($value);
            }
        }
    }
    
    public function licence() { return $this->_licence; }
    public function nom() { return $this->_nom; }
    public function prenom() { return $this->_prenom; }
    public function email() { return $this->_email; }
    public function date_naissance() { return $this->_date_naissance; }
    public function photo() { return $this->_photo; }
    
    public function setLicence($licence) {	$this->_licence = $licence; }
    public function setNom($nom) { $this->_nom = $nom; }
    public function setPrenom($prenom) { $this->_prenom = $prenom; }
    public function setDate_naissance($dateNaissance) { $this->_date_naissance = $dateNaissance; }
    public function setEmail($email) { $this->_email = $email; }
    public function setPhoto($photo) { $this->_photo = $photo; }
    
    
    public function nomPrenom() { return $this->_nom . " " . $this->_prenom; }
    public function age() {
        list($annee, $mois, $jour) = explode('-', $this->_date_naissance);
        $today['mois'] = date('n');
        $today['jour'] = date('j');
        $today['annee'] = date('Y');
        $annees = $today['annee'] - $annee;
        if ($today['mois'] <= $mois) {
            if ($mois == $today['mois']) {
                if ($jour > $today['jour']) {
                    $annees--;
                }
            } else {
                $annees--;
            }
        }
        return $annees;
    }
    
    public static function liste() {
        $sql = "SELECT ar_licence, ar_nom, ar_prenom, ar_date_naissance, ar_email, ar_photo FROM GCTAA_archers ORDER BY ar_nom";
        
        // on envoie la requÍte
        $result = mysql_query($sql);
        
        if (!$result) {
            echo mysql_error();
        } else {
            $cpt=-1;
            $listeArcher = array();
            while($donneesArcher = mysql_fetch_assoc($result))
            {
                $cpt++;
                $archer = new Archer($donneesArcher);
                $listeArcher[$cpt] = $archer;
            }
        }
        return $listeArcher;
    }
    
    public static function listeCategories($licence) {
        $sql = "SELECT ct_categorie, tt_nom, ct_saison, ct_nom, ct_initiales FROM GCTAA_categories, GCTAA_archers_categ, GCTAA_typetir WHERE ct_categorie = ac_categorie and tt_code = ct_type and ac_licence = '$licence' order by ct_saison DESC, ct_type";        
        // on envoie la requÍte
        $result = mysql_query($sql);
        
        if (!$result) {
            echo mysql_error();
        } else {
            $cpt=-1;
            $listeCategories = array();
            while($donnees = mysql_fetch_assoc($result))
            {
                $cpt++;
                $listeCategories[$cpt] = $donnees;
            }
        }
        return $listeCategories;
    }
    
    public function affiche() {
        echo  '<table border="1">';
        echo  '<tr><td colspan="2" style="background:#123456;color:#ffffff;">Fiche archer</td></tr>' ;
        echo  '<tr><td width="150">Licence</td><td width="300">' . $this->licence() . '</td></tr>' ;
        echo  '<tr><td>Nom</td><td>' . $this->nom() . '</td></tr>' ;
        echo  '<tr><td>Prénom</td><td>' . $this->prenom() . '</td></tr>' ;
        echo  '<tr><td>Date de Naissance</td><td>' . Util::JJMMAAAA($this->date_naissance()) . '</td></tr>' ;
        echo  '<tr><td>Âge</td><td>' . $this->age() . '</td></tr>' ;
        echo  '</table><br />';
    }
    
    public function insertBDD() {
        $sql = "INSERT INTO GCTAA_archers (ar_licence, ar_nom, ar_prenom, ar_date_naissance, ar_email, ar_photo) VALUES ('".$this->licence()."', '".$this->nom()."', '".$this->prenom()."', '".$this->date_naissance()."', '".$this->email()."', '".$this->photo()."')";
        
        // on envoie la requÍte
        $result = mysql_query($sql);
        
        if (!$result) {
            return mysql_error();
		} else {
            return "";
        }
        
	}
    
    public static function selectBDD($licence) {
		// chargement d'un Archer
		$sql = "SELECT ar_licence, ar_nom, ar_prenom, ar_date_naissance, ar_email, ar_photo FROM GCTAA_archers WHERE ar_licence='".$licence."'";
        
        // on envoie la requÍte
        $result = mysql_query($sql);
        
		if (!$result) {
            echo mysql_error();
		} else {
			$cpt=0;
			if ($donneesArcher = mysql_fetch_assoc($result))
			{
				$archer = new Archer($donneesArcher);
				return $archer;
			}
		}
		return null;
	}
    
    
    public static function updateBDD($licence, $archer) {
		// chargement d'un Archer
		$sql = "UPDATE GCTAA_archers SET ar_licence = '".$archer->licence()."', ar_nom = '".$archer->nom()."', ar_prenom = '".$archer->prenom()."', ar_date_naissance = '".$archer->date_naissance()."', ar_email = '".$archer->email()."', ar_photo = '".$archer->photo()."' WHERE ar_licence='".$licence."'";
        
        // on envoie la requÍte
        $result = mysql_query($sql);
        
		if (!$result) {
            return mysql_error();
		}
		return "";
	}
    
    public static function deleteBDD($licence) {
		// suppression d'un archer des effectifs du club

		// on crÈe la requÍte SQL
		$sql = "DELETE FROM GCTAA_archers WHERE ar_licence = '".$licence."'";
        
		$result = mysql_query($sql);
        
		if (!$result) {
            return mysql_error();
		}
		return "";
	}
    
    public static function afficheListeArcher() {
        $hidden_field_name = 'GCTAA';
		$strRetour = '';
		$strRetour = $strRetour . '<table class="table table-bordered table-striped table-condensed table-hover">';
		$strRetour = $strRetour . '	<thead>';
		$strRetour = $strRetour . '	<tr>';
		$strRetour = $strRetour . '		<th><i class="icon-tag"></i> Licence</th>';
		$strRetour = $strRetour . '		<th colspan="2"><i class="icon-user"></i> Nom Pr&eacute;nom</th>';
		$strRetour = $strRetour . '		<th><i class="icon-calendar"></i> Date de Naissance</th>';
		$strRetour = $strRetour . '		<th><i class="icon-envelope"></i> E-mail</th>';
		$strRetour = $strRetour . '		<th><i class="icon-wrench"></i> Action</th>';
		$strRetour = $strRetour . '	</tr>';
		$strRetour = $strRetour . '	</thead>';
		$strRetour = $strRetour . '	<tbody>';
        
        $listeArcher = Archer::liste();
        foreach ($listeArcher as $archer) {
            $strRetour = $strRetour . '<tr>';
            $strRetour = $strRetour . '<td>'.$archer->licence().'</td>';
            $strRetour = $strRetour . '<td>'.$archer->nom().'</td>';
            $strRetour = $strRetour . '<td>'.$archer->prenom().'</td>';
            $strRetour = $strRetour . '<td>'.Util::JJMMAAAA($archer->date_naissance()).'</td>';
            $strRetour = $strRetour . '<td>'.$archer->email().'</td>';
            $strRetour = $strRetour . '<td><form name="form3" method="post" action="?page=ficheArcher"><input type="hidden" name="'.$hidden_field_name.'" value="A"><input type="hidden" name="licence" maxlength="7" size="7" value="'.$archer->licence().'" /><div class="btn-group"><button class="btn" type="submit" name="affiche"><i class="icon-user"></i></button><button class="btn" type="submit" name="modif"><i class="icon-pencil"></i></button><button class="btn" type="submit" name="supprime" onclick="javascript:check=confirm( \'Effacer cet Archer ? \');if(check==false) return false;"><i class="icon-trash"></i></button></div></form></td>';
            $strRetour = $strRetour . '</tr>';
        }
        
		$strRetour = $strRetour . '</tbody></table>';
		echo $strRetour;
	}
    
    public function afficheClassement($categorie) {
        // URL à ouvrir
        $url = 'http://ffta-public.cvf.fr/servlet/ResPalmares?NUM_ADH=' . substr($this->licence(), 0, 6) . '&CLASS_SELECT='.$categorie;
        $file = fopen ($url , "r");
        while (!feof ($file))
        {
            $line = fgets ($file);
            if(Util::stripos($line,'<table width="100%" border=0 cellspacing=4 cellpadding=2 valign=Top>') != 0)
            {
                for ($i = 1; $i <= 10; $i++) {
                    $line = fgets ($file);
                }
                $rang = str_replace( '<td class="Rang">' , '' , str_replace( '</td>' , '' , $line));
                
                $line = fgets ($file);
                $line = fgets ($file);
                for ($i = 1; $i <= 3; $i++) {
                    ${'tir'.$i} = trim(str_replace( '<td class="FaD">' , '' , str_replace( '</td>' , '' , $line)));
                    $line = fgets ($file);
                    $detail .= trim(${'tir'.$i});
                }
                $moyenne = str_replace( '<td align=right>' , '' , str_replace( '</td>' , '' , $line));
                echo '<td><abbr title="' . $tir1 . ' - ' . $tir2 . ' - ' . $tir3 . '">' . $moyenne . '</abbr></td>';
                echo "<td>" . $rang . "</td>";
            }
        }
        fclose($file);
    }
}

?>