<?php
/*******************************************************************************************
 ***                                                                                     ***
 ***                                      ARCHER                                         ***
 ***                                                                                     ***
 *******************************************************************************************/


function admin_liste_archers($wp_gctaa) {
    echo '<div class="wrap" id="gctaa">';
    $wp_gctaa->admin_affichemenupage("btn-small");
    echo '  <div class="page-header">';
    echo '      <h1>Archer <small>Liste des Archers</small></h1>';
    echo '  </div>';
    Archer::afficheListeArcher();
    echo '</div>';
}

function admin_fiche_archer($wp_gctaa) {

    echo '<div class="wrap" id="gctaa">';
    $contenuPage = "";

    $hidden_field_name = "GCTAA";
    if ( isset($_POST[ $hidden_field_name ]) ) {
        $licence =  $_POST[ 'licence' ];
        if ($_POST[ $hidden_field_name ] == "modifOK" ) {
            // On execute les modifications
            $nvLicence =  $_POST[ 'nvlicence' ];
            $nom =  $_POST[ 'nom' ];
            $prenom =  $_POST[ 'prenom' ];
            $datenaissance =  $_POST[ 'datenaissance' ];
            $mail =  $_POST[ 'mail' ];
            $photo =  $_POST[ 'photo' ];
            list($jour, $mois, $annee) = explode('/', $datenaissance);
            $date=$annee."-".$mois."-".$jour;

            $archer = new Archer(array( 'licence' => $nvLicence,
                'nom' => $nom,
                'prenom' => $prenom,
                'date_naissance' => $date,
                'email' => $mail,
                'photo' => $photo
            ));

            $erreur = Archer::updateBDD($licence, $archer);

            if ( $erreur == "" ) {
                $contenuPage="affiche";
                $info = "L'archer " . $nom . " " . $prenom . " a bien &eacute;t&eacute; modifi&eacute;.";
                $licence = $nvLicence;
            } else {
                $contenuPage="modif";
            }
        } else {
            if (isset($_POST['affiche'])) {
                // clic sur « Affiche »
                $contenuPage="affiche";
                //Chargement de l'Archer
                $archer = Archer::selectBDD($licence);
                if ( $archer != null ) {
                    $nom = $archer->nom();
                    $prenom = $archer->prenom();
                    $datenaissance = Util::JJMMAAAA($archer->date_naissance());
                    $mail = $archer->email();
                    $photo = $archer->photo();
                } else {
                    $erreur = "Erreur lors du chargement de l'archer ".$licence.".";
                }
            } elseif (isset($_POST['modif'])) {
                // clic sur « Modifier »
                $contenuPage="modif";
                //Chargement de l'Archer
                $archer = Archer::selectBDD($licence);
                if ( $archer != null ) {
                    $nom = $archer->nom();
                    $prenom = $archer->prenom();
                    $datenaissance = Util::JJMMAAAA($archer->date_naissance());
                    $mail = $archer->email();
                    $photo = $archer->photo();
                } else {
                    $erreur = "Erreur lors du chargement de l'archer ".$licence.".";
                }

            } elseif (isset($_POST['supprime'])){
                // clic sur « Supprimer »
                $contenuPage="supprime";
                //Suppression d'un Archer
                $erreur = Archer::deleteBDD($licence);
                if ( $erreur == "" ) {
                    $info = "L'archer " . $licence . " a bien &eacute;t&eacute; supprim&eacute;.";
                } else {
                    $erreur = "Erreur lors de la suppression de l'archer ".$licence.".";
                }
            } else {
                $erreur = "Qu'est-ce tu fous là ???";
                //PAGE 404...
            }
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
            case "affiche" :
                echo '<div class="page-header">';
                echo '<h1>Archer <small>Fiche de l\'Archer ' . $prenom . ' ' . $nom . '</small></h1>';
                echo '</div>';

                echo '<div class="row-fluid">';

                echo '<div class="span2">';
                echo '<img src="' . $photo . '" class="class="img-circle"">';
                echo '</div>';

                echo '<div class="span10">';
                echo '      <div class="input-prepend">';
                echo '          <span class="add-on"><i class="icon-tag"></i></span>';
                echo '          <input style="height:30px;" class="span3" id="prependedInput" type="text" name="nvlicence" maxlength="7" size="7" value="' . $licence . '" placeholder="Licence" disabled>';
                echo '      </div>';
                echo '      <div class="input-prepend">';
                echo '          <span class="add-on"><i class="icon-user"></i></span>';
                echo '          <input style="height:30px;" class="span5" id="prependedInput" type="text" name="nom" maxlength="100" size="100" value="' . $nom . '" placeholder="Nom de l\'archer" disabled>';
                echo '      </div>';
                echo '      <div class="input-prepend">';
                echo '          <span class="add-on"><i class="icon-user"></i></span>';
                echo '          <input style="height:30px;" class="span5" id="prependedInput" type="text" name="prenom" maxlength="100" size="32" value="' . $prenom . '" placeholder="Pr&eacute;nom de l\'archer" disabled>';
                echo '      </div>';
                echo '      <div class="input-prepend">';
                echo '          <span class="add-on"><i class="icon-calendar"></i></span>';
                echo '          <input style="height:30px;" class="span5" id="prependedInput" type="text" name="datenaissance" maxlength="100" size="32" value="' . $datenaissance . '" placeholder="Date de Naissance (JJ/MM/AAAA)" disabled>';
                echo '      </div>';
                echo '      <div class="input-prepend">';
                echo '          <span class="add-on"><i class="icon-envelope"></i></span>';
                echo '          <input style="height:30px;" class="span5" id="prependedInput" type="email" name="mail" maxlength="200" size="70" value="' . $mail . '" placeholder="E-mail de l\'archer" disabled>';
                echo '      </div>';
                echo '</div>';
                echo '</div>';



                echo '<br />';

                //Liste des catégories de l'archer


                echo '<h3>Cat&eacute;gories / Classement</h3>';

                echo '<table class="table table-bordered table-striped table-condensed table-hover">';
                echo '	<thead>';
                echo '	<tr>';
                echo '		<th><i class="icon-calendar"></i> Saison</th>';
                echo '		<th><i class="icon-th-list"></i> Type de tir</th>';
                echo '		<th><Nom de la cat&eacute;gorie</th>';
                echo '		<th>Code cat&eacute;gorie</th>';
                echo '		<th>Moyenne</th>';
                echo '		<th>Rang</th>';

                echo '	</tr>';
                echo '	</thead>';
                echo '<tbody>';

                foreach( Archer::listeCategories($licence) as $cat) {
                    echo '<tr>';
                    echo '<td>' . $cat['ct_saison'] . '</td>';
                    echo '<td>' . $cat['tt_nom'] . '</td>';
                    echo '<td>' . $cat['ct_nom'] . '</td>';
                    echo '<td>' . $cat['ct_initiales'] . '</td>';
                    //$archer->afficheClassement($cat['ct_categorie']);
                    echo '</tr>';

                }
                echo '</tbody>';
                echo '</table>';

                echo '<br />';

                echo '<form name="action" method="post" action="?page=ficheArcher">';
                echo '<input type="hidden" name="'.$hidden_field_name.'" value="A">';
                echo '<input type="hidden" name="licence" maxlength="7" size="7" value="'.$licence.'" />';
                echo '<div class="btn-group">';
                echo '<button class="btn btn-primary" type="submit" name="modif"><i class="icon-pencil icon-white"></i> Modifier cet Archer</button>';
                echo '<button class="btn btn-danger" type="submit" name="supprime" onclick="javascript:check=confirm( \'Effacer cet Archer ? \');if(check==false) return false;"><i class="icon-trash icon-white"></i> Supprimer cet Archer</button>';
                echo '</div>';
                echo '</form>';

                break;

            case "modif" :
                echo '<div class="page-header">';
                echo '<h1>Archer <small>Modification de l\'Archer ' . $prenom . ' ' . $nom . '</small></h1>';
                echo '</div>';

                echo '<form name="modifArcher" method="post" action="">';
                echo '  <fieldset>';
                echo '      <input type="hidden" name="' . $hidden_field_name . '" value="modifOK">';
                echo '      <input type="hidden" name="licence" maxlength="7" size="7" value="' . $licence . '" />';
                echo '      <div class="input-prepend">';
                echo '          <span class="add-on"><i class="icon-tag"></i></span>';
                echo '          <input style="height:30px;" class="span3" id="prependedInput" type="text" name="nvlicence" maxlength="7" size="7" value="' . $licence . '" placeholder="Licence">';
                echo '      </div>';
                echo '      <div class="input-prepend">';
                echo '          <span class="add-on"><i class="icon-user"></i></span>';
                echo '          <input style="height:30px;" class="span5" id="prependedInput" type="text" name="nom" maxlength="100" size="100" value="' . $nom . '" placeholder="Nom de l\'archer">';
                echo '      </div>';
                echo '      <div class="input-prepend">';
                echo '          <span class="add-on"><i class="icon-user"></i></span>';
                echo '          <input style="height:30px;" class="span5" id="prependedInput" type="text" name="prenom" maxlength="100" size="32" value="' . $prenom . '" placeholder="Pr&eacute;nom de l\'archer">';
                echo '      </div>';
                echo '      <div class="input-prepend">';
                echo '          <span class="add-on"><i class="icon-calendar"></i></span>';
                echo '          <input style="height:30px;" class="span5" id="prependedInput" type="text" name="datenaissance" maxlength="100" size="32" value="' . $datenaissance . '" placeholder="Date de Naissance (JJ/MM/AAAA)">';
                echo '      </div>';
                echo '      <div class="input-prepend">';
                echo '          <span class="add-on"><i class="icon-envelope"></i></span>';
                echo '          <input style="height:30px;" class="span5" id="prependedInput" type="email" name="mail" maxlength="200" size="70" value="' . $mail . '" placeholder="E-mail de l\'archer">';
                echo '      </div>';
                echo '      <div class="input-prepend input-append">';
                echo '          <span class="add-on"><i class="icon-picture"></i></span>';
                echo '          <input style="height:30px;" class="span5" id="prependedInput" type="text" name="photo" maxlength="200" size="70" value="' . $photo . '" placeholder="Photo de l\'archer">';
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
                echo '      <h1>Archer <small>Liste des Archers</small></h1>';
                echo '  </div>';
                Archer::afficheListeArcher();
                break;
        }
    } else {
        $erreur = "Qu'est-ce tu fous là ???";
        //PAGE 404...
    }
    echo '</div>';
}

