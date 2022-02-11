<!-- Post section-->
<section id="post" class="mt-5">
    <article class="py-5 blog-post">
        <div class="container blog-post__container p-4 my-2">
            <div class="row">
                <div class="col-12 col-md-2 text-center blog-post__aside">
                    <div class="mt-3">
                        <p class="mb-0">Publié le</p>
                        <p><?= $post->publishedAtFormatted ?></p>
                        <p class="pt-2 text-capitalize"><strong>--<?= $post->author ?></strong></p>
                    </div>
                </div>
                <div class="col blog-post__body">
                    <h3 class="blog-post__title">
                        <a class="text-decoration-none" href="<?= R_POST . $post->id ?>">
							<?= $post->title ?>
                        </a>
                    </h3>
                    <div class="p-3 blog-post__content">
                        <i class="fa fa-quote-left mr-5"></i>
                        <p><?= $post->header ?></p>
                        <p><?= $post->content ?></p>
                    </div>
                    <div class="text-end text-black-50 fst-italic mt-2">
                        <small>
							<?php
							if($post->updatedAtFormatted):
								echo 'Dernière mise à jour : '. $post->updatedAtFormatted;
							endif;
							?>
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </article>
</section>

<!-- Comments section-->
<section id="comments" class="p-5">
    <div class="px-4 pb-4 d-md-flex justify-content-between align-items-center">
        <h2>
            Commentaires
			<?= '(' . count($post->comments) . ')' ?>
        </h2>
        <a class="btn btn-primary-color mt-3 mt-md-0" data-bs-toggle="collapse" href="#collapseExample" role="button">Déposer un commentaire</a>
    </div>
    <div class="collapse mb-5" id="collapseExample">
        <div class="d-flex justify-content-center">
            <form id="commentForm" class="col-10 col-md-6 mt-4" data-href="<?= F_COMMENT . $post->id?>" method="POST">
                <div class="alert d-flex align-items-center js-form-message d-none" role="alert"></div>
                <div class="form-group mb-4">
                    <input class="form-control mb-4" type="text" name="author" value="<?= ($form->error == true) ? $form->author : '' ?>" placeholder="Nom d'utilisateur*" required>
                </div>
                <div class="form-group mb-4">
                    <textarea class="form-control" name="content" placeholder="Votre commentaire ..." rows="5" required><?= ($form->error == true) ? $form->content : '' ?></textarea>
                </div>
                <input type="hidden" name="csrfToken" value="<?= $form->csrfToken ?>">
                <button class="btn btn-primary-color" name="submit" type="submit">
                    Envoyer
                </button>
            </form>
        </div>
    </div>

    <?php
    if(count($post->comments)):
        foreach ($post->comments as $comment):
			require VIEWS . '/post/_comment.php';
        endforeach;
    endif;
    ?>
</section>