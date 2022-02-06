
<!doctype html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Magali</title>
        <link rel="icon" type="image/x-icon" href="<?= FAVICON ?>" />
        <link href="<?= CSS ?>" rel="stylesheet" type="text/css">
        <script type="text/javascript" src="<?= JS ?>" defer></script>
        <script></script>
    </head>
    <body data-bs-spy="scroll" data-bs-target="#navbarNav" class="d-flex flex-column h-100" data-bs-offset="70">
        <main class="flex-shrink-0">
            <!-- Navigation-->
            <nav class="navbar navbar-expand-lg sticky-top navbar-dark bg-dark" role="navigation">
                <div class="container-fluid px-5">
                    <a class="navbar-brand logo" href="<?= R_HOMEPAGE ?>">
                        <span class="text-info">Magali</span>
                        THUAIRE
                    </a>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarNav">
                        <div class="navbar-nav ms-auto mb-2 mb-lg-0">
                            <a class="nav-link" href="<?= R_HOMEPAGE ?>">Accueil</a>
                            <a class="nav-link" href="<?= R_BLOG ?>">Mon blog</a>
                            <a class="nav-link" href="<?= R_CONTACT ?>">Contact</a>
                        </div>
                    </div>
                </div>
            </nav>
            <?= $content ?>
        </main>

        <a aria-label="Scroll to the top of the page" href="#" id="scroll-top" class="scroll-top-right">
            <i class=" fa fa-angle-up" aria-hidden="true" role="img"></i>
        </a>

        <!-- Footer-->
        <footer class="bg-dark py-4 mt-auto">
            <div class="container px-5">
                <div class="row align-items-center justify-content-between flex-column flex-sm-row">
                    <div class="col-auto"><div class="small m-0 text-white">Copyright &copy; Magali 2022</div></div>
                </div>
            </div>
        </footer>
    </body>
</html>
