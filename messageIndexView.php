<?PHP
header("Content-Type:text/html; charset=utf-8");
echo '<script src="jquery.min.js"></script>';
$html = <<<HTML
    <form method="post">
        <input type ="hidden" name="method" value="insert" />
        <table class="table">
            <tr>
                <td  align="right">username</td>
                <td>
                    <input type="text" name="username" size="10" />
                </td>
            </tr>
            <tr>
                <td  align="right">title</td>
                <td>
                    <input type="text" name="title" size="20" />
                </td>
            </tr>
            <tr>
                <td  align="right">message</td>
                <td>
                    <textarea rows="4" cols="50" name ="message"></textarea>
                </td>
            </tr>
            <tr>
                <td colspan="2" align="center">
                                                            一次新增<input type="text" name="times" value=1 size="2" />筆
                    <button type="submit" class="btn" >submit</button>
                </td>
            </tr>
        </table>
    </form>
HTML;

echo $html;

$htmlRows = <<<HTML
    <form method="post" id="bulkForm">
    <input type ="hidden" name="method" id="method" value="" />
    <table class="table">
        <tr>
            <td></td>
            <td whdth="20px" >username</td>
            <td width="200px" align="center">title</td>
            <td>update time</td>
            <td>del</td>
        </tr>
HTML;
if (is_array($rows) && count($rows) >= 1) {
    foreach ($rows as $row){
        $htmlRows .= <<<HTML
			<tr>
			    <td><input type="checkbox" name="ids[]" value="{$row['id']}" /></td>
				<td>{$row['name']}</td>
				<td align="center"><a href='/message.php?method=content&id={$row['id']}'>{$row['title']}</a></td>
				<td>{$row['updateTime']}</td>
				<td> <input type="button" onclick="javascript:location.href='/message.php?method=del&id={$row['id']}'" class="btn" value="DEL"/></td>
			</tr>
HTML;
    }
}

$htmlRows .= <<<HTML
        <tr>
            <td colspan="5" align="right">
                <input type="button" value="批次更新" onclick="bulkUpdate()" />
                <input type="button" value="批次刪除" onclick="bulkDel()" />
            </td>
        </tr>
    </table>
    </form>
HTML;

echo $htmlRows;

echo " <br/> 頁數 : ";

for ($i = 1 ; $i <= $totalPage ; $i++) {
	if ($i == $page){
		echo "&nbsp<span style='font-weight:bold;'>$i</span>&nbsp";
	}else{
		echo "&nbsp<a href='/message.php?page=$i'>$i</a>&nbsp";
	}
}

$js = <<<JavaScript
<script type="text/javascript">

    function bulkUpdate(){
        $('#method').val('bulkUpdateView');
        $('#bulkForm').submit();
    }

    function bulkDel(){
        $('#method').val('bulkDel');
        $('#bulkForm').submit();
    }

</script>
JavaScript;

echo $js;



