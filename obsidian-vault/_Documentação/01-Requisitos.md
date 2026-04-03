# 01 - Requisitos do Projeto

## 📌 Contexto

Sistema para **gestão de manutenção de máquinas industriais** com abordagem Industry 4.0. Técnicos registram ordens de serviço, gerentes acompanham a saúde dos equipamentos em tempo real.

### Foco Atual
- Estrutura **Filament/Laravel** sólida
- Interface intuitiva para técnicos e gerentes
- Rastreabilidade completa de intervenções

### Futuro
- Integração com sensores **ESP-32 via MQTT**
- Leitura em tempo real de vibrações, temperatura, etc.
- Análise preditiva de falhas

---

## 🎯 Requisitos Funcionais

### RF1: Gestão de Máquinas
- [ ] Registrar máquinas com serial_number único
- [ ] Rastrear localização (ex: Galpão A - Linha 3)
- [ ] Status: operacional, em manutenção, crítico, offline
- [ ] Histórico de leitura de sensores

### RF2: Ordens de Serviço
- [ ] Criar O.S. preventiva ou corretiva
- [ ] Atribuir a técnico específico
- [ ] Prioridades: baixa, média, alta, crítica
- [ ] Fluxo: aberta → em progresso → concluída → cancelada
- [ ] Notas de resolução ao concluir

### RF3: Histórico de Manutenção
- [ ] Log de todas as intervenções
- [ ] Rastrear definições de defeito recorrente
- [ ] Análise de padrões de falha

### RF4: Alertas de Status
- [ ] Notificação automática ao mudar status da máquina
- [ ] Alertas não lidos na dashboard
- [ ] Histórico de todos os alertas

### RF5: Controle de Acesso
- [ ] Roles: admin, gerente, tecnico, operador
- [ ] Permissões granulares por função
- [ ] Auditoria de ações

---

## 📊 Requisitos Não-Funcionais

- **Performance**: Dashboard carrega em <2s
- **Usabilidade**: Interface em português (pt_BR)
- **Segurança**: Autenticação + RBAC via Spatie
- **Disponibilidade**: Dark mode para ambiente industrial
- **Escalabilidade**: Preparado para >1000 máquinas

---

## 💻 Stack Tecnológico

```
Frontend: Filament v3 (Laravel Admin Panel)
Backend: Laravel 11 + PHP 8.3+
Banco: MySQL 8.0+
ORM: Eloquent
Autenticação: Spatie Laravel Permission
Deploy: Docker ready
```

---

*[[_Documentação/README]] | [[_Documentação/02-Arquitetura]]*
