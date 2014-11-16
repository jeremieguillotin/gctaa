<?php
    
    class Util {
        public static function JJMMAAAA($date) {
            list($annee, $mois, $jour) = explode('-', $date);
            return $jour."/".$mois."/".$annee;
        }
        
        public static function JJMMAAAAtoSQL($date) {
            list($jour, $mois, $annee) = explode('/', $date);
            return $annee."-".$mois."-".$jour;
        }

        public static function dateValide($date) {
            if ( preg_match("/^((((19|20)(([02468][048])|([13579][26]))-02-29))|((20[0-9][0-9])|(19[0-9][0-9]))-((((0[1-9])|(1[0-2]))-((0[1-9])|(1\d)|(2[0-8])))|((((0[13578])|(1[02]))-31)|(((0[1,3-9])|(1[0-2]))-(29|30)))))$/",$date) === 1 ) {
                return TRUE;
            }
            return FALSE;
        }
        
        public static function stripos($haystack, $needle, $offset = 0) {
            return strpos(strtolower($haystack), strtolower($needle), $offset);
        }
    }
    
?>