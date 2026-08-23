# Maelekezo kwa Copilot / Developer — Multi-Item Sales, COGS, Stock-In & Units Refactor

Faili hii inaelezea kazi kubwa iliyofanyika kwenye branch hii, kwa nini ilifanyika, na
hatua zinazotakiwa kabla ya ku-merge/push kwenye `main` GitHub. Soma yote kabla ya
kuanza kufanya chochote.

## Muktasari wa mabadiliko (nini kimefanyika na kwa nini)

Awali `Sale` ilikuwa "bidhaa moja kwa mauzo moja" — haikuweza kuuza cart ya bidhaa
nyingi kwa risiti moja, dashboard ilionyesha "Total Profit" iliyokuwa sawa na
"Total Sales" (bug — haikuwa na COGS kabisa), reports hazikuwa na date filter ya
kweli, na hapakuwa na njia ya kurekodi manunuzi/stock-in wala unit za kipimo
(kg/lita/dazani).

Kazi hii imerekebisha yote hayo:

1. **Multi-item Sale (Cart/POS)** — `Sale` sasa ni "order header", bidhaa zake
   ziko kwenye `sale_items` (jedwali jipya). Sale moja inaweza kuwa na bidhaa nyingi.
2. **COGS/Profit fix** — Dashboard na Reports sasa zinahesabu `COGS`, `Gross Profit`,
   na `Net Profit` kwa usahihi (badala ya `Total Sales - Total Expenses` tu).
3. **Invoice/Receipt** — `sales/show.blade.php` ni risiti inayoweza kuchapishwa
   (browser print, hakuna dependency ya PDF library).
4. **Date range filter** — reports (`daily`, `weekly`, `monthly`, `profit-loss`)
   sasa zina fomu za tarehe kwenye UI (awali backend ilikuwa nazo lakini UI haikuwa
   nazo kabisa).
5. **Stock-In / Purchases module** — jedwali mpya `purchases` + `purchase_items`,
   controller mpya `PurchaseController`, views mpya `resources/views/purchases/*`.
   Kurekodi stock-in kunaongeza `stock_quantity` ya bidhaa automatic na kubadilisha
   `purchase_price` kuwa bei mpya ya ununuzi.
6. **Units of Measure** — `products.unit` (kg, lita, kipande, dazani, pakiti, mita,
   roli). `stock_quantity` na `sale_items.quantity`/`purchase_items.quantity`
   zimebadilishwa kutoka `integer` kwenda `decimal(10,2)` ili kuruhusu kiasi kama
   `2.5 kg`.
7. **Bug fixes za ziada zilizogundulika njiani**: `app/Http/Controllers/Api/SaleController.php`,
   `Api/ReportController.php`, na `Api/DashboardController.php` zilikuwa dead code
   kabisa (zilitumia fields zisizokuwepo kwenye database — `total_amount`,
   `customer_id`, `$sale->products()`, n.k.). Zote zimeandikwa upya ziendane na
   schema halisi.

## Files kuu zilizoguswa

```
database/migrations/2025_08_25_000000_create_sale_items_table.php          [MPYA]
database/migrations/2025_08_25_000001_convert_sales_to_multi_item_orders.php [MPYA]
database/migrations/2025_08_25_000002_add_unit_of_measure_to_products.php  [MPYA]
database/migrations/2025_08_25_000004_create_purchases_table.php           [MPYA]
database/migrations/2025_08_25_000005_create_purchase_items_table.php      [MPYA]

app/Models/Sale.php                    [imeandikwa upya]
app/Models/SaleItem.php                [MPYA]
app/Models/Product.php                 [imerekebishwa]
app/Models/Purchase.php                [MPYA]
app/Models/PurchaseItem.php            [MPYA]

app/Http/Controllers/SaleController.php            [imeandikwa upya]
app/Http/Controllers/PurchaseController.php        [MPYA]
app/Http/Controllers/DashboardController.php       [imerekebishwa]
app/Http/Controllers/ReportController.php          [imeandikwa upya]
app/Http/Controllers/ProductController.php         [imerekebishwa - unit field]
app/Http/Controllers/AI/AiChatController.php        [imerekebishwa]
app/Http/Controllers/Api/SaleController.php         [imeandikwa upya]
app/Http/Controllers/Api/ReportController.php       [imeandikwa upya]
app/Http/Controllers/Api/DashboardController.php    [imeandikwa upya]

resources/views/sales/create.blade.php   [imeandikwa upya - cart UI]
resources/views/sales/show.blade.php     [imeandikwa upya - invoice/receipt]
resources/views/sales/index.blade.php    [imerekebishwa]
resources/views/sales/edit.blade.php     [imeandikwa upya - order-level fields tu]
resources/views/purchases/create.blade.php [MPYA]
resources/views/purchases/index.blade.php  [MPYA]
resources/views/purchases/show.blade.php   [MPYA]
resources/views/products/create.blade.php  [imerekebishwa - unit dropdown]
resources/views/products/edit.blade.php    [imerekebishwa - unit dropdown]
resources/views/products/show.blade.php    [imerekebishwa - saleItems]
resources/views/products/index.blade.php   [imerekebishwa - unit label]
resources/views/dashboard.blade.php        [imerekebishwa - COGS/Gross/Net cards]
resources/views/reports/daily.blade.php    [imerekebishwa - date filter + items]
resources/views/reports/weekly.blade.php   [imerekebishwa - date filter + items]
resources/views/reports/monthly.blade.php  [imerekebishwa - month filter]
resources/views/reports/profit-loss.blade.php [imerekebishwa - date range filter]
resources/views/layouts/navigation.blade.php [imerekebishwa - Purchases link]

routes/web.php                          [imerekebishwa - purchases routes]
database/seeders/RolePermissionSeeder.php [imerekebishwa - purchases permissions]
database/seeders/SampleDataSeeder.php     [imeandikwa upya]
composer.json                           [imerekebishwa - +doctrine/dbal]
```

