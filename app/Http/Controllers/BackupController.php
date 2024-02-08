<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Backup\BackupFacade as Backup;
use Spatie\Backup\BackupDestination\BackupDestination;
use Spatie\Backup\Tasks\Backup\BackupDestination\BackupFile;

class BackupController extends Controller
{
    public function backup()
    {
        $backup = Backup::createBackup();

        $backupFile = $backup->destination(BackupDestination::create('local'))->getFile();
    
        return response()->download($backupFile->path());
    }
}

