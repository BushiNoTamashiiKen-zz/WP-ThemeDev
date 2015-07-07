<?php get_header(); ?>
<!-- content.php -->
<section id="posts" data-type="background" data-speed="15">
	<div class="topAngle"></div>
	<article class="aboutWrapper">
		<div class="about">
			<h2><em>ABOUT</em></h2>
			<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean sollicitudin libero quis nisi interdum. 
			At ultrices dui interdum. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed quis lorem non ipsum sodales iaculis. Praesent rhoncus pulvinar dolor sit amet pretium. Nam aliquet vehicula accumsan.
			Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean sollicitudin libero quis nisi interdum. 
			</p>
			<a id="contact" href="#third"><em>CONTACT ME</em></a>
		</div>
		<div class="avatar"><img src="images/avatar2.png"></div>
	</article>
</section>

<header id="header">
	<div class="branding">
		<hr></hr>
		<div class="logo2"></div>
		<hr></hr>
	</div>
</header>
<div class="main">
	<div id="content">
		<section id="second" data-type="background" data-speed="10">
		<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
		<h4>Posted on <?php the_time('F jS, Y') ?></h4>
		<p><?php the_content(__('(more...)')); ?></p>
			<div class="col-1-2">
				<div id="postOverlay1" class="effects">
					<div class="postRow">
						<div class="postColumn">
							<img src="images/Bg_01.jpg">
							<div class="overlay">
								<a href="#" class="expand"><span>Saturday's Jeremiah CPO</span></a>
								<a class="close-overlay hidden">x</a>
							</div>
						</div>
					</div>
					<div class="postRow">
						<div class="postColumn-1-2">
							<img src="images/testPost_01.jpg">
							<div class="overlay">
								<a href="#" class="expand"><span>Saturday's Jeremiah CPO</span></a>
								<a class="close-overlay hidden">x</a>
							</div>
						</div>
						<div class="postColumn-2-2">
							<img src="images/Bg_01.jpg">
							<div class="overlay">
								<a href="#" class="expand"><span>Saturday's Jeremiah CPO</span></a>
								<a class="close-overlay hidden">x</a>
							</div>
						</div>
					</div>
					<div class="postRow">
						<div class="postColumn">
							<img src="images/Bg_01.jpg">
							<div class="overlay">
								<a href="#" class="expand"><span>Saturday's Jeremiah CPO</span></a>
								<a class="close-overlay hidden">x</a>
							</div>
						</div>
					</div>
					<div class="postRow">
						<div class="postColumn-2-2">
							<img src="images/Bg_01.jpg">
							<div class="overlay">
								<a href="#" class="expand"><span>Saturday's Jeremiah CPO</span></a>
								<a class="close-overlay hidden">x</a>
							</div>
						</div>
						<div class="postColumn-1-2">
							<img src="images/testPost_01.jpg">
							<div class="overlay">
								<a href="#" class="expand"><span>Saturday's Jeremiah CPO</span></a>
								<a class="close-overlay hidden">x</a>
							</div>
						</div>
					</div>
					<div class="postRow">
						<div class="postColumn">
							<img src="images/Bg_01.jpg">
							<div class="overlay">
								<a href="#" class="expand"><span>Saturday's Jeremiah CPO</span></a>
								<a class="close-overlay hidden">x</a>
							</div>
						</div>
					</div>
					<div class="postRow">
						<div class="postColumn-1-2">
							<img src="images/testPost_01.jpg">
							<div class="overlay">
								<a href="#" class="expand"><span>Saturday's Jeremiah CPO</span></a>
								<a class="close-overlay hidden">x</a>
							</div>
						</div>
						<div class="postColumn-2-2">
							<img src="images/Bg_01.jpg">
							<div class="overlay">
								<a href="#" class="expand"><span>Saturday's Jeremiah CPO</span></a>
								<a class="close-overlay hidden">x</a>
							</div>
						</div>
					</div>
					<div class="postRow">
						<div class="postColumn">
							<img src="images/Bg_01.jpg">
							<div class="overlay">
								<a href="#" class="expand"><span>Saturday's Jeremiah CPO</span></a>
								<a class="close-overlay hidden">x</a>
							</div>
						</div>
					</div>
				</div>
				<?php endwhile; else: ?>
				<p><?php _e('Sorry, no posts matched your criteria.'); ?></p><?php endif; ?>
			</div>
			<?php get_sidebar(); ?>
		</div>
		<?php get_footer(); ?>