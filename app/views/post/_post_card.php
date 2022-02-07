<article class="mb-4 blog-post card p-4">
    <h3>
        <a class="text-decoration-none link-dark stretched-link" href="<?= R_POST . $post->id ?>">
            <?= $post->title ?>
        </a>
    </h3>
    <div class="d-flex justify-content-between text-muted">
        <div>
            <?= $post->publishedAtFormatted ?>
        </div>
        <div class="text-uppercase">AUTEUR <?= $post->author ?></div>
    </div>
    <p class="mt-4"><?= $post->header ?></p>
    <div class="text-end text-muted text fst-italic">
        <?php
            if($post->updatedAtFormatted):
              echo 'Dernière modification : '. $post->updatedAtFormatted;
            endif;
        ?>
    </div>
</article>