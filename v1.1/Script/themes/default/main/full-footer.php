<!-- Footer  -->
    <footer id="footer" class="page_footer">
        <div class="footer-copyright">
            <div class="container valign-wrapper">
                <span><?php echo __( 'Copyright' );?> © <?php echo date( "Y" ) . " " . ucfirst( $config->site_name );?>. <?php echo __( 'All rights reserved' );?>.</span>
				<span class="dt_fotr_spn">
				<ul class="dt_footer_links">
					<li><a href="<?php echo $site_url;?>/about" data-ajax="/about"><?php echo __( 'About Us' );?></a></li>
					&nbsp;-&nbsp;<li><a href="<?php echo $site_url;?>/terms" data-ajax="/terms"><?php echo __( 'Terms' );?></a></li>
                    &nbsp;-&nbsp;<li><a href="<?php echo $site_url;?>/privacy" data-ajax="/privacy"><?php echo __( 'Privacy Policy' );?></a></li>
					&nbsp;-&nbsp;<li><a href="<?php echo $site_url;?>/contact" data-ajax="/contact"><?php echo __( 'Contact' );?></a></li>
				</ul>
                <?php require( $theme_path . 'main' . $_DS . 'language.php' );?>
				</span>
            </div>
        </div>
    </footer>
<!-- End Footer  -->