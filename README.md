# Sistema de Contas Bancárias - Code Test

Este sistema foi desenvolvido para avaliar as habilidades de desenvolvimento dos candidatos por meio de um desafio prático. A aplicação permite a criação e o gerenciamento de três tipos de contas bancárias: **Conta Poupança**, **Conta Corrente** e **Conta Investimentos**. O sistema inclui um backend em **Laravel** e um frontend em **React**, com autenticação implementada via **Laravel Passport**.

---

## 🚀 Funcionalidades Gerais

### 1. Cadastro de Usuário
- Os usuários podem se cadastrar fornecendo:
  - **Nome**
  - **CPF** (único)
  - **E-mail** (único)
  - **Senha**
- O sistema valida para evitar duplicidade de **CPF** e **e-mail**.

### 2. Tipos de Contas
- **Conta Poupança**
- **Conta Corrente**
- **Conta Investimentos**

### 3. Operações Disponíveis
- **Depósito**: Adicionar valores ao saldo de uma conta específica.
- **Consulta de Saldo**: Visualizar o saldo atual de qualquer conta do usuário.
- **Correção Monetária**: Aplicada mensalmente conforme as regras de cada tipo de conta.

### 4. Autenticação e Controle de Acesso
- Apenas usuários autenticados podem acessar as operações de suas contas.
- A autenticação é realizada via **Laravel Passport** com tokens de acesso OAuth2.

---

## 📜 Regras de Negócio

### 1. Conta Poupança
- **Depósito**:
  - O valor depositado é adicionado diretamente ao saldo da conta.
- **Correção Monetária**:
  - 0,001% do saldo é aplicado no final de cada mês.

### 2. Conta Corrente
- **Depósito**:
  - O valor depositado recebe um incremento de **R$0,50** antes de ser adicionado ao saldo.
  - **Exemplo**: Um depósito de **R$100,00** resulta em **R$100,50** adicionados ao saldo.
- **Correção Monetária**:
  - 0,1% do saldo é aplicado no final de cada mês.

### 3. Conta Investimentos
- **Depósito**:
  - O valor depositado recebe um incremento de **R$0,50** antes de ser adicionado ao saldo.
  - **Exemplo**: Um depósito de **R$200,00** resulta em **R$200,50** adicionados ao saldo.
- **Correção Monetária**:
  - 0,1% do saldo é aplicado no final de cada mês.

---

## 🛠️ Tecnologias Utilizadas
- **Backend**: Laravel
- **Frontend**: React
- **Autenticação**: Laravel Passport (OAuth2)

---

## 😁 Sobre suas habilidades
Este code test não foi feito para avaliar o quão bem você conhece PHP, mas sim para explorar suas habilidades com Laravel e, acima de tudo, seu domínio em programação orientada a objetos no **modo hard**. Prepare-se para brilhar! 😎

## 🏁 Como Usar
1. Clone este repositório.
2. Configure o backend em Laravel e o frontend em React.
3. Configure o Laravel Passport para autenticação.
4. Execute os comandos de migração para configurar o banco de dados.
5. Inicie o servidor backend e frontend para acessar o sistema.

---

## 📢 Observações
Este sistema é uma simulação para o teste técnico de contratação e visa avaliar suas habilidades em backend, frontend, e lógica de negócios.
