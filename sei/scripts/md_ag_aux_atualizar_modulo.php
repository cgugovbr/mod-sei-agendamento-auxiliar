<?php

require_once dirname(__FILE__) . '/../web/SEI.php';

class MdAgAuxAtualizador extends InfraScriptVersao
{
    private $nomeModulo = 'Módulo de Agendamento Auxiliar';
    private $versaoAtual = '1.5.0';
    private $parametroVersao = 'VERSAO_MODULO_AGENDAMENTO_AUXILIAR';
    private $arrayVersoes = array(
      '1.5.0' => 'instalarv150',
    );

    public function __construct()
    {
        parent::__construct();

        $this->setStrNome($this->nomeModulo);
        $this->setStrVersaoAtual($this->versaoAtual);
        $this->setStrParametroVersao($this->parametroVersao);
        $this->setArrVersoes($this->arrayVersoes);

        $this->setStrVersaoInfra('2.0.18');
        $this->setBolMySql(true);
        $this->setBolOracle(true);
        $this->setBolSqlServer(true);
        $this->setBolPostgreSql(true);
        $this->setBolErroVersaoInexistente(false);
    }

    protected function inicializarObjInfraIBanco()
    {
        return BancoSEI::getInstance();
    }

    protected function instalarv150()
    {
        $objInfraIBanco = $this->inicializarObjInfraIBanco();
        $objInfraMetaBD = new InfraMetaBD($objInfraIBanco);

        // Cria tabela para salvar data de início do ciclo de validade do
        // cadastro de usuários externos
        $this->logar('Criando tabela md_ag_aux_usuario_externo');

        $objInfraIBanco->executarSql(
            'CREATE TABLE md_ag_aux_usuario_externo ('.
                'id_usuario ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL,'.
                'dth_inicio_ciclo_validade ' . $objInfraMetaBD->tipoDataHora() . ' NOT NULL'.
            ')');

        $objInfraMetaBD->adicionarChavePrimaria(
            'md_ag_aux_usuario_externo',
            'pk_md_ag_aux_usuario_externo',
            ['id_usuario']
        );

        $objInfraMetaBD->adicionarChaveEstrangeira(
            'fk1_md_ag_aux_usuario_externo_id_usuario',
            'md_ag_aux_usuario_externo',
            ['id_usuario'],
            'usuario',
            ['id_usuario']
        );
    }
}

try {
    SessaoSEI::getInstance(false);

     $objVersaoRN = new MdAgAuxAtualizador();
     $objVersaoRN->atualizarVersao();
} catch (Exception $e) {
    echo(InfraException::inspecionar($e));
    try {
        LogSEI::getInstance()->gravar(InfraException::inspecionar($e));
    } catch (Exception $e) {
    }
    exit(1);
}
?>