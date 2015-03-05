<?php
    class Resultat {
        private $_idresultat;
        private $_licencearcher;
        private $_idconcours;
        private $_place;
        private $_score1;
        private $_score2;
        private $_score3;
        private $_score4;
        
        public function __construct(array $donnees) {
            $this->hydrate($donnees);
        }
        
        public function hydrate(array $donnees) {
            foreach ($donnees as $key => $value) {
                $key = str_replace( 're_' , '' , $key);
                $method = 'set'.ucfirst($key);
                if (method_exists($this, $method)) {
                    $this->$method($value);
                }
            }
        }
        
        public function idresultat() { return $this->_idresultat; }
        public function licencearcher() { return $this->_licencearcher; }
        public function idconcours() { return $this->_idconcours; }
        public function place() { return $this->_place; }
        public function score1() { return $this->_score1; }
        public function score2() { return $this->_score2; }
        public function score3() { return $this->_score3; }
        public function score4() { return $this->_score4; }
        
        public function setIdresultat($idresultat) { $this->_idresultat = $idresultat; }
        public function setLicencearcher($licencearcher) { $this->_licencearcher = $licencearcher; }
        public function setIdconcours($idconcours) { $this->_idconcours = $idconcours; }
        public function setPlace($place) { $this->_place = $place; }
        public function setScore1($score1) { $this->_score1 = $score1; }
        public function setScore2($score2) { $this->_score2 = $score2; }
        public function setScore3($score3) { $this->_score3 = $score3; }
        public function setScore4($score4) { $this->_score4 = $score4; }

        public static function selectBDD($idresultat) {
            global $wpdb;
            // chargement d'un Club
            $sql = "SELECT re_idresultat, re_licencearcher, re_idconcours, re_place, re_score1, re_score2, re_score3, re_score4 FROM " . $wpdb->prefix . "gctaa_resultat WHERE re_idresultat = ".$idresultat;
            $donneesResultat = $wpdb->get_row($sql, ARRAY_A);
            
            if (!$donneesResultat) {
                echo "Erreur lors du chargement du résultat (" . $idconcours . ") : Résultat non trouvé.";
            } else {
                $resultat = new Resultat($donneesResultat);
                return $resultat;
            }
            return null;
        }

        public function insertBDD() {
            global $wpdb;

            $result = $wpdb->insert(
                $wpdb->prefix . 'gctaa_resultat',
                array('re_licencearcher' => $this->licencearcher(), 're_idconcours' => $this->idconcours(), 're_place' => $this->place(), 're_score1' => $this->score1(), 're_score2' => $this->score2(), 're_score3' => $this->score3(), 're_score4' => $this->score4()),
                array( '%s', '%d','%d','%d','%d','%d')
            );

            if ( false === $result ) {
                return "Impossible d'ajouter le resultat (" . $this->licencearcher()." - ".$this->idconcours() . ") : " . $wpdb->last_error;
            } else {
                return "";
            }
        }
        public static function updateBDD($idresultat, $resultat) {
            global $wpdb;
            $result = $wpdb->update(
                $wpdb->prefix . 'gctaa_resultat',
                array('re_licencearcher' => $resultat->licencearcher(), 're_idconcours' => $resultat->idconcours(), 're_place' => $resultat->place(), 're_score1' => $resultat->score1(), 're_score2' => $resultat->score2(), 're_score3' => $resultat->score3(), 're_score4' => $resultat->score4()),
                array( 're_resultat' => $idresultat),
                array( '%s', '%d','%d','%d','%d','%d')
            );
            if ( false === $result ) {
                return "Impossible de mettre à jour le résultat (" . $idresultat . ") : " . $wpdb->last_error;
            } elseif ( 0 === $result ) {
                return "Impossible de mettre à jour le résultat (" . $idresultat . ") : Résultat non trouvé.";
            } elseif ( 0 < $result ) {
                return "";
            }
        }
        public static function deleteBDD($idresultat) {
            global $wpdb;
            // http://codex.wordpress.org/Class_Reference/wpdb#DELETE_Rows
            $result = $wpdb->delete(
                $wpdb->prefix . 'gctaa_resultat',
                array( 're_resultat' => $idresultat)
            );
            if ( false === $result ) {
                return "Impossible de mettre à jour le résultat (" . $idresultat . ") : " . $wpdb->last_error;
            } elseif ( 0 === $result ) {
                return "Impossible de mettre à jour le résultat (" . $idresultat . ") : Résultat non trouvé.";
            } elseif ( 0 < $result ) {
                return "";
            }
        }
        
        public static function importResultatConcours($idConcours) {


            // Récupération de la saison du concours :
            $concours = Concours::selectBDD($idConcours);
            echo '<br>' . Club::libelle($concours->idclub()) .' > Concours du '.$concours->datedebut().'<br>';
            $saison = $concours->saison();
            $resultatsCharges = false;
            // Récupération de la liste des archers ayant une catégorie active sur cette saison
            $listeCategArcherActif = Archer::listeCategArcherActif($saison);
            $urlEnCours = '';
            foreach ($listeCategArcherActif as $CategArcherActif) {
                $categorie = $CategArcherActif['ct_initiales'];
                $var = Categorie::varResAfficheEpreuve($categorie);
                $url = 'http://ffta-public.cvf.fr/servlet/ResAffichEpreuve?NUM_EPR='.$idConcours.'&SEXE='.$var['SEXE'].'&CATAGE='.$var['CATAGE'].'&ARME='.$var['ARME'].'&FLAG=OK';
                $archer = $CategArcherActif['ar_nom'] . " " . $CategArcherActif['ar_prenom'];
                //echo $categorie . ' > ' . $url . '<br>';
                if ($urlEnCours != $url) {
                    $tabLigne = file($url);
                    $urlEnCours = $url;
                }

                if ( $tabLigne !== false ) {
                    $result =  array_search( "\t<td NOWRAP>".$archer."</td>\n", $tabLigne);

                    if ($result !== false) {
                        $place = trim(str_replace('</b></td>',' ',str_replace('<td NOWRAP><b>',' ',$tabLigne[$result-1])));
                        $score1 = trim(str_replace('</td>',' ',str_replace('<td NOWRAP align="right">',' ',$tabLigne[$result+2])));
                        $score2 = trim(str_replace('</td>',' ',str_replace('<td NOWRAP align="right">',' ',$tabLigne[$result+3])));
                        $total = trim(str_replace('</b></td>',' ',str_replace('<td NOWRAP align="right"><b>',' ',$tabLigne[$result+4])));

                        $resultat = new Resultat(array( 'licencearcher' => $CategArcherActif['ar_licence'],
                                                        'idconcours' => $idConcours, 
                                                        'place' => $place, 
                                                        'score1' => $score1,
                                                        'score2' => $score2, 
                                                        'score3' => $score3, 
                                                        'score4' => $score4
                                                        ));

                        $resultat->insertBDD();
                        $resultatsCharges = true;
                        echo $archer . ' ('.$CategArcherActif['ar_licence'].') : ' .$score1 . ' + ' . $score2 . ' = ' . $total . ' => ' . $place . '<br>';
                    }
                } 

            }

            if ($resultatsCharges) {
                $concours->setResultats(1);
                Concours::updateBDD($idConcours, $concours);
            } else {
                echo 'Pas de résultat en ligne ou pas de connexion internet.';
            }   

        }

        public static function resultatConcours($idConcours) {
            global $wpdb;
            $sql = "SELECT ct_nom, re_place, ar_nom, ar_prenom, re_score1, re_score2, re_score3, re_score4 FROM " . $wpdb->prefix . "gctaa_resultat  INNER JOIN " . $wpdb->prefix . "gctaa_concours ON re_idconcours = co_idconcours INNER JOIN " . $wpdb->prefix . "gctaa_archers ON re_licencearcher = ar_licence INNER JOIN " . $wpdb->prefix . "gctaa_archers_categ ON ar_licence = ac_licence INNER JOIN " . $wpdb->prefix . "gctaa_categories ON ct_categorie = ac_categorie AND ct_saison = co_saison WHERE re_idconcours = ".$idConcours." ORDER BY ct_categorie, re_place";
            $resultatsConcours = $wpdb->get_results($sql, ARRAY_A);

            return $resultatsConcours;
        }

        public static function resultats_concours($idConcours){
            $concours = Concours::selectBDD($idConcours);
            $resultatHTML = '';
            if ($concours->resultats() != 0) {
                //Si il y a des resultat 
                $resultatHTML .= '<h3>Résultats du concours de ' . Club::libelleVille($concours->idclub()) .' du '.Util::JJMMAAAA($concours->datedebut()) . '</h3>';

                $resultatsConcours = Resultat::resultatConcours($concours->idconcours()); 

            
                if (!$resultatsConcours) {
                    echo "Erreur lors du chargement des résultat (" . $idconcours . ") : Résultats non trouvé.";
                } else {
                    $categoriePrecedente = '';
                    foreach ($resultatsConcours as $resultat) {
                        $categorie = $resultat['ct_nom'];
                        $place = $resultat['re_place'];
                        $nom = $resultat['ar_nom'];
                        $prenom = $resultat['ar_prenom'];
                        $score1 = $resultat['re_score1'];
                        $score2 = $resultat['re_score2'];
                        $score3 = $resultat['re_score3'];
                        $score4 = $resultat['re_score4'];

                        if ($categoriePrecedente != $categorie) {
                            if ($categoriePrecedente != '') {
                                //Ferme le tableau précédent
                                $resultatHTML .= '</tbody>';
                                $resultatHTML .= '</table>';
                                $resultatHTML .= '<p></p>';
                            }

                            $categoriePrecedente = $categorie;
                            $resultatHTML .= '<table class="table table-bordered table-striped table-condensed table-hover"> ';
                            $resultatHTML .= '<thead>';
                            $resultatHTML .= '<tr><td class="hed" colspan="5" align="left"><b>'.Util::Majuscule($categorie).'</b></td></tr>';
                            $resultatHTML .= '<tr><td width="50">Clt</td><td width="400">Nom</td><td width="50">Score</td></tr>';
                            $resultatHTML .= '</thead>';
                            $resultatHTML .= '<tbody>';
                        }

                        $resultatHTML .= '<tr>';
                        $resultatHTML .= '<td>'.$place;
                        if ($place == 1) {
                            $resultatHTML .= '&nbsp; <font color="#e1b425"><i class="fa fa-trophy"></i></font></td>';
                        } else if ($place == 2) {
                            $resultatHTML .= '&nbsp; <font color="#828b91"><i class="fa fa-trophy"></i></font></td>';
                        } else if ($place == 3) {
                            $resultatHTML .= '&nbsp; <font color="#7b5643"><i class="fa fa-trophy"></i></font></td>';
                        } else {
                            $resultatHTML .= '</td>';
                        }

                                  
                        
                        $resultatHTML .= '<td>'.$nom.' ' .$prenom.'</td>';
                        $resultatHTML .= '<td>'.($score1 + $score2).'</td>';
                        $resultatHTML .= '</tr>';

                    }

                    if ($categoriePrecedente != '') {
                        //Ferme le tableau précédent
                        $resultatHTML .= '</tbody>';
                        $resultatHTML .= '</table>';
                        $resultatHTML .= '<p></p>';
                    }

                }

            }
            return $resultatHTML;

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
                $strRetour = $strRetour . '<td><form name="formListeConcours" method="post" action="?page=ficheConcours"><input type="hidden" name="'.$hidden_field_name.'" value="A"><input type="hidden" name="idconcours" maxlength="10" size="10" value="'.$concours->idconcours().'" /><div class="btn-group"><button class="btn btn-small" type="submit" name="affiche"><i class="fa fa-bullseye"></i></button><button class="btn btn-small" type="submit" name="modif"><i class="icon-pencil"></i></button><button class="btn btn-small" type="submit" name="supprime" onclick="javascript:check=confirm( \'Effacer ce concours ? \');if(check==false) return false;"><i class="icon-trash"></i></button></div></form></td>';
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