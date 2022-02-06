<div class="col-lg-4 mb-5">
	<div class="card h-100 shadow border-0">
		<div class="card-body p-4">
			<a class="text-decoration-none link-dark stretched-link" href="<?= R_POST . $post->id ?>">
                <h5 class="card-title mb-3">
                    <?= $post->title ?>
                </h5>
            </a>
			<p class="card-text mb-0">
				<?= $post->header ?>
            </p>
		</div>
		<div class="card-footer p-4 pt-0 bg-transparent border-top-0">
			<div class="d-flex align-items-end justify-content-between">
				<div class="d-flex align-items-center">
					<img class="rounded-circle me-3" src="https://dummyimage.com/40x40/ced4da/6c757d" alt="..." />
					<div class="small">
						<div class="fw-bold">
                            <?= $post->author ?>
                        </div>
						<div class="text-muted">
							<?= $post->updatedAtFormatted ?? $post->publishedAtFormatted ?>
                        </div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>