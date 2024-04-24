<table style="margin-top:50px " width="100%" border="1" cellspacing="0" cellpadding="2">
    <tr>
        <th>Title</th>
        <th>Body</th>
    </tr>
    <tr>
        <? foreach ($results as $result) { ?>
        <td><? echo $result['title'] ?></td>
        <td><? echo $result['body'] ?></td>
    </tr>
    <? } ?>
</table>
