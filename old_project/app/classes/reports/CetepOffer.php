<?php namespace reports\cetep;

class Offer extends \FPDF {

  /**
   * Variável necessária método de imprimir em ângulo predefinido
   * @var type float
   */
  public $angle;

  /**
   * Verifica se as informações de cabeçalho
   * @var type boolean
   */
  private $headerStatus;

  /**
   * Contém todas as informações de uma turma
   * @var type object
   */
  private $classInfo;

  /**
   * Contém as datas dos exames/provas
   * @var type array
   */
  private $examsDate;

  /**
   * Constructor para inicialização da classe
   * @param array $examsDate - Contém as datas das provas
   */
  public function __construct($classInfo = [], $examsDate = []) {
    $orientation='L';
    $unit='cm';
    $size='A4';
    parent::__construct($orientation, $unit, $size);
    $this->headerStatus = true;
    $this->setClassInfo($classInfo);
    $this->setExamsDate($examsDate);
  }

  public function Header()
  {
		$this->Image(public_path() . '/images/logolibreclass.png', 0, 0, 29.7, 21, 'PNG');
		$this->SetXY(1, 0.8);
    $this->SetFont('Times', '', 12);
    // $this->Cell(0, 0.5, 'GOVERNO DO ESTADO DA BAHIA', 0, 1, 'C');
    $this->SetFont('Times', '', 10);
    // $this->Cell(0, 0.5, 'SECRETARIA DA EDUCAÇÃO DO ESTADO DA BAHIA', 0, 1, 'C');
    $this->SetFont('Times', '', 8);
    $this->headerInfo();
  }

