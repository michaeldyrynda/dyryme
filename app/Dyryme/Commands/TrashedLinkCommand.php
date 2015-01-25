<?php namespace Dyryme\Commands;

use Dyryme\Repositories\LinkRepository;
use Indatus\Dispatcher\Scheduling\ScheduledCommand;
use Indatus\Dispatcher\Scheduling\Schedulable;
use Indatus\Dispatcher\Drivers\Cron\Scheduler;

class TrashedLinkCommand extends ScheduledCommand {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'dyryme:clear-trashed';

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
		$links = $this->linkRepository->getStaleTrashed();

		if ( ! $links->isEmpty() )
		{
			$this->info(sprintf('Purging %d trashed links', $links->count()));

			foreach ($links as $link)
			{
				\Event::fire('links.forceDeleting', [ $link, ]);

				$link->forceDelete();
			}
		}
		else
		{
			$this->info('No trashed links to clear at this time');
		}
	}


	/**
	 * @param Schedulable $scheduler
	 *
	 * @return mixed
	 */
	public function schedule(Schedulable $scheduler)
	{
		return $scheduler->daily()->hours(0)->minutes(30);
	}


}
