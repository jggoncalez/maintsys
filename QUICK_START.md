# 🚀 Guia Rápido - Filament v5 Admin Panel

## ⚙️ Como Rodar o Admin Panel

### 1. Instalar Dependências
```bash
composer install
npm install
```

### 2. Configurar Banco de Dados
```bash
# Copiar .env.example para .env
cp .env.example .env

# Gerar APP_KEY
php artisan key:generate

# Rodar migrations
php artisan migrate
```

### 3. Criar Usuário Admin (Opcional)
```bash
php artisan tinker
>>> $user = User::factory()->create(['name' => 'Admin', 'email' => 'admin@test.com']);
>>> $user->assignRole('admin');
>>> exit
```

### 4. Rodar o Servidor
```bash
php artisan serve
```

### 5. Acessar Admin Panel
```
http://localhost:8000/admin
```

---

## 📊 URL Structure

```
http://localhost:8000/admin/
  ├── machines/                    # Gerenciar Máquinas
  ├── service-orders/             # Gerenciar Ordens de Serviço
  ├── maintenance-logs/           # Visualizar Logs de Manutenção
  ├── status-alerts/              # Visualizar Alertas
  └── users/                      # Gerenciar Usuários (Admin Only)
```

---

## 🎯 Fluxo de Uso Típico

### Para Admin:
1. ✅ Login no `/admin`
2. ✅ Acesso total a todos os recursos
3. ✅ Criar, editar, deletar tudo
4. ✅ Gerenciar usuários e roles

### Para Gerente:
1. ✅ Login no `/admin`
2. ✅ Criar e editar máquinas/ordens
3. ✅ Editar logs de manutenção
4. ✅ ❌ Não pode deletar nem gerenciar usuários

### Para Técnico:
1. ✅ Login no `/admin`
2. ✅ Ver máquinas e ordens de serviço
3. ✅ Criar ordens e logs de manutenção
4. ✅ ❌ Não pode editar nem deletar

---

## 🧪 Como Testar

### Rodar Todos os Testes
```bash
php artisan test
```

### Rodar Testes Específicos
```bash
# Apenas testes unitários
php artisan test tests/Unit

# Apenas testes de recursos
php artisan test tests/Feature

# Um arquivo específico
php artisan test tests/Unit/Models/MachineTest.php
```

### Com Coverage
```bash
php artisan test --coverage
```

### Em Paralelo (mais rápido)
```bash
php artisan test --parallel
```

---

## 📁 Estrutura de Arquivos Criados

```
app/Filament/Resources/
├── MachineResource.php
│   └── Pages/
│       ├── ListMachines.php
│       ├── CreateMachine.php
│       ├── EditMachine.php
│       └── ViewMachine.php          ← Read-Only
├── ServiceOrderResource.php
│   └── Pages/
│       ├── ListServiceOrders.php
│       ├── CreateServiceOrder.php
│       ├── EditServiceOrder.php
│       └── ViewServiceOrder.php     ← Read-Only
├── MaintenanceLogResource.php
│   └── Pages/
│       ├── ListMaintenanceLogs.php
│       ├── CreateMaintenanceLog.php
│       ├── EditMaintenanceLog.php
│       └── ViewMaintenanceLog.php   ← Read-Only
├── StatusAlertResource.php
│   └── Pages/
│       ├── ListStatusAlerts.php
│       └── ViewStatusAlert.php      ← Read-Only
└── UserResource.php
    └── Pages/
        ├── ListUsers.php
        ├── CreateUser.php
        ├── EditUser.php
        └── ViewUser.php             ← Read-Only
```

---

## 🎨 Recursos do Filament v5

### Tabelas
- ✅ Sorting e paginação
- ✅ Filtros avançados
- ✅ Busca em múltiplas colunas
- ✅ Ações por linha
- ✅ Seleção múltipla
- ✅ Bulk actions

### Formulários
- ✅ Validação em tempo real
- ✅ Campos customizados
- ✅ Sections organizacionais
- ✅ Dicas e help text
- ✅ Upload de arquivos

### Pages
- ✅ List Page (tabela com filtros)
- ✅ View Page (read-only, mostra dados)
- ✅ Create Page (novo registro)
- ✅ Edit Page (editar registro)

### Ações
- ✅ Ações por linha (Edit, Delete, etc)
- ✅ Ações customizadas com confirmação
- ✅ Bulk actions (deletar múltiplos)
- ✅ Header actions (Create, Export, etc)

---

## 🔐 Sistema de Permissões

### Roles Disponíveis
1. **Admin** - Acesso total
2. **Gerente** - Criar, editar máquinas e ordens
3. **Técnico** - Criar ordens e logs, visualizar dados

### Aplicação de Permissões
- Verificação no Resource (canCreate, canEdit, canDelete)
- Verificação nas Pages (visibilidade de actions)
- Verificação nas Ações (visible fn com hasRole)

---

## 📝 Exemplo: Criar uma Máquina

1. Acessar `/admin/machines`
2. Clicar em "Adicionar Máquina"
3. Preencher formulário:
   - Número de Série (único)
   - Nome
   - Modelo
   - Localização
   - Status (padrão: operacional)
   - Data de Instalação
   - Descrição (opcional)
   - Imagem (opcional)
4. Clicar em "Criar"
5. Redireciona para lista com mensagem de sucesso

---

## 🔗 Documentação Completa

Para mais informações, veja:
- `FILAMENT_PAGES.md` - Documentação das páginas
- `FILAMENT_ROUTES.md` - Mapa de rotas
- `TESTING.md` - Como rodar e criar testes

---

## 🆘 Debugging

### Ver logs em tempo real
```bash
php artisan pail
```

### Acessar Tinker (shell interativo)
```bash
php artisan tinker
>>> User::first()
>>> Machine::all()
```

### Limpar cache
```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

---

## 📞 Suporte

- **Filament Docs**: https://filamentphp.com
- **Laravel Docs**: https://laravel.com
- **Pest Docs**: https://pestphp.com
