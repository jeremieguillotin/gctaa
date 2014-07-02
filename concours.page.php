<?php
    /*******************************************************************************************
     ***                                                                                     ***
     ***                                     CONCOURS                                        ***
     ***                                                                                     ***
     *******************************************************************************************/
    
    function admin_liste_concours($wp_gctaa){
        echo '<div class="wrap" id="gctaa">';
        $wp_gctaa->admin_affichemenupage("btn-small");
        echo '  <div class="page-header">';
        echo '      <h1>Concours <small>Liste des Concours</small></h1>';
        echo '  </div>';
        Concours::afficheListe();
        echo '</div>';
    }

    
    function admin_fiche_concours($wp_gctaa) {
        
        echo '<div class="wrap" id="gctaa">';
        $contenuPage = "";
        $erreur = "Page à développer";
        /*$hidden_field_name = "GCTAA";
        if ( isset($_POST[ $hidden_field_name ]) ) {
            $idclub =  $_POST[ 'idclub' ];
            if ($_POST[ $hidden_field_name ] == "modifOK" ) {
                // On execute les modifications
                $nvidclub =  $_POST[ 'nvidclub' ];
                $nom =  $_POST[ 'nom' ];
                $ville =  $_POST[ 'ville' ];
                $dept =  $_POST[ 'dept' ];
                $ligue =  $_POST[ 'ligue' ];
                $logo =  $_POST[ 'logo' ];
                $club = new Club(array( 'idclub' => $nvidclub,
                                       'nom' => $nom,
                                       'ville' => $ville,
                                       'dept' => $dept,
                                       'ligue' => $ligue,
                                       'logo' => $logo
                                       ));
                
                $erreur = Club::updateBDD($idclub, $club);
                if ( $erreur == "" ) {
                    $contenuPage="affiche";
                    $info = "Le club " . $nom . " a bien &eacute;t&eacute; modifi&eacute;.";
                    $idclub = $nvidclub;
                } else {
                    $contenuPage="modif";
                }
            } else {
                if (isset($_POST['affiche'])) {
                    // clic sur « Affiche »
                    $contenuPage="affiche";
                    //Chargement du club
                    $club = Club::selectBDD($idclub);
                    if ( $club != null ) {
                        $nom =  $club->nom();
                        $ville = $club->ville();
                        $logo = $club->logo();
                        $dept = $club->dept();
                        $ligue = $club->ligue();
                    } else {
                        $erreur = "Erreur lors du chargement du club ".$idclub.".";
                    }
                } elseif (isset($_POST['modif'])) {
                    // clic sur « Modifier »
                    $contenuPage="modif";
                    //Chargement du club
                    $club = Club::selectBDD($idclub);
                    if ( $club != null ) {
                        $nom =  $club->nom();
                        $ville = $club->ville();
                        $logo = $club->logo();
                        $dept = $club->dept();
                        $ligue = $club->ligue();
                    } else {
                        $erreur = "Erreur lors du chargement du club ".idclub.".";
                    }
                    
                } elseif (isset($_POST['supprime'])){
                    // clic sur « Supprimer »
                    $contenuPage="supprime";
                    //Suppression d'un Club
                    $erreur = Club::deleteBDD($idclub);
                    if ( $erreur == "" ) {
                        $info = "Le club " . $idclub . " a bien &eacute;t&eacute; supprim&eacute;.";
                    } else {
                        $erreur = "Erreur lors de la suppression du club ".$idclub.".";
                    }
                } else {
                    $erreur = "Qu'est-ce tu fous là ???";
                    //PAGE 404...
                }
            }*/
        
            $wp_gctaa->admin_affichemenupage("btn-small");
            
            if ( $info != "" ) {
                echo '<div class="alert alert-block alert-info">';
                echo '  <button type="button" class="close" data-dismiss="alert">&times;</button>';
                echo '  <h4>Info</h4>';
                echo $info;
                echo '</div>';
            }
            if ( $erreur != "" ) {
                echo '<div class="alert alert-block alert-error">';
                echo '<button type="button" class="close" data-dismiss="alert">&times;</button>';
                echo '<h4>Warning!</h4>';
                echo $erreur;
                echo '</div>';
            }
            
            
            /*
            //Affichage du contenu selon $contenuPage
            switch ($contenuPage) {
                case "affiche" :
                    echo '<div class="page-header">';
                    echo '<h1>Club <small>Fiche du club ' . $nom . '</small></h1>';
                    echo '</div>';
                    
                    echo '<div class="row-fluid">';
                    
                    echo '<div class="span2">';
                    echo '<img src="' . $logo . '" class="img-polaroid">';
                    echo '</div>';
                    
                    echo '<div class="span10">';
                    echo '      <div class="input-prepend">';
                    echo '          <span class="add-on"><i class="icon-tag"></i></span>';
                    echo '          <input style="height:30px;" class="span3" id="prependedInput" type="text" name="idclub" maxlength="7" size="7" value="' . $idclub . '" placeholder="ID Club" disabled>';
                    echo '      </div>';
                    echo '      <div class="input-prepend">';
                    echo '          <span class="add-on"><i class="icon-comment"></i></span>';
                    echo '          <input style="height:30px;" class="span5" id="prependedInput" type="text" name="nom" maxlength="100" size="100" value="' . $nom . '" placeholder="Nom" disabled>';
                    echo '      </div>';
                    echo '      <div class="input-prepend">';
                    echo '          <span class="add-on"><i class="icon-map-marker"></i></span>';
                    echo '          <input style="height:30px;" class="span5" id="prependedInput" type="text" name="ville" maxlength="100" size="32" value="' . $ville . '" placeholder="Ville" disabled>';
                    echo '      </div>';
                    echo '      <div class="input-prepend">';
                    echo '          <span class="add-on"><i class="icon-globe"></i></span>';
                    echo '          <input style="height:30px;" class="span5" id="prependedInput" type="text" name="dept" maxlength="100" size="32" value="' . Departement::libelle($club->dept()).' ('.$club->dept().')" placeholder="Département" disabled>';
                    echo '      </div>';
                    echo '      <div class="input-prepend">';
                    echo '          <span class="add-on"><i class="icon-globe"></i></span>';
                    echo '          <input style="height:30px;" class="span5" id="prependedInput" type="text" name="ligue" maxlength="200" size="70" value="' . Ligue::libelle($club->ligue()) . '" placeholder="Ligue" disabled>';
                    echo '      </div>';
                    echo '</div>';
                    echo '</div>';
                    
                    
                    
                    echo '<br />';
                    
                    
                    echo '<form name="action" method="post" action="?page=ficheClub">';
                    echo '<input type="hidden" name="'.$hidden_field_name.'" value="A">';
                    echo '<input type="hidden" name="idclub" maxlength="7" size="7" value="'.$idclub.'" />';
                    echo '<div class="btn-group">';
                    echo '<button class="btn btn-primary" type="submit" name="modif"><i class="icon-pencil icon-white"></i> Modifier ce Club</button>';
                    echo '<button class="btn btn-danger" type="submit" name="supprime" onclick="javascript:check=confirm( \'Effacer ce Club ? \');if(check==false) return false;"><i class="icon-trash icon-white"></i> Supprimer ce Club</button>';
                    echo '</div>';
                    echo '</form>';
                    
                    break;
                    
                case "modif" :
                    echo '<div class="page-header">';
                    echo '<h1>Club <small>Modification du club ' . $nom . '</small></h1>';
                    echo '</div>';
                    
                    echo '<form name="modifClub" method="post" action="">';
                    echo '  <fieldset>';
                    echo '      <input type="hidden" name="' . $hidden_field_name . '" value="modifOK">';
                    echo '      <input type="hidden" name="idclub" maxlength="7" size="7" value="' . $idclub . '" />';
                    echo '      <div class="input-prepend">';
                    echo '          <span class="add-on"><i class="icon-tag"></i></span>';
                    echo '          <input style="height:30px;" class="span3" id="prependedInput" type="text" name="nvidclub" maxlength="7" size="7" value="' . $idclub . '" placeholder="ID Club">';
                    echo '      </div>';
                    echo '      <div class="input-prepend">';
                    echo '          <span class="add-on"><i class="icon-comment"></i></span>';
                    echo '          <input style="height:30px;" class="span5" id="prependedInput" type="text" name="nom" maxlength="100" size="100" value="' . $nom . '" placeholder="Nom du club">';
                    echo '      </div>';
                    echo '      <div class="input-prepend">';
                    echo '          <span class="add-on"><i class="icon-map-marker"></i></span>';
                    echo '          <input style="height:30px;" class="span5" id="prependedInput" type="text" name="ville" maxlength="100" size="32" value="' . $ville . '" placeholder="Ville">';
                    echo '      </div>';
                    echo '      <select class="span3" name="ligue">';
                    $listeLigue = Ligue::liste();
                    foreach ($listeLigue as $lig) {
                        if ( $lig->idffta() == $ligue ) {
                            echo '          <option value="'.$lig->idffta().'" selected>' . $lig->nom() . '</option>';
                        } else {
                            echo '          <option value="'.$lig->idffta().'">' . $lig->nom() . '</option>';
                        }
                    }
                    echo '      </select>';
                    
                    echo '      <select name="dept" class="span3">';
                    $listeDepartement = Departement::liste();
                    foreach ($listeDepartement as $departement) {
                        if ( $departement->iddept() == $dept ) {
                            echo '          <option value="'.$departement->iddept().'" selected>' . $departement->nom() . '</option>';
                        } else {
                            echo '          <option value="'.$departement->iddept().'">' . $departement->nom() . '</option>';
                        }
                    }
                    echo '      </select>';
                    
                    echo '      <div class="input-prepend input-append">';
                    echo '          <span class="add-on"><i class="icon-picture"></i></span>';
                    echo '          <input style="height:30px;" class="span5" id="prependedInput" type="text" name="logo" maxlength="200" size="70" value="' . $logo . '" placeholder="Logo du club">';
                    echo '          <button class="btn" type="button">Parcourir...</button>';
                    echo '      </div>';
                    echo '      <br />';
                    echo '      <button type="submit" class="btn btn-primary">Valider les modifications</button>';
                    echo '  </fieldset>';
                    echo '</form>';
                    echo '<p>Les champs marqu&eacute; d\'un * sont obligatoires.</p>';
                    
                    break;
                    
                case "supprime" :
                    echo '  <div class="page-header">';
                    echo '      <h1>Club <small>Liste des Clubs</small></h1>';
                    echo '  </div>';
                    Club::afficheListeClubs();
                    break;
            }
        } else {
            $erreur = "Qu'est-ce tu fous là ???";
            //PAGE 404...
        }
             */
        echo '</div>';
    }
    
    
    function admin_import_concours($wp_gctaa) {
        echo '<div class="wrap" id="gctaa">';
        $contenuPage = "";
        $hidden_field_name = "GCTAA";
        if ( isset($_POST[ $hidden_field_name ]) ) {
            $discipline =  $_POST[ 'discipline' ];
            $ligue =  $_POST[ 'ligue' ];
            $dateDebut =  $_POST[ 'DateDebut' ];
            $dateFin =  $_POST[ 'DateFin' ];
            if ($_POST[ $hidden_field_name ] == "SelectCritere" ) {
                // On lance l'affichage de la liste des concours à importer
                $contenuPage="afficheListeImport";
            } elseif ($_POST[ $hidden_field_name ] == "ImportConcours" ) {
                $info = "Import Concours";
                // récupération des lignes à insérer
                if ( isset($_POST[ 'concours' ]) ) {
                    $listeDonneesConcours = $_POST[ 'concours' ];
                    if (wp_gctaa::$DEBUG) {
                        print_r($listeDonneesConcours);
                    }
                    foreach ($listeDonneesConcours as $DonneesConcours) {
                        list($idconcours, $saison, $datedebut, $datefin, $idclub, $desc, $type) = explode(';', $DonneesConcours);

                        $concours = new Concours(array( 'idconcours' => $idconcours,
                                                        'saison' => $saison,
                                                        'datedebut' => $datedebut,
                                                        'datefin' => $datefin,
                                                        'idclub' => $idclub,
                                                        'type' => $type,
                                                        'desc' => $desc
                                                        ));

                        $err = $concours->insertBDD();
                        if ($err != "") {
                            $erreur .= "Erreur lors de l'insertion du concours du " . $concours->datedebut() . " : " . $err . "<br />";
                        } else {
                            $info .= "Ajout avec succ&egrave;s du concours du " . $concours->datedebut() . "<br />";
                        }
                    }
                    $contenuPage="afficheListeConcours";
                } else {
                    $erreur = "Veuillez s&eacute;lectionner au moins un concours &agrave; importer !";
                    $contenuPage="afficheListeImport";
                }
                
            }
        } else {
            //premier passage pas de formulaire validé
            $contenuPage="afficheCritere";
        }
        
        $wp_gctaa->admin_affichemenupage("btn-small");
        if(!empty($info)){
			if ( $info != "" ) {
				echo '<div class="alert alert-block alert-info">';
				echo '  <button type="button" class="close" data-dismiss="alert">&times;</button>';
				echo '  <h4>Info</h4>';
				echo $info;
				echo '</div>';
			}
		}
		if(!empty($erreur)){
			if ( $erreur != "" ) {
				echo '<div class="alert alert-block alert-error">';
				echo '<button type="button" class="close" data-dismiss="alert">&times;</button>';
				echo '<h4>Warning!</h4>';
				echo $erreur;
				echo '</div>';
			}
		}
        
        
        
        //Affichage du contenu selon $contenuPage
        switch ($contenuPage) {
            case "afficheCritere" :
                echo '  <div class="page-header">';
                echo '      <h1>Concours <small>Importation de Concours</small></h1>';
                echo '  </div>';
                
                echo '<form name="importConcours" method="post" action="" class="form-horizontal">';
                echo '   <input type="hidden" name="' . $hidden_field_name . '" value="SelectCritere">';
                echo '   <div class="control-group">';
                echo '   <label class="control-label" for="selectDiscipline">Discipline</label>';
                echo '       <div class="controls">';
                echo '          <select name="discipline" id="selectDiscipline">';
                $listeTypeTir = TypeTir::liste();
                foreach ($listeTypeTir as $typeTir) {
                    echo '             <option value="' . $typeTir->code() . '" >' . $typeTir->nom() . '</option>';
                }
                echo '       </select>';
                echo '       </div>';
                echo '   </div>';
                echo '   <div class="control-group">';
                echo '   <label class="control-label" for="selectLigue">Ligue</label>';
                echo '       <div class="controls">';
                echo '          <select name="ligue" id="selectLigue">';
                echo '             <option value="">Toutes</option>';
                $listeLigue = Ligue::liste();
                foreach ($listeLigue as $ligue) {
                    echo '             <option value="' . $ligue->idffta() . '" >' . $ligue->nom() . '</option>';
                }
                echo '       </select>';
                echo '       </div>';
                echo '   </div>';
                echo '   <div class="control-group">';
                echo '   <label class="control-label" for="inputDateDebut">Date de d&eacute;but</label>';
                echo '       <div class="controls">';
                echo '          <input type="text" id="inputDateDebut" name="DateDebut" value="01/01/2014" size="10" maxlength="10">';
                echo '       </div>';
                echo '   </div>';
                echo '   <div class="control-group">';
                echo '   <label class="control-label" for="inputDateFin">Date de fin</label>';
                echo '       <div class="controls">';
                echo '          <input type="text" id="inputDateFin" name="DateFin" value="06/01/2014" size="10" maxlength="10">';
                echo '       </div>';
                echo '   </div>';
                echo '   <div class="control-group">';
                echo '      <div class="controls">';
                echo '         <button type="submit" class="btn btn-primary">Afficher les concours <i class="icon-chevron-down icon-white"></i> </button>';
                echo '      </div>';
                echo '   </div>';
                echo '</form>';
                break;
                
            case "afficheListeImport" :
                echo '  <div class="page-header">';
                echo '      <h1>Concours <small>S&eacute;lection des concours &agrave; importer</small></h1>';
                echo '  </div>';
                Concours::importConcours($discipline, $ligue, $dateDebut, $dateFin);
                break;
                /*
            case "afficheListeClubs" :
                echo '  <div class="page-header">';
                echo '      <h1>Club <small>Liste des Clubs</small></h1>';
                echo '  </div>';
                Club::afficheListeClubs();
                break;
                 */
        }
        
        echo '</div>';
    }
?>