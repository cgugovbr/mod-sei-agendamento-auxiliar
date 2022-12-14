<?php
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

    /**
     * Remove usu�rios externos criados h� mais de 'qtdDias' de dias, ativos, que n�o possuem nenhuma
     * assinatura no sistema.
     *
     * @param $qtdDias
     * @return mixed
     */
    public function removerUsuariosExternosPendentes($qtdDias)
    {
        try {
            $sql = 'delete from usuario where id_usuario in (SELECT usuario.id_usuario FROM usuario LEFT JOIN contato ON contato.id_contato = usuario.id_contato WHERE usuario.sta_tipo = 2 and usuario.sin_ativo=\'S\' and usuario.id_usuario not in (select id_usuario from assinatura) and contato.dth_cadastro < DATEADD(day, -' . $qtdDias . ', GETDATE()))';

            return $this->getObjInfraIBanco()->executarSql($sql);
        } catch (Exception $e) {
            throw new InfraException('Erro removendo usu�rios externos pendentes.', $e);
        }
    }
}
