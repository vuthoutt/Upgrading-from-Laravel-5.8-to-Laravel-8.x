<?php

namespace App\Console\Commands;

use App\Helpers\CommonHelpers;
use App\Models\ShineDocumentStorage;
use Illuminate\Console\Command;
use Imagick;

class FixerImageThumbnail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:fixer_image_thumb_nail {--option=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        try{
//          // only image of items will be cropped, location and property losing quality
            $this->info("==========> Start convert all items/locations/properties before 03/12/2019!");
            $arr_fail = [];
            $after_date = '2019-12-03';
            $images = ShineDocumentStorage::where('created_at', '>', $after_date)
                ->whereIn('type', [PROPERTY_PHOTO,LOCATION_IMAGE,ITEM_PHOTO,ITEM_PHOTO_LOCATION,ITEM_PHOTO_ADDITIONAL,PROPERTY_SURVEY_IMAGE])->get();
//            $images = ShineDocumentStorage::whereIn('type', [PROPERTY_PHOTO,LOCATION_IMAGE,ITEM_PHOTO,ITEM_PHOTO_LOCATION,ITEM_PHOTO_ADDITIONAL,PROPERTY_SURVEY_IMAGE])
//                ->whereIn('id',[200184,200200,200185,200187,200186,200189,200190,200188,200193,200194,200192,200196,200197,200195,200199,200198])->get();
            if(!$images->isEmpty()){
                foreach ($images as $image){
                    $real_path = public_path() . '/'. $image->path;
                    if(file_exists($real_path)){
                        // $alias_path is mklink path
                        $is_crop = true;
                         $this->info("==========>Start cropping image : ".$image->path);
                        CommonHelpers::createThumbnail($image->path, $is_crop);
                    } else {
                        $arr_fail[] = $image->id;
                    }
                }
            }
        } catch (\Exception $e){
            $this->info("==========> Exception: ".$e->getMessage());
        }
        if(count($arr_fail)){
            $this->info("There are ".count($arr_fail)." when progressing!");
            $this->info(implode(", ", $arr_fail));
        }
        $this->info("==========> End convert!");
    }
}
