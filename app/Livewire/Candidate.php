<?php

namespace App\Livewire;

use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use App\Models\Candidate as CandidateModel;
use App\Models\Party as PartyModel;

class Candidate extends Component
{
    use WithPagination;
    use WithFileUploads;

    #[Url(as: 'id')]
    public $id;
    #[Url(as: 'q')]
    public $search = "";
    #[Url (as: 'pid')]
    public $party_id = null;
    public $form = [];

    public $url = '';
    public $perPage = 10;
    public $showDeleted = false;


    protected $rules = [

        'form.name' => 'required',
        'form.birthdate' => 'required|date',
        'form.bio' => 'required',
        'form.image' => 'nullable|image|mimes:jpeg,png,jpg,svg|max:2048',
    ];

    public function render()
    {
        $warning = null;

        if ($this->id) {
            $results = $this->id
                ? CandidateModel::withTrashed()->with('parties')->find($this->id)
                : CandidateModel::withTrashed()->with('parties')->get();
        } else if ($this->party_id) {
            $results = PartyModel::withoutTrashed()->find($this->party_id)->candidates;
            $warning = "You're looking at the members of " . PartyModel::find($this->party_id)->name . " to return to the full view, use the sidebar!";
        } else {
            $results = $this->getCandidates();
        }


        return view(
            'livewire.Candidates.index',
            [
                'results' => $results,
                'fillables' => (new CandidateModel())->getFillable(),
                'url' => current(explode('?', url()->current())),
                'warning' => $warning
            ]
        );
    }


    public function mount()
    {
        $this->perPage = session()->get('perPage') ?? 10;

        if ($this->id) {
            $candidate = CandidateModel::withTrashed()->find($this->id);
            $this->form = $candidate->toArray();
        }
    }

    public function create()
    {
        $this->validate();
        $candidate = new CandidateModel();
        foreach ($this->form as $key => $value) {
            $candidate->$key = $value;
        }
        if (isset($this->form['image'])) {
            $candidate->image = $this->form['image']->store('candidates', 'public');
            $candidate->image = '/storage/' . $candidate->image;
        }

        $candidate->save();
        $this->form = [];

        session()->flash('message', 'Candidate successfully created.');
    }

    public function update()
    {
        $candidate = CandidateModel::withTrashed()->find($this->id);
        foreach ($this->form as $key => $value) {
            $candidate->$key = $value;
        }
        $candidate->save();
        if (isset($this->form['image'])) {
            $candidate->image = $this->form['image']->store('candidates', 'public');
            $candidate->image = '/storage/' . $candidate->image;
            $candidate->save();
        }

        session()->flash('message', 'Candidate successfully updated.');
    }

    public function delete($id)
    {
        $candidate = CandidateModel::find($id);
        $candidate->delete();
        session()->flash('message', 'Candidate successfully deleted.');

        return redirect()->back();
    }

    public function restore($id)
    {
        $candidate = CandidateModel::withTrashed()->find($id);
        $candidate->restore();
        session()->flash('message', 'Candidate successfully restored.');

        return redirect()->back();
    }

    public function forceDelete($id)
    {
        $candidate = CandidateModel::withTrashed()->find($id);
        $candidate->forceDelete();
        session()->flash('message', 'Candidate successfully deleted.');

        return redirect()->back();
    }

    public function clearFilters()
    {
        $this->reset(['search', 'showDeleted']);
    }

    private function getCandidates()
    {
        $query = CandidateModel::query();

        if ($this->id && !CandidateModel::withTrashed()->find($this->id)) {
            $this->id = '';
        }

        if ($this->perPage) {
            session()->put('perPage', $this->perPage);
        }

        if ($this->showDeleted) {
            $query->onlyTrashed();
        } else {
            $query->withoutTrashed();
        }

        $query->where(function ($query) {
            $query->where('name', 'like', '%' . $this->search . '%');
        })
            ->orderBy('id', 'desc')
            ->whereNotIn('id', function ($query) {
                $query->select('candidate_id')
                    ->from('party_candidate')
                    ->where('party_id', $this->id);
            });

        return $query->paginate($this->perPage);
    }
}
