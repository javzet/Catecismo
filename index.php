<?php

require __DIR__ . '/vendor/autoload.php';

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

use Dompdf\Dompdf;


function get_html_table($table_data)
{
  require_once './template.php';
  $html = ob_get_clean();

  return $html;
}

function generate_pdf($html)
{
  $dompdf = new Dompdf();
  $options = $dompdf->getOptions();
  $options->set(array('isRemoteEnabled' => true));
  $dompdf->setOptions($options);

  $dompdf->loadHtml($html);
  $dompdf->setPaper('letter');

  return $dompdf;
}

$connection = null;
$data = [];

try {
  $connection = Connection::connect();
  $statement = $connection->prepare('SELECT * FROM alumnos');

  $statement->execute();

  foreach ($statement as $row) {
    $data[] = $row;
  }
} catch (PDOException $error) {
  die('Ha ocurrido un error al conectar con la base de datos:\n' . $error->getMessage());
} finally {
  $connection = null;
}

if (sizeof($data) === 0) exit(0);

$generated_table = get_html_table($data);
$generated_pdf = generate_pdf($generated_table);

$generated_pdf->render();
$generated_pdf->stream("Lista generada", array("Attachment" => false));
