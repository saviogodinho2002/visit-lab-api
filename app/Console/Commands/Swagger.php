<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use OpenApi\Generator;

class Swagger extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'swagger';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Esse comando gera a documentação swagger atual';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $openapi = Generator::scan([config("swagger.sources")]);
        file_put_contents("public/swagger/swagger.json" , $openapi->toJson());
        $this->info("Documentação da api gerada com sucesso");
        return Command::SUCCESS;
    }
}
