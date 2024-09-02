<?php
/**
 *
 * CONTROLADORIA GERAL DA UNIÃO
 *
 */

class MdAgAuxUsuarioExternoDTO extends InfraDTO
{
    public function getStrNomeTabela()
    {
        return 'md_ag_aux_usuario_externo';
    }

    public function montar()
    {
        $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
            'IdUsuario',
            'id_usuario');

        $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_DTH,
            'InicioCicloValidade',
            'dth_inicio_ciclo_validade');

        $this->configurarPK('IdUsuario', InfraDTO::$TIPO_PK_INFORMADO);

        $this->configurarFK('IdUsuario', 'usuario', 'id_usuario');
    }
}
