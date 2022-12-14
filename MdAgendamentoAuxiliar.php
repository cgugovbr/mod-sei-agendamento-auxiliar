<?
/**
 * CONTROLADORIA GERAL DA UNIAO
 *
 * 22/07/2020 - criado por Evert Ramos evert.ramos@cgu.gov.br
 *
 */

class MdAgendamentoAuxiliar extends SeiIntegracao
{

  public function __construct()
  {
  }

  public function getNome()
  {
    return 'Módulo de Agendamento Auxiliar';
  }

  public function getVersao()
  {
    return '1.4.0';
  }

  public function getInstituicao()
  {
    return 'CGU - Controladoria Geral da União';
  }

  public function inicializar($strVersaoSEI)
  {
   // 
  }
}

?>
