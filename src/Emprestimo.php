<?php

declare(strict_types=1);

namespace Biblioteca;

class Emprestimo
{
    private const DIAS_PARA_DEVOLUCAO = 14;

    // duas propriedades que representam o estado do empréstimo
    private EmprestimoStatus $status = EmprestimoStatus::ATIVO;
    private ?\DateTimeImmutable $dataDevolucao = null;

    public function __construct(
        private readonly Livro $livro,
        private readonly Usuario $usuario,
        private readonly \DateTimeImmutable $dataRetirada,
    ) {
    }

    public function getLivro(): Livro
    {
        return $this->livro;
    }

    public function getUsuario(): Usuario
    {
        return $this->usuario;
    }

    public function getStatus(): EmprestimoStatus
    {
        return $this->status;
    }

    public function getDataPrevistaDevolucao(): \DateTimeImmutable
    {
        return $this->dataRetirada->modify('+' . self::DIAS_PARA_DEVOLUCAO . ' dias');
    }

    public function registrarDevolucao(\DateTimeImmutable $data): void
    {
        $this->dataDevolucao = $data;
        $this->status = EmprestimoStatus::DEVOLVIDO;
        $this->livro->marcarComoDisponivel();
    }
}