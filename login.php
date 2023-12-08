<?php
require_once './config.php';
$msg = "";
if( isset( $_SESSION["student"] )) header("location: ./student.php");
if( isset( $_SESSION["staff"] )) header("location: ./staff.php");

if (isset($p["id"], $p["password"])) {

  if ($db->query("SELECT * FROM student WHERE username='$p[id]' AND password='$p[password]'")->rowCount() > 0) {
    $user = $db->query("SELECT * FROM student WHERE username='$p[id]' AND password='$p[password]'")->fetch();
    $_SESSION["student"] = $user["id"];
    header("location: ./student.php");
  }elseif ($db->query("SELECT * FROM staff WHERE username='$p[id]' AND password='$p[password]'")->rowCount() > 0) {
    $user = $db->query("SELECT * FROM staff WHERE username='$p[id]' AND password='$p[password]'")->fetch();
    $_SESSION["staff"] = $user["id"];
    header("location: ./staff.php");
  } else {

    $msg = "البيانات التي ادخلتها خاطئة";
  }
}

?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />

  <!-- Bootstrap CSS -->
  <!-- cdn -->
  <!-- <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.rtl.min.css"
      integrity="sha384-gXt9imSW0VcJVHezoNQsP+TNrjYXoGcrqBZJpry9zJt8PCQjobwmhMGaDHTASo9N"
      crossorigin="anonymous"
    /> -->
  <!-- local -->
  <link rel="stylesheet" href="./src/css/bootstrap.rtl.min.css" />
  <link rel="stylesheet" href="./src/css/common.css" />

  <title>تسجيل الدخول</title>
</head>

<body class="bg-light">
  <nav class="navbar navbar-expand-lg bg-light rounded shadow mb-5" aria-label="Twelfth navbar example">
    <div class="container-fluid">
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarsExample10" aria-controls="navbarsExample10" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse justify-content-md-center" id="navbarsExample10">
        <ul class="navbar-nav">

          <li class="nav-item">
            <a class="nav-link" aria-current="page" href="./index.php">تسجيل الدخول</a>
          </li>

        </ul>
      </div>
    </div>
  </nav>
  <div class="container border shadow rounded pt-3 pb-4">
    <?php if ($msg != "") { ?>
      <div class="text-center alert alert-dismissible alert-danger row mx-auto">
        <strong><?= $msg ?></strong>
      </div>
    <?php } ?>
    <h1 class="leading-4 text-center">تسجيل الدخول</h1>
    <hr class="mx-auto mb-3" />
    <form class="row" method="post">

      <div class="col-md-6 my-2">

        <label for="buyP" class="form-label">ID</label>
        <input name="id" type="text" class="form-control" />
      </div>
      <div class="col-6 my-2">
        <label class="form-label">كلمة السر</label>
        <input name="password" type="password" class="form-control" />

      </div>

      <div class="col-12 mt-4">
        <button class="btn btn-dark w-100" type="submit">تسجيل الدخول</button>
      </div>
    </form>
  </div>


  <script src="./src/js/bootstrap.bundle.min.js"></script>
  <script src="./src/js/main.js"></script>

</body>

</html>