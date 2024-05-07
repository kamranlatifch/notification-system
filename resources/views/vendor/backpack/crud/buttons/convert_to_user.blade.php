@if ($crud->hasAccess('update'))
    <a href="{{ url('admin/makeuser' . '/' . $entry->getKey()) }}" class="btn btn-sm btn-link text-capitalize"><i
            class="la la-user"></i>Make User</a>
@endif
