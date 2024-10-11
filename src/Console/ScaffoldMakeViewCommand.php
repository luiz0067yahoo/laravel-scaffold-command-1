<?php

namespace Scaffolding\Console;

use Illuminate\Console\GeneratorCommand;
use InvalidArgumentException;
use Illuminate\Support\Str;
use Scaffolding\Traits\UseField;
use Symfony\Component\Console\Input\InputOption;


class ScaffoldMakeViewCommand extends GeneratorCommand
{
    use UseField;
    /**
     * The console command name.
     *
     * @var string
     */

    protected $signature = 'scaffold:view {name : The name of entity}
                                        {--field=* : Generate columns with this option (e.x. name:string)}
                                        {--fields= : Generate columns with this option (e.x. name:string,email:string)}
                                        {--remove : drop files Views}
                                        {--file : The name of file}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scaffold a new view (blade file)';

    /**
     * The type of file being generated.
     *
     * @var string
     */
    protected $type = 'Blade file';

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
		$file = strtolower($this->option('file'));
		$name = strtolower($this->argument('name'));

		$this->files->ensureDirectoryExists($this->viewPath($name));

		if (!$this->files->exists($this->viewPath($name.'/'.$file.'.blade.php'))) {
			$this->files->put(
                $this->viewPath($name.DIRECTORY_SEPARATOR.$file.'.blade.php'), ''
            );
		}

		return $this->resolveStubPath("/stubs/scaffold.{$file}.stub");
    }

    /**
     * Build the class with the given name.
     *
     * Remove the base controller import if we are already in the base namespace.
     *
     * @param  string  $name
     * @return string
     */
    protected function populateViewStub($name, $stub, $fieldString)
    {

        $replace = [
            '{{ lowerModelPlural }}' => Str::lower(Str::plural($name)),
            '{{lowerModelPlural}}' => Str::plural($name),
            '{{ modelPlural }}' => Str::plural(lcfirst($name)),
            '{{modelPlural}}' => Str::plural(lcfirst($name)),
            '{{ model }}' => lcfirst($name),
            '{{model}}' => lcfirst($name),
            '{{ columns }}' => $fieldString,
            '{{columns}}' => $fieldString,
        ];

        return str_replace(
            array_keys($replace), array_values($replace), $stub
        );
    }

    /**
     * Get the path to the stubs.
     *
     * @return string
     */
    public function stubPath()
    {
        return __DIR__.'/stubs';
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $file = $this->option('file');
        $name = $this->argument('name');

		$stub = $this->getStub();
        $path = $this->getViewPath($name, $file);

        $this->info(json_encode($this->option('fields')));
        $fieldString = "[".($this->getStringColumns($this->option('field'), $this->option('fields')))."]";
        $stub = $this->populateViewStub($name, $this->files->get($stub), $fieldString);

        $this->files->put(
            $path, $stub
        );

        $this->line("<info>view created:</info> {$file}");
    }

    /**
     * Get the destination class path.
     *
     * @param  string  $name
     * @return string
     */
    protected function getViewPath($name, $fileName)
    {
		$name = strtolower($name);
        return $this->viewPath($name.DIRECTORY_SEPARATOR.$fileName).'.blade.php';
    }

    /**
     * Resolve the fully-qualified path to the stub.
     *
     * @param  string  $stub
     * @return string
     */
    protected function resolveStubPath($stub)
    {
        return file_exists($customPath = $this->laravel->basePath(trim($stub, '/')))
                        ? $customPath
                        : __DIR__.$stub;
    }
}
