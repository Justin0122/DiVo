<?php

namespace App\Livewire;

use DateTime;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\VotingPeriod as VotingPeriodModel;

class VotingPeriod extends Component
{
    use WithPagination;

    #[Url (as: 'id')]
    public $id;
    public $form = [];
    public $perPage = 10;
    public $url = '';


    public function render(): View|Application|Factory|\Illuminate\Contracts\Foundation\Application
    {
        $now = new DateTime();
        $this->form['start_time'] = $now->format('Y-m-d') . ' 12:00:00';

        if ($this->id && !VotingPeriodModel::find($this->id)) {
            $this->id = '';
        } elseif ($this->id) {
            $this->form = VotingPeriodModel::find($this->id)->toArray();
        }
        return view('livewire.VotingPeriod.index',
            [
                'results' => $this->id ? VotingPeriodModel::find($this->id) : VotingPeriodModel::paginate($this->perPage),
                'current' => $this->id ? VotingPeriodModel::find($this->id) : VotingPeriodModel::where('end_time', '>=', now())
                    ->first(),
                'fillables' => (new VotingPeriodModel())->getFillable(),
                'url' => current(explode('?', url()->current())),
            ]);
    }

    public function create(): void
    {
        $VotingPeriod = new VotingPeriodModel();
        foreach ($this->form as $key => $value) {
            $VotingPeriod->$key = $value;
        }
        $VotingPeriod->save();
    }

    public function update(): void
    {
        $VotingPeriod = VotingPeriodModel::find($this->id);
        foreach ($this->form as $key => $value) {
            $VotingPeriod->$key = $value;
        }
        $VotingPeriod->save();
    }

    public function delete($id): RedirectResponse
    {
        $VotingPeriod = VotingPeriodModel::find($id);
        $VotingPeriod->delete();

        return redirect()->back();
    }
}
