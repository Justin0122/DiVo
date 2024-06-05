<?php

namespace App\Livewire;

use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Party as PartyModel;
use App\Models\Candidate;
use Livewire\WithFileUploads;

class Party extends Component
{
    use WithPagination;
    use WithFileUploads;

    #[Url(as: 'id')]
    public $id;
    #[Url(as: 'q')]
    public $search = "";
    public $form = [];

    public $url = '';
    public $searchUnassignedCandidates = '';
    public $perPage = 10;
    public $showDeleted = false;

    protected $rules = [
        'form.name' => 'required',
        'form.abbreviation' => 'required',
        'form.bio' => 'required',
        'form.logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
    ];

    public function render(): View|Application|Factory|\Illuminate\Contracts\Foundation\Application
    {
        $this->validateId();
        $this->updatePerPageSession();

        $partyQuery = $this->getPartyQuery();
        $candidates = $this->getCandidates();

        return view('livewire.Party.index', [
            'results' => $this->id ? PartyModel::withTrashed()->find($this->id) : $partyQuery->paginate($this->perPage),
            'fillables' => (new PartyModel())->getFillable(),
            'url' => current(explode('?', url()->current())),
            'candidates' => $candidates,
        ]);
    }

    public function mount(): void
    {
        $this->perPage = session()->get('perPage') ?? 10;

        if ($this->id) {
            $party = PartyModel::withTrashed()->find($this->id);
            $this->form = $party->toArray();
        }
    }

    public function create(): void
    {
        $this->validate();
        $party = new PartyModel();
        $this->fillPartyModel($party);
        $this->storeLogo($party);
        $party->save();

        session()->flash('message', 'Party successfully created.');

        $this->form = [];
    }

    public function update(): void
    {
        // $this->validate();
        $party = PartyModel::withTrashed()->find($this->id);
        $this->fillPartyModel($party);
        $this->storeLogo($party);
        $party->save();

        session()->flash('message', 'Party successfully updated.');
    }

    public function delete($id)
    {
        $party = PartyModel::find($id);
        $party->delete();

        session()->flash('message', 'Party successfully deleted.');

        return redirect()->back();
    }

    public function restore($id)
    {
        $party = PartyModel::withTrashed()->find($id);
        $party->restore();

        session()->flash('message', 'Party successfully restored.');

        return redirect()->back();
    }

    public function assignCandidate($partyId): void
    {
        $party = PartyModel::withTrashed()->find($this->id);
        $candidate = Candidate::find($partyId);

        $party->candidates()->attach($candidate);

        session()->flash('message', 'Candidate successfully assigned.');
    }

    public function removeCandidate($partyId): void
    {
        $party = PartyModel::withTrashed()->find($this->id);
        $party->candidates()->detach($partyId);

        session()->flash('message', 'Candidate successfully removed.');
    }

    public function createAndAssignCandidate(): void
    {
        $candidate = new Candidate();
        $candidate->name = $this->form['candidate']['name'];
        $candidate->birthdate = $this->form['candidate']['birthdate'];
        $candidate->bio = $this->form['candidate']['bio'];

        $this->storeCandidatePhoto($candidate);
        $candidate->save();

        $party = PartyModel::withTrashed()->find($this->id);
        $party->candidates()->attach($candidate);

        session()->flash('message', 'Candidate successfully created and assigned.');
    }

    public function forceDelete($id)
    {
        $party = PartyModel::withTrashed()->find($id);
        $party->forceDelete();

        session()->flash('message', 'Party successfully deleted.');

        return redirect()->back();
    }

    private function validateId(): void
    {
        if ($this->id && !PartyModel::withTrashed()->find($this->id)) {
            $this->id = '';
        }
    }

    private function updatePerPageSession(): void
    {
        if ($this->perPage) {
            session()->remove('perPage');
            session()->put('perPage', $this->perPage);
        }
    }

    private function getPartyQuery()
    {
        $query = PartyModel::where(function ($query) {
            $query->where('name', 'like', '%' . $this->search . '%');
        })->orderBy('id', 'desc');

        if ($this->showDeleted) {
            $query->onlyTrashed();
        } else {
            $query->withoutTrashed();
        }

        return $query;
    }

    private function getCandidates()
    {
        $candidatesQuery = Candidate::where('name', 'like', "%{$this->searchUnassignedCandidates}%");

        return $candidatesQuery
            ->whereNotIn('id', function ($query) {
                $query->select('candidate_id')
                    ->from('party_candidate')
                    ->where('party_id', $this->id);
            })
            ->get();
    }

    private function fillPartyModel($party): void
    {
        foreach ($this->form as $key => $value) {
            $party->$key = $value;
        }
    }

    private function storeLogo($party): void
    {
        if (isset($this->form['logo'])) {
            $party->logo = $this->form['logo']->store('logos', 'public');
            $party->logo = '/storage/' . $party->logo;
        }
    }

    private function storeCandidatePhoto($candidate): void
    {
        if (isset($this->form['candidate']['image'])) {
            $candidate->image = $this->form['candidate']['image']->store('photos', 'public');
            $candidate->image = '/storage/' . $candidate->image;
        } else{
            dd('no photo');
        }
    }
}
