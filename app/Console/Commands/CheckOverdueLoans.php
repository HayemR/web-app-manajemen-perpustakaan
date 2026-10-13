<?php

namespace App\Console\Commands;

use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CheckOverdueLoans extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'library:check-overdue';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check and update active book loans that have exceeded their due date to overdue status and calculate fines';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $now = Carbon::now();
        $fineRate = (float) config('library.fine_per_day', 1000);

        $overdueLoans = Transaction::whereIn('status', [Transaction::STATUS_ACTIVE, Transaction::STATUS_OVERDUE])
            ->whereNotNull('due_date')
            ->where('due_date', '<', $now)
            ->with(['user', 'book'])
            ->get();

        if ($overdueLoans->isEmpty()) {
            $this->info('Tidak ada peminjaman aktif yang terlambat.');
            return Command::SUCCESS;
        }

        $this->info("Menemukan {$overdueLoans->count()} peminjaman yang terlambat/jatuh tempo.");

        $updatedCount = 0;
        foreach ($overdueLoans as $transaction) {
            $daysLate = (int) $transaction->due_date->copy()->startOfDay()->diffInDays($now->copy()->startOfDay());
            if ($daysLate < 1) {
                $daysLate = 1;
            }

            $fineAmount = $daysLate * $fineRate;

            $transaction->update([
                'status' => Transaction::STATUS_OVERDUE,
                'fine_amount' => $fineAmount,
            ]);

            $this->line(sprintf(
                ' - Transaksi #%s: Peminjam "%s", Buku "%s", Terlambat %d hari, Denda: Rp %s',
                $transaction->id,
                $transaction->user?->name ?? 'N/A',
                $transaction->book?->title ?? 'N/A',
                $daysLate,
                number_format($fineAmount, 0, ',', '.')
            ));

            $updatedCount++;
        }

        $this->info("Berhasil memperbarui {$updatedCount} peminjaman menjadi status overdue.");
        return Command::SUCCESS;
    }
}
