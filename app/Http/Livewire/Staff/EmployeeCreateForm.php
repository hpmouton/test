<?php

namespace App\Http\Livewire\Staff;

use Livewire\Component;
use Filament\Forms;
use Illuminate\Contracts\View\View;
use App\Models\User;
use App\Models\Section;
class EmployeeCreateForm extends Component implements Forms\Contracts\HasForms
{
    use Forms\Concerns\InteractsWithForms;
    public $name = '';
    public $email = '';
    public $section_id = '';
    public $supervisor_id = '';

    public function getFormSchema(): array{
        return [
            Forms\Components\Select::make('section_id')
            ->label('Section')
            ->options(Section::all()->pluck('name','id')->toArray())
            ->reactive()
            ->afterStateUpdated(fn (callable $set)=>$set('supervisor_id', [])),

        Forms\Components\Select::make('supervisor_id')
            ->label('Supervisor')
            ->options(function (callable $get){

                $section = Section::find($get('section_id'));
                if(! $section){
                    return User::all()->pluck('name','id')->toArray();
                }
                return $section->user->pluck('name','id')->toArray();
            }),
        Forms\Components\TextInput::make('name')
            ->required(),
        Forms\Components\TextInput::make('email')
            ->required()
            ->email()
            ->unique(User::class, 'email', fn ($record) => $record),
        ];
    }

    public function render()
    {

        return view('livewire.staff.employee-create-form');
    }
}
