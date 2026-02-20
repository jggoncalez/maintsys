# Requisitos Funcionais — MaintSys

- O sistema deve permitir o cadastro de técnicos especializados, armazenando informações como nome, especialização e credenciais de acesso, garantindo autenticação segura para uso nos terminais industriais.
    
- O sistema deve permitir o cadastro de máquinas e equipamentos com as seguintes informações: número de série, modelo, localização no galpão e data de instalação.
    
- O sistema deve permitir a criação de Ordens de Serviço (O.S.) do tipo Preventiva ou Corretiva, vinculando obrigatoriamente um técnico responsável a uma máquina específica.
    
- O sistema deve registrar todas as intervenções realizadas em uma máquina, mantendo um histórico completo que permita a análise de reincidência de defeitos.
    
- O sistema deve notificar automaticamente quando uma máquina sofrer mudança de status, como "Máquina 04 em Parada Crítica" ou "Manutenção Concluída".
    
- O sistema deve disponibilizar uma visão geral da planta das oficinas, identificando cada máquina cadastrada e exibindo seu status atual em tempo real.
    
- O sistema deve permitir a atualização do status de uma Ordem de Serviço ao longo do seu ciclo de vida, contemplando estados como "Aberta", "Em Andamento", "Aguardando Peças" e "Concluída".
    
- O sistema deve registrar o tempo de início e término de cada Ordem de Serviço, possibilitando o cálculo do tempo médio de reparo (MTTR) por equipamento.
    
- O sistema deve permitir que técnicos anexem observações e laudos técnicos às Ordens de Serviço, descrevendo o problema identificado e a solução aplicada.
    
- O sistema deve oferecer ao gestor um painel de indicadores com métricas como número de O.S. abertas, máquinas em parada crítica e taxa de manutenções preventivas versus corretivas.
    
- O sistema deve registrar o histórico de alterações de status das máquinas, incluindo data, hora e o técnico ou gestor responsável pela mudança.
    
- O sistema deve controlar os níveis de acesso dos usuários, distinguindo ao menos dois perfis: Técnico, com permissão para registrar e atualizar O.S., e Gestor, com acesso completo aos relatórios e configurações do sistema.
    

# Requisitos Não Funcionais — MaintSys

**Desempenho

- A API deve responder às requisições em no máximo 5 segundos em condições normais de uso.
    
- O sistema deve suportar pelo menos 50 requisições simultâneas sem degradação perceptível de desempenho.
    
- Consultas ao histórico de manutenções devem retornar resultados em no máximo 3 segundos, mesmo com grande volume de registros.
      


**Segurança

- O sistema deve autenticar os usuários via token JWT, garantindo que apenas usuários autorizados acessem os endpoints da API.
    
- As senhas dos técnicos e gestores devem ser armazenadas com hash seguro (bcrypt).
    
- O sistema deve bloquear o acesso a recursos restritos com base no perfil do usuário, retornando HTTP 403 em caso de permissão insuficiente.
    
- Toda comunicação com a API deve ser realizada via HTTPS para garantir a confidencialidade dos dados transmitidos.
    


**Manutenibilidade

- O código deve seguir os padrões PSR-12 e princípios de Clean Code, garantindo legibilidade e facilidade de manutenção.
    
- A aplicação deve ser estruturada seguindo o padrão MVC do Laravel, com separação clara entre regras de negócio, controle e persistência de dados.
    
- O projeto deve ser versionado no GitHub com commits organizados e descrições claras das alterações realizadas.
    


**Portabilidade

- A API deve ser consumível por qualquer cliente HTTP, como tablets industriais, aplicações web e Postman.
    
- O sistema deve ser executável em ambientes Linux e Windows, desde que com PHP e MySQL devidamente configurados.
    


**Entrega

- O repositório no GitHub deve conter um arquivo README com instruções claras de instalação, configuração e execução do projeto.
    
- A API deve ter suas rotas documentadas e testáveis via arquivo de coleção exportado d Postman.
    
- O banco de dados deve ser configurado via Migrations do Laravel, permitindo a recriação do ambiente com um único comando.
    


**Confiabilidade

- O sistema deve garantir a integridade referencial dos dados por meio das constraints do banco de dados MySQL e das relações definidas no Eloquent ORM.
    
- Em caso de falha em uma operação crítica, o sistema deve retornar mensagens de erro padronizadas em JSON, com código HTTP apropriado.
    
- O sistema deve utilizar transações de banco de dados em operações que envolvam múltiplas tabelas, evitando inconsistências em caso de falha parcial.
    

