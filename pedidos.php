<?php
session_start();

# Dados de conexão
$host = "localhost";
$username = "root";
$password = "";

# Nome do bd
$dbase = "acai";

function uniqidReal($leng = 5) {
    $item = rand(0,999999);
    $numero = str_pad($item, $leng, 0, STR_PAD_LEFT);
    return $numero;    
}


try {
    $pedido = $_POST['pedido'];
    $preco = $_POST['preco'];
    $pedidocad;

    if ($pedido != '' && $preco != ''){
        $conn = new PDO("mysql:host=$host;dbname=$dbase", $username, $password);
        
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $pedido = intval($pedido);
        $preco = floatval($preco);

        $pedidoid = uniqidReal();
        while (true) {
            $data = $conn->prepare('SELECT * FROM Pedido WHERE id = :pedidoid');
            $data->execute(array('pedidoid' => $pedidoid));
            $result = $data->fetchAll();
            if ( count($result) ) {
                $pedidoid = uniqidReal();
            }else{
                break;
            }
        }
        
        $data = $conn->query("INSERT INTO Pedido (id, tipo, preco) VALUES ($pedidoid, '$pedido', $preco)");
        unset($data);

        if (isset($_POST['delivery'])){
            $telefone = $_POST['telefone'];
            $cep = $_POST['endereco'];
            
            $conn = new PDO("mysql:host=$host;dbname=$dbase", $username, $password);
            
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
            $data = $conn->prepare('SELECT id FROM Pedido WHERE id = :pedidoid');
            $data->execute(array('pedidoid' => $pedidoid));
            $result = $data->fetchAll();
            if ( count($result) ) {
                foreach($result as $row) {
                    $pedidoid = $row[0];
                }
    
            }
            $data = $conn->query("INSERT INTO delivery (pedido_id, telefone, endereco_cliente) VALUES ($pedidoid, '$telefone', '$cep')");
            unset($data);
        }else{
            $conn = new PDO("mysql:host=$host;dbname=$dbase", $username, $password);
            
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
            $data = $conn->prepare('SELECT id FROM Pedido WHERE id = :pedidoid');
            $data->execute(array('pedidoid' => $pedidoid));
            $result = $data->fetchAll();
            if ( count($result) ) {
                foreach($result as $row) {
                    $pedidoid = $row[0];
                }
    
            }
            $hora = date('Y-m-d');
            $data = $conn->query("INSERT INTO Presencial (pedido_id, hora) VALUES ($pedidoid, '$hora')");
            unset($data);
        }
        echo
        '<!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Açaí</title>
            <link rel="stylesheet" href="../_css/style-site-cliente.css">
            <meta http-equiv="refresh" content="0;URL=verpedidos.php" />
        </head>
        <body>
        </body>';
        header("Location: verpedidos.php");
        exit();
        }
}catch(PDOException $e) {
    $z='';
    echo 'ERROR: ' . $e->getMessage();
}
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
			<li class="active">
				<a href="pedidos.php">
					<i class='bx bxs-shopping-bag-alt' ></i>
					<span class="text">Fazer Pedido</span>
				</a>
			</li>
			<li>
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
					<h1>Realizar Pedido</h1>
					<ul class="breadcrumb">
						<li>
							<a href="index.php">Açaiteria</a>
						</li>
						<li><i class='bx bx-chevron-right' ></i></li>
					</ul>
				</div>
			</div>
			<div class="addplan">
			    <div class="content">
    			    <div class="head">
    					<h3></h3>
    			    </div>
                    <form id="normal" action="pedidos" method="post">
                        <div class = "campo">
                            <label for = "pedido">Pedido: </label>
                            <select name="pedido" id = "pedido" required>
                                <option value="">Escolha uma opção</option>
                                <option value="1">Açaí Puro</option>
                                <option value="2">Açaí Gourmet</option>
                                <option value="3">Açaí Personalizado</option>
                            </select>
                        </div><br>
                        
                        <div>
                            <label for = "delivery">Marque, se for delivery: </label>
                            <input type = "checkbox" id = "delivery" name = "delivery" minlength="11" placeholder = "apenas números com DDD"> <br>
                        </div><br>
    
                        <div id="telefone_div" style="opacity:50%" class = "campo">
                            <label for = "telefone">Telefone: </label>
                            <input disabled type = "text" id = "telefone" name = "telefone" minlength="11" placeholder = "apenas números com DDD"> <br>
                        </div>
    
                        <div id="endereco_div" style="opacity:50%" class = "campo">
                            <label for = "endereco">Endereço: </label>
                            <input disabled type = "text" id = "endereco" name = "endereco" placeholder = "Rua, Bairro, Cidade"> <br>
                        </div>
    
                        <div class = "campo">
                            <label for = "preco">Preço R$: </label>
                            <input type = "number" step="0.01" id = "preco" name = "preco" required minlength="11" placeholder = "25.50"> <br>
                        </div>
                        <br><br>
                        <input type="submit" value="Realizar Pedido" id='cad' class="btn-submit" name="enviar"> <br><br>
                    </form>
            </div>
    </main>
    <script>
        const isDelivery = document.getElementById("delivery")
        
        function deliveryCheck(){
            let telefone = document.getElementById("telefone_div")
            let ende = document.getElementById("endereco_div")
            if (isDelivery.checked){
                telefone.innerHTML = '<label for = "telefone"><ion-icon name="mail-outline"></ion-icon> Telefone: </label><input type = "number" class = "campo" id = "telefone" name = "telefone" required minlength="11" placeholder = "apenas números com DDD"><br>'
                telefone.style.opacity = "100%"

                ende.innerHTML = '<label for = "endereco"><ion-icon name="mail-outline"></ion-icon> Endereço: </label><input type = "text" class = "campo" id = "endereco" name = "endereco" required placeholder = "Rua, Bairro, Cidade"><br>'
                ende.style.opacity = "100%"
            }else{
                telefone.innerHTML = '<label for = "telefone"><ion-icon name="mail-outline"></ion-icon> Telefone: </label><input disabled type = "number" class = "campo" id = "telefone" name = "telefone" required minlength="11" placeholder = "apenas números com DDD"><br>'
                telefone.style.opacity = "50%"
                ende.innerHTML = '<label for = "endereco"><ion-icon name="mail-outline"></ion-icon> Endereço: </label><input disabled type = "text" class = "campo" id = "endereco" name = "endereco" required placeholder = "Rua, Bairro, Cidade"><br>'
                ende.style.opacity = "50%"
            }
        }
        
        isDelivery.addEventListener('click', deliveryCheck)
    </script>
</body>
</html>
