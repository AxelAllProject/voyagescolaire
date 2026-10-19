<x-layouts.app title="Comptes">
    <x-slot:header>
        <x-page-header
            title="Comptes"
            subtitle="Validez les inscriptions et gérez les droits du personnel."
            :breadcrumbs="['Comptes' => null]"
        />
    </x-slot:header>

    {{-- Inscriptions en attente --}}
    <section class="section-card p-4 p-md-5 mb-4" data-reveal>
        <div class="d-flex align-items-center gap-3 mb-4">
            <div class="section-icon"><i class="bi bi-hourglass-split"></i></div>
            <h2 class="h5 fw-bold mb-0">Inscriptions en attente</h2>
            <span class="badge rounded-pill {{ $enAttente->isEmpty() ? 'text-bg-light border' : 'text-bg-warning' }} ms-auto">{{ $enAttente->count() }}</span>
        </div>

        @forelse ($enAttente as $compte)
            <div class="d-flex flex-wrap align-items-center gap-3 py-3 border-top" data-reveal>
                <div class="vs-avatar">{{ \Illuminate\Support\Str::of($compte->name)->explode(' ')->map(fn ($mot) => \Illuminate\Support\Str::substr($mot, 0, 1))->take(2)->implode('') }}</div>
                <div class="flex-grow-1">
                    <div class="fw-semibold text-dark">{{ $compte->name }}</div>
                    <div class="small text-secondary">{{ $compte->email }} · inscrit {{ $compte->created_at->diffForHumans() }}</div>
                </div>
                <form method="POST" action="{{ route('comptes.valider', $compte) }}">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="btn btn-success btn-sm"><i class="bi bi-check-lg me-1"></i> Valider</button>
                </form>
                <button type="button" class="btn btn-outline-danger btn-sm" data-bs-toggle="modal" data-bs-target="#supprimer-compte-{{ $compte->id }}">
                    <i class="bi bi-x-lg me-1"></i> Refuser
                </button>
            </div>
            <x-confirm-delete
                :id="'supprimer-compte-'.$compte->id"
                :action="route('comptes.destroy', $compte)"
                title="Refuser cette inscription ?"
                :message="'Le compte de '.$compte->name.' ('.$compte->email.') sera supprimé.'"
            />
        @empty
            <p class="text-secondary mb-0"><i class="bi bi-check2-circle text-success me-1"></i> Aucune inscription en attente.</p>
        @endforelse
    </section>

    {{-- Comptes validés --}}
    <section class="section-card overflow-hidden" data-reveal>
        <div class="d-flex align-items-center gap-3 p-4 pb-3">
            <div class="section-icon"><i class="bi bi-people"></i></div>
            <h2 class="h5 fw-bold mb-0">Comptes actifs</h2>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">Nom</th>
                        <th>Email</th>
                        <th>Rôle</th>
                        <th class="text-end pe-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($valides as $compte)
                        <tr>
                            <td class="ps-4 fw-semibold">
                                {{ $compte->name }}
                                @if ($compte->is(auth()->user()))
                                    <span class="badge rounded-pill text-bg-light border ms-1">Vous</span>
                                @endif
                            </td>
                            <td class="text-secondary">{{ $compte->email }}</td>
                            <td>
                                @can('update', $compte)
                                    <form method="POST" action="{{ route('comptes.update', $compte) }}" class="d-flex gap-2">
                                        @csrf
                                        @method('PUT')
                                        <select name="role" class="form-select form-select-sm w-auto" aria-label="Rôle de {{ $compte->name }}" onchange="this.form.requestSubmit()">
                                            @foreach (App\Enums\Role::cases() as $role)
                                                <option value="{{ $role->value }}" @selected($compte->role === $role)>{{ $role->libelle() }}</option>
                                            @endforeach
                                        </select>
                                        <noscript><button type="submit" class="btn btn-sm btn-outline-secondary">OK</button></noscript>
                                    </form>
                                @else
                                    {{ $compte->role->libelle() }}
                                @endcan
                            </td>
                            <td class="text-end pe-4">
                                @can('delete', $compte)
                                    <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#supprimer-compte-{{ $compte->id }}" aria-label="Supprimer le compte de {{ $compte->name }}">
                                        <i class="bi bi-trash3"></i>
                                    </button>
                                @endcan
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>

    @foreach ($valides as $compte)
        @can('delete', $compte)
            <x-confirm-delete
                :id="'supprimer-compte-'.$compte->id"
                :action="route('comptes.destroy', $compte)"
                title="Supprimer ce compte ?"
                :message="'Le compte de '.$compte->name.' sera supprimé et ses jetons d\'API révoqués.'"
            />
        @endcan
    @endforeach
</x-layouts.app>
