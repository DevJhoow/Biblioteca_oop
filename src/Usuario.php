<?php

declare(strict_types=1);

namespace Biblioteca;

class Usuario
{
    private const LIMITE_EMPRESTIMOS = 3;

    public function __construct(
        private readonly string $nome,
        private readonly string $email
    ) {
        if (trim($nome) === '') {
            throw new \InvalidArgumentException('Nome não pode ser vazio.');
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new \InvalidArgumentException('E-mail inválido.');
        }
    }

    public function getNome(): string
    {
        return $this->nome;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public static function getLimiteEmprestimos(): int
    {
        return self::LIMITE_EMPRESTIMOS;
    }
}