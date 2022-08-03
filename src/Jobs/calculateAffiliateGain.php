<?php

namespace Properos\Commerce\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Properos\Users\Models\User;
use Properos\Users\Models\UserLedger;

class calculateAffiliateGain implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $data;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if (\Schema::hasColumn('users', 'affiliate_id')) {
            if (isset($this->data['user_id']) && $this->data['user_id'] > 0 && isset($this->data['amount']) && $this->data['amount'] > 0) {
                $user = User::where([['id',$this->data['user_id']],['affiliate_id','>',0]])->first();
                if ($user) {
                    $affiliate = User::where('id', $user->affiliate_id)->first();
                    if ($affiliate) {
                        $ledger = new UserLedger();
                        $ledger->user_id = $affiliate->id;
                        if (isset($this->data['operation'])) {
                            if ($this->data['operation'] == 'charge') {
                                $ledger->type = 'income';
                                $aff_amount = $this->data['amount'] * $affiliate->percent / 100;
                                $ledger->debit = $aff_amount;
                            } else {
                                $ledger->type = 'withdrawal';
                                $aff_amount = - ($this->data['amount'] * $affiliate->percent / 100);
                                $ledger->credit = $this->data['amount'] * $affiliate->percent / 100;
                            }
                        }
                        $ledger->sbalance = $affiliate->pending_balance;
                        $affiliate->pending_balance = $affiliate->pending_balance + $aff_amount;
                        $ledger->ebalance = $ledger->sbalance + $aff_amount;
                        $ledger->status = 'pending';
                        $affiliate->save();
                        $ledger->save();
                    }
                }
            }
        }
    }
}
