<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Occasion;
use App\Models\Task;
use App\Models\WorkshopAppointment;

class DashboardController extends Controller
{
    public function index()
    {
        // Occasions die NIET verkocht zijn (verkocht = "(VERKOCHT)" in model-veld)
        $activeQuery = Occasion::query()
            ->where('binnenkort', false)
            ->where(function ($q) {
                $q->whereNull('model')->orWhere('model', 'NOT LIKE', '%(VERKOCHT)%');
            });

        $activeCount       = (clone $activeQuery)->count();
        $stockValue        = (clone $activeQuery)->sum('prijs');
        $stockBuyValue     = (clone $activeQuery)->whereNotNull('inkoop_prijs')->sum('inkoop_prijs');
        $missingInkoop     = (clone $activeQuery)->whereNull('inkoop_prijs')->count();
        $binnenkortCount   = Occasion::where('binnenkort', true)->count();

        // Marge: alleen waar zowel inkoop als verkoop is ingevuld
        $marginRows = (clone $activeQuery)
            ->whereNotNull('inkoop_prijs')
            ->whereNotNull('prijs')
            ->where('prijs', '>', 0)
            ->get(['prijs', 'inkoop_prijs']);

        $totalMargin = $marginRows->sum(fn ($o) => (float) $o->prijs - (float) $o->inkoop_prijs);
        $avgMargin   = $marginRows->count() > 0 ? $totalMargin / $marginRows->count() : 0;
        $avgMarginPct = 0;
        if ($marginRows->count() > 0) {
            $pcts = $marginRows
                ->filter(fn ($o) => (float) $o->inkoop_prijs > 0)
                ->map(fn ($o) => (((float) $o->prijs - (float) $o->inkoop_prijs) / (float) $o->inkoop_prijs) * 100);
            $avgMarginPct = $pcts->count() > 0 ? $pcts->avg() : 0;
        }

        // Langst staande auto's (60+ dagen, niet verkocht)
        $oldStock = (clone $activeQuery)
            ->where('created_at', '<', now()->subDays(60))
            ->orderBy('created_at')
            ->limit(5)
            ->get();

        // Tasks
        $tasksOpen    = Task::whereNull('completed_at')->count();
        $tasksToday   = Task::whereNull('completed_at')->whereDate('due_at', today())->count();
        $tasksOverdue = Task::whereNull('completed_at')->whereNotNull('due_at')->where('due_at', '<', now())->count();
        $upcomingTasks = Task::with('occasion')
            ->whereNull('completed_at')
            ->orderByRaw('CASE WHEN due_at IS NULL THEN 1 ELSE 0 END')
            ->orderBy('due_at')
            ->limit(6)
            ->get();

        // Werkplaats-afspraken vandaag (mocht het model bestaan)
        $workshopToday = 0;
        if (class_exists(WorkshopAppointment::class)) {
            try {
                $workshopToday = WorkshopAppointment::whereDate('created_at', today())->count();
            } catch (\Throwable) {
                $workshopToday = 0;
            }
        }

        // Recent toegevoegd (laatste 5)
        $recentlyAdded = Occasion::orderByDesc('created_at')->limit(5)->get();

        // ============ VERKOOP-TRACKING ============
        $now = now();
        $thirtyDaysAgo = $now->copy()->subDays(30);

        $sold30d = Occasion::whereNotNull('verkocht_datum')
            ->where('verkocht_datum', '>=', $thirtyDaysAgo->toDateString())
            ->get(['verkoopprijs', 'inkoop_prijs', 'verkocht_datum', 'created_at']);

        $omzet30d  = $sold30d->sum('verkoopprijs');
        $verkocht30dCount = $sold30d->count();
        $margeRealised30d = $sold30d->reduce(function ($carry, $o) {
            if ($o->verkoopprijs === null || $o->inkoop_prijs === null) return $carry;
            return $carry + ((float) $o->verkoopprijs - (float) $o->inkoop_prijs);
        }, 0);

        // Gemiddelde tijd in voorraad bij verkoop (laatste 30 dagen)
        $avgDaysInStock = null;
        $withDates = $sold30d->filter(fn ($o) => $o->verkocht_datum && $o->created_at);
        if ($withDates->isNotEmpty()) {
            $totalDays = $withDates->sum(fn ($o) => $o->created_at->diffInDays($o->verkocht_datum));
            $avgDaysInStock = round($totalDays / $withDates->count());
        }

        // Bar-chart: omzet per week, laatste 12 weken
        $weeks = [];
        for ($i = 11; $i >= 0; $i--) {
            $weekStart = $now->copy()->startOfWeek()->subWeeks($i);
            $weekEnd   = $weekStart->copy()->endOfWeek();
            $rows = Occasion::whereNotNull('verkocht_datum')
                ->whereBetween('verkocht_datum', [$weekStart->toDateString(), $weekEnd->toDateString()])
                ->get(['verkoopprijs', 'inkoop_prijs']);
            $weeks[] = [
                'label'  => $weekStart->format('d M'),
                'short'  => 'W' . $weekStart->isoWeek(),
                'omzet'  => (float) $rows->sum('verkoopprijs'),
                'marge'  => (float) $rows->reduce(function ($c, $o) {
                    if ($o->verkoopprijs === null || $o->inkoop_prijs === null) return $c;
                    return $c + ((float) $o->verkoopprijs - (float) $o->inkoop_prijs);
                }, 0),
                'count'  => $rows->count(),
            ];
        }
        $weeksMaxOmzet = collect($weeks)->max('omzet') ?: 1;

        // Recent verkocht
        $recentlySold = Occasion::whereNotNull('verkocht_datum')
            ->orderByDesc('verkocht_datum')
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact(
            'activeCount', 'stockValue', 'stockBuyValue', 'missingInkoop', 'binnenkortCount',
            'totalMargin', 'avgMargin', 'avgMarginPct', 'marginRows',
            'oldStock', 'tasksOpen', 'tasksToday', 'tasksOverdue', 'upcomingTasks',
            'workshopToday', 'recentlyAdded',
            'omzet30d', 'verkocht30dCount', 'margeRealised30d', 'avgDaysInStock',
            'weeks', 'weeksMaxOmzet', 'recentlySold'
        ));
    }
}
