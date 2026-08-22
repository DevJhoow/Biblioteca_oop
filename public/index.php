<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use Biblioteca\GerenciadorEmprestimos;
use Biblioteca\Livro;
use Biblioteca\Usuario;
use Biblioteca\Exception\LivroIndisponivelException;
use Biblioteca\Exception\LimiteEmprestimosExcedidoException;

$gerenciador = new GerenciadorEmprestimos();

// Criando alguns livros
$livro1 = new Livro('001', 'Clean Code', 'Robert C. Martin');
$livro2 = new Livro('002', 'Clean Architecture', 'Robert C. Martin');
$livro3 = new Livro('003', 'Refactoring', 'Martin Fowler');
$livro4 = new Livro('004', 'Design Patterns', 'Gang of Four');

// Criando um usuário
$usuario = new Usuario('João Silva', 'joao@email.com');

$eventos = [];

// Cenário 1: empréstimo com sucesso
try {
    $emprestimo1 = $gerenciador->realizarEmprestimo($livro1, $usuario, new DateTimeImmutable());
    $eventos[] = [
        'tipo' => 'sucesso',
        'mensagem' => "Empréstimo realizado: \"{$livro1->getTitulo()}\" para {$usuario->getNome()}. Devolução prevista: {$emprestimo1->getDataPrevistaDevolucao()->format('d/m/Y')}",
    ];
} catch (Exception $e) {
    $eventos[] = ['tipo' => 'erro', 'mensagem' => $e->getMessage()];
}

// Cenário 2: tentar emprestar o mesmo livro (já emprestado) para outro usuário
$outroUsuario = new Usuario('Maria Souza', 'maria@email.com');
try {
    $gerenciador->realizarEmprestimo($livro1, $outroUsuario, new DateTimeImmutable());
} catch (LivroIndisponivelException $e) {
    $eventos[] = ['tipo' => 'erro', 'mensagem' => $e->getMessage()];
}

// Cenário 3: estourar o limite de empréstimos do mesmo usuário
try {
    $gerenciador->realizarEmprestimo($livro2, $usuario, new DateTimeImmutable());
    $eventos[] = ['tipo' => 'sucesso', 'mensagem' => "Empréstimo realizado: \"{$livro2->getTitulo()}\" para {$usuario->getNome()}"];

    $gerenciador->realizarEmprestimo($livro3, $usuario, new DateTimeImmutable());
    $eventos[] = ['tipo' => 'sucesso', 'mensagem' => "Empréstimo realizado: \"{$livro3->getTitulo()}\" para {$usuario->getNome()}"];

    // Esse deve estourar o limite (usuário já tem 3 ativos)
    $gerenciador->realizarEmprestimo($livro4, $usuario, new DateTimeImmutable());
} catch (LimiteEmprestimosExcedidoException $e) {
    $eventos[] = ['tipo' => 'erro', 'mensagem' => $e->getMessage()];
}

// Cenário 4: devolução libera o livro
$gerenciador->devolverLivro($emprestimo1, new DateTimeImmutable());
$eventos[] = [
    'tipo' => 'info',
    'mensagem' => "Livro \"{$livro1->getTitulo()}\" devolvido. Disponível novamente: " . ($livro1->isDisponivel() ? 'Sim' : 'Não'),
];

$totalAtivos = count($gerenciador->listarEmprestimosAtivos());

?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Sistema de Biblioteca - Demonstração</title>
    <style>
        body { font-family: system-ui, sans-serif; max-width: 700px; margin: 40px auto; padding: 0 20px; background: #0f172a; color: #e2e8f0; }
        h1 { color: #38bdf8; }
        .evento { padding: 12px 16px; margin: 10px 0; border-radius: 6px; border-left: 4px solid; }
        .sucesso { background: #14532d; border-color: #22c55e; }
        .erro { background: #450a0a; border-color: #ef4444; }
        .info { background: #1e3a8a; border-color: #3b82f6; }
        .resumo { margin-top: 30px; padding: 16px; background: #1e293b; border-radius: 6px; }
        code { background: #1e293b; padding: 2px 6px; border-radius: 4px; }
    </style>
</head>
<body>
    <h1>📚 Sistema de Biblioteca — Demonstração </h1>
    <p>Simulação executada em <?= (new DateTimeImmutable())->format('d/m/Y H:i') ?></p>

    <?php foreach ($eventos as $evento) : ?>
        <div class="evento <?= $evento['tipo'] ?>">
            <?= htmlspecialchars($evento['mensagem']) ?>
        </div>
    <?php endforeach; ?>

    <div class="resumo">
        <strong>Empréstimos ativos no momento:</strong> <?= $totalAtivos ?>
    </div>
</body>
</html>