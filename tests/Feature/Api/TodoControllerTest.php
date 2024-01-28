<?php

namespace Tests\Feature\Api;

use App\Models\Todo;
// use Illuminate\Foundation\Testing\RefreshDatabase;
// use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class TodoControllerTest extends TestCase
{
    use DatabaseTransactions;

    public function setUp():void
    {
        parent::setUp();
    }

    /**
     * @test
     */
    public function Todoの新規作成()
    {

        $params = [
            'title' => 'テスト:タイトル',
            'content' => 'テスト:内容'
        ];

        $res = $this->postJson(route('api.todo.create'), $params);
        $res->assertOk();
        $todos = Todo::all();

        $this->assertCount(1, $todos);

        $todo = $todos->first();

        $this->assertEquals($params['title'], $todo->title);
        $this->assertEquals($params['content'], $todo->content);
    }

    /**
     * @test
     */
    public function Todoの新規作成時に未入力があった際に新規作成失敗()
    {
        $params = [
            'title' => null,
            'content' => 'コンテンツ',
        ];

        $res = $this->postJson(route('api.todo.create'), $params);
        $res->assertstatus(422);
    }

    /**
     * @test
     */
    public function Todoの新規作成時に256文字以上の入力があった場合に新規作成ができない()
    {
        $params = [
            `title` => str_repeat('a', 256),
        ];

        $res = $this->postJson(route('api.todo.create'), $params);
        $res->assertstatus(422);
    }

    /**
     * @test
     */
    public function Todoの更新処理が成功する()
    {
        $todo = Todo::factory()->create();
        $res = $this->putJson(route('api.todo.update',$todo->id), [
            'title' => '投稿の更新',
            'content' => '投稿の更新をしました',
        ]);

        $this->assertDatabaseHas('todos', [
            'title' => '投稿の更新',
            'content' => '投稿の更新をしました',
        ]);
    }

    /**
     * @test
     */
    public function 更新処理の際に期待しない情報の入力があった場合に失敗する()
    {
        $todo = Todo::factory()->create();
        $res = $this->putJson(route('api.todo.update',$todo->id), [
            'title' => null,
            'content' => '投稿の更新をしました',
        ]);

        $res->assertstatus(422);
    }

    /**
     * @test
     */
    public function 更新処理の際に256文字以上の入力があった場合に失敗する()
    {
        $todo = Todo::factory()->create();
        $res = $this->putJson(route('api.todo.update',$todo->id), [
            'title' => str_repeat('a', 256),
        ]
        );
        $res->assertstatus(422);
    }

    /**
     * @test
     */
    public function 詳細取得が成功する()
    {
        $todo = Todo::factory()->create();
        $res = $this->getJson(route('api.todo.show',$todo->id));
        $res->assertOk();
    }

    /**
     * @test
     */
    public function 詳細取得が失敗する()
    {
        $todo = Todo::factory()->create([
            'title' => 'テスト',
            'content' => 'tesuto'
        ]);
        $res = $this->getJson(route('api.todo.show',++$todo->id));
        $res->assertstatus(404);
    }

}
