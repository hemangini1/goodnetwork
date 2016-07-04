<p class="alignleft"><a href="<?php printf( '%1$s/inc/setup/images/setup-content.gif', get_template_directory_uri() ); ?>">
	<img src="<?php printf( '%1$s/inc/setup/images/setup-content.gif', get_template_directory_uri() ); ?>" width="430" alt=""/>
</a></p>

<p><?php _e( 'Installing the demo content is not required to use this theme. It is simply meant to provide a way to get a feel for the theme without having to manually set up all of your own content. <strong>If you choose not to import the demo content you need to make sure you manually create all necessary page templates for your website.', 'jobify' ); ?></strong></p>

<p><?php _e( 'The Jobify theme package includes multiple demo content .XML files. This is what you will upload to the WordPress importer. Depending on the plugins you have activated or the intended use of your website you may not need to upload all .XML files.', 'jobify' ); ?></p>

<p><a href="<?php echo admin_url( 'import.php' ); ?>" class="button button-primary button-large"><?php _e( 'Begin Importing Content', 'jobify' ); ?></a></p>

<hr />

<p><?php _e( 'For more help regarding this step, please see', 'jobify' ); ?>:</p>

<p>
	<a href="http://jobify.astoundify.com/" class="button-secondary"><?php _e( 'Full Documentation', 'jobify' ); ?></a>&nbsp;
	<a href="http://jobify.astoundify.com/article/380-installing-demo-content" class="button-secondary"><?php _e( 'Install Demo Content', 'jobify' ); ?></a>&nbsp;
	<a href="http://codex.wordpress.org/Importing_Content" class="button-secondary"><?php _e( 'Import Content (Codex)', 'jobify' ); ?></a>&nbsp;
	<a href="https://wordpress.org/plugins/wordpress-importer/" class="button-secondary"><?php _e( 'WordPress Importer', 'jobify' ); ?></a>&nbsp;
</p>
