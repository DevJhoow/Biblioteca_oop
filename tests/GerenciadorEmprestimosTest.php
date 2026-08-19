<?php
declare(strict_types=1);

namespace Biblioteca\Tests;

use Biblioteca\Emprestimo;
use Biblioteca\EmprestimoStatus;
use Biblioteca\Exception\LimiteEmprestimosExcedidoException;
use Biblioteca\Exception\LivroIndisponivelException;
use Biblioteca\GerenciadorEmprestimos;
use Biblioteca\Livro;
use Biblioteca\Usuario;
use PHPUnit\Framework\TestCase;

class GerenciadorEmprestimosTest extends TestCase
{
    private GerenciadorEmprestimos $gerenciador;

    protected function setUp(): void
    {
        $this->gerenciador = new GerenciadorEmprestimos();
    }

    public function testRealizarEmprestimoComSucesso(): void 
    {
        $livro = new Livro('123', 'Clean Code', 'Robert C');
        $usuario = new Usuario('Joâo Silva', 'Joaosilva@gmail.com');

        $emprestimo = $this->gerenciador->realizarEmprestimo(
            $livro,
            $usuario,
            new \DateTimeImmutable('2026-01-01')
        );
        
          $this->assertFalse($livro->isDisponivel());
        $this->assertSame(EmprestimoStatus::ATIVO, $emprestimo->getStatus());
    }

    public function testNaoPermiteEmprestarLivroIndisponivel(): void
    {
        $livro = new Livro('123', 'Clean Code', 'Robert C. Martin');
        $usuario1 = new Usuario('João Silva', 'joao@email.com');
        $usuario2 = new Usuario('Maria Souza', 'maria@email.com');

        $this->gerenciador->realizarEmprestimo($livro, $usuario1, new \DateTimeImmutable());

        $this->expectException(LivroIndisponivelException::class);
        $this->gerenciador->realizarEmprestimo($livro, $usuario2, new \DateTimeImmutable());
    }

    public function testNaoPermiteExcederLimiteDeEmprestimos(): void
    {
        $usuario = new Usuario('João Silva', 'joao@email.com');

        // Usuário pega emprestado até o limite (3 livros)
        for ($i = 1; $i <= 3; $i++) {
            $livro = new Livro((string) $i, "Livro {$i}", 'Autor');
            $this->gerenciador->realizarEmprestimo($livro, $usuario, new \DateTimeImmutable());
        }

        // O 4º empréstimo deve estourar o limite
        $livroExtra = new Livro('999', 'Livro Extra', 'Autor');

        $this->expectException(LimiteEmprestimosExcedidoException::class);
        $this->gerenciador->realizarEmprestimo($livroExtra, $usuario, new \DateTimeImmutable());
    }

    public function testDevolucaoLiberaLivroParaNovoEmprestimo(): void
    {
        $livro = new Livro('123', 'Clean Code', 'Robert C. Martin');
        $usuario1 = new Usuario('João Silva', 'joao@email.com');
        $usuario2 = new Usuario('Maria Souza', 'maria@email.com');

        $emprestimo = $this->gerenciador->realizarEmprestimo($livro, $usuario1, new \DateTimeImmutable());
        $this->gerenciador->devolverLivro($emprestimo, new \DateTimeImmutable());

        $this->assertTrue($livro->isDisponivel());

        // Agora outro usuário consegue pegar o mesmo livro
        $novoEmprestimo = $this->gerenciador->realizarEmprestimo($livro, $usuario2, new \DateTimeImmutable());
        $this->assertSame(EmprestimoStatus::ATIVO, $novoEmprestimo->getStatus());
    }
    
}