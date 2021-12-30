<?php
function showParentDepart($data,$tmp){
    if (isset($data)) {
        $tmp[] = $data;
    }
    if (isset($data->allParents) and !is_null($data->allParents) ) {
        return showParentDepart($data->allParents,$tmp);
    }
    return $tmp;
}
// Home
Breadcrumbs::for('home', function ($trail) {
    $trail->push('Home', route('home'));
});
// Home > Summary
Breadcrumbs::for('summary', function ($trail, $data) {
    $trail->push('Home', route('home'));
    $trail->push('Summary', route('summary.riskassessment'));
    $trail->push($data);
});

Breadcrumbs::for('app_audit_trail', function ($trail, $data) {
    $trail->parent('home');
    $trail->push('App Audit Trail');
});

Breadcrumbs::for('home_property_listing', function ($trail,$data) {
    $trail->push('Home', route('home'));
    $trail->push('Properties Listing', route('zone',['client_id'=> optional($data->property)->client_id ]));
    $trail->push($data->property->zone->zone_name ?? '', route('zone.group',['zone_id'=> $data->property->zone_id ?? '','client_id'=> optional($data->property)->client_id  ]));
});
// Home > Profile
Breadcrumbs::for('profile', function ($trail,$data) {
    $trail->parent('home');
    if (\Auth::user()->client_id == $data->client_id) {
        $type = ORGANISATION_TYPE;
        $route_deparment = 'my_organisation.department_users';
    } else {
        $type = CONTRACTOR_TYPE;
        $route_deparment = 'contractor.department_users';
    }
    $trail->push($data->clients->name ?? '', route('my_organisation',['user' => $data->client_id]));

    if ($data->client_id == 1) {
        //recursive
        if(isset($data->department->allParents) and !is_null($data->department->allParents)){
            // dd($data->allParents);
            $tmp = [];
            $all_parents = showParentDepart($data->department->allParents,$tmp);

            $all_parents = array_reverse($all_parents);
            foreach ($all_parents as $parent) {
                $trail->push($parent->name ?? '', route($route_deparment,['department_id' => $parent->id ?? '', 'type' => $type,'client_id' => $data->client_id]));
            }
        }
        $trail->push($data->department->name ?? '', route($route_deparment,['department_id' => $data->department_id ?? '', 'type' => $type,'client_id' => $data->client_id]));
    } else {

        //recursive
        if(isset($data->departmentContractor->allParents) and !is_null($data->departmentContractor->allParents)){
            // dd($data->allParents);
            $tmp = [];
            $all_parents = showParentDepart($data->departmentContractor->allParents,$tmp);

            $all_parents = array_reverse($all_parents);
            foreach ($all_parents as $parent) {
                $trail->push($parent->name ?? '', route($route_deparment,['department_id' => $parent->id ?? '', 'type' => $type,'client_id' => $data->client_id]));
            }
        }
        $trail->push($data->departmentContractor->name ?? '', route($route_deparment,['department_id' => $data->department_id ?? '', 'type' => $type,'client_id' => $data->client_id]));
    }
    $trail->push('Profile', route('profile',['user' => $data->id]));
});

// Home > Profile > Edit
Breadcrumbs::for('user-edit', function ($trail,$data) {
    $trail->parent('profile', $data);
    $trail->push($data->full_name, route('user.get-edit',['user' =>$data->id]));
});

// Home > Profile > Edit
Breadcrumbs::for('user-change-pass', function ($trail,$data) {
    $trail->parent('profile', $data);
    $trail->push('Change Password');
});

function showParent($data,$tmp){
    if (isset($data[0])) {
        $tmp[] = $data[0];
    }
    if (isset($data[0]->allParents) and !is_null($data[0]->allParents) ) {
        return showParent($data[0]->allParents,$tmp);
    }
    return $tmp;
}

// Home > Zone
Breadcrumbs::for('zones', function ($trail, $data) {
    $trail->parent('home');
    $trail->push('Properties Listing', route('zone',['client_id'=> $data->client_id ]));
    if ($data->table_title != '') {
        $trail->push($data->table_title);
    }

    if (isset($data->zone_current)) {
        if(!is_null($data->zone_current->allParents)){
            $tmp = [];
            $all_parents = showParent($data->zone_current->allParents,$tmp);
            $all_parents = array_reverse($all_parents);
            foreach ($all_parents as $parent) {
                $trail->push($parent->zone_name, route('zone.group', ['zone_id' => $parent->id, 'client_id' => $parent->client_id ]));
            }
        }
        $trail->push($data->zone_current->zone_name);
    }
});

// Home > Zone
Breadcrumbs::for('zone_map', function ($trail) {
    $trail->parent('home');
    $trail->push('City Of Westminster', route('zone_map',['client_id'=> 1]));
});

// Home > Zone
Breadcrumbs::for('zone_map_child', function ($trail, $data) {
    $trail->parent('zone_map');
    $trail->push($data->zone_name ?? '', route('zone_map_child',['zone_id' => $data->id,'client_id'=> $data->client_id]));
});

// Home > Zone
Breadcrumbs::for('zone_map_child_detail', function ($trail, $data) {
    $trail->parent('zone_map_child',$data->parent);
    $trail->push($data->zone_name ?? '');
});


// Home > Zone > Zone Group
Breadcrumbs::for('zone_group', function ($trail, $data) {
    $trail->parent('home');
    $trail->push('Properties Listing', route('zone',['client_id'=> $data->client_id ]));
    $trail->push($data->zone_name, route('zone.group', ['zone_id' => $data->id, 'client_id' => $data->client_id ]));
    if ($data->table_title != '') {
        $trail->push($data->table_title);
    }
});



// Home > Zone > Zone Group > property add
Breadcrumbs::for('add_property', function ($trail, $data) {
    $trail->parent('home');
    $trail->push('Properties Listing', route('zone',['client_id'=> $data->client_id ]));
    $trail->push($data->zone_name, route('zone.group', ['zone_id' => $data->id, 'client_id' => $data->client_id ]));
    $trail->push('Add Property');
});

// Home > Zone > Zone Group > property edit
Breadcrumbs::for('edit_property', function ($trail, $data) {
    $trail->parent('home');
    $trail->push('Properties Listing', route('zone',['client_id'=> $data->client_id ]));
    $trail->push($data->zone->zone_name ?? '', route('zone.group',['zone_id'=> $data->zone_id ?? '','client_id'=> optional($data->property)->client_id ]));
    $trail->push($data->name, route('property_detail',['id'=> $data->id, 'section' => SECTION_DEFAULT ]));
    $trail->push('Edit Property');
});


// Home > My organisation
Breadcrumbs::for('my-organisation', function ($trail, $data) {
    $trail->parent('home');
    $trail->push('My Organisation', route('my_organisation',['id'=> $data->id]));
});

// Home > My organisation edit
Breadcrumbs::for('my-organisation-edit', function ($trail, $data) {
    $trail->parent('my-organisation',$data);
    $trail->push('Edit', route('my_organisation.get_edit',['id'=> $data->id]));
});

// Home > My organisation > deparment users
Breadcrumbs::for('department-users', function ($trail, $data) {
    $trail->parent('my-organisation',$data->client);

    if (\Auth::user()->client_id == $data->client->id) {
        $type = ORGANISATION_TYPE;
        $route_deparment = 'my_organisation.department_users';
    } else {
        $type = CONTRACTOR_TYPE;
        $route_deparment = 'contractor.department_users';
    }

    if ($data->client->id == 1) {
        //recursive
        if(isset($data->allParents) and !is_null($data->allParents)){
            // dd($data->allParents);
            $tmp = [];
            $all_parents = showParentDepart($data->allParents,$tmp);

            $all_parents = array_reverse($all_parents);
            foreach ($all_parents as $parent) {
                $trail->push($parent->name ?? '', route($route_deparment,['department_id' => $parent->id ?? '', 'type' => $type,'client_id' => $data->client->id]));
            }
        }
    } else {

        //recursive
        if(isset($data->allParents) and !is_null($data->allParents)){
            // dd($data->allParents);
            $tmp = [];
            $all_parents = showParentDepart($data->allParents,$tmp);

            $all_parents = array_reverse($all_parents);
            foreach ($all_parents as $parent) {
                $trail->push($parent->name ?? '', route($route_deparment,['department_id' => $parent->id ?? '', 'type' => $type,'client_id' => $data->client->id]));
            }
        }

    }
    $trail->push($data->name ?? '', route('contractor.department_users', ['department_id' => $data->id ]));
});
// Home > My contractor
Breadcrumbs::for('all-contractor', function ($trail) {
    $trail->parent('home');
    $trail->push('Contractors', route('contractor.clients'));
});

// Home > all contractors > contractor
Breadcrumbs::for('contractor', function ($trail, $data) {
    $trail->parent('all-contractor');
    $trail->push($data->name ?? '', route('contractor', ['client_id' => $data->id]));
});

// Home > system owner
Breadcrumbs::for('system_owner', function ($trail) {
    $trail->parent('home');
    $trail->push('System Owner', route('contractor',['client_id' => 1]));
});

// Home > system owner
Breadcrumbs::for('user_system_owner', function ($trail, $data) {
    $trail->parent('system_owner');
    $trail->push($data->name ?? '', route('contractor.department_users', ['department_id' => $data->id ]));
});

// Home > all contractors > add contractor
Breadcrumbs::for('contractor-add', function ($trail, $data) {
    $trail->parent('all-contractor');
    $trail->push('Add');
});

// Home > clients list
Breadcrumbs::for('client_list', function ($trail) {
    $trail->parent('home');
    $trail->push('Clients', route('client_list'));

});
// Home > client
Breadcrumbs::for('client', function ($trail, $data) {
    $trail->parent('client_list');
    $trail->push($data->name,route('client.detail',['client_id' => $data->id]));

});

// Home > all clients > client > edit
Breadcrumbs::for('contractor-edit', function ($trail, $data) {
    $trail->parent('contractor', $data);
    $trail->push('Edit');
});

// Home > all clients > add client
Breadcrumbs::for('client-add', function ($trail, $data) {
    $trail->parent('client_list');
    $trail->push('Add');
});

// Home > all contractors > contractor > edit
Breadcrumbs::for('client-edit', function ($trail, $data) {
    $trail->parent('client', $data);
    $trail->push('Edit');
});

// Home > all contractors > contractor > department user
Breadcrumbs::for('contractor-department-users', function ($trail, $data) {
    $trail->parent('contractor',$data->client);
    $trail->push($data->name ?? '', route('contractor.department_users', ['department_id' => $data->id ]));
});
// Home > My contractor (Compliance)
Breadcrumbs::for('all-contractor-compliance', function ($trail) {
    $trail->parent('home_shineCompliance');
    $trail->push('Contractors', route('shineCompliance.contractor.clients'));
});

// Home > Clients > client detail > department user
Breadcrumbs::for('client-department-users', function ($trail, $data) {
    $trail->parent('client',$data->client);
    $trail->push($data->name ?? '', route('contractor.department_users', ['department_id' => $data->id ]));
});

// Home > all contractors > Department > Add User
Breadcrumbs::for('contractor-add-user', function ($trail, $data) {
    $trail->parent('all-contractor-compliance',$data->client);
    $trail->push($data->name ?? '', route('shineCompliance.contractor', ['client_id' => $data->id ]));
    $trail->push('Add User');
});
// Home > all clients > Department > Add User
Breadcrumbs::for('client-add-user', function ($trail, $data) {
    $trail->parent('client_list_compliance',$data->client);
    $trail->push($data->name ?? '', route('shineCompliance.contractor', ['client_id' => $data->id ]));
    $trail->push('Add User');
});

Breadcrumbs::for('my-organisation-add-user', function ($trail, $data) {
    $trail->parent('my-organisation', $data);
    $trail->push('Add User');
});

// Home > property > survey
Breadcrumbs::for('surveys', function ($trail, $data) {
    $trail->parent('properties',$data);
    $trail->push($data['survey_reference'], route('property.surveys', ['survey_id' => $data['survey_id'] ]));
});

// Home > property > survey > survey infomation
Breadcrumbs::for('survey_info', function ($trail, $data) {
    $trail->parent('home_property_listing', $data);
    $trail->push(isset($data->property->name) ? $data->property->name : '', route('property_detail',['id'=> $data->property_id, 'section' => SECTION_DEFAULT ]));
    $trail->push(isset($data->reference) ? $data->reference : '', route('property.surveys',['id'=> $data->id, 'section' => SECTION_DEFAULT ]));
    $trail->push( 'Edit ' . $data->breadcrumb_title);
});

