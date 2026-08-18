<?php

declare(strict_types=1);

namespace Biblioteca\Exception;

class LivroIndisponivelException extends \DomainException
{
    public function __construct(string $isbn)
    {
        parent::__construct("O livro com ISBN {$isbn} não está disponível para empréstimo.");
    }
}