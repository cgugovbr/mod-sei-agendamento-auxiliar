# Módulo de Agendamento Auxiliar para o SUPER/SEI

nome: mod-sei-agendamento-auxiliar

Este módulo foi desenvolvido para que os usuários possam escrever seus próprios agendamentos para o SEI independentes das atualizações do sistema. 

Pedimos que todo e qualquer agendamento desenvolvido seja compartilhado para a sociedade e enviado um _pull request_ para este repositório.

## Compatibilidade

SEI 3.0 ou superior
SUPER 4.0 ou superior

## Procedimentos antes da instalação

    Fazer backup completo dos bancos de dados do SEI e do SIP.
    Fazer backup da pasta ./sei/web/modulos/

## Instalação
Faça o download desse projeto no seguinte diretório do SEI
```bash
$ cd sei/web/modulos
$ git clone https://github.com/evertramos/mod-sei-agendamento-auxiliar.git
```

Para que o SEI reconheça esse módulo é necessário editar o arquivo *sei/config/ConfiguracaoSEI.php*.

Atualize a propriedade *Modulos* no objeto *SEI* (caso nao exista crie essa propriedade) com o seguinte valor:

**'MdAgendamentoAuxiliar' => 'mod-sei-agendamento-auxiliar'**

```shell
[...]
  'SEI' => array(
      ...
      'Modulos' => array('MdAgendamentoAuxiliar' => 'mod-sei-agendamento-auxiliar')),
[...]
```

## Funções disponíveis

### 1. Remoção de usuários externos pendentes 

Esta função remove usuários externos que ativos que não possuem nenhuma atividade no sistema, registrados há mais de 15 dias (padrão).

Para executar essa funcionalidade, basta criar um agendamento definindo-se a periodicidade desejada, com a seguinte função:

 ```
MdAgendamentoAuxiliarRN::removerUsuariosExternosPendentes
 ```

> O Sugerimos que a execução desse script seja feita diariamente em horário não comercial. 

> Por padrão verifica-se usuários criados há 15 dias atrás, contudo essa informação pode ser alterado utilizando o parâmetro: **qtdDias** no agendamento, ou seja, se quiser que exclua somente usuários cadastrados há 30 dias insira no campo "parâmetros" no agendamenteo a seguinte informação **"qtdDias=30"**.
