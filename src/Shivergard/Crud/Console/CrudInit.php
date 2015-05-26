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
	protected $description = 'Initalize crud basic Model/Controller/Views';



	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
		if ($this->files->exists($this->laravel['path'].'/BaseModel.php'))
		{
			return $this->error('Model already exists!');
		}
		$this->files->put($this->laravel['path'].'/BaseModel.php', $this->files->get($this->getModelStub()));


		if ($this->files->exists($this->laravel['path'].'/Http/Controllers/CrudController.php'))
		{
			return $this->error('Model already exists!');
		}
		$this->files->put($this->laravel['path'].'/Http/Controllers/CrudController.php', $this->files->get($this->getControllerStub()));
		

		);
		$this->files->copyDirectory(__DIR__.'/stubs/crud' , base_path().'/resources/views/');

		
	}


	protected function getModelStub()
	{
		return __DIR__.'/stubs/base_model.stub';
	}


	protected function getStub(){
		return __DIR__.'/stubs/';
	}

	protected function getControllerStub()
	{
		return __DIR__.'/stubs/base_controller.stub';
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