// Home > property > survey > survey edit
Breadcrumbs::for('survey_edit', function ($trail, $data) {
    $trail->parent('home_property_listing', $data);
    $trail->push(isset($data->property->name) ? $data->property->name : '', route('property_detail',['id'=> $data->property_id, 'section' => SECTION_DEFAULT ]));
    $trail->push(isset($data->reference) ? $data->reference : '', route('property.surveys',['id'=> $data->id, 'section' => SECTION_DEFAULT ]));
    $trail->push( 'Edit Survey');
});

// Home > property > survey > add
Breadcrumbs::for('survey_add', function ($trail, $data) {
    $trail->parent('home');
    $trail->push('Properties Listing', route('zone',['client_id'=> $data->client_id ]));
    $trail->push($data->zone->zone_name ?? '', route('zone.group',['zone_id'=> $data->zone_id ?? '','client_id'=> optional($data->property)->client_id ]));
    $trail->push($data->name ?? '', route('property_detail',['id'=> $data->id, 'section' => SECTION_DEFAULT ]));
    $trail->push( 'Add Survey');
});

// Home > property > project
Breadcrumbs::for('projects', function ($trail, $data) {
    $trail->parent('properties', $data->property);
    $trail->push(isset($data->reference) ? $data->reference : '', route('project.index', ['id' => $data->id ]));
});

// Home > property > project > edit
Breadcrumbs::for('project_edit', function ($trail, $data) {
    $trail->parent('properties', $data->property);
    $trail->push(isset($data->reference) ? $data->reference : '', route('project.index', ['id' => $data->id ]));
    $trail->push( 'Edit');
});

// Home > property > project > add
Breadcrumbs::for('project_add', function ($trail, $data) {
    $trail->parent('home');
    $trail->push('Properties Listing', route('zone',['client_id'=> $data->client_id ]));
    $trail->push($data->zone->zone_name ?? '', route('zone.group',['zone_id'=> $data->zone_id ?? '','client_id'=> optional($data->property)->client_id ]));
    $trail->push(isset($data->name) ? $data->name : '', route('property_detail',['id'=> $data->id, 'section' => SECTION_DEFAULT ]));
    $trail->push( 'Add Project');
});

// Home > property > survey > sample eidt
Breadcrumbs::for('sample', function ($trail, $data) {
    $trail->parent('home_property_listing', $data);
    $trail->push(isset($data->property->name) ? $data->property->name : '', route('property_detail',['id'=> $data->property_id, 'section' => SECTION_DEFAULT ]));
    $trail->push(isset($data->reference) ? $data->reference : '', route('property.surveys',['id'=> $data->id, 'section' => SECTION_DEFAULT ]));
    $trail->push('Sample', route('property.surveys',['id'=> $data->id, 'section' => SECTION_DEFAULT ]));
    $trail->push( 'Edit ' . $data->breadcrumb_title);
});

// Home > property > survey > survey question
Breadcrumbs::for('survey_question', function ($trail, $data) {
    $trail->parent('home_property_listing', $data);
    $trail->push(isset($data->property->name) ? $data->property->name : '', route('property_detail',['id'=> $data->property_id, 'section' => SECTION_DEFAULT ]));
    $trail->push(isset($data->reference) ? $data->reference : '', route('property.surveys',['id'=> $data->id, 'section' => SECTION_DEFAULT ]));
    $trail->push( 'Edit Method By Questionnaire');
});

// Home > property > survey > survey site data
Breadcrumbs::for('survey_property_info', function ($trail, $data) {
    $trail->parent('home_property_listing', $data);
    $trail->push(isset($data->property->name) ? $data->property->name : '', route('property_detail',['id'=> $data->property_id, 'section' => SECTION_DEFAULT ]));
    $trail->push(isset($data->reference) ? $data->reference : '', route('property.surveys',['id'=> $data->id, 'section' => SECTION_DEFAULT ]));
    $trail->push( 'Edit Property Information');
});

// Home > Properties
Breadcrumbs::for('properties', function ($trail, $data) {
    $trail->parent('home');
    $trail->push('Properties Listing', route('zone',['client_id'=> $data->client_id ]));
    $trail->push($data->zone->zone_name ?? '', route('zone.group',['zone_id'=> $data->zone_id ?? '','client_id'=> $data->client_id ]));
    $trail->push(isset($data->name) ? $data->name : '', route('property_detail',['id'=> $data->id, 'section' => SECTION_DEFAULT ]));
    if ($data->table_title != '') {
        $trail->push($data->table_title);
    }
});

// Home > Properties > area
Breadcrumbs::for('properties_area', function ($trail, $data) {
    $trail->parent('home_property_listing', $data);
    $trail->push(isset($data->property->name) ? $data->property->name : '', route('property_detail',['id'=> $data->property->id, 'section' => SECTION_DEFAULT ]));
    $trail->push($data->title, route('property_detail',['id'=> $data->property_id, 'area'=> $data->id, 'section' => SECTION_AREA_FLOORS_SUMMARY ]));
    if ($data->table_title != '') {
        $trail->push($data->table_title);
    }
});

// Home > Properties > area > location
Breadcrumbs::for('properties_location', function ($trail, $data) {
    $trail->parent('home_property_listing', $data);
    $trail->push(isset($data->property->name) ? $data->property->name : '', route('property_detail',['id'=> $data->property_id, 'section' => SECTION_DEFAULT ]));
    $trail->push(isset($data->area->title) ? $data->area->title : '', route('property_detail',['id'=> $data->property_id, 'area'=> $data->area_id, 'section' => SECTION_AREA_FLOORS_SUMMARY ]));
    $trail->push(isset($data->location_reference) ? $data->location_reference : '', route('property_detail',['id'=> $data->property_id, 'location'=> $data->id, 'section' => SECTION_ROOM_LOCATION_SUMMARY ]));
    if ($data->table_title != '') {
        $trail->push($data->table_title);
    }
});

// Home > Properties > area > location add
Breadcrumbs::for('properties_location_add', function ($trail, $data) {
    $trail->parent('home_property_listing', $data);
    $trail->push(isset($data->property->name) ? $data->property->name : '', route('property_detail',['id'=> $data->property_id, 'section' => SECTION_DEFAULT ]));
    $trail->push($data->title, route('property_detail',['id'=> $data->property_id, 'area'=> $data->id, 'section' => SECTION_AREA_FLOORS_SUMMARY ]));
    $trail->push('Add Register Location');
});

// Home > Properties > area > location edit
Breadcrumbs::for('properties_location_edit', function ($trail, $data) {
    $trail->parent('home_property_listing', $data);
    $trail->push(isset($data->property->name) ? $data->property->name : '', route('property_detail',['id'=> $data->property_id, 'section' => SECTION_DEFAULT ]));
    $trail->push(isset($data->area->title) ? $data->area->title : '', route('property_detail',['id'=> $data->property_id, 'area'=> $data->area_id, 'section' => SECTION_AREA_FLOORS_SUMMARY ]));
    $trail->push(isset($data->reference) ? $data->reference : '', route('property_detail',['id'=> $data->property_id, 'location'=> $data->id, 'section' => SECTION_ROOM_LOCATION_SUMMARY ]));
    $trail->push('Edit Register Location');
});

// Home > Properties > area > location > item
Breadcrumbs::for('properties_item', function ($trail, $data) {
    $trail->parent('home_property_listing', $data);
    $trail->push(isset($data->property->name) ? $data->property->name : '', route('property_detail',['id'=> $data->property_id, 'section' => SECTION_DEFAULT ]));
    $trail->push(isset($data->area->title) ? $data->area->title : '', route('property_detail',['id'=> $data->property_id, 'area'=> $data->area_id, 'section' => SECTION_AREA_FLOORS_SUMMARY ]));
    $trail->push(isset($data->location->location_reference) ? $data->location->location_reference : '', route('property_detail',['id'=> $data->property_id, 'location'=> $data->location_id, 'section' => SECTION_ROOM_LOCATION_SUMMARY ]));
    $trail->push(isset($data->reference) ? $data->reference : '', route('item.index',['id'=> $data->id ]));
});

// Home > Properties > area > location > item > edit
Breadcrumbs::for('properties_item_edit', function ($trail, $data) {
    $trail->parent('home_property_listing', $data);
    $trail->push(isset($data->property->name) ? $data->property->name : '', route('property_detail',['id'=> $data->property_id, 'section' => SECTION_DEFAULT ]));
    $trail->push(isset($data->area->title) ? $data->area->title : '', route('property_detail',['id'=> $data->property_id,'area'=> $data->area_id, 'section' => SECTION_AREA_FLOORS_SUMMARY ]));
    $trail->push(isset($data->location->location_reference) ? $data->location->location_reference : '', route('property_detail',['id'=> $data->property_id,'location'=> $data->location_id, 'section' => SECTION_ROOM_LOCATION_SUMMARY ]));
    $trail->push(isset($data->reference) ? $data->reference : '', route('item.index',['id'=> $data->id]));
    $trail->push('Edit Register Item', route('item.index',['id'=> $data->id]));
});

// Home > Properties > area > location > item > add
Breadcrumbs::for('properties_item_add', function ($trail, $data) {
    $trail->parent('home_property_listing', $data);
    $trail->push(isset($data->property->name) ? $data->property->name : '', route('property_detail',['id'=> $data->property_id, 'section' => SECTION_DEFAULT ]));
    $trail->push(isset($data->area->title) ? $data->area->title : '', route('property_detail',['id'=> $data->property_id,'area'=> $data->area_id, 'section' => SECTION_AREA_FLOORS_SUMMARY ]));
    $trail->push(isset($data->location_reference) ? $data->location_reference : '', route('property_detail',['id'=> $data->property_id,'location'=> $data->id, 'section' => SECTION_ROOM_LOCATION_SUMMARY ]));
    $trail->push('Add Register Item', route('item.index',['id'=> $data->id]));
});

// Home > Properties > survey
Breadcrumbs::for('survey', function ($trail, $data) {
    $trail->parent('home_property_listing', $data);
    $trail->push(isset($data->property->name) ? $data->property->name : '', route('property_detail',['id'=> $data->property_id, 'section' => SECTION_DEFAULT ]));
    $trail->push(isset($data->reference) ? $data->reference : '', route('property.surveys',['id'=> $data->id, 'section' => SECTION_DEFAULT ]));
    if ($data->table_title != '') {
        $trail->push($data->table_title);
    }
});

// Home > Properties > survey > area
Breadcrumbs::for('survey_area', function ($trail, $data) {
    $trail->parent('home_property_listing', $data);
    $trail->push(isset($data->property->name) ? $data->property->name : '', route('property_detail',['id'=> $data->property_id, 'section' => SECTION_DEFAULT ]));
    $trail->push(isset($data->survey->reference) ? $data->survey->reference : '', route('property.surveys',['id'=> $data->survey_id, 'section' => SECTION_DEFAULT ,]));
    $trail->push($data->title, route('property.surveys',['id'=> $data->survey_id,'area'=> $data->id, 'section' => SECTION_AREA_FLOORS_SUMMARY ]));

    if ($data->table_title != '') {
        $trail->push($data->table_title);
    }
});

// Home > Properties > survey > area > location
Breadcrumbs::for('survey_location', function ($trail, $data) {
    $trail->parent('home_property_listing', $data);
    $trail->push(isset($data->property->name) ? $data->property->name : '', route('property_detail',['id'=> $data->property_id, 'section' => SECTION_DEFAULT ]));
    $trail->push(isset($data->survey->reference) ? $data->survey->reference : '', route('property.surveys',['id'=> $data->survey_id, 'section' => SECTION_DEFAULT ]));
    $trail->push(isset($data->area->title) ? $data->area->title : '', route('property.surveys',['id'=> $data->survey_id,'area'=> $data->area_id, 'section' => SECTION_AREA_FLOORS_SUMMARY ]));
    $trail->push(isset($data->location_reference) ? $data->location_reference : '', route('property.surveys',['id'=> $data->survey_id,'location'=> $data->id, 'section' => SECTION_ROOM_LOCATION_SUMMARY ]));
    if ($data->table_title != '') {
        $trail->push($data->table_title);
    }
});

// Home > Properties > survey > area > location add
Breadcrumbs::for('survey_location_add', function ($trail, $data) {
    $trail->parent('home_property_listing', $data);
    $trail->push(isset($data->property->name) ? $data->property->name : '', route('property_detail',['id'=> $data->property_id, 'section' => SECTION_DEFAULT ]));
    $trail->push(isset($data->survey->reference) ? $data->survey->reference : '', route('property.surveys',['id'=> $data->survey_id, 'section' => SECTION_DEFAULT ]));
    $trail->push($data->title, route('property.surveys',['id'=> $data->survey_id,'area'=> $data->id, 'section' => SECTION_AREA_FLOORS_SUMMARY ]));
    $trail->push('Add Survey Location');
});

