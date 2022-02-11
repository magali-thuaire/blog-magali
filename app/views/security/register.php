<section id="register" class="py-5 mt-5">
    <div>
        <div class="register card col-10 col-md-6 col-lg-5 p-5 mt-3 mx-auto justify-content-center">
            <h1 class="register__title text-center mb-4" >S'inscrire</h1>
            <div class="alert alert-danger d-flex align-items-center mb-2 <?php if($form->error == true): echo '';  else: echo 'd-none'; endif; ?>" role="alert"><?= ($form->error == true) ? $form->error : '' ?></div>
            <div class="alert alert-success d-flex align-items-center mb-2 <?php if($form->success == true): echo '';  else: echo 'd-none'; endif; ?>" role="alert"><?= ($form->success == true) ? $form->success : '' ?></div>
            <form id="registerForm" action="<?= F_REGISTER ?>" method="POST">
                <div class="form-floating">
                    <input type="text" name="username" class="form-control" placeholder="nom d'utilisateur" value="<?= ($form->error == true) ? $form->username : '' ?>" required>
                    <label>Nom d'utilisateur</label>
                </div>
                <div class="form-floating mt-2">
                    <input type="email" name="email" class="form-control" placeholder="email" value="<?= ($form->error == true) ? $form->email : '' ?>" required>
                    <label>Adresse email</label>
                </div>
                <div class="form-floating mt-2">
                    <input type="password" name="plainPassword" class="form-control" placeholder="mot de passe" required>
                    <label>Mot de passe</label>
                </div>
                <div class="form-floating mt-2">
                    <input type="password" name="plainPasswordConfirm" class="form-control" placeholder="confirmation du mot de passe" required>
                    <label>Confirmation du mot de passe</label>
                </div>
                <input type="hidden" name="csrfToken" value="<?= $form->csrfToken ?>">
                <div class="col-lg-12 text-center mt-5">
                    <button class="btn btn-primary-color" name="submit" type="submit">
                        S'inscrire
                    </button>
                </div>
	        </form>
        </div>
    </div>
</section>