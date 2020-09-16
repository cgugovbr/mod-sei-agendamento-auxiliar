# Módulo de Agendamento Auxiliar para o SEI

nome: mod-sei-agendamento-auxiliar

Este módulo foi desenvolvido para que os usuários possam escrever seus próprios agendamentos para o SEI independentes das atualizações do sistema. 

Pedimos que todo e qualquer agendamento desenvolvido seja compartilhado para a sociedade e enviado um _pull request_ para este repositório.

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

Em seguida basta criar um agendamento definindo-se a periodicidade desejada, com a seguinte função:

 ```
MdAgendamentoAuxiliarRN::removerUsuariosExternosPendentes
 ```
