<?php

declare(strict_types=1);

namespace Biblioteca\Exception;

class LimiteEmprestimosExcedidoException extends \DomainException
{
    public function __construct(string $usuarioNome, int $limite)
    {
        parent::__construct("O usuário {$usuarioNome} já atingiu o limite de {$limite} empréstimos ativos.");
    }
}