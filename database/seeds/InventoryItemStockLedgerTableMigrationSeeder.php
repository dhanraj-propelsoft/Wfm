<?php
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\InventoryItemStock;
use App\InventoryItemStockLedger;
use App\Transaction;
use App\AccountVoucher;
use App\TransactionItem;

class InventoryItemStockLedgerTableMigrationSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $itemStocks = InventoryItemStock::all();

        Schema::disableForeignKeyConstraints();
        collect($itemStocks)->map(function ($itemStock) {

            $data = $itemStock->data;
            $invItemStock_ID = $itemStock->id;

            $stock_datas = json_decode($data);

            Log::info('InventoryItemStockLedgerTableMigrationSeeder->run :- $stock_datas data' . json_encode($stock_datas));
            collect($stock_datas)->map(function ($stock_data) use ($invItemStock_ID) {
                // Log::info('InventoryItemStockLedgerTableMigrationSeeder->run :- $stock_data -- '.json_encode($stock_data));
                // Log::info('InventoryItemStockLedgerTableMigrationSeeder->run :- $stock_data --2 '.$stock_data->transaction_id);

                $account_entry_id = null;
                $order_no = null;
                $date = null;

                if (isset($stock_data->transaction_id)) {
                    Log::info('InventoryItemStockLedgerTableMigrationSeeder->run :- $$$stock_data->transaction_id ' . $stock_data->transaction_id);
                    $transaction = Transaction::find($stock_data->transaction_id);
                    if ($transaction) {
                        Log::info('InventoryItemStockLedgerTableMigrationSeeder->run :- FOUND TANSACTION ' . $transaction->id);
                        $account_entry_id = $transaction->entry_id;
                        $order_no = $transaction->order_no;
                        if ($transaction->date) {
                            $date = Carbon::parse($transaction->date)->format('Y-m-d H:i:s');
                        } else {
                            $date = (isset($stock_data->date)) ? $stock_data->date : null;
                        }

                        $accountVoucher = AccountVoucher::find($transaction->transaction_type_id);
                        if ($accountVoucher) {
                            $voucher_type = $accountVoucher->display_name;
                        } else {
                            $voucher_type = (isset($stock_data->voucher_type)) ? $stock_data->voucher_type : null;
                        }
                        
                        $item = TransactionItem::where('transaction_id', $transaction->id)
                        ->where('item_id', $invItemStock_ID)
                        ->first();
                        $batch_id = $item->batch_id;
                        
                    } else {
                        Log::info('InventoryItemStockLedgerTableMigrationSeeder->run :- NOT FOUND TANSACTION ' . $stock_data->transaction_id);
                        $account_entry_id = (isset($stock_data->entry_id)) ? $stock_data->entry_id : null;
                        $order_no = (isset($stock_data->order_no)) ? $stock_data->order_no : null;
                        $date = (isset($stock_data->date)) ? $stock_data->date : null;
                        $voucher_type = (isset($stock_data->voucher_type)) ? $stock_data->voucher_type : null;
                    }
                } else {
                    $account_entry_id = (isset($stock_data->entry_id)) ? $stock_data->entry_id : null;
                    $order_no = (isset($stock_data->order_no)) ? $stock_data->order_no : null;
                    $date = (isset($stock_data->date)) ? $stock_data->date : null;
                    $voucher_type = (isset($stock_data->voucher_type)) ? $stock_data->voucher_type : null;
                }

                $model = new InventoryItemStockLedger();
                $model->inventory_item_stock_id = $invItemStock_ID;
                $model->inventory_item_batch_id = (isset($batch_id) ? $batch_id : null);
                $model->transaction_id = (isset($stock_data->transaction_id)) ? $stock_data->transaction_id : null;
                $model->account_entry_id = $account_entry_id; // (isset($stock_data->entry_id)) ? $stock_data->entry_id : null;
                $model->voucher_type = $voucher_type; // (isset($stock_data->voucher_type)) ? $stock_data->voucher_type : null;
                $model->order_no = $order_no; // (isset($stock_data->order_no)) ? $stock_data->order_no : null;
                $model->quantity = (isset($stock_data->quantity)) ? $stock_data->quantity : null;
                $model->date = $date; // (isset($stock_data->date)) ? $stock_data->date : null;
                $model->in_stock = (isset($stock_data->in_stock)) ? $stock_data->in_stock : null;
                $model->purchase_price = (isset($stock_data->purchase_price)) ? $stock_data->purchase_price : null;
                $model->sale_price = (isset($stock_data->sale_price)) ? $stock_data->sale_price : null;
                $model->status = (isset($stock_data->status)) ? $stock_data->status : 0;

                $model->created_at = (Carbon::now());

                $model->save();

                // {"transaction_id":2535,"entry_id":null,"voucher_type":"Job Invoice Credit","order_no":"JI-2019-88","quantity":"1.00","date":"2020-01-07 15:28:49","in_stock":103,"purchase_price":"768.00","sale_price":"1280.00","status":1}
            });
        });
        Schema::enableForeignKeyConstraints();
    }
}
