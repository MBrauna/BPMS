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

                    case 'DSB':
                        $vReturn    =   '/img/dsb/disbral.jpg';

                    default:
                        $vReturn    =   '/img/dev.png';
                        break;
                }

                return $vReturn.'-----'.$vSubdomain;
            } // try { ... }
            catch(Exception $erro) {
                return '/img/dev.png';
            } // catch($erro) { ... }
        } // function logo_instancia() { ... }
    } // if(!function_exists('logo_instancia')) { ... }


    if(!function_exists('nome_instancia')) {
        function nome_instancia() {
            try {
                $vSubdomain =   substr_count($_SERVER['HTTP_HOST'], '.') > 1 ? substr($_SERVER['HTTP_HOST'], 0, strpos($_SERVER['HTTP_HOST'], '.')) : '';
                $vSubdomain =   strtoupper($vSubdomain);

                $vReturn    =   'BPMS*';

                switch ($vSubdomain) {
                    case 'BPMS':
                        $vReturn    =   'BPMS';
                        break;

                    case '1NT':
                        $vReturn    =   '1NessTech';
                        break;
                    
                    case 'IPG':
                        $vReturn    =   'Instituto Panamericano de Gestão';
                        break;
                    
                    case 'DTF':
                        $vReturn    =   'Data Traffic';
                        break;
                    
                    case 'VZT':
                        $vReturn    =   'Vizentec';
                        break;
                    
                    case 'DSB':
                        $vReturn    =   'Disbral';
                        break;

                    default:
                        $vReturn    =   'BPMS*';
                        break;
                }

                return $vReturn.' ---- '.$vSubdomain;
            } // try { ... }
            catch(Exception $erro) {
                return 'BPMS*';
            } // catch($erro) { ... }
        } // function logo_instancia() { ... }
    } // if(!function_exists('logo_instancia')) { ... }