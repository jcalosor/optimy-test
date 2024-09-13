<?php
declare(strict_types=1);

namespace App\Providers;

use App\Accessors\News;
use App\Utils\CommentManager;
use App\Utils\DB;
use App\Utils\NewsManager;

class NewsManagerProvider extends AbstractProvider
{

    public function abstractClass(): string
    {
        return NewsManager::class;
    }

    public function factory()
    {
        return new NewsManager(
            $this->container->get(DB::class),
            $this->container->get(CommentManager::class)
        );
    }
}