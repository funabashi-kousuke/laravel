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

        $todo = Todo::factory()->state([
            'title' => 'テスト',
            'content' => 'テスト',
        ])->create();

        $this->assertDatabaseHas('todos', [
            'title' => 'テスト',
            'content' => 'テスト',
        ]);
    }

    /**
     * @test
     */
    public function Todoの新規作成時に期待しない情報の入力があった場合に新規作成失敗()
    {
        $params = [
            'name' => 'taro',
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


}
