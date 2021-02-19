<?php namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class ImportCommand extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'import';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Importação de dados a partir de csv.';

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

		$files = ['EMEF OSVALDO CRUZ.csv', 'APAE - ESCOLA REVIVER.csv'];
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
					if (!$course = Course::where('institution_id', $institution->id)->where('name', $cols[1])->first()){
						$course = Course::create(['institution_id'=>$institution->id, 'name'=>$cols[1]]);
					}
				}
				elseif (!empty($cols[2]) && $cols[2] == 'Sala de Aula') {
					$classroom = $cols[3];
				}
				elseif ($cols[0] == 'Ano') {//Period
					if (!$period = Period::where('course_id', $course->id)->where('name', $cols[1])->first()){
						$period = Period::create(['course_id'=>$course->id, 'name'=>$cols[1]]);
					}
					if (!$class = Classe::where('period_id', $period->id)->where('name', $cols[3])->first()){
						$class = Classe::create(['period_id'=>$period->id, 'name'=>$cols[3], 'class'=>'2017']);
					}
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
						$gender = !empty($cols[2]) ? $cols[2][0] : '-';//Primeira letra do sexo (alguns arquivos trazem 'Masculino/Feminino')
						$student = User::create(['name'=>$cols[1], 'type'=>'N', 'enrollment'=>$cols[0], 'cadastre'=>'N', 'gender'=>$gender,
						'birthdate'=>$birthdate]);
						Relationship::create(['user_id'=>$institution->id, 'friend_id'=>$student->id, 'type'=>'1']);
						$students[] = $student;
					}else {//Linha em branco: acabou a listagem de alunos desta turma.
						$cadastro_alunos = false;
					}
				}
				elseif ($cols[0] == '[PROFESSORES]') {
					$cadastro_professores = true;
				}
				elseif ($cadastro_professores) {
					if (strlen($cols[1])){
						if(!$professor = User::where('name', $cols[1])->whereIn('type', ['P', 'M'])->first()){
							$professor = User::create(['name'=>$cols[1], 'type'=>'M', 'cadastre'=>'N']);
						}
						if(!Relationship::where('user_id', $institution->id)->where('friend_id', $professor->id)->count()){
							Relationship::create(['user_id'=>$institution->id, 'friend_id'=>$professor->id, 'type'=>'2']);
						}
					}
					if (strlen($cols[2])){//Disciplinas
						if (!$discipline = Discipline::where('name', $cols[2])->where('period_id', $period->id)->first()){
							$discipline = Discipline::create(['name'=>$cols[2], 'period_id'=>$period->id]);
						}
						if (!$offer = Offer::where('class_id', $class->id)->where('discipline_id', $discipline->id)->first()){
							$offer = Offer::create(['class_id'=>$class->id, 'discipline_id'=>$discipline->id, 'classroom'=>$classroom, 'day_period'=>$day_period]);
							$unit = Unit::create(['offer_id'=>$offer->id]);
							foreach ($students as $student) {
								Attend::create(['user_id'=>$student->id, 'unit_id'=>$unit->id]);
							}
						}
						if (strlen($cols[1])){
							Lecture::create(['user_id'=>$professor->id, 'offer_id'=>$offer->id]);
						}
					}
					if (!strlen($cols[1]) && !strlen($cols[2])) {
						$cadastro_professores = false;
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
