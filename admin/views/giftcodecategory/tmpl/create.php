<?php defined('_JEXEC') or die('Restricted access'); ?>
<form action="index.php" method="post" name="adminForm" enctype="multipart/form-data">
    <table class="adminlist" >
        <tr>
            <td colspan="3"><img src="./components/com_awardpackage/asset/giftcodecategory/<?php echo $this->data->image ?>" width="60px" height="30px"/></td>
        </tr>
        <tr>
            <td>Category Name</td>
        	<td>:</td>
            <td><?php echo $this->data->name?></td>
        </tr>
        <tr>
            <td>Published</td>
            <td>:</td>
            <td>
        	<select name="published" id="published">
        			<option <?php if($this->data->published == '1') echo 'selected="selected"' ?> value="1"/>Publish</option>
        			<option <?php if($this->data->published == '0') echo 'selected="selected"' ?> value="0"/>Unpublish</option>
        		</select>
        	</td>
        </tr>
    </table>
    <input type="hidden" name="id" value="<?php echo $this->data->id?>" />
    <input type="hidden" name="image" value="<?php echo $this->data->image?>" />
    <input type="hidden" name="option" value="com_awardpackage" />
    <input type="hidden" name="task" value="" />
    <input type="hidden" name="controller" value="giftcodecategory" />
</form>