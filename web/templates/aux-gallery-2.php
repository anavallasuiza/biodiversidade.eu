<div class="media">
	<div class="galeria">
		<div class="main-imaxe">
			<figure>
				<a href="http://lorempixum.com/600/400/people" class="gallery" rel="gallery-a">
					<?php
					echo $Html->img(array(
						'src' => 'http://lorempixum.com/600/400/nature',
						'transform' => 'resizeCrop, 600,400',
					));
					?>
				</a>
			</figure>
		</div>

		<div class="thumbnails">
			<ul id="carousel" class="elastislide-list">
				<li>
					<a href="<?php echo $Html->imgSrc('templates|images/1.jpg', 'resizeCrop, 600,600'); ?>">
						<?php
						echo $Html->img(array(
							'src' => 'http://lorempixum.com/200/200/people',
							'transform' => 'resizeCrop, 200,200'
						));
						?>
					</a>
				</li>		
				<li>
					<a href="<?php echo $Html->imgSrc('templates|images/1.jpg', 'resizeCrop, 600,600'); ?>">
						<?php
						echo $Html->img(array(
							'src' => 'http://lorempixum.com/200/200/people',
							'transform' => 'resizeCrop, 200,200'
						));
						?>
					</a>
				</li>		
				<li>
					<a href="<?php echo $Html->imgSrc('templates|images/1.jpg', 'resizeCrop, 600,600'); ?>">
						<?php
						echo $Html->img(array(
							'src' => 'http://lorempixum.com/200/200/people',
							'transform' => 'resizeCrop, 200,200'
						));
						?>
					</a>
				</li>
				<li>
					<a href="<?php echo $Html->imgSrc('templates|images/1.jpg', 'resizeCrop, 600,600'); ?>">
						<?php
						echo $Html->img(array(
							'src' => 'http://lorempixum.com/200/200/people',
							'transform' => 'resizeCrop, 200,200'
						));
						?>
					</a>
				</li>
			</ul>
		</div>
	</div>
</div>