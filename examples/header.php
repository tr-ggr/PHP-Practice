<header class="p-3 text-bg-dark">
    <div class="container">
      <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start">
        <a href="/" class="d-flex align-items-center mb-2 mb-lg-0 text-white text-decoration-none">
          <svg class="bi me-2" width="40" height="32" role="img" aria-label="Bootstrap"><use xlink:href="#bootstrap"></use></svg>
        </a>

        <ul class="nav col-12 col-lg-auto me-lg-auto mb-2 justify-content-center mb-md-0">
          <li><a href="#" class="nav-link px-2 text-secondary">Home</a></li>
          <li><a href="#" class="nav-link px-2 text-white">Posts</a></li>
        </ul>

        <div class>
            <?php
                echo 'Hello! <b>'.$_GET['username'].'</b>';
            ?>
        </div>


        <form method = "post" style="margin: 10px;">
            <input  type="submit" id="sign-up" name="logout" value="logout" class="btn btn-danger">
        </form>
</div>

           

  </header>