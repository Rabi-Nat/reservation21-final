    //توابغ ورود و فراموشی رمز 

// توابع ورود و فراموشی رمز

// بارگذاری مودال لاگین
fetch('assets/includes/login-modal.html')
    .then(response => response.text())
    .then(data => {
        document.getElementById('modalContainer').innerHTML += data;
    });

// بارگذاری مودال فراموشی رمز
fetch('assets/includes/forgot-password-modal.html')
    .then(response => response.text())
    .then(data => {
        document.getElementById('modalContainer').innerHTML += data;
    });

// توابع باز کردن و بسته کردن مدال‌ها
function openModal(id) {
    // try direct id first, otherwise try id + 'Modal'
    let el = document.getElementById(id) || document.getElementById(id + 'Modal');
    if (el) {
        el.setAttribute('aria-hidden', 'false');
        el.style.display = 'block';
        document.body.style.overflow = 'hidden';
    }
}

function closeModal() {
    const modals = document.querySelectorAll('.modal');
    modals.forEach(modal => {
        modal.setAttribute('aria-hidden', 'true');
        modal.style.display = 'none';
    });
    document.body.style.overflow = 'auto';
}

// بررسی ورود قبل از اجرای کلیک ها
function isLoggedIn() {
    const token = localStorage.getItem('userToken');
    if (!token) return false;
    return true;
}

function checkAuth(pageUrl, pageName) {
    if (!isLoggedIn()) {
        localStorage.setItem('redirectAfterLogin', pageUrl);
        alert(`برای دسترسی به ${pageName}، لطفاً ابتدا وارد شوید`);
        openModal('login');
        return false;
    }
    // اگر کاربر لاگین کرده بود، به مسیر هدایت شود
    window.location.href = pageUrl;
    return true;
}

// کمک به بازگشت پس از لاگین
function onLoginSuccess() {
    const redirectUrl = localStorage.getItem('redirectAfterLogin') || 'index.html';
    localStorage.removeItem('redirectAfterLogin');
    window.location.href = redirectUrl;
}

// event handlers UI
document.addEventListener('DOMContentLoaded', function() {
    const loginBtn = document.querySelector('.header-login-btn');
    if (loginBtn) {
        loginBtn.addEventListener('click', function(e) {
            e.preventDefault();
            openModal('login');
        });
    }

    const closeBtn = document.querySelector('.close-modal');
    if (closeBtn) {
        closeBtn.addEventListener('click', closeModal);
    }
});

/*  قسمت مربوط به فرم ثبت نام */
document.querySelectorAll('.user-type-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        document.querySelectorAll('.user-type-btn').forEach(b => b.classList.remove('active'));
        this.classList.add('active');

        document.querySelectorAll('.registration-form').forEach(form => {
            form.classList.remove('active');
        });

        if(this.dataset.type === 'customer') {
            document.getElementById('customerForm').classList.add('active');
        } else {
            document.getElementById('managerForm').classList.add('active');
        }
    });



});

// Note: Persian datepicker/timepicker removed for booking inputs per request.



$(document).ready(function() {


    // Initialize Time Picker
    $("#bookingTime, #bookingTime2").persianDatepicker({
        format: 'HH:mm',
        autoClose: true,
        initialValue: false,
        onlyTimePicker: true,
        calendar: {
            persian: {
                locale: 'fa'
            }
        },
        toolbox: {
        calendarSwitch: {
        enabled: false
        },
        todayButton: {
        enabled: false
        },
        submitButton: {
        enabled: true
        }
        },
        dayPicker: {
            enabled: false
        },
        monthPicker: {
            enabled: false
        },
        yearPicker: {
            enabled: false
        },
        timePicker: {
            enabled: true,
            hour: {
            enabled: true,
            step: 1
            },
            minute: {
            enabled: true,
            step: 1
            },
            second: {
            enabled: false // غیرفعال کردن ثانیه
            },
            meridian: {
            enabled: false // غیرفعال کردن AM/PM
            }
        },
        onSelect: function(unix) {
            console.log('Selected time:', unix);
        }


    });


    // Close picker when clicking outside
    $(document).on('click', function(e) {
        if (!$(e.target).closest('.pdatepicker').length && 
            !$(e.target).is(' #bookingTime, #bookingTime2')) {
        $(" #bookingTime, #bookingTime2").persianDatepicker('hide');
        }
    });
    });

