<?php
declare(strict_types=1);

namespace Biblioteca;

enum EmprestimoStatus: string 
{
    case ATIVO = 'ativo';
    case DEVOLVIDO = 'devolvido';
    case ATRASADO = 'atrasado';
}


