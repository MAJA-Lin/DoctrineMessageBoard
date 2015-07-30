<?php
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping as ORM;

class BoardRepository extends EntityRepository
{
    /**
     * 取得總頁數
     * @param int $pageLimit 每頁筆數
     * @return int $totalPage 總頁數
     */
    public function getTotalPage($pageLimit){

        $totalPage = 1;

        $row = $this->_em->createQuery('select count(b.id) from Board b')->getSingleResult();

        $totalCount = $row[1];

        if ($totalCount != 0){
            $totalPage = ceil(bcdiv($totalCount, $pageLimit, 1));
        }

        return $totalPage;
    }
}