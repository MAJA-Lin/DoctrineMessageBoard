<?PHP
header("Content-Type:text/html; charset=utf-8");

require_once __DIR__ . '/dbDoctrine.php';

spl_autoload_register("autoload");

try {

    $method = 'index';

    if (isset($_REQUEST['method'])){

        $method = escapeString($_REQUEST['method']);

        if (!function_exists($method)) {
            throw new Exception('function not exists : ' . $method);
        }
    }

    $method($em);

}catch (Exception $e){
    error_log(__FILE__ . ' ' . $e->getMessage());
    echo $e->getMessage();
}

/**
 * 首頁
 *
 * @param EntityManager $em db連線
 */
function index($em){

    $pageLimit = 10;

    try {
        // 取得總頁數
        $totalPage = $em->getRepository('Board')->getTotalPage($pageLimit);

        // 取得當下頁數, 預設為1
        $page = 1;

        if (isset($_GET['page'])) {
            if (!is_numeric($_GET['page'])) {
                throw new Exception('取得頁數不為數字 : ' . $_GET['page']);
            }
            $page = (int)$_GET['page'];
        }

        // 計算起始資料為第幾筆
        $start = bcmul($pageLimit, bcsub($page, 1));

        $datas = $em->getRepository('Board')->findBy([], ['updateTime'=>'desc'], $pageLimit, $start);

        if (is_array($datas) && count($datas) >= 1) {
            foreach ($datas as $key => $val) {
                $rows[] = [
                    'id'          => $val->getId(),
                    'name'        => $val->getName(),
                    'title'       => $val->getTitle(),
                    'updateTime'  => $val->getUpdateTime()->format('Y-m-d H:i:s')
                ];
            }
        }

        require_once 'messageIndexView.php';

    }catch (Exception $e){
        error_log(__FILE__ . ' function : ' . __FUNCTION__ . ' ' . $e->getMessage());
        echo $e->getMessage();
    }
}

/**
 * 訊息內容頁
 *
 * @param EntityManager $em db連線
 */
function content($em){

    try {

        if (!isset($_GET['id'])) {
            throw new Exception('無法取得留言編號');
        }

        if (!is_numeric($_GET['id'])) {
            throw new Exception('取得編號不為數字 : '.$_GET['id']);
        }

        $id = (int)$_GET['id'];

        $query = $em->getRepository('Board')->find($id);

        $row = [
            'id'          => $query->getId(),
            'name'        => $query->getName(),
            'title'       => $query->getTitle(),
            'message'     => $query->getMessage(),
            'updateTime'  => $query->getUpdateTime()->format('Y-m-d H:i:s')
        ];

        require_once 'messageContentView.php';

    }catch (Exception $e){
        error_log(__FILE__ . ' function : ' . __FUNCTION__ . ' ' . $e->getMessage());
        echo $e->getMessage();
    }
}

/**
 * 新增留言,新增完畢轉回首頁
 *
 * @param EntityManager $em db連線
 */
function insert($em) {

    try {

        if (!isset($_POST['times'])) {
            throw new Exception('無法取得新增次數');
        }

        if (!is_numeric($_POST['times'])) {
            throw new Exception('輸入新增次數非數字');
        }

        $times = (int) $_POST['times'];

        if ($times > 100) {
            throw new Exception('單次新增筆數不得超過100');
        }

        if ($times < 1) {
            throw new Exception('單次新增筆數不得少於1');
        }

        if ($times > 1) {
            bulkInsert($em);
        }else{
            $message = escapeString($_POST['message']);
            $username = escapeString($_POST['username']);
            $title = escapeString($_POST['title']);

            if (!$username && $username !== '0') {
                throw new Exception('無法取得username');
            }

            if (!$title && $title !== '0') {
                throw new Exception('無法取得title');
            }

            $board = new Board();

            $board->setTitle($title);
            $board->setMessage($message);
            $board->setName($username);
            $board->setUpdateTime(new DateTime());
            $em->persist($board);
            $em->flush();

            header("Location: /message.php");
        }
    }catch (Exception $e){
        error_log(__FILE__ . ' function : ' . __FUNCTION__ . ' ' . $e->getMessage());
        echo $e->getMessage();
    }
}

/**
 * 批次新增留言,新增完畢轉回首頁
 *
 * @param EntityManager $em db連線
 */
function bulkInsert($em){

    try {

        $message = escapeString($_POST['message']);
        $username = escapeString($_POST['username']);
        $title = escapeString($_POST['title']);
        $times = (int) $_POST['times'];

        if (!$username && $username !== '0') {
            throw new Exception('無法取得username');
        }

        if (!$title && $title !== '0') {
            throw new Exception('無法取得title');
        }

        for ($i = 1; $i <= $times; $i++) {
            $board = new Board();
            $board->setTitle($title);
            $board->setMessage($message);
            $board->setName($username);
            $board->setUpdateTime(new DateTime());
            $em->persist($board);
        }

        $em->flush();
        $em->clear();

        header("Location: /message.php");

    }catch (Exception $e){
        error_log(__FILE__ . ' function : ' . __FUNCTION__ . ' ' . $e->getMessage());
        echo $e->getMessage();
    }
}

