<div class="row">
     @include('tables.work_supporting_doc',[
        'tableId' => 'work-supporting-doc',
        'title' => 'Work Request Supporting Documents',
        'collapsed' => false,
        'plus_link' => $is_locked == true ? false : true,
        'data' => $workRequest->workSupportingDocument,
        'modal_id' => 'add-work-doc',
        'edit_permission' => $is_locked == true ? false : true
    ])
    @include('modals.add_work_doc',[ 'modal_id' => 'add-work-doc','action' => 'add','url' => route('wr.post_document'),'work_id' => $workRequest->id,'unique' => \Str::random(5), ])

</div>
