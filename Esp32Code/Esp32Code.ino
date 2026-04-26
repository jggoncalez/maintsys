#include <WiFi.h>
#include <PubSubClient.h>

const bool LevelSensorPin = 2;
const int TemperaturePin = 4;
// Esp config wifi & mqtt
const char* ssid = "REDE";
const char* password = "SENHA";
const char* mqtt_server = "192.168.1.100"; // IP da máquina

WiFiClient espClient;
PubSubClient client(espClient);

void reconnect() {
  while (!client.connected()) {
    Serial.print("Conectando ao MQTT...");
    if (client.connect("ESP32Client")) {
      Serial.println("MQTT Conectado!");
    } else {
      Serial.print("Falhou rc=");
      Serial.println(client.state());
      delay(5000);
    }
  }
}

void setup() {
  Serial.begin(115200);
  delay(1000); // aguarda Serial estabilizar
  
  Serial.println("=== Iniciando ===");
  Serial.print("Conectando ao WiFi: ");
  Serial.println(ssid);

  WiFi.begin(ssid, password);

  int tentativas = 0;
  while (WiFi.status() != WL_CONNECTED) {
    delay(500);
    Serial.print(".");
    tentativas++;
    if (tentativas > 20) { // 10 segundos
      Serial.println("\nFalhou! Verifique ssid e senha.");
      break;
    }
  }

  if (WiFi.status() == WL_CONNECTED) {
    Serial.println("\nWiFi conectado!");
    Serial.print("IP: ");
    Serial.println(WiFi.localIP());
    client.setServer(mqtt_server, 1883);
  }
}

void loop() {
  bool LevelSensorValue = digitalRead(LevelSensorPin);
  int TemperatureValue = 20; 
  if (WiFi.status() != WL_CONNECTED) {
    Serial.println("WiFi desconectado!");
    delay(5000);
    return;
  }

  if (!client.connected()) reconnect();
  client.loop();

  // Publica sensor a cada 5s
  String payload = "{\"temperatura\": " + String(TemperatureValue) + ", \"Sensor_de_Nível\": " + String(LevelSensorValue) + "}";
  client.publish("sensors/esp32/data", payload.c_str());
  Serial.print("Publicado: ");
  Serial.println(payload);

  delay(5000);
}