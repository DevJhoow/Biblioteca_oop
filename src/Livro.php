<?php

declare(strict_types=1);
// tipagem estrita, fica rigoroso a tipagem do algoritmo

namespace Biblioteca;

class Livro
{
    private bool $disponivel = true;

    public function __construct(
        private readonly string $isbn,
        private readonly string $titulo,
        private readonly string $autor,
    ) {
        if (trim($isbn) === '') {
            throw new \InvalidArgumentException('ISBN não pode ser vazio.');
        }
        if (trim($titulo) === '') {
            throw new \InvalidArgumentException('Título não pode ser vazio.');
        }
    }

    public function getIsbn(): string
    {
        return $this->isbn;
    }

    public function getTitulo(): string
    {
        return $this->titulo;
    }

    public function getAutor(): string
    {
        return $this->autor;
    }

    // Verifica se o livro está disponível para empréstimo
    public function isDisponivel(): bool
    {
        return $this->disponivel;
    }

    public function marcarComoEmprestado(): void
    {
        $this->disponivel = false;
    }

    public function marcarComoDisponivel(): void
    {
        $this->disponivel = true;
    }
}