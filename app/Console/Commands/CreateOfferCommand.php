<?php namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class CreateOfferCommand extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'create:offer';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = '';

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
		$files = ['EMEI RECANTO DO SABER.csv', 'EMEF SANTA RITA DE CÁSSIA.csv', 'EMEF ANITA GARIBALDI.csv', 'APAE - ESCOLA REVIVER.csv',
		'EMEF JAMES JOHNSON.csv', 'EMEF MIGUEL COUTO.csv', 'EMEF OSVALDO CRUZ.csv', 'EMEI THEREZA FRANCESCHI.csv'];
		// $csv = utf8_encode(file_get_contents("EMEI RECANTO DO SABER.csv"));

		// $files = ['EMEI RECANTO DO SABER.csv', 'EMEI THEREZA FRANCESCHI.csv'];
		foreach ($files as $file) {
			var_dump($file);
			$csv = utf8_encode(file_get_contents($file));

			$csv = explode("\n", $csv);
			$cadastro_alunos = $cadastro_professores = false;
			$students = [];
			foreach ($csv as $line)
			{
				$cols = explode(";", $line);

				if ($cols[0] == '--FIM TURMA') {
					$students = [];
					$cadastro_alunos = $cadastro_professores = false;
				}
				elseif ($cols[0] == 'RM' || $cols[0] == 'Cód. Funcional'){
					continue;
				}
				elseif ($cols[0] == 'Escola'){//Institution (User)
					$institution = User::where('type', 'I')->where('name', $cols[1])->first();
				}
				elseif ($cols[0] == 'Tipo de Ensino') {//Course
					$course = Course::where('institution_id', $institution->id)->where('name', $cols[1])->first();
				}
				elseif (!empty($cols[2]) && $cols[2] == 'Sala de Aula') {
					$classroom = $cols[3];
				}
				elseif ($cols[0] == 'Ano') {//Period
					$period = Period::where('course_id', $course->id)->where('name', $cols[1])->first();
					$class = Classe::where('period_id', $period->id)->where('name', $cols[3])->first();

					switch ($cols[5]) {
						case 'MANHÃ':
						case 'Manhã':
							$day_period = 'M';
							break;
						case 'TARDE':
							$day_period = 'V';
							break;
						case 'NOITE':
							$day_period = 'N';
							break;
						default:
							$day_period = '-';
							break;
					}
				}
				elseif ($cols[0] == '[ALUNOS]') {
					$cadastro_alunos = true;
				}
				elseif ($cadastro_alunos) {
					if (strlen($cols[1])){
						$birthdate = !empty($cols[3]) ? date_create_from_format('d/m/Y', $cols[3])->format('Y-m-d') : null;
						$student = User::where('name', $cols[1])->where('type','N')->where('birthdate', $birthdate)->first();
						$students[] = $student;
					}else {//Linha em branco: acabou a listagem de alunos desta turma.
						$cadastro_alunos = false;
					}
				}
				elseif ($cols[0] == '[PROFESSORES]') {
					$cadastro_professores = true;
				}
				elseif ($cadastro_professores) {
					if (!strlen($cols[1]) && !strlen($cols[2])) {
						$cadastro_professores = false;
					}
					else{
						if (strlen($cols[1])){
							$professor = User::where('name', $cols[1])->whereIn('type', ['P', 'M'])->first();

							if (!strlen($cols[2])){//Disciplinas
								if (!$discipline = Discipline::where('name', $period->name)->where('period_id', $period->id)->first()){
									$discipline = Discipline::create(['name'=>$period->name, 'period_id'=>$period->id]);
								}
								if (!$offer = Offer::where('class_id', $class->id)->where('discipline_id', $discipline->id)->first()){
									$offer = Offer::create(['class_id'=>$class->id, 'discipline_id'=>$discipline->id, 'classroom'=>$classroom, 'day_period'=>$day_period]);
									$unit = Unit::create(['offer_id'=>$offer->id]);
									foreach ($students as $student) {
										Attend::create(['user_id'=>$student->id, 'unit_id'=>$unit->id]);
									}
								}

								Lecture::create(['user_id'=>$professor->id, 'offer_id'=>$offer->id]);
							}
						}
					}
				}
			}
		}
		return;
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return [
			['name'],
		];
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
