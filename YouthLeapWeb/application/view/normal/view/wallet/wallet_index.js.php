<script type="text/javascript">

$(function () {
  $('.btn-remove').click(function() {
      var wallet_id = $(this).attr("wallet_id");
      var user_token = "<?php p(_token());?>";
      confirmBox("Wallet Remove", "Do you want to remove this wallet?", function(note) {
        App.callAPI("api/wallet/remove",
          {
            wallet_id: wallet_id,
            user_token: user_token
          })
        .done(function(res) {
            alertBox("Wallet Remove", "wallet removed successfully", function() {
              location.reload();
            });
          })
          .fail(function(res) {
            errorBox("Remove Error", res.err_msg);
          });
      });
    });  
});
</script>