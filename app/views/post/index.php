<!-- Posts section-->
<section id="posts" class="py-5">
    <div class="container px-5 my-5">
        <div class="row gx-5 justify-content-center">
            <div class="col-lg-8 col-xl-6">
                <div class="text-center">
                    <h2 class="fw-bolder">Articles</h2>
                    <p class="lead fw-normal text-muted mb-5">Lorem ipsum, dolor sit amet consectetur adipisicing elit. Eaque fugit ratione dicta mollitia. Officiis ad.</p>
                </div>
            </div>
        </div>
        <div class="row gx-5">

            <?php
                foreach ($posts as $post):
                    require VIEWS . '/post/_post_card.php';
				endforeach;
            ?>

        </div>
    </div>
</section>
