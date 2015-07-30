<?PHP
header("Content-Type:text/html; charset=utf-8");

$html_rows = '<table class="table">';
$html_rows .= <<<HTML
    <form method="post">
        <input type ="hidden" name="id" value="{$row['id']}" />
        <input type ="hidden" name="method" value="update" />
        <tr>
            <td  align="right">username</td>
            <td>{$row['name']}</td>
        </tr>
         <tr>
            <td  align="right">title</td>
            <td>{$row['title']}</td>
        </tr>
        <tr>
            <td  align="right">message</td>
            <td>
                <textarea rows="4" cols="50" name ="message">{$row['message']}</textarea>
            </td>
        </tr>
        <tr>
            <td colspan="2" align="center">
                <input type="button" onclick="javascript:location.href='/message.php'" value="back" />
                <button type="submit" class="btn" >修改內容</button>
                <input type="button" onclick="javascript:location.href='/message.php?method=del&id={$row['id']}'" class="btn" value="DEL"/>
            </td>
        </tr>
    </form>
HTML;

$html_rows .= '</table>';

echo $html_rows;



