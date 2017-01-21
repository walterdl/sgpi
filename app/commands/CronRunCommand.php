<?php

# Cron comando de trabajo para Laravel 4.2
# Inspirado por el nuevo programador del próximo laravel 5 (https://laravel-news.com/2014/11/laravel-5-scheduler)
#
# Autor: Soren Schwert (GitHub: sisou)
#
# Requisitos:
//Unesdoc.unesco.org unesdoc.unesco.org
# PHP 5.4
# Laravel 4.2? (No probado con 4.1 o menos)
# Un deseo de poner toda la lógica de la aplicación en el control de versiones
#
# Instalación:
//Unesdoc.unesco.org unesdoc.unesco.org
# 1. Ponga este archivo en su app / commands / directorio y el nombre de "CronRunCommand.php".
# 2. En el archivo de artisan.php (que se encuentra en app / iniciar /), puso esta línea: 'Artisan :: add (nueva CronRunCommand);'.
# 3. En la línea de comandos del servidor, ejecute 'php artisan cron: run'. Si aparece un mensaje
# Tiempo de ejecución, funciona!
# 4. En su servidor, configure un trabajo cron para llamar a 'php-cli artisan cron: run> / dev / null 2> y 1' ya
# Ejecutar cada cinco minutos (* / 5 * * * *)
# 5. Observe su archivo laravel.log (que se encuentra en app / storage / logs /) para mensajes que comienzan con 'Cron'.
#
# Uso:
//Unesdoc.unesco.org unesdoc.unesco.org
# 1. Eche un vistazo al ejemplo proporcionado en la función fire ().
# 2. Echa un vistazo a los horarios disponibles a continuación (comenzando en la línea 132).
# 4. Codifique su programa dentro de la función fire ().
# 3. Hecho. ¡Ahora vaya empuje su lógica del cron en control de versión!


