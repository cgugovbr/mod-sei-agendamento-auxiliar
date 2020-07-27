# M�dulo de Agendamento Auxiliar para o SEI

nome: mod-sei-agendamento-auxiliar

Este m�dulo foi desenvolvido para que os usu�rios possam escrever seus pr�prios agendamentos para o SEI independentes das atualiza��es do sistema. 

Pedimos que todo e qualquer agendamento desenvolvido seja compartilhado para a sociedade enviado um _merge request_ para este reposit�rio.

## Instala��o
Fa�a o download desse projeto no seguinte diret�rio do SEI
```bash
$ cd sei/web/modulos
$ git clone https://github.com/evertramos/mod-sei-agendamento-auxiliar.git
```

Para que o SEI reconhe�a esse m�dulo � necess�rio editar o arquivo *sei/config/ConfiguracaoSEI.php*.

Atualize a propriedade *Modulos* no objeto *SEI* (caso nao exista crie essa propriedade) com o seguinte valor:

**'MdAgendamentoAuxiliar' => 'mod-sei-agendamento-auxiliar'**

```shell
[...]
  'SEI' => array(
      ...
      'Modulos' => array('MdAgendamentoAuxiliar' => 'mod-sei-agendamento-auxiliar')),
[...]
```

Em seguida basta criar um agendamento definindo-se a periodicidade desejada, com a seguinte fun��o:

 ```
MdAgendamentoAuxiliarRN::removerUsuariosExternosPendentes
 ```