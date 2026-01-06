<header class="pc-header">
    <div class="header-wrapper"> <!-- [Mobile Media Block] start -->
  <div class="me-auto pc-mob-drp">
    <ul class="list-unstyled">
      <!-- ======= Menu collapse Icon ===== -->
      <li class="pc-h-item pc-sidebar-collapse">
        <a href="#" class="pc-head-link ms-0" id="sidebar-hide">
          <i class="ti ti-menu-2"></i>
        </a>
      </li>
      <li class="pc-h-item pc-sidebar-popup">
        <a href="#" class="pc-head-link ms-0" id="mobile-collapse">
          <i class="ti ti-menu-2"></i>
        </a>
      </li>
      <li class="dropdown pc-h-item d-inline-flex d-md-none">
        <a
          class="pc-head-link dropdown-toggle arrow-none m-0"
          data-bs-toggle="dropdown"
          href="#"
          role="button"
          aria-haspopup="false"
          aria-expanded="false"
        >
          <i class="ti ti-search"></i>
        </a>
        <div class="dropdown-menu pc-h-dropdown drp-search">
          <form class="px-3">
            <div class="form-group mb-0 d-flex align-items-center">
              <i data-feather="search"></i>
              <input type="search" class="form-control border-0 shadow-none" placeholder="Search here. . .">
            </div>
          </form>
        </div>
      </li>
      <li class="pc-h-item d-none d-md-inline-flex">
        <form class="header-search">
          <input type="search" class="form-control" placeholder="Search here. . .">
        </form>
      </li>
    </ul>
  </div>
  <!-- [Mobile Media Block end] -->
  <div class="ms-auto">
    <ul class="list-unstyled">
      <li class="dropdown pc-h-item">
        <a
          class="pc-head-link dropdown-toggle arrow-none me-0 position-relative"
          data-bs-toggle="dropdown"
          href="#"
          role="button"
          aria-haspopup="false"
          aria-expanded="false"
          id="notificationsDropdown"
        >
          <i class="ti ti-bell"></i>
          <span class="badge bg-danger rounded-pill position-absolute top-0 start-100 translate-middle" 
                id="notificationBadge" 
                style="font-size: 0.7rem; padding: 3px 6px; min-width: 18px; height: 18px; display: none; font-weight: bold; line-height: 1.2;">
            0
          </span>
        </a>
        <div class="dropdown-menu dropdown-notification dropdown-menu-end pc-h-dropdown" style="width: 350px;">
          <div class="dropdown-header d-flex align-items-center justify-content-between">
            <h5 class="m-0">الإشعارات</h5>
            <div>
              <a href="#" id="markAllAsRead" class="pc-head-link bg-transparent me-2" title="تحديد الكل كمقروء">
                <i class="ti ti-check text-success"></i>
              </a>
              <a href="#!" class="pc-head-link bg-transparent" data-bs-toggle="dropdown">
                <i class="ti ti-x text-danger"></i>
              </a>
            </div>
          </div>
          <div class="dropdown-divider"></div>
          <div class="dropdown-header px-0 text-wrap header-notification-scroll position-relative" style="max-height: calc(100vh - 215px)">
            <div class="list-group list-group-flush w-100" id="notificationsList">
              <div class="text-center py-4">
                <div class="spinner-border spinner-border-sm text-primary" role="status">
                  <span class="visually-hidden">جاري التحميل...</span>
                </div>
              </div>
            </div>
          </div>
          <div class="dropdown-divider"></div>
          <div class="text-center py-2">
            <a href="#!" class="link-primary" id="viewAllNotifications">عرض الكل</a>
          </div>
        </div>
      </li>
      <li class="dropdown pc-h-item">
        <a
          class="pc-head-link dropdown-toggle arrow-none me-0"
          data-bs-toggle="dropdown"
          href="#"
          role="button"
          aria-haspopup="false"
          aria-expanded="false"
        >
          <i class="ti ti-mail"></i>
        </a>
        <div class="dropdown-menu dropdown-notification dropdown-menu-end pc-h-dropdown">
          <div class="dropdown-header d-flex align-items-center justify-content-between">
            <h5 class="m-0">Message</h5>
            <a href="#!" class="pc-head-link bg-transparent"><i class="ti ti-x text-danger"></i></a>
          </div>
          <div class="dropdown-divider"></div>
          <div class="dropdown-header px-0 text-wrap header-notification-scroll position-relative" style="max-height: calc(100vh - 215px)">
            <div class="list-group list-group-flush w-100">
              <a class="list-group-item list-group-item-action">
                <div class="d-flex">
                  <div class="flex-shrink-0">
                    <img src={{ asset('dist/assets/images/user/avatar-2.jpg') }}" alt="user-image" class="user-avtar">
                  </div>
                  <div class="flex-grow-1 ms-1">
                    <span class="float-end text-muted">3:00 AM</span>
                    <p class="text-body mb-1">It's <b>Cristina danny's</b> birthday today.</p>
                    <span class="text-muted">2 min ago</span>
                  </div>
                </div>
              </a>
              <a class="list-group-item list-group-item-action">
                <div class="d-flex">
                  <div class="flex-shrink-0">
                    <img src="{{ asset('dist/assets/images/user/avatar-1.jpg') }}" alt="user-image" class="user-avtar">
                  </div>
                  <div class="flex-grow-1 ms-1">
                    <span class="float-end text-muted">6:00 PM</span>
                    <p class="text-body mb-1"><b>Aida Burg</b> commented your post.</p>
                    <span class="text-muted">5 August</span>
                  </div>
                </div>
              </a>
              <a class="list-group-item list-group-item-action">
                <div class="d-flex">
                  <div class="flex-shrink-0">
                    <img src="{{ asset('dist/assets/images/user/avatar-3.jpg') }}" alt="user-image" class="user-avtar">
                  </div>
                  <div class="flex-grow-1 ms-1">
                    <span class="float-end text-muted">2:45 PM</span>
                    <p class="text-body mb-1"><b>There was a failure to your setup.</b></p>
                    <span class="text-muted">7 hours ago</span>
                  </div>
                </div>
              </a>
              <a class="list-group-item list-group-item-action">
                <div class="d-flex">
                  <div class="flex-shrink-0">
                    <img src="{{ asset('dist/assets/images/user/avatar-4.jpg') }}" alt="user-image" class="user-avtar">
                  </div>
                  <div class="flex-grow-1 ms-1">
                    <span class="float-end text-muted">9:10 PM</span>
                    <p class="text-body mb-1"><b>Cristina Danny </b> invited to join <b> Meeting.</b></p>
                    <span class="text-muted">Daily scrum meeting time</span>
                  </div>
                </div>
              </a>
            </div>
          </div>
          <div class="dropdown-divider"></div>
          <div class="text-center py-2">
            <a href="#!" class="link-primary">View all</a>
          </div>
        </div>
      </li>
      <li class="dropdown pc-h-item header-user-profile">
        <a
          class="pc-head-link dropdown-toggle arrow-none me-0"
          data-bs-toggle="dropdown"
          href="#"
          role="button"
          aria-haspopup="false"
          data-bs-auto-close="outside"
          aria-expanded="false"
        >
          <img src="{{ asset('dist/assets/images/user/avatar-2.jpg') }}" alt="user-image" class="user-avtar">
          <span>{{ Auth::user()->name }}</span>
        </a>
        <div class="dropdown-menu dropdown-user-profile dropdown-menu-end pc-h-dropdown">
          <div class="dropdown-header">
            <div class="d-flex mb-1">
              <div class="flex-shrink-0">
                <img src="{{ asset('dist/assets/images/user/avatar-2.jpg') }}" alt="user-image" class="user-avtar wid-35">
              </div>
              <div class="flex-grow-1 ms-3">
                <h6 class="mb-1">{{ Auth::user()->name }}</h6>
                <span>UI/UX Designer</span>
              </div>
              <a href="#!" class="pc-head-link bg-transparent"><i class="ti ti-power text-danger"></i></a>
            </div>
          </div>
          <div class="dropdown-divider"></div>
          <a href="{{ route('logout') }}" class="dropdown-item text-danger"
             onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
             <i class="ti ti-power"></i> Logout
          </a>
          <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
              @csrf
          </form>
        </div>
      </li>
        </ul>
      </div>
   </div>
  </header>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const notificationBadge = document.getElementById('notificationBadge');
    const notificationsList = document.getElementById('notificationsList');
    const markAllAsReadBtn = document.getElementById('markAllAsRead');
    const notificationsDropdown = document.getElementById('notificationsDropdown');
    
    let previousUnreadCount = null; // Track previous unread count to detect new notifications (null = not initialized yet)
    let audioContextInitialized = false;
    let audioContext = null;
    let userInteracted = false; // Track if user has interacted with page
    let isFirstLoad = true; // Track if this is the first load

    // Initialize audio context immediately on page load
    function initAudioContext() {
        if (!audioContextInitialized) {
            try {
                audioContext = new (window.AudioContext || window.webkitAudioContext)();
                audioContextInitialized = true;
                
                // Try to resume if suspended (some browsers allow this)
                if (audioContext.state === 'suspended') {
                    audioContext.resume().then(() => {
                        console.log('Audio context resumed');
                    }).catch(err => {
                        console.log('Could not resume audio context:', err);
                    });
                }
            } catch (error) {
                console.log('Audio context not supported:', error);
            }
        }
        return audioContext;
    }

    // Initialize audio context on page load
    initAudioContext();

    // Mark user interaction for future audio playback
    function markUserInteraction() {
        userInteracted = true;
        if (audioContext && audioContext.state === 'suspended') {
            audioContext.resume();
        }
    }

    // Play notification sound
    function playNotificationSound() {
        const ctx = audioContext || initAudioContext();
        if (!ctx) {
            console.log('Audio context not available');
            return;
        }
        
        try {
            // Resume audio context if suspended
            if (ctx.state === 'suspended') {
                ctx.resume().then(() => {
                    playSound(ctx);
                }).catch(err => {
                    console.log('Could not resume audio context:', err);
                    // Try again after a short delay
                    setTimeout(() => {
                        if (ctx.state !== 'suspended') {
                            playSound(ctx);
                        }
                    }, 100);
                });
            } else {
                playSound(ctx);
            }
        } catch (error) {
            console.log('Audio playback error:', error);
        }
    }
    
    function playSound(ctx) {
        try {
            // Create a notification sound with multiple beeps
            const createBeep = (frequency, startTime, duration) => {
                const oscillator = ctx.createOscillator();
                const gainNode = ctx.createGain();
                
                oscillator.type = 'sine';
                oscillator.frequency.value = frequency;
                
                gainNode.gain.setValueAtTime(0, startTime);
                gainNode.gain.linearRampToValueAtTime(0.3, startTime + 0.01);
                gainNode.gain.linearRampToValueAtTime(0, startTime + duration);
                
                oscillator.connect(gainNode);
                gainNode.connect(ctx.destination);
                
                oscillator.start(startTime);
                oscillator.stop(startTime + duration);
            };
            
            const now = ctx.currentTime;
            // Three beeps: high-low-high (like WhatsApp/Messenger)
            createBeep(800, now, 0.1);
            createBeep(600, now + 0.15, 0.1);
            createBeep(800, now + 0.3, 0.1);
        } catch (error) {
            console.log('Sound playback error:', error);
        }
    }

    // Load notifications
    function loadNotifications() {
        fetch('{{ route("property-management.notifications.index") }}')
            .then(response => response.json())
            .then(data => {
                const notifications = data.notifications || [];
                const unreadCount = data.unread_count || 0;

                // Only play sound if:
                // 1. This is not the first load (to avoid playing on page load)
                // 2. previousUnreadCount is not null (has been initialized)
                // 3. unreadCount has actually increased (new notification)
                // 4. unreadCount is greater than 0 (there are notifications)
                if (!isFirstLoad && previousUnreadCount !== null && unreadCount > previousUnreadCount && unreadCount > 0) {
                    // Play sound automatically when new notification appears
                    setTimeout(() => {
                        playNotificationSound();
                    }, 300);
                }
                
                // Update previous count after checking
                previousUnreadCount = unreadCount;
                
                // Mark that first load is complete
                if (isFirstLoad) {
                    isFirstLoad = false;
                }

                // Update badge with better styling
                if (unreadCount > 0) {
                    if (notificationBadge) {
                        notificationBadge.textContent = unreadCount > 99 ? '99+' : unreadCount;
                        notificationBadge.style.display = 'flex';
                        notificationBadge.style.alignItems = 'center';
                        notificationBadge.style.justifyContent = 'center';
                        notificationBadge.style.backgroundColor = '#dc3545'; // Red color
                        notificationBadge.style.color = '#fff';
                    }
                } else {
                    if (notificationBadge) {
                        notificationBadge.style.display = 'none';
                    }
                }

                // Update notifications list
                if (notifications.length > 0) {
                    notificationsList.innerHTML = notifications.map(notification => {
                        const timeAgo = getTimeAgo(notification.created_at);
                        const iconClass = notification.type === 'payment_due' ? 'ti-alert-triangle text-warning' : 'ti-info-circle text-info';
                        const contractLink = notification.contract_id ? `{{ url('property-management/contracts') }}/${notification.contract_id}` : '#';
                        
                        return `
                            <div class="list-group-item notification-item ${notification.is_read ? '' : 'bg-light'}" 
                                 data-id="${notification.id}">
                                <div class="d-flex align-items-start">
                                    <div class="flex-shrink-0">
                                        <div class="avatar-sm bg-warning bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center">
                                            <i class="ti ${iconClass}"></i>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 ms-2">
                                        <div class="d-flex justify-content-between align-items-start mb-1">
                                            <div class="flex-grow-1">
                                                ${notification.contract_id ? `<a href="${contractLink}" class="text-decoration-none text-dark">` : ''}
                                                    <p class="text-body mb-1 fw-semibold" style="font-size: 0.875rem; margin-bottom: 0.25rem !important;">${notification.title}</p>
                                                ${notification.contract_id ? '</a>' : ''}
                                            </div>
                                            <button type="button" 
                                                    class="btn btn-sm btn-link text-danger p-0 ms-2 delete-notification-btn" 
                                                    data-id="${notification.id}"
                                                    title="حذف"
                                                    style="line-height: 1; font-size: 0.875rem;">
                                                <i class="ti ti-trash"></i>
                                            </button>
                                        </div>
                                        <span class="text-muted d-block" style="font-size: 0.75rem;">${notification.message}</span>
                                        <span class="text-muted float-end" style="font-size: 0.7rem;">${timeAgo}</span>
                                    </div>
                                </div>
                            </div>
                        `;
                    }).join('');
                } else {
                    notificationsList.innerHTML = `
                        <div class="text-center py-4">
                            <i class="ti ti-bell-off" style="font-size: 48px; color: #ccc;"></i>
                            <p class="text-muted mt-2 mb-0">لا توجد إشعارات</p>
                        </div>
                    `;
                }
            })
            .catch(error => {
                console.error('Error loading notifications:', error);
                notificationsList.innerHTML = `
                    <div class="text-center py-4">
                        <p class="text-muted">حدث خطأ في تحميل الإشعارات</p>
                    </div>
                `;
            });
    }

    // Get time ago in Arabic
    function getTimeAgo(dateString) {
        const date = new Date(dateString);
        const now = new Date();
        const diffInSeconds = Math.floor((now - date) / 1000);
        
        if (diffInSeconds < 60) return 'الآن';
        if (diffInSeconds < 3600) return `منذ ${Math.floor(diffInSeconds / 60)} دقيقة`;
        if (diffInSeconds < 86400) return `منذ ${Math.floor(diffInSeconds / 3600)} ساعة`;
        if (diffInSeconds < 604800) return `منذ ${Math.floor(diffInSeconds / 86400)} يوم`;
        return date.toLocaleDateString('ar-SA');
    }

    // Delete notification
    notificationsList.addEventListener('click', function(e) {
        const deleteBtn = e.target.closest('.delete-notification-btn');
        if (deleteBtn) {
            e.preventDefault();
            e.stopPropagation();
            
            const notificationId = deleteBtn.getAttribute('data-id');
            if (notificationId && confirm('هل أنت متأكد من حذف هذا الإشعار؟')) {
                fetch(`{{ url('property-management/notifications') }}/${notificationId}`, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        loadNotifications();
                    }
                })
                .catch(error => {
                    console.error('Error deleting notification:', error);
                });
            }
        }
    });

    // Mark all as read
    if (markAllAsReadBtn) {
        markAllAsReadBtn.addEventListener('click', function(e) {
            e.preventDefault();
            fetch('{{ route("property-management.notifications.mark-all-as-read") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(() => {
                loadNotifications();
            });
        });
    }

    // Mark user interaction to enable audio for future notifications
    ['click', 'keydown', 'touchstart', 'mousemove'].forEach(event => {
        document.addEventListener(event, markUserInteraction, { once: true });
    });
    
    // Load notifications when dropdown is opened
    if (notificationsDropdown) {
        notificationsDropdown.addEventListener('click', function() {
            markUserInteraction();
            loadNotifications();
        });
    }

    // Load notifications on page load (this will set previousUnreadCount and mark first load as complete)
    loadNotifications();

    // Refresh notifications every 30 seconds (to catch new notifications quickly)
    setInterval(loadNotifications, 30000);
});
</script>
