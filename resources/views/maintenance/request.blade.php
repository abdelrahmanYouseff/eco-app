<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>طلب صيانة - ECO Property</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .form-container {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
    </style>
</head>
<body class="bg-gray-100 min-h-screen">
    <div class="form-container min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8">
            <div class="bg-white rounded-lg shadow-xl p-8">
                <!-- Header -->
                <div class="text-center mb-8">
                    <div class="mx-auto h-12 w-12 bg-blue-600 rounded-full flex items-center justify-center mb-4">
                        <i class="fas fa-tools text-white text-xl"></i>
                    </div>
                    <h2 class="text-3xl font-bold text-gray-900 mb-2">طلب صيانة</h2>
                    <p class="text-gray-600">أرسل طلب صيانة جديد</p>
                </div>

                <!-- Login Form -->
                <form id="maintenanceForm" class="space-y-6">
                    <!-- Service Name -->
                    <div>
                        <label for="service_name" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-cog text-blue-600 ml-2"></i>
                            نوع الخدمة
                        </label>
                        <select id="service_name" name="service_name" required 
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            <option value="">اختر نوع الخدمة</option>
                            <option value="صيانة الكهرباء">صيانة الكهرباء</option>
                            <option value="صيانة التكييف">صيانة التكييف</option>
                            <option value="صيانة السباكة">صيانة السباكة</option>
                            <option value="صيانة المصاعد">صيانة المصاعد</option>
                            <option value="صيانة الأجهزة الإلكترونية">صيانة الأجهزة الإلكترونية</option>
                            <option value="صيانة الإنشاءات">صيانة الإنشاءات</option>
                        </select>
                    </div>

                    <!-- Description -->
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-file-alt text-blue-600 ml-2"></i>
                            وصف المشكلة
                        </label>
                        <textarea id="description" name="description" rows="4" required
                                  placeholder="اشرح المشكلة بالتفصيل..."
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors resize-none"></textarea>
                    </div>

                    <!-- Submit Button -->
                    <div>
                        <button type="submit" 
                                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-lg transition-colors duration-200 flex items-center justify-center">
                            <i class="fas fa-paper-plane ml-2"></i>
                            إرسال الطلب
                        </button>
                    </div>
                </form>

                <!-- Response Message -->
                <div id="responseMessage" class="mt-6 hidden">
                    <div id="successMessage" class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg hidden">
                        <i class="fas fa-check-circle ml-2"></i>
                        <span id="successText"></span>
                    </div>
                    <div id="errorMessage" class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg hidden">
                        <i class="fas fa-exclamation-circle ml-2"></i>
                        <span id="errorText"></span>
                    </div>
                </div>

                <!-- Loading Spinner -->
                <div id="loadingSpinner" class="mt-6 hidden text-center">
                    <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
                    <p class="text-gray-600 mt-2">جاري إرسال الطلب...</p>
                </div>
            </div>

            <!-- Footer -->
            <div class="text-center text-white">
                <p class="text-sm opacity-75">© 2025 ECO Property. جميع الحقوق محفوظة.</p>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('maintenanceForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const formData = {
                service_name: document.getElementById('service_name').value,
                description: document.getElementById('description').value
            };

            // Show loading
            document.getElementById('loadingSpinner').classList.remove('hidden');
            document.getElementById('responseMessage').classList.add('hidden');

            try {
                const response = await fetch('/api/maintenance-requests', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'Authorization': 'Bearer ' + getToken() // You'll need to implement this
                    },
                    body: JSON.stringify(formData)
                });

                const data = await response.json();

                // Hide loading
                document.getElementById('loadingSpinner').classList.add('hidden');
                document.getElementById('responseMessage').classList.remove('hidden');

                if (response.ok) {
                    // Success
                    document.getElementById('successMessage').classList.remove('hidden');
                    document.getElementById('errorMessage').classList.add('hidden');
                    document.getElementById('successText').textContent = data.message || 'تم إرسال الطلب بنجاح!';
                    
                    // Reset form
                    document.getElementById('maintenanceForm').reset();
                } else {
                    // Error
                    document.getElementById('errorMessage').classList.remove('hidden');
                    document.getElementById('successMessage').classList.add('hidden');
                    document.getElementById('errorText').textContent = data.message || 'حدث خطأ أثناء إرسال الطلب';
                }
            } catch (error) {
                // Hide loading
                document.getElementById('loadingSpinner').classList.add('hidden');
                document.getElementById('responseMessage').classList.remove('hidden');
                
                // Show error
                document.getElementById('errorMessage').classList.remove('hidden');
                document.getElementById('successMessage').classList.add('hidden');
                document.getElementById('errorText').textContent = 'حدث خطأ في الاتصال بالخادم';
            }
        });

        // Function to get token (you'll need to implement this based on your auth system)
        function getToken() {
            // For now, return empty string - you'll need to implement proper token management
            return '';
        }
    </script>
</body>
</html>