// Home > Properties > survey > area > location edit
Breadcrumbs::for('survey_location_edit', function ($trail, $data) {
    $trail->parent('home_property_listing', $data);
    $trail->push(isset($data->property->name) ? $data->property->name : '', route('property_detail',['id'=> $data->property_id, 'section' => SECTION_DEFAULT ]));
    $trail->push(isset($data->survey->reference) ? $data->survey->reference : '', route('property.surveys',['id'=> $data->survey_id, 'section' => SECTION_DEFAULT ]));
    $trail->push(isset($data->area->title) ? $data->area->title : '', route('property.surveys',['id'=> $data->survey_id,'area'=> $data->area_id, 'section' => SECTION_AREA_FLOORS_SUMMARY ]));
    $trail->push(isset($data->location_reference) ? $data->location_reference : '', route('property.surveys',['id'=> $data->survey_id,'location'=> $data->id, 'section' => SECTION_ROOM_LOCATION_SUMMARY ]));
    $trail->push('Edit Survey Location');
});

// Home > Properties > survey > area > location > item
Breadcrumbs::for('survey_item', function ($trail, $data) {
    $trail->parent('home_property_listing', $data);
    $trail->push(isset($data->property->name) ? $data->property->name : '', route('property_detail',['id'=> $data->property_id, 'section' => SECTION_DEFAULT ]));
    $trail->push(isset($data->survey->reference) ? $data->survey->reference : '', route('property.surveys',['id'=> $data->survey_id, 'section' => SECTION_DEFAULT ]));
    $trail->push(isset($data->area->title) ? $data->area->title : '', route('property.surveys',['id'=> $data->survey_id,'area'=> $data->area_id, 'section' => SECTION_AREA_FLOORS_SUMMARY ]));
    $trail->push(isset($data->location->location_reference) ? $data->location->location_reference : '', route('property.surveys',['id'=> $data->survey_id,'location'=> $data->location_id, 'section' => SECTION_ROOM_LOCATION_SUMMARY ]));
    $trail->push(isset($data->reference) ? $data->reference : '');
});

// Home > Properties > survey > area > location > item > edit
Breadcrumbs::for('survey_item_edit', function ($trail, $data) {
    $trail->parent('home_property_listing', $data);
    $trail->push(isset($data->property->name) ? $data->property->name : '', route('property_detail',['id'=> $data->property_id, 'section' => SECTION_DEFAULT ]));
    $trail->push(isset($data->survey->reference) ? $data->survey->reference : '', route('property.surveys',['id'=> $data->survey_id, 'section' => SECTION_DEFAULT ]));
    $trail->push(isset($data->area->title) ? $data->area->title : '', route('property.surveys',['id'=> $data->survey_id,'area'=> $data->area_id, 'section' => SECTION_AREA_FLOORS_SUMMARY ]));
    $trail->push(isset($data->location->location_reference) ? $data->location->location_reference : '', route('property.surveys',['id'=> $data->survey_id,'location'=> $data->location_id, 'section' => SECTION_ROOM_LOCATION_SUMMARY ]));
    $trail->push(isset($data->reference) ? $data->reference : '', route('item.index',['id'=> $data->id]));
    $trail->push('Edit Survey Item', route('item.index',['id'=> $data->id]));
});

// Home > Properties > survey > area > location > item add
Breadcrumbs::for('survey_item_add', function ($trail, $data) {
    $trail->parent('home_property_listing', $data);
    $trail->push(isset($data->property->name) ? $data->property->name : '', route('property_detail',['id'=> $data->property_id, 'section' => SECTION_DEFAULT ]));
    $trail->push(isset($data->survey->reference) ? $data->survey->reference : '', route('property.surveys',['id'=> $data->survey_id, 'section' => SECTION_DEFAULT ]));
    $trail->push(isset($data->area->title) ? $data->area->title : '', route('property.surveys',['id'=> $data->survey_id,'area'=> $data->area_id, 'section' => SECTION_AREA_FLOORS_SUMMARY ]));
    $trail->push(isset($data->reference) ? $data->reference : '', route('property.surveys',['id'=> $data->survey_id,'location'=> $data->id, 'section' => SECTION_ROOM_LOCATION_SUMMARY ]));
    $trail->push('Add Survey Item');
});

// Home > Data Centre > ...
Breadcrumbs::for('data_centre', function ($trail, $data) {
    $trail->parent('home');
    $trail->push('Data Centre','#');
    $trail->push($data);
});

// Home > Resource
Breadcrumbs::for('e_learning', function ($trail, $data) {
    $trail->parent('home');
    $trail->push('E-Learning');
});
// Home > Resource
Breadcrumbs::for('blue_light', function ($trail, $data) {
    $trail->parent('home');
    $trail->push('Blue Light Service');
});
// Home > Resource
Breadcrumbs::for('job_role', function ($trail, $data) {
    $trail->parent('home');
    $trail->push('Job Roles', route('shineCompliance.list_job_role.compliance'));
});
// Home > Resource
Breadcrumbs::for('job_role_detail', function ($trail, $data) {
    $trail->parent('job_role', $data);
    $trail->push($data->name ?? '');
});

// Home > Resource
Breadcrumbs::for('resource_doc', function ($trail, $data) {
    $trail->parent('home');
    $trail->push('Resource Document');
});

// Home > Audit Trail
Breadcrumbs::for('audit_trail', function ($trail, $data) {
    $trail->parent('home');
    $trail->push('Audit Trail');
});

// Home > Tool Box
Breadcrumbs::for('tool_box', function ($trail, $data) {
    $trail->parent('home');
    $trail->push('Admin Tool');
});

// Home > Work Requests
Breadcrumbs::for('work_requests', function ($trail) {
    $trail->parent('home');
    $trail->push('Work Requests', route('wr.get_list'));
});

// Home > Work Requests > add
Breadcrumbs::for('work_requests_add', function ($trail, $data) {
    $trail->parent('work_requests');
    $trail->push('Add Work Request');
});

// Home > Work Requests > detail
Breadcrumbs::for('work_requests_detail', function ($trail,$data) {
    $trail->parent('work_requests');
    $trail->push($data->reference, route('wr.details',['id' => $data->id]));
});

// Home > Work Requests > detail > edit
Breadcrumbs::for('work_requests_edit', function ($trail,$data) {
    $trail->parent('work_requests_detail', $data);
    $trail->push('Edit', route('wr.get_edit',['id' => $data->id]));
});




// Home > Resource > Department Management
Breadcrumbs::for('department_management', function ($trail, $data) {
    $trail->parent('home');
    $trail->push('Department Management', route('department_list'));

    if ($data) {

        //recursive
        if(isset($data->allParents) and !is_null($data->allParents)){
            // dd($data->allParents);
            $tmp = [];
            $all_parents = showParentDepart($data->allParents,$tmp);

            $all_parents = array_reverse($all_parents);
            foreach ($all_parents as $parent) {
                $trail->push($parent->name, route('department_list',['parent_id' => $parent->id]));
            }
        }
        $trail->push($data->name, route('department_list',['parent_id' => $data->id]));
    }
});

// Home ShineCompliance
Breadcrumbs::for('home_shineCompliance', function ($trail) {
    $trail->push('Home', route('shineCompliance.home_shineCompliance'));
});
Breadcrumbs::for('assessment_user', function ($trail) {
    $trail->parent('home_shineCompliance');
    $trail->push('Assessment', route('shineCompliance.assessment_user'));
});
// Home ShineCompliance > Profile
Breadcrumbs::for('profile_shineCompliance', function ($trail,$data) {
    $trail->parent('home_shineCompliance');
    if (\Auth::user()->client_id == $data->client_id) {
        $type = ORGANISATION_TYPE;
        $route_deparment = '#';
    } else {
        $type = CONTRACTOR_TYPE;
        $route_deparment = '#';
    }

    if ($data->client_id == 1) {
        $trail->push($data->clients->name ?? '', route('my_organisation',['client_id' => $data->client_id]));
        //recursive
        if(isset($data->department->allParents) and !is_null($data->department->allParents)){
            // dd($data->allParents);
            $tmp = [];
            $all_parents = showParentDepart($data->department->allParents,$tmp);

            $all_parents = array_reverse($all_parents);
            foreach ($all_parents as $parent) {
                $trail->push($parent->name ?? '', route('my_organisation.department_users', ['department_id' => $parent->id, 'type' => ORGANISATION_TYPE, 'client_id' => $data->client_id]));
            }
        }
        $trail->push($data->department->name ?? '', route('my_organisation.department_users', ['department_id' => $data->department->id, 'type' => ORGANISATION_TYPE, 'client_id' => $data->client_id]));
    } else {

        $trail->push($data->clients->name ?? '', route('contractor',['client_id' => $data->client_id]));
        //recursive
        if(isset($data->departmentContractor->allParents) and !is_null($data->departmentContractor->allParents)){
            // dd($data->allParents);
            $tmp = [];
            $all_parents = showParentDepart($data->departmentContractor->allParents,$tmp);

            $all_parents = array_reverse($all_parents);
            foreach ($all_parents as $parent) {
                $trail->push($parent->name ?? '', route('contractor.department_users', ['department_id' => $parent->id, 'type' => CONTRACTOR_TYPE, 'client_id' => $data->client_id ]));
            }
        }
        $trail->push($data->departmentContractor->name ?? '', route('contractor.department_users', ['department_id' => $data->departmentContractor->id, 'type' => CONTRACTOR_TYPE, 'client_id' => $data->client_id ]));
    }
    $trail->push('Profile', route('shineCompliance.profile-shineCompliance',['user' => $data->id]));
});

// Home > Profile > Edit_ShineCompliance
Breadcrumbs::for('user_edit_shineCompliance', function ($trail,$data) {
    $trail->parent('profile_shineCompliance', $data);
    $trail->push($data->full_name ?? '', route('shineCompliance.user.get-edit-profile',['user' =>$data->id ?? '']));
});


// COMPLIANCE BREADCRUMB
//Home >List-zone
Breadcrumbs::for('list_zone', function ($trail) {
    $trail->parent('home_shineCompliance');
//    $trail->push($client->name ?? '',route('shineCompliance.zone'));
});
Breadcrumbs::for('all_register_zone_overall', function ($trail, $data) {
    if(isset($data->id) &&  $data->id != 0){
        $trail->parent('list_zone',$data);
        $trail->push('Client Register Group',route('shineCompliance.all_zone.register',['client_id' => $data->id ?? 0]));
    }else{
        $trail->parent('home_shineCompliance');
        $trail->push('All Register Group',route('shineCompliance.all_zone.register'));
    }

});

Breadcrumbs::for('all_register_zone_asbestos', function ($trail, $data) {
    $trail->parent('all_register_zone_overall', $data);
    if(isset($data->id)){
        $trail->push('Register Client Risk',route('shineCompliance.all_zone.asbestos',['client_id' =>$data->id ?? '']));
    }else{
        $trail->push('Register',route('shineCompliance.all_zone.asbestos'));
    }
});

//Home >List-zone > register property
Breadcrumbs::for('all_register_zone_asbestos_summary', function ($trail,$data) {
    $trail->parent('all_register_zone_asbestos', $data);
    $trail->push($data->breadcrumb_title ?? '');
});

Breadcrumbs::for('all_register_zone_fire', function ($trail, $data) {
    $trail->parent('all_register_zone_overall', $data);
    if(isset($data->id)){
        $trail->push('Fire Client',route('shineCompliance.all_zone.fire',['client_id' =>$data->id]));
    }else{
        $trail->push('Fire ',route('shineCompliance.all_zone.fire'));
    }
});

Breadcrumbs::for('all_register_zone_fire_summary', function ($trail,$data) {
    $trail->parent('all_register_zone_fire', $data ?? []);
    $trail->push($data->breadcrumb_title ?? '');
});

Breadcrumbs::for('all_register_zone_water', function ($trail,$data) {
    $trail->parent('all_register_zone_overall', $data ?? []);
    if(isset($data->id)){
        $trail->push('Water Client',route('shineCompliance.all_zone.water',['client_id' => $data->id]));
    }else{
        $trail->push('Water ', route('shineCompliance.all_zone.water'));
    }
});

Breadcrumbs::for('all_register_zone_water_summary', function ($trail,$data) {
    $trail->parent('all_register_zone_water', $data ?? []);
    $trail->push($data->breadcrumb_title ?? '');
});
//Home >List-zone > list property
Breadcrumbs::for('zone_detail_shineCompliance', function ($trail,$zone) {
    $trail->parent('list_zone');
    $trail->push($zone->zone_name ?? '',route('shineCompliance.zone.details',['zone_id' =>$zone->id ?? '']));
    $trail->push('Details',route('shineCompliance.zone.details',['zone_id' =>$zone->id ?? '']));
});

//Home >List-zone > list property//Home >List-zone > list property
Breadcrumbs::for('zone_properties_shineCompliance', function ($trail,$zone) {
    $trail->parent('list_zone', $zone->clients);
    $trail->push($zone->zone_name ?? '',route('shineCompliance.zone.details',['zone_id' =>$zone->id ?? '']));
    $trail->push('Details',route('shineCompliance.zone.details',['zone_id' =>$zone->id ?? '']));
});

