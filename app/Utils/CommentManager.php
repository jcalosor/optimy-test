<?php

namespace App\Utils;

use App\Accessors\Comment;

class CommentManager
{
    public function __construct(protected DB $db)
    {
    }

    public function listComments(): array
    {
        // $db = DB::getInstance();
        $rows = $this->db->select('SELECT * FROM `comment`');

        $comments = [];
        foreach ($rows as $row) {
            $comments[] = $this->comment
                ->setId($row['id'])
                ->setBody($row['body'])
                ->setCreatedAt($row['created_at'])
                ->setNewsId($row['news_id']);
        }

        return $comments;
    }

    public function addCommentForNews($body, $newsId): bool|string
    {
        $sql = "INSERT INTO `comment` (`body`, `created_at`, `news_id`) VALUES('" . $body . "','" . date('Y-m-d') . "','" . $newsId . "')";
        $this->db->exec($sql);

        return $this->db->lastInsertId($sql);
    }

    public function deleteComment($id)
    {
        $sql = "DELETE FROM `comment` WHERE `id`=" . $id;

        return $this->db->exec($sql);
    }
}