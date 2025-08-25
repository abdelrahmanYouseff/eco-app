# API طلبات الصيانة - Maintenance Requests API

## الوصف
API لتسجيل وإدارة طلبات الصيانة في النظام. يتيح للمستخدمين إرسال طلبات صيانة جديدة وعرض طلباتهم السابقة.

## Endpoints

### 1. إرسال طلب صيانة جديد
**POST** `/api/maintenance-requests`

#### Headers المطلوبة
```
Authorization: Bearer {token}
Content-Type: application/json
```

#### Request Body
```json
{
    "service_name": "صيانة مكيف الهواء",
    "description": "المكيف لا يعمل بشكل صحيح ويحتاج إلى صيانة عاجلة"
}
```

#### Response (201 Created)
```json
{
    "status": true,
    "message": "تم إرسال طلب الصيانة بنجاح",
    "data": {
        "id": 1,
        "service_name": "صيانة مكيف الهواء",
        "description": "المكيف لا يعمل بشكل صحيح ويحتاج إلى صيانة عاجلة",
        "status": "pending",
        "created_at": "2024-01-15T10:30:00.000000Z",
        "requested_by": "أحمد محمد",
        "company_name": "شركة التقنية المتقدمة"
    }
}
```

### 2. جلب طلبات الصيانة للمستخدم
**GET** `/api/maintenance-requests`

#### Headers المطلوبة
```
Authorization: Bearer {token}
```

#### Response (200 OK)
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
        },
        {
            "id": 2,
            "service_name": "صيانة الكهرباء",
            "description": "مشكلة في الإضاءة",
            "status": "in_progress",
            "created_at": "2024-01-14T09:15:00.000000Z",
            "company_name": "شركة التقنية المتقدمة"
        }
    ]
}
```

## حالات الطلب (Status)
- `pending` - في الانتظار
- `in_progress` - قيد التنفيذ
- `completed` - مكتمل
- `rejected` - مرفوض

## الميزات
- ✅ إنشاء طلب صيانة جديد
- ✅ عرض طلبات الصيانة للمستخدم
- ✅ إنشاء فئات الصيانة تلقائياً
- ✅ ربط الطلب بالشركة والمستخدم تلقائياً
- ✅ رسائل استجابة باللغة العربية
- ✅ تحقق من صحة البيانات

## أمثلة على الاستخدام

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

### باستخدام JavaScript/Fetch
```javascript
// إرسال طلب صيانة جديد
const response = await fetch('/api/maintenance-requests', {
    method: 'POST',
    headers: {
        'Authorization': 'Bearer ' + token,
        'Content-Type': 'application/json'
    },
    body: JSON.stringify({
        service_name: 'صيانة مكيف الهواء',
        description: 'المكيف لا يعمل بشكل صحيح'
    })
});

const data = await response.json();
console.log(data);

// جلب طلبات الصيانة
const requestsResponse = await fetch('/api/maintenance-requests', {
    headers: {
        'Authorization': 'Bearer ' + token
    }
});

const requestsData = await requestsResponse.json();
console.log(requestsData);
```

## ملاحظات مهمة
1. يجب أن يكون المستخدم مسجل دخوله (Bearer Token)
2. يتم ربط الطلب تلقائياً بشركة المستخدم
3. يتم إنشاء فئة الصيانة تلقائياً إذا لم تكن موجودة
4. جميع الطلبات الجديدة تأخذ حالة "pending" تلقائياً
5. يمكن للمستخدم رؤية طلباته فقط

## الأخطاء المحتملة

### 400 Bad Request
```json
{
    "message": "The given data was invalid.",
    "errors": {
        "service_name": ["The service name field is required."],
        "description": ["The description field is required."]
    }
}
```

### 401 Unauthorized
```json
{
    "message": "Unauthenticated."
}
```

### 422 Unprocessable Entity
```json
{
    "message": "The given data was invalid.",
    "errors": {
        "service_name": ["The service name may not be greater than 255 characters."]
    }
}
```
