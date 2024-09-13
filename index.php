<?php
require 'vendor/autoload.php';

/** @var \App\Container $app */
$app = require_once __DIR__ . '/app.php';

/** @var \App\Utils\NewsManager $newsManager */
$newsManager = $app->get(\App\Utils\NewsManager::class);

/**
 * The difference of this implementation from the previous one is that,
 * we reduced the number of database call to only 1,
 * use join statement to get both the parent "news" and child "comments",
 * micro-optimizing the number of database invoke will be valuable
 * once the app began to scale.
 */
foreach ($newsManager->listNewsWithComments() as $news) {
    echo("############ NEWS " . $news->getTitle() . " ############\n");
    echo($news->getBody() . "\n");
    foreach ($news->getComments() as $comment) {
        if ($comment->getNewsId() === $news->getId()) {
            echo("Comment " . $comment->getId() . " : " . $comment->getBody() . "\n");
        }
    }
}
