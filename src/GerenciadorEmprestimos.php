<?php

declare(strict_types=1);

namespace Biblioteca;

use Biblioteca\Exception\LivroIndisponivelException;
use Biblioteca\Exception\LimiteEmprestimosExcedidoException;

class GerenciadorEmprestimos
{
    private array $emprestimos = [];

    public function realizarEmprestimo(Livro $livro, Usuario $usuario, \DateTimeImmutable $dataRetirada): Emprestimo
    {
        if (!$livro->isDisponivel()) {
            throw new LivroIndisponivelException($livro->getIsbn());
        }

        $ativoDoUsuario = $this->contarEmprestimosAtivos($usuario);

        if ($ativoDoUsuario >= Usuario::getLimiteEmprestimos()) {
            throw new LimiteEmprestimosExcedidoException(
                $usuario->getNome(),
                Usuario::getLimiteEmprestimos()
            );
        }

        $emprestimo = new Emprestimo($livro, $usuario, $dataRetirada);
        $livro->marcarComoEmprestado();
        $this->emprestimos[] = $emprestimo;

        return $emprestimo;
    }

    public function devolverLivro(Emprestimo $emprestimo, \DateTimeImmutable $dataDevolucao): void
    {
        $emprestimo->registrarDevolucao($dataDevolucao);
    }

    public function listarEmprestimosAtivos(): array
    {
        return array_values(array_filter(
            $this->emprestimos,
            fn (Emprestimo $e) => $e->getStatus() === EmprestimoStatus::ATIVO
        ));
    }

    private function contarEmprestimosAtivos(Usuario $usuario): int
    {
        return count(array_filter(
            $this->emprestimos,
            fn (Emprestimo $e) => $e->getUsuario() === $usuario
                && $e->getStatus() === EmprestimoStatus::ATIVO
        ));
    }
}