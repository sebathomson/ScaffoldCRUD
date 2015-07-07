<?php include("scaffold.php");

$show_form = 0;
$message = '';

if (isset($_POST['scaffold_info'])) {

	$data = trim($_POST['sql']);
	$data_lines = explode("\n", $data);
	
	// strip all comments
	foreach ($data_lines  AS $key =>
$value) {
		$value = trim($value);
		if ($value[0] == '-' && $value[1] == '-') unset($data_lines[$key]);
		elseif (stripos($value, 'insert into')) unset($data_lines[$key]);
	}
	
	$table = array();
	
	// store into cookie
	foreach($_POST AS $key => $value) {
		$date = time() + 999999;
		if ($key == 'sql') $date = time() + 600;
		setcookie($key, $value, $date, '/');
	}
	
	$table['list_page'] = stripslashes($_POST['list_page']);
	$table['edit_page'] = stripslashes($_POST['edit_page']);
	$table['new_page'] = stripslashes($_POST['new_page']);
	$table['delete_page'] = stripslashes($_POST['delete_page']);
	$table['include'] = stripslashes($_POST['include']);
	
	$table['id_key'] = trim($_POST['id_key']);
	if ($table['id_key'] == '') $table['id_key'] = 'id';
	
	// get first table name
	if ( eregi('CREATE TABLE `(.)+` \(', $data, $matches) ) {
		$table['name'] = find_text($matches[0]);
		$max = count($data_lines);
		for ($i = 1; $i
< $max; $i++ ) {
			if ( strpos( trim($data_lines[$i]), '`') === 0) { // this line has a column
				$col = find_text(trim($data_lines[$i]));
				$blob = ( stripos($data_lines[$i], 'TEXT') || stripos($data_lines[$i], 'BLOB') ) ? 1 : 0;
				$datetime = ( stripos($data_lines[$i], 'DATETIME') ) ? 1 : 0;
				eval( "\$table['$col'] = array('blob' =>
	$blob, 'datetime' => $datetime );");
			}
		}
		
		$show_form = 1;
	
		//print_r($table);
	
	}
	else {
		$message .= "No se puede encontrar 'CREATE TABLE `table_name` ( '";
	}
} 
?>
<!DOCTYPE HTML>
<html lang="es-ES">
<head>
	<title>ScaffoldCRUD</title>
	<meta charset="UTF-8">
	
	<script type="text/javascript" src="js/prototype.js"></script>
	<script type="text/javascript" src="js/scriptaculous.js"></script>
	<script type="text/javascript" src="js/s.js"></script>
	<script type="text/javascript" src="scripts/shCore.js"></script>
	<script type="text/javascript" src="scripts/shBrushSql.js"></script>
	<script type="text/javascript" src="scripts/shBrushPhp.js"></script>
	<script type="text/javascript">
	SyntaxHighlighter.all();
	</script>

	<link type="text/css" rel="stylesheet" href="css/bootstrap.css"/>
	<link type="text/css" rel="stylesheet" href="styles/shCoreDefault.css"/>
	<link type="text/css" rel="stylesheet" href="styles/shThemeRDark.css"/>
    <style> .navbar { margin-bottom: 20px; } </style>
</head>
<body>
		<div class="navbar navbar-inverse navbar-static-top">
			<div class="navbar-inner">
				<div class="container-fluid">
					<a class="brand" href="index.php">
						php<strong>Scaffold</strong>
					</a>
					<?php if ($show_form) { ?>
					<ul class="nav">
						<li class="active">
							<a href="index.php">Entrar nueva tabla</a>
						</li>
<!-- 						<li>
							<a href="javascript:showAll()">Mostrar todos</a>
						</li>
						<li>
							<a href="javascript:hideAll()">Ocultar todos</a>
						</li> -->
					</ul>
					<?php } ?>
				</div>
			</div>
		</div>
		<div class="container" >
			<div class="hero-unit" <?php if ($show_form) echo "style='display:none'"; ?>>
				<p>
					<h4>
						Bienvenido, aquí puedes generar fácilmente tus páginas CRUD para PHP/MySQL.
					</h4>
					Ingrese tu tabla de SQL exportada desde tu gestor de base de datos.
					<a href="javascript:showHint('sql_hint');" class="label label-info">Ejemplo</a>
				</p>
			</div>
			<?php if ($message != '') {
				echo "<div class='alert alert-error'>";
				echo $message;
				echo "</div>";
			} ?>
			<div <?php if ($show_form) echo "style='display:none'"; ?>>
				<form action="" method="post">
					<div id="sql_hint" style="display:none; ">
						<div class="alert alert-info">
							<a href="javascript:showHint('sql_hint');" class="close" data-dismiss="alert">&times;</a>
							<p>
								Ejemplo de la estructura de una tabla
							</p>
							<pre class="brush: sql;" contentEditable="false">
