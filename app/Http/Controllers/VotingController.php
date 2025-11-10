<?php

namespace App\Http\Controllers;

use App\Models\Voting;
use App\Models\Vote;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;

class VotingController extends Controller
{
    public function index(Request $request): Response
    {
        $votings = Voting::query()
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return Inertia::render('voting/Index', [
            'items' => $votings,
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('voting/Create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nis' => ['required', 'string', 'max:50', 'unique:votings,nip'],
            'nama' => ['required', 'string', 'max:255'],
            'kelas' => ['required', 'string', 'max:100'],
            'jurusan' => ['required', 'string', 'max:100'],
            'visi' => ['required', 'string'],
            'misi' => ['required', 'string'],
            'foto' => ['nullable', 'image', 'mimes:png,jpg,jpeg,webp', 'max:4096'],
        ]);

        $data = [
            'nip' => $validated['nis'],
            'nama' => $validated['nama'],
            'kelas' => $validated['kelas'],
            'jurusan' => $validated['jurusan'],
            'visi' => $validated['visi'],
            'misi' => $validated['misi'],
        ];

        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('voting', 'public');
        }

        Voting::create($data);

        return to_route('voting.index')->with('status', 'created');
    }

    public function edit(Voting $voting): Response
    {
        return Inertia::render('voting/Edit', [
            'item' => $voting,
        ]);
    }

    public function update(Request $request, Voting $voting): RedirectResponse
    {
        $validated = $request->validate([
            'nis' => ['required', 'string', 'max:50', 'unique:votings,nip,' . $voting->id],
            'nama' => ['required', 'string', 'max:255'],
            'kelas' => ['required', 'string', 'max:100'],
            'jurusan' => ['required', 'string', 'max:100'],
            'visi' => ['required', 'string'],
            'misi' => ['required', 'string'],
            'foto' => ['nullable', 'image', 'mimes:png,jpg,jpeg,webp', 'max:4096'],
        ]);

        $data = [
            'nip' => $validated['nis'],
            'nama' => $validated['nama'],
            'kelas' => $validated['kelas'],
            'jurusan' => $validated['jurusan'],
            'visi' => $validated['visi'],
            'misi' => $validated['misi'],
        ];

        if ($request->hasFile('foto')) {
            if ($voting->foto && Storage::disk('public')->exists($voting->foto)) {
                Storage::disk('public')->delete($voting->foto);
            }
            $data['foto'] = $request->file('foto')->store('voting', 'public');
        }

        $voting->update($data);

        return to_route('voting.index')->with('status', 'updated');
    }

    public function destroy(Voting $voting): RedirectResponse
    {
        if ($voting->foto && Storage::disk('public')->exists($voting->foto)) {
            Storage::disk('public')->delete($voting->foto);
        }

        $voting->delete();

        return to_route('voting.index')->with('status', 'deleted');
    }

    public function candidates(): Response
    {
        $items = Voting::query()->orderBy('nama')->get();
        $hasVoted = false;
        if (Auth::check()) {
            $hasVoted = Vote::query()->where('user_id', Auth::id())->exists();
        }

        return Inertia::render('candidates/Index', [
            'items' => $items,
            'hasVoted' => $hasVoted,
        ]);
    }

    public function vote(Voting $voting): RedirectResponse
    {
        $userId = Auth::id();
        if (!$userId) {
            return redirect()->back()->with('error', 'Unauthenticated');
        }

        // Cek apakah user sudah pernah vote
        $already = Vote::query()->where('user_id', $userId)->exists();
        if ($already) {
            return redirect()->back()->with('status', 'already_voted');
        }

        Vote::create([
            'user_id' => $userId,
            'voting_id' => $voting->id,
        ]);

        return redirect()->back()->with('status', 'voted');
    }
}