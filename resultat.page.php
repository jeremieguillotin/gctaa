<?php
    /*******************************************************************************************
     ***                                                                                     ***
     ***                                     RESULTAT                                        ***
     ***                                                                                     ***
     *******************************************************************************************/

    function admin_import_resultat($wp_gctaa){
        echo '<div class="wrap" id="gctaa">';
        $wp_gctaa->admin_affichemenupage("btn-small");
        echo '  <div class="page-header">';
        echo '      <h1>Résultat <small>Import des Résultats</small></h1>';
        echo '  </div>';

        // Pour chaque concours connu 
        $listeConcours = Concours::liste();
        foreach ($listeConcours as $concours) {
            if ($concours->resultats() == 0) {
                Resultat::importResultatConcours($concours->idconcours());    
            }
            
        }

        echo '</div>';
    }

    function admin_affiche_resultats($wp_gctaa){
        echo '<div class="wrap" id="gctaa">';
        $wp_gctaa->admin_affichemenupage("btn-small");
        echo '  <div class="page-header">';
        echo '      <h1>Résultat <small>Affichage des Résultats</small></h1>';
        echo '  </div>';

        // Pour chaque concours connu 
        $listeConcours = Concours::liste();
        foreach ($listeConcours as $concours) {
            
            echo Resultat::resultats_concours($concours->idconcours());
            
        }

        echo '</div>';

    }



?>