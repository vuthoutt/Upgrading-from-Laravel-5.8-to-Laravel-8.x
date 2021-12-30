<?php

namespace App\Models\ShineCompliance;

use App\Models\ModelBase;

class ItemInfo extends ModelBase
{
    protected $table = 'tbl_items_info';

    protected $fillable = [
        'extent',
        'item_id',
        'is_r_and_d_element',
        'inspection_date',
        'comment',
        'assessment',
        'created_at',
    ];
    protected $hidden = ['item_id'];
    public function getAssessmentTypeTextAttribute() {
        $assessment = $this->attributes['assessment'];
        switch ($assessment) {
            case ITEM_FULL_ASSESSMENT:
                $assessment_text = 'Full Assessment Inaccessible Item';
                break;

            case ITEM_LIMIT_ASSESSMENT:
                $assessment_text = 'Limited Assessment Inaccessible Item';
                break;

            default:
                $assessment_text = '';
                break;
        }
        return $assessment_text;
    }

    public function getIsRAndDElementTextAttribute() {
        $r_d = $this->attributes['is_r_and_d_element'];
        switch ($r_d) {
            case 1:
                $r_d_text = 'Yes';
                break;

            case 0:
                $r_d_text = 'No';
                break;

            default:
                $r_d_text = '';
                break;
        }
        return $r_d_text;
    }
}
