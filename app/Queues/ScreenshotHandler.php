<?php namespace Dyryme\Queues;

use Dyryme\Commands\Command;
use Dyryme\Repositories\LinkRepository;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ServerException;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldBeQueued;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Intervention\Image\Facades\Image;
use Screenshot;

/**
 * Screenshot queue handler
 *
 * @package    Dyryme
 * @subpackage Queues
 * @copyright  2015 IATSTUTI
 * @author     Michael Dyrynda <michael@iatstuti.net>
 */
class ScreenshotHandler extends Command implements SelfHandling, ShouldBeQueued {

	use InteractsWithQueue, SerializesModels;

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
			list( $link, $local_path, $thumbnail ) = $this->makeScreenshot($data);

			if ( $link->fill([ 'screenshot' => $local_path, 'thumbnail' => $thumbnail, ])->save() )
			{
				$job->delete();

				return true;
			}

			$job->release();

			return false;
		}
		catch (ClientException $e)
		{
			//	Couldn't hit screeenly API, try again later
			$job->release();
		}
		catch (ModelNotFoundException $e)
		{
			$job->delete();

			return true;
		}
		catch (ServerException $e)
		{
			$job->release();

			return false;
		}
	}


	/**
	 * @param  string $link
	 *
	 * @return mixed
	 */
	private function getScreenshot($link)
	{
		$screenshot = Screenshot::capture($link);

		return $screenshot->store(storage_path() . '/screenshots/', md5($link) . '.jpg');
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


	/**
	 * @param $data
	 *
	 * @return array
	 */
	public function makeScreenshot($data)
	{
		$link = $this->linkRepository->lookupById($data['id']);

		$local_path = $this->cropScreenshot($this->getScreenshot($link->url));
		$thumbnail  = $this->makeThumbnail($local_path);

		return array( $link, $local_path, $thumbnail );
	}

}
