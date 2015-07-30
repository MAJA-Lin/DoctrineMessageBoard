<?php
/**
 *@Entity(repositoryClass="BoardRepository")
 *@Table(name="board")
 */
class Board
{
    /**
     *  @Id
     *  @Column(name="id",type="integer")
     *  @GeneratedValue
     */
    protected $id;

    /**
     *  使用者名稱
     *
     *  @Column(name="name", type="string", length=40, nullable=FALSE)
     */
    protected $name;

    /**
     *  留言標題
     *
     *  @Column(name="title", type="string", length=80, nullable=FALSE)
     */
    protected $title;

    /**
     *  留言內容
     *
     *  @Column(name="message", type="text")
     */
    protected $message;

    /**
     *  更新時間
     *
     *  @Column(name="update_time", type="datetime")
     */
    protected $updateTime;

    /**
     * 取得 id
     *
     * @return $this->id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * 設定 ID
     *
     * @param integer $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * 取得使用者名稱
     *
     * @return $this->name
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * 設定使用者名稱
     *
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * 取得留言標題
     *
     * @return $this->title
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * 設定留言標題
     *
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * 取得留言內容
     *
     * @return $this->message
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * 設定留言內容
     *
     * @param string $message
     */
    public function setMessage($message)
    {
        $this->message = $message;
    }

    /**
     * 取得留言更新時間
     *
     * @return $this->updateTime
     */
    public function getUpdateTime()
    {
        return $this->updateTime;
    }

    /**
     * 設定留言更新時間
     *
     * @param datetime $updateTime
     */
    public function setUpdateTime($updateTime)
    {
        $this->updateTime = $updateTime;
    }
}
