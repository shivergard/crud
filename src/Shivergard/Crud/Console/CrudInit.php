<?php namespace Shivergard\Crud\Console;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Console\GeneratorCommand;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class CrudInit extends GeneratorCommand {

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'crud:init';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Initalize crud basic Views';



    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire()
    {

        $this->files->copyDirectory(__DIR__.'/stubs/crud' , base_path().'/resources/views/');

        
    }





    protected function getStub(){
        return __DIR__.'/stubs/';
    }



    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return array(
        );
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return array(
        );
    }

}