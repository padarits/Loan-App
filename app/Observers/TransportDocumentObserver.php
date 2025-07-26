<?php

namespace App\Observers;

use App\Models\TransportDocument;
use Carbon\Carbon;

class TransportDocumentObserver //tiek registrēts app/Providers/AppServiceProvider.php
{
    /**
     * Handle events after all transactions are committed.
     *
     * @var bool
     */
    // public $afterCommit = true; //Nevajag slēgt, jo pēc noklusējuma ir false

     /**
     * Handle the TransportDocument "saving" event.
     *
     * @param  \App\Models\TransportDocument  $document
     * @return void
     */
    public function saving(TransportDocument $document)
    {
        // Ja `document_date` ir definēts, tad pārvēršam to uz string formātu "dd.mm.yyyy"
        //if ($document->document_date) {
            // $document->document_date_str = Carbon::parse($document->document_date)->format('d.m.Y');
        //}
    }
    /**
     * Handle the TransportDocument "created" event.
     */
    public function created(TransportDocument $transportDocument): void
    {
        //
    }

    /**
     * Handle the TransportDocument "updated" event.
     */
    public function updated(TransportDocument $transportDocument): void
    {
        //
    }

    /**
     * Handle the TransportDocument "deleted" event.
     */
    public function deleted(TransportDocument $transportDocument): void
    {
        //
    }

    /**
     * Handle the TransportDocument "restored" event.
     */
    public function restored(TransportDocument $transportDocument): void
    {
        //
    }

    /**
     * Handle the TransportDocument "force deleted" event.
     */
    public function forceDeleted(TransportDocument $transportDocument): void
    {
        //
    }
}
