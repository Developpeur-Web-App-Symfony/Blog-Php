<?php
namespace Model;


use DateTime;

class Article extends \Framework\Model
{
    private mixed $id;
    private mixed $created_at;
    private mixed $lastModification;
    private mixed $content;
    private mixed $title;
    private mixed $publish;
    private mixed $excerpt;
    private mixed $imageFilename;
    private mixed $imageAlt;
    private mixed $userId;

    public function __construct()
    {
        $dateTime = new \DateTime();
        $this->created_at = $dateTime->format('Y-m-d H:i:s');
    }

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
        return $this->created_at;
    }

    /**
     * @param mixed $created_at
     */
    public function setCreatedAt(mixed $created_at): void
    {
        $this->created_at = $created_at;
    }

    /**
     * @return mixed
     */
    public function getLastModification(): mixed
    {
        return $this->lastModification;
    }

    /**
     * @param mixed $lastModification
     */
    public function setLastModification(mixed $lastModification): void
    {
        $this->lastModification = $lastModification;
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
    public function getTitle(): mixed
    {
        return $this->title;
    }

    /**
     * @param mixed $title
     */
    public function setTitle(mixed $title): void
    {
        $this->title = $title;
    }

    /**
     * @return mixed
     */
    public function getPublish(): mixed
    {
        return $this->publish;
    }

    /**
     * @param mixed $publish
     */
    public function setPublish(mixed $publish): void
    {
        $this->publish = $publish;
    }

    /**
     * @return mixed
     */
    public function getExcerpt(): mixed
    {
        return $this->excerpt;
    }

    /**
     * @param mixed $excerpt
     */
    public function setExcerpt(mixed $excerpt): void
    {
        $this->excerpt = $excerpt;
    }

    /**
     * @return mixed
     */
    public function getImageFilename(): mixed
    {
        return $this->imageFilename;
    }

    /**
     * @param mixed $imageFilename
     */
    public function setImageFilename(mixed $imageFilename): void
    {
        $this->imageFilename = $imageFilename;
    }

    /**
     * @return mixed
     */
    public function getImageAlt(): mixed
    {
        return $this->imageAlt;
    }

    /**
     * @param mixed $imageAlt
     */
    public function setImageAlt(mixed $imageAlt): void
    {
        $this->imageAlt = $imageAlt;
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