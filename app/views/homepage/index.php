<!-- Header-->
<header id="home" class="bg-dark py-5">
    <div class="container px-5">
        <div class="row gx-5 align-items-center ">
            <div class="col-lg-7 mt-5 justify-content-start">
                <div class="my-5 text-xl-start">
                    <h1 class="display-6 fw-bolder text-white mb-2">Développeuse Web PHP/Symfony </h1>
                    <p class="lead fw-normal text-white-50 mb-4">En formation...</p>
                    <div class="d-grid gap-3 d-sm-flex">
                        <a class="btn btn-outline-light btn-hover-primary-color btn-lg px-4 me-sm-3" href="<?= FILES . '/cv_magali_thuaire.pdf' ?>" download>
                            <i class="fas fa-file-download icon"></i>
                            Mon CV
                        </a>
                        <a class="btn btn-outline-light btn-hover-primary-color btn-lg px-4" href="<?= R_CONTACT ?>">
                            <i class="fas fa-paper-plane icon"></i>
                            Me contacter
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
<!-- Contact section-->
<section id="contact" class="py-5">
    <div class="container px-5 my-5">
        <div class="row gx-5 justify-content-center">
            <div class="col-lg-8 col-xl-6">
                <div class="text-center">
                    <h2 class="fw-bolder text-uppercase">Me contacter</h2>
                    <p class="lead fw-normal text-muted mb-5">Lorem ipsum, dolor sit amet consectetur adipisicing elit. Eaque fugit ratione dicta mollitia. Officiis ad.</p>
                </div>
            </div>
        </div>
        <div class="row mt-2">
            <div class="col-lg-6 mb-4 mb-lg-0">
                <div class="card shadow border-0">
                    <div class="card-body p-4">
                        <a class="text-decoration-none link-dark stretched-link d-flex justify-content-start" href="#!">
                            <div class="icon-social">
                                <i class="fab fa-linkedin-in"></i>
                            </div>
                            <div class="text-muted d-inline-block">
                                <span class="title-social">Linkedin</span>
                                <p class="text-muted">linkedin.com/in/thuaire</p>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="card shadow border-0 mt-2">
                    <div class="card-body p-4">
                        <div class="text-decoration-none link-dark stretched-link d-flex justify-content-start">
                            <div class="icon-social">
                                <i class="fas fa-street-view"></i>
                            </div>
                            <div class="text-muted d-inline-block">
                                <span class="title-social">Mon secteur</span>
                                <p class="text-muted">Dijon, Bourgogne Franche-Comté</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 mt-lg-1">
                <form id="contactForm" data-href="<?= F_CONTACT ?>" method="POST">
                    <div class="alert d-flex align-items-center js-form-message d-none" role="alert"></div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group mb-4">
                                <input class="form-control mb-4" type="text" name="name" value="<?= ($form->error == true) ? $form->name : '' ?>" placeholder="Votre nom*" required>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="form-group mb-4">
                                <input class="form-control" type="email" name="email" value="<?= ($form->error == true) ? $form->email : '' ?>" placeholder="Votre email*" required>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="form-group mb-4">
                                <textarea class="form-control" name="message" placeholder="Votre message ..." rows="10" required><?= ($form->error == true) ? $form->message : '' ?></textarea>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="csrfToken" value="<?= $form->csrfToken ?>">
                    <div class="row">
                        <div class="col-lg-12 text-center">
                            <button class="btn btn-primary-color" name="submit" type="submit">
                                Envoyer votre message
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>