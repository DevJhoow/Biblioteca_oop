# 📚 Sistema de Biblioteca — Gerenciamento de Empréstimos

Sistema de gerenciamento de empréstimos de livros, desenvolvido em **PHP puro com Programação Orientada a Objetos**, sem uso de frameworks — construído como estudo aprofundado de arquitetura de software, princípios SOLID e boas práticas de mercado.

🔗 **[Ver demonstração ao vivo](#)** *(link após o deploy)*

## 🎯 Objetivo do projeto

Este projeto foi construído com foco em **fundamentos**, não em produtividade com framework. A ideia é demonstrar domínio de:

- Modelagem de domínio orientada a objetos
- Separação de responsabilidades (SRP)
- Tratamento de erros com exceções de domínio
- Testes automatizados como parte do processo, não como extra
- Autoload via Composer (PSR-4)

## 🛠️ Tecnologias

- PHP 8.2+
- Composer (gerenciamento de dependências e autoload PSR-4)
- PHPUnit (testes automatizados)

## 📐 Decisões de arquitetura

| Decisão | Motivo |
|---|---|
| `Emprestimo` como entidade própria, não um campo em `Livro` | Evita que `Livro` acumule responsabilidade sobre regras de empréstimo (violaria SRP) |
| `GerenciadorEmprestimos` como camada de orquestração | Regras que dependem de múltiplas entidades (limite por usuário, disponibilidade) não pertencem a uma entidade isolada |
| Exceções de domínio (`LivroIndisponivelException`, `LimiteEmprestimosExcedidoException`) | Permite tratamento específico por tipo de erro, em vez de `Exception` genérica |
| `readonly` em propriedades imutáveis | Garante que dados como ISBN não sejam alterados após criação do objeto |
| `DateTimeImmutable` em vez de `DateTime` | Evita mutação acidental de datas compartilhadas entre objetos |

## ✅ Regras de negócio implementadas

- Um livro só pode ser emprestado se estiver disponível
- Cada usuário tem um limite máximo de 3 empréstimos ativos simultâneos
- A devolução de um livro o torna disponível novamente para novo empréstimo
- Prazo de devolução calculado automaticamente (14 dias após retirada)

## 🧪 Testes

O projeto conta com testes unitários cobrindo as regras de negócio principais:

```bash
composer install
php vendor/bin/phpunit
```

## 🚀 Como rodar localmente

```bash
git clone https://github.com/seu-usuario/biblioteca-oop.git
cd biblioteca-oop
composer install
php -S localhost:8000 -t public
```

Depois acesse `http://localhost:8000`.

## 📂 Estrutura do projeto