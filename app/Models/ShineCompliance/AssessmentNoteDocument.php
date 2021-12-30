<?php

namespace App\Models\ShineCompliance;

use App\Models\ModelBase;

class AssessmentNoteDocument extends ModelBase
{
    protected $table = 'cp_assessment_note_documents';

    protected $fillable = [
        "id",
        "reference",
        "property_id",
        "description",
        "plan_reference",
        "assess_id",
        "document_present",
        "plan_date",
        "created_at",
        "updated_at",
        "deleted_at",
    ];


    public function shineDocumentStorage() {
        return $this->hasOne('App\Models\ShineDocumentStorage','object_id', 'id')->where('type',NOTE_FILE_ASSESSMENT);
    }
}