  public function headerInfo()
  {
    if ($this->headerStatus) {
      $this->SetXY(0.36, 1);
      $this->Cell(0, 0, $this->classInfo["escola"], 0, 1, '');
      $this->SetXY(19.3, 1.1);
      $this->Cell(0, 0, $this->classInfo["codigo_da_uee"], 0, 1, '');
      $this->SetXY(0.36, 1.6);
      $this->Cell(0, 0, $this->classInfo["endereco"], 0, 1, '');
      $this->SetXY(0.36, 2.1);
      $this->Cell(0, 0, $this->classInfo["periodo_letivo"], 0, 1, '');
      $this->SetXY(0.36, 2.7); //12.98 > PRÓX
      $this->Cell(0, 0, $this->classInfo["tipo_de_ensino"], 0, 1, '');
      $this->SetXY(9.98, 2.7); //19,3 > PRÓX
      $this->Cell(0, 0, $this->classInfo["modalidade"], 0, 1, '');
      $this->SetXY(19.3, 2.7);
      $this->Cell(0, 0, $this->classInfo["submodalidade"], 0, 1, '');
      $this->SetXY(0.36, 3.3);
      $this->Cell(0, 0, $this->classInfo["serie"], 0, 1, '');
      $this->SetXY(9.98, 3.3);
      $this->Cell(0, 0, $this->classInfo["turma"], 0, 1, '');
      $this->SetXY(19.3, 3.3);
      $this->Cell(0, 0, $this->classInfo["periodo_dia"], 0, 1, '');

      $this->SetFont('Times', '', 8);

      $this->SetXY(0.36, 3.7);
      $this->Cell(14.04, 0.5, $this->classInfo["disciplina"], 1, 0, '');
      $this->Cell(14.92, 0.5, $this->classInfo["professor"], 1, 1, '');

      $this->SetX(0.36);
      $this->Cell(14.04, 0.5, $this->classInfo["unidade"], 1, 0, '');
      $this->Cell(7.46, 0.5, 'Aulas Previstas: ', 1, 0, '');
      $this->Cell(7.46, 0.5, 'Aulas Dadas: '. $this->classInfo["toughtLessons"], 1, 1, '');

      $this->SetFont('Times', '', 8);
      $this->SetXY(0.36, 4.7);
      $this->Cell(0.6, 3.1, $this->RotatedText(0.75 , 7.7, 'Número' , 90), 1, 0, ''); // RotatedText (POSIÇÃO X, POSIÇÃO Y, 'TEXT', ÂNGULO)
      $this->Cell(5.6, 3.1, $this->RotatedText(3    , 7.7, 'ALUNOS' ,0), 1, 0, '');
      $this->Cell(0.4, 3.1, $this->RotatedText(6.84 , 7.7, 'Aula 1' , 90), 1, 0, '');
      $this->Cell(0.4, 3.1, $this->RotatedText(7.24 , 7.7, 'Aula 2' , 90), 1, 0, '');
      $this->Cell(0.4, 3.1, $this->RotatedText(7.64 , 7.7, 'Aula 3' , 90), 1, 0, '');
      $this->Cell(0.4, 3.1, $this->RotatedText(8.04 , 7.7, 'Aula 4' , 90), 1, 0, '');
      $this->Cell(0.4, 3.1, $this->RotatedText(8.44 , 7.7, 'Aula 5' , 90), 1, 0, '');
      $this->Cell(0.4, 3.1, $this->RotatedText(8.84 , 7.7, 'Aula 6' , 90), 1, 0, '');
      $this->Cell(0.4, 3.1, $this->RotatedText(9.24 , 7.7, 'Aula 7' , 90), 1, 0, '');
      $this->Cell(0.4, 3.1, $this->RotatedText(9.64 , 7.7, 'Aula 8' , 90), 1, 0, '');
      $this->Cell(0.4, 3.1, $this->RotatedText(10.04, 7.7, 'Aula 9' , 90), 1, 0, '');
      $this->Cell(0.4, 3.1, $this->RotatedText(10.44, 7.7, 'Aula 10', 90), 1, 0, '');
      $this->Cell(0.4, 3.1, $this->RotatedText(10.84, 7.7, 'Aula 11', 90), 1, 0, '');
      $this->Cell(0.4, 3.1, $this->RotatedText(11.24, 7.7, 'Aula 12', 90), 1, 0, '');
      $this->Cell(0.4, 3.1, $this->RotatedText(11.64, 7.7, 'Aula 13', 90), 1, 0, '');
      $this->Cell(0.4, 3.1, $this->RotatedText(12.04, 7.7, 'Aula 14', 90), 1, 0, '');
      $this->Cell(0.4, 3.1, $this->RotatedText(12.44, 7.7, 'Aula 15', 90), 1, 0, '');
      $this->Cell(0.4, 3.1, $this->RotatedText(12.84, 7.7, 'Aula 16', 90), 1, 0, '');
      $this->Cell(0.4, 3.1, $this->RotatedText(13.24, 7.7, 'Aula 17', 90), 1, 0, '');
      $this->Cell(0.4, 3.1, $this->RotatedText(13.64, 7.7, 'Aula 18', 90), 1, 0, '');
      $this->Cell(0.4, 3.1, $this->RotatedText(14.04, 7.7, 'Aula 19', 90), 1, 0, '');
      $this->Cell(0.4, 3.1, $this->RotatedText(14.44, 7.7, 'Aula 20', 90), 1, 0, '');
      $this->Cell(0.4, 3.1, $this->RotatedText(14.84, 7.7, 'Aula 21', 90), 1, 0, '');
      $this->Cell(0.4, 3.1, $this->RotatedText(15.24, 7.7, 'Aula 22', 90), 1, 0, '');
      $this->Cell(0.4, 3.1, $this->RotatedText(15.64, 7.7, 'Aula 23', 90), 1, 0, '');
      $this->Cell(0.4, 3.1, $this->RotatedText(16.04, 7.7, 'Aula 24', 90), 1, 0, '');
      $this->Cell(0.4, 3.1, $this->RotatedText(16.44, 7.7, 'Aula 25', 90), 1, 0, '');
      $this->Cell(0.4, 3.1, $this->RotatedText(16.84, 7.7, 'Aula 26', 90), 1, 0, '');
      $this->Cell(0.4, 3.1, $this->RotatedText(17.24, 7.7, 'Aula 27', 90), 1, 0, '');
      $this->Cell(0.4, 3.1, $this->RotatedText(17.64, 7.7, 'Aula 28', 90), 1, 0, '');
      $this->Cell(0.4, 3.1, $this->RotatedText(18.04, 7.7, 'Aula 29', 90), 1, 0, '');
      $this->Cell(0.4, 3.1, $this->RotatedText(18.44, 7.7, 'Aula 30', 90), 1, 0, '');
      $this->Cell(0.4, 3.1, $this->RotatedText(18.84, 7.7, 'Aula 31', 90), 1, 0, '');
      $this->Cell(0.4, 3.1, $this->RotatedText(19.24, 7.7, 'Aula 32', 90), 1, 0, '');
      $this->Cell(0.4, 3.1, $this->RotatedText(19.64, 7.7, 'Aula 33', 90), 1, 0, '');
      $this->Cell(0.4, 3.1, $this->RotatedText(20.04, 7.7, 'Aula 34', 90), 1, 0, '');
      $this->Cell(0.4, 3.1, $this->RotatedText(20.44, 7.7, 'Aula 35', 90), 1, 0, '');
      $this->Cell(0.4, 3.1, $this->RotatedText(20.84, 7.7, 'Aula 36', 90), 1, 0, '');
      $this->Cell(0.4, 3.1, $this->RotatedText(21.24, 7.7, 'Aula 37', 90), 1, 0, '');
      $this->Cell(0.4, 3.1, $this->RotatedText(21.64, 7.7, 'Aula 38', 90), 1, 0, '');
      $this->Cell(0.4, 3.1, $this->RotatedText(22.04, 7.7, 'Aula 39', 90), 1, 0, '');
      $this->Cell(0.4, 3.1, $this->RotatedText(22.44, 7.7, 'Aula 40', 90), 1, 0, '');
      $this->Cell(0.4, 3.1, $this->RotatedText(22.84, 7.7, 'Aula 41', 90), 1, 0, '');
      $this->Cell(0.4, 3.1, $this->RotatedText(23.24, 7.7, 'Aula 42', 90), 1, 0, '');
      $this->Cell(0.4, 3.1, $this->RotatedText(23.64, 7.7, 'Aula 43', 90), 1, 0, '');
      $this->Cell(0.4, 3.1, $this->RotatedText(24.04, 7.7, 'Aula 44', 90), 1, 0, '');
      $this->Cell(0.4, 3.1, $this->RotatedText(24.44, 7.7, 'Aula 45', 90), 1, 0, '');
      $this->Cell(0.6, 3.1, $this->RotatedText(24.95, 7.7, 'Número', 90), 1, 0, '');

      $this->Cell(0.6, 3.1, $this->RotatedText(25.55, 7.7, 'Aval', 90), 1, 0, '');
      $this->RotatedText(25.55, 5.4,'Data',90);
      $this->Cell(0.6, 3.1, $this->RotatedText(26.15, 7.7, 'Aval', 90), 1, 0, '');
      $this->RotatedText(26.15, 5.4,'Data',90);
      $this->Cell(0.6, 3.1, $this->RotatedText(26.75, 7.7, 'Aval', 90), 1, 0, '');
      $this->RotatedText(26.75, 5.4,'Data',90);
      $this->Cell(0.6, 3.1, $this->RotatedText(27.35, 7.7, 'Aval', 90), 1, 0, '');
      $this->RotatedText(27.35, 5.4,'Data',90);
      $this->Cell(0.6, 3.1, $this->RotatedText(27.95, 7.7, 'RU', 90), 1, 0, '');
      $this->RotatedText(27.95, 5.4,'Data',90);
      $this->Cell(0.6, 3.1, $this->RotatedText(28.55, 7.7, 'Recuperação paralela', 90), 1, 0, '');
      $this->Cell(0.5575, 3.1, $this->RotatedText(29.15, 7.7, 'Faltas', 90), 1, 1, '');

      $this->RotatedText(25.179, 5.5,'_____________________',0);
      $this->RotatedText(25.179, 6.9,'_____________________',0);

      $this->printExamsDate();
    }
    else {
      $this->SetY(2.8);
    }
  }

