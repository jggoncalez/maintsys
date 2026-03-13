# MaintSys — Sistema de Gerenciamento de Manutenção Industrial

> Um sistema integrado para controle, registro e análise de manutenções preventivas e corretivas em ambientes industriais, com visão em tempo real da planta e indicadores de performance.

---

## 📋 Sobre o Projeto

**MaintSys** é uma solução desenvolvida para otimizar o gerenciamento de manutenção em ambientes industriais, permitindo que técnicos e gestores tenham visibilidade completa sobre o status das máquinas, histórico de intervenções e métricas críticas de desempenho.

### Objetivos Principais

- ✅ Centralizar o registro de todas as intervenções em máquinas e equipamentos
- ✅ Reduzir o tempo de resposta em paradas críticas (MTTR)
- ✅ Diferenciar entre manutenções preventivas e corretivas
- ✅ Fornecer indicadores de performance para gestores
- ✅ Garantir rastreabilidade completa de alterações

---

## 🎯 Funcionalidades Principais

### Para Técnicos
- 📝 **Registrar Ordens de Serviço** (O.S. Preventiva ou Corretiva)
- 📊 **Atualizar Status** ao longo do ciclo de vida (Aberta → Em Andamento → Aguardando Peças → Concluída)
- 📎 **Anexar Observações e Laudos** técnicos detalhando problemas e soluções
- ⏱️ **Registrar Tempo** de início e término de intervenções
- 🔍 **Consultar Histórico** completo de cada máquina

### Para Gestores
- 📈 **Dashboard Executivo** com indicadores em tempo real
- 🏭 **Visão Geral da Planta** com identificação de máquinas e status
- 🔔 **Notificações Automáticas** de mudanças críticas de status
- 📊 **Relatórios e Métricas** de MTTR, taxa de manutenções preventivas vs corretivas
- 👥 **Controle de Acesso** baseado em perfis (Técnico/Gestor)

### Gerais
- 📦 **Cadastro de Equipamentos** com número de série, modelo, localização e data de instalação
- 👨‍🔧 **Gestão de Técnicos** com especialização e credenciais
- 📅 **Histórico Auditável** com rastreamento de data, hora e responsável
- 🔐 **Autenticação Segura** com JWT e hash bcrypt

---

## 🏗️ Arquitetura Técnica com Automação IoT

```
┌──────────────────────────────────────┐
│     MÁQUINAS INDUSTRIAIS             │
│  ├─ Temperatura, Vibração            │
│  ├─ Pressão, Corrente/Voltagem       │
│  └─ Contatos Secos (alarmes)         │
└────────────┬─────────────────────────┘
             │ GPIO, I2C, SPI
┌────────────▼─────────────────────────┐
│     ESP32 (Microcontrolador)         │
│  ├─ Leitura de Sensores              │
│  ├─ Processamento Local              │
│  └─ MQTT/HTTP para API               │
└────────────┬─────────────────────────┘
             │ WiFi/Ethernet
      ┌──────┴──────────────┐
      │                     │
 ┌────▼────────────┐   ┌────▼──────────────────┐
 │MQTT Broker      │   │ API REST (Laravel)    │
 │(Mosquitto)      │   │ ├─ Validação          │
 │Port: 1883       │   │ ├─ Automação IoT      │
 └────┬────────────┘   │ ├─ Criação automática │
      │                │ │   de O.S.           │
      │                │ ├─ Notificações       │
      │                └─ Alertas em tempo real│
      │                   │
      └────────┬──────────┘
               │
         ┌─────▼──────────────────┐
         │ MySQL Database         │
         │ ├─ Máquinas            │
         │ ├─ Sensores            │
         │ ├─ Leituras (TimeSeries│
         │ ├─ Ordens de Serviço   │
         │ └─ Eventos Automáticos │
         └─────┬──────────────────┘
               │
         ┌─────▼──────────────────┐
         │Dashboard em Tempo Real │
         │(Web & Mobile)          │
         └────────────────────────┘
```

### Stack Tecnológico

| Camada | Tecnologia |
|--------|-----------|
| **Backend** | Laravel 10+ (PHP) |
| **Banco de Dados** | MySQL 5.7+ |
| **Autenticação** | JWT (JSON Web Tokens) |
| **Criptografia** | bcrypt (senhas) |
| **ORM** | Eloquent |
| **Padrão Arquitetural** | MVC |
| **Versionamento** | Git/GitHub |
| **IoT Gateway** | ESP32 (Microcontrolador) |
| **Protocolo IoT** | MQTT + HTTP |
| **Broker MQTT** | Mosquitto |

---

## ⚡ Automação com ESP32

