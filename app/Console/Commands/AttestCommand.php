<?php namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class AttestCommand extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'update:attests';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = "Update all students' frequencies which has certificate.";

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
    DB::update("UPDATE Units, Lessons, Frequencies
                  SET Frequencies.value='F'
                  WHERE Units.id=Lessons.unit_id
                    AND Lessons.id=Frequencies.lesson_id
                    AND Units.status='E'
                    AND Frequencies.value='A'");

		DB::update("UPDATE Attests, Courses, Periods, Classes, Offers, Units, Lessons, Frequencies, Attends
                  SET Frequencies.value='A'
                  WHERE Attests.institution_id=Courses.institution_id
                    AND Courses.id=Periods.course_id
                    AND Periods.id=Classes.period_id
                    AND Classes.id=Offers.class_id
                    AND Offers.id=Units.offer_id
                    AND Units.id=Lessons.unit_id
                    AND Lessons.id=Frequencies.lesson_id
                    AND Frequencies.attend_id=Attends.id
                    AND Attends.user_id=Attests.student_id
                    AND Units.status='E'
                    AND Frequencies.value='F'
                    AND Lessons.date BETWEEN Attests.date AND ADDDATE(Attests.date, Attests.days-1)");
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
