# Property Management System - Documentation

## نظرة عامة

نظام إدارة عقارات احترافي مبني على Laravel باستخدام **Architecture Modular** قابل للتوسع والصيانة.

## هيكل المشروع

```
app/PropertyManagement/
├── Models/                          # Eloquent Models
│   ├── Unit.php
│   ├── Client.php
│   ├── Broker.php
│   ├── Contract.php
│   ├── ContractRepresentative.php
│   ├── RentPayment.php
│   └── Transaction.php
│
├── Repositories/                     # Data Access Layer
│   ├── Contracts/
│   │   ├── Interfaces/
│   │   │   └── ContractRepositoryInterface.php
│   │   └── ContractRepository.php
│   ├── Tenants/
│   │   ├── Interfaces/
│   │   │   └── TenantRepositoryInterface.php
│   │   └── TenantRepository.php
│   ├── Buildings/
│   │   ├── Interfaces/
│   │   │   └── BuildingRepositoryInterface.php
│   │   └── BuildingRepository.php
│   └── Payments/
│       ├── Interfaces/
│       │   └── PaymentRepositoryInterface.php
│       └── PaymentRepository.php
│
├── Services/                         # Business Logic Layer
│   ├── Contracts/
│   │   └── ContractService.php
│   ├── Tenants/
│   │   └── TenantService.php
│   ├── Buildings/
│   │   └── BuildingService.php
│   └── Payments/
│       └── PaymentService.php
│
├── Http/
│   ├── Controllers/                 # API Controllers
│   │   ├── Controller.php
│   │   ├── ContractController.php
│   │   ├── TenantController.php
│   │   ├── UnitController.php
│   │   └── PaymentController.php
│   ├── Requests/                    # Form Requests
│   │   ├── StoreContractRequest.php
│   │   ├── StoreTenantRequest.php
│   │   └── StoreUnitRequest.php
│   └── Resources/                   # API Resources (optional)
│
└── Providers/
    └── PropertyManagementServiceProvider.php
```

## المكونات الرئيسية

### 1. Models
- **Unit**: إدارة الوحدات (مكاتب/شقق/محلات)
- **Client**: إدارة العملاء/المستأجرين
- **Contract**: إدارة العقود
- **RentPayment**: إدارة دفعات الإيجار
- **Transaction**: إدارة المعاملات المالية

### 2. Repositories
كل Repository يحتوي على:
- **Interface**: لتسهيل Mocking في الاختبارات
- **Implementation**: التعامل المباشر مع قاعدة البيانات

**المميزات:**
- فصل كامل بين Business Logic و Data Access
- سهولة الاختبار (Unit Testing)
- إمكانية تغيير مصدر البيانات بسهولة

### 3. Services
تحتوي على **Business Logic** الكامل:

- **ContractService**: 
  - إنشاء/تعديل/فسخ العقود
  - تجديد العقود
  - حساب المبالغ المستحقة
  - توليد أرقام العقود

- **TenantService**:
  - إدارة العملاء
  - كشف الحساب
  - البحث في العملاء

- **BuildingService**:
  - إدارة الوحدات
  - الوحدات المتاحة/المشغولة
  - تفاصيل الوحدة

- **PaymentService**:
  - توليد جدول الدفع
  - تسجيل السداد
  - إدارة المعاملات المالية
  - حساب الرصيد

### 4. Controllers
Controllers خفيفة الوزن:
- تستقبل الطلبات
- تستدعي Services
- ترجع الردود

### 5. Form Requests
للتحقق من صحة البيانات:
- `StoreContractRequest`
- `StoreTenantRequest`
- `StoreUnitRequest`

## الاستخدام

### 1. تسجيل Service Provider

تم تسجيل `PropertyManagementServiceProvider` في `config/app.php` تلقائياً.

### 2. Routes

الـ Routes موجودة في `routes/property_management.php` ومتصلة تلقائياً.

**Prefix:** `api/property-management`

**مثال:**
```
GET    /api/property-management/contracts
POST   /api/property-management/contracts
GET    /api/property-management/contracts/{id}
PUT    /api/property-management/contracts/{id}
POST   /api/property-management/contracts/{id}/terminate
GET    /api/property-management/contracts/{id}/due-amounts
```

### 3. Dependency Injection

جميع Services و Repositories مسجلة في Service Container:

```php
// في Controller
public function __construct(
    private ContractService $contractService
) {}

// في Service
public function __construct(
    private ContractRepositoryInterface $contractRepository,
    private PaymentService $paymentService
) {}
```

## مثال على الاستخدام

### إنشاء عقد جديد

```php
$contractService = app(ContractService::class);

$contractData = [
    'contract_type' => 'جديد',
    'building_id' => 1,
    'unit_id' => 1,
    'client_id' => 1,
    'start_date' => '2025-01-01',
    'end_date' => '2025-12-31',
    'annual_rent' => 120000,
    'rent_cycle' => 1, // monthly
    // ... باقي البيانات
];

$representatives = [
    [
        'role' => 'lessor',
        'name' => 'John Doe',
        'id_number' => '1234567890',
        // ...
    ]
];

$contract = $contractService->createContract($contractData, $representatives);
```

### تسجيل دفعة

```php
$paymentService = app(PaymentService::class);

$transaction = $paymentService->recordPayment(
    $rentPayment,
    $amount = 10000,
    $paymentDate = now(),
    $description = 'Payment received'
);
```

## الاختبار

بسبب استخدام Interfaces، يمكنك بسهولة Mock الـ Repositories:

```php
use App\PropertyManagement\Repositories\Contracts\Interfaces\ContractRepositoryInterface;

$mockRepository = Mockery::mock(ContractRepositoryInterface::class);
$mockRepository->shouldReceive('find')->andReturn($contract);

$service = new ContractService($mockRepository, $paymentService);
```

## التوسع المستقبلي

لإضافة ميزات جديدة:

1. **إضافة Model جديد** في `Models/`
2. **إنشاء Repository** مع Interface في `Repositories/`
3. **إنشاء Service** في `Services/`
4. **إنشاء Controller** في `Http/Controllers/`
5. **إضافة Routes** في `routes/property_management.php`
6. **تسجيل في ServiceProvider** إذا لزم الأمر

## المميزات

✅ **Modular Architecture**: كل مكون منفصل وقابل لإعادة الاستخدام  
✅ **Testable**: سهولة الاختبار بفضل Interfaces  
✅ **Maintainable**: كود منظم وواضح  
✅ **Scalable**: سهل إضافة ميزات جديدة  
✅ **Separation of Concerns**: فصل واضح بين الطبقات  
✅ **Dependency Injection**: استخدام Laravel Service Container  

## ملاحظات

- جميع الـ Models تستخدم `$fillable` للـ Mass Assignment
- جميع الـ Repositories تستخدم `DB::transaction` للعمليات الحساسة
- الـ Services تحتوي على Business Logic فقط
- الـ Controllers خفيفة وتستخدم Services فقط


