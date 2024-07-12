<?php

use App\Events\UserExcelExportFinished;
use App\Jobs\ExportUsersJob;
use App\Models\Download;
use App\Models\User;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Storage;
use function Pest\Laravel\actingAs;
use Illuminate\Support\Facades\Queue;
use Inertia\Testing\AssertableInertia as Assert;


test('dashboad', function () {

    User::factory(100)->create();

    $user = User::find(1);

    actingAs($user);

    $response = $this->get(route('dashboard'));

    $response->assertStatus(200);

});

test('export endpoint', closure: function () {

    Storage::fake('public');

    Queue::fake();

    User::factory(2)->create();

    $user = User::find(1);

    actingAs($user);

    $response = $this->get(route('export'));

    Queue::assertPushed(ExportUsersJob::class, 1);

    Queue::assertPushedOn('default', ExportUsersJob::class);

    $response->assertStatus(200);

});

test('export job handler', function (){

    Storage::fake('public');

    Event::fake();

    User::factory(2)->create();

    $user = User::find(1);

    actingAs($user);

    (new ExportUsersJob($user))->handle();

    $this->assertDatabaseHas('downloads', ['id'=> 1]);

    $file = Download::all()->first();

    Storage::assertExists($file->file_name);

    Event::assertDispatched(UserExcelExportFinished::class);

});

test('can see view with list of files ready for download', function () {

    $this->withoutExceptionHandling();

    Event::fake();

    User::factory(10)->create();

    $user = User::find(1);

    actingAs($user);

    (new ExportUsersJob($user))->handle();

    $response = $this->get(route('files'));

    $response->assertStatus(200);

    $response->assertInertia(fn (Assert $page) => $page
        // Checking a root-level property...
        ->has('files')

    );

    $response2 = $this->get(route('download', ['download' => Download::all()->first()->id]));

    $response2->assertDownload();

});

