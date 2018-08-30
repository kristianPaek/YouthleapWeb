<section class="container">
	<?php $mBreadcrumb->render(); ?>
  
	<div class="row">
    <div class="col-md-12">
      <div class="portlet light ">
        <form role="form" action="api/wallet/save" id="form_common" class="horizontal-form" method="post">
          <?php $mWallet->hidden("wallet_id"); ?>
          <input type="hidden" id="user_token" name="user_token" value="<?php p(_token());?>" />
          <div class="portlet-body">
            <div class="form-group">
              <label for="points">Points</label>
              <?php $mWallet->input("points"); ?>
            </div>
            <div class="form-group">
              <label for="subject">Trasaction Type</label>
              <?php $mWallet->radio("transaction_type_id", CODE_TRANSTYPE); ?>
            </div>
            <div class="form-group">
              <label for="purpose_id">Purpose</label>
              <?php $mWallet->select_model("purpose_id", new sublookupModel(_db_options()), "lookup_id", "displayName", null, array("where"=>"parent_id = 3")); ?>
            </div>
            <div class="form-group">
              <label for="transaction_date">Transaction Date</label>
              <?php $mWallet->datebox("transaction_date"); ?>
            </div>
          </div>
          <div class="portlet-footer">
            <button type="submit" class="btn btn-primary"> Save </button>
            <a href="wallet/index" class="btn btn-default"> Cancel </a>
          </div>
        </form>
      </div>
    </div>
	</div>
	</div>
</section>