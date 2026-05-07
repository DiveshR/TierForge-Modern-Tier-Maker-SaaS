<?php

use App\Models\User;
use App\Models\TierList;
use App\Models\TierRow;
use App\Models\TierItem;
use App\Models\TierItemPosition;
use App\Models\Comment;
use App\Models\Like;
use App\Models\Tag;
use Illuminate\Support\Str;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

test('database uses uuid as primary keys', function () {
    $user = User::factory()->create();
    expect(Str::isUuid($user->id))->toBeTrue();

    $tierList = TierList::create([
        'user_id' => $user->id,
        'title' => 'Test Tier List',
        'slug' => 'test-tier-list-' . Str::random(5),
        'category' => 'Gaming',
    ]);
    expect(Str::isUuid($tierList->id))->toBeTrue();
});

test('tier list relationships are functional', function () {
    $user = User::factory()->create();
    $tierList = TierList::create([
        'user_id' => $user->id,
        'title' => 'My Rankings',
        'slug' => 'my-rankings',
        'category' => 'Movies',
    ]);

    $row = $tierList->rows()->create([
        'label' => 'S Tier',
        'color' => '#FF0000',
        'order_index' => 0,
    ]);

    $item = TierItem::create([
        'name' => 'The Matrix',
        'metadata' => ['year' => 1999],
    ]);

    $position = TierItemPosition::create([
        'tier_list_id' => $tierList->id,
        'tier_row_id' => $row->id,
        'tier_item_id' => $item->id,
        'position' => 1,
    ]);

    expect($tierList->user->id)->toBe($user->id);
    expect($tierList->rows)->toHaveCount(1);
    expect($row->tierList->id)->toBe($tierList->id);
    expect($position->tierItem->id)->toBe($item->id);
});

test('social features work correctly', function () {
    $user = User::factory()->create();
    $tierList = TierList::create([
        'user_id' => $user->id,
        'title' => 'Social List',
        'slug' => 'social-list',
        'category' => 'Food',
    ]);

    $comment = Comment::create([
        'user_id' => $user->id,
        'tier_list_id' => $tierList->id,
        'content' => 'Great list!',
    ]);

    $reply = Comment::create([
        'user_id' => $user->id,
        'tier_list_id' => $tierList->id,
        'parent_id' => $comment->id,
        'content' => 'I agree!',
    ]);

    $like = Like::create([
        'user_id' => $user->id,
        'likeable_id' => $tierList->id,
        'likeable_type' => TierList::class,
    ]);

    expect($comment->replies)->toHaveCount(1);
    expect($reply->parent->id)->toBe($comment->id);
    expect($tierList->likes)->toHaveCount(1);
});

test('soft deletes are functional', function () {
    $user = User::factory()->create();
    $userId = $user->id;

    $user->delete();

    expect(User::find($userId))->toBeNull();
    expect(User::withTrashed()->find($userId))->not->toBeNull();
});
