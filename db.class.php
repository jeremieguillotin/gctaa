<?php
    
    class Db {
        
        public static function createBDD() {
            global $wpdb;

            // gctaa_archers
            $tableName = $wpdb->prefix . "gctaa_archers";
            $sql = "CREATE TABLE " . $tableName . " (";
            $sql .= "ar_licence varchar(8) COLLATE latin1_general_ci NOT NULL,";
            $sql .= "ar_nom varchar(32) CHARACTER SET utf8 NOT NULL,";
            $sql .= "ar_prenom varchar(32) CHARACTER SET utf8 NOT NULL,";
            $sql .= "ar_date_naissance date NOT NULL DEFAULT '1900-01-01',";
            $sql .= "ar_email varchar(100) COLLATE latin1_general_ci NOT NULL,";
            $sql .= "ar_photo varchar(255) COLLATE latin1_general_ci NOT NULL,";
            $sql .= "PRIMARY KEY (ar_licence)";
            $sql .= ");";
            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
            dbDelta($sql);
            
            // gctaa_archers_categ
            $tableName = $wpdb->prefix . "gctaa_archers_categ";
            $sql = "CREATE TABLE " . $tableName . " (";
            $sql .= "ac_licence varchar(8) COLLATE latin1_general_ci NOT NULL,";
            $sql .= "ac_categorie int(10) NOT NULL,";
            $sql .= "ac_topdefaut int(1) NOT NULL";
            $sql .= ");";
            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
            dbDelta($sql);

            // gctaa_categories
            $tableName = $wpdb->prefix . "gctaa_categories";
            $sql = "CREATE TABLE " . $tableName . " (";
            $sql .= "ct_categorie int(10) NOT NULL,";
            $sql .= "ct_type varchar(1) COLLATE latin1_general_ci NOT NULL,";
            $sql .= "ct_saison int(4) NOT NULL,";
            $sql .= "ct_nom varchar(255) COLLATE latin1_general_ci NOT NULL,";
            $sql .= "ct_initiales varchar(10) COLLATE latin1_general_ci NOT NULL DEFAULT '-',";
            $sql .= "PRIMARY KEY (ct_categorie)";
            $sql .= ");";
            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
            dbDelta($sql);

            // gctaa_clubs
            $tableName = $wpdb->prefix . "gctaa_clubs";
            $sql = "CREATE TABLE " . $tableName . " (";
            $sql .= "cl_idclub int(11) NOT NULL,";
            $sql .= "cl_nom varchar(255) NOT NULL,";
            $sql .= "cl_ville varchar(255) NOT NULL,";
            $sql .= "cl_dept varchar(3) NOT NULL,";
            $sql .= "cl_ligue int(8) NOT NULL,";
            $sql .= "cl_logo varchar(255) NOT NULL,";
            $sql .= "PRIMARY KEY (cl_idclub)";
            $sql .= ");";
            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
            dbDelta($sql);
            
            // gctaa_concours
            $tableName = $wpdb->prefix . "gctaa_concours";
            $sql = "CREATE TABLE " . $tableName . " (";
            $sql .= "co_idconcours int(11) NOT NULL,";
            $sql .= "co_idclub int(11) NOT NULL,";
            $sql .= "co_type varchar(1) CHARACTER SET latin1 NOT NULL,";
            $sql .= "co_saison int(11) NOT NULL,";
            $sql .= "co_datedebut date NOT NULL,";
            $sql .= "co_datefin date NOT NULL,";
            $sql .= "co_desc varchar(250) CHARACTER SET latin1 COLLATE latin1_general_cs NOT NULL,";
            $sql .= "PRIMARY KEY (co_idconcours)";
            $sql .= ");";
            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
            dbDelta($sql);

            //gctaa_departement
            $tableName = $wpdb->prefix . "gctaa_departement";
            $sql = "CREATE TABLE " . $tableName . " (";
            $sql .= "de_iddept varchar(3) COLLATE latin1_general_ci NOT NULL,";
            $sql .= "de_nom varchar(255) COLLATE latin1_general_ci NOT NULL,";
            $sql .= "de_ligue int(8) NOT NULL,";
            $sql .= "PRIMARY KEY (de_iddept)";
            $sql .= ");";
            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
            dbDelta($sql);

            //gctaa_ligue
            $tableName = $wpdb->prefix . "gctaa_ligue";
            $sql = "CREATE TABLE " . $tableName . " (";
            $sql .= "li_idffta int(8) NOT NULL,";
            $sql .= "li_code varchar(8) COLLATE latin1_general_ci NOT NULL,";
            $sql .= "li_nom varchar(100) COLLATE latin1_general_ci NOT NULL,";
            $sql .= "PRIMARY KEY (li_idffta)";
            $sql .= ");";
            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
            dbDelta($sql);

            //gctaa_typetir
            $tableName = $wpdb->prefix . "gctaa_typetir";
            $sql = "CREATE TABLE " . $tableName . " (";
            $sql .= "tt_code varchar(1) COLLATE latin1_general_ci NOT NULL,";
            $sql .= "tt_nom varchar(255) COLLATE latin1_general_ci NOT NULL,";
            $sql .= "tt_ordre int(5) NOT NULL,";
            $sql .= "PRIMARY KEY (tt_code)";
            $sql .= ");";
            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
            dbDelta($sql);
                
        }
        
        public static function insertExempleBDD() {
            global $wpdb;
            
            //Exemple d'archers
            $sql = "INSERT INTO " . $wpdb->prefix . "gctaa_archers (ar_licence, ar_nom, ar_prenom, ar_date_naissance, ar_email, ar_photo) VALUES ";
            $sql .= "('365223G', 'GUILLOTIN', 'Jérémie', '1983-09-18', 'webmaster@snostiralarc.fr', 'http://127.0.0.1:8080/wordpress/wp-content/plugins/gctaa/images/jg.jpg'),";
            $sql .= "('675308F', 'MINET', 'Candice', '1994-10-17', 'candiceminet94@hotmail.fr', 'http://127.0.0.1:8080/wordpress/wp-content/plugins/gctaa/images/cm.jpg'),";
            $sql .= "('741927T', 'MINET', 'Patrick', '1963-04-15', 'patenath@msn.com', 'http://127.0.0.1:8080/wordpress/wp-content/plugins/gctaa/images/pm.jpg');";
            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
            dbDelta($sql);
            
            //gctaa_archers_categ
            $sql = "INSERT INTO " . $wpdb->prefix . "gctaa_archers_categ (ac_licence, ac_categorie, ac_topdefaut) VALUES ";
            $sql .= "('365223G', 6121, 1),";
            $sql .= "('365223G', 5460, 1),";
            $sql .= "('365223G', 5971, 1),";
            $sql .= "('741927T', 6123, 1),";
            $sql .= "('675308F', 6118, 1),";
            $sql .= "('741927T', 5460, 1),";
            $sql .= "('741927T', 4855, 1);";
            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
            dbDelta($sql);
            
            //gctaa_clubs
            $sql = "INSERT INTO " . $wpdb->prefix . "gctaa_clubs (cl_idclub, cl_nom, cl_ville, cl_dept, cl_ligue, cl_logo) VALUES ";
            $sql .= "(1602, 'ARCHERS DE LA MEE', 'CHATEAUBRIANT', '44', 711, 'nc.png'),";
            $sql .= "(1634, 'LES ARCHERS PIRIACAIS', 'PIRIAC SUR MER', '44', 711, 'nc.png'),";
            $sql .= "(1754, 'LES ARCHERS PORNICAIS', 'PORNIC', '44', 711, 'nc.png'),";
            $sql .= "(2377, 'LES FLECHES TURBALLAISES', 'LA TURBALLE', '44', 711, 'nc.png'),";
            $sql .= "(1536, 'CIE D''ARC OLIVIER DE CLISSON', 'CLISSON', '44', 711, 'nc.png'),";
            $sql .= "(1671, 'CIE DES ARCHERS DU PE', 'ST JEAN DE BOISEAU', '44', 711, 'nc.png'),";
            $sql .= "(1872, 'ARC NATURE VALLETAIS', 'VALLET', '44', 711, 'nc.png'),";
            $sql .= "(1762, 'ARC''HAYE', 'LA HAIE FOUASSIERE', '44', 711, 'nc.png'),";
            $sql .= "(1745, 'ARCHER PHILIBERTIN', 'ST PHILBERT DE GRAND LIEU', '44', 711, 'nc.png'),";
            $sql .= "(1578, 'ARCHERS D''ORVAULT', 'ORVAULT', '44', 711, 'nc.png'),";
            $sql .= "(1584, 'ARCHERS DE L''ERDRE', 'CARQUEFOU', '44', 711, 'nc.png'),";
            $sql .= "(1619, 'A.S.C.E. TIR A L''ARC', 'ST BREVIN LES PINS', '44', 711, 'nc.png'),";
            $sql .= "(1551, 'A.S.CORDEMAIS TIR ARC', 'CORDEMAIS', '44', 711, 'nc.png'),";
            $sql .= "(1604, 'ARCHERS DE ST HERBLAIN', 'ST HERBLAIN', '44', 711, 'nc.png'),";
            $sql .= "(234, 'S G T A - TIR A L''ARC', 'ANGERS', '49', 711, 'nc.png'),";
            $sql .= "(1866, 'ARCHERS DU GOTHA', 'MESANGER', '44', 711, 'nc.png');";
            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
            dbDelta($sql);
            
            //gctaa_concours
            $sql = "INSERT INTO " . $wpdb->prefix . "gctaa_concours (co_idconcours, co_idclub, co_type, co_saison, co_datedebut, co_datefin, co_desc) VALUES ";
            $sql .= "(32818, 104, 'S', 2014, '2014-01-04', '2014-01-05', 'Championnat d?partemental - 70000 - COMITE DEPARTEMENTAL HAUTE SAONE -'),";
            $sql .= "(33136, 1602, 'S', 2014, '2014-01-04', '2014-01-04', ''),";
            $sql .= "(33137, 1602, 'S', 2014, '2014-01-05', '2014-01-05', ''),";
            $sql .= "(33245, 389, 'S', 2014, '2014-01-04', '2014-01-05', 'LIGUE DU CENTRE DE TIR A L''ARC'),";
            $sql .= "(33344, 1619, 'S', 2014, '2014-01-26', '2014-01-26', 'Championnat d?partemental - 44000 - COMITE DEPARTEMENTAL LOIRE ATLANTIQUE -'),";
            $sql .= "(33362, 1604, 'S', 2014, '2014-01-19', '2014-01-19', 'Championnat d?partemental - 44000 - COMITE DEPARTEMENTAL LOIRE ATLANTIQUE -'),";
            $sql .= "(35422, 234, 'S', 2014, '2014-01-11', '2014-01-11', 'Championnat d?partemental - 49000 - COMITE DEPARTEMENTAL MAINE ET LOIRE -');";
            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
            dbDelta($sql);
            
        }
        
        public static function insertDataBDD() {
            global $wpdb;

            //gctaa_categories
            $sql = "INSERT INTO " . $wpdb->prefix . "gctaa_categories (ct_categorie, ct_type, ct_saison, ct_nom, ct_initiales) VALUES ";
            $sql .= "(6143, 'S', 2013, 'Cadet Femme Bare Bow', 'CFBB'),";
            $sql .= "(6144, 'S', 2013, 'Cadet Homme Bare Bow', 'CHBB'),";
            $sql .= "(6111, 'S', 2013, 'Scratch Femme Bare Bow', 'SFBB'),";
            $sql .= "(6110, 'S', 2013, 'Scratch Homme Bare Bow', 'SHBB'),";
            $sql .= "(6112, 'S', 2013, 'Benjamin Femme Classique', 'BFCL'),";
            $sql .= "(6113, 'S', 2013, 'Benjamin Homme Classique', 'BHCL'),";
            $sql .= "(6114, 'S', 2013, 'Minime Femme Classique', 'MFCL'),";
            $sql .= "(6115, 'S', 2013, 'Minime Homme Classique', 'MHCL'),";
            $sql .= "(6116, 'S', 2013, 'Cadet Femme Classique', 'CFCL'),";
            $sql .= "(6117, 'S', 2013, 'Cadet Homme Classique', 'CHCL'),";
            $sql .= "(6118, 'S', 2013, 'Junior Femme Classique', 'JFCL'),";
            $sql .= "(6119, 'S', 2013, 'Junior Homme Classique', 'JHCL'),";
            $sql .= "(6120, 'S', 2013, 'Senior Femme Classique', 'SFCL'),";
            $sql .= "(6121, 'S', 2013, 'Senior Homme Classique', 'SHCL'),";
            $sql .= "(6122, 'S', 2013, 'Vétéran Femme Classique', 'VFCL'),";
            $sql .= "(6123, 'S', 2013, 'Vétéran Homme Classique', 'VHCL'),";
            $sql .= "(6124, 'S', 2013, 'Super Vétéran Femme Classique', 'SVFCL'),";
            $sql .= "(6125, 'S', 2013, 'Super Vétéran Homme Classique', 'SVHCL'),";
            $sql .= "(6141, 'S', 2013, 'Cadet Femme Poulies', 'CFCO'),";
            $sql .= "(6142, 'S', 2013, 'Cadet Homme Poulies', 'CHCO'),";
            $sql .= "(6126, 'S', 2013, 'Junior Femme Poulies', 'JFCO'),";
            $sql .= "(6127, 'S', 2013, 'Junior Homme Poulies', 'JHCO'),";
            $sql .= "(6128, 'S', 2013, 'Senior Femme Poulies', 'SFCO'),";
            $sql .= "(6129, 'S', 2013, 'Senior Homme Poulies', 'SHCO'),";
            $sql .= "(6130, 'S', 2013, 'Vétéran Femme Poulies', 'VFCO'),";
            $sql .= "(6131, 'S', 2013, 'Vétéran Homme Poulies', 'VHCO'),";
            $sql .= "(6132, 'S', 2013, 'Super Vétéran Femme Poulies', 'SVFCO'),";
            $sql .= "(6133, 'S', 2013, 'Super Vétéran Homme Poulies', 'SVHCO'),";
            $sql .= "(6627, 'N', 2013, 'Benjamin Femme Bare Bow', 'BFBB'),";
            $sql .= "(6628, 'N', 2013, 'Benjamin Homme Bare Bow', 'BHBB'),";
            $sql .= "(6629, 'N', 2013, 'Minime Femme Bare Bow', 'MFBB'),";
            $sql .= "(6630, 'N', 2013, 'Minime Homme Bare Bow', 'MHBB'),";
            $sql .= "(6631, 'N', 2013, 'Cadet Femme Bare Bow', 'CFBB'),";
            $sql .= "(6632, 'N', 2013, 'Cadet Homme Bare Bow', 'CHBB'),";
            $sql .= "(6633, 'N', 2013, 'Junior Femme Bare Bow', 'JFBB'),";
            $sql .= "(6634, 'N', 2013, 'Junior Homme Bare Bow', 'JHBB'),";
            $sql .= "(6635, 'N', 2013, 'Senior Femme Bare Bow', 'SFBB'),";
            $sql .= "(6636, 'N', 2013, 'Senior Homme Bare Bow', 'SHBB'),";
            $sql .= "(6638, 'N', 2013, 'Vétéran Homme Bare Bow', 'VHBB'),";
            $sql .= "(6637, 'N', 2013, 'Vétéran Femme Bare Bow', 'VFBB'),";
            $sql .= "(6667, 'N', 2013, 'Junior Homme Arc Droit', '-'),";
            $sql .= "(6666, 'N', 2013, 'Junior Femme Arc Droit', '-'),";
            $sql .= "(6639, 'N', 2013, 'Senior Femme Arc Droit', '-'),";
            $sql .= "(6640, 'N', 2013, 'Senior Homme Arc Droit', '-'),";
            $sql .= "(6686, 'N', 2013, 'Vétéran Homme Arc Droit', '-'),";
            $sql .= "(6685, 'N', 2013, 'Vétéran Femme Arc Droit', '-'),";
            $sql .= "(6674, 'N', 2013, 'Junior Femme Arc Chasse', '-'),";
            $sql .= "(6675, 'N', 2013, 'Junior Homme Arc Chasse', '-'),";
            $sql .= "(6641, 'N', 2013, 'Senior Femme Arc Chasse', '-'),";
            $sql .= "(6642, 'N', 2013, 'Senior Homme Arc Chasse', '-'),";
            $sql .= "(6687, 'N', 2013, 'Vétéran Femme Arc Chasse', '-'),";
            $sql .= "(6688, 'N', 2013, 'Vétéran Homme Arc Chasse', '-'),";
            $sql .= "(6680, 'N', 2013, 'Cadet Femme Arc Libre', '-'),";
            $sql .= "(6681, 'N', 2013, 'Cadet Homme Arc Libre', '-'),";
            $sql .= "(6643, 'N', 2013, 'Junior Femme Arc Libre', '-'),";
            $sql .= "(6644, 'N', 2013, 'Junior Homme Arc Libre', '-'),";
            $sql .= "(6645, 'N', 2013, 'Senior Femme Arc Libre', '-'),";
            $sql .= "(6646, 'N', 2013, 'Senior Homme Arc Libre', '-'),";
            $sql .= "(6689, 'N', 2013, 'Vétéran Femme Arc Libre', '-'),";
            $sql .= "(6690, 'N', 2013, 'Vétéran Homme Arc Libre', '-'),";
            $sql .= "(6653, 'N', 2013, 'Junior Femme Poulies Nu', '-'),";
            $sql .= "(6654, 'N', 2013, 'Junior Homme Poulies Nu', '-'),";
            $sql .= "(6655, 'N', 2013, 'Senior Femme Poulies Nu', '-'),";
            $sql .= "(6656, 'N', 2013, 'Senior Homme Poulies Nu', '-'),";
            $sql .= "(6657, 'N', 2013, 'Vétéran Femme Poulies Nu', '-'),";
            $sql .= "(6658, 'N', 2013, 'Vétéran Homme Poulies Nu', '-'),";
            $sql .= "(6459, '3', 2013, 'Benjamin Femme Bare Bow', '-'),";
            $sql .= "(6460, '3', 2013, 'Benjamin Homme Bare Bow', '-'),";
            $sql .= "(6461, '3', 2013, 'Minime Femme Bare Bow', '-'),";
            $sql .= "(6462, '3', 2013, 'Minime Homme Bare Bow', '-'),";
            $sql .= "(6463, '3', 2013, 'Cadet Femme Bare Bow', '-'),";
            $sql .= "(6464, '3', 2013, 'Cadet Homme Bare Bow', '-'),";
            $sql .= "(6465, '3', 2013, 'Junior Femme Bare Bow', '-'),";
            $sql .= "(6466, '3', 2013, 'Junior Homme Bare Bow', '-'),";
            $sql .= "(6467, '3', 2013, 'Senior Femme Bare Bow', '-'),";
            $sql .= "(6468, '3', 2013, 'Senior Homme Bare Bow', '-'),";
            $sql .= "(6470, '3', 2013, 'Vétéran Homme Bare Bow', '-'),";
            $sql .= "(6469, '3', 2013, 'Vétéran Femme Bare Bow', '-'),";
            $sql .= "(6502, '3', 2013, 'Junior Homme Arc Droit', '-'),";
            $sql .= "(6501, '3', 2013, 'Junior Femme Arc Droit', '-'),";
            $sql .= "(6471, '3', 2013, 'Senior Femme Arc Droit', '-'),";
            $sql .= "(6472, '3', 2013, 'Senior Homme Arc Droit', '-'),";
            $sql .= "(6523, '3', 2013, 'Vétéran Femme Arc droit', '-'),";
            $sql .= "(6524, '3', 2013, 'Vétéran Homme Arc droit', '-'),";
            $sql .= "(6511, '3', 2013, 'Junior Femme Arc Chasse', '-'),";
            $sql .= "(6512, '3', 2013, 'Junior Homme Arc Chasse', '-'),";
            $sql .= "(6473, '3', 2013, 'Senior Femme Arc Chasse', '-'),";
            $sql .= "(6474, '3', 2013, 'Senior Homme Arc Chasse', '-'),";
            $sql .= "(6525, '3', 2013, 'Vétéran Femme Arc Chasse', '-'),";
            $sql .= "(6526, '3', 2013, 'Vétéran Homme Arc Chasse', '-'),";
            $sql .= "(6519, '3', 2013, 'Cadet Femme Arc Libre', '-'),";
            $sql .= "(6520, '3', 2013, 'Cadet Homme Arc Libre', '-'),";
            $sql .= "(6475, '3', 2013, 'Junior Femme Arc Libre', '-'),";
            $sql .= "(6476, '3', 2013, 'Junior Homme Arc Libre', '-'),";
            $sql .= "(6477, '3', 2013, 'Senior Femme Arc Libre', '-'),";
            $sql .= "(6478, '3', 2013, 'Senior Homme Arc Libre', '-'),";
            $sql .= "(6528, '3', 2013, 'Vétéran Homme Arc libre', '-'),";
            $sql .= "(6527, '3', 2013, 'Vétéran Femme Arc libre', '-'),";
            $sql .= "(6485, '3', 2013, 'Junior Femme Poulies Nu', '-'),";
            $sql .= "(6486, '3', 2013, 'Junior Homme Poulies Nu', '-'),";
            $sql .= "(6487, '3', 2013, 'Senior Femme Poulies Nu', '-'),";
            $sql .= "(6488, '3', 2013, 'Senior Homme Poulies Nu', '-'),";
            $sql .= "(6489, '3', 2013, 'Vétéran Femme Poulies Nu', '-'),";
            $sql .= "(6490, '3', 2013, 'Vétéran Homme Poulies Nu', '-'),";
            $sql .= "(6563, 'E', 2013, 'Benjamin Femme Arc Classique', '-'),";
            $sql .= "(6564, 'E', 2013, 'Benjamin Homme Arc Classique', '-'),";
            $sql .= "(6565, 'E', 2013, 'Minime Femme Arc Classique', '-'),";
            $sql .= "(6566, 'E', 2013, 'Minime Homme Arc Classique', '-'),";
            $sql .= "(6567, 'E', 2013, 'Cadet Femme Arc Classique', '-'),";
            $sql .= "(6568, 'E', 2013, 'Cadet Homme Arc Classique', '-'),";
            $sql .= "(6569, 'E', 2013, 'Junior Femme Arc Classique', '-'),";
            $sql .= "(6570, 'E', 2013, 'Junior Homme Arc Classique', '-'),";
            $sql .= "(6571, 'E', 2013, 'Senior Femme Arc Classique', '-'),";
            $sql .= "(6572, 'E', 2013, 'Senior Homme Arc Classique', '-'),";
            $sql .= "(6573, 'E', 2013, 'Vétéran Femme Arc Classique', '-'),";
            $sql .= "(6574, 'E', 2013, 'Vétéran Homme Arc Classique', '-'),";
            $sql .= "(6575, 'E', 2013, 'Super Vétéran Femme Arc Classique', '-'),";
            $sql .= "(6576, 'E', 2013, 'Super Vétéran Homme Arc Classique', '-'),";
            $sql .= "(6594, 'E', 2013, 'Cadet Femme Arc à poulies', '-'),";
            $sql .= "(6596, 'E', 2013, 'Cadet Homme Arc à poulies', '-'),";
            $sql .= "(6578, 'E', 2013, 'Junior Femme Arc à Poulies', '-'),";
            $sql .= "(6577, 'E', 2013, 'Junior Homme Arc à Poulies', '-'),";
            $sql .= "(6579, 'E', 2013, 'Senior Femme Arc à Poulies', '-'),";
            $sql .= "(6580, 'E', 2013, 'Senior Homme Arc à Poulies', '-'),";
            $sql .= "(6581, 'E', 2013, 'Vétéran Femme Arc à Poulies', '-'),";
            $sql .= "(6582, 'E', 2013, 'Vétéran Homme Arc à Poulies', '-'),";
            $sql .= "(6583, 'E', 2013, 'Super Vétéran Femme Arc à Poulies', '-'),";
            $sql .= "(6584, 'E', 2013, 'Super Vétéran Homme Arc à Poulies', '-'),";
            $sql .= "(6600, 'F', 2013, 'Benjamin Femme Arc classique', '-'),";
            $sql .= "(6601, 'F', 2013, 'Benjamin Homme Arc classique', '-'),";
            $sql .= "(6602, 'F', 2013, 'Minime Femme Arc classique', '-'),";
            $sql .= "(6603, 'F', 2013, 'Minime Homme Arc classique', '-'),";
            $sql .= "(6604, 'F', 2013, 'Cadet Femme Arc classique', '-'),";
            $sql .= "(6605, 'F', 2013, 'Cadet Homme Arc classique', '-'),";
            $sql .= "(6606, 'F', 2013, 'Junior Femme Arc classique', '-'),";
            $sql .= "(6607, 'F', 2013, 'Junior Homme Arc classique', '-'),";
            $sql .= "(6610, 'F', 2013, 'Vétéran Femme Arc classique', '-'),";
            $sql .= "(6611, 'F', 2013, 'Vétéran Homme Arc classique', '-'),";
            $sql .= "(6612, 'F', 2013, 'Super Vétéran Femme Arc classique', '-'),";
            $sql .= "(6613, 'F', 2013, 'Super Vétéran Homme Arc classique', '-'),";
            $sql .= "(6716, 'F', 2013, 'Scratch Femme Classique', '-'),";
            $sql .= "(6717, 'F', 2013, 'Scratch Homme Classique', '-'),";
            $sql .= "(6625, 'F', 2013, 'Cadet Femme Arc a poulies', '-'),";
            $sql .= "(6622, 'F', 2013, 'Cadet Homme Arc a poulies', '-'),";
            $sql .= "(6614, 'F', 2013, 'Junior Femme Arc à Poulies', '-'),";
            $sql .= "(6615, 'F', 2013, 'Junior Homme Arc à Poulies', '-'),";
            $sql .= "(6618, 'F', 2013, 'Vétéran Femme Arc à Poulies', '-'),";
            $sql .= "(6619, 'F', 2013, 'Vétéran Homme Arc à Poulies', '-'),";
            $sql .= "(6620, 'F', 2013, 'Super Vétéran Femme Arc à Poulies', '-'),";
            $sql .= "(6621, 'F', 2013, 'Super Vétéran Homme Arc à Poulies', '-'),";
            $sql .= "(6718, 'F', 2013, 'Scratch Femme Poulies', '-'),";
            $sql .= "(6719, 'F', 2013, 'Scratch Homme Poulies', '-'),";
            $sql .= "(5460, 'S', 2012, 'Senior Homme Classique', 'SHCL'),";
            $sql .= "(5971, 'F', 2012, 'Scratch Homme Poulies', 'SHCO'),";
            $sql .= "(4855, 'S', 2011, 'Senior Homme Classique', 'SHCL');";
            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
            dbDelta($sql);
            
            //gctaa_departement
            $sql = "INSERT INTO " . $wpdb->prefix . "gctaa_departement (de_iddept, de_nom, de_ligue) VALUES ";
            $sql .= "('01', 'Ain', 2234),";
            $sql .= "('02', 'Aisne', 840),";
            $sql .= "('03', 'Allier', 688),";
            $sql .= "('04', 'Alpes de Hautes-Provence', 665),";
            $sql .= "('05', 'Hautes-Alpes', 665),";
            $sql .= "('06', 'Alpes-Maritimes', 712),";
            $sql .= "('07', 'Ardèche', 2234),";
            $sql .= "('08', 'Ardennes', 721),";
            $sql .= "('09', 'Ariège', 731),";
            $sql .= "('10', 'Aube', 721),";
            $sql .= "('11', 'Aude', 703),";
            $sql .= "('12', 'Aveyron', 731),";
            $sql .= "('13', 'Bouches-du-Rhône', 665),";
            $sql .= "('14', 'Calvados', 270),";
            $sql .= "('15', 'Cantal', 688),";
            $sql .= "('16', 'Charente', 720),";
            $sql .= "('17', 'Charente-Maritime', 720),";
            $sql .= "('18', 'Cher', 713),";
            $sql .= "('19', 'Corrèze', 698),";
            $sql .= "('2A', 'Corse-du-Sud', 736),";
            $sql .= "('2B', 'Haute-Corse', 736),";
            $sql .= "('21', 'Côte-d''Or', 692),";
            $sql .= "('22', 'Côtes d''Armor', 723),";
            $sql .= "('23', 'Creuse', 698),";
            $sql .= "('24', 'Dordogne', 687),";
            $sql .= "('25', 'Doubs', 685),";
            $sql .= "('26', 'Drôme', 2234),";
            $sql .= "('27', 'Eure', 270),";
            $sql .= "('28', 'Eure-et-Loir', 713),";
            $sql .= "('29', 'Finistère', 723),";
            $sql .= "('30', 'Gard', 703),";
            $sql .= "('31', 'Haute-Garonne', 731),";
            $sql .= "('32', 'Gers', 731),";
            $sql .= "('33', 'Gironde', 687),";
            $sql .= "('34', 'Hérault', 703),";
            $sql .= "('35', 'Ille-et-Vilaine', 723),";
            $sql .= "('36', 'Indre', 713),";
            $sql .= "('37', 'Indre-et-Loire', 713),";
            $sql .= "('38', 'Isère', 2234),";
            $sql .= "('39', 'Jura', 685),";
            $sql .= "('40', 'Landes', 687),";
            $sql .= "('41', 'Loir-et-Cher', 713),";
            $sql .= "('42', 'Loire', 2234),";
            $sql .= "('43', 'Haute-Loire', 688),";
            $sql .= "('44', 'Loire-Atlantique', 711),";
            $sql .= "('45', 'Loiret', 713),";
            $sql .= "('46', 'Lot', 731),";
            $sql .= "('47', 'Lot-et-Garonne', 687),";
            $sql .= "('48', 'Lozère', 703),";
            $sql .= "('49', 'Maine-et-Loire', 711),";
            $sql .= "('50', 'Manche', 270),";
            $sql .= "('51', 'Marne', 721),";
            $sql .= "('52', 'Haute-Marne', 721),";
            $sql .= "('53', 'Mayenne', 711),";
            $sql .= "('54', 'Meurthe-et-Moselle', 704),";
            $sql .= "('55', 'Meuse', 704),";
            $sql .= "('56', 'Morbihan', 723),";
            $sql .= "('57', 'Moselle', 704),";
            $sql .= "('58', 'Nièvre', 692),";
            $sql .= "('59', 'Nord', 696),";
            $sql .= "('60', 'Oise', 840),";
            $sql .= "('61', 'Orne', 270),";
            $sql .= "('62', 'Pas-de-Calais', 696),";
            $sql .= "('63', 'Puy-de-Dôme', 688),";
            $sql .= "('64', 'Pyrénées-Atlantiques', 687),";
            $sql .= "('65', 'Hautes-Pyrénées', 731),";
            $sql .= "('66', 'Pyrénées-Orientales', 703),";
            $sql .= "('67', 'Bas-Rhin', 727),";
            $sql .= "('68', 'Haut-Rhin', 727),";
            $sql .= "('69', 'Rhône', 2234),";
            $sql .= "('70', 'Haute-Saône', 685),";
            $sql .= "('71', 'Saône-et-Loire', 692),";
            $sql .= "('72', 'Sarthe', 711),";
            $sql .= "('73', 'Savoie', 2234),";
            $sql .= "('74', 'Haute-Savoie', 2234),";
            $sql .= "('75', 'Paris', 1984),";
            $sql .= "('76', 'Seine-Maritime', 270),";
            $sql .= "('77', 'Seine-et-Marne', 1984),";
            $sql .= "('78', 'Yvelines', 1984),";
            $sql .= "('79', 'Deux-Sèvres', 720),";
            $sql .= "('80', 'Somme', 840),";
            $sql .= "('81', 'Tarn', 731),";
            $sql .= "('82', 'Tarn-et-Garonne', 731),";
            $sql .= "('83', 'Var', 712),";
            $sql .= "('84', 'Vaucluse', 665),";
            $sql .= "('85', 'Vendée', 711),";
            $sql .= "('86', 'Vienne', 720),";
            $sql .= "('87', 'Haute-Vienne', 698),";
            $sql .= "('88', 'Vosges', 704),";
            $sql .= "('89', 'Yonne', 692),";
            $sql .= "('90', 'Territoire-de-Belfort', 685),";
            $sql .= "('91', 'Essonne', 1984),";
            $sql .= "('92', 'Hauts-de-Seine', 1984),";
            $sql .= "('93', 'Seine-Saint-Denis', 1984),";
            $sql .= "('94', 'Val-de-Marne', 1984),";
            $sql .= "('95', 'Val-d''Oise', 1984);";
            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
            dbDelta($sql);

            //gctaa_ligue
            $sql = "INSERT INTO " . $wpdb->prefix . "gctaa_ligue (li_idffta, li_code, li_nom) VALUES ";
            $sql .= "(662, '00', 'Fédération'),";
            $sql .= "(665, '01', 'Provence'),";
            $sql .= "(840, '0200', 'Picardie'),";
            $sql .= "(685, '11', 'Franche Comte'),";
            $sql .= "(687, '12', 'Aquitaine'),";
            $sql .= "(270, '1300', 'Normandie'),";
            $sql .= "(688, '14', 'Auvergne'),";
            $sql .= "(692, '15', 'Bourgogne'),";
            $sql .= "(696, '17', 'Flandres'),";
            $sql .= "(698, '18', 'Limousin'),";
            $sql .= "(703, '20', 'Languedoc Roussillon'),";
            $sql .= "(704, '21', 'Lorraine'),";
            $sql .= "(711, '22', 'Pays De Loire'),";
            $sql .= "(712, '23', 'Cote D''azur'),";
            $sql .= "(713, '24', 'Centre'),";
            $sql .= "(1984, '2500', 'Ile De France'),";
            $sql .= "(720, '27', 'Poitou Charentes'),";
            $sql .= "(721, '28', 'Champagne Ardenne'),";
            $sql .= "(723, '29', 'Bretagne'),";
            $sql .= "(727, '31', 'Alsace'),";
            $sql .= "(731, '32', 'Midi Pyrenees'),";
            $sql .= "(2234, '33', 'Rhone Alpes'),";
            $sql .= "(736, '34', 'Corse'),";
            $sql .= "(738, '35', 'Reunion'),";
            $sql .= "(739, '36', 'Guyane'),";
            $sql .= "(744, '37', 'Guadeloupe'),";
            $sql .= "(745, '38', 'Nouvelle Calédonie'),";
            $sql .= "(747, '39', 'Martinique');";
            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
            dbDelta($sql);
            
            //gctaa_typetir
            $sql = "INSERT INTO " . $wpdb->prefix . "gctaa_typetir (tt_code, tt_nom, tt_ordre) VALUES ";
            $sql .= "('S', 'Tir en Salle', 1),";
            $sql .= "('F', 'Tir FITA', 3),";
            $sql .= "('E', 'Tir Fédéral', 2),";
            $sql .= "('B', 'Tir Beursault', 4),";
            $sql .= "('C', 'Tir Campagne', 5),";
            $sql .= "('N', 'Parcours Nature', 6),";
            $sql .= "('3', 'Tir sur cibles 3D', 7);";
            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
            dbDelta($sql);
        }
        
    }
    ?>";