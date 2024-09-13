<?php

namespace App\Utils;

use App\Accessors\Comment;
use App\Accessors\News;

class NewsManager
{

    public function __construct(
        protected DB $db,
        protected CommentManager $commentManager
    ) {
    }

    /**
     * @return array
     */
    public function listNews(): array
    {
        $rows = $this->db->select('SELECT * FROM `news`');

        $result = [];
        foreach ($rows as $row) {
            $news = new News();
            $result[] = $news
                ->setId($row['id'])
                ->setTitle($row['title'])
                ->setBody($row['body'])
                ->setCreatedAt($row['created_at']);
        }

        return $result;
    }

    /**
     * Return a resolve list of news with it's respective comments.
     *
     * @return array
     */
    public function listNewsWithComments(): array
    {
        $result = [];
        $commentsResult = [];
        $rows = $this->db->select('
                SELECT n.id, 
                       n.title, 
                       n.body, 
                       n.created_at, 
                       c.id AS comment_id, 
                       c.body AS comment_body, 
                       c.news_id 
                FROM `news` n
                LEFT JOIN comment c ON n.id = c.news_id
                ORDER BY n.id
        ');

        foreach ($rows as $row) {
            $comments = new Comment();
            $news = new News();

            $commentsResult[$row['id']][] = $comments
                ->setId($row['comment_id'])
                ->setNewsId($row['news_id'])
                ->setBody($row['comment_body'])
                ->setCreatedAt($row['created_at']);

            $result[$row['id']] =
                $news->setId($row['id'])
                    ->setTitle($row['title'])
                    ->setBody($row['body'])
                    ->setCreatedAt($row['created_at'])
                    ->setComments($commentsResult[$row['id']]);
        }

        return $result;
    }

    /**
     * add a record in news table
     */
    public function addNews(string $title, string $body): bool|string
    {
        $sql = "INSERT INTO `news` (`title`, `body`, `created_at`) VALUES('" . $title . "','" . $body . "','" . date('Y-m-d') . "')";
        $this->db->exec($sql);

        return $this->db->lastInsertId($sql);
    }

    /**
     * deletes a news, and also linked comments
     */
    public function deleteNews($id)
    {
        $comments = "DELETE FROM `comment` WHERE `news_id` = {$id}";

        if ($this->db->exec($comments) === true) {
            $sql = "DELETE FROM `news` WHERE `id`= {$id}";

            return $this->db->exec($sql);
        }

        return false;
    }
}