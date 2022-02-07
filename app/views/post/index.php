<!-- Header-->
<header id="blog" class="bg-dark py-5"></header>

<!-- Posts section-->
<section id="posts" class="py-5">
    <div class="container px-5 my-5">
       <div class="row gx-5 justify-content-center">
           <div class="col-8">
			   <?php
			   foreach ($posts as $post):
				   require VIEWS . '/post/_post_card.php';
			   endforeach;
			   ?>
           </div>
       </div>
    </div>
</section>
