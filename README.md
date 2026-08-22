# 📚 Sistema de Biblioteca — Gerenciamento de Empréstimos

![Testes](https://github.com/DevJhoow/biblioteca-oop/actions/workflows/tests.yml/badge.svg)

Sistema de gerenciamento de empréstimos de livros, em **PHP puro com Programação Orientada a Objetos**, sem framework — projeto de estudo focado em arquitetura de software, princípios SOLID e boas práticas de mercado.

🔗 **[Ver demonstração ao vivo](#)** *(link após o deploy)*

## 🎯 Objetivo

Demonstrar domínio de modelagem orientada a objetos, separação de responsabilidades (SOLID), tratamento de erros com exceções de domínio e testes automatizados — sem depender de um framework fazer isso "por baixo dos panos".

## 🛠️ Tecnologias

PHP 8.2+ · Composer (PSR-4) · PHPUnit

## 📐 Principais decisões de arquitetura

| Decisão | Motivo |
|---|---|
| `Emprestimo` é entidade própria, não campo em `Livro` | Evita que `Livro` acumule responsabilidade sobre regras de empréstimo |
| `GerenciadorEmprestimos` orquestra as regras | Regras que envolvem múltiplas entidades não pertencem a uma isolada |
| Exceções de domínio próprias | Permite tratamento específico por tipo de erro |
| `readonly` + `DateTimeImmutable` | Evita mutação acidental de dados que não deveriam mudar |

## ✅ Regras de negócio

- Livro só é emprestado se estiver disponível
- Usuário tem limite de 3 empréstimos ativos simultâneos
- Devolução libera o livro para novo empréstimo
- Prazo de devolução: 14 dias após retirada

## 🧠 Sobre a demonstração visual (`public/index.php`)

- **`src/`** é o núcleo do sistema — classes de domínio, regras de negócio, arquitetura.
- **`tests/`** valida essas regras de forma automatizada.
- **`public/index.php`** é só uma **vitrine**: consome as classes de `src/` para mostrar alguns cenários no navegador. Não contém lógica de negócio.

Em resumo: `src/` é o motor, `public/index.php` é o painel mostrando o motor funcionando.

## 🧪 Testes

```bash
composer install
php vendor/bin/phpunit
```

## 🚀 Rodar localmente

```bash
git clone https://github.com/seu-usuario/biblioteca-oop.git
cd biblioteca-oop
composer install
php -S localhost:8000 -t public
```

Acesse `http://localhost:8000`.

## 📂 Estrutura