//Home >List-zone > list property//Home >List-zone > list property
Breadcrumbs::for('mixed_zone_properties_shineCompliance', function ($trail,$data) {
    $trail->parent('list_zone');
    $trail->push($data);
});
//Home >List-zone > register property
Breadcrumbs::for('zone_register_shinecompliance', function ($trail,$zone) {
    $trail->parent('list_zone',$zone);
    $trail->push($zone->zone_name ?? '',route('shineCompliance.zone.details',['zone_id' =>$zone->id ?? '']));
});

//Home >List-zone > register overall
Breadcrumbs::for('register_zone_overall', function ($trail,$zone) {
    $trail->parent('zone_register_shinecompliance', $zone);
    $trail->push('Register',route('shineCompliance.zone.register',['id' => $zone->id ?? 0]));
});

//Home >zone > list property > asbestos
Breadcrumbs::for('register_zone_asbestos', function ($trail, $zone) {
    $trail->parent('zone_register_shinecompliance', $zone);
    $trail->push('Asbestos Risk',route('shineCompliance.zone.asbestos',['id' => $zone->id ?? 0]));
});

//Home >zone > list property > Fire
Breadcrumbs::for('register_zone_fire', function ($trail, $zone) {
    $trail->parent('zone_register_shinecompliance', $zone);
    $trail->push('Fire Risk',route('shineCompliance.zone.fire',['id' => $zone->id ?? 0]));
});

//Home >zone > list property > Gas
Breadcrumbs::for('register_zone_gas', function ($trail, $zone) {
    $trail->parent('zone_register_shinecompliance', $zone);
    $trail->push('Gas Risk',route('shineCompliance.zone.gas',['id' => $zone->id ?? 0]));
});

//Home >zone > list property > Water
Breadcrumbs::for('register_zone_water', function ($trail, $zone) {
    $trail->parent('zone_register_shinecompliance', $zone);
    $trail->push('Water Risk',route('shineCompliance.zone.water',['id' => $zone->id ?? 0]));
});

//Home >zone > list property > Water
Breadcrumbs::for('register_zone_water_summary', function ($trail, $zone) {
    $trail->parent('zone_register_shinecompliance', $zone);
    $trail->push('Water Risk',route('shineCompliance.zone.water',['id' => $zone->id ?? 0]));
    $trail->push($zone->breadcrumb_title ?? '');
});

//Home >List-zone > register property
Breadcrumbs::for('register_zone_asbestos_summary', function ($trail,$zone) {
    $trail->parent('zone_register_shinecompliance',$zone);
    $trail->push('Asbestos Risk',route('shineCompliance.zone.asbestos',['id' => $zone->id ?? 0]));
    $trail->push($zone->breadcrumb_title ?? '');
});

//Home >List-zone > register property
Breadcrumbs::for('register_zone_fire_summary', function ($trail,$zone) {
    $trail->parent('zone_register_shinecompliance',$zone);
    $trail->push('Fire Risk',route('shineCompliance.zone.fire',['id' => $zone->id ?? 0]));
    $trail->push($zone->breadcrumb_title ?? '');
});

//Home >List-zone > list property > property_add
Breadcrumbs::for('property_add', function ($trail,$zone) {
    $trail->parent('list_zone', $zone->clients );
    $trail->push($zone->zone_name ?? '',route('shineCompliance.zone.details',['zone_id' =>$zone->id ?? '']));
    $trail->push('Add Property',route('shineCompliance.property.get_add',['zone_id' =>$zone->id ?? '']));
//    $trail->push($zone->name,route('shineCompliance.property.property_detail'));
});


//Home >List-zone > list property > property > add_sub
Breadcrumbs::for('sub_property_add', function ($trail,$property) {
    $trail->parent('home_shineCompliance');
    $trail->push($property->zone->zone_name ?? '',route('shineCompliance.zone.details',['zone_id' =>$property->zone_id ?? '']));
    $trail->push($property->name ?? '',route('shineCompliance.property.sub_property',['property_id' =>$property->id ?? '']));
    $trail->push('Add Property',route('shineCompliance.property.get_add',['zone_id' =>$property->zone_id ?? '']));
//    $trail->push($zone->name,route('shineCompliance.property.property_detail'));
});


//Home >zone > list property > property_detail
Breadcrumbs::for('property_detail', function ($trail,$property) {
    $trail->parent('home_shineCompliance');
    $trail->push($property->zone->zone_name ?? '',route('shineCompliance.zone.details',['zone_id' =>$property->zone_id ?? '']));
    $trail->push($property->name ?? '',route('shineCompliance.property.property_detail',['property_id' =>$property->id ?? '']));
});


//Home >zone > list property > property > assessment
Breadcrumbs::for('assessment_list', function ($trail,$property) {
    $trail->parent('property_detail',$property);
    $trail->push("Assessment List");
});

//Home >zone > list property > property > sub > assessment
Breadcrumbs::for('sub_assessment_list_origin', function ($trail,$property) {
    $trail->parent('home_shineCompliance');
    $trail->push($property->zone->zone_name ?? '',route('shineCompliance.zone.details',['zone_id' =>$property->zone_id ?? '']));
    $trail->push($property->parents->name ?? '',route('shineCompliance.property.property_detail',['property_id' =>$property->parent_id ?? '']));
    $trail->push($property->name ?? '',route('shineCompliance.assessment.index',['property_id' =>$property->id ?? '']));
});
//Home >zone > list property > property > sub > assessment
Breadcrumbs::for('sub_assessment_list', function ($trail,$property) {
    $trail->parent('sub_assessment_list_origin',$property);
    $trail->push("Assessment List");
});

//Home >zone > list property > property > assessment > add survey
Breadcrumbs::for('survey_add_shineCompliance', function ($trail,$data) {
    $trail->parent('property_detail',$data);
    $trail->push("Add Survey");
});

//Home >List-zone > list property > property > sub_list
Breadcrumbs::for('sub_survey_add_shineCompliance', function ($trail,$property) {
    $trail->parent('sub_assessment_list_origin',$property);
    $trail->push('Add Survey');
});

//Home >zone > list property > property_detail > sub_property_detail
Breadcrumbs::for('sub_property_detail', function ($trail,$property) {
    $trail->parent('home_shineCompliance');
    $trail->push($property->zone->zone_name ?? '',route('shineCompliance.zone.details',['zone_id' =>$property->zone_id ?? '']));
    $trail->push($property->parents->name ?? '',route('shineCompliance.property.property_detail',['property_id' =>$property->parent_id ?? '']));
    $trail->push($property->name ?? '',route('shineCompliance.property.property_detail',['property_id' =>$property->id ?? '']));
});

//Home >List-zone > list property > property > edit_sub
Breadcrumbs::for('sub_property_edit', function ($trail,$property) {
    $trail->parent('sub_property_detail',$property);
    $trail->push('Edit Property');
//    $trail->push($zone->name,route('shineCompliance.property.property_detail'));
});

//Home >zone > list property > property_detail > list vehicle parking
Breadcrumbs::for('register_vehicle_parking', function ($trail, $property = []) {
    $trail->parent('property_detail', $property);
    $trail->push('Vehicle Parking List', route('shineCompliance.property.parking',['property_id' => $property->id ?? 0]));
});

//Home >zone > list property > property_detail > list vehicle parking
Breadcrumbs::for('sub_register_vehicle_parking', function ($trail, $property = []) {
    $trail->parent('home_shineCompliance');
    $trail->push($property->zone->zone_name ?? '',route('shineCompliance.zone.details',['zone_id' =>$property->zone_id ?? '']));
    $trail->push($property->parents->name ?? '',route('shineCompliance.property.property_detail',['property_id' =>$property->parent_id ?? '']));
    $trail->push($property->name ?? '',route('shineCompliance.property.property_detail',['property_id' =>$property->id ?? '']));
    $trail->push('Vehicle Parking List', route('shineCompliance.property.parking',['property_id' => $property->id ?? 0]));
});

//Home >zone > list property > property_detail > list vehicle parking
Breadcrumbs::for('register_vehicle_parking_add', function ($trail, $property) {
    $trail->parent('register_vehicle_parking', $property);
    $trail->push('Add Vehicle Parking');
});

//Home >zone > list property > property_detail > list vehicle parking
Breadcrumbs::for('register_vehicle_parking_detail', function ($trail, $data) {
    $trail->parent('register_vehicle_parking', $data->property ?? []);
    $trail->push($data->reference ?? '', route('shineCompliance.assessment.get_vehicle_parking',['id' => $data->id ?? 0]));
});

//Home >zone > list property > property_detail > list fire exist
Breadcrumbs::for('register_vehicle_parking_edit', function ($trail, $data) {
    $trail->parent('register_vehicle_parking_detail', $data);
    $trail->push('Edit Vehicle Parking');
});

//Home >zone > list property > property_detail > overall risk
Breadcrumbs::for('register_overall', function ($trail, $property) {
    $trail->parent('property_detail', $property);
    $trail->push('Overall Risk',route('shineCompliance.property.register',['id' => $property->id ?? 0]));
});

//Home >zone > list property > property_detail > asbestos
Breadcrumbs::for('register_asbestos', function ($trail, $property) {
    $trail->parent('property_detail', $property);
    $trail->push('Asbestos Risk',route('shineCompliance.property.asbestos',['id' => $property->id ?? 0]));
});

//Home >zone > list property > property_detail > asbestos > summary
Breadcrumbs::for('register_asbestos_summary', function ($trail, $property) {
    $trail->parent('register_asbestos', $property);
    $trail->push($property->breadcrumb_title ?? '');
});

//Home >zone > list property > property_detail > gas
Breadcrumbs::for('register_gas', function ($trail, $property) {
    $trail->parent('property_detail', $property);
    $trail->push('Gas Risk',route('shineCompliance.property.gas',['id' => $property->id ?? 0]));
});

//Home >zone > list property > property_detail > water
Breadcrumbs::for('register_water', function ($trail, $property) {
    $trail->parent('property_detail', $property);
    $trail->push('Water Risk',route('shineCompliance.property.water',['id' => $property->id ?? 0]));
});

//Home >zone > list property > property_detail > water > summary
Breadcrumbs::for('register_water_summary', function ($trail, $property) {
    $trail->parent('register_water', $property);
    $trail->push($property->breadcrumb_title ?? '');
});

//Home >zone > list property > property_detail > fire
Breadcrumbs::for('register_fire', function ($trail, $property) {
    $trail->parent('property_detail', $property);
    if($property->assess_type == ASSESSMENT_FIRE_TYPE){
        $trail->push('Fire Risk',route('shineCompliance.property.fire',['id' => $property->id ?? 0]));
    }
    elseif($property->assess_type == ASSESSMENT_WATER_TYPE){
        $trail->push('Water Risk',route('shineCompliance.property.water',['id' => $property->id ?? 0]));
    }

});

//Home >zone > list property > property_detail > fire > summary
Breadcrumbs::for('register_fire_summary', function ($trail, $property) {
    $trail->parent('register_fire', $property);
    $trail->push($property->breadcrumb_title ?? '');
});

//Home >zone > list property > property_detail > health and safety
Breadcrumbs::for('register_health_and_safety', function ($trail, $property) {
    $trail->parent('property_detail', $property);
    $trail->push('Health And Safety',route('shineCompliance.property.health_and_safety',['id' => $property->id ?? 0]));
});

//Home >zone > list property > property_detail > list fire exist and assembly
Breadcrumbs::for('sub_register_fire_exit_and_assembly_list', function ($trail, $property) {
    $trail->parent('home_shineCompliance', $property);
    $trail->push($property->parents->name ?? '',route('shineCompliance.property.property_detail',['property_id' =>$property->parent_id ?? '']));
    $trail->push($property->name ?? '',route('shineCompliance.property.property_detail',['property_id' =>$property->id ?? '']));
    $trail->push('Fire Exit & Assembly Point List',route('shineCompliance.property.fireExit',['id' => $property->id ?? 0]));
});

//Home >zone > list property > property_detail > sub > list fire exist and assembly
Breadcrumbs::for('register_fire_exit_and_assembly_list', function ($trail, $property) {
    $trail->parent('property_detail', $property);
    $trail->push('Fire Exit & Assembly Point List',route('shineCompliance.property.fireExit',['id' => $property->id ?? 0]));
});

//Home >zone > list property > property_detail > list fire exist
Breadcrumbs::for('register_fire_exit_add', function ($trail, $property) {
    $trail->parent('register_fire_exit_and_assembly_list', $property);
    $trail->push('Add Fire Exit');
});

