<?PHP
header("Content-Type:text/html; charset=utf-8");
$html_rows = '
    <h2>批次更新</h2>
    <form method="post">
        <input type="hidden" name="method" value="bulkUpdate" />
        <table class="table">';

foreach ($rows as $row){

    $html_rows .= <<<HTML

        <input type ="hidden" name="ids[]" value="{$row['id']}" />
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
                <textarea rows="4" cols="50" name ="message_{$row['id']}">{$row['message']}</textarea>
            </td>
        </tr>
        <tr>
            <td colspan="2" align="center">
                <hr></hr>
            </td>
        </tr>
HTML;

}

$html_rows .= '
    <tr>
        <td colspan="2">
            <input type="submit" value="送出"/>
        </td>
    </tr>
    </table>
        </form>
';

echo $html_rows;



