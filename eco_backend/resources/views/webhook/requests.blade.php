<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Webhook Requests - عرض الطلبات</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .request-card {
            transition: all 0.3s ease;
        }
        .request-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }
        .method-badge {
            font-size: 0.75rem;
            padding: 0.25rem 0.5rem;
            border-radius: 0.375rem;
            font-weight: 600;
        }
        .method-post { background-color: #10B981; color: white; }
        .method-get { background-color: #3B82F6; color: white; }
        .method-put { background-color: #F59E0B; color: white; }
        .method-delete { background-color: #EF4444; color: white; }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">
    <div class="container mx-auto px-4 py-8">
        <!-- Header -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-800 mb-2">
                        <i class="fas fa-webhook text-blue-600 mr-3"></i>
                        Webhook Requests
                    </h1>
                    <p class="text-gray-600">عرض جميع الطلبات التي تم استقبالها عبر Webhook</p>
                </div>
                <div class="flex space-x-4 space-x-reverse">
                    <button onclick="clearAllRequests()" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg transition-colors">
                        <i class="fas fa-trash mr-2"></i>
                        حذف الكل
                    </button>
                    <button onclick="location.reload()" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition-colors">
                        <i class="fas fa-sync-alt mr-2"></i>
                        تحديث
                    </button>
                </div>
            </div>

            <!-- Webhook URL Info -->
            <div class="mt-6 p-4 bg-blue-50 rounded-lg border border-blue-200">
                <h3 class="font-semibold text-blue-800 mb-2">
                    <i class="fas fa-link mr-2"></i>
                    رابط Webhook:
                </h3>
                <div class="flex items-center space-x-2 space-x-reverse">
                    <code class="bg-white px-3 py-2 rounded border text-sm font-mono text-blue-700 flex-1">
                        {{ url('/api/webhook') }}
                    </code>
                    <button onclick="copyWebhookUrl()" class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-2 rounded text-sm">
                        <i class="fas fa-copy"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Stats -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                        <i class="fas fa-list text-xl"></i>
                    </div>
                    <div class="mr-4">
                        <p class="text-sm text-gray-600">إجمالي الطلبات</p>
                        <p class="text-2xl font-bold text-gray-800">{{ count($requests) }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-green-100 text-green-600">
                        <i class="fas fa-clock text-xl"></i>
                    </div>
                    <div class="mr-4">
                        <p class="text-sm text-gray-600">آخر طلب</p>
                        <p class="text-sm font-semibold text-gray-800">
                            {{ count($requests) > 0 ? \Carbon\Carbon::parse($requests[0]['timestamp'])->diffForHumans() : 'لا يوجد' }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                        <i class="fas fa-calendar text-xl"></i>
                    </div>
                    <div class="mr-4">
                        <p class="text-sm text-gray-600">اليوم</p>
                        <p class="text-sm font-semibold text-gray-800">
                            {{ count(array_filter($requests, function($req) { return \Carbon\Carbon::parse($req['timestamp'])->isToday(); })) }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-orange-100 text-orange-600">
                        <i class="fas fa-server text-xl"></i>
                    </div>
                    <div class="mr-4">
                        <p class="text-sm text-gray-600">الحالة</p>
                        <p class="text-sm font-semibold text-green-600">نشط</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Requests List -->
        @if(count($requests) > 0)
            <div class="space-y-6">
                @foreach($requests as $index => $request)
                    <div class="request-card bg-white rounded-lg shadow-md overflow-hidden">
                        <div class="p-6">
                            <div class="flex items-center justify-between mb-4">
                                <div class="flex items-center space-x-3 space-x-reverse">
                                    <span class="method-badge method-{{ strtolower($request['method']) }}">
                                        {{ $request['method'] }}
                                    </span>
                                    <h3 class="text-lg font-semibold text-gray-800">
                                        طلب #{{ count($requests) - $index }}
                                    </h3>
                                </div>
                                <div class="text-sm text-gray-500">
                                    <i class="fas fa-clock mr-1"></i>
                                    {{ \Carbon\Carbon::parse($request['timestamp'])->format('Y-m-d H:i:s') }}
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Request Details -->
                                <div>
                                    <h4 class="font-semibold text-gray-700 mb-3">
                                        <i class="fas fa-info-circle text-blue-500 mr-2"></i>
                                        تفاصيل الطلب
                                    </h4>
                                    <div class="space-y-2 text-sm">
                                        <div class="flex justify-between">
                                            <span class="text-gray-600">IP Address:</span>
                                            <span class="font-mono text-gray-800">{{ $request['ip'] }}</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-gray-600">User Agent:</span>
                                            <span class="font-mono text-gray-800 truncate max-w-xs">{{ $request['user_agent'] }}</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-gray-600">URL:</span>
                                            <span class="font-mono text-gray-800 truncate max-w-xs">{{ $request['url'] }}</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Request Body -->
                                <div>
                                    <h4 class="font-semibold text-gray-700 mb-3">
                                        <i class="fas fa-code text-green-500 mr-2"></i>
                                        محتوى الطلب
                                    </h4>
                                    <div class="bg-gray-50 rounded p-3 max-h-32 overflow-y-auto">
                                        <pre class="text-xs text-gray-700 whitespace-pre-wrap">{{ json_encode($request['body'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                    </div>
                                </div>
                            </div>

                            <!-- Headers -->
                            <div class="mt-4">
                                <button onclick="toggleHeaders({{ $index }})" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                    <i class="fas fa-chevron-down mr-1"></i>
                                    عرض Headers
                                </button>
                                <div id="headers-{{ $index }}" class="hidden mt-3">
                                    <div class="bg-gray-50 rounded p-3">
                                        <pre class="text-xs text-gray-700 whitespace-pre-wrap">{{ json_encode($request['headers'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="bg-white rounded-lg shadow-md p-12 text-center">
                <div class="text-gray-400 mb-4">
                    <i class="fas fa-inbox text-6xl"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-600 mb-2">لا توجد طلبات</h3>
                <p class="text-gray-500">لم يتم استقبال أي طلبات عبر Webhook بعد</p>
            </div>
        @endif
    </div>

    <script>
        function toggleHeaders(index) {
            const headersDiv = document.getElementById(`headers-${index}`);
            const button = headersDiv.previousElementSibling;
            const icon = button.querySelector('i');

            if (headersDiv.classList.contains('hidden')) {
                headersDiv.classList.remove('hidden');
                icon.classList.remove('fa-chevron-down');
                icon.classList.add('fa-chevron-up');
            } else {
                headersDiv.classList.add('hidden');
                icon.classList.remove('fa-chevron-up');
                icon.classList.add('fa-chevron-down');
            }
        }

        function copyWebhookUrl() {
            const url = '{{ url("/api/webhook") }}';
            navigator.clipboard.writeText(url).then(() => {
                alert('تم نسخ رابط Webhook!');
            });
        }

        function clearAllRequests() {
            if (confirm('هل أنت متأكد من حذف جميع الطلبات؟')) {
                fetch('/api/webhook/clear', {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        location.reload();
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('حدث خطأ أثناء حذف الطلبات');
                });
            }
        }

        // Auto refresh every 30 seconds
        setInterval(() => {
            location.reload();
        }, 30000);
    </script>
</body>
</html>