//Home >zone > list property > property_detail > list fire exist
Breadcrumbs::for('register_fire_exit_detail', function ($trail, $data) {
    $trail->parent('register_fire_exit_and_assembly_list', $data->property ?? []);
    $trail->push($data->reference ?? '', route('shineCompliance.assessment.get_fire_exit',['id' => $data->id ?? 0]));
});

//Home >zone > list property > property_detail > list vehicle parking
Breadcrumbs::for('register_fire_exit_edit', function ($trail, $data) {
    $trail->parent('register_fire_exit_detail', $data);
    $trail->push('Edit Fire Exit');
});

//Home >zone > list property > property_detail > list assembly point
Breadcrumbs::for('register_assembly_point_add', function ($trail, $property) {
    $trail->parent('register_fire_exit_and_assembly_list', $property);
    $trail->push('Add Assembly Point');
});

//Home >zone > list property > property_detail > list assembly point
Breadcrumbs::for('register_assembly_point_detail', function ($trail, $data) {
    $trail->parent('register_fire_exit_and_assembly_list', $data->property ?? []);
    $trail->push($data->reference ?? '', route('shineCompliance.assessment.get_assembly_point',['id' => $data->id ?? 0]));
});

//Home >zone > list property > property_detail > list assembly point
Breadcrumbs::for('register_assembly_point_edit', function ($trail, $data) {
    $trail->parent('register_assembly_point_detail', $data);
    $trail->push('Edit Assembly Point');
});

//Home >zone > list property > property_detail > list assembly point
Breadcrumbs::for('register_hazard_add', function ($trail, $property) {
    $trail->parent('register_fire', $property);
    $trail->push('Add Register Hazard');
});

//Home >zone > list property > property_detail > list assembly point
Breadcrumbs::for('register_hazard_detail', function ($trail, $data) {
    $trail->parent('register_fire', $data->property ?? []);
    $trail->push($data->reference ?? '', route('shineCompliance.assessment.get_hazard_detail',['id' => $data->id ?? 0]));
});

//Home >zone > list property > property_detail > list assembly point
Breadcrumbs::for('register_hazard_edit', function ($trail, $data) {
    $trail->parent('register_hazard_detail', $data);
    $trail->push('Edit Register Hazard');
});

//Home >List-zone > list property > property_edit
Breadcrumbs::for('property_edit', function ($trail,$property) {
    $trail->parent('home_shineCompliance');
    $trail->push($property->zone->zone_name ?? '',route('shineCompliance.zone.details',['zone_id' =>$property->zone_id ?? '']));
    $trail->push($property->name ?? '',route('shineCompliance.property.property_detail',['property_id' =>$property->id ?? '']));
    $trail->push('Edit Property',route('shineCompliance.property.property_edit',['property_id' =>$property->id ?? '']));
});

//Home >List-zone > list property > property > sub_list
Breadcrumbs::for('sub_list', function ($trail,$property) {
    $trail->parent('home_shineCompliance');
    $trail->push($property->zone->zone_name ?? '',route('shineCompliance.zone.details',['zone_id' =>$property->zone_id ?? '']));
    $trail->push($property->name ?? '',route('shineCompliance.property.property_detail',['property_id' =>$property->id ?? '']));
    $trail->push('Sub Properties List',route('shineCompliance.property.get_add_sub_property',['zone_id' => $property->zone->id ?? '' ,'property_id' =>$property->id ?? '']));
});

//Home >List-zone > list property > property > sub_list
Breadcrumbs::for('sub_list_property', function ($trail,$property) {
    $trail->parent('home_shineCompliance');
    $trail->push($property->zone->zone_name ?? '',route('shineCompliance.zone.details',['zone_id' =>$property->zone_id ?? '']));
    $trail->push($property->parents->name ?? '',route('shineCompliance.property.property_detail',['property_id' =>$property->parent_id ?? '']));
    $trail->push($property->name ?? '',route('shineCompliance.property.property_detail',['property_id' =>$property->id ?? '']));
    $trail->push('Sub Properties List',route('shineCompliance.property.get_add_sub_property',['zone_id' => $property->zone->id ?? '' ,'property_id' =>$property->id ?? '']));
});



//Home >List-zone > list property > property > sys_detail
Breadcrumbs::for('system_detail', function ($trail,$system) {
    $trail->parent('home_shineCompliance');
    $trail->push($system->property->zone->zone_name ?? '',route('shineCompliance.zone.details',['zone_id' =>$system->property->zone_id ?? '']));
    $trail->push($system->property->name ?? '',route('shineCompliance.property.property_detail',['property_id' =>$system->property_id ?? '']));
    $trail->push($system->name,route('shineCompliance.systems.list',['system_id' =>$system->name ?? '']));
});

//Home >List-zone > list property > property > sys_add
Breadcrumbs::for('system_add', function ($trail,$property) {
    $trail->parent('home_shineCompliance');
    $trail->push($property->zone->zone_name ?? '',route('shineCompliance.zone.details',['zone_id' =>$property->zone_id ?? '']));
    $trail->push($property->name ?? '',route('shineCompliance.property.property_detail',['property_id' =>$property->id ?? '']));
    $trail->push('Add System');
});

//Home >List-zone > list property > property > sys_add
Breadcrumbs::for('system_list_property', function ($trail,$property) {
    $trail->parent('home_shineCompliance');
    $trail->push($property->zone->zone_name ?? '',route('shineCompliance.zone.details',['zone_id' =>$property->zone_id ?? '']));
    $trail->push($property->parents->name ?? '',route('shineCompliance.property.property_detail',['property_id' =>$property->parent_id ?? '']));
    $trail->push($property->name ?? '',route('shineCompliance.property.property_detail',['property_id' =>$property->id ?? '']));
    $trail->push('Add System');
});

//Home >List-zone > list property > property > sys_list
Breadcrumbs::for('system_list', function ($trail,$property) {
    $trail->parent('home_shineCompliance');
    $trail->push($property->zone->zone_name ?? '',route('shineCompliance.zone.details',['zone_id' =>$property->zone_id ?? '']));
    $trail->push($property->name ?? '',route('shineCompliance.property.property_detail',['property_id' =>$property->id ?? '']));
    $trail->push('List Systems',route('shineCompliance.systems.list',['property_id' =>$property->id ?? '']));
});

//Home >List-zone > list property > property > sys_edit
Breadcrumbs::for('system_edit', function ($trail,$system) {
    $trail->parent('home_shineCompliance');
    $trail->push($system->property->zone->zone_name ?? '',route('shineCompliance.zone.details',['zone_id' =>$system->property->zone_id ?? '']));
    $trail->push($system->property->name ?? '',route('shineCompliance.property.property_detail',['property_id' =>$system->property_id ?? '']));
    $trail->push($system->name ?? '',route('shineCompliance.systems.detail',['system_id' =>$system->id ?? '']));
    $trail->push('Edit System');
});

//Home >List-zone > list property > property > system > programmes_list
Breadcrumbs::for('programmes_list', function ($trail,$system) {
    $trail->parent('home_shineCompliance');
    $trail->push($system->property->zone->zone_name ?? '',route('shineCompliance.zone.details',['zone_id' =>$system->property->zone_id ?? '']));
    $trail->push($system->property->name ?? '',route('shineCompliance.property.property_detail',['property_id' =>$system->property_id ?? '']));
    $trail->push($system->name ?? '',route('shineCompliance.systems.detail',['system_id' =>$system->id]));
    $trail->push('Programmes List');
});

//Home >List-zone > list property > property > system > programmes_detail
Breadcrumbs::for('programmes_detail', function ($trail,$programmes) {
    $trail->parent('home_shineCompliance');
    $trail->push($programmes->property->zone->zone_name ?? '',route('shineCompliance.zone.details',['zone_id' =>$programmes->property->zone_id ?? '']));
    $trail->push($programmes->system->property->name ?? '',route('shineCompliance.property.property_detail',['property_id' =>$programmes->system->property_id ?? '']));
    $trail->push($programmes->system->name ?? '',route('shineCompliance.systems.detail',['system_id' =>$programmes->system->id ?? '']));
    $trail->push($programmes->name ?? '',route('shineCompliance.programme.detail',['system_id' =>$programmes->id ?? '']));
});

//Home >List-zone > list property > property > system > programmes_edit
Breadcrumbs::for('programmes_edit', function ($trail,$programmes) {
    $trail->parent('home_shineCompliance');
    $trail->push($programmes->property->zone->zone_name ?? '',route('shineCompliance.zone.details',['zone_id' =>$programmes->property->zone_id ?? '']));
    $trail->push($programmes->system->property->name ?? '',route('shineCompliance.property.property_detail',['property_id' =>$programmes->system->property_id ?? '']));
    $trail->push($programmes->system->name ?? '',route('shineCompliance.systems.detail',['system_id' =>$programmes->system->id ?? '']));
    $trail->push($programmes->name ?? '',route('shineCompliance.programme.detail',['system_id' =>$programmes->id ?? '']));
    $trail->push('Edit Programme');
});

//Home >List-zone > list property > property > system > equip_list
Breadcrumbs::for('equipment_list', function ($trail,$system) {
    $trail->parent('home_shineCompliance');
    $trail->push($system->property->zone->zone_name ?? '',route('shineCompliance.zone.details',['zone_id' =>$system->property->zone_id ?? '']));
    $trail->push($system->property->name ?? '',route('shineCompliance.property.property_detail',['property_id' =>$system->property_id ?? '']));
    $trail->push($system->name ?? '',route('shineCompliance.systems.detail',['system_id' =>$system->id ?? '']));
    $trail->push('System Equipments List ',route('shineCompliance.equipment.list',['system_id' =>$system->id ?? '']));
});

//Home >List-zone > list property > property > system > equip_add
Breadcrumbs::for('equipment_add', function ($trail,$system) {
    $trail->parent('home_shineCompliance');
    $trail->push($system->property->zone->zone_name ?? '',route('shineCompliance.zone.details',['zone_id' =>$system->property->zone_id ?? '']));
    $trail->push($system->property->name ?? '',route('shineCompliance.property.property_detail',['property_id' =>$system->property_id ?? '']));
    $trail->push($system->name ?? '',route('shineCompliance.equipment.list',['system_id' =>$system->id ?? '']));
    $trail->push('Add Equipment');
});

//Home >List-zone > list property > property > system > equip_edit
Breadcrumbs::for('equipment_edit', function ($trail,$equipment) {
    $trail->parent('home_shineCompliance');
    $trail->push($equipment->property->zone->zone_name ?? '',route('shineCompliance.zone.details',['zone_id' =>$equipment->property->zone_id ?? '']));
    $trail->push($equipment->property->name ?? '',route('shineCompliance.property.property_detail',['property_id' =>$equipment->property_id ?? '']));
    $trail->push($equipment->system->name ?? '',route('shineCompliance.systems.detail',['system_id' =>$equipment->system->id ?? '']));
    $trail->push($equipment->name ?? '',route('shineCompliance.equipment.detail',['system_id' =>$equipment->id ?? '']));
    $trail->push('Edit Equipment');
});

//Home >List-zone > list property > property > equipment
Breadcrumbs::for('equipment_pro', function ($trail,$property) {
    $trail->parent('home_shineCompliance');
    $trail->push($property->zone->zone_name ?? '',route('shineCompliance.zone.details',['zone_id' => $property->zone_id ?? '']));
    $trail->push($property->name ?? '',route('shineCompliance.property.property_detail',['property_id' => $property->id ?? '']));
    $trail->push('Property Equipments List', route('shineCompliance.property.equipment',['property_id' => $property->id ?? '']));
});

//Home >List-zone > list property > property > equipment
Breadcrumbs::for('sub_equipment_pro', function ($trail,$property) {
    $trail->parent('home_shineCompliance');
    $trail->push($property->zone->zone_name ?? '',route('shineCompliance.zone.details',['zone_id' => $property->zone_id ?? '']));
    $trail->push($property->parents->name ?? '',route('shineCompliance.property.property_detail',['property_id' => $property->parent_id ?? '']));
    $trail->push($property->name ?? '',route('shineCompliance.property.property_detail',['property_id' => $property->id ?? '']));
    $trail->push('Property Equipments List', route('shineCompliance.property.equipment',['property_id' => $property->id ?? '']));
});

//Home >List-zone > list property > property > equipment list > add equipment
Breadcrumbs::for('property_equipment_add', function ($trail, $property) {
    $trail->parent('equipment_pro', $property);
    $trail->push('Property Equipments List');
});

//Home >List-zone > list property > property > equipment list > register equipment
Breadcrumbs::for('equipment_detail', function ($trail,$equipment) {
    $trail->parent('equipment_pro', $equipment->property ?? null);
    $trail->push($equipment->name ?? '', route('shineCompliance.register_equipment.detail', ['id' => $equipment->id ?? 0]));
});

