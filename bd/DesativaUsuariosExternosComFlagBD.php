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

    public function desativarUsuariosExternosComFlag()
    {
        // Especificar a Flag
        $flag = '*';

        try {
            $sql = 'update contato set sin_ativo = "N" where sin_ativo = "S" and id_contato in (select id_contato from contato where nome like "' . $flag . '%");';

            return $this->getObjInfraIBanco()->executarSql($sql);

        } catch (Exception $e) {
            throw new InfraException('Erro desativando usuarios externos com a flag: "' . $flag . '".', $e);
        }
    }
}