/**
 * 更新留言,更新完畢轉回首頁
 *
 * @param EntityManager $em db連線
 */
function update($em) {

    try {

        $message = escapeString($_POST['message']);

        if (!isset($_POST['id'])) {
            throw new Exception('無法取得留言編號');
        }

        if (!is_numeric($_POST['id'])) {
            throw new Exception('輸入留言編號非數字');
        }

        $id = (int) $_POST['id'];

        $board = $em->find('Board', $id);

        $board->setMessage($message);
        $board->setUpdateTime(new DateTime());

        $em->flush();

        header("Location: /message.php?method=content&id=$id");

    }catch (Exception $e){
        error_log(__FILE__ . ' function : ' . __FUNCTION__ . ' ' . $e->getMessage());
        echo $e->getMessage();
    }
}

/**
 * 批次更新頁
 *
 * @param EntityManager $em db連線
 */
function bulkUpdateView($em) {

    try {

        if (!isset($_POST['ids'])) {
            throw new Exception('無法取得留言編號');
        }

        if (!is_array($_POST['ids'])) {
            throw new Exception('留言編號非正確格式');
        }

        $ids = $_POST['ids'];

        $rows = $em->createQueryBuilder()
                   ->select('b')
                   ->from('Board', 'b')
                   ->where('b.id in (:ids)')
                   ->setParameter('ids', $ids)
                   ->getQuery()
                   ->getArrayResult();

        require_once 'messageBulkContentView.php';

    }catch (Exception $e){
        error_log(__FILE__ . ' function : ' . __FUNCTION__ . ' ' . $e->getMessage());
        echo $e->getMessage();
    }
}

/**
 * 批次更新
 *
 * @param EntityManager $em
 */
function bulkUpdate($em) {

    try {

        if (!isset($_POST['ids'])) {
            throw new Exception('無法取得留言編號');
        }

        if (!is_array($_POST['ids'])) {
            throw new Exception('留言編號非正確格式');
        }

        $ids = $_POST['ids'];

        foreach ($ids as $id) {

            if (!is_numeric($id)) {
                throw new Exception('輸入留言編號非數字');
            }

            $id = (int) $id;

            $message = escapeString($_POST['message_'.$id]);

            $board = $em->find('Board', $id);

            $board->setMessage($message);
            $board->setUpdateTime(new DateTime());
        }

        $em->flush();

        header("Location: /message.php");

    }catch (Exception $e){
        error_log(__FILE__ . ' function : ' . __FUNCTION__ . ' ' . $e->getMessage());
        echo $e->getMessage();
    }
}

/**
 * 刪除留言,刪除完畢轉回首頁
 *
 * @param EntityManager $em db連線
 */
function del($em) {

    try {

        if (!isset($_GET['id'])) {
            throw new Exception('無法取得留言編號');
        }

        if (!is_numeric($_GET['id'])) {
            throw new Exception('輸入留言編號非數字');
        }

        $id = (int)$_GET['id'];
        $board = $em->find('Board', $id);
        $em->remove($board);
        $em->flush();

        header("Location: /message.php");

    }catch (Exception $e){
        error_log(__FILE__ . ' function : ' . __FUNCTION__ . ' ' . $e->getMessage());
        echo $e->getMessage();
    }
}

/**
 * 批次刪除留言,刪除完畢轉回首頁
 *
 * @param EntityManager $em db連線
 */
function bulkDel($em) {

    try {

        if (!isset($_POST['ids'])) {
            throw new Exception('無法取得留言編號');
        }

        if (!is_array($_POST['ids'])) {
            throw new Exception('留言編號非正確格式');
        }

        $ids = $_POST['ids'];

        foreach($ids as $id){
            $board = $em->find('Board', $id);
            $em->remove($board);
        }

        $em->flush();

        header("Location: /message.php");

    }catch (Exception $e){
        error_log(__FILE__ . ' function : ' . __FUNCTION__ . ' ' . $e->getMessage());
        echo $e->getMessage();
    }
}


/**
 * 字元過濾
 *
 * @param string $str 要過濾的字串
 * @return string
 */
function escapeString($str) {

    $str = trim($str);
    $str = mysql_real_escape_string($str);
    $str = htmlentities($str);

    return $str;
}

function autoload ($class) {
    include(__DIR__ . '/entities/' . $class . '.php');
}
