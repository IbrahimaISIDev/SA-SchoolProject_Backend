<?php
namespace App\Services\Planification;

use App\Repositories\Interfaces\IAnneeRepository;
use App\Services\Interfaces\IServiceAnnee;
use App\Models\AnneeAcademique;

class ServiceJustification implements IJustificationService {
    private $justificationRepository;
    private $storageService;

    public function __construct(
        IJustificationRepository $justificationRepository,
        StorageService $storageService
    ) {
        $this->justificationRepository = $justificationRepository;
        $this->storageService = $storageService;
    }

    public function soumettre(array $data, ?UploadedFile $fichier = null): Justification {
        if ($fichier) {
            $data['piece_jointe'] = $this->storageService->stockerJustificatif($fichier);
        }

        return $this->justificationRepository->create($data);
    }

    public function traiter(int $justificationId, string $decision, ?string $commentaire = null): bool {
        $justification = $this->justificationRepository->findOrFail($justificationId);
        
        $justification->update([
            'statut' => $decision,
            'commentaire' => $commentaire,
            'date_traitement' => now()
        ]);

        return true;
    }
}