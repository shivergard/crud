<?php namespace Shivergard\Crud\Console;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Console\GeneratorCommand;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

use Config;

class CrudMake extends GeneratorCommand {

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'make:crud';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new Eloquent Crud class';

    protected $type = 'Crud';


    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire()
    {
        $name = $this->parseModelName($this->getNameInput());

        //make model
        $this->info($this->getModelPath($name));
        if ($this->files->exists($path = $this->getModelPath($name)))
        {
            return $this->error($this->type.' already exists!');
        }
        $this->makeDirectory($path);
        $this->files->put($path, $this->buildModel($name));
        $this->info($this->type.' model created successfully.');

        if ( ! $this->option('no-migration'))
        {
            $table = str_plural(snake_case(class_basename($this->argument('name'))));

            $this->call('make:migration', ['name' => "create_{$table}_table", '--create' => $table]);
        }


        $controllerName = $this->getNameInput().'Controller';
        $controllerName = $this->parseControllerName($controllerName);
        //make Controller getControllerStub
        if ($this->files->exists($path = $this->getControllerPath($controllerName)))
        {
            return $this->error($this->type.' already exists!');
        }
        $this->makeDirectory($path);
        $this->files->put($path, $this->buildController($controllerName));
        $this->info($this->type.' controller created successfully.');

        $this->info("Route::resource('".strtolower($this->getNameInput())."', '".str_replace("App\Http\Controllers\\" , "" , $controllerName)."');");
    }

    protected function parseModelName($name)
    {
        $rootNamespace = $this->getUniversalAppNamespace();
        if (starts_with($name, $rootNamespace))
        {
            return $name;
        }
        if (str_contains($name, '/'))
        {
            $name = str_replace('/', '\\', $name);
        }
        return $this->parseName($this->getModelNamespace(trim($rootNamespace, '\\')).$name);
    }


    /**
     * Get the application namespace from the Composer file.
     *
     * @return string
     *
     * @throws \RuntimeException
     */
    protected function getUniversalAppNamespace()
    {
        $composer = json_decode(file_get_contents(base_path().'/composer.json'), true);

        foreach ((array) data_get($composer, 'autoload.psr-4') as $namespace => $path)
        {
            foreach ((array) $path as $pathChoice)
            {
                if (realpath(app_path()) == realpath(base_path().'/'.$pathChoice)) return $namespace;
            }
        }

        throw new RuntimeException("Unable to detect application namespace.");
    }



    /**
     * Replace the namespace for the given stub.
     *
     * @param  string  $stub
     * @param  string  $name
     * @return $this
     */
    protected function replaceNamespace(&$stub, $name)
    {
        $stub = str_replace(
            '{{namespace}}', $this->getNamespace($name), $stub
        );

        $stub = str_replace(
            '{{rootNamespace}}', $this->getAppNamespace(), $stub
        );

        return $this;
    }


    protected function parseControllerName($name)
    {
        $rootNamespace = $this->getUniversalAppNamespace();
        if (starts_with($name, $rootNamespace))
        {
            return $name;
        }
        if (str_contains($name, '/'))
        {
            $name = str_replace('/', '\\', $name);
        }
        return $this->parseName($this->getControllerNamespace(trim($rootNamespace, '\\')).'\\'.$name);
    }

    protected function getStub(){
        return $this->getModelStub();
    }

    protected function getModelStub()
    {
        return __DIR__.'/stubs/crud_mode.stub';
    }


    protected function getControllerStub()
    {
        return __DIR__.'/stubs/crud_controller.stub';
    }

    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    protected function getModelNamespace()
    {
        return $this->getUniversalAppNamespace().'Model\\'.ucfirst(Config::get('app.name')).'\\';
    }

        protected function getControllerNamespace()
    {
        return $this->getUniversalAppNamespace().'Http\Controllers\\'.ucfirst(Config::get('app.name'));
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return array(
            array('no-migration', null, InputOption::VALUE_NONE, 'Do not create a new migration file.'),
        );
    }

    protected function buildModel($name)
    {
        $stub = $this->files->get($this->getModelStub());
        return $this->replaceNamespace($stub, $name)->replaceTable($stub, $name);
    }


    protected function buildController($name)
    {
        $stub = $this->files->get($this->getControllerStub());
        return $this->replaceNamespace($stub, $name)->replaceModel($stub, $name);
    }

    protected function replaceTable($stub, $name)
    {
        $stub = parent::replaceClass($stub, $name);
        return str_replace('{{table}}', str_plural(snake_case(class_basename($this->argument('name')))), $stub);
    }

    protected function replaceModel($stub, $name){
        $stub = parent::replaceClass($stub, $name);
        return str_replace('{{model}}', $this->parseModelName($this->argument('name')), $stub); 
    }


    protected function getModelPath($name)
    {
        $name = str_replace($this->getUniversalAppNamespace(), '', $name);
        return $this->laravel['path'].'/'.str_replace('\\', '/', $name).'.php';
    }


    protected function getControllerPath($name){
        $name = str_replace($this->getUniversalAppNamespace(), '', $name);
        return $this->laravel['path'].'/'.str_replace('\\', '/', $name).'.php';
    }


    /**
     * Get the desired class name from the input.
     *
     * @return string
     */
    protected function getNameInput()
    {
        return $this->argument('name');
    }
    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return array(
            array('name', InputArgument::REQUIRED, 'The name of the class'),
        );
    }

}