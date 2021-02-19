<?php namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class ResolveTransferidosCommand extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'correcao:transferidos-status';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Command description.';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
		$attends = Attend::whereStatus('T')->groupBy('user_id')->get();

		foreach($attends as $attend) {
			echo $attend->status;
			$offer = $attend->getUnit()->getOffer();
			$class = Classe::find($offer->class_id);
			$offers = Offer::where('class_id',$class->id)->get();
			$units = [];
			foreach($offers as $offer) {
				$units = array_merge($units, array_pluck($offer->units, 'id'));
			}
			$attends_change = Attend::whereIn('unit_id', $units)->where('user_id', $attend->user_id)->get();
			foreach($attends_change as $attend_change) {
				echo "CHANGE: User: $attend->user_id | Attend: $attend_change->id | Status: $attend_change->status | To: 'T'\n";
				if($attend_change->status == 'M') {
					$attend_change->status = 'T';
					$attend_change->save();
				}
			}
		}
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

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions()
	{
		return array(
			array('example', null, InputOption::VALUE_OPTIONAL, 'An example option.', null),
		);
	}

}
