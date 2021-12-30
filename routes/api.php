<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('audit/list_audit', 'API\V1\DropdownController@getAuditDropdown');

Route::prefix('v1')->group(function () {
    Route::get('dropdown/getall', 'API\V1\DropdownController@getAllDropdown');
    Route::middleware(['log_request'])->group(function () {
        Route::post('auth/register', 'API\V1\APIController@register');
        Route::post('auth/login', 'API\V1\APIController@login');
        Route::post('reset-password', 'API\V1\APIController@resetPassword');
        Route::post('auth/logout', 'API\V1\APIController@logout');
        Route::middleware(['check_api'])->group(function () {
            Route::post('manifest', 'API\V1\APIController@getManifest');
            Route::post('get_survey_detail', 'API\V1\APIController@getSurveyDetail');
        });
    });
});

Route::prefix('download')->group(function () {
    Route::post('survey_detail', 'API\V1\DownloadController@getSurveyDetail');
    Route::middleware(['check_api','log_request'])->group(function () {
        Route::post('check_surveys', 'API\V1\DownloadController@checkSurvey');
    });
});

Route::prefix('upload')->group(function () {
    Route::middleware(['check_api','log_request'])->group(function () {
        Route::post('data', 'API\V1\UploadController@uploadData');
        Route::post('manifest', 'API\V1\UploadController@uploadManifest');
        Route::post('image', 'API\V1\UploadController@uploadImage');
        Route::post('check_complete', 'API\V1\UploadController@checkComplete');
    });
});

Route::prefix('backup')->group(function () {
    Route::post('restore', 'API\V1\BackupController@restore');
    Route::middleware(['check_api','log_request'])->group(function () {
        Route::post('list', 'API\V1\BackupController@listBackup');
        Route::post('upload', 'API\V1\BackupController@doBackup');
    });
});

Route::prefix('image')->group(function () {
        Route::post('asset', 'API\V1\DownloadController@getSurveyImage');
    Route::middleware(['check_api','log_request'])->group(function () {
        Route::post('pplan', 'API\V1\DownloadController@getPPlan');
    });
});

Route::prefix('v2')->group(function () {
    Route::prefix('/backup')->group(function () {
        Route::post('restoreData', 'API\V2\BackupController@restoreData');
        Route::post('restoreImage', 'API\V2\BackupController@restoreImage');
        Route::middleware(['check_api','log_request'])->group(function () {
            Route::post('image', 'API\V2\BackupController@backupImage');
            Route::post('data', 'API\V2\BackupController@backupData');
            Route::post('checkComplete', 'API\V2\BackupController@checkComplete');
        });
    });

    Route::group([
        'prefix' => 'audit-trail',
        'middleware' => ['check_api','log_request'],
    ], function () {
        Route::post('/log', 'API\V2\AppAuditTrailController@logAppAuditTrail');
    });

    Route::group([
        'prefix'     => 'compliance',
        'namespace' => 'API\V2\Compliance',
    ], function () {
        Route::group([
            'prefix'     => 'assessments',
            'namespace' => 'Assessment',
        ], function () {
            Route::get('dropdowns', 'ApiAssessmentController@getAllDropdowns');
            Route::middleware(['check_api'])->group(function () {
                Route::get('/', 'ApiAssessmentController@listAssessment');
                Route::get('/{assess_id}', 'ApiAssessmentController@getAssessmentDetail');
                Route::middleware(['log_request'])->group(function () {
                    Route::post('upload_manifest', 'ApiAssessmentController@uploadManifest');
                    Route::post('upload_data', 'ApiAssessmentController@uploadData');
                    Route::post('upload_image', 'ApiAssessmentController@uploadImage');
                });
            });

            Route::prefix('/backup')->group(function () {
                Route::get('list/{assess_id}', 'ApiBackupAssessmentController@listBackup')->middleware(['check_api']);
                Route::get('restoreData/{backup_id}', 'ApiBackupAssessmentController@restoreData');
                Route::get('restoreImage/{backup_id}', 'ApiBackupAssessmentController@restoreImage');
                Route::middleware(['check_api','log_request'])->group(function () {
                    Route::post('manifest', 'ApiBackupAssessmentController@backupManifest');
                    Route::post('image', 'ApiBackupAssessmentController@backupImage');
                    Route::post('data', 'ApiBackupAssessmentController@backupData');
                });
            });
        });
    });
});

Route::prefix('orchard')->group(function () {
    Route::post('api_no1', 'API\OrchardController@api_no1');
});

