<?php

namespace Repository;

use Framework\Model;
use \PDO;

class Comment extends \Framework\Model
{
    private object $comment;

    public function __construct($comment)
    {
        $this->comment = $comment;
    }

    public function getCommentsArticle($articleId): array
    {
        $sql = 'SELECT c.id, c.created_at, c.content, c.status, c.article_id, c.user_id, u.username FROM comments as c INNER JOIN users as u ON c.user_id = u.id WHERE article_id=:article_id AND status= 1 ORDER BY c.created_at DESC';


        $req = $this->executeRequest($sql, array(
            'article_id' => $articleId
        ));
        return $req->fetchAll(PDO::FETCH_CLASS, \Model\Comment::class);
    }

    public function save()
    {
        $sql = "INSERT INTO comments(created_at, content, article_id, user_id) VALUES(:created_at, :content, :article_id, :user_id)";

        $req = $this->executeRequest($sql, array(
            'created_at' => $this->comment->getCreatedAt(),
            'content' => $this->comment->getContent(),
            'user_id' => $this->comment->getUserId(),
            'article_id' => $this->comment->getArticleId(),
        ));
    }

    public function getAllComments(): array
    {
        $sql = 'SELECT id, created_at, content, status, article_id, user_id FROM comments WHERE status=:status';

        $req = $this->executeRequest($sql, array(
            'status'=> $this->comment->getStatus(),
        ));
        return $req->fetchAll(PDO::FETCH_CLASS, \Model\Comment::class);
    }

    public function updateComment()
    {
        $sql = 'UPDATE comments SET status=:status WHERE id =:id';
        $req = $this->executeRequest($sql, array(
            'id' => $this->comment->getId(),
            'status' => $this->comment->getStatus()
        ));
    }

    public function deleteComment()
    {
        $sql = 'DELETE FROM comments WHERE id =:id';
        $req = $this->executeRequest($sql, array(
            'id' => $this->comment->getId(),
        ));
    }
}