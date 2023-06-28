<?php
// Establece la conexión a la base de datos (reemplaza los valores con los de tu configuración)
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "tienda_unid";

$conexion = mysqli_connect($servername, $username, $password, $dbname);
if (!$conexion) {
    die("Error de conexión: " . mysqli_connect_error());
}

// Incluye la clase TCPDF
require_once('../../tcpdf/tcpdf.php');

// Crea una instancia de la clase TCPDF y configura los parámetros del documento PDF
$pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8');
$fechaActual = date('Y-m-d H:i:s'); // Obtiene la fecha y hora actual en el formato deseado
$pdf->SetTitle('Reporte de productos - ' . $fechaActual); // Concatena la fecha al título del reporte
$pdf->SetMargins(10, 10, 10);
$pdf->SetFont('helvetica', '', 11);

// Agrega una página
$pdf->AddPage();

// Realiza la consulta SQL para obtener los productos
$sql = "SELECT * FROM productos";
$productos = mysqli_query($conexion, $sql);

// Verifica si existen registros
if ($productos->num_rows > 0) {
    // Construye el encabezado de la tabla con estilos
    $html = '<table style="border-collapse: collapse; width: 100%;">';
    $html .= '<tr style="background-color: #f5f5f5;">';
    $html .= '<th style="padding: 8px; border: 1px solid #ddd;">Codigo</th>';
    $html .= '<th style="padding: 8px; border: 1px solid #ddd;">Nombre</th>';
    $html .= '<th style="padding: 8px; border: 1px solid #ddd;">Descripcion</th>';
    $html .= '<th style="padding: 8px; border: 1px solid #ddd;">Color</th>';
    $html .= '<th style="padding: 8px; border: 1px solid #ddd;">Precio</th>';
    $html .= '<th style="padding: 8px; border: 1px solid #ddd;">Cantidad</th>';
    $html .= '<th style="padding: 8px; border: 1px solid #ddd;">Cantidad Minima</th>';
    $html .= '<th style="padding: 8px; border: 1px solid #ddd;">Categorias</th>';
    $html .= '</tr>';

    // Construye el contenido de la tabla con estilos
    foreach ($productos as $key => $row) {
        $html .= '<tr>';
        $html .= '<td style="padding: 8px; border: 1px solid #ddd;">' . $row['id'] . '</td>';
        $html .= '<td style="padding: 8px; border: 1px solid #ddd;">' . $row['nombre'] . '</td>';
        $html .= '<td style="padding: 8px; border: 1px solid #ddd;">' . $row['descripcion'] . '</td>';
        $html .= '<td style="padding: 8px; border: 1px solid #ddd;">' . $row['color'] . '</td>';
        $html .= '<td style="padding: 8px; border: 1px solid #ddd;">' . $row['precio'] . '</td>';
        $html .= '<td style="padding: 8px; border: 1px solid #ddd;">' . $row['cantidad'] . '</td>';
        $html .= '<td style="padding: 8px; border: 1px solid #ddd;">' . $row['cantidad_min'] . '</td>';
        $html .= '<td style="padding: 8px; border: 1px solid #ddd;">' . $row['categorias'] . '</td>';
        $html .= '</tr>';
    }

    $html .= '</table>';

    // Agrega el contenido HTML de la tabla al documento PDF
    $pdf->writeHTML($html, true, false, true, false, '');
} else {
    $pdf->writeHTML('<p>No existen registros</p>', true, false, true, false, '');
}

// Crea una tabla separada para mostrar la cantidad de productos y la fecha de emisión del reporte con estilos
$html = '<table style="margin-top: 20px;">';
$html .= '<tr>';
$html .= '<td style="padding: 8px; border: 1px solid #ddd;">Cantidad de productos</td>';
$html .= '<td style="padding: 8px; border: 1px solid #ddd;">Fecha de emision del reporte</td>';
$html .= '</tr>';
$html .= '<tr>';
$html .= '<td style="padding: 8px; border: 1px solid #ddd;">' . $productos->num_rows . '</td>';
$html .= '<td style="padding: 8px; border: 1px solid #ddd;">' . $fechaActual . '</td>';
$html .= '</tr>';
$html .= '</table>';

$pdf->writeHTML($html, true, false, true, false, '');

// Genera el archivo PDF y lo descarga en el navegador
$pdf->Output('reporte_productos_' . date('Y-m-d') . '.pdf', 'D');

// Cierra la conexión a la base de datos
mysqli_close($conexion);
?>
