# 🔐 Roles e Permissions - MaintSys

## 📋 Matriz de Acesso

| Role | Máquinas | O.S. | Logs | Leituras | Alertas | Usuários |
|------|----------|------|------|----------|---------|----------|
| **Admin** | CRUD | CRUD | CRUD | CRUD | CRUD | CRUD |
| **Gerente** | R, U | CRUD | R | - | R | R |
| **Técnico** | R | R, U (próprias) | C | - | R | R |
| **Operador** | R | R | R | R | R | R |

---

## 👥 Credenciais de Teste

### 🔑 Admin
```
Email: admin@example.com
Senha: password
Acesso: Total (CRUD em tudo)
```

### 👔 Gerente
```
Email: gerente@example.com
Senha: password
Permissões:
  - Máquinas: Ver e Editar
  - Ordens de Serviço: CRUD completo
  - Logs: Apenas Visualizar
  - Alertas: Apenas Visualizar
  - Usuários: Apenas Visualizar
```

### 🔧 Técnico
```
Email: tecnico@example.com
Senha: password
Permissões:
  - Máquinas: Apenas Visualizar
  - Ordens de Serviço: Ver, Criar e Editar (próprias)
  - Logs: Criar
  - Alertas: Apenas Visualizar
  - Usuários: Apenas Visualizar
```

### 📱 Operador
```
Email: operador@example.com
Senha: password
Permissões:
  - Acesso somente leitura (Read-Only) em todos os recursos
```

---

## 🌐 URLs de Acesso

```
Login: http://localhost:8000/admin
Admin: http://localhost:8000/admin/resources/machines
```

---

## 🔓 Permissões Detalhadas

### Machines
- ✅ admin: view, create, update, delete
- ✅ gerente: view, update
- ✅ tecnico: view
- ✅ operador: view

### Service Orders
- ✅ admin: view, create, update, delete
- ✅ gerente: view, create, update, delete
- ✅ tecnico: view, create, update (próprias)
- ✅ operador: view

### Maintenance Logs
- ✅ admin: view, create, update, delete
- ✅ gerente: view
- ✅ tecnico: view, create
- ✅ operador: view

### Machine Readings
- ✅ admin: view (CRUD)
- ✅ operador: view

### Status Alerts
- ✅ admin: view (CRUD)
- ✅ gerente: view
- ✅ tecnico: view
- ✅ operador: view

### Users
- ✅ admin: view, create, update, delete
- ✅ gerente: view
- ✅ tecnico: view
- ✅ operador: view

---

## 🧪 Testando as Permissões

1. Faça login com cada usuário
2. Verifique quais botões de ação aparecem
3. Tente acessar URLs que não tem permissão - deve receber erro 403

**Exemplo de URL referida:**
- Admin pode ver tudo: `/admin/resources/machines`
- Gerente pode ver máquinas mas não pode deletar
- Técnico pode ver máquinas mas não pode editar
- Operador pode ver máquinas mas não pode criar
