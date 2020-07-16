export const BPMS = {
    iniciaFiltro: function(){
        sessionStorage.setItem('filtroCodigo', null);
        sessionStorage.setItem('filtroTitulo', null);
        sessionStorage.setItem('filtroSituacao', "");
        sessionStorage.setItem('filtroEmpresa', "");
        sessionStorage.setItem('filtroProcesso', "");
        sessionStorage.setItem('filtroTipo', "");
    },
    coletaFiltro: function(){
        var vgbFiltro   =   {
            'codigo'    :   null,
            'titulo'    :   null,
            'situacao'  :   "",
            'empresa'   :   "",
            'processo'  :   "",
            'tipo'      :   "",
        };

        if(sessionStorage.getItem('filtroProcesso') === null) BPMS.iniciaFiltro();

        vgbFiltro.codigo    =   sessionStorage.getItem('filtroCodigo');
        vgbFiltro.titulo    =   sessionStorage.getItem('filtroTitulo');
        vgbFiltro.situacao  =   sessionStorage.getItem('filtroSituacao');
        vgbFiltro.empresa   =   sessionStorage.getItem('filtroEmpresa');
        vgbFiltro.processo  =   sessionStorage.getItem('filtroProcesso');
        vgbFiltro.tipo      =   sessionStorage.getItem('filtroTipo');

        return vgbFiltro;
    }
}
