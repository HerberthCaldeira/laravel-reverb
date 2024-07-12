<?php

namespace App\Jobs;

use App\Exports\UsersExport;
use App\Models\Download;
use App\Models\User;
use Illuminate\Bus\Batch;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Bus;

class ExportUsersJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;



    private string $fileName;

    /**
     * Create a new job instance.
     */
    public function __construct(private User $user)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->fileName = Str::random(20)  . '.xlsx';

        (new UsersExport())->store($this->fileName);
        Download::create(['file_name' =>   $this->fileName]);
        \App\Events\UserExcelExportFinished::dispatch($this->user);
    }

    /**
     * Handle a job failure.
     */
    public function failed(?\Throwablee $exception): void
    {
        // Send user notification of failure, etc...
        Download::where(['file_name' => $this->fileName])->delete();
        Storage::delete($this->fileName);
    }
}
