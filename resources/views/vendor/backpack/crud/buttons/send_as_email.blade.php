@if ($crud->hasAccess('update'))
    <a href="{{ url('admin/sendemail' . '/' . $entry->getKey()) }}" class="btn btn-sm btn-link text-capitalize"><i
            class="la la-envelope"></i>Send via Mail</a>
@endif
