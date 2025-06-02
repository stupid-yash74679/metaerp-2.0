<?php

use App\Models\Contact;
use App\Models\Proposal;
use App\Models\User;
use Diglactic\Breadcrumbs\Breadcrumbs;
use Diglactic\Breadcrumbs\Generator as BreadcrumbTrail;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;
use App\Models\ContactGroup;
use App\Models\Currency;
use App\Models\CRM\Lead;
use App\Models\CustomField;

// Home
Breadcrumbs::for('home', function (BreadcrumbTrail $trail) {
    $trail->push('Home', route('dashboard'));
});

// Home > Dashboard
Breadcrumbs::for('dashboard', function (BreadcrumbTrail $trail) {
    $trail->parent('home');
    $trail->push('Dashboard', route('dashboard'));
});

// Home > Dashboard > User Management
Breadcrumbs::for('user-management.index', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push('User Management', route('user-management.users.index'));
});

// Home > Dashboard > User Management > Users
Breadcrumbs::for('user-management.users.index', function (BreadcrumbTrail $trail) {
    $trail->parent('user-management.index');
    $trail->push('Users', route('user-management.users.index'));
});

// Home > Dashboard > User Management > Users > [User]
Breadcrumbs::for('user-management.users.show', function (BreadcrumbTrail $trail, User $user) {
    $trail->parent('user-management.users.index');
    $trail->push(ucwords($user->name), route('user-management.users.show', $user));
});

// Home > Dashboard > User Management > Roles
Breadcrumbs::for('user-management.roles.index', function (BreadcrumbTrail $trail) {
    $trail->parent('user-management.index');
    $trail->push('Roles', route('user-management.roles.index'));
});

// Home > Dashboard > User Management > Roles > [Role]
Breadcrumbs::for('user-management.roles.show', function (BreadcrumbTrail $trail, Role $role) {
    $trail->parent('user-management.roles.index');
    $trail->push(ucwords($role->name), route('user-management.roles.show', $role));
});

// Home > Dashboard > User Management > Permission
Breadcrumbs::for('user-management.permissions.index', function (BreadcrumbTrail $trail) {
    $trail->parent('user-management.index');
    $trail->push('Permissions', route('user-management.permissions.index'));
});

// Home > Dashboard > Contacts
Breadcrumbs::for('contacts.index', function (BreadcrumbTrail $trail) {
    $trail->parent('home');
    $trail->push('Contacts', route('contacts.index'));
});

// Home > Dashboard > Contacts > Contact Groups
Breadcrumbs::for('contacts.contact-groups.index', function (BreadcrumbTrail $trail) {
    $trail->parent('contacts.index');
    $trail->push('Contact Groups', route('contacts.contact-groups.index'));
});

// Home > Dashboard > Contacts > Contact Groups > [Group Name]
Breadcrumbs::for('contacts.contact-groups.show', function (BreadcrumbTrail $trail, ContactGroup $contactGroup) {
    $trail->parent('contacts.contact-groups.index');
    $trail->push(ucwords($contactGroup->name), route('contacts.contact-groups.show', $contactGroup));
});

// Home > Dashboard > Contacts > Contact List
Breadcrumbs::for('contacts.contacts.index', function (BreadcrumbTrail $trail) {
    $trail->parent('contacts.index');
    $trail->push('Contact List', route('contacts.index'));
});

// Home > Dashboard > Contacts > Contact List > [Contact Name or New]
Breadcrumbs::for('contacts.contacts.show', function (BreadcrumbTrail $trail, Contact $contact) {
    $trail->parent('contacts.contacts.index');
    $label = $contact->exists ? ucwords($contact->name) : 'New Contact';
    $url = $contact->exists ? route('contacts.contacts.show', $contact) : '#'; // Assuming route name 'contacts.contacts.show'
    $trail->push($label, $url);
});

// Home > Dashboard > System > Currencies
Breadcrumbs::for('system.currencies.index', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push('Currencies', route('system.currencies.index'));
});

// Home > Dashboard > System > Currencies > [Currency]
Breadcrumbs::for('system.currencies.show', function (BreadcrumbTrail $trail, Currency $currency) {
    $trail->parent('system.currencies.index');
    $trail->push(ucwords($currency->code), route('system.currencies.show', $currency));
});

// Home > Dashboard > CRM > Leads
Breadcrumbs::for('crm.leads.index', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push('Leads', route('leads.index'));
});

// Home > Dashboard > CRM > Leads > Add
Breadcrumbs::for('crm.leads.add', function (BreadcrumbTrail $trail) {
    $trail->parent('crm.leads.index');
    $trail->push('Add Lead', route('leads.add'));
});

// Home > Dashboard > CRM > Leads > [Lead #]
Breadcrumbs::for('crm.leads.show', function (BreadcrumbTrail $trail, Lead $lead) {
    $trail->parent('crm.leads.index');
    $trail->push('Lead #' . $lead->enquiry_number, route('leads.show', $lead));
});

// Home > Dashboard > System > Custom Fields
Breadcrumbs::for('system.custom-fields.index', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push('Custom Fields', route('system.custom-fields.index'));
});

// Home > Dashboard > System > Custom Fields > [Custom Field]
Breadcrumbs::for('system.custom-fields.show', function (BreadcrumbTrail $trail, CustomField $customField) {
    $trail->parent('system.custom-fields.index');
    $trail->push(ucwords($customField->label), route('system.custom-fields.show', $customField));
});
// Home > Dashboard > System > Message Templates
Breadcrumbs::for('system.message-templates.index', function ($trail) {
    $trail->parent('dashboard'); // Or your correct system parent
    $trail->push('Message Templates', route('system.message-templates.index'));
});
// Home > Dashboard > System > Company Settings
Breadcrumbs::for('system.company-settings.edit', function ($trail) {
    $trail->parent('dashboard'); // Or your system index breadcrumb
    $trail->push('Company Settings', route('system.company-settings.edit'));
});
// Home > Dashboard > System > Tax Rates
Breadcrumbs::for('system.tax-rates.index', function ($trail) {
    $trail->parent('dashboard'); // Or parent to a general 'system.index' if you have one
    // If you have a general system page: $trail->parent('system.index');
    // $trail->push('System Settings', route('some.system.index.route')); // Example if 'System' is a page
    $trail->push('Tax Rates', route('system.tax-rates.index'));
});
// Home > Dashboard > Projects (Optional parent for all project related things)
Breadcrumbs::for('projects.index', function ($trail) {
    $trail->parent('dashboard'); // Or your main app dashboard breadcrumb
    $trail->push('Projects', route('projects.project-types.index')); // Point to project types list as the main projects landing for now
});

// Home > Dashboard > Projects > Project Types
Breadcrumbs::for('projects.project-types.index', function ($trail) {
    $trail->parent('projects.index'); // Assumes a projects.index breadcrumb exists
    // If not, change parent to 'dashboard' or appropriate parent
    // $trail->parent('dashboard');
    $trail->push('Project Types', route('projects.project-types.index'));
});
