{{-- This file is used for menu items by any Backpack v6 theme --}}
<li class="nav-item"><a class="nav-link" href="{{ backpack_url('dashboard') }}"><i class="la la-home nav-icon"></i>
        {{ trans('backpack::base.dashboard') }}</a></li>

<x-backpack::menu-item title="Notifications" icon="la la-bell" :link="backpack_url('notifications')" />

<x-backpack::menu-item title="Users" icon="la la-user" :link="backpack_url('user')" />
<x-backpack::menu-item title="Contacts" icon="la la-user-friends" :link="backpack_url('contact')" />
{{-- <x-backpack::menu-item title="Locations" icon="la la-question" :link="backpack_url('location')" /> --}}
<x-backpack::menu-item title="Settings" icon="la la-cog" :link="backpack_url('setting')" />
<x-backpack::menu-item title="System Notifications" icon="la la-concierge-bell" :link="backpack_url('system-notification')" />
{{-- <x-backpack::menu-item title="Roles" icon="la la-question" :link="backpack_url('roles')" /> --}}