  public function insertStudent($info = []) {
    $this->SetFont('Times', '', 8);
    $name = mb_strtolower($info["name"]);
    $name = ucwords($name);
    $this->SetX(0.36);
    $this->Cell(0.6, 0.6, $info["num"], 1, 0, 'C');
    $this->Cell(5.6, 0.6, $name, 1, 0, '');
    $this->Cell(0.4, 0.6, $info["lesson-1"], 1, 0, 'C');
    $this->Cell(0.4, 0.6, $info["lesson-2"], 1, 0, 'C');
    $this->Cell(0.4, 0.6, $info["lesson-3"], 1, 0, 'C');
    $this->Cell(0.4, 0.6, $info["lesson-4"], 1, 0, 'C');
    $this->Cell(0.4, 0.6, $info["lesson-5"], 1, 0, 'C');
    $this->Cell(0.4, 0.6, $info["lesson-6"], 1, 0, 'C');
    $this->Cell(0.4, 0.6, $info["lesson-7"], 1, 0, 'C');
    $this->Cell(0.4, 0.6, $info["lesson-8"], 1, 0, 'C');
    $this->Cell(0.4, 0.6, $info["lesson-9"], 1, 0, 'C');
    $this->Cell(0.4, 0.6, $info["lesson-10"], 1, 0, 'C');
    $this->Cell(0.4, 0.6, $info["lesson-11"], 1, 0, 'C');
    $this->Cell(0.4, 0.6, $info["lesson-12"], 1, 0, 'C');
    $this->Cell(0.4, 0.6, $info["lesson-13"], 1, 0, 'C');
    $this->Cell(0.4, 0.6, $info["lesson-14"], 1, 0, 'C');
    $this->Cell(0.4, 0.6, $info["lesson-15"], 1, 0, 'C');
    $this->Cell(0.4, 0.6, $info["lesson-16"], 1, 0, 'C');
    $this->Cell(0.4, 0.6, $info["lesson-17"], 1, 0, 'C');
    $this->Cell(0.4, 0.6, $info["lesson-18"], 1, 0, 'C');
    $this->Cell(0.4, 0.6, $info["lesson-19"], 1, 0, 'C');
    $this->Cell(0.4, 0.6, $info["lesson-20"], 1, 0, 'C');
    $this->Cell(0.4, 0.6, $info["lesson-21"], 1, 0, 'C');
    $this->Cell(0.4, 0.6, $info["lesson-22"], 1, 0, 'C');
    $this->Cell(0.4, 0.6, $info["lesson-23"], 1, 0, 'C');
    $this->Cell(0.4, 0.6, $info["lesson-24"], 1, 0, 'C');
    $this->Cell(0.4, 0.6, $info["lesson-25"], 1, 0, 'C');
    $this->Cell(0.4, 0.6, $info["lesson-26"], 1, 0, 'C');
    $this->Cell(0.4, 0.6, $info["lesson-27"], 1, 0, 'C');
    $this->Cell(0.4, 0.6, $info["lesson-28"], 1, 0, 'C');
    $this->Cell(0.4, 0.6, $info["lesson-29"], 1, 0, 'C');
    $this->Cell(0.4, 0.6, $info["lesson-30"], 1, 0, 'C');
    $this->Cell(0.4, 0.6, $info["lesson-31"], 1, 0, 'C');
    $this->Cell(0.4, 0.6, $info["lesson-32"], 1, 0, 'C');
    $this->Cell(0.4, 0.6, $info["lesson-33"], 1, 0, 'C');
    $this->Cell(0.4, 0.6, $info["lesson-34"], 1, 0, 'C');
    $this->Cell(0.4, 0.6, $info["lesson-35"], 1, 0, 'C');
    $this->Cell(0.4, 0.6, $info["lesson-36"], 1, 0, 'C');
    $this->Cell(0.4, 0.6, $info["lesson-37"], 1, 0, 'C');
    $this->Cell(0.4, 0.6, $info["lesson-38"], 1, 0, 'C');
    $this->Cell(0.4, 0.6, $info["lesson-39"], 1, 0, 'C');
    $this->Cell(0.4, 0.6, $info["lesson-40"], 1, 0, 'C');
    $this->Cell(0.4, 0.6, $info["lesson-41"], 1, 0, 'C');
    $this->Cell(0.4, 0.6, $info["lesson-42"], 1, 0, 'C');
    $this->Cell(0.4, 0.6, $info["lesson-43"], 1, 0, 'C');
    $this->Cell(0.4, 0.6, $info["lesson-44"], 1, 0, 'C');
    $this->Cell(0.4, 0.6, $info["lesson-45"], 1, 0, 'C');
    $this->Cell(0.6, 0.6, $info["num"], 1, 0, 'C');
    $this->Cell(0.6, 0.6, $info["exam-1"], 1, 0, 'C');
    $this->Cell(0.6, 0.6, $info["exam-2"], 1, 0, 'C');
    $this->Cell(0.6, 0.6, $info["exam-3"], 1, 0, 'C');
    $this->Cell(0.6, 0.6, $info["exam-4"], 1, 0, 'C');
    $this->Cell(0.6, 0.6, $info["average"], 1, 0, 'C');
    $this->Cell(0.6, 0.6, $info["final-average"], 1, 0, 'C');
    $this->Cell(0.5575, 0.6, $info["absence"], 1, 1, 'C');
  }

