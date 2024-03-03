<?php

use Source\model\User;

require "./vendor/autoload.php";

// Inicializa a sessão
session_start();

// Verifique se o usuário já está logado, em caso afirmativo, redirecione-o para a página de boas-vindas
if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
  header("location: index.php");
  exit;
}

$username = $password = "";
$username_err = $password_err = $login_err = "";

// Processando dados do formulário quando o formulário é enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // Verifique se o nome de usuário está vazio
  if (empty(trim($_POST["username"]))) {
    $username_err = "Por favor, insira o nome de usuário.";
  } else {
    $username = trim($_POST["username"]);
  }

  // Verifique se a senha está vazia
  if (empty(trim($_POST["password"]))) {
    $password_err = "Por favor, insira sua senha.";
  } else {
    $password = trim($_POST["password"]);
  }

  // Validar credenciais
  if (empty($username_err) && empty($password_err)) {

    if ($usuario = (new User())->find("email = :name", "name={$username}")->fetch()) {

      // var_dump($usuario);

      if ($usuario->senha == md5($password)) {

        // A senha está correta, então inicie uma nova sessão
        session_start();

        // Armazene dados em variáveis de sessão
        $_SESSION["loggedin"] = true;
        $_SESSION["username"] = $usuario->nome;
        $_SESSION["userid"] = $usuario->id;

        // Redirecionar o usuário para a página de boas-vindas
        header("location: index.php");
      } else {
        // A senha do usuário está incorreta. Exibe uma mensagem de erro genérica
        $login_err = "Nome de usuário ou senha inválidos.";
      }
    } else {
      // O nome de usuário não existe. Exibe uma mensagem de erro genérica
      $login_err = "Nome de usuário ou senha inválidos.";
    }
  }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Planejamento Operacional Virtual | Log in</title>


  <!-- Font Awesome -->
  <link rel="stylesheet" href="./plugins/fontawesome-free/css/all.min.css">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="./plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="./dist/css/adminlte.min.css">

  <style>
    .video-bg {
      object-fit: cover;
      width: 100vw;
      height: 100vh;
      position: fixed;
      top: 0;
      left: 0;
      z-index: -1;
      box-shadow: 0 0 500px rgba(0, 0, 0, 0.9) inset;
    }
  </style>
</head>

<body class="hold-transition login-page">

  <video class="video-bg" src="./media/bg.mp4" autoplay loop playsinline muted></video>

  <div class="login-box">


    <div class="card card-outline card-primary">

      <div class="card-header text-center">
        <a href="#" class="h1"><b>POV</b>irtual</a>
      </div>
      <!-- /.login-logo -->

      <div class="card-body login-card-body">
        <p class="login-box-msg">Entre com suas credenciais</p>

        <?php
        if (!empty($login_err)) {
          echo '<div class="alert alert-danger">' . $login_err . '</div>';
        }
        ?>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
          <div class="input-group mb-3">

            <input type="email" name="username" class="form-control <?php echo (!empty($username_err)) ? 'is-invalid' : ''; ?>" placeholder="Usuário" value="<?php echo $username; ?>">


            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-envelope"></span>
              </div>
            </div>

            <span class="invalid-feedback"><?php echo $username_err; ?></span>
          </div>
          <div class="input-group mb-3">
            <input type="password" name="password" class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>" placeholder="Senha">

            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-lock"></span>
              </div>

              <span class="invalid-feedback"><?php echo $password_err; ?></span>
            </div>
          </div>
          <div class="row">
            <!-- <div class="col-8">
              <div class="icheck-primary">
                <input type="checkbox" id="remember">
                <label for="remember">
                  Lembrar
                </label>
              </div>
            </div> -->
            <!-- /.col -->
            <div class="col-12">
              <button type="submit" class="btn btn-primary btn-block">Entrar</button>
            </div>
            <!-- /.col -->
          </div>
        </form>

        <p class="mb-1">
          <a href="#">Esqueci minha senha</a>
        </p>
        <p class="mb-0">
          <a href="#" class="text-center">Solicite acesso</a>
        </p>
      </div>
      <!-- /.login-card-body -->
    </div>
  </div>
  <!-- /.login-box -->

  <!-- jQuery -->
  <script src="./plugins/jquery/jquery.min.js"></script>
  <!-- Bootstrap 4 -->
  <script src="./plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
  <!-- AdminLTE App -->
  <script src="./dist/js/adminlte.min.js"></script>
</body>

</html>