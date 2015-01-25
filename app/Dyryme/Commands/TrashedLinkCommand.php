<?php namespace Dyryme\Commands;

use Dyryme\Repositories\LinkRepository;
use Illuminate\Console\Command;

class TrashedLinkCommand extends Command {

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


}
