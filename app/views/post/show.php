<!-- Header-->
<header id="blog" class="bg-dark py-5"></header>

<!-- Posts section-->
<section id="posts" class="py-5">
    <div class="container px-5 my-5">
       <div class="row gx-5 justify-content-center">
           <div class="col-8">
               <article class="mb-4 blog-post card p-4">
                   <h3>
						   <?= $post->title ?>
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
               <?php
               if(current($post->comments)):
                   foreach ($post->comments as $comment):
                       require VIEWS . '/post/_comment.php';
                   endforeach;
               endif;
               ?>
           </div>
       </div>
    </div>
</section>
