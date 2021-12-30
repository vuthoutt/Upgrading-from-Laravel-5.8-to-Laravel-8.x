@if(isset($data))
    <div class="row">
        <div class="col-12 pr-0 mb-1">
            @include('shineCompliance.tables.property_documents', [
                'title' => $data->name ?? '',
                'tableId' => 'property-compliance-category-documents'.($data->id ?? 0),
                'collapsed' => true,
                'plus_link' => $can_add_new,
                'modal_id' => 'property-document-add',
                'modal_category_id' => $data->id ?? 0,//for add selected dropdown category
                'modal_category' => 1,//for add selected dropdown category
                'edit_link' => $edit_link ?? false,
                'edit_modal' => $edit_modal ?? '',
                'edit_id' => $data->id ?? 0,//for edit selected dropdown category
                'edit_url' => route('shineCompliance.property.post_edit.category',['id' => $data->id]),
                'edit_name' => $data->name ?? '',
                'data' => $data->documents ?? [],
                'order_table' => "[]"
                ])
        </div>
    </div>
@endif
