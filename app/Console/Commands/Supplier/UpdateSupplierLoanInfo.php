<?php

namespace App\Console\Commands\Supplier;

use App\Models\Finance\Loan;
use App\Models\Supplier\Supplier;
use Illuminate\Console\Command;

class UpdateSupplierLoanInfo extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'supplier:update-loan-info {supplier_id? : 供应商ID}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '供应商 ：更新供应商的最高最低贷款额度';

    /**
     * Create a new command instance.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     */
    public function handle()
    {
        $supplierId = $this->argument('supplier_id');

        $query = Supplier::query();

        if($supplierId) {
            $query->where('id', $supplierId);
        }

        $suppliers = $query->get();
        $suppliers->each(function ($supplier) {
            $loanInfo = Loan::getSupplierAmount($supplier);
            $supplier->loan_info = $loanInfo;
            $supplier->save();
        });
    }
}
