<?php
namespace Model;

class Comment extends \Framework\Model
{
    private mixed $id;
    private mixed $createdAt;
    private mixed $content;
    private mixed $status;
    private mixed $articleId;
    private mixed $userId;

    /**
     * @return mixed
     */
    public function getId(): mixed
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId(mixed $id): void
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getCreatedAt(): mixed
    {
        return $this->createdAt;
    }

    /**
     * @param mixed $createdAt
     */
    public function setCreatedAt(mixed $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return mixed
     */
    public function getContent(): mixed
    {
        return $this->content;
    }

    /**
     * @param mixed $content
     */
    public function setContent(mixed $content): void
    {
        $this->content = $content;
    }

    /**
     * @return mixed
     */
    public function getStatus(): mixed
    {
        return $this->status;
    }

    /**
     * @param mixed $status
     */
    public function setStatus(mixed $status): void
    {
        $this->status = $status;
    }

    /**
     * @return mixed
     */
    public function getArticleId(): mixed
    {
        return $this->articleId;
    }

    /**
     * @param mixed $articleId
     */
    public function setArticleId(mixed $articleId): void
    {
        $this->articleId = $articleId;
    }

    /**
     * @return mixed
     */
    public function getUserId(): mixed
    {
        return $this->userId;
    }

    /**
     * @param mixed $userId
     */
    public function setUserId(mixed $userId): void
    {
        $this->userId = $userId;
    }




}