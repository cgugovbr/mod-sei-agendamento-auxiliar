<?
/**
 * CONTROLADORIA GERAL DA UNIAO
 *
 * 27/07/2020 - criado por Evert Ramos evert.ramos@cgu.gov.br
 *
 */

class MdAgendamentoAuxiliarRN extends InfraRN
{

    protected function inicializarObjInfraIBanco()
    {
        return BancoSEI::getInstance();
    }

    protected function removerUsuariosExternosPendentesControlado($parametros = [])
    {
        try {
            InfraDebug::getInstance()->setBolLigado(true);
            InfraDebug::getInstance()->setBolDebugInfra(false);
            InfraDebug::getInstance()->setBolEcho(false);
            InfraDebug::getInstance()->limpar();

            $qtdDias = array_key_exists('qtdDias', $parametros) ? intval($parametros['qtdDias'][0]) : 15;

            $objRemoverUsuariosPendentesBD = new RemoverUsuariosExternosPendentesBD($this->getObjInfraIBanco());

            $numSeg = InfraUtil::verificarTempoProcessamento();
            InfraDebug::getInstance()->gravar('REMOVENDO USUARIOS EXTERNOS PENDENTES - qtdDias: ' . $qtdDias);
            InfraDebug::getInstance()->gravar($objRemoverUsuariosPendentesBD->removerUsuariosExternosPendentes($qtdDias).' REGISTROS');
            $numSeg = InfraUtil::verificarTempoProcessamento($numSeg);
            InfraDebug::getInstance()->gravar('TEMPO TOTAL DE EXECUCAO: '.$numSeg.' s');
            InfraDebug::getInstance()->gravar('FIM');
            
            LogSEI::getInstance()->gravar(InfraDebug::getInstance()->getStrDebug(),InfraLog::$INFORMACAO);
          
        } catch(Exception $e) {
            InfraDebug::getInstance()->setBolLigado(false);
            InfraDebug::getInstance()->setBolDebugInfra(false);
            InfraDebug::getInstance()->setBolEcho(false);
            
            throw new InfraException('Erro removendo usuarios externos pendentes.',$e);
        }
    }

    protected function desativarUsuariosExternosComFlagControlado($parametros = [])
    {
        try {
            InfraDebug::getInstance()->setBolLigado(true);
            InfraDebug::getInstance()->setBolDebugInfra(false);
            InfraDebug::getInstance()->setBolEcho(false);
            InfraDebug::getInstance()->limpar();

            $strFlag = array_key_exists('strFlag', $parametros) ? $parametros['strFlag'][0] : '*';

            $objUsuariosExternosBD = new DesativaUsuariosExternosComFlagBD($this->getObjInfraIBanco());

            $numSeg = InfraUtil::verificarTempoProcessamento();
            InfraDebug::getInstance()->gravar('DESATIVANDO USUARIOS EXTERNOS COM FLAG: ' . $strFlag);
            InfraDebug::getInstance()->gravar($objUsuariosExternosBD->desativarUsuariosExternosComFlag($strFlag) . ' REGISTROS');
            $numSeg = InfraUtil::verificarTempoProcessamento($numSeg);
            InfraDebug::getInstance()->gravar('TEMPO TOTAL DE EXECUCAO: ' . $numSeg . ' s');
            InfraDebug::getInstance()->gravar('FIM');

            LogSEI::getInstance()->gravar(InfraDebug::getInstance()->getStrDebug(), InfraLog::$INFORMACAO);

        } catch(Exception $e) {
            InfraDebug::getInstance()->setBolLigado(false);
            InfraDebug::getInstance()->setBolDebugInfra(false);
            InfraDebug::getInstance()->setBolEcho(false);

            throw new InfraException('Erro desativando usuarios externos com flag.', $e);
        }
    }

    public function desativarUsuariosExternosAntigosControlado($parametros = [])
    {
        try {
            InfraDebug::getInstance()->setBolLigado(true);
            InfraDebug::getInstance()->setBolDebugInfra(false);
            InfraDebug::getInstance()->setBolEcho(false);
            InfraDebug::getInstance()->limpar();

            $numQtdDias = array_key_exists('qtdDias', $parametros) ? intval($parametros['qtdDias'][0]) : 365;

            $objUsuariosExternosBD = new MdAgAuxUsuarioExternoBD($this->getObjInfraIBanco());

            $numSeg = InfraUtil::verificarTempoProcessamento();
            InfraDebug::getInstance()->gravar("DESATIVANDO USUÁRIOS EXTERNOS CADASTRADOS HÁ MAIS DE $numQtdDias DIAS");
            $idsUsuarioDesativados = $objUsuariosExternosBD->desativarUsuariosExternosAntigos($numQtdDias);
            InfraDebug::getInstance()->gravar(count($idsUsuarioDesativados) . ' REGISTROS');

            // Grava data de desativação na tabela auxiliar
            $objMdAgAuxUsuarioExternoRN = new MdAgAuxUsuarioExternoRN();
            foreach ($idsUsuarioDesativados as $idUsuario) {
                $objMdAgAuxUsuarioExternoDTO = new MdAgAuxUsuarioExternoDTO();
                $objMdAgAuxUsuarioExternoDTO->setNumIdUsuario($idUsuario);
                $objMdAgAuxUsuarioExternoDTO->setDthInicioCicloValidade(InfraData::getStrDataHoraAtual());

                $objMdAgAuxUsuarioExternoRN->cadastrarOuAlterar($objMdAgAuxUsuarioExternoDTO);
            }

            $numSeg = InfraUtil::verificarTempoProcessamento($numSeg);
            InfraDebug::getInstance()->gravar('TEMPO TOTAL DE EXECUCAO: ' . $numSeg . ' s');
            InfraDebug::getInstance()->gravar('FIM');

            LogSEI::getInstance()->gravar(InfraDebug::getInstance()->getStrDebug(), InfraLog::$INFORMACAO);

        } catch(Exception $e) {
            InfraDebug::getInstance()->setBolLigado(false);
            InfraDebug::getInstance()->setBolDebugInfra(false);
            InfraDebug::getInstance()->setBolEcho(false);

            throw new InfraException('Erro desativando usuários externos antigos.', $e);
        }
    }
}
?>