### Como Funciona

1. **ESP32 conectado à máquina** coleta dados de sensores (temperatura, vibração, pressão, corrente)
2. **MQTT envia eventos** para o servidor (máquina ligada, parada, temperatura anormal)
3. **API Laravel processa** automaticamente (cria O.S., envia alertas)
4. **Dashboard atualiza** em tempo real com status das máquinas
5. **Zero intervenção manual** — tudo é automático

### Fluxo de Automação

```
Sensor → ESP32 → MQTT Broker → API Laravel → BD MySQL → Dashboard
          (Lê)    (Publica)      (Processa)    (Armazena) (Exibe)
```

### Tipos de Sensores Suportados

| Sensor | Tipo | Função |
|--------|------|--------|
| **DS18B20** | Temperatura | Monitorar superaquecimento |
| **ADXL345** | Vibração | Detectar anomalias mecânicas |
| **MPX5050** | Pressão | Monitorar pressão hidráulica |
| **ACS712** | Corrente | Detectar sobrecargas |
| **SW-420** | Impacto/Vibração | Alarme de impacto |
| **Fim de Curso** | Digital | Detectar posição/estado |
| **Relé Inteligente** | Controle | Ligar/desligar máquina |

### Eventos Automáticos Disparados

| Evento | Ação |
|--------|------|
| **Máquina ligada** | Registra timestamp de início |
| **Temperatura > 80°C** | Cria O.S. Preventiva automática |
| **Vibração anormal** | Alerta para possível desalinhamento |
| **Parada inesperada** | Cria O.S. Corretiva automática |
| **Corrente acima de limite** | Alerta de possível travamento |
| **Máquina desligada** | Registra timestamp e MTTR |

### Exemplo: Automação Prática

**Cenário:** Máquina começa a vibrar anormalmente
```
1. Sensor ADXL345 detecta vibração > 5 m/s²
2. ESP32 publica: maintsys/machine/04/events -> {tipo: "VIBRATION_ALERT"}
3. API Laravel recebe e valida
4. Sistema cria automaticamente:
   - Ordem de Serviço Preventiva
   - Assigina técnico disponível
   - Envia notificação em tempo real
   - Registra no histórico
5. Dashboard mostra máquina com status "ALERT"
6. Técnico é notificado no celular/tablet
7. Máquina aguarda O.S. ser fechada
```

### Configuração de Thresholds (Limites)

Cada máquina pode ter limites personalizados:

```json
{
  "machine_id": 4,
  "thresholds": {
    "temperatura_max": 80,
    "temperatura_min": 5,
    "vibracao_max": 5.0,
    "corrente_max": 25.0,
    "pressao_max": 2.5,
    "uptime_max_horas": 12
  }
}
```

Quando um limite é excedido → **Automação é disparada**

---

## 📋 Requisitos Funcionais

### Gerenciamento de Usuários
- **RF-01**: Cadastro de técnicos com especialização e credenciais seguras

### Gerenciamento de Equipamentos
- **RF-02**: Cadastro de máquinas com número de série, modelo, localização e data de instalação

### Ordens de Serviço
- **RF-03**: Criação de O.S. (Preventiva/Corretiva) vinculando técnico e máquina
- **RF-07**: Atualização de status durante ciclo de vida
- **RF-08**: Registro de tempo (MTTR)
- **RF-09**: Anexação de observações e laudos

### Histórico e Auditoria
- **RF-04**: Registro completo de intervenções e análise de reincidência
- **RF-11**: Histórico de alterações com data, hora e responsável

### Visibilidade e Indicadores
- **RF-05**: Notificações automáticas de mudança de status
- **RF-06**: Dashboard em tempo real com status de máquinas
- **RF-10**: Painel de indicadores para gestor (O.S., paradas críticas, taxa preventiva/corretiva)

### Segurança
- **RF-12**: Controle de acesso com perfis Técnico e Gestor

---

## 📊 Requisitos Não Funcionais

### Desempenho
| Requisito | Limite |
|-----------|--------|
| Tempo de resposta da API | ≤ 5 segundos |
| Requisições simultâneas | ≥ 50 |
| Consultas ao histórico | ≤ 3 segundos |

### Segurança
- 🔐 Autenticação via **JWT**
- 🔒 Senhas com hash **bcrypt**
- 🚫 Controle de acesso baseado em perfil (HTTP 403)
- 🔗 Comunicação via **HTTPS**

### Manutenibilidade
- 📐 Padrão de código **PSR-12** & **Clean Code**
- 🎯 Arquitetura **MVC (Laravel)**
- 📚 Versionamento no GitHub com commits organizados

