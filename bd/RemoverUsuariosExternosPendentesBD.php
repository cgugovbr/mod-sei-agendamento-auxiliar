<?
/**
 * CONTROLADORIA GERAL DA UNIAO
 *
 * 27/07/2020 - criado por Evert Ramos <evert.ramos@cgu.gov.br>
 *
 */

require_once dirname(__FILE__) . '/../../../SEI.php';

class RemoverUsuariosExternosPendentesBD extends InfraBD
{

  public function __construct(InfraIBanco $objInfraIBanco)
  {
  	 parent::__construct($objInfraIBanco);
  }

  public function removerUsuariosExternosPendentes()
  {
    try{
      // Dias para exclusão de usuários externos pendentes (ex. 15 - quinze dias antes)
      $dias = 15;
      $sql = 'delete from usuario where id_usuario in (SELECT usuario.id_usuario FROM usuario LEFT JOIN contato ON contato.id_contato = usuario.id_contato WHERE usuario.sta_tipo = 2 and contato.dth_cadastro < DATEADD(day, -' . $dias . ', GETDATE()))';
      // $sql = 'delete from usuario where id_usuario in (dth_snapshot <= '.$this->getObjInfraIBanco()->formatarGravacaoDth(InfraData::calcularData(1, InfraData::$UNIDADE_DIAS, InfraData::$SENTIDO_ATRAS, InfraData::getStrDataHoraAtual()));

      return $this->getObjInfraIBanco()->executarSql($sql);
    }catch(Exception $e){
      throw new InfraException('Erro removendo usuários externos pendentes.',$e);
    }
  }
}
?>