--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `username` varchar(30) NOT NULL,
  `password` varchar(30) NOT NULL,
  `email` varchar(30) NOT NULL,
  `created_on` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 ;
							</pre>
<!-- 							<div class="clearfix"></div>
							<div class="pull-right">
								<button class="btn btn-mini btn-inverse" type="button">Probar</button>
							</div>
							<div class="clearfix"></div> -->
						</div>
					</div>
					<hr>
					<textarea class="input-block-level brush: sql;" name="sql" rows="10" placeholder="Ingresa la estructura de tu tabla"><?php if (isset($_REQUEST['sql'])) echo stripslashes($_REQUEST['sql']); else echo '' ?></textarea>
					<div class="well">
						<label>Nombre del archivo de configuración <a class="label label-info" href="javascript:showHint('include_hint');">Ejemplo del archivo</a></label>
						<input name="include" type="text" class="input-medium" value="<?php if (isset($_REQUEST['include'])) echo stripslashes($_REQUEST['include']); else echo 'config.php' ?>">
						<div id="include_hint" style="display:none;">
							<div class="alert alert-info">
								<a href="javascript:showHint('include_hint');" class="close" data-dismiss="alert">&times;</a>
								<pre class="brush: php;">
								// connect to db
								$link = mysql_connect('localhost', 'mysql_user', 'mysql_password');
								if (!$link) {
								    die('Not connected : ' . mysql_error());
								}

								if (! mysql_select_db('foo') ) {
								    die ('Can\'t use foo : ' . mysql_error());
								}
								</pre>
							</div>
						</div>
						<label>Nombre de la PK</label>
						<input name="id_key" type="text" class="input-medium" value="<?php if (isset($_REQUEST['id_key'])) echo stripslashes($_REQUEST['id_key']); else echo 'id' ?>">
						
						<label>Nombre de archivo de la lista</label>
						<input name="list_page" type="text" class="input-medium" value="<?php if (isset($_REQUEST['list_page'])) echo stripslashes($_REQUEST['list_page']); else echo 'list.php' ?>">
						
						<label>Nombre de archivo del crear</label>
						<input name="new_page" type="text" class="input-medium" value="<?php if (isset($_REQUEST['new_page'])) echo stripslashes($_REQUEST['new_page']); else echo 'new.php' ?>">
						
						<label>Nombre de archivo del editar</label>
						<input name="edit_page" type="text" class="input-medium" value="<?php if (isset($_REQUEST['edit_page'])) echo stripslashes($_REQUEST['edit_page']); else echo 'edit.php' ?>">
						
						<label>Nombre de archivo del eliminar</label>
						<input name="delete_page" type="text" class="input-medium" value="<?php if (isset($_REQUEST['delete_page'])) echo stripslashes($_REQUEST['delete_page']); else echo 'del.php' ?>">
						
						<input name="scaffold_info" type="hidden" value="1" />
					</div>
					<input type="submit" class="btn btn-success btn-large btn-block" value="GENERAR" />
				</form>
			</div>
