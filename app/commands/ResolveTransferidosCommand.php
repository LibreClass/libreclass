<?php

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
		$attends = Attend::whereStatus('T')->groupBy('idUser')->get();
		// $attends = Attend::whereStatus('T')->where('idUser', 5358)->groupBy('idUser')->get();

		foreach($attends as $attend) {
			echo $attend->status;
			$offer = $attend->getUnit()->getOffer();
			$class = Classe::find($offer->idClass);
			$offers = Offer::where('idClass',$class->id)->get();
			$units = [];
			foreach($offers as $offer) {
				$units = array_merge($units, array_pluck($offer->units, 'id'));
			}
			// $offer->units = array_pluck($offer->units, 'id');
			// echo "$attend->id | $attend->idUser | $offer->id\n";
			$attends_change = Attend::whereIn('idUnit', $units)->where('idUser', $attend->idUser)->get();
			foreach($attends_change as $attend_change) {
				echo "CHANGE: User: $attend->idUser | Attend: $attend_change->id | Status: $attend_change->status | To: 'T'\n";
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
