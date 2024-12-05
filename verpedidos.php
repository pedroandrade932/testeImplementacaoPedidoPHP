<?php
session_start(); // Inicia a sessão
# Dados de conexão
$host = "localhost";
$username = "root";
$password = "";

# Nome do bd
$dbase = "acai";



$nomecad;
$pedidoid;
$precocad;

$conn = new PDO("mysql:host=$host;dbname=$dbase", $username, $password);

$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
	<link rel="stylesheet" href="../_css/style-adm.css">
    <link rel="shortcut icon" href="../_imagens/LUNARIS_logo-menu_lua-ico.ico" type="image/x-icon">
	<title>Açaiteria</title>
</head>
<body>

	<!-- SIDEBAR -->
	<section id="sidebar">
		<a href="index.php" class="brand">
			<span class="text">Açaiteria</span>
		</a>
		<ul class="side-menu top">
			<li>
				<a href="pedidos.php">
					<i class='bx bxs-shopping-bag-alt' ></i>
					<span class="text">Fazer Pedido</span>
				</a>
			</li>
			<li class="active">
				<a href="verpedidos.php">
					<i class='bx bxs-shopping-bag-alt' ></i>
					<span class="text">Ver Pedidos</span>
				</a>
			</li>
		</ul>
	</section>
	<!-- SIDEBAR -->



	<!-- CONTENT -->
	<section id="content">
		<!-- MAIN -->
		<main>
			<div class="head-title">
				<div class="left">
					<h1>Pedidos Feitos</h1>
					<ul class="breadcrumb">
						<li>
							<a href="index.php">Açaiteria</a>
						</li>
						<li><i class='bx bx-chevron-right' ></i></li>
					</ul>
				</div>
			</div>
			
			<div class="table-data">
				<div class="order">
					<div class="head">
						<h3>Pedidos e Preços</h3>
						<i class='bx bx-search' ></i>
						<i class='bx bx-filter' ></i>
					</div>
					<table id="tableserv">
						<thead>
							<tr>
								<th>Pedido</th>
								<th>Telefone</th>
								<th>Endereço</th>
								<th>Preço</th>
							</tr>
						</thead>
						<tbody>
						    <?php
                            $data = $conn->prepare('SELECT * FROM Pedido');
                            $data->execute();
                            $result = $data->fetchAll();
                            if ( count($result) ) {
                                foreach($result as $row) {
        						    $telefone = '-';
                                    $ende = '-';

                                    $nomecad = $row[2];
                                    $pedidoid = $row[0];
                                    $precocad = $row[1];
                                    
                                    $data2 = $conn->prepare('SELECT * FROM delivery WHERE pedido_id = :pedidoid');
                                    $data2->execute(array('pedidoid' => $pedidoid));
                                    $result2 = $data2->fetchAll();
                                    if ( count($result2) ) {
                                        foreach($result2 as $row2) {
                                            $ende = $row2[2];
                                            $telefone = $row2[1];
                                        }
                                    }
                                    unset($data2);
                                    switch ($nomecad){
                                        case 1:
                                            $nomecad = "Açaí Puro";
                                            break;
                                        case 2:
                                            $nomecad = "Açaí Gourmet";
                                            break;
                                        case 3:
                                            $nomecad = "Açaí Personalizado";
                                            break;
                                    }
                                    echo "
        							<tr>
        								<td>
        									<p>$nomecad</p>
        								</td>
        								<td>$telefone</td>
        								<td>$ende</td>
        								<td>R$$precocad</td>
        							</tr>
                                    ";
                                }
        				    unset($data);
                            }
                            ?>
						</tbody>
					</table>
				</div>
			</div>

		</main>
		<!-- MAIN -->
	</section>
	<!-- CONTENT -->
</body>
</html>
