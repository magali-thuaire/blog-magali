<article class="mb-5 blog-post">
    <div class="container blog-post__container p-4">
        <div class="row">
            <div class="col-4 col-md-2 text-center blog-post__aside">
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
                <div class="p-3 blog-post__header">
                    <i class="fa fa-quote-left mr-5"></i>
                    <p><?= $post->header ?></p>
                </div>
                <div class="text-end text-muted text fst-italic mt-2">
                    <small>
                        <?php
                        if ($post->updatedAtFormatted) :
                            echo 'Dernière mise à jour : ' . $post->updatedAtFormatted;
                        endif;
                        ?>
                    </small>
                </div>
            </div>
        </div>
    </div>
    <a class="text-decoration-none" href="<?= R_POST . $post->id ?>">
        <p class="blog-post__comment text-center p-3">
            <i class="fa fa-comments blog-post__comment-icon"></i> <?= count($post->comments) ?> commentaires
        </p>
    </a>
</article>