## HATUA ZA LAZIMA KABLA YA KU-PUSH (fuata kwa mpangilio huu)

### 1. Install dependency mpya
```bash
composer update doctrine/dbal
```
Hii inahitajika kwa sababu migration za `2025_08_25_000002_add_unit_of_measure_to_products.php`
zinatumia `->change()` kubadilisha column iliyokuwepo tayari (`stock_quantity` kutoka
`integer` kwenda `decimal`). Bila `doctrine/dbal`, migration hii itashindwa na error.

### 2. Fanya backup ya database YA SASA kabla ya migrate
Kama kuna data ya majaribio ya mteja tayari kwenye DB (hasa `sales` table), chukua
backup kabla ya kuendesha migrations — hasa migration
`2025_08_25_000001_convert_sales_to_multi_item_orders.php` inayohamisha data ya
zamani ya `sales` kwenda `sale_items`.

```bash
# Mfano kwa SQLite (default ya app hii kwa sasa)
cp database/database.sqlite database/database.sqlite.backup-$(date +%Y%m%d)
```

### 3. Endesha migrations kwenye mazingira ya majaribio KWANZA (sio production moja kwa moja)
```bash
php artisan migrate
```
Fuatilia output kwa makini. Kama kuna error, ISHIMILIE — usiendelee kwenye
production mpaka imetatuliwa.

### 4. Regenerate permissions (Spatie inaweka cache)
```bash
php artisan permission:cache-reset
php artisan db:seed --class=RolePermissionSeeder
```
Hii inaongeza permissions mpya (`view_purchases`, `create_purchases`,
`delete_purchases`) bila kufuta users/data zilizopo (seeder inatumia `firstOrCreate`).

### 5. Jaribu manually (checklist)
- [ ] Fungua `/sales/create` — ongeza bidhaa 2-3 kwenye cart, hifadhi, hakikisha
      invoice inaonekana sahihi kwenye `/sales/{id}`
- [ ] Bonyeza "Chapisha Risiti" — hakikisha print preview inaonekana vizuri
- [ ] Fungua `/dashboard` — hakikisha COGS, Gross Profit, Net Profit zinaonekana
      (sio Sales = Profit tena)
- [ ] Fungua `/reports/profit-loss` — jaribu date range filter (mfano mwezi
      uliopita) — hakikisha number zinabadilika
- [ ] Fungua `/products/create` — hakikisha dropdown ya "Unit" ipo, jaribu
      kuunda bidhaa yenye unit "kg" na stock ya `2.5`
- [ ] Fungua `/purchases/create` — rekodi stock-in ya bidhaa moja, hakikisha
      `stock_quantity` ya bidhaa hiyo imeongezeka baada ya kuhifadhi
- [ ] Jaribu ku-void purchase (`/purchases/{id}` → "Void Stock-In") — hakikisha
      stock inarudi chini
- [ ] Login kama role tofauti (Admin/Manager/Cashier) — hakikisha "Purchases"
      link inaonekana tu kwa walio na permission husika

### 6. Ukikuta kosa lolote wakati wa migrate au testing
Usijaribu "kurekebisha haraka" kwa kuhariri migration zilizoshaendeshwa kwenye
production — badala yake unda migration MPYA ya kurekebisha (Laravel
haipendekezi kuhariri migration ambayo tayari imeshaendeshwa mahali fulani).

### 7. Push GitHub
```bash
git add -A
git commit -m "feat: multi-item sales (cart+invoice), COGS/profit fix, stock-in module, units of measure"
git push origin main
```
(Au tumia branch/PR kama huo ndio mtiririko wenu wa kawaida — pendekezo langu ni
kufanya PR badala ya push moja kwa moja kwenye `main`, kwa sababu haya ni
mabadiliko makubwa ya schema.)

## Vitu vilivyobaki nje ya scope hii (kwa taarifa tu, si lazima leo)

- `app/Http/Controllers/Api/ProductController.php` ina bug ya zamani isiyohusiana:
  inatumia `quantity` badala ya `stock_quantity` halisi kwenye baadhi ya query zake.
  Haikuguswa kwenye kazi hii.
- Awamu 2 ya mpango mzima (madeni ya wateja/credit sales, returns/refunds, supplier
  ledger) bado haijaanza.
- Hakuna automated tests (PHPUnit/Pest) zilizoongezwa kwa features hizi mpya —
  inashauriwa kuongeza angalau feature tests za `SaleController::store()` (multi-item
  + stock locking) na `PurchaseController::store()` kabla ya kazi ijayo.
