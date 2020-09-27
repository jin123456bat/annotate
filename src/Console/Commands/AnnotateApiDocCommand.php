<?php

namespace Annotate\Console\Commands;

use Annotate\Library\AnnotateRoute;
use Annotate\Library\DocBuilder;
use Annotate\Library\Saver;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Route;

class AnnotateApiDocCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'annotate:doc';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $routes = Route::getRoutes();
        $annotate_route = [];
        foreach ($routes as $route) {
            $annotate = new AnnotateRoute($route);
            if ($annotate->isValid()) {
                $annotate_route[] = $annotate;
            }
        }

        $drivers = config('annotate.driver');
        foreach ($drivers as $driver => $driver_config) {
            if (!class_exists($driver)) {
                throw new Exception('Annotate [Error] : Driver Not Exist ' . $driver);
            }
            $docBuilder = new DocBuilder(new $driver($driver_config));
            $document = $docBuilder->build($annotate_route);

            $savers = config('annotate.saver');
            foreach ($savers as $saver => $saver_config) {
                if (!class_exists($saver)) {
                    throw new Exception('Annotate [Error] : Saver Not Exist ' . $saver);
                }
                $saver = new Saver(new $saver($this->input, $this->output, $saver_config));
                $saver->save($document);
            }
        }
    }
}
