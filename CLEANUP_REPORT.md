# Análise de Cleanup - MaintSys Repository
📅 Data: 2026-04-04 | Tamanho Total: ~160 MB

---

## 🗑️ ARQUIVOS/DIRETÓRIOS DESNECESSÁRIOS (Remover)

### 1. **`/temp` (988 KB)** ⚠️ PRIORIDADE ALTA
- **Tipo**: Diretório de cache com vendor cópia
- **Por quê remover**: Duplicação do `/vendor`. Parece ser um backup temporário de dependências
- **Ação**: `rm -rf /home/racer/Codes/maintsys/temp`
- **Economia**: ~988 KB

### 2. **`/obsidian-vault` (16 KB)** ⚠️ PRIORIDADE MÉDIA
- **Tipo**: Notas de desenvolvimento pessoais em Obsidian
- **Por quê remover**: Não faz parte do projeto; é overhead de documentação pessoal
- **Conteúdo**: `.obsidian/workspace.json` (configuração de workspace)
- **Ação**: `rm -rf /home/racer/Codes/maintsys/obsidian-vault`
- **Alternativa**: Mover para fora do repo ou documentar em README/wiki
- **Economia**: ~16 KB

### 3. **`tests/Feature/ExampleTest.php` (20 linhas)** ⚠️ PRIORIDADE MÉDIA
- **Tipo**: Teste boilerplate/exemplo não usado
- **Por quê remover**: Substitui testes reais (RoleResourceTest, MachineResourceTest, etc.)
- **Conteúdo**: Apenas testa se "/" retorna 200 - irrelevante
- **Ação**: `rm /home/racer/Codes/maintsys/tests/Feature/ExampleTest.php`
- **Economia**: Manutenção + clareza

### 4. **`tests/Unit/ExampleTest.php` (17 linhas)** ⚠️ PRIORIDADE MÉDIA
- **Tipo**: Teste boilerplate/exemplo não usado
- **Por quê remover**: Não tem valor (testa `assertTrue(true)`)
- **Ação**: `rm /home/racer/Codes/maintsys/tests/Unit/ExampleTest.php`
- **Economia**: Manutenção + clareza

### 5. **`resources/views/welcome.blade.php` (41 KB)** ⚠️ PRIORIDADE ALTA
- **Tipo**: View padrão Laravel não usada
- **Por quê remover**: Sistema usa Filament para UI; rota "/" renderiza isso (overhead)
- **Problema**: Rota `/` em `routes/web.php` aponta para esta view com 41KB de Tailwind CSS inline
- **Ação**: Remover view + atualizar rota
- **Economia**: ~41 KB + melhor performance

### 6. **`seed_users.sql` (1.2 KB)** ⚠️ PRIORIDADE BAIXA
- **Tipo**: Script SQL manual de seed
- **Por quê remover**: Duplica funcionalidade de `database/seeders/RoleAndPermissionSeeder.php` (63 linhas)
- **Status**: Não rastreado no git (aparece com `??`), não é parte ativa
- **Ação**: `rm /home/racer/Codes/maintsys/seed_users.sql`
- **Alternativa**: Usar apenas seeders Laravel
- **Economia**: Clareza + manutenção

---

## ⚡ ARQUIVOS COM OVERHEAD (Simplificar)

### 1. **`/public/css` (688 KB) + `/public/js` (3.5 MB) + `/public/fonts` (244 KB)**⚠️ PRIORIDADE ALTA
- **Problema**: Assets compilados/copiados pelo Filament
- **Tamanho**: ~4.4 MB (assets distribuídos)
- **Por quê overhead**:
  - Filament v5 cria assets compilados em `/public`
  - Ideal é usar Vite (já configurado) para compilar on-demand em dev, distribuir otimizado em prod
  - Estes arquivos são regenerados em cada build
- **Ação**:
  - ✅ Não remover (necessário para execução)
  - ✅ Adicionar ao `.gitignore` se ainda não está
  - Executar: `npm run build` para otimizar em produção
- **Verificar**.gitignore`:
  ```
  echo "/public/css" >> .gitignore
  echo "/public/js" >> .gitignore
  echo "/public/fonts" >> .gitignore
  ```

### 2. **`composer.lock` (420 KB)**
- **Problema**: Arquivo gerado, rastreado no git
- **Status**: Essencial para consistência (travamento de versões)
- **Ação**: ✅ Manter (padrão em Laravel)
- **Nota**: Se for produção, é crítico; em desenvolvimento pode ser regenerado

### 3. **`resources/views/filament/` (tamanho pequeno)**
- **Status**: ✅ OK, usado pelo Filament
- **Recomendação**: Manter como está

### 4. **`resources/js` (app.js 22 bytes + bootstrap.js 127 bytes)**
- **Status**: ✅ OK, mínimo esperado
- **Ação**: Manter

###5. **Config files overhead**
- **10 arquivos** em `/config` (padrão Laravel)
- **Status**: ✅ OK, maioria é necessária
- **Sugestão**: Revisar quais não são usados (cron, broadcasting, etc)
  ```bash
  grep -r "config('broadcasting'|'cache'|'session')" app/
  ```

---

## 📋 RESUMO: AÇÕES RECOMENDADAS

### **REMOVER IMEDIATAMENTE** (Ganho real ~42 MB)
```bash
# Menos urgente (16 KB)
rm -rf obsidian-vault
rm seed_users.sql
rm tests/Feature/ExampleTest.php
rm tests/Unit/ExampleTest.php

# Urgente (41 KB + manutenção)
rm resources/views/welcome.blade.php
# E atualizar routes/web.php (remover a rota "/" ou redirecionar para /admin)

# Crítico (988 KB)
rm -rf temp
```

### **ATUALIZAR CONFIGURAÇÕES**
```bash
# Verificar .gitignore (adicionar se não houver)
echo "/public/css" >> .gitignore
echo "/public/js" >> .gitignore
echo "/public/fonts" >> .gitignore
echo "/storage/logs/*" >> .gitignore

# Limpar cache do bootstrap
rm -rf bootstrap/cache/*
```

### **VALIDAR E TESTAR**
```bash
# Verificar testes ainda passam
php artisan test

# Verificar deps não usadas
composer validate --strict
```

---

## 🎯 GANHOS ESPERADOS

| Ação | Tamanho | Ganho real | Prioridade |
|------|---------|-----------|-----------|
| Remover `/temp` | 988 KB | ~988 KB | ⚠️ ALTA |
| Remover `welcome.blade.php` | 41 KB | ~41 KB | ⚠️ ALTA |
| Remover `/obsidian-vault` | 16 KB | ~16 KB | 🟡 MÉDIA |
| Remover exemplo tests | ~40 bytes | Clareza | 🟡 MÉDIA |
| Remover `seed_users.sql` | 1.2 KB | Clareza | 🟢 BAIXA |
| **TOTAL** | - | **~1 MB + Clareza** | - |

---

## ✅ CHECKLIST DE EXECUÇÃO

- [ ] Backup do repo (git commit + push)
- [ ] Remover `/temp`
- [ ] Remover `/obsidian-vault`
- [ ] Remover arquivos test exemplo
- [ ] Remover `welcome.blade.php`
- [ ] Atualizar `routes/web.php`
- [ ] Executar `php artisan test` (validar testes passam)
- [ ] Atualizar `.gitignore`
- [ ] Fazer commit com mensagem clara
- [ ] Verificar tamanho final: `du -sh .`

---

**📊 Resultado esperado:**
- De ~160 MB → ~159 MB (repositório)
- Melhor clareza de código
- Menos overhead de desenvolvimento
