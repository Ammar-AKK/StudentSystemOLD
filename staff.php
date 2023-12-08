<?php
  require_once './config.php';
  $date = date("Y-m-d", time());
  if( isset( $_SESSION["student"] )) header("location: ./student.php");
  if( isset( $_SESSION["staff"] ) == false) header("location: ./login.php");

  if( isset( $g["delete_session"] ) ){
    $db->query("DELETE FROM session WHERE id=$g[delete_session]");
    header("location: ?");
  }
  if( isset( $p["deny_removal_request"] ) ) $db->query("UPDATE course_remove_request SET `status`=1 WHERE id=$p[deny_removal_request]");
  if( isset( $p["accept_removal_request"] ) ) {
    $request = $db->query("SELECT * FROM course_remove_request WHERE id=$p[accept_removal_request]")->fetch();
    $db->query("DELETE FROM student_course WHERE student_id=$request[student_id] and course_id=$request[student_id]");
    $db->query("DELETE FROM course_remove_request WHERE id=$p[accept_removal_request]");
  }

  if( isset( $p["session_day"], $p["session_time"], $p["session_course"] ) ) {
    $db->query("INSERT INTO session ( day, time, course_id ) VALUES( $p[session_day], '$p[session_time]', $p[session_course] )");
  }
  if( isset( $p["course"] ) ) $db->query("INSERT IGNORE INTO student_course( student_id, course_id ) VALUES( $_SESSION[student], $p[course] )");
  $courses = $db->query("SELECT * FROM course WHERE staff_id=$_SESSION[staff]")->fetchAll();
  $courses = $db->query("SELECT * FROM course WHERE staff_id=$_SESSION[staff]")->fetchAll();
  $delete_requests = $db->query("SELECT course_remove_request.*, course.name, student.name as student_name FROM course_remove_request INNER JOIN course on course.id=course_remove_request.course_id INNER JOIN student ON student.id=course_remove_request.student_id WHERE staff_id=$_SESSION[staff] AND course_remove_request.status=0")->fetchAll();
  $sessions = $db->query( "SELECT session.*, course.name as course FROM session INNER JOIN student_course ON student_course.course_id=session.course_id INNER JOIN course on course.id=student_course.course_id WHERE course.staff_id=$_SESSION[staff]" )->fetchAll();

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
  <link href="./src/datatables.min.css" rel="stylesheet">

  <title>الموظف</title>
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
            <a class="nav-link" aria-current="page" href="./student.php">الرئيسية</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" aria-current="page" href="./logout.php">تسجيل الخروج</a>
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
    <h1 class="leading-4 text-center">اضافة كورس جديد</h1>
    <hr class="mx-auto mb-3" />
    <form class="row" method="post">
      <!-- Name -->
      <div class="col-12 my-2">
        <label for="City" class="form-label">الكورس</label>
        <select name="session_course" id="" class="form-select">
          <?php foreach ($courses as $course ) {?>
            <option value="<?= $course["id"] ?>"><?= $course["name"] ?></option>
          <?php } ?>
        </select>
      </div>
      <div class="col-12 my-2">
        <label for="City" class="form-label">اليوم</label>
        <select name="session_day" id="" class="form-select">
            <option value="1">الأحد</option>
            <option value="2">الأثنين</option>
            <option value="3">الثلاثاء</option>
            <option value="4">الأربعاء</option>
            <option value="5">الخميس</option>
           
        </select>
      </div>
      <div class="col-12 my-2">
        <label for="City" class="form-label">التوقيت</label>
        <input type="text" name="session_time" class="form-control" id="">
      </div>
      <div class="col-12 mt-4">
        <button class="btn btn-dark w-100" type="submit">اضافة</button>
      </div>
    </form>
 
    </div>
  
  <div class="container border shadow rounded pt-3 pb-4 mt-5">
    <?php if ($msg != "") { ?>
      <div class="text-center alert alert-dismissible alert-danger row mx-auto">
        <strong><?= $msg ?></strong>
      </div>
    <?php } ?>
    <div class="row mt-4 px-2">
    <h1 class="leading-4 text-center">الكورسات</h1>
    <hr class="mx-auto mb-3" />
    <table id="students" class="table table-dark">
      <thead>
        <tr>
          <th>Course Name</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($courses as $course) { ?>
          <tr>
            <td><?= $course["name"] ?></td>
          </tr>
        <?php } ?>
      </tbody>
    </table>
  </div>
  </div>

  <div class="container border shadow rounded pt-3 pb-4 mt-5">
    <?php if ($msg != "") { ?>
      <div class="text-center alert alert-dismissible alert-danger row mx-auto">
        <strong><?= $msg ?></strong>
      </div>
    <?php } ?>
    <div class="row mt-4 px-2">
    <h1 class="leading-4 text-center">طلبات الحذف</h1>
    <hr class="mx-auto mb-3" />
    <table id="students" class="table table-dark">
      <thead>
        <tr>
          <th>Course Name</th>
          <th>Student</th>
          <th>Accept</th>
          <th>Reject</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($delete_requests as $request) { ?>
          <tr>
            <td><?= $request["name"] ?></td>
            <td><?= $request["student_name"] ?></td>
            <td>
              <form method="post">
                <input type="hidden" name="accept_removal_request" value="<?= $request["id"] ?>">
                <input type="submit" value="قبول" class="btn btn-success">
              </form>
            </td>
            <td>
              <form method="post">
                <input type="hidden" name="deny_removal_request" value="<?= $request["id"] ?>" >
                <input type="submit" value="رفض" class="btn btn-danger">
              </form>
            </td>
          </tr>
        <?php } ?>
      </tbody>
    </table>
  </div>
  </div>
  </div>

  
  <div class="container border shadow rounded pt-3 pb-4 mt-5">
    <div class="row mt-4 px-2">
    <h1 class="leading-4 text-center">الكورسات</h1>
    <hr class="mx-auto mb-3" />
    <ul class="list-group">

          <li class="list-group-item">الأحد:
            <?php foreach ($sessions as $session ) {
              if( $session["day"] == 1 ){?>
                <p><?= $session["course"] ?>: <?= $session["time"] ?>
                <a class="btn btn-danger" href="?delete_session=<?= $session["id"] ?>">حذف</a></p>
            <?php }} ?>
          </li>
          <li class="list-group-item">الأثنين:
            <?php foreach ($sessions as $session ) {
              if( $session["day"] == 2 ){?>
                <p><?= $session["course"] ?>: <?= $session["time"] ?>
                <a class="btn btn-danger" href="?delete_session=<?= $session["id"] ?>">حذف</a></p>
            <?php }} ?>
          </li>
          <li class="list-group-item">الثلاثاء:
            <?php foreach ($sessions as $session ) {
              if( $session["day"] == 3 ){?>
                <p><?= $session["course"] ?>: <?= $session["time"] ?>
                <a class="btn btn-danger" href="?delete_session=<?= $session["id"] ?>">حذف</a></p>
            <?php }} ?>
          </li>
          <li class="list-group-item">الأربعاء:
            <?php foreach ($sessions as $session ) {
              if( $session["day"] == 4 ){?>
                <p><?= $session["course"] ?>: <?= $session["time"] ?>
                <a class="btn btn-danger" href="?delete_session=<?= $session["id"] ?>">حذف</a></p>
            <?php }} ?>
          </li>
          <li class="list-group-item">الخميس:
            <?php foreach ($sessions as $session ) {
              if( $session["day"] == 5 ){?>
                <p><?= $session["course"] ?>: <?= $session["time"] ?>
                <a class="btn btn-danger" href="?delete_session=<?= $session["id"] ?>">حذف</a></p>
            <?php }} ?>
          </li>

        </ul>
  </div>




  <!-- Scripts -->
  <!-- Bootstrap Bundle  -->
  <!-- cdn -->
  <!-- <script
      src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
      integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
      crossorigin="anonymous"
    ></script> -->
  <!-- local -->
  <script src="./src/js/jquery.dataTables.min.js"></script>
  <script src="./src/js/bootstrap.bundle.min.js"></script>
  <script src="./src/datatables.min.js"></script>
  <script src="./src/js/main.js"></script>
  <script>
    // new DataTable('#students');
  </script>
</body>

</html>