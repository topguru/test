<script type="text/javascript" src="./../administrator/components/com_awardpackage/shared/jquery.min.js"></script>
<script language="javascript">
$(function(){
 
    // add multiple select / deselect functionality
    $("#checkall-toggle").click(function () {
          $('.child').attr('checked', this.checked);
    });
 
    // if all checkbox are selected, check the selectall checkbox
    // and viceversa
    $(".child").click(function(){
 
        if($(".child").length == $(".child:checked").length) {
            $("#checkall-toggle").attr("checked", "checked");
        } else {
            $("#checkall-toggle").removeAttr("checked");
        }
 
    });
});
</script>