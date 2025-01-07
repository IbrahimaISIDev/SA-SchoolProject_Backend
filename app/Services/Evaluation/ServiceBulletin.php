<?php
// app/Services/Evaluation/ServiceBulletin.php
namespace App\Services\Evaluation;

use Illuminate\Database\Eloquent\Collection;
use App\Repositories\Interfaces\IBulletinRepository;
use App\Repositories\Interfaces\INoteRepository;
use App\Models\Utilisateurs\Etudiant;
use App\Models\Academique\Semestre;
use App\Models\Evaluation\Bulletin;
use App\Services\Interfaces\IBulletinService;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class ServiceBulletin implements IBulletinService
{

    private $bulletinRepository;
    private $noteRepository;

    public function __construct(
        IBulletinRepository $bulletinRepository,
        INoteRepository $noteRepository
    ) {
        $this->bulletinRepository = $bulletinRepository;
        $this->noteRepository = $noteRepository;
    }

    public function genererBulletin(
        Etudiant $etudiant,
        Semestre $semestre
    ): Bulletin {
        if (!$this->peutGenererBulletin($etudiant, $semestre)) {
            throw new \Exception('Impossible de générer le bulletin : notes manquantes ou incomplètes');
        }

        // Récupérer toutes les notes de l'étudiant pour le semestre
        $notes = $this->noteRepository->getNotesByEtudiant($etudiant->id, $semestre->id);

        // Calculer les moyennes par module
        $moyennesModules = $this->calculerMoyennesModules($notes);

        // Calculer la moyenne générale
        $moyenneGenerale = $this->calculerMoyenneGenerale($moyennesModules);

        // Déterminer le statut de validation
        $estValide = $moyenneGenerale >= 10;

        // Créer le bulletin
        return $this->bulletinRepository->create([
            'etudiant_id' => $etudiant->id,
            'semestre_id' => $semestre->id,
            'moyennes' => $moyennesModules,
            'moyenne_generale' => $moyenneGenerale,
            'date_generation' => now(),
            'est_valide' => $estValide,
            'observations' => $this->genererObservations($moyennesModules, $moyenneGenerale)
        ]);
    }
    private function calculerMoyennesModules(Collection $notes): array
    {
        return $notes->groupBy('evaluation.module_id')
            ->map(function ($moduleNotes) {
                $moyenne = $moduleNotes->sum(function ($note) {
                    return $note->valeur * $note->evaluation->coefficient;
                }) / $moduleNotes->sum('evaluation.coefficient');

                return [
                    'module_id' => $moduleNotes->first()->evaluation->module_id,
                    'module_nom' => $moduleNotes->first()->evaluation->module->nom,
                    'moyenne' => round($moyenne, 2),
                    'coefficient' => $moduleNotes->first()->evaluation->module->coefficient,
                    'est_valide' => $moyenne >= 10,
                    'notes' => $moduleNotes->map(function ($note) {
                        return [
                            'valeur' => $note->valeur,
                            'coefficient' => $note->evaluation->coefficient,
                            'type' => $note->evaluation->type,
                            'date' => $note->date_saisie
                        ];
                    })
                ];
            })->toArray();
    }
    // private function calculerMoyenneGenerale(array $moyennesModules): float
    // {
    //     $total = 0;
    //     $coefficientsSum = 0;

    //     foreach ($moyennesModules as $moduleData) {
    //         $total += $moduleData['moyenne'] * $moduleData['coefficient'];
    //         $coefficientsSum += $moduleData['coefficient'];
    //     }

    //     return $coefficientsSum > 0 ? round($total / $coefficientsSum, 2) : 0;
    // }


    public function calculerMoyenneGenerale(int $etudiantId, int $semestreId): float
    {
        $notes = $this->noteRepository->getNotesByEtudiant($etudiantId, $semestreId);

        if ($notes->isEmpty()) {
            return 0;
        }

        $moyennesModules = $this->calculerMoyennesModules($notes);
        return $this->calculerMoyenneGenerale($moyennesModules);
    }

    private function genererObservations(array $moyennesModules, float $moyenneGenerale): array
    {
        $observations = [];

        foreach ($moyennesModules as $module) {
            if (!$module['est_valide']) {
                $observations[] = "Module {$module['module_nom']} non validé (moyenne: {$module['moyenne']})";
            }
        }

        if ($moyenneGenerale >= 10) {
            $observations[] = "Semestre validé avec une moyenne de {$moyenneGenerale}";
        } else {
            $observations[] = "Semestre non validé - moyenne insuffisante ({$moyenneGenerale})";
        }

        return $observations;
    }

    public function exporterPDF(Bulletin $bulletin): string
    {
        $pdf = PDF::loadView('bulletins.pdf', [
            'bulletin' => $bulletin,
            'etudiant' => $bulletin->etudiant,
            'semestre' => $bulletin->semestre,
            'date_generation' => Carbon::parse($bulletin->date_generation)->format('d/m/Y')
        ]);

        $filename = "bulletin_{$bulletin->etudiant->id}_{$bulletin->semestre->id}.pdf";
        $pdf->save(storage_path("app/bulletins/{$filename}"));

        return $filename;
    }

    public function exporterExcel(Bulletin $bulletin): string
    {
        $filename = "bulletin_{$bulletin->etudiant->id}_{$bulletin->semestre->id}.xlsx";

        Excel::create($filename, function ($excel) use ($bulletin) {
            $excel->sheet('Bulletin', function ($sheet) use ($bulletin) {
                $sheet->loadView('bulletins.excel', [
                    'bulletin' => $bulletin,
                    'etudiant' => $bulletin->etudiant,
                    'semestre' => $bulletin->semestre
                ]);
            });
        })->store('xlsx', storage_path('app/bulletins'));

        return $filename;
    }

    public function getBulletinsEtudiant(Etudiant $etudiant): Collection
    {
        return $this->bulletinRepository->where('etudiant_id', $etudiant->id)
            ->orderBy('date_generation', 'desc')
            ->get();
    }

    public function getBulletinSemestre(Etudiant $etudiant, Semestre $semestre): ?Bulletin
    {
        return $this->bulletinRepository->where([
            'etudiant_id' => $etudiant->id,
            'semestre_id' => $semestre->id
        ])->latest('date_generation')->first();
    }

    public function peutGenererBulletin(Etudiant $etudiant, Semestre $semestre): bool
    {
        $notes = $this->noteRepository->getNotesByEtudiant($etudiant->id, $semestre->id);

        if ($notes->isEmpty()) {
            return false;
        }

        // Vérifier que chaque module a au moins une note
        $modulesAvecNotes = $notes->pluck('evaluation.module_id')->unique();
        $modulesRequis = $semestre->modules->pluck('id');

        return $modulesRequis->diff($modulesAvecNotes)->isEmpty();
    }

    public function validerBulletin(Bulletin $bulletin): bool
    {
        if ($bulletin->est_valide) {
            return true;
        }

        $moyenneGenerale = $bulletin->moyenne_generale;
        $validation = $moyenneGenerale >= 10;

        return $this->bulletinRepository->update($bulletin->id, [
            'est_valide' => $validation,
            'date_validation' => $validation ? now() : null
        ]);
    }
    public function recupererHistorique(int $etudiantId): Collection
    {
        return $this->bulletinRepository->getBulletinsEtudiant($etudiantId);
    }
    public function publier(int $classeId, int $semestreId): bool
    {
        try {
            // Récupérer tous les étudiants de la classe
            $etudiants = Etudiant::where('classe_id', $classeId)->get();

            foreach ($etudiants as $etudiant) {
                // Vérifier si on peut générer le bulletin
                $semestre = Semestre::findOrFail($semestreId);

                if ($this->peutGenererBulletin($etudiant, $semestre)) {
                    // Générer le bulletin s'il n'existe pas déjà
                    if (!$this->getBulletinSemestre($etudiant, $semestre)) {
                        $bulletin = $this->genererBulletin($etudiant, $semestre);

                        // Valider automatiquement le bulletin
                        $this->validerBulletin($bulletin);
                    }
                }
            }

            return true;
        } catch (\Exception $e) {
            Log::error('Erreur lors de la publication des bulletins: ' . $e->getMessage());
            return false;
        }
    }
}