//Home >List-zone > list property > property > equipment list > register equipment > edit
Breadcrumbs::for('property_equipment_edit', function ($trail,$equipment) {
    $trail->parent('equipment_detail', $equipment);
    $trail->push('Edit Equipment');
});
//Home >List-zone > list property > property > equipment > photography

Breadcrumbs::for('photography_equipment_detail', function ($trail,$equipment) {
    $trail->parent('home_shineCompliance');
    $trail->push($equipment->property->zone->zone_name ?? '',route('shineCompliance.zone.details',['zone_id' =>$equipment->property->zone_id ?? '']));
    $trail->push($equipment->property->name ?? '',route('shineCompliance.property.property_detail',['property_id' =>$equipment->property->id ?? '']));
    if(!is_null($equipment->system)){
        $trail->push($equipment->system->name ?? '',route('shineCompliance.systems.detail',['id' =>$equipment->system_id ?? '']));
    }
    $trail->push($equipment->name ?? '',route('shineCompliance.register_equipment.detail',['system_id' =>$equipment->id ?? '']));
    $trail->push('Photography');
});

//Home >List-zone > list property > property > equipment > Hazard

Breadcrumbs::for('hazard_equipment_detail', function ($trail,$equipment) {
    $trail->parent('home_shineCompliance');
    $trail->push($equipment->property->zone->zone_name ?? '',route('shineCompliance.zone.details',['zone_id' =>$equipment->property->zone_id ?? '']));
    $trail->push($equipment->property->name ?? '',route('shineCompliance.property.property_detail',['property_id' =>$equipment->property->id ?? '']));
    if(!is_null($equipment->system)){
        $trail->push($equipment->system->name ?? '',route('shineCompliance.systems.detail',['id' =>$equipment->system_id ?? '']));

    }
    $trail->push($equipment->name ?? '',route('shineCompliance.register_equipment.detail',['system_id' =>$equipment->id ?? '']));
    $trail->push('Hazards');

});

//Home >List-zone > list property > property > equipment > Non-conformities

Breadcrumbs::for('nonconformities_equipment_detail', function ($trail,$equipment) {
    $trail->parent('home_shineCompliance');
    $trail->push($equipment->property->zone->zone_name ?? '',route('shineCompliance.zone.details',['zone_id' =>$equipment->property->zone_id ?? '']));
    $trail->push($equipment->property->name ?? '',route('shineCompliance.property.property_detail',['property_id' =>$equipment->property->id ?? '']));
    if(!is_null($equipment->system)){
        $trail->push($equipment->system->name ?? '',route('shineCompliance.systems.detail',['id' =>$equipment->system_id ?? '']));

    }
    $trail->push($equipment->name ?? '',route('shineCompliance.register_equipment.detail',['system_id' =>$equipment->id ?? '']));
    $trail->push('Non-conformities');

});

//Home >List-zone > list property > property > equipment > Pre-Planned Maintenance

Breadcrumbs::for('programme_equipment_detail', function ($trail,$equipment) {
    $trail->parent('home_shineCompliance');
    $trail->push($equipment->property->zone->zone_name ?? '',route('shineCompliance.zone.details',['zone_id' =>$equipment->property->zone_id ?? '']));
    $trail->push($equipment->property->name ?? '',route('shineCompliance.property.property_detail',['property_id' =>$equipment->property->id ?? '']));
    if(!is_null($equipment->system)){
        $trail->push($equipment->system->name ?? '',route('shineCompliance.systems.detail',['id' =>$equipment->system_id ?? '']));
    }

    $trail->push($equipment->name ?? '',route('shineCompliance.register_equipment.detail',['system_id' =>$equipment->id ?? '']));
    $trail->push('Programme');
});


//Home >List-zone > list property > property > equipment > Pre-Planned Maintenance

Breadcrumbs::for('temperature_equipment_detail', function ($trail,$equipment) {
    $trail->parent('home_shineCompliance');
    $trail->push($equipment->property->zone->zone_name ?? '',route('shineCompliance.zone.details',['zone_id' =>$equipment->property->zone_id ?? '']));
    $trail->push($equipment->property->name ?? '',route('shineCompliance.property.property_detail',['property_id' =>$equipment->property->id ?? '']));

    if(!is_null($equipment->system)){
        $trail->push($equipment->system->name ?? '',route('shineCompliance.systems.detail',['id' =>$equipment->system_id ?? '']));
    }
    $trail->push($equipment->name ?? '',route('shineCompliance.register_equipment.detail',['system_id' =>$equipment->id ?? '']));
    $trail->push('Temperature & PH');
});

//Home >List-zone > list property > property > area
Breadcrumbs::for('list_area', function ($trail,$property) {
    $trail->parent('home_shineCompliance');
    $trail->push($property->zone->zone_name ?? '',route('shineCompliance.zone.details',['property_id' =>$property->zone_id]));
    $trail->push($property->name ?? '',route('shineCompliance.property.property_detail',['property_id' =>$property->id]));
    $trail->push('Area/Floor List',route('shineCompliance.property.list_area',['property_id' =>$property->id]));
});

//Home >List-zone > list property > property > sub > area
Breadcrumbs::for('sub_list_area', function ($trail,$property) {
    $trail->parent('home_shineCompliance');
    $trail->push($property->zone->zone_name ?? '',route('shineCompliance.zone.details',['property_id' =>$property->zone_id ?? '']));
    $trail->push($property->parents->name ?? '',route('shineCompliance.property.property_detail',['property_id' =>$property->parent_id ?? '']));
    $trail->push($property->name ?? '',route('shineCompliance.property.property_detail',['property_id' =>$property->id ?? '']));
    $trail->push('Area/Floor List',route('shineCompliance.property.list_area',['property_id' =>$property->id ?? '']));
});

//Home > property > list_area > area_detail
Breadcrumbs::for('area_detail', function ($trail,$area) {
    $trail->parent('home_shineCompliance');
    $trail->push($area->property->zone->zone_name ?? '',route('shineCompliance.zone.details',['property_id' =>$area->property->zone_id]));
    $trail->push($area->property->name ?? '',route('shineCompliance.property.property_detail',['property_id' =>$area->property->id]));
    $trail->push($area->area_reference ?? '',route('shineCompliance.property.area_detail',['property_id' =>$area->property->id, 'area_id' => $area->id ?? '']));
});


//Home > property > area > location_list
Breadcrumbs::for('list_location', function ($trail,$area) {
    $trail->parent('home_shineCompliance');
    $trail->push($area->property->zone->zone_name ?? '',route('shineCompliance.zone.details',['property_id' =>$area->property->zone_id]));
    $trail->push($area->property->name ?? '',route('shineCompliance.property.property_detail',['property_id' =>$area->property->id]));
    $trail->push($area->area_reference ?? '',route('shineCompliance.property.area_detail',['property_id' =>$area->property->id, 'area_id' => $area->id ?? '']));
    $trail->push('List Location',route('shineCompliance.location.list',['area_id' => $area->id ?? '']));
});

//Home > property > area > location_add
Breadcrumbs::for('add_location', function ($trail,$area) {
    $trail->parent('home_shineCompliance');
    $trail->push($area->property->zone->zone_name ?? '',route('shineCompliance.zone.details',['property_id' =>$area->property->zone_id]));
    $trail->push($area->property->name ?? '',route('shineCompliance.property.property_detail',['property_id' =>$area->property->id]));
    $trail->push($area->area_reference ?? '',route('shineCompliance.property.area_detail',['property_id' =>$area->property->id, 'area_id' => $area->id ?? '']));
    $trail->push('Add Location ');
});

//Home > property > area > location_detail
Breadcrumbs::for('detail_location', function ($trail,$location) {
    $trail->parent('home_shineCompliance');
    $trail->push($location->property->zone->zone_name ?? '',route('shineCompliance.zone.details',['property_id' =>$location->property->zone_id]));
    $trail->push($location->property->name ?? '',route('shineCompliance.property.property_detail',['property_id' =>$location->property->id]));
    $trail->push($location->area->area_reference ?? '',route('shineCompliance.property.area_detail',['property_id' =>$location->property_id, 'area_id' => $location->area_id ?? '']));
    $trail->push($location->location_reference ?? '',route('shineCompliance.location.detail',['location_id' => $location->id ?? '']));
});

//Home > property > area > location_edit
Breadcrumbs::for('edit_location', function ($trail,$location) {
    $trail->parent('home_shineCompliance');
    $trail->push($location->property->zone->zone_name ?? '',route('shineCompliance.zone.details',['property_id' =>$location->property->zone_id]));
    $trail->push($location->property->name ?? '',route('shineCompliance.property.property_detail',['property_id' =>$location->property->id]));
    $trail->push($location->area->area_reference ?? '',route('shineCompliance.property.area_detail',['property_id' =>$location->property_id ?? '', 'area_id' => $location->area_id ?? '']));
    $trail->push($location->location_reference ?? '',route('shineCompliance.location.detail',['location_id' => $location->id ?? '']));
    $trail->push('Edit Location');
});

//Home > property > area > location > item_list
Breadcrumbs::for('items_list', function ($trail,$location) {
    $trail->parent('home_shineCompliance');
    $trail->push($location->property->zone->zone_name ?? '',route('shineCompliance.zone.details',['property_id' =>$location->property->zone_id ?? '']));
    $trail->push($location->property->name ?? '',route('shineCompliance.property.property_detail',['property_id' => ($location->property_id ?? 0)]));
    $trail->push($location->area->area_reference ?? '',route('shineCompliance.property.area_detail',['property_id' =>($location->property_id ?? 0), 'area_id' => $location->area_id ?? '']));
    $trail->push($location->location_reference ?? '',route('shineCompliance.location.detail',['location_id' => $location->id ?? '']));
    $trail->push('List Item',route('shineCompliance.item.list',['location_id' => $location->id ?? '']));
});

//Home > property > area > location > item > add
Breadcrumbs::for('items_add_compliance', function ($trail,$location) {
    $trail->parent('home_shineCompliance');
    $trail->push($location->property->zone->zone_name ?? '',route('shineCompliance.zone.details',['property_id' =>$location->property->zone_id]));
    $trail->push($location->property->name ?? '',route('shineCompliance.property.property_detail',['property_id' =>($location->property_id ?? 0)]));
    $trail->push($location->area->area_reference ?? '',route('shineCompliance.property.area_detail',['property_id' =>($location->property_id ?? 0), 'area_id' => $location->area_id ?? '']));
    $trail->push($location->location_reference ?? '',route('shineCompliance.location.detail',['location_id' => $location->id ?? '']));
    $trail->push('Add Item');
});

//Home > property > area > location > item > edit
Breadcrumbs::for('items_edit_compliance', function ($trail,$item) {
    $trail->parent('home_shineCompliance');
    $trail->push($item->property->zone->zone_name ?? '',route('shineCompliance.zone.details',['property_id' =>$item->property->zone_id ?? '']));
    $trail->push($item->property->name ?? '',route('shineCompliance.property.property_detail',['property_id' =>($item->property_id ?? 0)]));
    $trail->push($item->area->area_reference ?? '',route('shineCompliance.property.area_detail',['property_id' => ($item->property_id ?? 0), 'area_id' => $item->area_id ?? 0]));
    $trail->push($item->location->location_reference ?? '',route('shineCompliance.location.detail',['location_id' => ($item->location_id ?? 0) ?? '']));
    $trail->push($item->name ?? '',route('shineCompliance.item.detail',['location_id' => $item->id ?? '']));
    $trail->push('Edit Item');
});

//Home > property > area > location > item > detail
Breadcrumbs::for('items_detail_compliance', function ($trail,$item) {
    $trail->parent('home_shineCompliance');
    $trail->push($item->property->zone->zone_name ?? '',route('shineCompliance.zone.details',['property_id' =>$item->property->zone_id]));
    $trail->push($item->property->name ?? '',route('shineCompliance.property.property_detail',['property_id' =>($item->property_id ?? 0)]));
    $trail->push($item->area->description ?? '',route('shineCompliance.property.area_detail',['property_id' =>($item->property_id ?? 0), 'area_id' => ($item->area_id ?? 0)]));
    $trail->push($item->location->description ?? '',route('shineCompliance.location.detail',['location_id' => $item->location_id ?? '']));
    $trail->push($item->name ?? '',route('shineCompliance.item.detail',['location_id' => $item->id ?? '']));
});


//Home > summary
Breadcrumbs::for('summary_com', function ($trail) {
    $trail->parent('home_shineCompliance');
    $trail->push('Summary');
});
//Home > tool_box_upload
Breadcrumbs::for('tool_box_upload', function ($trail) {
    $trail->parent('home_shineCompliance');
    $trail->push('Upload');
});

//Home > DataCentre
Breadcrumbs::for('data_center', function ($trail) {
    $trail->parent('home_shineCompliance');
    $trail->push('Data Centre');
});


