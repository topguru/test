<?php
//print_r($this->data);exit();
defined('_JEXEC') or die('Restricted access');
?>
<div id="editcell">
    <form action="index.php" method="post" name="adminForm">
        <table class="adminlist" >
            <tr>
                <td style="width:200px">Prize Image</td>
            </tr>
            <tr>
                <td style="width:200px"><img src="" width="200px" height="300px"/></td>
            </tr>
            <tr>
                <td style="width:100px"><input type="submit" value="Browse & Upload"/></td>
            </tr>
            <tr>
                <td style="width:100px">Prize Name <input type="text"/></td>
            </tr>
            <tr>
                <td style="width:100px">Prize Value <input type="text"/></td>
            </tr>
            <tr>
                <td style="width:100px">Create By <input type="text" value="admin"/></td>
            </tr>
            <tr>
                <td style="width:100px">Created Date <input type="text" value="13-04-2011"/></td>
            </tr>
            <tr>
                <td style="width:100px">Description <textarea></textarea></td>
            </tr>
        </table>
        <br>
        <br>
        <input type="hidden" name="publish_setting_id" value="<? echo $this->data->publish_setting_id ?>" />
        <input type="hidden" name="code_setting_id" value="<? echo $this->data->code_setting_id ?>" />
        <input type="hidden" name="option" value="com_award" />
        <input type="hidden" name="task" value="" />
        <input type="hidden" name="controller" value="award" />
    </form>
    <br />
</div>
<br>
<br>