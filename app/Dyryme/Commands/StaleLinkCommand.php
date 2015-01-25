<?php namespace Dyryme\Commands;

use Dyryme\Repositories\LinkRepository;
use Indatus\Dispatcher\Scheduling\ScheduledCommand;
use Indatus\Dispatcher\Scheduling\Schedulable;
use Indatus\Dispatcher\Drivers\Cron\Scheduler;

class StaleLinkCommand extends ScheduledCommand {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'dyryme:clear-stale';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Soft delete all links that have had no hits in 7 days';

	/**
	 * @var LinkRepository
	 */
	private $linkRepository;


	/**
	 * Create a new command instance.
	 *
	 * @param LinkRepository $linkRepository
	 */
	public function __construct(LinkRepository $linkRepository)
	{
		parent::__construct();

		$this->linkRepository = $linkRepository;
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
		$links = $this->linkRepository->getStale();

		if ( ! $links->isEmpty() )
		{
			$this->info(sprintf('Trashing %d stale links', $links->count()));

			foreach ($links as $link)
			{
				$link->delete();
			}
		}
		else
		{
			$this->info('No stale links to trash at this time');
		}
	}


	/**
	 * @param Schedulable $scheduler
	 *
	 * @return mixed
	 */
	public function schedule(Schedulable $scheduler)
	{
		return $scheduler->daily()->hours(0)->minutes(5);
	}


}
