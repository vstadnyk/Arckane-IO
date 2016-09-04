<a href="<? echo $this->data->href; ?>" class="title display-block align-center padding-10-20 size-14 color-inherit">
	<img class="row-100" src="<? echo $this->img->thumb($this->data->photo, 'crop/400/250'); ?>" alt="<? echo $this->data->name; ?>">
	<b class="display-block padding-10-0"><? echo $this->data->name; ?></b>
	<span class="display-block size-12"><? echo $this->data->announce; ?></span>
</a>