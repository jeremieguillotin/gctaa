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
        global $wpdb;

        $sql = "SELECT ar_licence, ar_nom, ar_prenom, ar_date_naissance, ar_email, ar_photo FROM " . $wpdb->prefix . "gctaa_archers ORDER BY ar_nom";
        $donneesArchers = $wpdb->get_results($sql, ARRAY_A);

        $listeArcher = array();
        $cpt=-1;

        if ( $donneesArchers )
        {
            foreach ( $donneesArchers as $donneesArcher )
            {
                $cpt++;
                $archer = new Archer($donneesArcher);
                $listeArcher[$cpt] = $archer;
            }
        }
        else
        {
            echo 'erreur';
        }

        return $listeArcher;
    }

    public static function listeCategories($licence) {
        global $wpdb;
        $sql = "SELECT ct_categorie, tt_nom, ct_saison, ct_nom, ct_initiales FROM " . $wpdb->prefix . "gctaa_categories, " . $wpdb->prefix . "gctaa_archers_categ, " . $wpdb->prefix . "gctaa_typetir WHERE ct_categorie = ac_categorie and tt_code = ct_type and ac_licence = '$licence' order by ct_saison DESC, ct_type";
        // on envoie la requÍte
        $donneesCategories = $wpdb -> get_results($sql, ARRAY_A);
        $listeCategories = array();
        $cpt=-1;
        if ($donneesCategories) {
            foreach ( $donneesCategories as $donneesCategorie )
            {
                $cpt++;
                $listeCategories[$cpt] = $donneesCategorie;
            }
        } else {
            echo 'Erreur';
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
        global $wpdb;

        /*$sql = "INSERT INTO " . $wpdb->prefix . "gctaa_archers (ar_licence, ar_nom, ar_prenom, ar_date_naissance, ar_email, ar_photo) VALUES ('".$this->licence()."', '".$this->nom()."', '".$this->prenom()."', '".$this->date_naissance()."', '".$this->email()."', '".$this->photo()."')";
        // on envoie la requÍte
        $result = mysql_query($sql);*/

        $result = $wpdb->insert(
            $wpdb->prefix . 'gctaa_archers',
            array( 'ar_licence' => $this->licence(),'ar_nom' => $this->nom(),'ar_prenom' => $this->prenom(),'ar_date_naissance' => $this->date_naissance(),'ar_email' => $this->email(),'ar_photo' => $this->photo()),
            array( '%s', '%s','%s','%s','%s','%s')
        );

        if (!$result) {
            return mysql_error();
        } else {
            return "";
        }

    }

    public static function selectBDD($licence) {
        global $wpdb;
        // chargement d'un Archer
        $sql = "SELECT ar_licence, ar_nom, ar_prenom, ar_date_naissance, ar_email, ar_photo FROM " . $wpdb->prefix . "gctaa_archers WHERE ar_licence='".$licence."'";
        $donneesArcher = $wpdb->get_row($sql, ARRAY_A);

        if (!$donneesArcher) {
            echo 'Erreur';
        } else {
            $archer = new Archer($donneesArcher);
            return $archer;
        }
        return null;
    }


    public static function updateBDD($licence, $archer) {
        global $wpdb;
        // http://codex.wordpress.org/Class_Reference/wpdb#UPDATE_rows

<<<<<<< HEAD
		//$sql = "UPDATE " . $wpdb->prefix . "gctaa_archers SET ar_licence = '".$archer->licence()."', ar_nom = '".$archer->nom()."', ar_prenom = '".$archer->prenom()."', ar_date_naissance = '".$archer->date_naissance()."', ar_email = '".$archer->email()."', ar_photo = '".$archer->photo()."' WHERE ar_licence='".$licence."'";
=======
        //$sql = "UPDATE " . $wpdb->prefix . "gctaa_archers SET ar_licence = '".$archer->licence()."', ar_nom = '".$archer->nom()."', ar_prenom = '".$archer->prenom()."', ar_date_naissance = '".$archer->date_naissance()."', ar_email = '".$archer->email()."', ar_photo = '".$archer->photo()."' WHERE ar_licence='".$licence."'";
>>>>>>> origin/master
        //$result = mysql_query($sql);

        $result = $wpdb->update(
            $wpdb->prefix . 'gctaa_archers',
            array( 'ar_nom' => $archer->nom(),'ar_prenom' => $archer->prenom(),'ar_date_naissance' => $archer->date_naissance(),'ar_email' => $archer->email(),'ar_photo' => $archer->photo()),
            array( 'ar_licence' => $licence),
            array( '%s','%s','%s','%s','%s')
        );

        if (!$result) {
            return mysql_error();
        } else {
            return "";
        }
<<<<<<< HEAD
	}
    
=======
    }

>>>>>>> origin/master
    public static function deleteBDD($licence) {
        global $wpdb;
        // http://codex.wordpress.org/Class_Reference/wpdb#DELETE_Rows
        $result = $wpdb->delete(
            $wpdb->prefix . 'gctaa_archers',
            array( 'ar_licence' => $licence )
        );
<<<<<<< HEAD
		if (!$result) {
            return mysql_error();
		}
        else{
            return "";
        }
	}
    
=======
        if (!$result) {
            return mysql_error();
        }
        else{
            return "";
        }
    }

>>>>>>> origin/master
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