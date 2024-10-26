<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

  <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="style.css">

  <title>Document</title>
</head>

<body>

  <header>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
      <div class="container-fluid">
        <a class="navbar-brand" href="index.php">
          <img src="logod.png" alt="Logo" style="height: 50px;">
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
              <span class="navbar-toggler-icon"></span>
            </button>
        <div class="collapse navbar-collapse" id="navbarNav">
          <ul class="navbar-nav ms-auto">
            <li class="nav-item">
              <a class="nav-link" href="index.php?page=shop"> المتجر <img class="tab-icon" src="assets/icons/stored.png"
                  alt=""></a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="index.php?page=dashboard">dashboard<img class="tab-icon"
                  src="assets/icons/stored.png" alt=""></a>
            </li>
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" id="categoriesDropdown" role="button"
                data-bs-toggle="dropdown" aria-expanded="false">
                كل اصناف
                <img class="tab-icon" src="assets/icons/category.png" alt="">
              </a>
              <ul class="dropdown-menu" aria-labelledby="categoriesDropdown">
                <li><a class="dropdown-item" href="index.php?page=categories&category=2">سخان ماء غازي <img
                      class="drop-tab-icon" src="assets/icons/heaterd.png" alt=""></a></li>
                <li><a class="dropdown-item" href="index.php?page=categories&category=3">فرن كهربائي <img
                      class="drop-tab-icon" src="assets/icons/ovend.png" alt=""></a></li>
                <li><a class="dropdown-item" href="index.php?page=categories&category=1">طحانة كهربائية <img
                      class="drop-tab-icon" src="assets/icons/blenderd.png" alt=""></a></li>
                <!-- Add more categories as needed -->
              </ul>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="index.php?page=contact">إتصل بنا <img class="tab-icon"
                  src="assets/icons/call.png" alt=""></a>
            </li>
          </ul>
        </div>
      </div>
    </nav>
  </header>



</body>

</html>