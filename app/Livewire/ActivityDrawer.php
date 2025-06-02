<?php

namespace App\Livewire;

use App\Models\ActivityLog;
use Livewire\Component;

class ActivityDrawer extends Component
{
    public $activities = [];
    protected $listeners = ['activity-logged' => 'loadActivities'];

    public function mount()
    {
        $this->loadActivities();
    }

    public function render()
    {
        $this->loadActivities(); // ← Load latest on each re-render (triggered by poll)
        return view('livewire.activity-drawer');
    }

    public function loadActivities()
    {
        $this->activities = ActivityLog::with('user')->latest()->take(10)->get();
    }
}