### Confiabilidade
- 🔗 Integridade referencial (Constraints MySQL + Eloquent)
- ⚠️ Tratamento de erros padronizado em JSON
- 🔄 Transações de banco de dados para operações críticas

### Portabilidade
- 💻 Executável em **Linux** e **Windows**
- 📱 Consumível por qualquer cliente HTTP (tablets, web, Postman)
- ✅ Requer PHP 8.0+ e MySQL 5.7+

---

## 🚀 Como Começar

### Pré-requisitos

- PHP 8.0 ou superior
- MySQL 5.7 ou superior
- Composer 2.0+
- Git

### Instalação

1. **Clone o repositório**
   ```bash
   git clone <URL_DO_REPOSITORIO>
   cd MaintSys
   ```

2. **Instale as dependências**
   ```bash
   composer install
   ```

3. **Configure o ambiente**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Configure o banco de dados** no arquivo `.env`
   ```
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=maintsys
   DB_USERNAME=root
   DB_PASSWORD=sua_senha
   ```

5. **Execute as migrations**
   ```bash
   php artisan migrate
   ```

6. **(Opcional) Popule dados de exemplo**
   ```bash
   php artisan db:seed
   ```

7. **Inicie o servidor**
   ```bash
   php artisan serve
   ```

   A API estará disponível em: `http://localhost:8000`

---

## 🔌 Configuração ESP32 (IoT Gateway)

### Pré-requisitos

- Arduino IDE ou PlatformIO
- Biblioteca: PubSubClient (MQTT)
- Biblioteca: ArduinoJson (JSON)
- Conhecimento básico de eletrônica

### Instalação das Bibliotecas

No Arduino IDE, vá em **Sketch → Include Library → Manage Libraries** e instale:

```
- PubSubClient (Nick O'Leary)
- ArduinoJson (Benoit Blanchon)
- DHT (Adafruit) — se usar sensor DHT22
- Adafruit_ADXL345 — se usar sensor de vibração
```

### Configuração do Código ESP32

1. **Clone o repositório do código ESP32**
   ```bash
   git clone <URL_REPO_ESP32> ESP32_MaintSys
   cd ESP32_MaintSys
   ```

2. **Configure as credenciais** em `config.h`:
   ```cpp
   // WiFi
   const char* SSID = "sua_rede_wifi";
   const char* PASSWORD = "sua_senha";
   
   // MQTT Broker
   const char* MQTT_SERVER = "192.168.1.100"; // IP do servidor
   const char* MQTT_USER = "usuario";
   const char* MQTT_PASS = "senha";
   const int MQTT_PORT = 1883;
   
   // Identificação da Máquina
   const int MACHINE_ID = 4;
   const char* MACHINE_NAME = "Torno_CNC_01";
   
   // Pinos dos Sensores
   const int TEMP_PIN = 33;      // DS18B20
   const int VIBRATION_PIN = 35; // ADXL345 (I2C)
   const int PRESSURE_PIN = 32;  // MPX5050 (Analógico)
   const int CURRENT_PIN = 34;   // ACS712 (Analógico)
   ```

3. **Upload do código para o ESP32**
   ```bash
   Arduino IDE:
   - Selecione placa: ESP32 Dev Module
   - Selecione porta COM correta
   - Pressione Upload
   ```

### Monitorar Comunicação ESP32

Use o **Serial Monitor** para verificar a conexão:

```bash
# Arduino IDE (Ctrl+Shift+M) ou
# PlatformIO Monitor
pio device monitor --baud 115200
```

Você verá:
```
[WiFi] Conectando a: MeuWiFi...
[WiFi] IP: 192.168.1.50
[MQTT] Conectando a broker: 192.168.1.100:1883
[MQTT] Conectado!
[Sensor] Temperatura: 45.2°C
[MQTT] Publicando: maintsys/machine/4/temperature → 45.2
```

### Testando Publicação MQTT

Use o **MQTT Explorer** ou linha de comando:

```bash
# Instalar MQTT Client
sudo apt-get install mosquitto-clients

# Subscribe para ver mensagens
mosquitto_sub -h 192.168.1.100 -t "maintsys/machine/+/+"

# Publicar teste
mosquitto_pub -h 192.168.1.100 -t "maintsys/machine/4/status" -m "RUNNING"
```

---

## 📊 Integração MQTT → API Laravel

### Listener MQTT na API

A API Laravel possui um listener que processa eventos MQTT automaticamente:

```bash
# Terminal 1: Inicie o servidor Laravel
php artisan serve

# Terminal 2: Inicie o listener MQTT
php artisan mqtt:listen

# Agora eventos do ESP32 são processados automaticamente
```

### Fluxo de Dados

