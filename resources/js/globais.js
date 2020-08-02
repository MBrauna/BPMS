export const BPMS = {
    iniciaFiltro: function(){
        sessionStorage.setItem('filtroCodigo', "");
        sessionStorage.setItem('filtroTitulo', "");
        sessionStorage.setItem('filtroSituacao', "");
        sessionStorage.setItem('filtroEmpresa', "");
        sessionStorage.setItem('filtroProcesso', "");
        sessionStorage.setItem('filtroTipo', "");
        sessionStorage.setItem('filtroSolicitante', "");
        sessionStorage.setItem('filtroResponsavel', "");
    },
    coletaFiltro: function(){
        var vgbFiltro   =   {
            'codigo'        :   "",
            'titulo'        :   "",
            'situacao'      :   "",
            'empresa'       :   "",
            'processo'      :   "",
            'tipo'          :   "",
            'solicitante'   :   "",
            'responsavel'   :   "",
        };

        if(sessionStorage.getItem('filtroProcesso') === null) BPMS.iniciaFiltro();

        vgbFiltro.codigo        =   sessionStorage.getItem('filtroCodigo');
        vgbFiltro.titulo        =   sessionStorage.getItem('filtroTitulo');
        vgbFiltro.situacao      =   sessionStorage.getItem('filtroSituacao');
        vgbFiltro.empresa       =   sessionStorage.getItem('filtroEmpresa');
        vgbFiltro.processo      =   sessionStorage.getItem('filtroProcesso');
        vgbFiltro.tipo          =   sessionStorage.getItem('filtroTipo');
        vgbFiltro.solicitante   =   sessionStorage.getItem('filtroSolicitante');
        vgbFiltro.responsavel   =   sessionStorage.getItem('filtroResponsavel');

        return vgbFiltro;
    }
}
