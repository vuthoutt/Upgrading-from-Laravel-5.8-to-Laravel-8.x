<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Survey;
use App\Helpers\CommonHelpers;
use App\Models\ShineDocumentStorage;
use Imagick;
class FixerImageTransparent extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:image_transparent {survey_id}';

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
        $arr_fail = [];
        try{
            $survey_id = $this->argument('survey_id');
            $survey = Survey::find($survey_id);
            $this->info("==========> Start convert all items/locations/properties for survey {$survey_id}!");
            //for property image
            $prop_image = ShineDocumentStorage::where('object_id', $survey_id)->where('type',PROPERTY_SURVEY_IMAGE)->first();
            if (!is_null($prop_image)) {
                $real_path = public_path() . '/'. $prop_image->path;
                if(file_exists($real_path)){
                    $is_crop = true;
                     $this->info("==========>Start cropping property survey image : ".$prop_image->path);
                    CommonHelpers::createThumbnail($prop_image->path, $is_crop);
                } else {
                    $arr_fail[] = $prop_image->id;
                }
            }
            //for location

            $locations = $survey->location;
            if(!is_null($locations)){
                foreach ($locations as $location){
                    $locationImages = $location->shineDocumentStorage;
                    if(!is_null($locationImages)){
                        $image = $locationImages;
                        $real_path = public_path() . '/'. $image->path;
                        if(file_exists($real_path)){
                            $is_crop = true;
                             $this->info("==========>Start cropping image Location: ".$image->path);
                            CommonHelpers::createThumbnail($image->path, $is_crop);
                        } else {
                            $arr_fail[] = $image->id;
                        }

                    }
                }
            }
            // for item
            $items = $survey->item;

            if(!is_null($items)){
                foreach ($items as $item){
                    $itemImages = $item->shineDocumentStorage;
                    if(!is_null($itemImages)){
                        foreach ($itemImages as $image){
                            $real_path = public_path() . '/'. $image->path;
                            if(file_exists($real_path)){
                                $is_crop = true;
                                 $this->info("==========>Start cropping image Item: ".$image->path);
                                CommonHelpers::createThumbnail($image->path, $is_crop);
                            } else {
                                $arr_fail[] = $image->id;
                            }
                        }
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
