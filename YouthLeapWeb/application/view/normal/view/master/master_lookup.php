<section class="container">
	<?php $mBreadcrumb->render(); ?>
  
  <form id="search_form" action="master/lookup" class="search-form form-inline text-right" role="form" method="post">
    <div class="form-group input-group input-icon left">
        <i class="icon-magnifier"></i>
        <?php $this->search->input("search_string", array("class" => "input-circle-left", "placeholder" => _l("Search..."), "maxlength" => "50")); ?>
    </div>
    <a href="master/lookup_edit//?callback=on_update" class="btn btn-default fancybox" fancy-width="450" fancy-height="320" title="Edit"><i class="icon-plus"></i> Add</a>
  </form>
	<div class="row">
		<div class="col-md-12">
				<div class="portlet light ">
					<div class="portlet-title">
						<div class="caption caption-md">
							<i class="icon-globe theme-font hide"></i>
							<span class="caption-subject font-blue-madison bold uppercase">Lookup List</span>
						</div>
          </div>
					<div class="portlet-body">
            <div class="row">
            <?php $this->pagebar->display(_url("master/lookup/")); ?>
            <table class="table table-striped table-hover table-bordered">
              <thead>
                <tr>
                  <th class="td-no">#</th>
                  <th>Name</th>
                </tr>
              </thead>
              <tbody>
              <?php
                $i = $this->pagebar->start_no();
                foreach ($mLookupMasters as $master) {
              ?>
                <tr>
                  <td><?php p($i); ?></td>
                  <td>
                    <?php $master->detail_class('displayName'); ?>
                    <a href="master/lookup_edit/<?php p($master->lookup_id);?>?callback=on_update" class="fancybox" fancy-width="450" fancy-height="320" title="Edit"><i class="icon-note"></i></a>
                    <a class="btn_lookup_remove" lookup_id="<?php p($master->lookup_id);?>" lookup_name="<?php p($master->displayName);?>" title="Remove"><i class="ln-icon-trash2"></i></a>
                  </td>
                </tr>
              <?php
                  $i ++;
                }
              ?>
              </tbody>
            </table>
            <?php _nodata_message($mLookupMasters); ?>              
            <?php $this->pagebar->display(_url("master/lookup/")); ?>
            </div>
            <div class="button-bar">
              <a href="student/index/1" class="btn btn-default"> Cancel </a>
            </div>
					</div>
				</div>
		</div>
	</div>
</section>