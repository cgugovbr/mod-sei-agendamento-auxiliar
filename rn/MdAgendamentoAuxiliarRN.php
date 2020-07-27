<?
/**
 * CONTROLADORIA GERAL DA UNIAO
 *
 * 27/07/2020 - criado por Evert Ramos evert.ramos@cgu.gov.br
 *
 */

require_once dirname(__FILE__) . '/../../../SEI.php';

class MdAgendamentoAuxiliarRN extends InfraRN
{

    protected function inicializarObjInfraIBanco()
    {
        return BancoSEI::getInstance();
    }

    protected function removerUsuariosExternosPendentesControlado()
    {
        try{            
            InfraDebug::getInstance()->setBolLigado(true);
            InfraDebug::getInstance()->setBolDebugInfra(false);
            InfraDebug::getInstance()->setBolEcho(false);
            InfraDebug::getInstance()->limpar();

            $objRemoverUsuariosPendentesBD = new RemoverUsuariosExternosPendentesBD($this->getObjInfraIBanco());

            $numSeg = InfraUtil::verificarTempoProcessamento();
            InfraDebug::getInstance()->gravar('REMOVENDO USUÁRIOS EXTERNOS PENDENTES');
            InfraDebug::getInstance()->gravar($objRemoverUsuariosPendentesBD->removerUsuariosExternosPendentes().' REGISTROS');
            $numSeg = InfraUtil::verificarTempoProcessamento($numSeg);
            InfraDebug::getInstance()->gravar('TEMPO TOTAL DE EXECUCAO: '.$numSeg.' s');
            InfraDebug::getInstance()->gravar('FIM');
            
            LogSEI::getInstance()->gravar(InfraDebug::getInstance()->getStrDebug(),InfraLog::$INFORMACAO);
          
        }catch(Exception $e){
            InfraDebug::getInstance()->setBolLigado(false);
            InfraDebug::getInstance()->setBolDebugInfra(false);
            InfraDebug::getInstance()->setBolEcho(false);
            
            throw new InfraException('Erro removendo usuários externos pendentes.',$e);
        }
    }
}
?>
