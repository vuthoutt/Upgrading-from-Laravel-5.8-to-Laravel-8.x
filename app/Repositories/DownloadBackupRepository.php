<?php
namespace App\Repositories;
use App\Models\DownloadBackup;
use Prettus\Repository\Eloquent\BaseRepository;

class DownloadBackupRepository extends BaseRepository {


    function model()
    {
        return DownloadBackup::class;
    }
}
