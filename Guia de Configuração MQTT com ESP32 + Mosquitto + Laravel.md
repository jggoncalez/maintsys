## Visão Geral da Arquitetura

```
ESP32 → publica mensagem MQTT → Broker Mosquitto → Laravel assina e processa
```

---

## 1. Instalação do Mosquitto (Broker)

1. Baixe o instalador em [mosquitto.org/download](https://mosquitto.org/download/)
2. Instale normalmente no Windows
3. Abra o **Notepad como Administrador** e edite o arquivo:

```
   C:\Program Files\Mosquitto\mosquitto.conf
```

4. Adicione as seguintes linhas:

conf

```conf
   listener 1883 0.0.0.0
   allow_anonymous true
```

5. Salve o arquivo

### Iniciar o serviço (CMD como Administrador)

cmd

```cmd
mosquitto install
net start mosquitto
```

### Verificar se está rodando

cmd

```cmd
netstat -an | findstr 1883
```

✅ Deve aparecer: `TCP 0.0.0.0:1883 ... LISTENING`

### Liberar no Firewall

cmd

```cmd
netsh advfirewall firewall add rule name="Mosquitto" dir=in action=allow protocol=TCP localport=1883
```

---

## 2. Descobrir o IP do seu PC

No CMD, rode:

cmd

```cmd
ipconfig
```

Procure pelo adaptador ativo (Ethernet ou Wi-Fi):

```
Ethernet adapter Ethernet:
   IPv4 Address. . . : 192.168.X.X  ← use este IP no ESP32
```

> ⚠️ O ESP32 e o PC precisam estar na **mesma rede** para se comunicar.

---

## 3. Configuração do ESP32 (Arduino IDE)

### Bibliotecas necessárias

- **PubSubClient** — instale pelo Library Manager
- **WiFi** — já inclusa no pacote ESP32

### Código base

cpp

```cpp
#include <WiFi.h>
#include <PubSubClient.h>

const char* ssid       = "SUA_REDE";
const char* password   = "SUA_SENHA";
const char* mqtt_server = "192.168.X.X"; // IP do seu PC

WiFiClient espClient;
PubSubClient client(espClient);

void reconnect() {
  while (!client.connected()) {
    Serial.print("Conectando ao MQTT...");
    if (client.connect("ESP32Client")) {
      Serial.println("Conectado!");
    } else {
      Serial.print("Falhou rc=");
      Serial.println(client.state());
      delay(5000);
    }
  }
}

void setup() {
  Serial.begin(115200);
  delay(1000);

  Serial.print("Conectando ao WiFi...");
  WiFi.begin(ssid, password);
  while (WiFi.status() != WL_CONNECTED) {
    delay(500);
    Serial.print(".");
  }
  Serial.println("\nWiFi conectado! IP: " + WiFi.localIP().toString());

  client.setServer(mqtt_server, 1883);
}

void loop() {
  if (!client.connected()) reconnect();
  client.loop();

  // Monta o payload com variáveis
  float temperatura = 25.4;
  float umidade     = 60.0;

  char payload[100];
  sprintf(payload, "{\"temperatura\": %.2f, \"umidade\": %.2f}", temperatura, umidade);

  client.publish("sensors/esp32/data", payload);
  Serial.println("Publicado: " + String(payload));

  delay(5000);
}
```

---

## 4. Configuração do Laravel

### Instalar o pacote MQTT

bash

```bash
composer require php-mqtt/laravel-client
php artisan vendor:publish --provider="PhpMqtt\Client\MqttClientServiceProvider"
```

### Configurar o `.env`

env

```env
MQTT_HOST=127.0.0.1
MQTT_PORT=1883
MQTT_CLIENT_ID=laravel-client
```

### Criar o Command subscriber

bash

```bash
php artisan make:command MqttSubscribe
```

php

```php
// app/Console/Commands/MqttSubscribe.php

use PhpMqtt\Client\Facades\MQTT;

class MqttSubscribe extends Command
{
    protected $signature = 'mqtt:subscribe';

    public function handle()
    {
        $mqtt = MQTT::connection();

        $mqtt->subscribe('sensors/esp32/data', function (string $topic, string $message) {
            $data = json_decode($message, true);

            // Salva no banco de dados
            SensorReading::create([
                'temperatura' => $data['temperatura'],
                'umidade'     => $data['umidade'],
            ]);

            $this->info("Recebido: $message");
        });

        $mqtt->loop(true); // fica escutando indefinidamente
    }
}
```

### Iniciar o listener

bash

```bash
php artisan mqtt:subscribe
```

---

## 5. Fluxo Completo

```
ESP32
 └── conecta no WiFi
 └── conecta no Mosquitto (porta 1883)
 └── publica JSON a cada 5s no tópico "sensors/esp32/data"

Mosquitto (Broker)
 └── recebe a mensagem
 └── repassa para todos os subscribers

Laravel (php artisan mqtt:subscribe)
 └── recebe a mensagem
 └── salva no banco de dados
 └── pode disparar eventos, WebSockets, notificações...
```

---

## Troubleshooting

|Problema|Solução|
|---|---|
|`rc=-2` no Serial Monitor|IP do `mqtt_server` errado ou Mosquitto não está rodando|
|`rc=-4`|Timeout — verifique firewall e se estão na mesma rede|
|Serial Monitor vazio|Aperte o botão **EN** no ESP32 para reiniciar|
|`Access Denied` no `net start`|Abra o CMD como Administrador|
|`0.0.0.0:1883` não aparece no netstat|Falta o `listener 1883 0.0.0.0` no `mosquitto.conf`|