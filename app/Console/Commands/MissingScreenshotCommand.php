<?php namespace Dyryme\Console\Commands;

use DB;
use Dyryme\Queues\ScreenshotHandler;
use Dyryme\Repositories\LinkRepository;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class MissingScreenshotCommand extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'dyryme:missing-screenshots';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Command description.';

	/**
	 * @var LinkRepository
	 */
	private $linkRepository;

	/**
	 * @var ScreenshotHandler
	 */
	private $screenshotHandler;


	/**
	 * Create a new command instance.
	 *
	 * @param LinkRepository    $linkRepository
	 * @param ScreenshotHandler $screenshotHandler
	 */
	public function __construct(LinkRepository $linkRepository, ScreenshotHandler $screenshotHandler)
	{
		parent::__construct();

		$this->linkRepository    = $linkRepository;
		$this->screenshotHandler = $screenshotHandler;
	}


	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
		$links = $this->linkRepository->missingScreenshots($this->option('number'));

		foreach ($links as $link)
		{
			try
			{
				dd($this->screenshotHandler->makeScreenshot([ 'id' => $link->id, ]));
			}
			catch (\Exception $e)
			{
				dd($e->getMessage());
			}
		}
	}


	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getOptions()
	{
		return [
			[ 'number', null, InputOption::VALUE_OPTIONAL, 'Limit the number of missing screenshots processed.' ],
		];
	}


}
