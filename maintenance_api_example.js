/**
 * مثال على استخدام API طلبات الصيانة في JavaScript
 */

// دالة لإرسال طلب صيانة جديد
async function submitMaintenanceRequest(serviceName, description, token) {
    try {
        const response = await fetch('/api/maintenance-requests', {
            method: 'POST',
            headers: {
                'Authorization': `Bearer ${token}`,
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                service_name: serviceName,
                description: description
            })
        });

        const data = await response.json();

        if (response.ok) {
            console.log('تم إرسال طلب الصيانة بنجاح:', data);
            return data;
        } else {
            console.error('خطأ في إرسال طلب الصيانة:', data);
            throw new Error(data.message || 'خطأ في إرسال طلب الصيانة');
        }
    } catch (error) {
        console.error('خطأ في الاتصال:', error);
        throw error;
    }
}

// دالة لجلب طلبات الصيانة
async function getMaintenanceRequests(token) {
    try {
        const response = await fetch('/api/maintenance-requests', {
            headers: {
                'Authorization': `Bearer ${token}`
            }
        });

        const data = await response.json();

        if (response.ok) {
            console.log('تم جلب طلبات الصيانة:', data);
            return data;
        } else {
            console.error('خطأ في جلب طلبات الصيانة:', data);
            throw new Error(data.message || 'خطأ في جلب طلبات الصيانة');
        }
    } catch (error) {
        console.error('خطأ في الاتصال:', error);
        throw error;
    }
}

// مثال على الاستخدام
async function example() {
    const token = 'YOUR_BEARER_TOKEN_HERE';

    try {
        // إرسال طلب صيانة جديد
        const newRequest = await submitMaintenanceRequest(
            'صيانة مكيف الهواء',
            'المكيف لا يعمل بشكل صحيح ويحتاج إلى صيانة عاجلة',
            token
        );

        // جلب جميع طلبات الصيانة
        const requests = await getMaintenanceRequests(token);

        // عرض النتائج
        console.log('طلب جديد:', newRequest.data);
        console.log('جميع الطلبات:', requests.data);

    } catch (error) {
        console.error('خطأ:', error.message);
    }
}

// استخدام مع jQuery (إذا كنت تستخدم jQuery)
function submitMaintenanceRequestWithJQuery(serviceName, description, token) {
    $.ajax({
        url: '/api/maintenance-requests',
        method: 'POST',
        headers: {
            'Authorization': `Bearer ${token}`,
            'Content-Type': 'application/json'
        },
        data: JSON.stringify({
            service_name: serviceName,
            description: description
        }),
        success: function(data) {
            console.log('تم إرسال طلب الصيانة بنجاح:', data);
        },
        error: function(xhr, status, error) {
            console.error('خطأ في إرسال طلب الصيانة:', error);
        }
    });
}

// تصدير الدوال للاستخدام في ملفات أخرى
if (typeof module !== 'undefined' && module.exports) {
    module.exports = {
        submitMaintenanceRequest,
        getMaintenanceRequests,
        submitMaintenanceRequestWithJQuery
    };
}