```
ESP32 publica:
  maintsys/machine/4/temperature → 85.5
    ↓
MQTT Broker recebe
    ↓
Laravel Listener processa
    ↓
MachineEventListener::handle()
    ↓
Verifica threshold (80°C)
    ↓
EXCEDEU LIMITE → Cria O.S. automática
    ↓
WorkOrderService::createAutomatic()
    ↓
Salva no BD + Notifica técnico
    ↓
Dashboard atualiza em tempo real
```

### Exemplo de Automação no Laravel

Arquivo: `app/Listeners/MachineEventListener.php`

```php
public function handle(MqttEventReceived $event)
{
    $machine = Machine::find($event->machineId);
    $value = $event->value;
    
    // Verificar temperatura
    if ($event->sensorType === 'temperature' && $value > $machine->threshold_temp) {
        // Criar O.S. automática
        WorkOrder::create([
            'machine_id' => $machine->id,
            'type' => 'Preventiva',
            'status' => 'Aberta',
            'description' => "Temperatura crítica: {$value}°C",
            'technician_id' => $this->assignTechnician($machine),
            'created_by' => 'SYSTEM_AUTO'
        ]);
        
        // Notificar
        Notification::dispatch("Máquina {$machine->name} em ALERTA");
    }
}
```

---

## 📚 Documentação

### Estrutura do Projeto

```
MaintSys/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── TechnicianController.php
│   │   │   ├── MachineController.php
│   │   │   ├── WorkOrderController.php
│   │   │   └── ...
│   │   └── Requests/     (Validação de inputs)
│   ├── Models/
│   │   ├── User.php
│   │   ├── Machine.php
│   │   ├── WorkOrder.php
│   │   └── ...
│   └── Services/         (Lógica de negócio)
├── database/
│   ├── migrations/       (Criação de tabelas)
│   └── seeders/          (Dados iniciais)
├── routes/
│   └── api.php           (Endpoints da API)
├── tests/                (Testes unitários e de integração)
├── .env                  (Configuração do ambiente)
└── README.md
```

### Rotas Principais

Veja a **Coleção Postman** (`MaintSys.postman_collection.json`) para testar todos os endpoints:

#### Autenticação
- `POST /api/auth/login` — Login de usuário
- `POST /api/auth/logout` — Logout
- `POST /api/auth/refresh` — Renovar token

#### Técnicos
- `GET /api/technicians` — Listar técnicos
- `POST /api/technicians` — Criar técnico
- `GET /api/technicians/{id}` — Detalhe do técnico
- `PUT /api/technicians/{id}` — Atualizar técnico

#### Máquinas
- `GET /api/machines` — Listar máquinas
- `POST /api/machines` — Criar máquina
- `GET /api/machines/{id}` — Detalhe da máquina
- `PUT /api/machines/{id}` — Atualizar máquina

#### Ordens de Serviço
- `GET /api/work-orders` — Listar O.S.
- `POST /api/work-orders` — Criar O.S.
- `PUT /api/work-orders/{id}/status` — Atualizar status
- `POST /api/work-orders/{id}/notes` — Adicionar observações

#### Dashboard
- `GET /api/dashboard/overview` — Visão geral da planta
- `GET /api/dashboard/metrics` — Indicadores e métricas

---

## 🧪 Testes

Execute os testes automatizados:

```bash
# Testes unitários
php artisan test --testsuite=Unit

# Testes de integração
php artisan test --testsuite=Feature

# Cobertura de código
php artisan test --coverage
```

---

## 📋 Metodologia de Desenvolvimento

O projeto segue a metodologia **Scrum** com Sprints de 2 semanas.

### Sprint 1 (20/02/2026) ✅ CONCLUÍDA
- ✅ Levantamento de requisitos funcionais e não funcionais
- ✅ Prototipagem de interface
- ✅ Definição de arquitetura

### Próximas Sprints
- Sprint 2: Desenvolvimento do backend (Autenticação, CRUD base)
- Sprint 3: Desenvolvimento do backend (Lógica de O.S. e Histórico)
- Sprint 4: Desenvolvimento do frontend
- Sprint 5: Integração, testes e deploy

---

## 🔒 Segurança

### Práticas Implementadas
- ✅ Autenticação JWT com refresh tokens
- ✅ Hash bcrypt para senhas (min. 10 rounds)
- ✅ CORS configurado para domínios autorizados
- ✅ Rate limiting em endpoints de autenticação
- ✅ Validação rigorosa de inputs com FormRequest
- ✅ SQL injection prevention (Eloquent ORM)
- ✅ HTTPS obrigatório em produção
- ✅ Logs auditáveis de todas as operações críticas

