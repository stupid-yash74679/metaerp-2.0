<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Projects\ProjectType; // Your ProjectType model
use App\Models\User;
use Illuminate\Support\Facades\DB; // For direct DB interaction if preferred, but Eloquent is fine
use Illuminate\Support\Str; // For generating unique stage IDs

class ProjectTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $firstUser = User::first(); // Get a user to associate as creator
        $createdBy = $firstUser ? $firstUser->id : null;

        $projectTypesData = [
            [
                'name' => 'Residential Prefab Home - Single Unit',
                'description' => 'Standard single-family prefabricated home construction from design to installation.',
                'is_active' => true,
                'stages' => [
                    ['id' => (string) Str::uuid(), 'name' => 'Client Consultation & Requirement Gathering', 'order' => 1, 'is_default_start' => true, 'color' => '#4CAF50'],
                    ['id' => (string) Str::uuid(), 'name' => 'Site Assessment & Feasibility', 'order' => 2, 'color' => '#8BC34A'],
                    ['id' => (string) Str::uuid(), 'name' => 'Design & Engineering Approvals', 'order' => 3, 'color' => '#CDDC39'],
                    ['id' => (string) Str::uuid(), 'name' => 'Permitting', 'order' => 4, 'color' => '#FFEB3B'],
                    ['id' => (string) Str::uuid(), 'name' => 'Factory Prefabrication & QA', 'order' => 5, 'color' => '#FFC107'],
                    ['id' => (string) Str::uuid(), 'name' => 'Site Preparation & Foundation', 'order' => 6, 'color' => '#FF9800'],
                    ['id' => (string) Str::uuid(), 'name' => 'Module Transportation & Logistics', 'order' => 7, 'color' => '#FF5722'],
                    ['id' => (string) Str::uuid(), 'name' => 'On-site Assembly & Installation', 'order' => 8, 'color' => '#F44336'],
                    ['id' => (string) Str::uuid(), 'name' => 'Finishing & Utility Hookups', 'order' => 9, 'color' => '#E91E63'],
                    ['id' => (string) Str::uuid(), 'name' => 'Final Inspection & Handover', 'order' => 10, 'is_default_end' => true, 'color' => '#9C27B0'],
                ]
            ],
            [
                'name' => 'Modular Commercial Building - Small Scale',
                'description' => 'Design and construction of small-scale modular commercial structures like site offices or retail kiosks.',
                'is_active' => true,
                'stages' => [
                    ['id' => (string) Str::uuid(), 'name' => 'Initial Brief & Scope Definition', 'order' => 1, 'is_default_start' => true, 'color' => '#2196F3'],
                    ['id' => (string) Str::uuid(), 'name' => 'Concept Design & Budgeting', 'order' => 2, 'color' => '#03A9F4'],
                    ['id' => (string) Str::uuid(), 'name' => 'Detailed Engineering & Approvals', 'order' => 3, 'color' => '#00BCD4'],
                    ['id' => (string) Str::uuid(), 'name' => 'Module Manufacturing', 'order' => 4, 'color' => '#009688'],
                    ['id' => (string) Str::uuid(), 'name' => 'Site Works & Foundation', 'order' => 5, 'color' => '#4CAF50'],
                    ['id' => (string) Str::uuid(), 'name' => 'Delivery & Crane Operations', 'order' => 6, 'color' => '#8BC34A'],
                    ['id' => (string) Str::uuid(), 'name' => 'Installation & Seaming', 'order' => 7, 'color' => '#CDDC39'],
                    ['id' => (string) Str::uuid(), 'name' => 'Interior Fit-out & Services', 'order' => 8, 'color' => '#FFEB3B'],
                    ['id' => (string) Str::uuid(), 'name' => 'Commissioning & Handover', 'order' => 9, 'is_default_end' => true, 'color' => '#FFC107'],
                ]
            ],
            [
                'name' => 'Prefabricated Bathroom Pods (B2B)',
                'description' => 'Manufacturing and supply of prefabricated bathroom units for large construction projects.',
                'is_active' => true,
                'stages' => [
                    ['id' => (string) Str::uuid(), 'name' => 'Client Order & Specification Review', 'order' => 1, 'is_default_start' => true, 'color' => '#607D8B'],
                    ['id' => (string) Str::uuid(), 'name' => 'Prototyping & Sample Approval', 'order' => 2, 'color' => '#795548'],
                    ['id' => (string) Str::uuid(), 'name' => 'Production Planning & Material Procurement', 'order' => 3, 'color' => '#9E9E9E'],
                    ['id' => (string) Str::uuid(), 'name' => 'Assembly Line Manufacturing', 'order' => 4, 'color' => '#FF5722'],
                    ['id' => (string) Str::uuid(), 'name' => 'Quality Control & Testing', 'order' => 5, 'color' => '#F44336'],
                    ['id' => (string) Str::uuid(), 'name' => 'Packaging & Dispatch Scheduling', 'order' => 6, 'color' => '#E91E63'],
                    ['id' => (string) Str::uuid(), 'name' => 'Delivery to Site', 'order' => 7, 'is_default_end' => true, 'color' => '#9C27B0'],
                ]
            ],
            [
                'name' => 'Temporary Event Structures (Prefab)',
                'description' => 'Design, fabrication, and installation of temporary prefabricated structures for events.',
                'is_active' => true,
                'stages' => [
                    ['id' => (string) Str::uuid(), 'name' => 'Event Brief & Requirements', 'order' => 1, 'is_default_start' => true, 'color' => '#3F51B5'],
                    ['id' => (string) Str::uuid(), 'name' => 'Conceptual Design & Visualization', 'order' => 2, 'color' => '#2196F3'],
                    ['id' => (string) Str::uuid(), 'name' => 'Structural Engineering & Safety Compliance', 'order' => 3, 'color' => '#03A9F4'],
                    ['id' => (string) Str::uuid(), 'name' => 'Fabrication & Branding', 'order' => 4, 'color' => '#00BCD4'],
                    ['id' => (string) Str::uuid(), 'name' => 'Logistics & Site Access Plan', 'order' => 5, 'color' => '#009688'],
                    ['id' => (string) Str::uuid(), 'name' => 'On-site Erection & Setup', 'order' => 6, 'color' => '#4CAF50'],
                    ['id' => (string) Str::uuid(), 'name' => 'Event Operation Support (Optional)', 'order' => 7, 'color' => '#8BC34A'],
                    ['id' => (string) Str::uuid(), 'name' => 'Dismantling & Site Clearance', 'order' => 8, 'is_default_end' => true, 'color' => '#CDDC39'],
                ]
            ],
            [
                'name' => 'Custom Prefab Kiosk/Booth',
                'description' => 'Bespoke design and fabrication of kiosks or booths for retail or exhibitions.',
                'is_active' => true,
                'stages' => [
                    ['id' => (string) Str::uuid(), 'name' => 'Client Design Brief', 'order' => 1, 'is_default_start' => true, 'color' => '#7CB342'],
                    ['id' => (string) Str::uuid(), 'name' => '3D Modelling & Revision', 'order' => 2, 'color' => '#8BC34A'],
                    ['id' => (string) Str::uuid(), 'name' => 'Material Selection & Costing', 'order' => 3, 'color' => '#CDDC39'],
                    ['id' => (string) Str::uuid(), 'name' => 'Fabrication', 'order' => 4, 'color' => '#FFEB3B'],
                    ['id' => (string) Str::uuid(), 'name' => 'Finishing & Graphics Application', 'order' => 5, 'color' => '#FFC107'],
                    ['id' => (string) Str::uuid(), 'name' => 'Delivery/Installation', 'order' => 6, 'is_default_end' => true, 'color' => '#FF9800'],
                ]
            ],
            [
                'name' => 'Facade Panel Manufacturing',
                'description' => 'Manufacturing of prefabricated facade panels for building exteriors.',
                'is_active' => true,
                'stages' => [
                    ['id' => (string) Str::uuid(), 'name' => 'Architectural Drawings Review', 'order' => 1, 'is_default_start' => true, 'color' => '#00ACC1'],
                    ['id' => (string) Str::uuid(), 'name' => 'Shop Drawings & Panel Design', 'order' => 2, 'color' => '#00BCD4'],
                    ['id' => (string) Str::uuid(), 'name' => 'Material Sourcing & QA', 'order' => 3, 'color' => '#009688'],
                    ['id' => (string) Str::uuid(), 'name' => 'Panel Fabrication & Assembly', 'order' => 4, 'color' => '#4CAF50'],
                    ['id' => (string) Str::uuid(), 'name' => 'Surface Treatment & Finishing', 'order' => 5, 'color' => '#8BC34A'],
                    ['id' => (string) Str::uuid(), 'name' => 'Final QA & Packaging', 'order' => 6, 'color' => '#CDDC39'],
                    ['id' => (string) Str::uuid(), 'name' => 'Shipment to Site', 'order' => 7, 'is_default_end' => true, 'color' => '#FFEB3B'],
                ]
            ],
            [
                'name' => 'Affordable Housing Project (Prefab)',
                'description' => 'Mass production and installation of affordable prefabricated housing units.',
                'is_active' => true,
                'stages' => [
                    ['id' => (string) Str::uuid(), 'name' => 'Govt. Tender/Client MoU', 'order' => 1, 'is_default_start' => true, 'color' => '#D32F2F'],
                    ['id' => (string) Str::uuid(), 'name' => 'Standardized Design Approval', 'order' => 2, 'color' => '#E91E63'],
                    ['id' => (string) Str::uuid(), 'name' => 'Bulk Material Procurement', 'order' => 3, 'color' => '#F44336'],
                    ['id' => (string) Str::uuid(), 'name' => 'Assembly Line Setup & Optimization', 'order' => 4, 'color' => '#FF5722'],
                    ['id' => (string) Str::uuid(), 'name' => 'Mass Production of Modules', 'order' => 5, 'color' => '#FF9800'],
                    ['id' => (string) Str::uuid(), 'name' => 'Logistics & Phased Delivery Planning', 'order' => 6, 'color' => '#FFC107'],
                    ['id' => (string) Str::uuid(), 'name' => 'Rapid On-site Assembly', 'order' => 7, 'color' => '#FFEB3B'],
                    ['id' => (string) Str::uuid(), 'name' => 'Community Infrastructure Integration', 'order' => 8, 'color' => '#CDDC39'],
                    ['id' => (string) Str::uuid(), 'name' => 'Handover & Occupancy', 'order' => 9, 'is_default_end' => true, 'color' => '#8BC34A'],
                ]
            ],
            [
                'name' => 'Prefabricated Site Office Setup',
                'description' => 'Quick deployment of prefabricated office units for construction sites.',
                'is_active' => true,
                'stages' => [
                    ['id' => (string) Str::uuid(), 'name' => 'Client Requirement (Size, Layout)', 'order' => 1, 'is_default_start' => true, 'color' => '#546E7A'],
                    ['id' => (string) Str::uuid(), 'name' => 'Stock Availability Check / Quick Fab', 'order' => 2, 'color' => '#607D8B'],
                    ['id' => (string) Str::uuid(), 'name' => 'Transportation to Site', 'order' => 3, 'color' => '#78909C'],
                    ['id' => (string) Str::uuid(), 'name' => 'Placement & Leveling', 'order' => 4, 'color' => '#90A4AE'],
                    ['id' => (string) Str::uuid(), 'name' => 'Basic Utility Hookup (Power, Water)', 'order' => 5, 'color' => '#B0BEC5'],
                    ['id' => (string) Str::uuid(), 'name' => 'Handover to Site Manager', 'order' => 6, 'is_default_end' => true, 'color' => '#CFD8DC'],
                ]
            ],
            [
                'name' => 'Modular Classroom Blocks',
                'description' => 'Design, fabrication, and installation of modular classroom buildings for educational institutions.',
                'is_active' => false, // Example of an inactive type
                'stages' => [
                    ['id' => (string) Str::uuid(), 'name' => 'Educational Board Consultation', 'order' => 1, 'is_default_start' => true, 'color' => '#4A148C'],
                    ['id' => (string) Str::uuid(), 'name' => 'Classroom Layout Design & Compliance', 'order' => 2, 'color' => '#6A1B9A'],
                    ['id' => (string) Str::uuid(), 'name' => 'Manufacturing of Classroom Modules', 'order' => 3, 'color' => '#7B1FA2'],
                    ['id' => (string) Str::uuid(), 'name' => 'Site Preparation & Foundation', 'order' => 4, 'color' => '#8E24AA'],
                    ['id' => (string) Str::uuid(), 'name' => 'Module Installation & Linking', 'order' => 5, 'color' => '#9C27B0'],
                    ['id' => (string) Str::uuid(), 'name' => 'Interior Finishing & Furnishing', 'order' => 6, 'color' => '#AB47BC'],
                    ['id' => (string) Str::uuid(), 'name' => 'Final Safety Checks & Handover', 'order' => 7, 'is_default_end' => true, 'color' => '#BA68C8'],
                ]
            ],
            [
                'name' => 'Emergency Relief Shelters (Prefab)',
                'description' => 'Rapid deployment of prefabricated shelters for disaster relief.',
                'is_active' => true,
                'stages' => [
                    ['id' => (string) Str::uuid(), 'name' => 'Needs Assessment & Deployment Area ID', 'order' => 1, 'is_default_start' => true, 'color' => '#B71C1C'],
                    ['id' => (string) Str::uuid(), 'name' => 'Stock Mobilization / Rapid Fabrication', 'order' => 2, 'color' => '#C62828'],
                    ['id' => (string) Str::uuid(), 'name' => 'Logistics & Transportation (Air/Land)', 'order' => 3, 'color' => '#D32F2F'],
                    ['id' => (string) Str::uuid(), 'name' => 'Quick On-Site Assembly', 'order' => 4, 'color' => '#E53935'],
                    ['id' => (string) Str::uuid(), 'name' => 'Basic Amenities Provision', 'order' => 5, 'color' => '#F44336'],
                    ['id' => (string) Str::uuid(), 'name' => 'Distribution & Occupancy Management', 'order' => 6, 'is_default_end' => true, 'color' => '#EF5350'],
                ]
            ],
        ];

        foreach ($projectTypesData as $typeData) {
            ProjectType::updateOrCreate(
                ['name' => $typeData['name']], // Condition to find existing record
                [
                    'description' => $typeData['description'],
                    'stages' => json_encode($typeData['stages']), // Ensure stages are JSON encoded
                    'is_active' => $typeData['is_active'],
                    'created_by' => $createdBy,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }

        $this->command->info('Prefab industry project types and stages seeded successfully!');
    }
}
