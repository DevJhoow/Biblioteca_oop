<?php
declare(strict_types=1);
// para a tipagem ficar mais rigida , porque =1?na programação 1 é positivo 0 é negativo 

namespace Biblioteca;

class Usuario
{
    private const LIMITE_EMPRESTIMO = 3;

    public function __construct(
        private readonly string $nome,
        private readonly string $email
    ){
        if(trim($nome) === '') {
            throw new \InvalidArgumentException('Nome não pode ser vazio');
        }
            // filtra o email para saber se é valido 
          if(!filter_var(trim($email, FILTER_VALIDATE_EMAIL) === '')) {
            throw new \InvalidArgumentException('E-mail é invalido');
        }
    }

    public function getNome(): string 
    {
        return this->nome;
    }

    public function getEmail(): string 
    {
        return this->email;
    }

    public function getLimiteDeEmprestimos(): int 
    {
        return self::LIMITE_EMPRESTIMO;
    }
}