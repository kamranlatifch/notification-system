<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\SystemNotificationRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Illuminate\Support\Facades\Mail;
use App\Mail\SystemNotificationMail;
use App\Models\SystemNotification;

/**
 * Class SystemNotificationCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class SystemNotificationCrudController extends CrudController
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
        CRUD::setModel(\App\Models\SystemNotification::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/system-notification');
        CRUD::setEntityNameStrings('system notification', 'system notifications');
    }


//     public function sendEmail($notification_id)
// {

//     $notification = SystemNotification::findOrFail($notification_id);

//     // // Prepare the email message
//     // $email = new SystemNotificationMail($notification);

//     try {
//         // Send the email

//             $title = 'Metal OWY';
//             $body = $notification->name;

//             // Mail::to('kamranlatif98@gmail.com')->send(new SystemNotificationMail($title, $body));
//             Mail::to($notification->to)
//             ->cc(explode(',', $notification->cc))
//             ->send(new SystemNotificationMail($title, $body));

//             // \Alert::success(trans('Notification via Email Sent Successfully.'))->flash();


//         // Redirect back with a success message
//         \Alert::success(trans('Notification via Email Sent Successfully.'))->flash();
//     } catch (\Exception $e) {
//         // Handle any exceptions, such as mail delivery failure
//         \Alert::error('Failed to send notification email: ' . $e->getMessage())->flash();
//     }
//     return redirect()->back();
// }
public function sendEmail($notification_id)
{
    // Get the notification by ID
    $notification = SystemNotification::findOrFail($notification_id);

    try {
        // Prepare the email message
        $title ="Metalo OWY.";
        $body = $notification->name;

        // Send the email to 'to' recipients
        $toRecipients = explode(',', $notification->to);
        foreach ($toRecipients as $to) {
            Mail::to(trim($to))->send(new SystemNotificationMail($title, $body));
        }

        // Send a copy to 'cc' recipients
        $ccRecipients = explode(',', $notification->cc);
        foreach ($ccRecipients as $cc) {
            Mail::cc(trim($cc))->send(new SystemNotificationMail($title, $body));
        }

        // Redirect back with a success message
        \Alert::success(trans('Notification via Email Sent Successfully.'))->flash();
    } catch (\Exception $e) {
        // Handle any exceptions, such as mail delivery failure
        \Alert::error('Failed to send notification email: ' . $e->getMessage())->flash();
    }

    return redirect()->back();
}

    /**
     * Define what happens when the List operation is loaded.
     *
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        CRUD::setFromDb(); // set columns from db columns.
        $this->crud->addButtonFromView('line', 'send_as_email', 'send_as_email', 'end');
        /**
         * Columns can be defined using the fluent syntax:
         * - CRUD::column('price')->type('number');
         */
    }

    /**
     * Define what happens when the Create operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation(SystemNotificationRequest::class);
        CRUD::setFromDb(); // set fields from db columns.

        /**
         * Fields can be defined using the fluent syntax:
         * - CRUD::field('price')->type('number');
         */
    }

    /**
     * Define what happens when the Update operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-update
     * @return void
     */
    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }
}
