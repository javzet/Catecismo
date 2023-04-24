<?php

ob_start();

function get_apellido_paterno($apellidos)
{

  $apellidos_arr = explode(" ", $apellidos);

  return $apellidos_arr[0] ?? '';
}

function get_apellido_materno($apellidos)
{
  $apellidos_arr = explode(" ", $apellidos);

  return $apellidos_arr[1] ?? '';
}

function get_grado(int $grado)
{
  if ($grado === 1) return 'Comunión 1°';
  if ($grado === 2) return 'Comunión 2°';
  if ($grado === 3) return 'Confirmación 1°';
  if ($grado === 4) return 'Confirmación 2°';

  return 'Sin asignar';
}

function parse_number($number)
{
  if ($number > 9) return $number;

  return "0$number";
}

function parse_phone_number($number)
{
  $number_part_1 = "";
  $number_part_2 = "";
  $number_part_3 = "";

  foreach (str_split($number) as $digit) {
    if (strlen($number_part_1) < 2) {
      $number_part_1 .= $digit;
    }

    if (strlen($number_part_2) < 4) {
      $number_part_2 .= $digit;
    }

    if (strlen($number_part_3) < 4) {
      $number_part_3 .= $digit;
    }
  };

  return "($number_part_1) $number_part_2-$number_part_3";
}

function get_last_name_parts($complete_last_name)
{
  $last_name = "";
  $mothers_last_name = "";
  $found_normal = false;

  $connectors = ["De la", "la", "De", "Del", "Los", "Las", "Y", "Of"];

  $words = preg_split('/\s+/', trim($complete_last_name));

  if (count($words) === 1) {
    $last_name = $words[0];
  } else {
    for ($i = 0; $i < count($words); $i++) {
      $current_word = $words[$i];

      $has_connector = in_array($current_word, $connectors);

      if ($has_connector && trim($mothers_last_name) === "") {
        $last_name .= " " . $current_word;
      }
      if ($has_connector && !(trim($mothers_last_name) === "")) {
        $mothers_last_name .= " " . $current_word;
      }

      if (!$has_connector && $found_normal) {
        $mothers_last_name .= " " . $current_word;
      }
      if (!$has_connector && !$found_normal) {
        $last_name .= " " . $current_word;
      }

      $found_normal = !$has_connector;
    }
  }

  return array("last_name" => $last_name, "mothers_last_name" => $mothers_last_name);
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
  <style>
    *,
    *::after,
    *::before {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
      font-family: sans-serif;
    }

    body:first-child {
      margin-top: 0in;
      margin-bottom: 0in;
    }

    body {
      margin-top: 0.5in;
      margin-bottom: 0.25in;
    }

    caption {
      font-weight: bold;
      font-size: x-large;
      margin-bottom: 0.4rem;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-left: auto;
      margin-right: auto;
      height: 10.25in;
    }

    .container {
      width: 7.65in;
      height: 10.25in;

      margin-left: auto;
      margin-right: auto;

      display: block;
    }

    thead tr {
      background-color: #141414;
      color: #ffffff;
    }

    tbody td,
    thead th {
      padding-left: 0.1in;
      padding-right: 0.1in;

      line-height: 1;
    }

    thead th {
      font-size: 12pt;
      font-weight: 500;
      text-align: left;

      padding-top: 0.12in;
      padding-bottom: 0.12in;

      font-family: sans-serif;
    }

    tbody tr {
      background-color: #ffffff;
    }

    tbody tr:nth-of-type(odd) {
      background-color: #d7d7d7;
    }

    tbody td {
      font-size: 11pt;
      color: #1c1c1c;

      padding-top: 0.1in;
      padding-bottom: 0.1in;
    }
  </style>
</head>

<body>
  <div class="container">
    <table>
      <caption>Alumnos catecismo</caption>
      <thead>
        <tr>
          <th>Nombre(s)</th>
          <th>Apellido paterno</th>
          <th>Apellido materno</th>
          <th>Edad*</th>
          <th>Grado</th>
          <th>Número celular</th>
        </tr>
      </thead>
      <tbody>
        <?php


        foreach ($table_data as $alumno) {

          $exist_phone_number = !is_null($alumno['numero_celular']) && !empty($alumno['numero_celular']);

          $phone_number = $exist_phone_number ? parse_phone_number($alumno['numero_celular']) : 'Sin dato';

          echo '<tr>';

          echo '<td>' . $alumno['nombres'] . '</td>';
          echo '<td>' . get_last_name_parts($alumno['apellidos'])['last_name'] . '</td>';
          echo '<td>' . get_last_name_parts($alumno['apellidos'])['mothers_last_name'] . '</td>';
          echo '<td>' . parse_number($alumno['edad_inscripcion']) . '</td>';
          echo '<td>' . get_grado($alumno['grado']) . '</td>';
          echo '<td>' . $phone_number . '</td>';

          echo '</tr>';
        }

        ?>
      </tbody>
    </table>
  </div>
</body>

</html>