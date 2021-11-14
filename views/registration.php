<?php
use app\core\Application;

/** @var $params \app\models\RegistrationModel */


if (Application::getApp()->getSession()->getFlash("success")) {
    $message = Application::getApp()->getSession()->getFlash("success");
    echo "<script>";
    echo "toastr.success('$message')";
    echo "</script>";
}

if (Application::getApp()->getSession()->getFlash("error")) {
    $message = Application::getApp()->getSession()->getFlash("error");
    echo "<script>";
    echo "toastr.error('$message')";
    echo "</script>";
}
?>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-4 col-md-6 d-flex flex-column align-items-center justify-content-center">

            <div class="d-flex justify-content-center py-4">
                <a href="index.html" class="logo d-flex align-items-center w-auto">
                    <img src="assets/img/logo.png" alt="">
                    <span class="d-none d-lg-block">Smartphone Market</span>
                </a>
            </div><!-- End Logo -->

            <div class="card mb-3">

                <div class="card-body">

                    <div class="pt-4 pb-2">
                        <h5 class="card-title text-center pb-0 fs-4">Create an Account</h5>
                        <p class="text-center small">Enter your personal details to create account</p>
                    </div>

                    <form method="post" action="registrationProcess" class="row g-3 needs-validation" novalidate>
                        <div class="col-12">
                            <label for="inputEmail4" class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" id="email" required>
                            <div class="invalid-feedback">Please enter a valid Email adddress!</div>
                            <?php
                            //ispis gresaka ispod inputa za unos email-a korisnika
                            if ($params->errors !== null)
                            {
                                echo "<ul>";
                                foreach ($params->errors['email'] as $errorMessage)
                                    echo "<li class='text-danger'>$errorMessage</li>";
                                echo "</ul>";
                            }
                            ?>

                        </div>

                        <div class="col-12">
                            <label for="yourPassword" class="form-label">Password</label>
                            <input type="password" name="password" class="form-control" id="yourPassword" required>
                            <div class="invalid-feedback">Please enter your password!</div>
                            <?php
                            //ispis gresaka ispod inputa za unos lozinke korisnika
                            //za buduci edit: sa css-om ukloniti default tacke iz liste i margine
                            if ($params->errors !== null)
                            {
                                echo "<ul>";
                                foreach ($params->errors['password'] as $errorMessage)
                                    echo "<li class='text-danger'>$errorMessage</li>";
                                echo "</ul>";
                            }
                            ?>
                        </div>

                        <div class="col-12">
                            <label for="yourPhone" class="form-label">Phone Number</label>
                            <input type="tel" name="phone_number" class="form-control" id="phone_number" required>
                            <div class="invalid-feedback">Please enter your phone number!</div>
                            <?php
                            //ispis gresaka ispod inputa za unos email-a korisnika
                            if ($params->errors !== null)
                            {
                                echo "<ul>";
                                foreach ($params->errors['phone_number'] as $errorMessage)
                                    echo "<li class='text-danger'>$errorMessage</li>";
                                echo "</ul>";
                            }
                            ?>
                        </div>

                        <div class="col-12">
                            <button class="btn btn-primary w-100" type="submit">Create Account</button>
                        </div>
                        <div class="col-12">
                            <p class="small mb-0">Already have an account? <a href="login">Log in</a></p>
                        </div>
                    </form>

                </div>
            </div>

            <div class="credits">
                <!-- All the links in the footer should remain intact. -->
                <!-- You can delete the links only if you purchased the pro version. -->
                <!-- Licensing information: https://bootstrapmade.com/license/ -->
                <!-- Purchase the pro version with working PHP/AJAX contact form: https://bootstrapmade.com/nice-admin-bootstrap-admin-html-template/ -->
                Designed by <a href="https://bootstrapmade.com/">BootstrapMade</a>
            </div>

        </div>
    </div>
</div>
