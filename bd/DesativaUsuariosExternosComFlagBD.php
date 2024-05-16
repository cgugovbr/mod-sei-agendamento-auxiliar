<?php
/**
 * CONTROLADORIA GERAL DA UNIAO
 *
 * 27/07/2020 - criado por Evert Ramos <evert.ramos@cgu.gov.br>
 *
 */

require_once dirname(__FILE__) . '/../../../SEI.php';

class DesativaUsuariosExternosComFlagBD extends InfraBD
{
    public function __construct(InfraIBanco $objInfraIBanco)
    {
        parent::__construct($objInfraIBanco);
    }

    /**
     * Desativa usuários externo com um caractere específico "strFlag" no início do nome
     * @param $strFlag
     * @return mixed
     */
    public function desativarUsuariosExternosComFlag($strFlag)
    {
        try {
            $sql = 'update contato set sin_ativo = \'N\' where sin_ativo = \'S\' and nome like \'' . $strFlag . '%\'';

            return $this->getObjInfraIBanco()->executarSql($sql);
        } catch (Exception $e) {
            throw new InfraException('Erro desativando usuarios externos com a flag: "' . $strFlag . '".', $e);
        }
    }
}
