@extends('tables.main_table', [
        'header' => ['Questions', 'Responses', 'Additional Comments'],
    ])
@section('datatable_content')
    <tr>
        <td>High Level Access</td>
        <td>{{ ($work_request->workRequirement->hight_level_access ?? 0) == 1 ? 'Yes' : 'No' }}</td>
        <td>{{ ($work_request->workRequirement->hight_level_access ?? 0) == 1 && isset($work_request->workRequirement->hight_level_access_comment) ? nl2br($work_request->workRequirement->hight_level_access_comment) : '' }}</td>
    </tr>
    <tr>
        <td>Max Height if Over 3m</td>
        <td>{{ ($work_request->workRequirement->max_height ?? 0 ) == 1 ? 'Yes' : 'No'}}</td>
        <td>{{ ($work_request->workRequirement->max_height ?? 0) == 1 && isset($work_request->workRequirement->max_height_comment) ? nl2br($work_request->workRequirement->max_height_comment) : 'N/A' }}</td>
    </tr>
    <tr>
        <td>Loft Spaces</td>
        <td>{{ ($work_request->workRequirement->loft_spaces ?? 0) == 1 ? 'Yes' : 'No' }}</td>
        <td>{{ ($work_request->workRequirement->loft_spaces ?? 0) == 1 && isset($work_request->workRequirement->loft_spaces_comment) ? nl2br($work_request->workRequirement->loft_spaces_comment) : '' }}</td>
    </tr>
    <tr>
        <td>Floor Voids</td>
        <td>{{ ($work_request->workRequirement->floor_voids ?? 0) == 1 ? 'Yes' : 'No' }}</td>
        <td>{{ ($work_request->workRequirement->floor_voids ?? 0) == 1 && isset($work_request->workRequirement->floor_voids_comment) ? nl2br($work_request->workRequirement->floor_voids_comment) : '' }}</td>
    </tr>
    <tr>
        <td>Basements</td>
        <td>{{ ($work_request->workRequirement->basements ?? 0) == 1 ? 'Yes' : 'No' }}</td>
        <td>{{ ($work_request->workRequirement->basements ?? 0) == 1 && isset($work_request->workRequirement->basements_comment) ? nl2br($work_request->workRequirement->basements_comment) : '' }}</td>
    </tr>
    <tr>
        <td>Ducts</td>
        <td>{{ ($work_request->workRequirement->ducts ?? 0) == 1 ? 'Yes' : 'No' }}</td>
        <td>{{ ($work_request->workRequirement->ducts ?? 0) == 1 && isset($work_request->workRequirement->ducts_comment) ? nl2br($work_request->workRequirement->ducts_comment) : '' }}</td>
    </tr>
    <tr>
        <td>Lift Shafts</td>
        <td>{{ ($work_request->workRequirement->lift_shafts ?? 0) == 1 ? 'Yes' : 'No' }}</td>
        <td>{{ ($work_request->workRequirement->lift_shafts ?? 0) == 1 && isset($work_request->workRequirement->lift_shafts_comment) ? nl2br($work_request->workRequirement->lift_shafts_comment) : '' }}</td>
    </tr>
    <tr>
        <td>Light Wells</td>
        <td>{{ ($work_request->workRequirement->light_wells ?? 0) == 1 ? 'Yes' : 'No' }}</td>
        <td>{{ ($work_request->workRequirement->light_wells ?? 0) == 1 && isset($work_request->workRequirement->light_wells_comment) ? nl2br($work_request->workRequirement->light_wells_comment) : '' }}</td>
    </tr>
    <tr>
        <td>Confined Spaces</td>
        <td>{{ ($work_request->workRequirement->confined_spaces ?? 0) == 1 ? 'Yes' : 'No' }}</td>
        <td>{{ ($work_request->workRequirement->confined_spaces ?? 0) == 1 && isset($work_request->workRequirement->confined_spaces_comment) ? nl2br($work_request->workRequirement->confined_spaces_comment) : '' }}</td>
    </tr>
    <tr>
        <td>Fumes/Duct</td>
        <td>{{ ($work_request->workRequirement->fumes_duct ?? 0) == 1 ? 'Yes' : 'No' }}</td>
        <td>{{ ($work_request->workRequirement->fumes_duct ?? 0) == 1 && isset($work_request->workRequirement->fumes_duct_comment) ? nl2br($work_request->workRequirement->fumes_duct_comment) : '' }}</td>
    </tr>
    <tr>
        <td>Patching/Making Good</td>
        <td>{{ ($work_request->workRequirement->pm_good ?? 0) == 1 ? 'Yes' : 'No' }}</td>
        <td>{{ ($work_request->workRequirement->pm_good ?? 0) == 1 && isset($work_request->workRequirement->pm_good_comment) ? nl2br($work_request->workRequirement->pm_good_comment) : '' }}</td>
    </tr>
    <tr>
        <td>Fragile Materials</td>
        <td>{{ ($work_request->workRequirement->fragile_material ?? 0) == 1 ? 'Yes' : 'No' }}</td>
        <td>{{ ($work_request->workRequirement->fragile_material ?? 0) == 1 && isset($work_request->workRequirement->fragile_material_comment) ? nl2br($work_request->workRequirement->fragile_material_comment) : '' }}</td>
    </tr>
    <tr>
        <td>Hot/Live Services</td>
        <td>{{ ($work_request->workRequirement->hot_live_services ?? 0) == 1 ? 'Yes' : 'No' }}</td>
        <td>{{ ($work_request->workRequirement->hot_live_services ?? 0) == 1 && isset($work_request->workRequirement->hot_live_services_comment) ? nl2br($work_request->workRequirement->hot_live_services_comment) : '' }}</td>
    </tr>
    <tr>
        <td>Pigeons</td>
        <td>{{ ($work_request->workRequirement->pieons ?? 0) == 1 ? 'Yes' : 'No' }}</td>
        <td>{{ ($work_request->workRequirement->pieons ?? 0) == 1 && isset($work_request->workRequirement->pieons_comment) ? nl2br($work_request->workRequirement->pieons_comment) : '' }}</td>
    </tr>
    <tr>
        <td>Vermin</td>
        <td>{{ ($work_request->workRequirement->vermin ?? 0) == 1 ? 'Yes' : 'No' }}</td>
        <td>{{ ($work_request->workRequirement->vermin ?? 0) == 1 && isset($work_request->workRequirement->vermin_comment) ? nl2br($work_request->workRequirement->vermin_comment) : '' }}</td>
    </tr>
    <tr>
        <td>Biological/Chemical</td>
        <td>{{ ($work_request->workRequirement->biological_chemical ?? 0) == 1 ? 'Yes' : 'No' }}</td>
        <td>{{ ($work_request->workRequirement->biological_chemical ?? 0) == 1 && isset($work_request->workRequirement->biological_chemical_comment) ? nl2br($work_request->workRequirement->biological_chemical_comment) : '' }}</td>
    </tr>
    <tr>
        <td>Vulnerable Tenant</td>
        <td>{{ ($work_request->workRequirement->vulnerable_tenant ?? 0) == 1 ? 'Yes' : 'No' }}</td>
        <td>{{ ($work_request->workRequirement->vulnerable_tenant ?? 0) == 1 && isset($work_request->workRequirement->vulnerable_tenant_comment) ? nl2br($work_request->workRequirement->vulnerable_tenant_comment) : '' }}</td>
    </tr>
@overwrite
