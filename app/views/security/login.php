<section id="login" class="py-5 mt-5">
    <div>
        <div class="login card col-10 col-md-6 col-lg-5 p-5 mt-5 mx-auto justify-content-center">
            <h1 class="login__title text-center mb-4" >Se connecter</h1>
            <div class="alert alert-danger d-flex align-items-center mb-2 <?php if($form->error == true): echo '';  else: echo 'd-none'; endif; ?>" role="alert"><?= ($form->error == true) ? $form->error : '' ?></div>
	        <form id="loginForm" action="<?= F_LOGIN ?>" method="POST">
                <div class="form-floating">
                    <input type="email" name="email" class="form-control" placeholder="email" value="<?= ($form->error == true) ? $form->email : '' ?>" required>
                    <label>Email</label>
                </div>
                <div class="form-floating mt-2">
                    <input type="password" name="password" class="form-control" placeholder="mot de passe" value="" required>
                    <label>Mot de passe</label>
                </div>
<!--                TODO: Remember me-->
<!--                <div class="checkbox mt-3">-->
<!--                    <label>-->
<!--                        <input type="checkbox" value="remember-me"> Se souvenir de moi-->
<!--                    </label>-->
<!--                </div>-->
                <input type="hidden" name="csrfToken" value="<?= $form->csrfToken ?>">
                <div class="col-lg-12 text-center mt-5">
                    <button class="btn btn-primary-color" name="submit" type="submit">
                        Se connecter
                    </button>
                </div>
	        </form>
        </div>
    </div>
</section>