function admin_ajoute_archer($wp_gctaa) {
    $hidden_field_name = "GCTAA";

    if ( isset($_POST[ $hidden_field_name ]) ) {
        if ($_POST[ $hidden_field_name ] == "A" ) {
            // On execute l'ajout
            $licence =  verif_licence_archer($_POST[ 'licence' ]);
            $nom =  $_POST[ 'nom' ];
            $prenom =  $_POST[ 'prenom' ];
            $datenaissance =  $_POST[ 'datenaissance' ];
            $mail =  $_POST[ 'mail' ];
            $photo =  $_POST[ 'photo' ];
            list($jour, $mois, $annee) = explode('/', $datenaissance);
            $date=$annee."-".$mois."-".$jour;

            if(isset($licence)){
                $archer = new Archer(array( 'licence' => $licence,
                    'nom' => $nom,
                    'prenom' => $prenom,
                    'date_naissance' => $date,
                    'email' => $mail,
                    'photo' => $photo
                ));

                $erreur = $archer->insertBDD();
                if ( $erreur == "" ) {
                    $contenuPage="afficheListeArcher";
                    $info = "L'archer " . $nom . " " . $prenom . " a bien &eacute;t&eacute; ajout&eacute;.";
                    $licence = $nvLicence;
                } else {
                    $contenuPage="AjoutArcher";
                }
            }
            else{
                $contenuPage="AjoutArcher";
            }
        } else {
            $contenuPage="AjoutArcher";
        }
    } else {
        $contenuPage="AjoutArcher";
    }


    echo '<div class="wrap" id="gctaa">';
    $wp_gctaa->admin_affichemenupage("btn-small");
    if( !empty($info)){
        if ( $info != "" ) {
            echo '<div class="alert alert-block alert-info">';
            echo '  <button type="button" class="close" data-dismiss="alert">&times;</button>';
            echo '  <h4>Info</h4>';
            echo $info;
            echo '</div>';
        }
    }
    if( !empty($erreur)){
        if ( $erreur != "" ) {
            echo '<div class="alert alert-block alert-error">';
            echo '<button type="button" class="close" data-dismiss="alert">&times;</button>';
            echo '<h4>Erreur !</h4>';
            echo $erreur;
            echo '</div>';
        }
    }



    //Affichage du contenu selon $contenuPage
    switch ($contenuPage) {
        case "afficheListeArcher" :
            echo '  <div class="page-header">';
            echo '      <h1>Archer <small>Liste des Archers</small></h1>';
            echo '  </div>';
            Archer::afficheListeArcher();
            break;


        case "AjoutArcher" :

            echo '  <div class="page-header">';
            echo '      <h1>Archer <small>Ajout d\'un Archer</small></h1>';
            echo '  </div>';
            echo '  <form name="AjoutArcher" method="post" action="">';
            echo '      <fieldset>';
            echo '          <input type="hidden" name="' . $hidden_field_name . '" value="A">';
            echo '          <div class="input-prepend">';
            echo '              <span class="add-on"><i class="icon-tag"></i></span>';
            echo '              <input style="height:30px;" class="span3" id="prependedInput" type="text" name="licence" maxlength="7" size="7" value="' . $licence . '" placeholder="Licence">';
            echo '          </div>';

            echo '          <div class="input-prepend">';
            echo '              <span class="add-on"><i class="icon-user"></i></span>';
            echo '              <input style="height:30px;" class="span5" id="prependedInput" type="text" name="nom" maxlength="100" size="100" value="' . $nom . '" placeholder="Nom de l\'archer">';
            echo '          </div>';
            echo '          <div class="input-prepend">';
            echo '              <span class="add-on"><i class="icon-user"></i></span>';
            echo '              <input style="height:30px;" class="span5" id="prependedInput" type="text" name="prenom" maxlength="100" size="32" value="' . $prenom . '" placeholder="Pr&eacute;nom de l\'archer">';
            echo '          </div>';
            echo '          <div class="input-prepend">';
            echo '              <span class="add-on"><i class="icon-calendar"></i></span>';
            echo '              <input style="height:30px;" class="span5" id="prependedInput" type="text" name="datenaissance" maxlength="100" size="32" value="' . $datenaissance . '" placeholder="Date de Naissance (JJ/MM/AAAA)">';
            echo '          </div>';

            echo '          <div class="input-prepend">';
            echo '              <span class="add-on"><i class="icon-envelope"></i></span>';
            echo '              <input style="height:30px;" class="span5" id="prependedInput" type="email" name="mail" maxlength="200" size="70" value="' . $mail . '" placeholder="E-mail de l\'archer">';
            echo '          </div>';
            echo '          <div class="input-prepend input-append">';
            echo '              <span class="add-on"><i class="icon-picture"></i></span>';
            echo '              <input style="height:30px;" class="span5" id="prependedInput" type="text" name="photo" maxlength="200" size="70" value="' . $photo . '" placeholder="Photo de l\'archer">';
            echo '              <button class="btn" type="button">Parcourir...</button>';
            echo '          </div>';
            echo '          <br />';
            echo '          <button type="submit" class="btn btn-primary">Ajouter cet Archer</button>';
            echo '      </fieldset>';
            echo '  </form>';
            echo '  <p>Les champs marqu&eacute; d\'un * sont obligatoires.</p>';
            echo '</div>';
            break;
    }
}

    function verif_licence_archer($licence){
        // Fonction de verification $licence -> 6 chiffres 1 lettre en majuscule
        // S'occupe de mettre la lettre en majuscule

        $chiffre_licence = substr($licence,0,6);
        $lettre_licence = substr($licence,6);
        if(is_numeric($chiffre_licence)){
            if(ctype_alpha($lettre_licence)){
                return $chiffre_licence.strtoupper($lettre_licence);
            }
            else{
                return null;
            }
        }
        else{
            return null;
        }
        return null;
    }
?>