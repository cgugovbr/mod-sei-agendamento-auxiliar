<?php
/**
 * CONTROLADORIA GERAL DA UNIAO
 *
 * 27/07/2020 - criado por Evert Ramos <evert.ramos@cgu.gov.br>
 *
 */

class MdAgAuxUsuariosExternosBD extends InfraBD
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

    /**
     * Desativa usuários externos que foram cadastrados há mais de uma certa
     * quantidade de dias.
     * @param int $qtdDias Quantidade de dias desde o cadastro do usuário para
     * ser apto a ser desativado.
     * @return int Quantidade de usuários afetados.
     */
    public function desativarUsuariosExternosAntigos($qtdDias)
    {
        // Os gestores pediram que a rotina só funcionasse daqui a um ano
        if (new DateTime() < new DateTime("2025-08-08")) {
            return 0;
        }

        // Data limite
        $dataLimite = new DateTime();
        $dataLimite->sub(new DateInterval('P'.$qtdDias.'D'));
        $dataLimiteFormatada = $dataLimite->format('Y-m-d H:i:s');

        $objInfraIBanco = $this->getObjInfraIBanco();

        // Consulta usuários elegíveis
        $sql = "SELECT u.id_usuario, u.id_contato, mu.dth_ultima_desativacao ".
                "FROM usuario u ".
                    "LEFT JOIN contato c ON u.id_contato = c.id_contato ".
                    "LEFT JOIN md_ag_aux_usuario_externo mu ON u.id_usuario = mu.id_usuario ".
                "WHERE u.sin_ativo = 'S' ".
                    "AND u.sta_tipo = 3 ".
                    "AND (".
                        "(mu.dth_ultima_desativacao IS NULL AND c.dth_cadastro < '$dataLimiteFormatada') ".
                        "OR mu.dth_ultima_desativacao < '$dataLimiteFormatada'".
                    ")";
        $resultados = $objInfraIBanco->consultarSql($sql);

        $idsUsuario = [];
        $idsContato = [];
        $idsUsuarioInsertData = [];
        $idsUsuarioUpdateData = [];
        foreach ($resultados as $linha) {
            $idsUsuario[] = $linha['id_usuario'];
            $idsContato[] = $linha['id_contato'];
            if ($linha['dth_ultima_desativacao']) {
                $idsUsuarioUpdateData[] = $linha['id_usuario'];
            } else {
                $idsUsuarioInsertData[] = $linha['id_usuario'];
            }
        }

        if (count($idsUsuario) == 0) {
            return 0;
        }

        // Atualizar tabela usuario
        $sql = "UPDATE usuario SET sin_ativo = 'N' WHERE id_usuario IN (".
            implode(',', $idsUsuario) . ")";
        $objInfraIBanco->executarSql($sql);

        // Atualizar tabela contato
        $sql = "UPDATE contato SET sin_ativo = 'N' WHERE id_contato IN (".
            implode(',', $idsContato) . ")";
        $objInfraIBanco->executarSql($sql);

        // Atualiza tabela md_ag_aux_usuario_externo com a data corrente
        // para cada usuário desativado
        $dataCorrenteFormatada = (new DateTime())->format('Y-m-d H:i:s');
        if (count($idsUsuarioInsertData) > 0) {
            foreach ($idsUsuarioInsertData as $idUsuario) {
                $sql = "INSERT INTO md_ag_aux_usuario_externo (id_usuario, dth_ultima_desativacao) ".
                    "VALUES ($idUsuario, '$dataCorrenteFormatada') ";
                $objInfraIBanco->executarSql($sql);
            }
        }
        if (count($idsUsuarioUpdateData) > 0) {
            $sql = "UPDATE md_ag_aux_usuario_externo ".
                "SET dth_ultima_desativacao = '$dataCorrenteFormatada' ".
                "WHERE id_usuario IN (".implode(',', $idsUsuarioUpdateData).")";
            $objInfraIBanco->executarSql($sql);
        }

        return count($idsUsuario);
    }
}