//Home > Audit Trail
Breadcrumbs::for('audit_trail_compliance', function ($trail) {
    $trail->parent('home_shineCompliance');
    $trail->push('Audit Trail');
});

Breadcrumbs::for('configurations', function ($trail) {
    $trail->parent('home_shineCompliance');
    $trail->push('Configurations');
});


//Home > Property Group > property > add assessment
Breadcrumbs::for('property_assessment_add', function ($trail, $property) {
    $trail->parent('home_shineCompliance');
    $trail->push($property->zone->zone_name ?? '',route('shineCompliance.zone.details',['zone_id' => $property->zone_id]));
    $trail->push($property->name ?? '',route('shineCompliance.property.property_detail',['property_id' => $property->id]));
    $trail->push('Add Assessment');
});

//Home > Property Group > property > assessment > Edit
Breadcrumbs::for('property_assessment_edit', function ($trail, $assessment) {
    $trail->parent('home_shineCompliance');
    $trail->push($assessment->property->zone->zone_name ?? '',route('shineCompliance.zone.details',['zone_id' => $assessment->property->zone_id ?? '']));
    $trail->push($assessment->property->name ?? '',route('shineCompliance.property.property_detail',['property_id' => $assessment->property->id ?? '']));
    $trail->push($assessment->reference ?? '',route('shineCompliance.assessment.show',['assess_id' => $assessment->id ?? '']));
    $trail->push('Edit Assessment Details');
});

//Home > Property Group > property > assessment > Executive Summary / Objective/scope > Edit
Breadcrumbs::for('property_assessment_executive_summary_edit', function ($trail, $assessment) {
    $trail->parent('property_assessment_detail', $assessment ?? []);
    $trail->push('Edit Executive Summary');
});

//Home > Property Group > property > assessment > Objective/scope > Edit
Breadcrumbs::for('property_assessment_objective_scope_edit', function ($trail, $assessment) {
    $trail->parent('property_assessment_detail', $assessment ?? []);
    $trail->push('Edit Objective/Scope');
});

//Home > Property Group > property > assessment > Property Information > Edit
Breadcrumbs::for('property_assessment_property_information_edit', function ($trail, $assessment) {
    $trail->parent('property_assessment_detail', $assessment ?? []);
    $trail->push('Edit Property Information');
});

//Home > Property Group > property > assessment > Assembly Point > Add
Breadcrumbs::for('property_assessment_assembly_point_add', function ($trail, $assessment) {
    $trail->parent('property_assessment_detail', $assessment ?? []);
    $trail->push('Add Assembly Point');
});

//Home > Property Group > property > assessment > Fire Exit > Add
Breadcrumbs::for('property_assessment_fire_exit_add', function ($trail, $assessment) {
    $trail->parent('property_assessment_detail', $assessment ?? []);
    $trail->push('Add Fire Exit');
});

//Home > Property Group > property > assessment > Vehicle Parking > Add
Breadcrumbs::for('property_assessment_vehicle_parking_add', function ($trail, $assessment) {
    $trail->parent('property_assessment_detail', $assessment ?? []);
    $trail->push('Add Vehicle Parking');
});

//Home > Property Group > property > assessment > Assembly Point > Edit
Breadcrumbs::for('property_assessment_assembly_point_edit', function ($trail, $assemblyPoint) {
    $trail->parent('property_assessment_detail', $assemblyPoint->assessment ?? []);
    $trail->push($assemblyPoint->reference ?? '', route('shineCompliance.assessment.get_assembly_point', $assemblyPoint->id));
    $trail->push('Edit');
});

//Home > Property Group > property > assessment > Fire Exit > Edit
Breadcrumbs::for('property_assessment_fire_exit_edit', function ($trail, $fireExit) {
    $trail->parent('property_assessment_detail', $fireExit->assessment ?? []);
    $trail->push($fireExit->reference ?? '', route('shineCompliance.assessment.get_fire_exit', $fireExit->id));
    $trail->push('Edit');
});

//Home > Property Group > property > assessment > Vehicle Parking > Edit
Breadcrumbs::for('property_assessment_vehicle_parking_edit', function ($trail, $vehicleParking) {
    $trail->parent('property_assessment_detail', $vehicleParking->assessment ?? []);
    $trail->push($vehicleParking->reference ?? '', route('shineCompliance.assessment.get_vehicle_parking', $vehicleParking->id));
    $trail->push('Edit');
});

//Home > Property Group > property > assessment > Assembly Point > Detail
Breadcrumbs::for('property_assessment_assembly_point_detail', function ($trail, $assemblyPoint) {
    $trail->parent('property_assessment_detail', $assemblyPoint->assessment ?? []);
    $trail->push($assemblyPoint->reference ?? '');
});

//Home > Property Group > property > assessment > Fire Exit > Detail
Breadcrumbs::for('property_assessment_fire_exit_detail', function ($trail, $fireExit) {
    $trail->parent('property_assessment_detail', $fireExit->assessment ?? []);
    $trail->push($fireExit->reference ?? '');
});

//Home > Property Group > property > assessment > Vehicle Parking > Detail
Breadcrumbs::for('property_assessment_vehicle_parking_detail', function ($trail, $vehicleParking) {
    $trail->parent('property_assessment_detail', $vehicleParking->assessment ?? []);
    $trail->push($vehicleParking->reference ?? '');
});

//Home > Property Group > property > assessment > Edit
Breadcrumbs::for('property_assessment_detail', function ($trail, $assessment) {
    $trail->parent('home_shineCompliance');
    $trail->push($assessment->property->zone->zone_name ?? '',route('shineCompliance.zone.details',['zone_id' => $assessment->property->zone_id ?? '']));
    $trail->push($assessment->property->name ?? '',route('shineCompliance.property.property_detail',['property_id' => $assessment->property->id ?? '']));
    $trail->push($assessment->reference ?? '',route('shineCompliance.assessment.show',['assess_id' => $assessment->id ?? '']));
});

//Home > Property Group > property > assessment > Hazard Detail
Breadcrumbs::for('property_assessment_hazard_detail', function ($trail, $hazard) {
    $trail->parent('property_assessment_detail', $hazard->assessment ?? []);
    $trail->push($hazard->name ?? '', route('shineCompliance.assessment.get_hazard_detail',[
        'assess_id' => $hazard->assess_id ?? 0,
        'id' => $hazard->id ?? 0]));
});

//Home > Property Group > property > assessment > Hazard Add
Breadcrumbs::for('property_assessment_hazard_add', function ($trail, $assessment) {
    $trail->parent('property_assessment_detail', $assessment ?? []);
    $trail->push('Add Hazard');
});

//Home > Property Group > property > assessment > Hazard Detail > Hazard Edit
Breadcrumbs::for('property_assessment_hazard_edit', function ($trail, $hazard) {
    $trail->parent('property_assessment_hazard_detail', $hazard ?? []);
    $trail->push('Edit Hazard');
});

//Home > Property Group > property > assessment > Hazard Detail
Breadcrumbs::for('property_assessment_questionnaire_edit', function ($trail, $assessment) {
    $trail->parent('property_assessment_detail', $assessment);
    $trail->push('Edit Assessment Questionnaire');
});

//Home > Property Group > property > assessment > equipment > Add
Breadcrumbs::for('property_assessment_equipment_add', function ($trail, $assessment) {
    $trail->parent('property_assessment_detail', $assessment);
    $trail->push('Add Assessment Equipment');
});

//Home > Property Group > property > assessment > equipment > Detail
Breadcrumbs::for('property_assessment_equipment_detail', function ($trail, $data) {
    $trail->parent('property_assessment_detail', $data->assessment);
    $trail->push($data->reference ?? '',route('shineCompliance.equipment.detail',['id' => $data->id ?? '']));
});

//Home > Property Group > property > assessment > equipment > Edit
Breadcrumbs::for('property_assessment_equipment_edit', function ($trail, $data) {
    $trail->parent('property_assessment_equipment_detail', $data);
    $trail->push('Edit Assessment Equipment');
});

//Home > Property Group > property > assessment > equipment > Add
Breadcrumbs::for('property_assessment_system_add', function ($trail, $assessment) {
    $trail->parent('property_assessment_detail', $assessment);
    $trail->push('Add Assessment System');
});


//Home > Property Group > property > assessment > equipment > Detail
Breadcrumbs::for('property_assessment_system_detail', function ($trail, $data) {
    $trail->parent('property_assessment_detail', $data->assessment);
    $trail->push($data->reference ?? '',route('shineCompliance.assessment.system_detail',['id' => $data->id ?? '']));
});

//Home > Property Group > property > assessment > equipment > Edit
Breadcrumbs::for('property_assessment_system_edit', function ($trail, $data) {
    $trail->parent('property_assessment_system_detail', $data);
    $trail->push('Edit Assessment System');
});

//Home > Property Group > property > assessment > asbestos view
Breadcrumbs::for('property_assessment_asbestos_view', function ($trail, $assessment) {
    $trail->parent('property_assessment_detail', $assessment);
    $trail->push('Assessment Asbestos');
});

//Home > Property Group > property > document
Breadcrumbs::for('property_document_compliance', function ($trail,$property) {
    $trail->parent('home_shineCompliance');
    $trail->push($property->zone->zone_name ?? '',route('shineCompliance.zone.details',['zone_id' =>$property->zone_id]));
    $trail->push($property->name ?? '',route('shineCompliance.property.property_detail',['property_id' =>$property->id]));
    $trail->push('Document');
});

//Home > Property Group > property > sub > document
Breadcrumbs::for('sub_property_document_compliance', function ($trail,$property) {
    $trail->parent('home_shineCompliance');
    $trail->push($property->zone->zone_name ?? '',route('shineCompliance.zone.details',['zone_id' =>$property->zone_id ?? '']));
    $trail->push($property->parents->name ?? '',route('shineCompliance.property.property_detail',['property_id' =>$property->parent_id ?? '']));
    $trail->push($property->name ?? '',route('shineCompliance.property.property_detail',['property_id' =>$property->id ?? '']));
    $trail->push('Document');
});

//Home > Property Group > property > >system > document
Breadcrumbs::for('system_document_compliance', function ($trail,$object) {
    $trail->parent('home_shineCompliance');
    $trail->push($object->property->zone->zone_name ?? '',route('shineCompliance.zone.details',['zone_id' =>$object->property->zone_id ?? '']));
    $trail->push($object->property->name ?? '',route('shineCompliance.property.property_detail',['property_id' =>$object->property->id ?? '']));
    $trail->push($object->name ?? '',route('shineCompliance.systems.detail',['system_id' =>$object->id]));
    $trail->push('Document');
});

//Home > Property Group > property > >system > programme > document
Breadcrumbs::for('programme_document_compliance', function ($trail,$object) {
    $trail->parent('home_shineCompliance');
    $trail->push($object->property->zone->zone_name ?? '',route('shineCompliance.zone.details',['zone_id' =>$object->property->zone_id ?? '']));
    $trail->push($object->property->name ?? '',route('shineCompliance.property.property_detail',['property_id' =>$object->property->id ?? '']));
    $trail->push($object->system->name ?? '',route('shineCompliance.systems.detail',['system_id' =>$object->system_id ?? '']));
    $trail->push($object->name ?? '',route('shineCompliance.programme.detail',['system_id' =>$object->id ?? '']));
    $trail->push('Document');
});

//Home > Property Group > property > >system > programme > document
Breadcrumbs::for('equiment_document_com', function ($trail,$object) {
    $trail->parent('home_shineCompliance');
    $trail->push($object->property->zone->zone_name ?? '',route('shineCompliance.zone.details',['zone_id' =>$object->property->zone_id ?? '']));
    $trail->push($object->property->name ?? '',route('shineCompliance.property.property_detail',['property_id' =>$object->property->id ?? '']));
    if(!is_null($object->system)){
        $trail->push($object->system->name ?? '',route('shineCompliance.systems.detail',['system_id' =>$object->system_id ?? '']));
    }
    $trail->push($object->name ?? '',route('shineCompliance.register_equipment.detail',['system_id' =>$object->id ?? '']));
    $trail->push('Document');
});

// Home > My organisation
Breadcrumbs::for('shineCompliance-my-organisation', function ($trail, $data) {
    $trail->parent('home_shineCompliance');
    $trail->push('My Organisation', route('shineCompliance.my_organisation',['id'=> $data->id ?? '']));
});

// Home > My organisation edit
Breadcrumbs::for('shineCompliance-my-organisation-edit', function ($trail, $data) {
    $trail->parent('shineCompliance-my-organisation',$data);
    $trail->push('Edit', route('my_organisation.get_edit',['id'=> $data->id ?? '']));
});

