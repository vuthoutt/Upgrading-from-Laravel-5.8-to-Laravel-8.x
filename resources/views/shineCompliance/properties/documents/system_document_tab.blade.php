@if(isset($data))
    <div class="row">
        <div class="col-12 pr-0 mb-1">
            @include('shineCompliance.tables.property_documents', [
                'title' => $data->name ?? '',
                'tableId' => 'property-compliance-system-documents'.($data->id ?? 0),
                'collapsed' => true,
                'plus_link' => $can_add_new,
                'modal_id' => 'property-document-add',
                'system_id' => $data->id,
                'data' => $data->documents ?? [],
                'order_table' => "[]"
                ])
        </div>
    </div>
@endif