### Roles & Permissions
| Perfil | Permissões |
|--------|-----------|
| **Técnico** | Criar/Atualizar O.S., Anexar observações, Consultar máquinas |
| **Gestor** | Acesso total, Relatórios, Configurações, Gerenciamento de usuários |
| **Admin** | Todas as permissões (não exposto via API) |

---

## 📱 Integrações Planejadas

### IoT & Sensores
- Integração com dispositivos Arduino/ESP32 via MQTT
- Recebimento de status em tempo real de máquinas
- Alertas automáticos de anomalias

### Notificações
- Email para paradas críticas
- Push notifications para aplicativo móvel
- Integração com Teams/Slack (opcional)

---

## 🐛 Troubleshooting IoT

### ESP32 não conecta ao WiFi
```
Solução 1: Verifique credenciais em config.h
Solução 2: Reinicie o ESP32 (pressione RESET)
Solução 3: Verifique se o roteador está ligado
Solução 4: Veja os logs no Serial Monitor
```

### MQTT não publica
```
Solução 1: Verifique se Mosquitto está rodando
sudo systemctl status mosquitto

Solução 2: Verifique firewall (porta 1883 aberta)
sudo ufw allow 1883

Solução 3: Teste com MQTT Explorer
```

### Laravel não recebe eventos MQTT
```
Solução 1: Certifique-se de que o listener está rodando
php artisan mqtt:listen

Solução 2: Verifique os logs
tail -f storage/logs/laravel.log

Solução 3: Teste manualmente com Postman
POST /api/events/process
```

### Sensor não lê valor
```
Solução 1: Verifique as conexões (GND, VCC, DATA)
Solução 2: Verifique o pino configurado em config.h
Solução 3: Teste com código de exemplo Arduino
```

---

## 📚 Documentação Técnica Complementar

### Estrutura de Pastas do Código ESP32

```
ESP32_MaintSys/
├── config.h              (Credenciais e pinos)
├── sensors.cpp           (Leitura de sensores)
├── mqtt.cpp              (Comunicação MQTT)
├── main.ino              (Loop principal)
└── README_ESP32.md       (Docs específicas)
```

### Recursos Úteis

- 📖 [Documentação ESP32](https://docs.espressif.com/projects/esp-idf/en/latest/)
- 📖 [PubSubClient MQTT](https://github.com/knolleary/pubsubclient)
- 📖 [Arduino JSON](https://arduinojson.org/)
- 🛠️ [MQTT Explorer](http://mqtt-explorer.com/)
- 💻 [Arduino IDE](https://www.arduino.cc/en/software)

---

## 🔄 Pipeline de CI/CD

O projeto utiliza **GitHub Actions** para validação automática:

```yaml
# .github/workflows/test.yml
- Lint de código (PSR-12)
- Testes unitários
- Testes de integração
- Build de Docker (opcional)
```

Veja o status: [![Tests](https://github.com/seu-usuario/MaintSys/actions/workflows/test.yml/badge.svg)](https://github.com/seu-usuario/MaintSys/actions)

---



1. Crie uma branch para sua feature (`git checkout -b feature/AmazingFeature`)
2. Commit suas mudanças (`git commit -m 'Add some AmazingFeature'`)
3. Push para a branch (`git push origin feature/AmazingFeature`)
4. Abra um Pull Request com descrição clara

### Padrões de Código
- Seguir **PSR-12** para estilo
- Usar **Clean Code** principles
- Escrever testes para novas funcionalidades
- Documentar APIs com docblocks

---

## 📄 Licença

Este projeto está sob licença [MIT](LICENSE) — veja o arquivo LICENSE para detalhes.

---

## 👥 Equipe

- **Desenvolvimento**: SENAI Limeira — Curso Desenvolvimento de Sistemas
- **Orientação**: Professores do programa

---

## 📞 Suporte

Para dúvidas ou reportar bugs, abra uma **Issue** no repositório:
- [Abrir Issue](https://github.com/seu-usuario/MaintSys/issues)

---

## 🗂️ Estrutura de Documentação Complementar

Consulte os arquivos de documentação adicionais:

- **Requisitos.md** — Levantamento completo de requisitos funcionais e não funcionais
- **Arquitetura.md** — Diagrama de arquitetura em detalhes
- **API.md** — Documentação detalhada de endpoints
- **INSTALACAO.md** — Guia passo a passo de instalação para diferentes ambientes

---

**Versão**: 1.0.0  
**Última atualização**: Março de 2026  
**Status**: Em Desenvolvimento (Sprint 2)

---

*Desenvolvido como projeto acadêmico em SENAI Limeira*
