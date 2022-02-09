<article class="blog-post-comment mt-3">
    <div class="container blog-post-comment__container">
        <div class="row align-items-center">
            <div class="col-4 col-md-2 text-center blog-post-comment__aside">
                <p class="text-capitalize mb-0"><strong>--<?= $comment->author ?></strong></p>
                <p>le <?= $comment->createdAtFormatted ?></p>
            </div>
            <div class="col blog-post-comment__body">
                <div class="p-3">
                    <p><?= $comment->content ?></p>
                </div>
            </div>
        </div>
    </div>
</article>