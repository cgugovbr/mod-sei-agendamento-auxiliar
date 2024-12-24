<?php
/**
 * CONTROLADORIA GERAL DA UNIAO
 *
 */

 class MdAgAuxUsuarioExternoRN extends InfraRN
 {
    protected function inicializarObjInfraIBanco()
    {
        return BancoSEI::getInstance();
    }

    public function atualizarDataInicioValidadeConectado($arrObjContatoAPI)
    {
        $idsContato = [];
        foreach ($arrObjContatoAPI as $objContatoAPI) {
            $idsContato[] = $objContatoAPI->getIdContato();
        }

        // Lista usuários externos associados aos contatos
        $objUsuarioDTO = new UsuarioDTO();
        $objUsuarioDTO->setNumIdContato($idsContato, InfraDTO::$OPER_IN);
        $objUsuarioDTO->setStrStaTipo(UsuarioRN::$TU_EXTERNO);
        $objUsuarioDTO->retNumIdUsuario();

        $objUsuarioRN = new UsuarioRN();
        $arrObjUsuarioDTO = $objUsuarioRN->listarRN0490($objUsuarioDTO);

        foreach ($arrObjUsuarioDTO as $objUsuarioDTO) {
            $objMdAgAuxUsuarioExternoDTO = new MdAgAuxUsuarioExternoDTO();
            $objMdAgAuxUsuarioExternoDTO->setNumIdUsuario($objUsuarioDTO->getNumIdUsuario());
            $objMdAgAuxUsuarioExternoDTO->setDthInicioCicloValidade(InfraData::getStrDataHoraAtual());
            $this->cadastrarOuAlterar($objMdAgAuxUsuarioExternoDTO);
        }
    }

    public function cadastrarOuAlterarControlado($objMdAgAuxUsuarioExternoDTO) {
        $objMdAgAuxUsuarioExternoBD = new MdAgAuxUsuarioExternoBD($this->getObjInfraIBanco());
        $objMdAgAuxUsuarioExternoConsultaDTO = new MdAgAuxUsuarioExternoDTO();
        $objMdAgAuxUsuarioExternoConsultaDTO->setNumIdUsuario($objMdAgAuxUsuarioExternoDTO->getNumIdUsuario());
        $objMdAgAuxUsuarioExternoConsultaDTO->retNumIdUsuario();

        if ($objMdAgAuxUsuarioExternoBD->consultar($objMdAgAuxUsuarioExternoConsultaDTO)) {
            $objMdAgAuxUsuarioExternoBD->alterar($objMdAgAuxUsuarioExternoDTO);
        } else {
            $objMdAgAuxUsuarioExternoBD->cadastrar($objMdAgAuxUsuarioExternoDTO);
        }
    }
 }