<?php
declare(strict_types=1);

namespace Tests\Unit\Utils;

use App\Accessors\News;
use App\Utils\CommentManager;
use App\Utils\DB;
use App\Utils\NewsManager;
use Faker\Factory as FakerFactory;
use Mockery\MockInterface;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\TestDox;
use Tests\AbstractTestCase;

class NewsManagerTest extends AbstractTestCase
{
    public static function listNewsWithCommentsDataProvider(): array
    {
        $faker = FakerFactory::create();
        $newsId = $faker->numberBetween(1, 999);

        return [
            'Success' => [
                'expectations' => [
                    DB::class => [
                        'select' => [
                            'times' => 1,
                            'with' => 'SELECT n.id, n.title, 
                                           n.body, 
                                           n.created_at, 
                                           c.id AS comment_id, 
                                           c.body AS comment_body, 
                                           c.news_id 
                                    FROM `news` n
                                    LEFT JOIN comment c ON n.id = c.news_id
                                    ORDER BY n.id',
                            'andReturn' => [
                                [
                                    'id' => $newsId,
                                    'title' => 'Random news title',
                                    'body' => 'Random news body',
                                    'created_at' => '2016-11-30 14:21:23',
                                    'comment_id' => $faker->numberBetween(9, 999),
                                    'comment_body' => 'Random comment body',
                                    'news_id' => $newsId,
                                ]
                            ]
                        ]
                    ],
                ]
            ]
        ];
    }


    #[DataProvider('listNewsWithCommentsDataProvider')]
    #[TestDox('Assert news with comments result instance.')]
    public function testListNewsWithComments(array $expectations): void
    {
        /** @var \App\Utils\DB $db */
        $db = $this->mock(DB::class, function (MockInterface $db) use ($expectations): void {
            $dbExpectations = $expectations[DB::class];

            $select = $dbExpectations['select'];
            $db
                ->shouldReceive('select')
                ->times($select['times'])
                ->withArgs(
                    function ($sql) use ($select): bool {
                        $pattern = '/\s*/m';
                        $replace = '';

                        $actual = \preg_replace($pattern, $replace, $sql);
                        $expectation = \preg_replace($pattern, $replace, $select['with']);

                        $this->assertEquals($expectation, $actual);

                        return $actual === $expectation;
                    }
                )
                ->andReturn($select['andReturn']);
        });

        /** @var \App\Utils\CommentManager $commentManager */
        $commentManager = $this->mock(CommentManager::class);

        $result = (new NewsManager($db, $commentManager))->listNewsWithComments();

        $this->assertInstanceOf(News::class, \array_shift($result));
    }
}