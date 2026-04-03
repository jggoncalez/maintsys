# 🏗️ Arquitetura Técnica Geral

## 🔧 Stack em Camadas

```mermaid
flowchart TD
    subgraph presentation["🎨 PRESENTATION LAYER"]
        UI["Filament Dashboard (UI)"]
        Resources["Resources + Forms"]
        Widgets["Widgets + Charts"]
    end

    subgraph application["⚙️ APPLICATION LAYER"]
        Controllers["Controllers"]
        Actions["Filament Actions"]
        Listeners["Event Listeners"]
        Commands["Artisan Commands"]
    end

    subgraph domain["📦 DOMAIN LAYER"]
        Models["Eloquent Models"]
        Traits["Traits + Scopes"]
        Events["Domain Events"]
        Rules["Validation Rules"]
    end

    subgraph infrastructure["🗄️ INFRASTRUCTURE LAYER"]
        DB["MySQL Database"]
        MQTT["MQTT Broker"]
        Storage["File Storage"]
    end

    presentation --> application
    application --> domain
    domain --> infrastructure

    style presentation fill:#3498db
    style application fill:#9b59b6
    style domain fill:#e74c3c
    style infrastructure fill:#34495e
```

---

## 📡 Componentes do Sistema

```mermaid
graph TB
    subgraph frontend["🖥️ Frontend"]
        F1["Filament Admin<br/>Laravel Packages"]
        F2["Livewire<br/>Real-time components"]
        F3["Alpine.js<br/>Lightweight interactions"]
    end

    subgraph backend["🔧 Backend"]
        B1["Laravel 11 Router"]
        B2["Filament Resources<br/>CRUD wrapper"]
        B3["Eloquent Models<br/>ORM"]
        B4["Spatie Permission<br/>RBAC"]
    end

    subgraph services["🌐 Services"]
        S1["MQTT Client<br/>ESP-32 connection"]
        S2["Event System<br/>Listeners"]
        S3["Queue Jobs<br/>Background tasks"]
        S4["Email Service<br/>Notifications"]
    end

    subgraph data["💾 Data"]
        D1["MySQL<br/>Relational DB"]
        D2["Redis<br/>Cache"]
        D3["File Storage<br/>Images, docs"]
    end

    subgraph external["🤖 External"]
        E1["ESP-32 Sensors<br/>IoT Devices"]
        E2["MQTT Broker<br/>Message Queue"]
    end

    F1 --> B1
    B1 --> B2
    B2 --> B3
    B3 --> D1
    B4 --> D1
    B2 --> S1
    S1 --> E2
    E2 --> E1
    S2 --> S4
    S3 --> D1
    B3 --> D2

    style frontend fill:#3498db
    style backend fill:#9b59b6
    style services fill:#e74c3c
    style data fill:#34495e
    style external fill:#2ecc71
```

---

## 🔄 Data Flow: Request Completo

```mermaid
sequenceDiagram
    actor User
    participant Filament
    participant Controller
    participant Model
    participant DB
    participant Event
    participant Websocket

    User->>Filament: Clica ação
    Filament->>Filament: canCreate/Edit/Delete?
    Filament->>Controller: Envia formulário
    Controller->>Controller: Valida dados
    Controller->>Model: Model::create()
    Model->>DB: INSERT query
    DB-->>Model: ID retornado
    Model->>Event: Event dispatched
    Event->>Websocket: Broadcast
    Websocket-->>User: Real-time update
    Model-->>Controller: Return model
    Controller-->>Filament: Redirect
    Filament-->>User: Success message
```

---

## 🚀 Startup: Application Bootstrap

```mermaid
flowchart TD
    A["composer install"] --> B["Laravel bootstrapping"]
    B --> C["Load service providers"]
    C --> D["Register facades"]
    D --> E["Register middleware"]
    E --> F["Register routes"]
    F --> G["Filament boot"]
    G --> H["Load resources"]
    H --> I["Load widgets"]
    I --> J["✅ App ready"]
    J --> K["Listen for requests"]

    style J fill:#27ae60
    style K fill:#3498db
```

---

## 📊 Database Architecture

