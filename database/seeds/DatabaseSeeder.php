<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	$this->call(PlanAccountTypeSeeder::class);
        $this->call(AddonSeeder::class);
        $this->call(PersonIdSeeder::class);
        $this->call(BusinessAddressTypeSeeder::class);
        $this->call(BusinessNatureSeeder::class);
        $this->call(BusinessProfessionalismSeeder::class);
        $this->call(LicenseTypeSeeder::class);
        $this->call(BloodGroupSeeder::class);
        $this->call(GenderSeeder::class);
        $this->call(PeopleTitleSeeder::class);
        $this->call(TermPeriodSeeder::class);
        $this->call(ModuleSeeder::class);
        $this->call(PaymentModeSeeder::class);
        $this->call(PersonAddressTypeSeeder::class);
        $this->call(PersonAssetTypeSeeder::class);
        $this->call(SubscriptionTypeSeeder::class);
        $this->call(SubscriptionPlanSeeder::class);
        $this->call(PackageSeeder::class);
        $this->call(RecordSeeder::class);
        $this->call(PermissionTableSeeder::class);
        $this->call(MaritalStatusSeeder::class);
        $this->call(ReferenceVoucherSeeder::class);

        $this->call(PrintTemplateTypeSeeder::class);

        $this->call(BankAccountTypeSeeder::class);
        $this->call(PersonTypeSeeder::class);
        $this->call(AccountHeadSeeder::class);
        $this->call(AccountLedgerTypeSeeder::class);
        $this->call(AccountVoucherSeparatorSeeder::class);
        $this->call(AccountVoucherTypeSeeder::class);

        $this->call(PersonalTransactionTypeSeeder::class);

        $this->call(WeekdaySeeder::class); 
        $this->call(HrmStaffTypeSeeder::class);
        $this->call(PayHeadTypeSeeder::class);
        $this->call(GlobalItemCategoryTypeSeeder::class);
        $this->call(GlobalItemCategorySeeder::class);
        $this->call(DiscountTypeSeeder::class);
        $this->call(TaxTypeSeeder::class);
        
        $this->call(FieldTypeSeeder::class);
        $this->call(WFMTaskActionSeeder::class);
        $this->call(WFMTaskStatusSeeder::class);
        $this->call(HrmRecruitmentSeeder::class);
        $this->call(HrmDocumentTypeSeeder::class);
        $this->call(SubscriptionAddonSeeder::class);
    }
}
