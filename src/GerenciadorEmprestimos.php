<?php

declare(strict_types=1);

namespace Biblioteca ;

use Biblioteca\Exception\LivroIndisponivelException;
use Biblioteca\Exception\LimiteEmprestimosExcedidoException;

class GerenceiadorEmprestimo
{
    private array $emprestimos = [];

    public function realizarEmprestimo(Livro $livro, Usuario $usuario, \DateTimeImmutable $dataRetirada): Emprestimo
    {
        if(!$livro->idDisponivel()) {
            throw new LivroIndisponivelException($livro->getIsbn());
        }

        $ativoDoUsuario = $this->cpntarEmprestimosAtivos($usuario);

        if($ativoDoUsuario >= Usuario::getLimiteEmprestimo()) {
            throw new LimiteEmprestimoException(
                $usuario->getNome(),
                Usuario::getLimiteEmprestimos()
            );
        }

        $emprestimo = new Emprestimo($livro, $usuario, $dataRetirada);
        $livro->marcarComoEmprestado();
        $this->emprestimos[] = $emprestimo;

        return $emprestimos;
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