<?php
namespace Model;

use Cassandra\Date;
use DateTime;

class Article extends \Framework\Model
{
    private mixed $id;
    private mixed $created_at;
    private mixed $content;
    private mixed $title;
    private mixed $publish;
    private mixed $excerpt;
    private mixed $imageFilename;
    private mixed $imageAlt;
    private mixed $userId;

    public function __construct()
    {
        $dateTime = new Date('Y-m-d H:i:s');
        $dateTime->format('Y-m-d H:i:s');
        $this->created_at = $dateTime;
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

    public function hydrate($article)
    {
        $this->setCreatedAt($article->created_at);
        $this->setContent($article->content);
        $this->setTitle($article->title);
        $this->setPublish($article->publish);
        $this->setExcerpt($article->excerpt);
        $this->setImageFilename($article->imageFilename);
        $this->setImageAlt($article->imageAlt);
        $this->setUserId($article->userId);
        $this->setId($article->id);
    }
}