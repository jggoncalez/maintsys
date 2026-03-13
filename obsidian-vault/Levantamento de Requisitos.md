# Requisitos — MaintSys

## Requisitos Funcionais

| ID    | Descrição                                                                                                        | Módulo            | Priorização(MoSCoW) |
| ----- | ---------------------------------------------------------------------------------------------------------------- | ----------------- | ------------------- |
| RF-01 | Cadastro de técnicos especializados com nome, especialização, credenciais de acesso e autenticação segura        | Usuários          | M                   |
| RF-02 | Cadastro de máquinas e equipamentos com número de série, modelo, localização e data de instalação                | Equipamentos      | M                   |
| RF-03 | Criação de Ordens de Serviço (O.S.) do tipo Preventiva ou Corretiva, vinculando técnico e máquina                | Ordens de Serviço | M                   |
| RF-04 | Registro completo do histórico de intervenções em máquinas para análise de reincidência de defeitos              | Histórico         | M                   |
| RF-05 | Notificações automáticas de mudança de status de máquinas (ex: "Máquina 04 em Parada Crítica")                   | Notificações      | S                   |
| RF-06 | Visão geral da planta com identificação de máquinas e exibição de status em tempo real                           | Dashboard         | M                   |
| RF-07 | Atualização de status de O.S. durante ciclo de vida (Aberta, Em Andamento, Aguardando Peças, Concluída)          | Ordens de Serviço | S                   |
| RF-08 | Registro de tempo de início e término de O.S. para cálculo do MTTR por equipamento                               | Ordens de Serviço | S                   |
| RF-09 | Anexação de observações e laudos técnicos às Ordens de Serviço                                                   | Ordens de Serviço | M                   |
| RF-10 | Painel de indicadores para gestor com métricas de O.S. abertas, máquinas em parada crítica e taxa de manutenções | Dashboard         | S                   |
| RF-11 | Histórico de alterações de status de máquinas com data, hora e responsável                                       | Histórico         | S                   |
| RF-12 | Controle de acesso com dois perfis mínimos: Técnico e Gestor                                                     | Segurança         | S                   |

---

## Requisitos Não Funcionais

### Desempenho

| ID      | Descrição                                                     | Limite         | Priorização (MoSCoW) |
| ------- | ------------------------------------------------------------- | -------------- | -------------------- |
| RNF-D01 | Tempo máximo de resposta da API em condições normais          | 5 segundos     | C                    |
| RNF-D02 | Requisições simultâneas suportadas sem degradação perceptível | 50 requisições | S                    |
| RNF-D03 | Tempo máximo para consultas ao histórico de manutenções       | 3 segundos     | C                    |

### Segurança

| ID      | Descrição                               | Método                               | Priorização (MoSCoW) |
| ------- | --------------------------------------- | ------------------------------------ | -------------------- |
| RNF-S01 | Autenticação de usuários                | Token JWT                            | M                    |
| RNF-S02 | Armazenamento de senhas                 | Hash bcrypt                          | M                    |
| RNF-S03 | Controle de acesso a recursos restritos | HTTP 403 para permissão insuficiente | S                    |
| RNF-S04 | Comunicação com API                     | HTTPS                                | M                    |

### Manutenibilidade

| ID      | Descrição                | Padrão                         | Priorização (MoSCoW) |
| ------- | ------------------------ | ------------------------------ | -------------------- |
| RNF-M01 | Padrão de código         | PSR-12 e Clean Code            | C                    |
| RNF-M02 | Arquitetura da aplicação | MVC                            | S                    |
| RNF-M03 | Versionamento do projeto | GitHub com commits organizados | M                    |

### Portabilidade

| ID      | Descrição              | Escopo                                        | Priorização (MoSCoW) |
| ------- | ---------------------- | --------------------------------------------- | -------------------- |
| RNF-P01 | Consumibilidade da API | Qualquer cliente HTTP (tablets, web, Postman) | S                    |
| RNF-P02 | Ambientes de execução  | Linux e Windows com PHP e MySQL               | M                    |

### Entrega

| ID      | Descrição                              | Artefato                  | Priorização (MoSCoW) |
| ------- | -------------------------------------- | ------------------------- | -------------------- |
| RNF-E01 | Documentação de instalação e execução  | README no GitHub          | C                    |
| RNF-E02 | Documentação e testabilidade das rotas | Coleção Postman exportada | S                    |
| RNF-E03 | Configuração do banco de dados         | Migrations                | M                    |

### Confiabilidade

| ID      | Descrição                                 | Implementação                                             | Priorização (MoSCoW) |
| ------- | ----------------------------------------- | --------------------------------------------------------- | -------------------- |
| RNF-C01 | Integridade referencial dos dados         | Constraints MySQL e relações Eloquent ORM                 | S                    |
| RNF-C02 | Tratamento de erros em operações críticas | Mensagens padronizadas em JSON com código HTTP apropriado | S                    |
| RNF-C03 | Consistência em operações múltiplas       | Transações de banco de dados                              | M                    |


### Legenda de Priorização (MoSCoW)

- **Must-have (M):** Requisitos vitais para o sucesso do projeto e segurança operacional.
- **Should-have (S):** Requisitos importantes, mas não vitais para a fase inicial.
- **Could-have (C):** Requisitos desejáveis que melhoram a experiência, mas podem ser deixados para depois.
- **Won't-have (W):** Requisitos que não serão incluídos nesta entrega (ou ciclo de desenvolvimento).