  public function setExamsDate($date) {
    $this->examsDate = $date;
  }

  public function printExamsDate() {
    $this->RotatedText(25.56, 6.88, $this->examsDate[0], 90);
    $this->RotatedText(26.16, 6.88, $this->examsDate[1], 90);
    $this->RotatedText(26.76, 6.88, $this->examsDate[2], 90);
    $this->RotatedText(27.36, 6.88, $this->examsDate[3], 90);
  }

  /**
   * Permite a exibição das informações sobe a disciplina
   * @param boolean $status - Exibe ou não as informações da disciplina
   */
  public function setheaderStatus($status) {
    $this->headerStatus = $status;
  }

  public function Footer()
  {
	  // Posiciona-se o rodapé a 1cm do fim da página
    $this->SetXY(0.36,-5.2);
    $this->SetFont('Times','',8);
    $this->Cell(26.1, 9, 'Legenda RU: Resultado da Unidade', 0, 0, '');
    $this->Cell(0, 9, 'Gerado pelo LibreClass', 0, 0, '');
  }

  /**
   * Imprime no PDF as informações de notas de aulas
   *
   * @param int $lessonNum - Número da aula
   * @param string $lessonName - Título da aula
   * @param string $lessonNote - Nota de aula
   */
  public function insertLessonNotes($lessonNum, $lessonName, $lessonNote) {
    $this->setheaderStatus(false);
    $this->SetFont('Times', '', 9);
    $this->SetX(0.36);
    $this->Cell(0.5575, 0.6, "", 0, 1, 'L'); // Campo de FALTAS
    $this->SetX(0.36);
    $str = "Aula " . $lessonNum . " - " . $lessonName . "\n"
         . "Nota de aula: " . $lessonNote;
    $this->MultiCell(28.965, 0.6, $str, 1, 'L', 0); // Notas de aulas
  }

