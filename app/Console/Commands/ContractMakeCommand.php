<?php

namespace App\Console\Commands;

use Illuminate\Console\GeneratorCommand;

class ContractMakeCommand extends GeneratorCommand
{
    protected $signature = 'make:contract {name}';
    protected $description = 'Create a new contract';
    protected $type = 'Contract';

    protected function getStub(): string
    {
        return base_path('stubs/contract.stub');
    }

    protected function getDefaultNamespace($rootNamespace): string
    {
        return $rootNamespace . '\Contracts';
    }

    protected function replaceClass($stub, $name): array|string
    {
        $class = str_replace($this->getNamespace($name).'\\', '', $name);
        return str_replace('{{contract_name}}', $class, $stub);
    }
}
