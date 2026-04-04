# 📄 Páginas Filament v5 - MaintSys

Este documento descreve as páginas CRUD e read-only criadas para cada Resource do MaintSys com Filament v5.

## 📁 Estrutura de Páginas

Cada Resource possui 4 páginas padrão:

### 1. **ListPage** - Listagem de Registros
- Exibe todos os registros em uma tabela
- Possui ações para cada linha:
  - 👁️ **Visualizar** - Abre a página de visualização (read-only)
  - ✏️ **Editar** - Abre o formulário de edição
  - Actions customizadas por Resource
- Filtros e buscas
- Botão "Criar" no topo

### 2. **ViewPage** - Visualização (Read-Only)
- Mostra todos os dados do registro em modo visualização
- **Sem campos editáveis** na apresentação
- Actions header com:
  - **Editar** - Navega para a página de edição
  - **Deletar** - Com confirmação (apenas admin em alguns cases)
  - Actions customizadas por Resource

### 3. **CreatePage** - Criação de Novo Registro
- Formulário vazio para criar novo registro
- Todos os campos são editáveis
- Validações aplicadas
- Redireciona para lista após criar

### 4. **EditPage** - Edição de Registro
- Formulário preenchido com dados do registro
- Campos editáveis
- Botão de delete no header
- Redireciona para lista após salvar

## 🎯 Resources e Suas Pages

### **MachineResource** - Máquinas
```
Routes:
  /admin/machines                    → ListMachines
  /admin/machines/create            → CreateMachine
  /admin/machines/{id}              → ViewMachine (read-only)
  /admin/machines/{id}/edit         → EditMachine
```

**Actions Especiais:**
- ✏️ Visualizar - Abre View page
- 🎯 Marcar Crítica - Atualiza status instantaneamente
- ✅ Marcar Operacional - Atualiza status instantaneamente

### **ServiceOrderResource** - Ordens de Serviço
```
Routes:
  /admin/service-orders             → ListServiceOrders
  /admin/service-orders/create      → CreateServiceOrder
  /admin/service-orders/{id}        → ViewServiceOrder (read-only)
  /admin/service-orders/{id}/edit   → EditServiceOrder
```

**Actions Especiais (na View page):**
- ▶️ Iniciar - Muda status para in_progress (se status=open)
- ✅ Concluir - Abre modal para adicionar notas (se status=in_progress)

### **MaintenanceLogResource** - Logs de Manutenção
```
Routes:
  /admin/maintenance-logs           → ListMaintenanceLogs
  /admin/maintenance-logs/create    → CreateMaintenanceLog
  /admin/maintenance-logs/{id}      → ViewMaintenanceLog (read-only)
  /admin/maintenance-logs/{id}/edit → EditMaintenanceLog
```

### **StatusAlertResource** - Alertas de Status
```
Routes:
  /admin/status-alerts              → ListStatusAlerts
  /admin/status-alerts/{id}         → ViewStatusAlert (read-only)
```

**Actions Especiais (na View page):**
- ✅ Marcar como Lido / ❌ Marcar como Não Lido - Toggle is_read

### **UserResource** - Usuários (Admin Only)
```
Routes:
  /admin/users                      → ListUsers
  /admin/users/create               → CreateUser
  /admin/users/{id}                 → ViewUser (read-only)
  /admin/users/{id}/edit            → EditUser
```

## 🔐 Controle de Acesso

Cada página respeita as permissões baseadas em roles:

| Ação | Admin | Gerente | Tecnico |
|------|-------|---------|---------|
| Visualizar Máquina | ✅ | ✅ | ❌ |
| Criar Máquina | ✅ | ✅ | ❌ |
| Editar Máquina | ✅ | ✅ | ❌ |
| Deletar Máquina | ✅ | ❌ | ❌ |
| Visualizar O.S. | ✅ | ✅ | ✅ |
| Criar O.S. | ✅ | ✅ | ✅ |
| Editar O.S. | ✅ | ✅ | ❌ |
| Deletar O.S. | ✅ | ❌ | ❌ |
| Gerenciar Usuários | ✅ | ❌ | ❌ |

## 💡 Fluxo de Navegação

```
ListPage
  ↓ (clica no registro ou ícone Visualizar)
ViewPage (read-only)
  ↓ (clica em Editar)
EditPage
  ↓ (salva)
ListPage

ListPage
  ↓ (clica em Criar)
CreatePage
  ↓ (preencheu e criou)
ListPage
```

## 🎨 Componentes Visuais

### Tabelas
- Todas as tabelas Filament v5 têm:
  - Colunas com dados formatados
  - Buscas por coluna
  - Filtros avançados
  - Seleção múltipla
  - Ações por linha
  - Ações em massa (Bulk Actions)

### Formulários
- Todos os formulários têm:
  - Validação em tempo real
  - Campos customizados por tipo
  - Sections organizacionais
  - Help text explicativo
  - Estados de carregamento

### Actions Header
- Todos os headers têm ações contextuais:
  - Editar
  - Deletar
  - Actions customizadas
  - Baseadas em permissões

## 📝 Exemplo de Uso - Machine Resource

### 1️⃣ Acesso à Listagem
```
GET /admin/machines
```
- Lista todas as máquinas
- Mostra serial_number, name, model, location, status, etc.
- Actions: Visualizar, Editar, Marcar Crítica, Marcar Operacional

### 2️⃣ Visualizar Máquina (Read-Only)
```
GET /admin/machines/{machine_id}
```
- Mostra dados da máquina em modo visualização
- Sem campos editáveis
- Actions: Editar, Deletar (apenas admin)

### 3️⃣ Editar Máquina
```
GET/POST /admin/machines/{machine_id}/edit
```
- Formulário preenchido com dados
- Todos os campos editáveis
- Validações Laravel
- Action: Deletar

### 4️⃣ Criar Máquina
```
GET/POST /admin/machines/create
```
- Formulário vazio
- Todos os campos para preencher
- Validações obrigatórias

## 🧪 Testes

Todas as páginas são testadas com 65 testes Pest:
- ✅ Acesso por role
- ✅ Criação de registros
- ✅ Edição de registros
- ✅ Ações customizadas
- ✅ Permissões aplicadas

Execute: `php artisan test`

## 📱 Responsividade

Todas as páginas são totalmente responsivas:
- Desktop (full width)
- Tablet (adaptado)
- Mobile (stack layout)

## 🚀 Melhorias Futuras

Possíveis upgrades:
- [ ] Exportar para PDF/Excel
- [ ] Pesquisa avançada multi-campo
- [ ] Dashboard com gráficos
- [ ] Relatórios customizados
- [ ] Webhooks para eventos
- [ ] Activity log
