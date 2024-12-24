<?php
/**
 * CONTROLADORIA GERAL DA UNIAO
 *
 */

class MdAgAuxUsuarioExternoBD extends InfraBD
{
    public function __construct(InfraIBanco $objInfraIBanco)
    {
        parent::__construct($objInfraIBanco);
    }

    /**
     * Desativa usuários externos que foram cadastrados ou reativadoshá mais de
     * uma certa quantidade de dias.
     * @param int $qtdDias Quantidade de dias desde o cadastro/reativação do
     * usuário para ser apto a ser desativado.
     * @return array Array com os IDs de usuário desativados
     */
    public function desativarUsuariosExternosAntigos($qtdDias)
    {
        // Os gestores pediram que a rotina só funcionasse a partir da data indicada
        if (new DateTime() < new DateTime("2026-01-02")) {
            return [];
        }

        // Data limite
        $dataLimite = new DateTime();
        $dataLimite->sub(new DateInterval('P'.$qtdDias.'D'));
        $dataLimiteFormatada = $dataLimite->format('Y-m-d H:i:s');

        $objInfraIBanco = $this->getObjInfraIBanco();

        // Consulta usuários elegíveis
        $sql = "SELECT u.id_usuario, u.id_contato, mu.dth_inicio_ciclo_validade ".
                "FROM usuario u ".
                    "LEFT JOIN contato c ON u.id_contato = c.id_contato ".
                    "LEFT JOIN md_ag_aux_usuario_externo mu ON u.id_usuario = mu.id_usuario ".
                "WHERE u.sin_ativo = 'S' ".
                    "AND u.sta_tipo = 3 ".
                    "AND (".
                        "(mu.dth_inicio_ciclo_validade IS NULL AND c.dth_cadastro < '$dataLimiteFormatada') OR ".
                        "(mu.dth_inicio_ciclo_validade IS NOT NULL AND mu.dth_inicio_ciclo_validade < '$dataLimiteFormatada')".
                    ")";
        $resultados = $objInfraIBanco->consultarSql($sql);

        $idsUsuario = [];
        $idsContato = [];
        foreach ($resultados as $linha) {
            $idsUsuario[] = $linha['id_usuario'];
            $idsContato[] = $linha['id_contato'];
        }

        if (count($idsUsuario) == 0) {
            return [];
        }

        // Atualizar tabela usuario
        $sql = "UPDATE usuario SET sin_ativo = 'N' WHERE id_usuario IN (".
            implode(',', $idsUsuario) . ")";
        $objInfraIBanco->executarSql($sql);

        // Atualizar tabela contato
        $sql = "UPDATE contato SET sin_ativo = 'N' WHERE id_contato IN (".
            implode(',', $idsContato) . ")";
        $objInfraIBanco->executarSql($sql);

        return $idsUsuario;
    }
}
