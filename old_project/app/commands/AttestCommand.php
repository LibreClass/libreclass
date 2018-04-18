<?php

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
                  WHERE Units.id=Lessons.idUnit
                    AND Lessons.id=Frequencies.idLesson
                    AND Units.status='E'
                    AND Frequencies.value='A'");

		DB::update("UPDATE Attests, Courses, Periods, Classes, Offers, Units, Lessons, Frequencies, Attends
                  SET Frequencies.value='A'
                  WHERE Attests.idInstitution=Courses.idInstitution
                    AND Courses.id=Periods.idCourse
                    AND Periods.id=Classes.idPeriod
                    AND Classes.id=Offers.idClass
                    AND Offers.id=Units.idOffer
                    AND Units.id=Lessons.idUnit
                    AND Lessons.id=Frequencies.idLesson
                    AND Frequencies.idAttend=Attends.id
                    AND Attends.idUser=Attests.idStudent
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
