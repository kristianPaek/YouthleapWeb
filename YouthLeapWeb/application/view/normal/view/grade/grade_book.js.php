<script type="text/javascript">

$(function () {
  var fcBody = document.querySelector(".fix-column > .tbody"),
      rcBody = document.querySelector(".rest-columns > .tbody"),
      rcHead = document.querySelector(".rest-columns > .thead");
  rcBody.addEventListener("scroll", function() {
      fcBody.scrollTop = this.scrollTop;
      rcHead.scrollLeft = this.scrollLeft;
  }, { passive: true });
});
</script>