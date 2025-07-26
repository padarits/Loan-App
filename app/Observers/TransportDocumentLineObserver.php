<?php

namespace App\Observers;

use App\Models\TransportDocumentLine;
use App\Models\TransportDocument;
use Illuminate\Support\Facades\DB;

class TransportDocumentLineObserver //tiek registrēts app/Providers/AppServiceProvider.php
{
    /**
     * Handle events after all transactions are committed.
     *
     * @var bool
     */
    //public $afterCommit = true; Nevajag slēgt, jo pēc noklusējuma ir false
    
    public function created(TransportDocumentLine $line)
    {
        $this->updateTotalSum($line);
    }

    public function updated(TransportDocumentLine $line)
    {
        $this->updateTotalSum($line);
    }

    public function deleted(TransportDocumentLine $line)
    {
        $this->updateTotalSum($line);
    }

    protected function updateTotalSum(TransportDocumentLine $line)
    {
        $document = TransportDocument::find($line->transport_document_id);
        $totalSum = $document->lines()->sum('total');

        // Saglabā summu kā virkni ar 2 decimāldaļām
        $document->update(['total_sum' => number_format($totalSum, 2, '.', '')]);
    }

}
