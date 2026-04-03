# 📡 Fluxo de Conexão MQTT (IoT - Futuro)

## 🔌 Arquitetura: ESP-32 → MQTT → Laravel

```mermaid
flowchart TD
    A["🤖 ESP-32 em Máquina 1"] --> B["Sensores: Temp, Vibração, RPM"]
    B --> C["Coleta dados a cada 5s"]
    C --> D["Envia via MQTT"]
    D --> E["MQTT Broker<br/>mosquitto:1883"]
    E --> F["Topic: maintsys/machine/SN-2024-001"]
    F --> G["Laravel MQTT Client"]
    G --> H["MqttListener"]
    H --> I["Processa payload"]
    I --> J["MachineReading::create()"]
    J --> K["BD: temp=42.5°C, vibration=2.3mm/s"]
    K --> L["Updated Machine->last_reading_at"]
    L --> M["✅ Leitura salva"]
    M --> N["Event dispatch"]
    N --> O["SensorReadingReceived"]
    O --> P["Websocket broadcast"]
    P --> Q["Dashboard atualiza em tempo real"]

    style A fill:#2ecc71
    style E fill:#9b59b6
    style Q fill:#3498db
```

---

## 📨 Payload MQTT Esperado

```json
{
  "machine_id": "SN-2024-001",
  "sensors": {
    "temperature": 42.5,
    "vibration": 2.3,
    "rpm": 1500,
    "pressure": 3.2
  },
  "timestamp": "2026-04-03T14:30:45Z"
}
```

**Topic estrutura:**
```
maintsys/machine/{machine_serial}/sensors
maintsys/machine/{machine_serial}/status
maintsys/alert/critical
```

---

## 🖥️ Laravel Side: Listener MQTT

```mermaid
flowchart TD
    A["MqttListener rodando"] --> B["Subscribed a topics"]
    B --> C["Aguarda mensagem"]
    C --> D["MQTT Message recebida"]
    D --> E["Message payload"]
    E --> F["JSON decode"]
    F --> G{machine_serial válida?}
    G -->|Não| H["❌ Log erro"]
    G -->|Sim| I["Find Machine"]
    I --> J{Leitura dentro range?}
    J -->|Fora| K["⚠️ Alerta: valor anômalo"]
    K --> L["dispatch SensorAnomalyDetected"]
    J -->|Dentro| M["MachineReading::create()"]
    M --> N["sensor_key: 'temperature'"]
    N --> O["value: 42.5"]
    O --> P["unit: '°C'"]
    P --> Q["read_at: now()"]
    Q --> R["✅ Salva BD"]
    R --> S["Update M->last_reading_at"]
    S --> T["Event: SensorReadingReceived"]
    T --> U["Broadcast websocket"]

    style R fill:#27ae60
    style K fill:#e74c3c
    style U fill:#3498db
```

---

## 🚨 Fluxo: Detecção Automática de Anomalia

```mermaid
flowchart TD
    A["Leitura arrives via MQTT"] --> B["temperature = 85°C"]
    B --> C{threshold_max?}
    C -->|Sim: > 80°C| D["⚠️ Temperatura crítica"]
    D --> E["EventListener dispara"]
    E --> F["Check Machine status"]
    F --> G{Status já crítico?}
    G -->|Sim| H["Só log"]
    G -->|Não| I["Machine->status = 'critical'"]
    I --> J["Boot hook:]
    J --> K["StatusAlert::create()"]
    K --> L["message = 'Máquina X temperatura 85°C'"]
    L --> M["Notification enviada"]
    M --> N["Gerente vê alerta urgente"]

    style D fill:#e74c3c
    style N fill:#e74c3c
```

---

## 📊 Dashboard Real-Time com Websockets

```mermaid
flowchart TD
    A["MachineReading salva"] --> B["Event SensorReadingReceived"]
    B --> C["Broadcasting ao Laravel Echo"]
    C --> D["Websocket message"]
    D --> E["Cliente Livewire"]
    E --> F["Livewire event listener"]
    F --> G["$wire.dispatch('refresh')"]
    G --> H["Widget re-render"]
    H --> I["Nova temperatura mostra"]
    I --> J["✅ Dashboard atualiza em <1s"]

    style J fill:#27ae60
```

---

## 📈 Exemplo: Widget com Sensor Data

```php
// App/Filament/Widgets/RealtimeSensorWidget.php

class RealtimeSensorWidget extends Widget
{
    public Machine $machine;

    public function getChartData()
    {
        return $this->machine
            ->readings()
            ->where('sensor_key', 'temperature')
            ->where('read_at', '>', now()->subHours(1))
            ->orderBy('read_at')
            ->get();
    }

    // Livewire listener para atualizar em tempo real
    #[On('sensor-reading-received')]
    public function refreshData()
    {
        $this->dispatch('refresh');
    }
}
```

---

## 🔧 Configuração MQTT no .env

```env
MQTT_HOST=mosquitto
MQTT_PORT=1883
MQTT_USERNAME=maintsys_user
MQTT_PASSWORD=secure_password
MQTT_PROTOCOL=tcp

# Topics a subscrever
MQTT_TOPIC=maintsys/machine/+/sensors
MQTT_ALERT_TOPIC=maintsys/alert/critical

# Thresholds de anomalia
SENSOR_TEMP_MAX=80
SENSOR_TEMP_MIN=5
SENSOR_VIBRATION_MAX=5
```

---

## 🎯 Fluxo Completo: ESP-32 → Alert → Dashboard

```mermaid
flowchart LR
    A["🤖 ESP-32"] -->|MQTT| B["Broker"]
    B -->|Subscribe| C["Laravel"]
    C -->|Process| D["MachineReading"]
    D -->|Event| E["Anomaly?"]
    E -->|Sim| F["Machine Status"]
    F -->|Change| G["StatusAlert"]
    G -->|Notify| H["Usuarios"]
    H -->|Broadcast| I["Websocket"]
    I -->|Real-time| J["Dashboard"]
    J -->|Visual| K["✅ Gerente vê"]

    style A fill:#2ecc71
    style K fill:#3498db
```

---

## 📋 Checklist: Implementação MQTT

- [ ] Instalar Mosquitto MQTT Broker (Docker)
- [ ] Instalar `php-mqtt/client` via composer
- [ ] Criar MqttListener command
- [ ] Registrar listener em schedule/kernel
- [ ] Configurar topics no .env
- [ ] Criar MachineReading migration se não existir
- [ ] Implementar SensorReadingReceived event
- [ ] Criar listeners para eventos
- [ ] Broadcast com Laravel Echo + Websockets
- [ ] Criar widget com gráfico real-time
- [ ] Testar com ESP-32 simulado (MQTT.fx)
- [ ] Documentar thresholds de alerta

---

## 🧪 Testing com MQTT.fx

```bash
# 1. Conectar a mosquitto
mosquitto_sub -h localhost -t "maintsys/machine/+/sensors"

# 2. Enviar testamento (em outro terminal)
mosquitto_pub -h localhost \
  -t "maintsys/machine/SN-2024-001/sensors" \
  -m '{"temperature":42.5,"vibration":2.3,"rpm":1500}'

# 3. Verificar BD
select * from machine_readings where machine_id = 1 order by read_at DESC limit 5;
```

---

*[[DIAGRAMAS]] | [[_Fluxogramas/Fluxo-Status-Alert]] | [[Deploy]]*