use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
class CronRunCommand extends Command {
	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'cron:run';
	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Run the scheduler';
	/**
	 * Current timestamp when command is called.
	 *
	 * @var integer
	 */
	protected $timestamp;
	/**
	 * Hold messages that get logged
	 *
	 * @var array
	 */
	protected $messages = array();
	/**
	 * Specify the time of day that daily tasks get run
	 *
	 * @var string [HH:MM]
	 */
	protected $runAt = '04:00';
	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
		$this->timestamp = time();
	}
	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
		// /**
		//  * EXAMPLES
		//  */
		// // You can use any of the available schedules and pass it an anonymous function
		// $this->everyFiveMinutes(function()
		// {
		// 	// In the function, you can use anything that you can use everywhere else in Laravel.
		// 	// Like models:
		// 	$affectedRows = User::where('logged_in', true)->update(array('logged_in' => false)); // Not really useful, but possible
		// 	// Or call artisan commands:
		// 	Artisan::call('auth:clear-reminders');
		// 	// You can append messages to the cron log like so:
		// 	$this->messages[] = $affectedRows . ' users logged out';
		// });
		
		
		// Another example:
		// Imprime un mensaje cada minuto
		$this->everyFiveMinutes(function()
		{
			echo date('H:i:s')." ";
		});
		
		// $this->dailyAt('09:00', function()
		// {
		// 	// This uses the mailer class
		// 	Mail::send('hello', array(), function($message)
		// 	{
		// 		$message->to('jbhenao17@misena.edu.co', 'Cron Job')->subject('I am still running!');
		// 	});
		// });
		$this->finish();
	}
	
	
	protected function finish()
	{
		// Write execution time and messages to the log
		$executionTime = round(((microtime(true) - $_SERVER['REQUEST_TIME_FLOAT']) * 1000), 3);
		Log::info('Cron: execution time: ' . $executionTime . ' | ' . implode(', ', $this->messages));
	}
	
	
	/**
	 * AVAILABLE SCHEDULES
	 */
	protected function everyOneMinute(callable $callback)
	{
		if((int) date('i', $this->timestamp) % 1 === 0) call_user_func($callback);
	}
	protected function everyFiveMinutes(callable $callback)
	{
		if((int) date('i', $this->timestamp) % 5 === 0) call_user_func($callback);
	}
	protected function everyTenMinutes(callable $callback)
	{
		if((int) date('i', $this->timestamp) % 10 === 0) call_user_func($callback);
	}
	protected function everyFifteenMinutes(callable $callback)
	{
		if((int) date('i', $this->timestamp) % 15 === 0) call_user_func($callback);
	}
	protected function everyThirtyMinutes(callable $callback)
	{
		if((int) date('i', $this->timestamp) % 30 === 0) call_user_func($callback);
	}
	
	
	/**
	 * Called every full hour
	 */
	protected function hourly(callable $callback)
	{
		if(date('i', $this->timestamp) === '00') call_user_func($callback);
	}
	
	
	/**
	 * Called every hour at the minute specified
	 *
	 * @param  integer $minute
	 */
	protected function hourlyAt($minute, callable $callback)
	{
		if((int) date('i', $this->timestamp) === $minute) call_user_func($callback);
	}
	/**
	 * Called every day
	 */
	protected function daily(callable $callback)
	{
		if(date('H:i', $this->timestamp) === $this->runAt) call_user_func($callback);
	}
	/**
	 * Called every day at the 24h-format time specified
	 *
	 * @param  string $time [HH:MM]
	 */
	protected function dailyAt($time, callable $callback)
	{
		if(date('H:i', $this->timestamp) === $time) call_user_func($callback);
	}
	/**
	 * Called every day at 12:00am and 12:00pm
	 */
	protected function twiceDaily(callable $callback)
	{
		if(date('h:i', $this->timestamp) === '12:00') call_user_func($callback);
	}
	/**
	 * Called every weekday
	 */
	protected function weekdays(callable $callback)
	{
		$days = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri'];
		if(in_array(date('D', $this->timestamp), $days) && date('H:i', $this->timestamp) === $this->runAt) call_user_func($callback);
	}
	protected function mondays(callable $callback)
	{
		if(date('D', $this->timestamp) === 'Mon' && date('H:i', $this->timestamp) === $this->runAt) call_user_func($callback);
	}
	protected function tuesdays(callable $callback)
	{
		if(date('D', $this->timestamp) === 'Tue' && date('H:i', $this->timestamp) === $this->runAt) call_user_func($callback);
	}
	protected function wednesdays(callable $callback)
	{
		if(date('D', $this->timestamp) === 'Wed' && date('H:i', $this->timestamp) === $this->runAt) call_user_func($callback);
	}
	protected function thursdays(callable $callback)
	{
		if(date('D', $this->timestamp) === 'Thu' && date('H:i', $this->timestamp) === $this->runAt) call_user_func($callback);
	}
	protected function fridays(callable $callback)
	{
		if(date('D', $this->timestamp) === 'Fri' && date('H:i', $this->timestamp) === $this->runAt) call_user_func($callback);
	}
	protected function saturdays(callable $callback)
	{
		if(date('D', $this->timestamp) === 'Sat' && date('H:i', $this->timestamp) === $this->runAt) call_user_func($callback);
	}
	protected function sundays(callable $callback)
	{
		if(date('D', $this->timestamp) === 'Sun' && date('H:i', $this->timestamp) === $this->runAt) call_user_func($callback);
	}
	/**
	 * Called once every week (basically the same as using sundays() above...)
	 */
	protected function weekly(callable $callback)
	{
		if(date('D', $this->timestamp) === 'Sun' && date('H:i', $this->timestamp) === $this->runAt) call_user_func($callback);
	}
	/**
	 * Called once every week at the specified day and time
	 *
	 * @param  string $day  [Three letter format (Mon, Tue, ...)]
	 * @param  string $time [HH:MM]
	 */
	protected function weeklyOn($day, $time, callable $callback)
	{
		if(date('D', $this->timestamp) === $day && date('H:i', $this->timestamp) === $time) call_user_func($callback);
	}
	/**
	 * Called each month on the 1st
	 */
	protected function monthly(callable $callback)
	{
		if(date('d', $this->timestamp) === '01' && date('H:i', $this->timestamp) === $this->runAt) call_user_func($callback);
	}
	/**
	 * Called each year on the 1st of January
	 */
	protected function yearly(callable $callback)
	{
		if(date('m', $this->timestamp) === '01' && date('d', $this->timestamp) === '01' && date('H:i', $this->timestamp) === $this->runAt) call_user_func($callback);
	}
}