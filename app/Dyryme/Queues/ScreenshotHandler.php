<?php namespace Dyryme\Queues;

use Dyryme\Repositories\LinkRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Queue\Jobs\Job;
use Intervention\Image\Facades\Image;

/**
 * Screenshot queue handler
 *
 * @package    Dyryme
 * @subpackage Queues
 * @copyright  2015 IATSTUTI
 * @author     Michael Dyrynda <michael@iatstuti.net>
 */
class ScreenshotHandler {

	/**
	 * @var LinkRepository
	 */
	private $linkRepository;


	/**
	 * @param LinkRepository $linkRepository
	 */
	public function __construct(LinkRepository $linkRepository)
	{
		$this->linkRepository = $linkRepository;
	}


	/**
	 * @param Job $job
	 * @param     $data
	 *
	 * @return bool
	 */
	public function fire(Job $job, $data)
	{
		if ( $job->attempts() > 3 )
		{
			// Don't worry about it, then
			$job->delete();

			return true;
		}

		try
		{
			$link = $this->linkRepository->lookupById($data['id']);

			$local_path = $this->cropScreenshot($this->getScreenshot($link->url));
			$thumbnail  = $this->makeThumbnail($local_path);

			if ( $link->fill([ 'screenshot' => $local_path, 'thumbnail' => $thumbnail, ])->save() )
			{
				$job->delete();

				return true;
			}

			$job->release();

			return false;
		}
		catch (ModelNotFoundException $e)
		{
			$job->delete();

			return true;
		}
	}


	/**
	 * @param  string $link
	 *
	 * @return mixed
	 */
	private function getScreenshot($link)
	{
		\Screenshot::capture($link);

		return \Screenshot::store(storage_path() . '/screenshots/');
	}


	/**
	 * Resize and crop the screenshot
	 *
	 * @param  string $path
	 *
	 * @return mixed
	 */
	private function cropScreenshot($path)
	{
		$image = Image::make($path);

		$image->resize(1024, null, function ($constraint)
		{
			$constraint->aspectRatio();
		});

		$image->crop(1024, 768, 0, 0);
		$image->save($path);

		return $path;
	}


	/**
	 * @param  string $path
	 *
	 * @return string
	 */
	private function makeThumbnail($path)
	{
		$image = Image::make($path);

		$image->resize(100, null, function ($constraint)
		{
			$constraint->aspectRatio();
		});

		$info = pathinfo($path);
		$path = sprintf('%s/screenshots/%s_%s_%s.%s', storage_path(), $info['filename'], $image->width(),
			$image->height(), $info['extension']);

		$image->save($path);

		return $path;
	}

}
