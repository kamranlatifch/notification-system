<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\ContactRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanel;

use Illuminate\Support\Str;
use App\Models\Contact;
use App\Models\User;
use App\Models\Location;


/**
 * Class ContactCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class ContactCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     *
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(Contact::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/contact');
        CRUD::setEntityNameStrings('contact', 'contacts');
        CRUD::addField([
            'name' => 'first_name',
            'label' => 'First Name',
            'type' => 'text',
        ]);

        CRUD::addField([
            'name' => 'last_name',
            'label' => 'Last Name',
            'type' => 'text',
        ]);

        CRUD::addField([
            'name' => 'email',
            'label' => 'Email',
            'type' => 'email',
        ]);

        CRUD::addField([
            'name' => 'phone',
            'label' => 'Phone',
            'type' => 'text',
        ]);

        CRUD::addField([
            'name' => 'mobile',
            'label' => 'Mobile',
            'type' => 'text',
        ]);
        $this->addLocationFields();
    }

    protected function addLocationFields()
    {
        $fields = ['country', 'state', 'city', 'zip', 'address'];

        foreach ($fields as $field) {
            $contactId = $this->crud->getCurrentEntryId();
            $contact = Contact::with('location')->find($contactId);
            $defaultValue = null;
            if ($contact && $contact->location) {
                $defaultValue = $contact->location->$field;
            }
            CRUD::addField([
                'name' => $field,
                'label' => ucfirst($field),
                'type' => 'text',
                'default' => $defaultValue,
            ]);
        }
    }

    public function convertToUser($id)
{
    $contact = Contact::find($id);
    if (!$contact) {
        abort(404, 'Contact not found');
    }
    $existingUser = User::where('email', $contact->email)->first();

    if ($existingUser) {
        \Alert::warning(trans('This contact is already a user.'))->flash();
        return redirect()->back();
    }
    $randomPassword = Str::random(10);
    // $user = new User([
    //     'name' => $contact->first_name,
    //     'email' => $contact->email,
    //     'password' => bcrypt($randomPassword), // Hardcoded password "12345"
    //     'role_id' => null,
    //     'contact_id' => $contact->id,
    // ]);
    $user = User::create([
        'name' => $contact->first_name,
        'email' => $contact->email,
        'password' => bcrypt($randomPassword), // Hardcoded password "12345"
        'role_id' => null,
        'contact_id' => $contact->id,
    ]);
    $user->save();
    \Alert::success(trans('This contact is created as user.'))->flash();
    return redirect('admin/user');
}
    /**
     * Define what happens when the List operation is loaded.
     *
     * @see
     * @return void
     */

    protected function setupListOperation()
    {
        CRUD::setFromDb();
        $this->crud->addColumn([
            'name' => 'location.country',
            'label' => 'Country',
        ]);
        $this->crud->addButtonFromView('line', 'convert_to_user', 'convert_to_user', 'end');
    }
    /**
     * Define what happens when the Create operation is loaded.
     *
     * @see
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation(ContactRequest::class);
        CRUD::setFromDb();
    }
    /**
     * Define what happens when the Update operation is loaded.
     *
     * @see
     * @return void
     */
    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }
    protected function setupShowOperation()
    {

        $this->crud->addColumn([
            'name' => 'first_name',
            'label' => 'First Name',
        ]);
        $this->crud->addColumn([
            'name' => 'last_name',
            'label' => 'Last Name',
        ]);

        $this->crud->addColumn([
            'name' => 'email',
            'label' => 'Email',
        ]);


        $this->crud->addColumn([
            'name' => 'location.country',
            'label' => 'Country',
        ]);


        $this->crud->addColumn([
            'name' => 'location.state',
            'label' => 'State',
        ]);

        $this->crud->addColumn([
            'name' => 'location.city',
            'label' => 'City',
        ]);

        $this->crud->addColumn([
            'name' => 'location.zip',
            'label' => 'Zip',
        ]);

        $this->crud->addColumn([
            'name' => 'location.address',
            'label' => 'Address',
        ]);

        $this->crud->addColumn([
            'name' => 'created_at',
            'label' => 'Created',
        ]);
        $this->crud->addColumn([
            'name' => 'updated_at',
            'label' => 'Updated',
        ]);
    }

}