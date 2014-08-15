<ul class="nav nav-pills">
	<li <?php echo $this->uri->segment(4) == '' ? 'class="active"' : '' ?>>
		<a href="<?php echo site_url(SITE_AREA .'/developer/gallery') ?>" id="list"><?php echo lang('gallery_list'); ?></a>
	</li>
	<?php if ($this->auth->has_permission('Gallery.Developer.Create')) : ?>
	<li <?php echo $this->uri->segment(4) == 'create' ? 'class="active"' : '' ?> >
		<a href="<?php echo site_url(SITE_AREA .'/developer/gallery/create') ?>" id="create_new"><?php echo lang('gallery_new'); ?></a>
	</li>
	<?php endif; ?>
</ul>