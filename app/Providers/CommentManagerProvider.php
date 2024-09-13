<?php
declare(strict_types=1);

namespace App\Providers;

use App\Accessors\Comment;
use App\Utils\CommentManager;
use App\Utils\DB;

class CommentManagerProvider extends AbstractProvider
{

    public function abstractClass(): string
    {
        return CommentManager::class;
    }

    public function factory()
    {
        return new CommentManager(
            $this->container->get(DB::class)
        );
    }
}