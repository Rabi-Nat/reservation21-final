$(document).ready(function() {
    // Initialize Charts
    const appointmentsCtx = document.getElementById('appointmentsChart').getContext('2d');
    const appointmentsChart = new Chart(appointmentsCtx, {
        type: 'line',
        data: {
            labels: ['فروردین', 'اردیبهشت', 'خرداد', 'تیر', 'مرداد', 'شهریور'],
            datasets: [{
                label: 'تعداد نوبت‌ها',
                data: [12, 19, 15, 25, 22, 30],
                borderColor: '#9a563a',
                tension: 0.4,
                fill: false
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                }
            }
        }
    });

    const financialCtx = document.getElementById('financialChart').getContext('2d');
    const financialChart = new Chart(financialCtx, {
        type: 'bar',
        data: {
            labels: ['فروردین', 'اردیبهشت', 'خرداد', 'تیر', 'مرداد', 'شهریور'],
            datasets: [{
                label: 'درآمد',
                data: [1200000, 1900000, 1500000, 2500000, 2200000, 3000000],
                backgroundColor: '#9a563a',
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                }
            }
        }
    });

    // Notification System
    function markNotificationAsRead(notificationId) {
        $(`#notification-${notificationId}`).removeClass('unread');
        // Add AJAX call to update notification status
    }

    // Support System
    $('.support-options .btn').on('click', function() {
        const action = $(this).data('action');
        if (action === 'chat') {
            // Initialize chat system
            console.log('Opening chat...');
        } else if (action === 'ticket') {
            // Open ticket form
            console.log('Opening ticket form...');
        }
    });

    // Profile Image Upload
    $('.profile-image-container .btn').on('click', function() {
        // Trigger file input
        $('#profileImageInput').click();
    });

    $('#profileImageInput').on('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                $('.profile-image-container img').attr('src', e.target.result);
            }
            reader.readAsDataURL(file);
        }
    });

    // Settings Toggles
    $('.form-check-input').on('change', function() {
        const settingId = $(this).attr('id');
        const isEnabled = $(this).is(':checked');
        
        // Add AJAX call to update settings
        console.log(`Setting ${settingId} changed to ${isEnabled}`);
    });

    // Two Factor Authentication
    $('#twoFactorAuth').on('change', function() {
        if ($(this).is(':checked')) {
            // Show 2FA setup modal
            console.log('Opening 2FA setup...');
        }
    });

    // Password Change
    $('.btn-outline-primary').on('click', function() {
        if ($(this).find('.fa-key').length) {
            // Show password change modal
            console.log('Opening password change form...');
        }
    });

    // Persian date/time pickers removed for booking inputs per request.

    // Province-City Selection Logic
    const citiesByProvince = {
      'tehran': ['تهران', 'اسلامشهر', 'پردیس'],
      'alborz': ['کرج', 'فردیس', 'محمدشهر', 'ماهدشت', 'مشکین دشت', 'چهارباغ', 'کمال شهر', 'اشتهارد'],
      'isfahan': ['اصفهان', 'کاشان', 'نجف آباد', 'خمینی شهر', 'فولادشهر', 'گلپایگان', 'مبارکه'],
      'fars': ['شیراز', 'مرودشت', 'جهرم', 'فسا', 'فیروزآباد', 'نی ریز', 'لار'],
      'khorasan-razavi': ['مشهد', 'نیشابور', 'سبزوار', 'تربت حیدریه', 'کاشمر', 'تربت جام', 'قوچان'],
      'mazandaran': ['ساری', 'بابل', 'آمل', 'قائمشهر', 'بابلسر', 'بهشهر', 'نکا'],
      'gilan': ['رشت', 'انزلی', 'لاهیجان', 'آستارا', 'تالش', 'رودسر', 'فومن'],
      'kerman': ['کرمان', 'رفسنجان', 'سیرجان', 'بم', 'جیرفت', 'زرند', 'کهنوج'],
      'yazd': ['یزد', 'میبد', 'اردکان', 'بافق', 'تفت', 'ابرکوه', 'مهریز'],
      'kermanshah': ['کرمانشاه', 'اسلام آباد غرب', 'کنگاور', 'سنقر', 'گیلانغرب', 'هرسین', 'روانسر'],
      'markazi': ['اراک', 'ساوه', 'خمین', 'محلات', 'دلیجان', 'شازند', 'تفرش'],
      'hamadan': ['همدان', 'ملایر', 'نهاوند', 'تویسرکان', 'کبودرآهنگ', 'رزن', 'اسدآباد'],
      'golestan': ['گرگان', 'گنبد کاووس', 'علی آباد', 'مینودشت', 'آزادشهر', 'کردکوی', 'بندر ترکمن'],
      'qom': ['قم', 'پردیسان', 'قنوات', 'دستجرد', 'سلفچگان'],
      'zanjan': ['زنجان', 'ابهر', 'خدابنده', 'خرمدره', 'ماهنشان', 'ایجرود', 'طارم'],
      'ardabil': ['اردبیل', 'پارس آباد', 'خلخال', 'مشگین شهر', 'گرمی', 'نیر', 'کوثر'],
      'bushehr': ['بوشهر', 'برازجان', 'بندر گناوه', 'بندر دیر', 'بندر کنگان', 'دشتستان', 'دشتی'],
      'chaharmahal': ['شهرکرد', 'بروجن', 'فارسان', 'لردگان', 'سامان', 'بن', 'کیار'],
      'east-azerbaijan': ['تبریز', 'مراغه', 'مرند', 'میانه', 'اهر', 'بناب', 'سراب'],
      'hormozgan': ['بندر عباس', 'میناب', 'بندر لنگه', 'قشم', 'بندر خمیر', 'بستک', 'حاجی آباد'],
      'ilam': ['ایلام', 'ایوان', 'دره شهر', 'دهلران', 'مهران', 'آبدانان', 'چرداول'],
      'khuzestan': ['اهواز', 'دزفول', 'ماهشهر', 'ایذه', 'شوشتر'],
      'kohgiluyeh': ['یاسوج', 'گچساران', 'دنا', 'سی سخت', 'لنده', 'چرام', 'باشت'],
      'kurdistan': ['سنندج', 'سقز', 'مریوان', 'بانه', 'کامیاران', 'دیواندره', 'بیجار'],
      'lorestan': ['خرم آباد', 'بروجرد', 'دورود', 'الیگودرز', 'کوهدشت', 'ازنا', 'پلدختر'],
      'north-khorasan': ['بجنورد', 'شیروان', 'اسفراین', 'فاروج', 'مانه و سملقان', 'جاجرم', 'راز و جرگلان'],
      'qazvin': ['قزوین', 'تاکستان', 'البرز', 'آبیک', 'بوئین زهرا', 'آوج', 'رودبار'],
      'razavi-khorasan': ['مشهد', 'نیشابور', 'سبزوار', 'تربت حیدریه', 'کاشمر', 'تربت جام', 'قوچان'],
      'semnan': ['سمنان', 'دامغان', 'شاهرود', 'گرمسار', 'مهدی شهر', 'سرخه', 'آرادان'],
      'sistan': ['زاهدان', 'زابل', 'ایرانشهر', 'چابهار', 'خاش', 'سراوان', 'نیکشهر'],
      'south-khorasan': ['بیرجند', 'فردوس', 'قائن', 'نهبندان', 'سرایان', 'سربیشه', 'درمیان'],
      'west-azerbaijan': ['ارومیه', 'خوی', 'میاندوآب', 'بوکان', 'پیرانشهر', 'سلماس', 'اشنویه']
    };

    // استان‌ها را به صورت الفبایی مرتب کن و در سلکت قرار بده
    const provinceNames = Object.entries({
      'tehran': 'تهران',
      'alborz': 'البرز',
      'isfahan': 'اصفهان',
      'fars': 'فارس',
      'khorasan-razavi': 'خراسان رضوی',
      'mazandaran': 'مازندران',
      'gilan': 'گیلان',
      'kerman': 'کرمان',
      'yazd': 'یزد',
      'kermanshah': 'کرمانشاه',
      'markazi': 'مرکزی',
      'hamadan': 'همدان',
      'golestan': 'گلستان',
      'qom': 'قم',
      'zanjan': 'زنجان',
      'ardabil': 'اردبیل',
      'bushehr': 'بوشهر',
      'chaharmahal': 'چهارمحال و بختیاری',
      'east-azerbaijan': 'آذربایجان شرقی',
      'hormozgan': 'هرمزگان',
      'ilam': 'ایلام',
      'khuzestan': 'خوزستان',
      'kohgiluyeh': 'کهگیلویه و بویراحمد',
      'kurdistan': 'کردستان',
      'lorestan': 'لرستان',
      'north-khorasan': 'خراسان شمالی',
      'qazvin': 'قزوین',
      'razavi-khorasan': 'خراسان رضوی',
      'semnan': 'سمنان',
      'sistan': 'سیستان و بلوچستان',
      'south-khorasan': 'خراسان جنوبی',
      'west-azerbaijan': 'آذربایجان غربی'
    });
    provinceNames.sort((a, b) => a[1].localeCompare(b[1], 'fa'));
    const provinceSelect = $('#province');
    provinceSelect.empty();
    provinceSelect.append('<option value="">انتخاب استان</option>');
    provinceNames.forEach(([key, name]) => {
      provinceSelect.append(`<option value="${key}">${name}</option>`);
    });
    $('#city').prop('disabled', true);
    $('#province').on('change', function() {
      const selectedProvince = $(this).val();
      const citySelect = $('#city');
      citySelect.empty();
      if (selectedProvince) {
        citySelect.prop('disabled', false);
        citySelect.append('<option value="">انتخاب شهر</option>');
        const cities = citiesByProvince[selectedProvince] || [];
        cities.forEach(city => {
          citySelect.append(`<option value="${city}">${city}</option>`);
        });
      } else {
        citySelect.prop('disabled', true);
        citySelect.append('<option value="">ابتدا استان را انتخاب کنید</option>');
      }
    });

    // Format card number input
    $('#cardNumber').on('input', function() {
      let value = $(this).val().replace(/\D/g, '');
      if (value.length > 16) {
        value = value.substr(0, 16);
      }
      $(this).val(value);
    });

    // Format Sheba number input
    $('#shebaNumber').on('input', function() {
      let value = $(this).val().toUpperCase();
      if (!value.startsWith('IR')) {
        value = 'IR' + value.replace(/[^0-9]/g, '');
      }
      if (value.length > 26) {
        value = value.substr(0, 26);
      }
      $(this).val(value);
    });

    // Validate form submission
    $('#personalInfoForm').on('submit', function(e) {
      e.preventDefault();
      
      // Validate card number (Luhn algorithm)
      const cardNumber = $('#cardNumber').val();
      if (!isValidCardNumber(cardNumber)) {
        alert('شماره کارت نامعتبر است');
        return;
      }

      // Validate Sheba number
      const shebaNumber = $('#shebaNumber').val();
      if (!isValidShebaNumber(shebaNumber)) {
        alert('شماره شبا نامعتبر است');
        return;
      }

      // If all validations pass, submit the form
      this.submit();
    });

    // Luhn algorithm for card number validation
    function isValidCardNumber(cardNumber) {
      if (!/^\d{16}$/.test(cardNumber)) return false;
      
      let sum = 0;
      let isEven = false;
      
      for (let i = cardNumber.length - 1; i >= 0; i--) {
        let digit = parseInt(cardNumber.charAt(i));
        
        if (isEven) {
          digit *= 2;
          if (digit > 9) {
            digit -= 9;
          }
        }
        
        sum += digit;
        isEven = !isEven;
      }
      
      return sum % 10 === 0;
    }

    // Sheba number validation
    function isValidShebaNumber(shebaNumber) {
      if (!/^IR\d{24}$/.test(shebaNumber)) return false;
      
      // Convert letters to numbers for checksum calculation
      const converted = shebaNumber.slice(4) + '1827' + shebaNumber.slice(2, 4);
      const remainder = converted % 97;
      
      return remainder === 1;
    }

    // Mobile sidebar toggle
    $('.mobile-toggle').on('click', function() {
      $('.admin-sidebar').toggleClass('active');
    });

    // Handle submenu toggle
    $('.nav-link[data-bs-toggle="collapse"]').on('click', function(e) {
      e.preventDefault();
      const target = $($(this).data('bs-target'));
      const isExpanded = $(this).attr('aria-expanded') === 'true';
      
      if (isExpanded) {
        target.collapse('hide');
        $(this).attr('aria-expanded', 'false');
      } else {
        target.collapse('show');
        $(this).attr('aria-expanded', 'true');
      }
    });

    // Handle smooth scrolling to sections
    $('.nav-link[href^="#"]').on('click', function(e) {
      e.preventDefault();
      const targetId = $(this).attr('href');
      if (targetId === '#') return;

      const targetSection = $(targetId);
      if (targetSection.length) {
        // Close mobile sidebar if open
        if ($('.admin-sidebar').hasClass('active')) {
          $('.admin-sidebar').removeClass('active');
        }

        // Scroll to section with offset for header
        $('html, body').animate({
          scrollTop: targetSection.offset().top - 100
        }, 800);

        // Update active state in menu
        $('.nav-link').removeClass('active');
        $(this).addClass('active');
      }
    });

    // Update active menu item on scroll
    $(window).on('scroll', function() {
      const scrollPosition = $(window).scrollTop();

      $('.admin-card').each(function() {
        const currentSection = $(this);
        const sectionTop = currentSection.offset().top - 150;
        const sectionBottom = sectionTop + currentSection.outerHeight();

        if (scrollPosition >= sectionTop && scrollPosition < sectionBottom) {
          const targetId = '#' + currentSection.attr('id');
          $('.nav-link').removeClass('active');
          $('.nav-link[href="' + targetId + '"]').addClass('active');
        }
      });
    });

    // Initialize tooltips
    $('[data-toggle="tooltip"]').tooltip();

    // Datepicker initialization removed to disable datepicker widgets on profile pages per request.

    // Chat Toggle
    $('#chatToggleBtn').on('click', function() {
        $('#floatingChatBox').toggleClass('active');
        $(this).hide();
    });

    // Minimize Chat
    $('#minimizeChat').on('click', function() {
        $('#floatingChatBox').removeClass('active');
        $('#chatToggleBtn').show();
    });

    // Close Chat
    $('#closeChat').on('click', function() {
        $('#floatingChatBox').removeClass('active');
        $('#chatToggleBtn').show();
    });

    // Send Message
    $('.chat-footer .btn').on('click', function() {
        const messageInput = $('.chat-footer input');
        const message = messageInput.val().trim();
        
        if (message) {
            const time = new Date().toLocaleTimeString('fa-IR', { hour: '2-digit', minute: '2-digit' });
            const messageHtml = `
                <div class="message user">
                    <div class="message-content">${message}</div>
                    <small class="message-time">${time}</small>
                </div>
            `;
            $('.chat-messages').append(messageHtml);
            messageInput.val('');
            
            // Scroll to bottom
            const chatBody = $('.chat-body');
            chatBody.scrollTop(chatBody[0].scrollHeight);
        }
    });

    // Handle Enter key
    $('.chat-footer input').on('keypress', function(e) {
        if (e.which === 13) {
            $('.chat-footer .btn').click();
        }
    });
});
