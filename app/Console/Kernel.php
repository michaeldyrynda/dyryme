<?php namespace Dyryme\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel {

	/**
	 * The Artisan commands provided by your application.
	 *
	 * @var array
	 */
	protected $commands = [
		'Dyryme\Console\Commands\Inspire',
        'Dyryme\Console\Commands\StaleLinkCommand',
        'Dyryme\Console\Commands\TrashedLinkCommand',
		'Dyryme\Console\Commands\MissingScreenshotCommand',
	];

	/**
	 * Define the application's command schedule.
	 *
	 * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
	 * @return void
	 */
	protected function schedule(Schedule $schedule)
	{
		$schedule->command('inspire')
				 ->hourly();

        $schedule->command('dyryme:clear-stale')->dailyAt('00:05');
        $schedule->command('dyryme:clear-trashed')->dailyAt('00:30');
	}

}
