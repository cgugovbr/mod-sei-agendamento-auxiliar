# Módulo de Agendamento Auxiliar para o SUPER/SEI

nome: mod-sei-agendamento-auxiliar

Este módulo foi desenvolvido para que os usuários possam escrever seus próprios agendamentos para o SUPER/SEI independentes das atualizações do sistema. 

Pedimos que todo e qualquer agendamento desenvolvido seja compartilhado para a sociedade e enviado um _pull request_ para este repositório.

## Compatibilidade

SEI 3.0 ou superior
SUPER 4.0 ou superior

## Procedimentos antes da instalação

    Fazer backup completo dos bancos de dados do SEI e do SIP.
    Fazer backup da pasta ./sei/web/modulos/

## Instalação
Faça o download do códiog fonte deste projeto em formato ZIP e faça upload para
o servidor do SEI. Em seguinte extraia o conteúdo na raiz do SEI:
```sh
cd <RAIZ_INSTALACAO_SEI>
unzip mod-sei-agendamento-auxiliar-VERSAO.zip
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

Finalmente execute o script de instalação que fica na pasta sei scripts:
```sh
cd <RAIZ_INSTALACAO_SEI>
php sei/scripts/md_ag_aux_atualizar_modulo.php
```

Ao final da execução do script, caso tudo tenha ocorrido corretamente, deverá
aparecer a palavra **FIM**.

## Funções disponíveis

### 1. Remoção de usuários externos pendentes 

Esta função remove usuários externos que ativos que não possuem nenhuma atividade no sistema, registrados há mais de 15 dias (padrão).

Para executar essa funcionalidade, basta criar um agendamento definindo-se a periodicidade desejada, com a seguinte função:

 ```
MdAgendamentoAuxiliarRN::removerUsuariosExternosPendentes
 ```

> O Sugerimos que a execução desse script seja feita diariamente em horário não comercial. 

> Por padrão verifica-se usuários criados há 15 dias atrás, contudo essa informação pode ser alterado utilizando o parâmetro: **qtdDias** no agendamento, ou seja, se quiser que exclua somente usuários cadastrados há 30 dias insira no campo "parâmetros" no agendamenteo a seguinte informação **"qtdDias=30"**.

### 2. Desativar usuários externos com "flag"

Esta função desativa usuários externos que possuem um determinado caractere na primeira posição, exemplo: 

    Cadastro: JOHN DOE

Alterando o nome dele para:

    Novo nome: *JOHN DOE

Esse usuário será desativado ao chamar essa função.

Para executar essa funcionalidade, basta criar um agendamento definindo-se a periodicidade desejada, com a seguinte função:

 ```
MdAgendamentoAuxiliarRN::desativarUsuariosExternosComFlag
 ```

> O Sugerimos que a execução desse script seja feita diariamente em horário não comercial. 

> Por padrão o caracter utilizado para desativação é o asterico ("*"), pode-se alterar esse caracter utiliznado o parâmetro "strFlag", ou seja se quiser usar o caracter "UUU", insira no campo "parâmetro" no agendamento a seguinte informação **"strFlag=UUU"**

### 3. Desativar usuários externos antigos

Esta função desativa os usuários antigos que foram cadastrados há mais de uma
determinada quantidade de dias (por padrão 365 dias). Dessa forma, o usuário
deverá atualizar sua documentação e o administrador deverá reativá-lo no sistema.

Para utilizar esta funcionalidade basta criar um agendamento com a função:

```
MdAgendamentoAuxiliarRN::desativarUsuariosExternosAntigos
```

Configurar o parâmetro **qtDias** se desejar alterar a quantidade de dias para
cálculo da data de corte de quem será desativado.
