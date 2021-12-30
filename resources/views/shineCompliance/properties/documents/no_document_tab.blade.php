@if(isset($data))
    <div class="row">
        <div class="col-12 pr-0 mb-1">
            @include('shineCompliance.tables.property_documents', [
                'title' => "No Parent Document",
                'tableId' => 'no_parent_document',
                'collapsed' => true,
                'plus_link' => $can_add_new,
                'modal_id' => 'property-document-add',
                'system_id' => "no_parent_document",
                'data' => $data ?? [],
                'order_table' => "[]"
                ])
        </div>
    </div>
@endif
