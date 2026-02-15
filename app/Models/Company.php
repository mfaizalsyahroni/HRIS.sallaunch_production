<?php

    namespace App\Models;

    use Illuminate\Database\Eloquent\Model;

    class Company extends Model
    {
        protected $fillable = [
            'name',
            'description',
            'vision',
            'mission',
            'established_at',
            'logo',
            'employee_count',
            'department_count',
            'branch_count',
            'project_count',
            'stock_value',
            'stock_growth',
        ];

        protected $casts = [
            'established_at' => 'date',
        ];

        // Format stock value as IDR
        public function getStockValueFormattedAttribute()
        {
            return 'IDR ' . number_format($this->stock_value ?? 0, 0, ',', '.');
        }

        public function stats()
        {
            return [
                'Total Employees' => [
                    'value' => $this->employee_count,
                    'icon' => 'bi-people-fill',
                ],
                'Departments' => [
                    'value' => $this->department_count,
                    'icon' => 'bi-briefcase',
                ],
                'Branches' => [
                    'value' => $this->branch_count,
                    'icon' => 'bi-building',
                ],
                'Projects' => [
                    'value' => $this->project_count,
                    'icon' => 'bi-kanban-fill',
                ],
                'Stock Value' => [
                    'value' => number_format($this->stock_value, 0, ',', '.'),
                    'icon' => 'bi-currency-dollar',
                ],
                'Stock Growth' => [
                    'value' => $this->stock_growth . '%',
                    'icon' => 'bi-graph-up',
                ],
            ];
        }



    }