<?php
	if ($show_form) {

		$s = new Scaffold($table);

		echo "
			<div class=\"row-fluid\">
				<ul class=\"nav nav-pills\">
	  				<li class=\"span3 active\"><a id=\"listado\" href=\"#listado\">Listado</a></li>
	  				<li class=\"span3\"><a href=\"#nuevo\">Nuevo</a></li>
	  				<li class=\"span3\"><a href=\"#editar\">Editar</a></li>
	  				<li class=\"span3\"><a href=\"#eliminar\">Eliminar</a></li>
				</ul>
			</div>
			";

		echo "
			<ul class=\"breadcrumb\">
			  <li><a href=\"javascript:showHint('list');\">Mostrar/Ocultar</a> <span class=\"divider\">/</span></li>
			  <!--<li><a href=\"javascript:selectAll('list');\">Seleccionar Todo</a> <span class=\"divider\">/</span></li>-->
			  <li><a href=\"download.php\">Descargar Todos los Archivos</a></li>
			</ul>
			";

		echo "<pre class=\"brush: php;\" id=\"list\">";
		echo htmlentities($s->listtable());
		echo "</pre>";

		echo "<hr>";

		echo "
			<div class=\"row-fluid\">
				<ul class=\"nav nav-pills\">
	  				<li class=\"span3\"><a href=\"#listado\">Listado</a></li>
	  				<li class=\"span3 active\"><a id=\"nuevo\" href=\"#nuevo\">Nuevo</a></li>
	  				<li class=\"span3\"><a href=\"#editar\">Editar</a></li>
	  				<li class=\"span3\"><a href=\"#eliminar\">Eliminar</a></li>
				</ul>
			</div>
			";

		echo "
			<ul class=\"breadcrumb\">
			  <li><a href=\"javascript:showHint('new');\">Mostrar/Ocultar</a> <span class=\"divider\">/</span></li>
			  <!--<li><a href=\"javascript:selectAll('new');\">Seleccionar Todo</a> <span class=\"divider\">/</span></li>-->
			  <li><a href=\"download.php\">Descargar Todos los Archivos</a></li>
			</ul>
			";

		echo "<pre class=\"brush: php;\" id=\"new\">";
		echo htmlentities($s->newrow());
		echo "</pre>";

		echo "<hr>";

		echo "
			<div class=\"row-fluid\">
				<ul class=\"nav nav-pills\">
					<li class=\"span3\"><a href=\"#listado\">Listado</a></li>
	  				<li class=\"span3\"><a href=\"#nuevo\">Nuevo</a></li>
	  				<li class=\"span3 active\"><a id=\"editar\" href=\"#editar\">Editar</a></li>
	  				<li class=\"span3\"><a href=\"#eliminar\">Eliminar</a></li>
				</ul>
			</div>
			";

		echo "
			<ul class=\"breadcrumb\">
			  <li><a href=\"javascript:showHint('edit');\">Mostrar/Ocultar</a> <span class=\"divider\">/</span></li>
			  <!--<li><a href=\"javascript:selectAll('edit');\">Seleccionar Todo</a> <span class=\"divider\">/</span></li>-->
			  <li><a href=\"download.php\">Descargar Todos los Archivos</a></li>
			</ul>
			";

		echo "<pre class=\"brush: php;\" id=\"edit\">";
		echo htmlentities($s->editrow());
		echo "</pre>";

		echo "<hr>";

		echo "
			<div class=\"row-fluid\">
				<ul class=\"nav nav-pills\">
					<li class=\"span3\"><a href=\"#listado\">Listado</a></li>
	  				<li class=\"span3\"><a href=\"#nuevo\">Nuevo</a></li>
	  				<li class=\"span3\"><a href=\"#editar\">Editar</a></li>
	  				<li class=\"span3 active\"><a id=\"eliminar\" href=\"#eliminar\">Eliminar</a></li>
				</ul>
			</div>
			";

		echo "
			<ul class=\"breadcrumb\">
			  <li><a href=\"javascript:showHint('delete');\">Mostrar/Ocultar</a> <span class=\"divider\">/</span></li>
			  <!--<li><a href=\"javascript:selectAll('delete');\">Seleccionar Todo</a> <span class=\"divider\">/</span></li>-->
			  <li><a href=\"download.php\">Descargar Todos los Archivos</a></li>
			</ul>
			";

		echo "<pre class=\"brush: php;\" id=\"delete\">";
		echo htmlentities($s->deleterow());
		echo "</pre>";
}
?>
		<hr>
		<div class="well well-small">
		Puedes ver la versión original en <a href="http://www.phpscaffold.com/">www.phpscaffold.com</a> (uprz23<span class="text-info">< at ></span>gmail.com) | <a href="http://www.phpscaffold.com/source.rar">Descargar source</a>.<br>
		Versión en español y con <a href="http://twitter.github.com/bootstrap/index.html">Bootstrap</a> por <a href="http://sebathomson.github.io/">SebaThomson</a> | <a href="https://github.com/sebathomson/ScaffoldCRUD" target="_blank">Ver repositorio en GitHub</a>.<br>
		The source is under the <a href="http://creativecommons.org/licenses/MIT/">MIT License</a>.
		</div>
	</div>
</body>
</html>