```mermaid
graph TB
    subgraph core["Core Tables"]
        U["users"]
        M["machines"]
    end

    subgraph operations["Operations"]
        SO["service_orders"]
        ML["maintenance_logs"]
    end

    subgraph monitoring["Monitoring"]
        MR["machine_readings"]
        SA["status_alerts"]
    end

    subgraph auth["Authentication"]
        R["roles"]
        P["permissions"]
        RP["role_has_permissions"]
        UR["model_has_roles"]
    end

    U -.->|many| SO
    U -.->|many| ML
    U -.->|many| SA
    M -.->|many| SO
    M -.->|many| ML
    M -.->|many| MR
    M -.->|many| SA
    SO -.->|many| ML
    U -.->|has| R
    R -.->|many| P

    style core fill:#e74c3c
    style operations fill:#f39c12
    style monitoring fill:#3498db
    style auth fill:#9b59b6
```

---

## 🔌 Integration Points

```mermaid
graph LR
    A["Laravel APP"] -.->|MQTT Publish| B["Mosquitto Broker"]
    E["ESP-32 Sensors"] -->|MQTT Publish| B
    B -->|MQTT Subscribe| A
    A -->|Events| C["Laravel Echo"]
    C -->|Broadcast| D["Filament Dashboard"]
    A -->|Mail Service| F["SMTP Server"]
    A -->|Cache| G["Redis"]

    style A fill:#9b59b6
    style B fill:#34495e
    style D fill:#3498db
    style E fill:#2ecc71
```

---

## 🎯 Deployment Architecture

```mermaid
graph TB
    subgraph local["💻 Local Development"]
        L1["Docker Compose"]
        L2["Laravel 11 App"]
        L3["MySQL 8.0"]
        L4["Mosquitto MQTT"]
        L5["Redis"]
    end

    subgraph production["🚀 Production"]
        P1["Kubernetes"]
        P2["Laravel App Pods"]
        P3["RDS MySQL"]
        P4["MQTT Broker Managed"]
        P5["Elasticache Redis"]
    end

    subgraph monitoring["📊 Monitoring"]
        M1["Prometheus"]
        M2["Grafana"]
        M3["Error Tracking"]
    end

    local -.->|CI/CD| production
    production --> monitoring

    style local fill:#3498db
    style production fill:#27ae60
    style monitoring fill:#f39c12
```

---

## 🔐 Security Layers

```mermaid
graph TD
    A["HTTPS/TLS<br/>Encrypted transport"] --> B["Laravel Auth<br/>Session management"]
    B --> C["Spatie Permission<br/>Authorization"]
    C --> D["Policy checks<br/>Resource-level"]
    D --> E["Validation Rules<br/>Input sanitization"]
    E --> F["SQL Parameterization<br/>Prepared statements"]
    F --> G["CSRF Protection<br/>Token middleware"]
    G --> H["Rate Limiting<br/>DDoS protection"]

    style A fill:#27ae60
    style G fill:#27ae60
    style H fill:#27ae60
```

---

## 📈 Performance Optimization

```mermaid
graph TD
    A["Query Performance"] --> B["Eager Loading"]
    A --> C["Database Indexes"]
    A --> D["Query Caching"]

    E["Frontend Performance"] --> F["Livewire Lazy Loading"]
    E --> G["Asset Compression"]
    E --> H["CDN for static"]

    I["Backend Performance"] --> J["Redis Caching"]
    I --> K["Queue Jobs"]
    I --> L["Pagination"]

    style B fill:#27ae60
    style C fill:#27ae60
    style F fill:#27ae60
    style J fill:#27ae60
```

---

## 🧪 Testing Strategy

```mermaid
graph LR
    A["Unit Tests"] -.->|Model logic| B["App Logic"]
    C["Feature Tests"] -.->|Routes/Auth| B
    D["Browser Tests"] -.->|UI/Interactions| E["Filament UI"]
    F["Integration Tests"] -.->|MQTT/Events| B

    style A fill:#3498db
    style C fill:#3498db
    style D fill:#3498db
    style F fill:#3498db
```

---

*[[DIAGRAMAS]] | [[Fluxo-Permissoes]]*