  public function signatureField()
  {
    $this->Cell(9.8, 4, "____________________________________", 0, 0, 'C');
    $this->Cell(18, 4, "    ____/____/____       ", 0, 0, 'C');
    $this->Cell(-10, 4, "____________________________________", 0, 1, 'C');
    $this->Cell(9.6, -3, "ASSINATURA DO VICE-DIRETOR", 0, 0, 'C');
    $this->Cell(18, -3, "       DATA       ", 0, 0, 'C');
    $this->Cell(-10.0, -3, "ASSINATURA DO PROFESSOR", 0, 1, 'C');
  }

  /**
   * Atribui os valores à $classInfo verificando se a entrada é vazia
   * @param array $info
   */
  public function setClassInfo ($info = []) {
    //$info["escola"] = "CENTRO TERRITORIAL DE EDUCAÇÃO PROFISSIONAL DO SERTÃO DO SÃO FRANCISCO";
    $info["escola"] = empty($info["institution_name"]) ? "NOME DA ESCOLA NÃO CADASTRADO" : mb_strtoupper($info["institution_name"]);
    $info["endereco"] = empty($info["institution_street"]) ? "ENDEREÇO NÃO CADASTRADO" : mb_strtoupper($info["institution_street"]);
    $info["codigo_da_uee"] = empty($info["institution_uee"]) ? "Código da UEE: --" : "Código da UEE: ".$info["institution_uee"];
    $info["periodo_letivo"] = empty($info["periodo_letivo"]) ? "Periodo Letivo:" : "Periodo Letivo: " . $info["periodo_letivo"];
    $info["tipo_de_ensino"] = empty($info["tipo_de_ensino"]) ? "Tipo de Ensino:" : "Tipo de Ensino: " . $info["tipo_de_ensino"];
    $info["modalidade"] = empty($info["modalidade"]) ? "Modalidade:" : "Modalidade: " . $info["modalidade"];
    $info["submodalidade"]  = empty($info["submodalidade"])  ? "Submodalidade:"  : "Submodalidade: " . $info["submodalidade"];
    $info["serie"] = empty($info["serie"]) ? "Série:" : "Série: " . $info["serie"];
    $info["turma"] = empty($info["turma"]) ? "Turma:" : "Turma: " . $info["turma"];
    $info["periodo_dia"] = empty($info["periodo_dia"]) ? "Período:" : "Período: " . $info["periodo_dia"];
    $info["disciplina"] = empty($info["disciplina"]) ? "Disciplina:" : "Disciplina: " . $info["disciplina"];
    $info["professor"] = empty($info["professor"]) ? "Professor:" : "Professor: " . $info["professor"];
    $info["unidade"] = empty($info["unidade"]) ? "Unidade:" : "Unidade: " . $info["unidade"];
    $info["touchLessons"] = $info["toughtLessons"];
    $this->classInfo = $info;
  }

  /**
   * Retorna o status da classe
   * @return string
   */
  public static function status()
  {
    Return 'Offer ready!';
  }

}
