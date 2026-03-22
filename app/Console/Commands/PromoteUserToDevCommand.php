<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class PromoteUserToDevCommand extends Command
{
    protected $signature = 'user:promote-dev {username : Nome de usuário (login) a promover}';

    protected $description = 'Define nivel_acesso=dev (somente via terminal; não disponível no painel para o dono).';

    public function handle(): int
    {
        $username = strtolower($this->argument('username'));
        $user = User::where('username', $username)->first();

        if (! $user) {
            $this->error("Usuário \"{$username}\" não encontrado.");

            return self::FAILURE;
        }

        if ($user->nivel_acesso === 'dev') {
            $this->info('Este usuário já possui o cargo DEV.');

            return self::SUCCESS;
        }

        $user->update(['nivel_acesso' => 'dev']);
        $this->info("Usuário \"{$username}\" promovido a DEV com sucesso.");

        return self::SUCCESS;
    }
}
