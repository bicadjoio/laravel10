<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Http\ViewComposers\UserComposer;
use Illuminate\Support\Facades\Storage;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
         // Registra o Composer que define `$user` em todas as views
        View::composer('*', UserComposer::class);
        checkAndCreateHtaccess(storage_path('app')); // Criar em storage/app
    }
  
}


function checkAndCreateHtaccess($folderPath)
{

    if (!Storage::exists(config('filesystems.paths.uploads'))) {
        Storage::makeDirectory(config('filesystems.paths.uploads'));
    }

    $htaccessPath = $folderPath . '/.htaccess';

    // Verifica se o .htaccess já existe
    if (!file_exists($htaccessPath)) {
        // Conteúdo do .htaccess padrão
        $htaccessContent = <<<HTACCESS
            # Proteger diretórios sensíveis do Laravel
            <IfModule mod_rewrite.c>
                RewriteEngine On
                RewriteCond %{REQUEST_FILENAME} !-f
                RewriteCond %{REQUEST_FILENAME} !-d
                RewriteRule ^(.*)$ index.php/$1 [L]
            </IfModule>

            # Bloquear acesso direto a arquivos sensíveis
            <FilesMatch "(\.env|\.log|\.sql|\.ini|\.htaccess|\.htpasswd)">
                Order Allow,Deny
                Deny from all
            </FilesMatch>

            # Proteger uploads contra execução de scripts
            <FilesMatch "\.(php|php5|phtml)$">
                Order Allow,Deny
                Deny from all
            </FilesMatch>

            # Habilitar cache para performance
            <IfModule mod_expires.c>
                ExpiresActive On
                ExpiresDefault "access plus 1 month"
            </IfModule>

            # Bloquear acesso direto
            <FilesMatch "\.(csv|txt|xlsx)$">
                Require all denied
            </FilesMatch>

        HTACCESS;

        // Criar o arquivo .htaccess
        if (file_put_contents($htaccessPath, $htaccessContent)) {
            return "Arquivo .htaccess criado com sucesso em: $htaccessPath";
        } else {
            return "Erro ao criar o arquivo .htaccess em: $htaccessPath";
        }
    } else {
        return "O arquivo .htaccess já existe em: $htaccessPath";
    }
}

