export const BPMS = {
    iniciaFiltro: function(){
        sessionStorage.setItem('filtroCodigo', null);
        sessionStorage.setItem('filtroTitulo', null);
        sessionStorage.setItem('filtroSituacao', null);
        sessionStorage.setItem('filtroEmpresa', null);
        sessionStorage.setItem('filtroProcesso', null);
        sessionStorage.setItem('filtroTipo', null);
    },
    coletaFiltro: function(){
        var vgbFiltro   =   {
            'codigo'    :   null,
            'titulo'    :   null,
            'situacao'  :   null,
            'empresa'   :   null,
            'processo'  :   null,
            'tipo'      :   null,
        };

        if(vgbFiltro === null) this.iniciaFiltro();

        vgbFiltro.codigo    =   sessionStorage.getItem('filtroCodigo');
        vgbFiltro.titulo    =   sessionStorage.getItem('filtroTitulo');
        vgbFiltro.situacao  =   sessionStorage.getItem('filtroSituacao');
        vgbFiltro.empresa   =   sessionStorage.getItem('filtroEmpresa');
        vgbFiltro.processo  =   sessionStorage.getItem('filtroProcesso');
        vgbFiltro.tipo      =   sessionStorage.getItem('filtroTipo');

        return vgbFiltro;
    }
}
