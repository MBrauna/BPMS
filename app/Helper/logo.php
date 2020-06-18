<?php
    if(!function_exists('logo_instancia')) {
        function logo_instancia() {
            try {
                $vSubdomain =   substr_count($_SERVER['HTTP_HOST'], '.') > 1 ? substr($_SERVER['HTTP_HOST'], 0, strpos($_SERVER['HTTP_HOST'], '.')) : '';
                $vSubdomain =   strtoupper($vSubdomain);

                $vReturn    =   '/img/dev.png';

                switch ($vSubdomain) {
                    case '1NT':
                        $vReturn    =   '/img/1nt/1nt.png';
                        break;
                    
                    case 'IPG':
                        $vReturn    =   '/img/ipg/ipg.png';
                        break;
                    
                    case 'DTF':
                        $vReturn    =   '/img/dtf/dtf.png';
                        break;
                    
                    case 'VZT':
                        $vReturn    =   '/img/vzt/vzt.png';
                        break;
                    default:
                        $vReturn    =   '/img/dev.png';
                        break;
                }

                return $vReturn;
            } // try { ... }
            catch(Exception $erro) {
                return '/img/dev.png';
            } // catch($erro) { ... }
        } // function logo_instancia() { ... }
    } // if(!function_exists('logo_instancia')) { ... }