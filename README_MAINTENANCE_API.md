# API طلبات الصيانة - Maintenance Requests API

## 🚀 نظرة سريعة

تم إنشاء API لتسجيل طلبات الصيانة في النظام. الـ API يستقبل `service_name` و `description` فقط، ويقوم تلقائياً بربط الطلب بالمستخدم والشركة.

## 📋 المتطلبات

- Laravel Sanctum للـ Authentication
- Bearer Token للمستخدم المسجل دخوله
- المستخدم يجب أن يكون مرتبط بشركة

## 🔗 Endpoints

### إرسال طلب صيانة جديد
```
POST /api/maintenance-requests
```

**Request Body:**
```json
{
    "service_name": "صيانة مكيف الهواء",
    "description": "المكيف لا يعمل بشكل صحيح"
}
```

**Response:**
```json
{
    "status": true,
    "message": "تم إرسال طلب الصيانة بنجاح",
    "data": {
        "id": 1,
        "service_name": "صيانة مكيف الهواء",
        "description": "المكيف لا يعمل بشكل صحيح",
        "status": "pending",
        "created_at": "2024-01-15T10:30:00.000000Z",
        "requested_by": "أحمد محمد",
        "company_name": "شركة التقنية المتقدمة"
    }
}
```

### جلب طلبات الصيانة
```
GET /api/maintenance-requests
```

**Response:**
```json
{
    "status": true,
    "data": [
        {
            "id": 1,
            "service_name": "صيانة مكيف الهواء",
            "description": "المكيف لا يعمل بشكل صحيح",
            "status": "pending",
            "created_at": "2024-01-15T10:30:00.000000Z",
            "company_name": "شركة التقنية المتقدمة"
        }
    ]
}
```

## 🛠️ الملفات المعدلة

1. **`app/Http/Controllers/Api/MaintenanceRequestController.php`**
   - تحديث دالة `store()` لتقبل `service_name` و `description` فقط
   - إضافة دالة `index()` لجلب طلبات المستخدم
   - ربط تلقائي بالشركة والمستخدم

2. **`routes/api.php`**
   - إضافة route جديد لجلب طلبات الصيانة

## 🧪 اختبار الـ API

### باستخدام cURL
```bash
# إرسال طلب صيانة جديد
curl -X POST http://your-domain.com/api/maintenance-requests \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "service_name": "صيانة المصعد",
    "description": "المصعد لا يعمل في الطابق الثالث"
  }'

# جلب طلبات الصيانة
curl -X GET http://your-domain.com/api/maintenance-requests \
  -H "Authorization: Bearer YOUR_TOKEN"
```

### باستخدام Postman
1. استورد ملف `Maintenance_API_Postman_Collection.json`
2. عدّل المتغيرات `base_url` و `token`
3. اختبر الـ endpoints

## 📝 ملاحظات مهمة

- ✅ يتم إنشاء فئة الصيانة تلقائياً إذا لم تكن موجودة
- ✅ يتم ربط الطلب تلقائياً بشركة المستخدم
- ✅ جميع الطلبات الجديدة تأخذ حالة "pending"
- ✅ يمكن للمستخدم رؤية طلباته فقط
- ✅ رسائل الاستجابة باللغة العربية

## 🔒 الأمان

- يجب أن يكون المستخدم مسجل دخوله
- يتم التحقق من صحة البيانات
- يتم ربط الطلب تلقائياً بالمستخدم والشركة

## 📚 الملفات الإضافية

- `MAINTENANCE_API_DOCUMENTATION.md` - توثيق مفصل للـ API
- `test_maintenance_api.php` - أمثلة على الاستخدام
- `Maintenance_API_Postman_Collection.json` - Postman Collection