// Home > My organisation > deparment users
Breadcrumbs::for('shineCompliance-department-users', function ($trail, $data) {
    $trail->parent('my-organisation',$data->client ?? '');

    if (\Auth::user()->client_id == $data->client->id) {
        $type = ORGANISATION_TYPE;
        $route_deparment = 'my_organisation.department_users';
    } else {
        $type = CONTRACTOR_TYPE;
        $route_deparment = 'contractor.department_users';
    }

    if ($data->client->id == 1) {
        //recursive
        if(isset($data->allParents) and !is_null($data->allParents)){
            // dd($data->allParents);
            $tmp = [];
            $all_parents = showParentDepart($data->allParents,$tmp);

            $all_parents = array_reverse($all_parents);
            foreach ($all_parents as $parent) {
                $trail->push($parent->name ?? '', route($route_deparment,['department_id' => $parent->id ?? '', 'type' => $type,'client_id' => $data->client->id]));
            }
        }
    } else {

        //recursive
        if(isset($data->allParents) and !is_null($data->allParents)){
            // dd($data->allParents);
            $tmp = [];
            $all_parents = showParentDepart($data->allParents,$tmp);

            $all_parents = array_reverse($all_parents);
            foreach ($all_parents as $parent) {
                $trail->push($parent->name ?? '', route($route_deparment,['department_id' => $parent->id ?? '', 'type' => $type,'client_id' => $data->client->id]));
            }
        }

    }
    $trail->push($data->name ?? '', route('contractor.department_users', ['department_id' => $data->id ]));
});

// Home > property > project > add
Breadcrumbs::for('project_add_shineCompliance', function ($trail, $data) {
    $trail->parent('home_shineCompliance');
    $trail->push($data->zone->zone_name ?? '',route('shineCompliance.zone.details',['zone_id' =>$data->zone_id ?? '']));
    $trail->push($data->name ?? '',route('shineCompliance.project.project_list',['property_id' =>$data->id ?? '']));
    $trail->push( 'Add Project');
});

// Home > property > project > add
Breadcrumbs::for('property_detail_shineCompliance', function ($trail, $data) {
    $trail->parent('home_shineCompliance');
    $trail->push($data->zone->zone_name ?? '',route('shineCompliance.zone.details',['zone_id' =>$data->zone_id ?? '']));
    $trail->push($data->name ?? '',route('shineCompliance.project.project_list',['property_id' =>$data->id ?? '']));
});

// Home > property > project > detail
Breadcrumbs::for('project_detail_shineCompliance', function ($trail, $data) {
    $trail->parent('home_shineCompliance');
    $trail->push($data->property->zone->zone_name ?? '',route('shineCompliance.zone.details',['zone_id' =>$data->property->zone_id ?? '']));
    $trail->push($data->property->name ?? '',route('shineCompliance.project.project_list',['property_id' =>$data->property_id ?? '']));
    $trail->push($data->title ?? '',route('project.index',['project_id' =>$data->id ?? '']));
});

// Home > property > project > edit
Breadcrumbs::for('project_edit_shineCompliance', function ($trail, $data) {
    $trail->parent('home_shineCompliance');
    $trail->push($data->property->zone->zone_name ?? '',route('shineCompliance.zone.details',['zone_id' =>$data->property->zone_id]));
    $trail->push($data->property->name ?? '' ,route('shineCompliance.project.project_list',['property_id' =>$data->property_id]));
    $trail->push($data->title ?? '' ,route('project.index',['project_id' =>$data->id]));
    $trail->push('Edit Project');
});

// Home > My organisation > contractor
Breadcrumbs::for('shineCompliance_contractor', function ($trail, $data) {
    $trail->parent('home_shineCompliance');
    $trail->push('Contractor', route('shineCompliance.contractor.clients'));
    $trail->push($data->name, route('shineCompliance.contractor', ['client_id' => $data->id]));
});

Breadcrumbs::for('shineCompliance_system_owner', function ($trail) {
    $trail->parent('home_shineCompliance');
    $trail->push('System Owner', route('shineCompliance.contractor',['client_id' => 1]));
});


//Home > Property Group > property > assessment_list >survey
Breadcrumbs::for('survey_compliance', function ($trail, $data) {
    $trail->parent('home_shineCompliance');
    $trail->push($data->property->zone->zone_name ?? '',route('shineCompliance.zone.details',['zone_id' => $data->property->zone_id ?? '']));
    $trail->push($data->property->name ?? '',route('shineCompliance.assessment.index',['property_id' => $data->property_id ?? '']));
    $trail->push($data->reference ?? '',route('property.surveys',['id'=> $data->id ?? '', 'section' => SECTION_DEFAULT ]));
    if ($data->table_title != '') {
        $trail->push($data->table_title);
    }
});

// Home > property > survey > sample eidt
Breadcrumbs::for('sample_compliance', function ($trail, $data) {
    $trail->parent('home_shineCompliance', $data);
    $trail->push($data->property->zone->zone_name ?? '',route('shineCompliance.zone.details',['zone_id' => $data->property->zone_id ?? '']));
    $trail->push($data->property->name ?? '',route('shineCompliance.assessment.index',['property_id' => $data->property_id ?? '']));
    $trail->push('Sample', route('property.surveys',['id'=> $data->id ?? '', 'section' => SECTION_DEFAULT ]));
    $trail->push( 'Edit ' . $data->breadcrumb_title ?? '');
});

//Home > Property Group > property > assessment_list > survey > area
Breadcrumbs::for('survey_area_asbestos', function ($trail, $areaData) {
    $trail->parent('home_shineCompliance');
    $trail->push($areaData->property->name ?? '',route('shineCompliance.assessment.index',['property_id' => $areaData->property_id ?? '']));
    $trail->push($areaData->survey->reference ?? '',route('property.surveys',['id'=> $areaData->survey_id ?? '', 'section' => SECTION_DEFAULT ]));
    $trail->push($areaData->title, route('property.surveys',['id'=> $areaData->survey_id ?? '','area'=> $areaData->id ?? '', 'section' => SECTION_AREA_FLOORS_SUMMARY ]));
    if ($areaData->table_title != '') {
        $trail->push($areaData->table_title);
    }
});

//Home > Property Group > property > assessment_list > survey > area > location
Breadcrumbs::for('survey_location_asbestos', function ($trail, $data) {
    $trail->parent('home_shineCompliance');
    $trail->push($data->property->name ?? '',route('shineCompliance.assessment.index',['property_id' => $data->property_id ?? '']));
    $trail->push($data->survey->reference ?? '',route('property.surveys',['id'=> $data->survey_id ?? '', 'section' => SECTION_DEFAULT ]));
    $trail->push(isset($data->area->title) ? $data->area->title : '', route('property.surveys',['id'=> $data->survey_id ?? '','area'=> $data->area_id ?? '', 'section' => SECTION_AREA_FLOORS_SUMMARY ]));
    $trail->push(isset($data->location_reference) ? $data->location_reference : '', route('property.surveys',['id'=> $data->survey_id,'location'=> $data->id, 'section' => SECTION_ROOM_LOCATION_SUMMARY ]));
    if ($data->table_title != '') {
        $trail->push($data->table_title);
    }
});

//Home > Property Group > property > assessment_list > survey > area > location > add_location
Breadcrumbs::for('add_survey_location_asbestos', function ($trail, $data) {
    $trail->parent('home_shineCompliance');
    $trail->push($data->property->name ?? '',route('shineCompliance.assessment.index',['property_id' => $data->property_id]));
    $trail->push(isset($data->title) ? $data->title : '', route('shineCompliance.location.list',['area_id'=> $data->id]));
    $trail->push('Add Location');
});


//Home > Property Group > property > assessment_list > survey > area > location > item
Breadcrumbs::for('survey_item_asbestos', function ($trail, $data) {
    $trail->parent('home_shineCompliance');
    $trail->push($data->property->name ?? '',route('shineCompliance.assessment.index',['property_id' => $data->property_id]));
    $trail->push($data->survey->reference ?? '',route('property.surveys',['id'=> $data->survey_id, 'section' => SECTION_DEFAULT ]));
    $trail->push(isset($data->area->title) ? $data->area->title : '', route('property.surveys',['id'=> $data->survey_id,'area'=> $data->area_id, 'section' => SECTION_AREA_FLOORS_SUMMARY ]));
    $trail->push(isset($data->location->location_reference) ? $data->location->location_reference : '', route('property.surveys',['id'=> $data->survey_id,'location'=> $data->location_id, 'section' => SECTION_ROOM_LOCATION_SUMMARY ]));
    $trail->push(isset($data->reference) ? $data->reference : '');
});

//Home > Property Group > property > assessment_list > survey > area > location > add_item
Breadcrumbs::for('items_add_asbestos', function ($trail, $data) {
    $trail->parent('home_shineCompliance');
    $trail->push($data->property->name ?? '',route('shineCompliance.assessment.index',['property_id' => $data->property_id]));
    $trail->push($data->survey->reference ?? '',route('property.surveys',['id'=> $data->survey_id, 'section' => SECTION_DEFAULT ]));
    $trail->push(isset($data->area->title) ? $data->area->title : '', route('property.surveys',['id'=> $data->survey_id,'area'=> $data->area_id, 'section' => SECTION_AREA_FLOORS_SUMMARY ]));
    $trail->push(isset($data->location_reference) ? $data->location_reference : '', route('property.surveys',['id'=> $data->survey_id,'location'=> $data->id, 'section' => SECTION_ROOM_LOCATION_SUMMARY ]));
    $trail->push(isset($data->reference) ? $data->reference : '');
});

//Home > Property Group > property > assessment_list > survey > area > location > add_item
Breadcrumbs::for('items_edit_asbestos', function ($trail, $data) {
    $trail->parent('home_shineCompliance');
    $trail->push($data->property->name ?? '',route('shineCompliance.assessment.index',['property_id' => $data->property_id]));
    $trail->push($data->survey->reference ?? '',route('property.surveys',['id'=> $data->survey_id, 'section' => SECTION_DEFAULT ]));
    $trail->push(isset($data->area->title) ? $data->area->title : '', route('property.surveys',['id'=> $data->survey_id,'area'=> $data->area_id, 'section' => SECTION_AREA_FLOORS_SUMMARY ]));
    $trail->push(isset($data->location->location_reference) ? $data->location->location_reference : '', route('property.surveys',['id'=> $data->survey_id,'location'=> $data->location_id, 'section' => SECTION_ROOM_LOCATION_SUMMARY ]));
    $trail->push(isset($data->reference) ? $data->reference : '', route('shineCompliance.item.detail',['id'=> $data->id]));
    $trail->push('Edit Item');
});

// Home > property > survey > survey question
Breadcrumbs::for('survey_question_shinecompliance', function ($trail, $data) {
    $trail->parent('survey_compliance', $data);
//    $trail->push($data->property->zone->zone_name ?? '',route('shineCompliance.zone.details',['zone_id' => $data->property->zone_id]));
//    $trail->push(isset($data->property->name) ? $data->property->name : '', route('shineCompliance.assessment.index',['id'=> $data->property_id, 'section' => SECTION_DEFAULT ]));
//    $trail->push(isset($data->reference) ? $data->reference : '', route('shineCompliance.property.surveys',['id'=> $data->id, 'section' => SECTION_DEFAULT ]));
    $trail->push( 'Edit Method By Questionnaire');
});

// Home > property > survey > survey infomation
Breadcrumbs::for('survey_info_shineCompliance', function ($trail, $data) {
    $trail->parent('survey_compliance', $data);
    $trail->push( 'Edit ' . $data->breadcrumb_title);
});

// Home > property > survey > survey site data
Breadcrumbs::for('survey_property_info_compliance', function ($trail, $data) {
    $trail->parent('survey_compliance', $data);
//    $trail->push(isset($data->property->name) ? $data->property->name : '', route('property_detail',['id'=> $data->property_id, 'section' => SECTION_DEFAULT ]));
//    $trail->push(isset($data->reference) ? $data->reference : '', route('property.surveys',['id'=> $data->id, 'section' => SECTION_DEFAULT ]));
    $trail->push( 'Edit Property Information');
});

// Incident Report

// Home > Resources > Incident Reports
Breadcrumbs::for('incident_reports', function ($trail) {
    $trail->parent('home');
    $trail->push('Incident Reports', route('incident_reports'));
});
// Home > Add Incident Report
Breadcrumbs::for('add_incident_report', function ($trail) {
    $trail->parent('incident_reports');
    $trail->push('Add Incident Report');
});
// Home > Edit Incident Report
Breadcrumbs::for('edit_incident_report', function ($trail) {
    $trail->parent('incident_reports');
    $trail->push('Edit Incident Report');
});
// Home > Resources > Incident Reports > Detail Incident Report
Breadcrumbs::for('detail_incident_report', function ($trail, $data) {
    $trail->parent('incident_reports');
    $trail->push(isset($data->reference) ? $data->reference : '', route('shineCompliance.incident_reporting.incident_Report',['incident_id'=> $data->id]));
});
